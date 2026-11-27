<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Klien WhatsApp Gateway (kontrak: docs/API.md).
 *
 * Semua kegagalan (konfigurasi kosong, timeout, gateway error) ditelan dan
 * hanya dicatat ke log channel "whatsapp". Service ini tidak boleh melempar
 * exception ke pemanggil karena notifikasi bersifat pelengkap: proses utama
 * (registrasi, pengajuan layanan, verifikasi admin) harus tetap berjalan.
 */
class WhatsAppService
{
    /**
     * Normalisasi nomor ke format Indonesia tanpa tanda plus (628...).
     * Mengikuti aturan gateway: seluruh karakter non-digit dibuang, awalan
     * 0 atau 62 dinormalkan menjadi 62. Mengembalikan null bila tidak valid.
     */
    public static function normalizeNumber(?string $number): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $number);
        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '62')) {
            $normalized = $digits;
        } elseif (str_starts_with($digits, '0')) {
            $normalized = '62'.substr($digits, 1);
        } else {
            $normalized = '62'.$digits;
        }

        // Nomor Indonesia wajar 10-15 digit (62 + 8..13 digit).
        if (strlen($normalized) < 10 || strlen($normalized) > 15) {
            return null;
        }

        return $normalized;
    }

    public static function enabled(): bool
    {
        return (bool) config('whatsapp.enabled');
    }

    /**
     * Nomor WhatsApp admin dari konfigurasi, sudah dinormalisasi & unik.
     *
     * @return string[]
     */
    public static function adminNumbers(): array
    {
        $numbers = [];
        foreach ((array) config('whatsapp.admin_numbers', []) as $number) {
            $normalized = self::normalizeNumber($number);
            if ($normalized) {
                // Nilai (bukan key) dipakai agar tetap berupa string, karena
                // PHP mengubah key numerik menjadi integer.
                $numbers[$normalized] = $normalized;
            }
        }

        return array_values($numbers);
    }

    /**
     * @param  string[]  $recipients
     * @return array<int, array{to: string, success: bool}>
     */
    public static function sendToMany(array $recipients, string $message): array
    {
        $results = [];
        foreach (array_unique($recipients) as $recipient) {
            $results[] = [
                'to' => $recipient,
                'success' => self::send($recipient, $message),
            ];
        }

        return $results;
    }

    public static function sendAdmin(string $message): array
    {
        return self::sendToMany(self::adminNumbers(), $message);
    }

    /**
     * Kirim satu pesan teks ke satu nomor.
     *
     * @return bool true hanya bila gateway menerima pesan (HTTP 202).
     */
    public static function send(?string $to, string $message): bool
    {
        $number = self::normalizeNumber($to);
        if (! $number) {
            return false;
        }

        if (! self::enabled()) {
            self::log('debug', 'Notifikasi WhatsApp dinonaktifkan (WA_NOTIFICATION_ENABLED=false)', [
                'to' => $number,
            ]);

            return false;
        }

        $baseUrl = config('whatsapp.base_url');
        if (blank($baseUrl)) {
            self::log('warning', 'WA_GATEWAY_URL belum diset, notifikasi WhatsApp dilewati', [
                'to' => $number,
            ]);

            return false;
        }

        if (blank(config('whatsapp.token'))) {
            self::log('warning', 'WA_GATEWAY_TOKEN belum diset, notifikasi WhatsApp dilewati', [
                'to' => $number,
            ]);

            return false;
        }

        try {
            $response = Http::withToken((string) config('whatsapp.token'))
                ->acceptJson()
                ->asJson()
                ->timeout((int) config('whatsapp.timeout', 10))
                ->post(rtrim($baseUrl, '/'), [
                    'to' => $number,
                    'message' => $message,
                ]);

            if ($response->status() === 202) {
                self::log('info', 'Notifikasi WhatsApp diterima gateway', [
                    'to' => $number,
                    'message_id' => $response->json('message_id'),
                ]);

                return true;
            }

            self::log('warning', 'Notifikasi WhatsApp ditolak gateway', [
                'to' => $number,
                'status' => $response->status(),
                'body' => mb_substr($response->body(), 0, 1000),
            ]);

            return false;
        } catch (Throwable $e) {
            self::log('error', 'Notifikasi WhatsApp gagal dikirim', [
                'to' => $number,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    protected static function log(string $level, string $message, array $context = []): void
    {
        try {
            Log::channel((string) config('whatsapp.log_channel', 'whatsapp'))
                ->{$level}($message, $context);
        } catch (Throwable) {
            // Jangan sampai kegagalan logging menggagalkan proses utama.
        }
    }
}
