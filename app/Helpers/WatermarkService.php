<?php

namespace App\Helpers;

use App\Http\Controllers\Settings;
use App\Models\BHPNU;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Klien untuk Watermark (WM) Service.
 *
 * WM Service membaca dan menulis file langsung pada folder storage aplikasi
 * ini (storage/app), sehingga path yang dikirim pada request body adalah path
 * absolut di server ini - bukan path terpisah milik WM Service.
 *
 * - akta_path   : file akta asli dari halaman Setting, di storage/app/templates
 * - piagam_path : piagam satpen hasil generate, di storage/app/generated/piagam
 * - sk_path     : SK satpen hasil generate, di storage/app/generated/sk
 * - output_path : hasil watermark, ditulis WM Service ke storage/app/bhpnu-doc/akta
 */
class WatermarkService
{
    /**
     * Direktori hasil watermark relatif terhadap disk bhpnu-doc.
     */
    public const AKTA_DIRECTORY = 'akta';

    /**
     * Kirim permintaan watermark akta untuk satu permohonan BHPNU.
     *
     * @return array{success: bool, message: string}
     */
    public static function requestAkta(BHPNU $bhpnu): array
    {
        $satpen = $bhpnu->satpen;
        if (!$satpen) {
            return self::failed('Data satpen permohonan tidak ditemukan.');
        }

        /**
         * Kegagalan sebelum request dikirim tetap dicatat pada baris bhpnu.
         * Tanpa ini kolom Akta tetap kosong sehingga halaman BHPNU menampilkan
         * "Belum diproses" padahal approve sudah ditekan - dan admin tidak
         * punya petunjuk apa pun tentang penyebabnya.
         *
         * Endpoint diambil utuh dari config (termasuk path), jadi path tidak
         * lagi disusun di dalam kode.
         */
        $endpoint = (string) config('app.wm_service_url');
        if ($endpoint === '') {
            $note = 'URL Watermark Service belum dikonfigurasi (WM_SERVICE_URL)';

            self::recordFailure($bhpnu, $note);

            return self::failed('Gagal memproses watermark akta. ' . $note);
        }

        $aktaPath = self::aktaTemplatePath();
        if (!$aktaPath) {
            $note = 'File akta belum diatur. Silakan unggah pada halaman Pengaturan';

            self::recordFailure($bhpnu, $note);

            return self::failed('Gagal memproses watermark akta. ' . $note);
        }

        /**
         * Nama file hasil watermark sekaligus path output yang dikirim ke WM Service.
         * WM Service menulis langsung ke folder storage aplikasi, sehingga file
         * tersebut otomatis tersedia untuk admin maupun operator.
         */
        $outputFilename = self::outputFilename($bhpnu, $satpen->no_registrasi, $satpen->nm_satpen);

        $payload = [
            'bhpnu_id' => $bhpnu->id_bhpnu,
            'akta_path' => $aktaPath,
            'piagam_path' => self::generatedDocumentPath($satpen, 'piagam'),
            'sk_path' => self::generatedDocumentPath($satpen, 'sk'),
            'output_path' => self::aktaStoragePath($outputFilename),
            'data' => [
                'nama_satpen' => $satpen->nm_satpen,
                'no_registrasi' => $satpen->no_registrasi,
                'npsn' => $satpen->npsn,
            ],
        ];

        /**
         * Body request dicatat apa adanya (bukan sebagai context array) supaya
         * isi log sama persis dengan JSON yang dikirim ke WM Service. Kalau
         * dibungkus array, log menampilkan {"bhpnu_id":..,"payload":{..}} yang
         * menyesatkan seolah request-nya ikut terbungkus.
         *
         * Dicatat sebelum request dikirim agar isinya tetap ada walau koneksi
         * gagal (SSL/DNS/timeout) dan tidak ada respons sama sekali.
         */
        Log::info('[WATERMARK][requestAkta] POST ' . $endpoint . ' body: ' . json_encode($payload, JSON_UNESCAPED_SLASHES));

        try {
            $response = Http::timeout((int) config('app.wm_service_timeout'))
                ->acceptJson()
                ->post($endpoint, $payload);

            if ($response->successful()) {
                Log::info('[WATERMARK][requestAkta] respons WM Service', [
                    'bhpnu_id' => $bhpnu->id_bhpnu,
                    'http_status' => $response->status(),
                    'body' => $response->json() ?? $response->body(),
                ]);

                /**
                 * akta_file langsung diisi supaya halaman BHPNU tahu file mana
                 * yang ditunggu, sedangkan status tetap 'processing' sampai
                 * webhook dari WM Service melaporkan hasilnya.
                 */
                $bhpnu->update([
                    'akta_file' => $outputFilename,
                    'akta_status' => 'processing',
                    'akta_note' => 'Permintaan watermark akta terkirim ke WM Service',
                    'akta_requested_at' => now(),
                    'akta_processed_at' => null,
                ]);

                return [
                    'success' => true,
                    'message' => 'Dokumen berhasil dikirim dan akta sedang diproses oleh WM Service',
                ];
            }

            $error = $response->json('error')
                ?? $response->json('message')
                ?? 'HTTP ' . $response->status();

            Log::error('[WATERMARK][requestAkta] respons gagal dari WM Service', [
                'bhpnu_id' => $bhpnu->id_bhpnu,
                'http_status' => $response->status(),
                'body' => $response->json() ?? $response->body(),
            ]);

            self::recordFailure($bhpnu, $error);

            return self::failed('Gagal memproses watermark akta. ' . $error);
        } catch (\Exception $e) {
            self::recordFailure($bhpnu, $e->getMessage());

            return self::failed('Gagal menghubungi Watermark Service. ' . $e->getMessage());
        }
    }

    /**
     * Benar bila hasil watermark sudah tersedia di folder storage aplikasi.
     */
    public static function aktaDocumentExists(BHPNU $bhpnu): bool
    {
        return self::aktaDocumentExistsByName($bhpnu->akta_file);
    }

    /**
     * Versi yang menerima nama file langsung, dipakai webhook untuk memverifikasi
     * file yang dilaporkan WM Service sebelum status diubah menjadi success.
     */
    public static function aktaDocumentExistsByName(?string $filename): bool
    {
        if (!$filename) {
            return false;
        }

        return Storage::disk('bhpnu-doc')->exists(self::AKTA_DIRECTORY . '/' . $filename);
    }

    /**
     * Status akta yang ditampilkan pada halaman BHPNU.
     *
     * Mengembalikan:
     * - 'success'    : file hasil watermark ada di storage -> tombol View muncul
     * - 'failed'     : WM Service melaporkan gagal
     * - 'processing' : permintaan sudah dikirim, masih dalam batas waktu tunggu
     * - 'missing'    : permintaan sudah lewat batas waktu tunggu tapi file tetap
     *                  tidak ada (WM Service gagal menulis / file terhapus)
     * - null         : belum pernah diminta ke WM Service
     *
     * Kolom akta_status saja tidak cukup: permintaan yang sudah dikirim tidak
     * langsung berubah status (menunggu webhook). Pembedanya adalah waktu
     * permintaan (akta_requested_at) - dalam batas toleransi berarti masih
     * diproses, lewat batas berarti file benar-benar tidak ada.
     */
    public static function aktaDisplayStatus(BHPNU $bhpnu): ?string
    {
        if (self::aktaDocumentExists($bhpnu)) {
            return 'success';
        }

        if ($bhpnu->akta_status === 'failed') {
            return 'failed';
        }

        if ($bhpnu->akta_file) {
            return self::stillWithinProcessingWindow($bhpnu) ? 'processing' : 'missing';
        }

        return $bhpnu->akta_status;
    }

    /**
     * Berapa menit sebuah permintaan dianggap masih diproses sebelum file yang
     * tidak kunjung muncul dinyatakan hilang. Dapat diatur lewat
     * WM_PROCESSING_WINDOW_MINUTES.
     */
    private static function stillWithinProcessingWindow(BHPNU $bhpnu): bool
    {
        if (!$bhpnu->akta_requested_at) {
            /**
             * Baris lama (permintaan dikirim sebelum kolom akta_requested_at
             * ada) tidak punya penanda waktu, jadi diperlakukan sebagai sudah
             * lewat batas supaya admin diberi tahu bahwa filenya tidak ada.
             */
            return false;
        }

        return $bhpnu->akta_requested_at->gt(now()->subMinutes((int) config('app.wm_processing_window')));
    }

    /**
     * Hapus file akta lokal + bersihkan kolom akta pada permohonan (tarik dokumen).
     * Dipakai saat akses operator dicabut atau ketika akta perlu dibuat ulang.
     */
    public static function forgetAktaDocument(BHPNU $bhpnu): void
    {
        if ($bhpnu->akta_file) {
            Storage::disk('bhpnu-doc')->delete(self::AKTA_DIRECTORY . '/' . $bhpnu->akta_file);
        }

        $bhpnu->update([
            'akta_file' => null,
            'akta_status' => null,
            'akta_processed_at' => null,
            'akta_note' => null,
        ]);
    }

    /**
     * Nama file hasil watermark: akta-<no registrasi>-<nama satpen>.pdf
     * Karakter yang tidak aman untuk nama file diganti tanda hubung.
     */
    public static function outputFilename(BHPNU $bhpnu, ?string $noRegistrasi, ?string $namaSatpen): string
    {
        $safeName = str_replace(['/', '\\', '#', ' '], '-', (string) $namaSatpen);

        return 'akta-' . ($noRegistrasi ?: $bhpnu->id_bhpnu) . '-' . $safeName . '.pdf';
    }

    /**
     * Path absolut file akta di storage aplikasi ini.
     */
    public static function aktaStoragePath(string $filename): string
    {
        return storage_path('app/bhpnu-doc/' . self::AKTA_DIRECTORY . '/' . $filename);
    }

    /**
     * Path file akta asli yang diunggah melalui halaman Pengaturan.
     * Mengembalikan null bila belum diatur atau file tidak ditemukan.
     */
    private static function aktaTemplatePath(): ?string
    {
        $aktaFilename = (string) Settings::get('akta_template');
        if ($aktaFilename === '') {
            return null;
        }

        $path = storage_path('app/templates/' . $aktaFilename);

        return file_exists($path) ? $path : null;
    }

    /**
     * Path dokumen piagam/sk hasil generate satpen di storage aplikasi ini.
     * Mengembalikan string kosong bila dokumen belum pernah digenerate.
     */
    private static function generatedDocumentPath($satpen, string $typefile): string
    {
        $file = $satpen->file->firstWhere('typefile', $typefile);
        if (!$file) {
            return '';
        }

        $path = storage_path('app/generated/' . $typefile . '/' . $file->nm_file);

        return file_exists($path) ? $path : '';
    }

    /**
     * @return array{success: bool, message: string}
     */
    private static function failed(string $message): array
    {
        return [
            'success' => false,
            'message' => $message,
        ];
    }

    /**
     * Catat kegagalan permintaan watermark pada baris bhpnu.
     *
     * akta_requested_at sengaja diisi supaya baris ini punya penanda waktu:
     * tanpa itu, baris lama yang statusnya failed bisa disalahartikan sebagai
     * "belum pernah diproses" pada logika tampilan.
     */
    private static function recordFailure(BHPNU $bhpnu, string $error): void
    {
        $bhpnu->update([
            'akta_status' => 'failed',
            'akta_note' => 'Gagal watermark akta: ' . $error,
            'akta_requested_at' => $bhpnu->akta_requested_at ?? now(),
        ]);

        Log::error('[WATERMARK][requestAkta] bhpnu ' . $bhpnu->id_bhpnu . ' error: ' . $error);
    }
}
