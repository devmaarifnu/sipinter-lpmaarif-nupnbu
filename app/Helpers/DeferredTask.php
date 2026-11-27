<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Menjalankan pekerjaan pelengkap (email, notifikasi WhatsApp) di luar jalur
 * utama: dijadwalkan setelah response terkirim, dan seluruh kegagalannya hanya
 * dicatat ke log. Proses bisnis tidak boleh gagal/lambat karena layanan
 * eksternal tidak tersedia.
 */
class DeferredTask
{
    /**
     * @param  callable  $callback  pekerjaan yang dijalankan setelah response
     * @param  string  $logChannel  channel log tujuan pencatatan kegagalan
     * @param  string  $context  pesan log singkat, mis. "Email gagal dikirim"
     */
    public static function afterResponse(callable $callback, string $logChannel, string $context): void
    {
        try {
            /**
             * Bila dipanggil di dalam transaksi DB, tunggu commit dulu supaya
             * pekerjaan tidak dijalankan untuk data yang akhirnya di-rollback.
             */
            if (DB::transactionLevel() > 0) {
                DB::afterCommit(fn () => self::afterResponse($callback, $logChannel, $context));

                return;
            }

            /**
             * Di console (command/cron) afterResponse tidak akan pernah
             * dieksekusi, sehingga dijalankan langsung — tetap aman dari
             * exception.
             */
            if (app()->runningInConsole()) {
                self::runSafely($callback, $logChannel, $context);

                return;
            }

            dispatch(fn () => self::runSafely($callback, $logChannel, $context))->afterResponse();
        } catch (Throwable $e) {
            self::log($logChannel, $context, $e);
        }
    }

    /**
     * Jalankan sekarang dan telan kegagalannya (dicatat ke log).
     * Mengembalikan true bila pekerjaan berhasil — dipakai bila hasilnya perlu
     * diketahui pemanggil, mis. untuk memberi tahu pengguna bahwa email gagal.
     */
    public static function runSafely(callable $callback, string $logChannel, string $context): bool
    {
        try {
            $callback();

            return true;
        } catch (Throwable $e) {
            self::log($logChannel, $context, $e);

            return false;
        }
    }

    protected static function log(string $channel, string $context, Throwable $e): void
    {
        try {
            Log::channel($channel)->error($context, [
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);
        } catch (Throwable) {
            // Kegagalan logging tidak boleh mengganggu proses utama.
        }
    }
}
