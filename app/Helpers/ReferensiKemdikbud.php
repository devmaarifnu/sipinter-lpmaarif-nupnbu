<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class ReferensiKemdikbud {

    private bool $status;
    private $result;

    private function set(bool $status, $result) {
        $this->status = $status;
        $this->result = $result;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getResult() {
        return $this->result;
    }

    public function clone($npsn)
    {
        try {
            $url = rtrim(config('services.referensi_kemdikbud.url'), '/') . '/' . $npsn;

            $response = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36',
                ])
                ->timeout(30)
                ->get($url);

            if ($response->successful()) {
                $dataList = $this->parse($response->body());

                if (count($dataList) > 0) return $this->set(true, $dataList);

                Log::warning("ReferensiKemdikbud: NPSN {$npsn} tidak ditemukan (dataList kosong)");
                return $this->set(false, "NPSN {$npsn} tidak ditemukan di Referensi Kemdikbud");
            }

            if ($response->status() === 404) {
                Log::warning("ReferensiKemdikbud: NPSN {$npsn} tidak ditemukan (HTTP 404)");
                return $this->set(false, "NPSN {$npsn} tidak ditemukan di Referensi Kemdikbud");
            }

            Log::error("ReferensiKemdikbud: HTTP {$response->status()} untuk NPSN {$npsn}");
            return $this->set(false, "Gagal mengakses halaman Referensi Kemdikbud untuk NPSN {$npsn}: HTTP {$response->status()}");
        } catch (\Exception $err) {
            Log::error("ReferensiKemdikbud: Exception NPSN {$npsn} - {$err->getMessage()}", ['exception' => $err]);
            return $this->set(false, "Koneksi ke Referensi Kemdikbud gagal saat mengecek NPSN {$npsn}");

        }
    }

    /**
     * Halaman detail baru menaruh seluruh data (identitas, dokumen, sarana,
     * kontak, peta) pada tab yang sudah dirender sekaligus, dengan pola:
     * <div class="detail-row">
     *     <div class="detail-label">Nama</div>
     *     <div class="detail-value">SMKN 1 SURABAYA</div>
     * </div>
     *
     * Key diambil dari label (lowercase, selain huruf/angka menjadi underscore),
     * sehingga label baru dari sumber tetap ikut terbaca.
     */
    private function parse(string $html): array
    {
        $crawler = new Crawler($html);
        $dataList = [];

        $crawler->filter('.detail-row')->each(function (Crawler $row) use (&$dataList) {
            $label = $row->filter('.detail-label');
            $value = $row->filter('.detail-value');

            if ($label->count() === 0 || $value->count() === 0) return;

            $arrKey = $this->key($label->first()->text());
            if (!$arrKey) return;

            $link = $value->first()->filter('a[href]');
            if ($arrKey === 'file_sk_operasional' && $link->count() > 0) {
                // Tampilan hanya "↗ Lihat SK Operasional", URL aslinya ada di href
                $dataList[$arrKey] = trim($link->first()->attr('href'));
                return;
            }

            $dataList[$arrKey] = $this->clean($value->first()->text());
        });

        return $this->withAliases($dataList);
    }

    private function key(string $label): ?string
    {
        $arrKey = preg_replace('/[^a-z0-9]+/', '_', strtolower(trim($label)));
        $arrKey = trim($arrKey, '_');

        return $arrKey === '' ? null : $arrKey;
    }

    private function clean(string $value): string
    {
        // DomCrawler ikut membawa penanda "↗" dan karakter spasi tak terlihat pada teks link
        $value = str_replace(["\u{2197}", "\u{00A0}", "\u{200B}"], ' ', $value);

        return trim(preg_replace('/\s+/u', ' ', $value));
    }

    /**
     * Nama key milik DapoMaarifNU tetap disediakan agar pemakai lama
     * (SyncController, form registrasi, halaman satpen) tidak berubah.
     */
    private function withAliases(array $dataList): array
    {
        $aliases = [
            'propinsiluar_negeri_ln' => 'provinsi',
            'kabkotanegara_ln' => 'kabupaten_kota',
            'kecamatankota_ln' => 'kecamatan',
            'desakelurahan' => 'desa_kelurahan',
        ];

        foreach ($aliases as $alias => $source) {
            if (!isset($dataList[$source]) || $dataList[$source] === '') continue;

            $dataList[$alias] = $dataList[$source];
        }

        // Nilai kecamatan sumber berawalan "Kec." dan langsung dipakai sebagai isian form
        if (isset($dataList['kecamatankota_ln'])) {
            $dataList['kecamatankota_ln'] = trim(preg_replace('/^kec\.?\s+/i', '', $dataList['kecamatankota_ln']));
        }

        return $dataList;
    }

}
