<?php

namespace App\Http\Controllers\Api;

use App\Helpers\WatermarkService;
use App\Http\Controllers\Controller;
use App\Models\BHPNU;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

/**
 * Menerima laporan hasil proses watermark dari WM Service.
 *
 * Endpoint: POST /api/webhook/wm/akta
 * Autentikasi: header Authorization: Bearer <token pada tabel access_token (name = wm-service)>
 *
 * Contoh request body:
 * {
 *   "bhpnu_id": 181,
 *   "status": "success",
 *   "akta_path": "/var/www/sipinter/storage/app/bhpnu-doc/akta/akta-2184215-SMK-NU.pdf",
 *   "message": "Watermark berhasil"
 * }
 */
class WatermarkWebhookController extends Controller
{
    /**
     * Status yang diterima dari WM Service.
     */
    private const ACCEPTED_STATUS = ['success', 'failed'];

    public function handleAkta(Request $request)
    {
        /**
         * Validasi dilakukan manual (bukan $request->validate()) supaya
         * kegagalan validasi selalu dibalas JSON, bukan redirect - penting
         * karena endpoint ini dipanggil service lain, bukan browser.
         */
        $validator = Validator::make($request->all(), [
            'bhpnu_id' => 'required|integer',
            'status' => 'required|string|in:success,failed',
            'akta_path' => 'nullable|string',
            'message' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'success' => false,
                'message' => 'Data yang dikirim tidak valid',
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        $status = strtolower($request->input('status'));
        $bhpnu = BHPNU::find($request->input('bhpnu_id'));

        if (!$bhpnu) {
            return Response::json([
                'success' => false,
                'message' => 'Data permohonan BHPNU tidak ditemukan',
            ], 404);
        }

        /**
         * Path keluaran bisa dikirim WM Service dalam bentuk path absolut di
         * server ini (mis. /var/www/sipinter/storage/app/bhpnu-doc/akta/xxx.pdf)
         * maupun relatif terhadap folder storage. Keduanya dinormalkan menjadi
         * direktori + nama file relatif terhadap disk bhpnu-doc.
         */
        $reportedPath = $this->normalizeAktaPath($request->input('akta_path'), $bhpnu);
        $filename = $this->resolveFilename($bhpnu, $reportedPath);

        if ($status === 'success') {
            /**
             * Verifikasi tetap dilakukan: WM Service bisa saja melaporkan sukses
             * sebelum file benar-benar tertulis atau menulis ke path yang berbeda,
             * sehingga status di halaman BHPNU tidak pernah berbohong.
             */
            $documentExists = WatermarkService::aktaDocumentExistsByName($filename);

            if (!$documentExists) {
                $bhpnu->update([
                    'akta_processed_at' => null,
                ]);

                $this->logWebhook($bhpnu, 'success tanpa file', $request->all());

                return Response::json([
                    'success' => false,
                    'message' => 'Laporan sukses diterima tetapi file akta belum ditemukan pada folder storage',
                    'data' => [
                        'bhpnu_id' => $bhpnu->id_bhpnu,
                        'akta_status' => $bhpnu->akta_status,
                        'akta_file' => $bhpnu->akta_file,
                    ],
                ], 422);
            }

            $bhpnu->update([
                'akta_file' => $filename,
                'akta_status' => 'success',
                'akta_note' => $request->input('message') ?? 'Watermark akta berhasil',
                'akta_processed_at' => now(),
            ]);
        } else {
            $bhpnu->update([
                'akta_status' => 'failed',
                'akta_note' => $request->input('message') ?? 'Watermark akta gagal diproses WM Service',
                /**
                 * Dikosongkan supaya akta_processed_at selalu berarti "waktu
                 * watermark TERAKHIR BERHASIL", bukan waktu percobaan terakhir.
                 * Tanpa ini, hasil gagal masih menyisakan timestamp sukses lama.
                 */
                'akta_processed_at' => null,
            ]);
        }

        $this->logWebhook($bhpnu, $status, $request->all());

        return Response::json([
            'success' => true,
            'message' => $status === 'success'
                ? 'Status akta berhasil diperbarui menjadi success'
                : 'Status akta berhasil diperbarui menjadi failed',
            'data' => [
                'bhpnu_id' => $bhpnu->id_bhpnu,
                'akta_status' => $bhpnu->akta_status,
                'akta_file' => $bhpnu->akta_file,
                'akta_processed_at' => $bhpnu->akta_processed_at?->toDateTimeString(),
            ],
        ]);
    }

    /**
     * Normalisasi path akta yang dilaporkan WM Service.
     * Mengembalikan path relatif terhadap storage/app atau null bila kosong.
     */
    private function normalizeAktaPath(?string $reportedPath, BHPNU $bhpnu): ?string
    {
        if (!$reportedPath) {
            return null;
        }

        $path = str_replace('\\', '/', $reportedPath);

        /**
         * Path absolut dipotong pada penanda "storage/app" supaya aman walau
         * lokasi root aplikasi berbeda antara server WM dengan server ini.
         */
        $marker = 'storage/app';
        $markerPosition = strpos($path, $marker);

        if ($markerPosition !== false) {
            $relative = trim(substr($path, $markerPosition + strlen($marker)), '/');
        } elseif (str_starts_with($path, 'bhpnu-doc/')) {
            $relative = $path;
        } else {
            return $path;
        }

        return $relative === '' ? null : $relative;
    }

    /**
     * Tentukan nama file akta. Path dari WM Service dipakai bila tersedia,
     * jika tidak gunakan nama file yang sudah disimpan saat request dikirim.
     */
    private function resolveFilename(BHPNU $bhpnu, ?string $reportedPath): ?string
    {
        if ($reportedPath) {
            return basename($reportedPath);
        }

        return $bhpnu->akta_file;
    }

    private function logWebhook(BHPNU $bhpnu, string $status, array $payload): void
    {
        Log::info('[WATERMARK][webhook] bhpnu ' . $bhpnu->id_bhpnu . ' status: ' . $status, $payload);
    }
}
