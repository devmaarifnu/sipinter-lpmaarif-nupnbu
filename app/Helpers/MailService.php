<?php
namespace App\Helpers;

use GuzzleHttp\Client;

class MailService {

    public static function send(array $data)
    {
        $client = new Client();

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
        $body = $response->getBody()->getContents();

        return $body;
    }

}
