<?php

namespace App\Services;

use App\Models\BHPNU;
use App\Models\Coretax;
use App\Models\OSS;
use App\Models\PTK;
use App\Models\Satpen;
use App\Models\VirtualNPSN;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Penyusun pesan notifikasi WhatsApp beserta routing penerimanya.
 *
 * - Notifikasi admin (dari .env, bisa lebih dari satu nomor): hanya untuk
 *   pengajuan baru, registrasi baru, dan submit perbaikan.
 * - Notifikasi operator (satpen.telpon): semua aksi admin terhadap
 *   pengajuan/registrasi satpen.
 *
 * Pengiriman dijadwalkan setelah response terkirim (dispatch(...)->afterResponse())
 * sehingga tidak menambah waktu tunggu request pengguna.
 */
class WhatsAppNotifier
{
    /*
    |--------------------------------------------------------------------------
    | Registrasi & Satpen
    |--------------------------------------------------------------------------
    */

    public static function satpenRegistered(Satpen $satpen): void
    {
        self::notifyAdmin(
            "📝 *REGISTRASI SATPEN BARU*\n"
            ."Nama: {$satpen->nm_satpen}\n"
            ."No. Registrasi: {$satpen->no_registrasi}\n"
            .'NPSN: '.($satpen->npsn ?: '-')."\n"
            .'Yayasan: '.($satpen->yayasan ?: '-')."\n"
            .'Telpon: '.($satpen->telpon ?: '-')."\n"
            .'Tanggal: '.self::now()."\n\n"
            .'Silakan periksa dan verifikasi melalui panel admin.'
        );

        self::notifyOperator($satpen,
            "🎉 *REGISTRASI BERHASIL*\n"
            ."Selamat, {$satpen->nm_satpen} berhasil terdaftar di sistem.\n"
            ."No. Registrasi: *{$satpen->no_registrasi}*\n"
            ."Gunakan nomor registrasi tersebut untuk masuk ke portal.\n\n"
            .'Permohonan Anda sedang menunggu verifikasi admin. Notifikasi berikutnya akan dikirim ke nomor ini.'
        );
    }

    public static function satpenResubmitted(Satpen $satpen, string $jenis): void
    {
        self::notifyAdmin(
            "🔁 *SUBMIT PERBAIKAN SATPEN*\n"
            ."Jenis: {$jenis}\n"
            ."Nama: {$satpen->nm_satpen}\n"
            ."No. Registrasi: {$satpen->no_registrasi}\n"
            .'Tanggal: '.self::now()."\n\n"
            .'Data perbaikan sudah dikirim operator dan menunggu peninjauan.'
        );
    }

    public static function satpenStatusChanged(Satpen $satpen, string $status, ?string $keterangan = null): void
    {
        $label = self::statusLabel($status);
        $pesan = "📢 *UPDATE STATUS SATPEN*\n"
            ."Yth. Operator {$satpen->nm_satpen},\n"
            ."status satuan pendidikan Anda saat ini: *{$label}*.\n"
            ."No. Registrasi: {$satpen->no_registrasi}\n"
            .'Tanggal: '.self::now();

        if (filled($keterangan)) {
            $pesan .= "\nCatatan: {$keterangan}";
        }

        if (in_array($status, ['revisi', 'expired'], true)) {
            $pesan .= "\n\nSilakan masuk ke portal untuk menindaklanjuti.";
        } elseif (in_array($status, ['setujui', 'terima'], true)) {
            $pesan .= "\n\nPiagam dan SK dapat diunduh melalui portal.";
        }

        self::notifyOperator($satpen, $pesan);
    }

    /**
     * Pengingat manual yang dikirim admin (mis. mengingatkan masa revisi).
     */
    public static function satpenAdminNotice(Satpen $satpen, string $judul, string $isi): void
    {
        self::notifyOperator($satpen,
            "📣 *{$judul}*\n"
            ."Yth. Operator {$satpen->nm_satpen},\n"
            ."{$isi}\n\n"
            .'Tanggal: '.self::now()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | OSS
    |--------------------------------------------------------------------------
    */

    public static function ossSubmitted(OSS $oss, string $jenis = 'pengajuan baru'): void
    {
        [$satpen, $nama] = self::satpenOf($oss);
        self::notifyAdmin(
            "📄 *PENGAJUAN OSS*\n"
            ."Jenis: {$jenis}\n"
            ."Satpen: {$nama}\n"
            .'No. Registrasi: '.($satpen->no_registrasi ?? '-')."\n"
            .'Email: '.($oss->email ?: '-')."\n"
            .'Tanggal: '.self::now()."\n\n"
            .'Silakan periksa dan verifikasi melalui panel admin.'
        );
    }

    public static function ossAccepted(OSS $oss): void
    {
        [$satpen] = self::satpenOf($oss);
        self::notifyOperator($satpen,
            "✅ *PENGAJUAN OSS DITERIMA*\n"
            ."Permohonan OSS {$satpen->nm_satpen} telah diverifikasi dan saat ini sedang diproses oleh admin.\n"
            .'Tanggal: '.self::now()
        );
    }

    public static function ossRejected(OSS $oss, ?string $keterangan = null): void
    {
        [$satpen] = self::satpenOf($oss);
        $pesan = "❌ *PENGAJUAN OSS DITOLAK*\n"
            ."Permohonan OSS {$satpen->nm_satpen} perlu diperbaiki"
            .(filled($keterangan) ? " dengan catatan berikut:\n{$keterangan}" : '.')
            ."\n\nSilakan masuk ke portal untuk melakukan perbaikan.";
        self::notifyOperator($satpen, $pesan);
    }

    public static function ossIssued(OSS $oss): void
    {
        [$satpen] = self::satpenOf($oss);
        $pesan = "🎉 *IZIN OSS TERBIT*\n"
            ."Selamat, izin OSS untuk {$satpen->nm_satpen} telah terbit.\n";
        if ($oss->tgl_expired) {
            $pesan .= 'Berlaku sampai: '.self::formatDate($oss->tgl_expired)."\n";
        }
        $pesan .= "\nSilakan cek portal untuk detail izin.";
        self::notifyOperator($satpen, $pesan);
    }

    /*
    |--------------------------------------------------------------------------
    | BHPNU
    |--------------------------------------------------------------------------
    */

    public static function bhpnuSubmitted(BHPNU $bhpnu, string $jenis = 'pengajuan baru'): void
    {
        [$satpen, $nama] = self::satpenOf($bhpnu);
        self::notifyAdmin(
            "💳 *PENGAJUAN BHPNU*\n"
            ."Jenis: {$jenis}\n"
            ."Satpen: {$nama}\n"
            .'No. Registrasi: '.($satpen->no_registrasi ?? '-')."\n"
            .'Tanggal: '.self::now()."\n\n"
            .'Silakan periksa bukti pembayaran dan verifikasi melalui panel admin.'
        );
    }

    public static function bhpnuAccepted(BHPNU $bhpnu): void
    {
        [$satpen] = self::satpenOf($bhpnu);
        self::notifyOperator($satpen,
            "✅ *PENGAJUAN BHPNU DITERIMA*\n"
            ."Permohonan BHPNU {$satpen->nm_satpen} telah diverifikasi dan sedang diproses.\n"
            .'Tanggal: '.self::now()
        );
    }

    public static function bhpnuRejected(BHPNU $bhpnu, ?string $keterangan = null): void
    {
        [$satpen] = self::satpenOf($bhpnu);
        $pesan = "❌ *PENGAJUAN BHPNU DITOLAK*\n"
            ."Permohonan BHPNU {$satpen->nm_satpen} perlu diperbaiki"
            .(filled($keterangan) ? " dengan catatan berikut:\n{$keterangan}" : '.')
            ."\n\nSilakan masuk ke portal untuk melakukan perbaikan.";
        self::notifyOperator($satpen, $pesan);
    }

    public static function bhpnuDocumentSent(BHPNU $bhpnu): void
    {
        [$satpen] = self::satpenOf($bhpnu);
        $pesan = "📦 *DOKUMEN BHPNU DIKIRIM*\n"
            ."Dokumen BHPNU untuk {$satpen->nm_satpen} telah dikirim.\n";
        if ($bhpnu->no_resi) {
            $pesan .= "No. Resi: {$bhpnu->no_resi}\n";
        }
        if ($bhpnu->tgl_dikirim) {
            $pesan .= 'Tanggal kirim: '.self::formatDate($bhpnu->tgl_dikirim)."\n";
        }
        if ($bhpnu->tgl_expired) {
            $pesan .= 'Berlaku sampai: '.self::formatDate($bhpnu->tgl_expired)."\n";
        }
        $pesan .= "\nAkta dapat diakses melalui portal.";
        self::notifyOperator($satpen, $pesan);
    }

    /*
    |--------------------------------------------------------------------------
    | Coretax
    |--------------------------------------------------------------------------
    */

    public static function coretaxSubmitted(Coretax $coretax, string $jenis = 'pengajuan baru'): void
    {
        [$satpen, $nama] = self::satpenOf($coretax);
        self::notifyAdmin(
            "🧾 *PENGAJUAN CORETAX*\n"
            ."Jenis: {$jenis}\n"
            ."Satpen: {$nama}\n"
            .'No. Registrasi: '.($satpen->no_registrasi ?? '-')."\n"
            .'PIC: '.($coretax->nama_pic ?: '-')."\n"
            .'Tanggal: '.self::now()."\n\n"
            .'Silakan periksa dan verifikasi melalui panel admin.'
        );
    }

    public static function coretaxAccepted(Coretax $coretax): void
    {
        [$satpen] = self::satpenOf($coretax);
        self::notifyOperator($satpen,
            "✅ *PENGAJUAN CORETAX DITERIMA*\n"
            ."Permohonan Coretax {$satpen->nm_satpen} telah diverifikasi dan sedang diproses.\n"
            .'Tanggal: '.self::now()
        );
    }

    public static function coretaxRejected(Coretax $coretax, ?string $keterangan = null): void
    {
        [$satpen] = self::satpenOf($coretax);
        $pesan = "❌ *PENGAJUAN CORETAX DITOLAK*\n"
            ."Permohonan Coretax {$satpen->nm_satpen} perlu diperbaiki"
            .(filled($keterangan) ? " dengan catatan berikut:\n{$keterangan}" : '.')
            ."\n\nSilakan masuk ke portal untuk melakukan perbaikan.";
        self::notifyOperator($satpen, $pesan);
    }

    public static function coretaxApproved(Coretax $coretax): void
    {
        [$satpen] = self::satpenOf($coretax);
        $pesan = "🎉 *CORETAX DISETUJUI*\n"
            ."Pengajuan Coretax {$satpen->nm_satpen} telah disetujui.\n";
        if ($coretax->nitku) {
            $pesan .= "NITKU: {$coretax->nitku}\n";
        }
        if ($coretax->tgl_expiry) {
            $pesan .= 'Berlaku sampai: '.self::formatDate($coretax->tgl_expiry)."\n";
        }
        $pesan .= "\nSilakan cek portal untuk detail.";
        self::notifyOperator($satpen, $pesan);
    }

    /**
     * Permintaan operator yang perlu ditindaklanjuti admin (mis. buka expiry).
     */
    public static function coretaxRequestAction(Coretax $coretax, string $keterangan): void
    {
        [$satpen, $nama] = self::satpenOf($coretax);
        self::notifyAdmin(
            "🔔 *PERMINTAAN OPERATOR CORETAX*\n"
            ."Satpen: {$nama}\n"
            .'No. Registrasi: '.($satpen->no_registrasi ?? '-')."\n"
            ."Permintaan: {$keterangan}\n"
            .'Tanggal: '.self::now()."\n\n"
            .'Silakan tindak lanjuti melalui panel admin.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Virtual NPSN
    |--------------------------------------------------------------------------
    */

    public static function vnpsnRequested(VirtualNPSN $vnpsn): void
    {
        self::notifyAdmin(
            "📨 *PERMOHONAN NPSN VIRTUAL*\n"
            ."Sekolah: {$vnpsn->nama_sekolah}\n"
            .'Email: '.($vnpsn->email ?: '-')."\n"
            .'NIK Kepsek: '.($vnpsn->nik_kepsek ?: '-')."\n"
            .'Tanggal: '.self::now()."\n\n"
            .'Silakan validasi dan terbitkan nomor NPSN Virtual melalui panel admin.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PTK
    |--------------------------------------------------------------------------
    */

    public static function ptkSubmitted(PTK $ptk, string $jenis = 'pengajuan baru'): void
    {
        $satpen = $ptk->satpen;
        self::notifyAdmin(
            "👨‍🏫 *PENGAJUAN PTK*\n"
            ."Jenis: {$jenis}\n"
            ."Nama PTK: {$ptk->nama_ptk}\n"
            ."NIK: {$ptk->nik}\n"
            ."Jenis PTK: {$ptk->jenis_ptk}\n"
            .'Satpen: '.($satpen->nm_satpen ?? '-')."\n"
            .'No. Registrasi: '.($satpen->no_registrasi ?? '-')."\n"
            .'Tanggal: '.self::now()."\n\n"
            .'Silakan verifikasi data PTK melalui panel admin.'
        );
    }

    public static function ptkAction(PTK $ptk, string $action, ?string $catatan = null): void
    {
        $satpen = $ptk->satpen;

        [$judul, $keterangan] = match ($action) {
            'terima', 'proses' => ['✅ *PTK DITERIMA*', 'Data PTK telah diverifikasi dan sedang diproses.'],
            'tolak' => ['❌ *PTK PERLU REVISI*', 'Data PTK dikembalikan untuk diperbaiki.'],
            'approve' => ['🎉 *PTK DISETUJUI*', 'Data PTK telah disetujui.'],
            'keluarkan' => ['📜 *SK PTK DITERBITKAN*', 'SK PTK telah dikeluarkan dan dapat diunduh melalui portal.'],
            default => ['📢 *UPDATE PTK*', "Status PTK diperbarui: {$action}."],
        };

        $pesan = "{$judul}\n"
            ."Nama PTK: {$ptk->nama_ptk}\n"
            ."Jenis PTK: {$ptk->jenis_ptk}\n"
            .'Sekolah: '.($satpen->nm_satpen ?? '-')."\n"
            ."{$keterangan}\n"
            .'Tanggal: '.self::now();

        if (filled($catatan)) {
            $pesan .= "\nCatatan: {$catatan}";
        }

        self::notifyOperator($satpen, $pesan);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    protected static function notifyAdmin(string $message): void
    {
        $recipients = WhatsAppService::adminNumbers();
        if (empty($recipients)) {
            return;
        }
        self::dispatchLater(fn () => WhatsAppService::sendToMany($recipients, $message));
    }

    protected static function notifyOperator(?Satpen $satpen, string $message): void
    {
        if (! $satpen || blank($satpen->telpon)) {
            return;
        }
        $number = WhatsAppService::normalizeNumber($satpen->telpon);
        if (! $number) {
            return;
        }
        self::dispatchLater(fn () => WhatsAppService::send($number, $message));
    }

    /**
     * Jalankan pengiriman setelah response terkirim ke pengguna. Bila request
     * berjalan di console (command/cron), afterResponse tidak akan pernah
     * dieksekusi, sehingga dikirim langsung — tetap dibungkus try/catch.
     */
    protected static function dispatchLater(callable $callback): void
    {
        if (! WhatsAppService::enabled()) {
            return;
        }

        try {
            /**
             * Bila dipanggil di dalam transaksi DB, tunggu commit dulu supaya
             * notifikasi tidak terkirim untuk data yang akhirnya di-rollback.
             */
            if (DB::transactionLevel() > 0) {
                DB::afterCommit(fn () => self::dispatchLater($callback));

                return;
            }

            if (app()->runningInConsole()) {
                self::safelyRun($callback);

                return;
            }

            dispatch(function () use ($callback) {
                self::safelyRun($callback);
            })->afterResponse();
        } catch (Throwable $e) {
            self::logError('Gagal menjadwalkan notifikasi WhatsApp', $e);
        }
    }

    protected static function safelyRun(callable $callback): void
    {
        try {
            $callback();
        } catch (Throwable $e) {
            self::logError('Notifikasi WhatsApp gagal diproses', $e);
        }
    }

    protected static function logError(string $message, Throwable $e): void
    {
        try {
            Log::channel((string) config('whatsapp.log_channel', 'whatsapp'))
                ->error($message, ['error' => $e->getMessage()]);
        } catch (Throwable) {
            // Kegagalan logging tidak boleh mengganggu proses utama.
        }
    }

    /**
     * @return array{0: ?Satpen, 1: string}
     */
    protected static function satpenOf(object $model): array
    {
        $satpen = $model->satpen ?? null;
        $nama = $satpen->nm_satpen ?? ('User #'.($model->id_user ?? '-'));

        return [$satpen, $nama];
    }

    protected static function statusLabel(string $status): string
    {
        return match ($status) {
            'permohonan' => 'Menunggu Verifikasi',
            'revisi' => 'Perlu Revisi',
            'proses dokumen' => 'Proses Dokumen',
            'setujui', 'terima' => 'Disetujui',
            'expired' => 'Dokumen Expired',
            'perpanjangan' => 'Perpanjangan Diajukan',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    protected static function now(): string
    {
        // Locale aplikasi 'en', jadi nama bulan dipaksa ke id agar pesan konsisten.
        return Date::now()->locale('id')->translatedFormat('d F Y H:i').' WIB';
    }

    protected static function formatDate($date): string
    {
        try {
            return Carbon::parse($date)->locale('id')->translatedFormat('d F Y');
        } catch (Throwable) {
            return (string) $date;
        }
    }
}
