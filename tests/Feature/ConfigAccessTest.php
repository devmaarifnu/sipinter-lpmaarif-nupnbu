<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Setelah `php artisan config:cache`, env() di luar folder config/
 * selalu mengembalikan null. Nilai konfigurasi wajib dibaca via config().
 *
 * Regresi ini pernah menyebabkan error "URI must be a string or
 * UriInterface" pada regenerasi Piagam/SK (MailService).
 */
class ConfigAccessTest extends TestCase
{
    public function test_tidak_ada_pemanggilan_env_di_folder_app(): void
    {
        $pelanggaran = [];

        $berkas = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(app_path(), \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($berkas as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            foreach (file($file->getPathname()) as $nomor => $baris) {
                $tanpaKomentar = preg_replace('#//.*$#', '', $baris);

                if (preg_match('/\benv\s*\(/', $tanpaKomentar)) {
                    $pelanggaran[] = sprintf(
                        '%s:%d',
                        str_replace(base_path().DIRECTORY_SEPARATOR, '', $file->getPathname()),
                        $nomor + 1
                    );
                }
            }
        }

        $this->assertSame(
            [],
            $pelanggaran,
            "env() tidak boleh dipakai di luar folder config/ (akan null saat config:cache aktif):\n"
            .implode("\n", $pelanggaran)
        );
    }

    public function test_konfigurasi_layanan_terbaca(): void
    {
        $this->assertNotNull(
            config('services.mail_service.uri'),
            'services.mail_service.uri tidak terbaca. Pastikan ada di config/services.php.'
        );

        $this->assertNotNull(
            config('services.dapo.url'),
            'services.dapo.url tidak terbaca. Pastikan ada di config/services.php.'
        );
    }
}
