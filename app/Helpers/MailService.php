<?php

namespace App\Helpers;

use GuzzleHttp\Client;

/**
 * Pengiriman email lewat Mail Service.
 *
 * send() bersifat dekoratif: dipanggil setelah data tersimpan dan tidak pernah
 * melempar exception ke pemanggil. Kegagalan (service mati, timeout, respons
 * error) hanya dicatat pada channel log "mail" sehingga registrasi/pengajuan
 * tetap berhasil walau email tidak terkirim.
 */
class MailService
{
    /**
     * Kirim email sekarang (blocking) dan kembalikan body respons.
     * Gunakan hanya di luar alur utama yang tidak boleh gagal.
     *
     * @throws \RuntimeException bila konfigurasi kosong atau service gagal.
     */
    public static function sendOrFail(array $data): string
    {
        $client = new Client;

        // Dibaca dari config, bukan env(): setelah `config:cache`, env() di luar
        // folder config/ selalu mengembalikan null sehingga URI menjadi kosong.
        $uri = config('services.mail_service.uri');
        if (blank($uri)) {
            throw new \RuntimeException('MAIL_SERIVICE_URI belum diset pada konfigurasi.');
        }

        $response = $client->post($uri, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
        ]);

        // Get the response body as a string
        return $response->getBody()->getContents();
    }

    /**
     * Kirim email setelah response terkirim. Tidak pernah melempar exception.
     *
     * Default-nya fire-and-forget sehingga tidak ada nilai balik yang perlu
     * diandalkan; nilainya hanya berguna bila $defer = false (mis. pemanggil
     * perlu tahu apakah email benar-benar terkirim).
     */
    public static function send(array $data, bool $defer = true): bool
    {
        if (! $defer) {
            return DeferredTask::runSafely(
                fn () => self::sendOrFail($data),
                (string) config('services.mail_service.log_channel', 'mail'),
                'Email gagal dikirim via Mail Service'
            );
        }

        DeferredTask::afterResponse(
            fn () => self::sendOrFail($data),
            (string) config('services.mail_service.log_channel', 'mail'),
            'Email gagal dikirim via Mail Service'
        );

        return true;
    }
}
