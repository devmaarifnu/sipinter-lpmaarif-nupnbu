<?php

namespace App\Console\Commands;

use App\Models\Satpen;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;

/**
 * Uji koneksi WhatsApp Gateway dan routing notifikasi.
 *
 *   php artisan wa:test                          # cek konfigurasi + status gateway
 *   php artisan wa:test 0812xxxx --send=Halo     # kirim pesan uji ke satu nomor
 *   php artisan wa:test --satpen=1               # kirim pesan uji ke telpon satpen
 *   php artisan wa:test --admin                  # kirim pesan uji ke semua nomor admin
 */
class TestWhatsAppNotification extends Command
{
    protected $signature = 'wa:test
                            {number? : Nomor tujuan uji}
                            {--send= : Isi pesan yang dikirim (default: pesan uji bawaan)}
                            {--satpen= : ID satpen; kirim ke kolom satpen.telpon}
                            {--admin : Kirim ke seluruh nomor admin dari .env}';

    protected $description = 'Uji integrasi notifikasi WhatsApp (cek konfigurasi & kirim pesan uji)';

    public function handle(): int
    {
        $this->line('Fitur notifikasi : '.(config('whatsapp.enabled') ? 'aktif' : 'nonaktif'));
        $this->line('Gateway URL      : '.(config('whatsapp.base_url') ?: '(belum diset)'));

        $admins = WhatsAppService::adminNumbers();
        $this->line('Nomor admin      : '.(empty($admins) ? '(belum diset)' : implode(', ', $admins)));

        $message = $this->option('send') ?: 'Test notifikasi WhatsApp dari '.config('whatsapp.app_name');
        $targets = [];

        if ($this->option('admin')) {
            $targets = $admins;
        } elseif ($this->option('satpen')) {
            $satpen = Satpen::find($this->option('satpen'));
            if (! $satpen) {
                $this->error('Satpen tidak ditemukan.');

                return self::FAILURE;
            }
            $this->line("Telpon satpen    : {$satpen->telpon}");
            $targets = [$satpen->telpon];
        } elseif ($this->argument('number')) {
            $targets = [$this->argument('number')];
        }

        if (empty($targets)) {
            $this->info('Tidak ada tujuan pengiriman (cek WA_ADMIN_NUMBERS / --satpen / argument number).');

            return blank(config('whatsapp.base_url')) ? self::FAILURE : self::SUCCESS;
        }

        $hasil = WhatsAppService::sendToMany($targets, $message);
        foreach ($hasil as $item) {
            $this->line(sprintf(
                '%s -> %s',
                $item['to'],
                $item['success'] ? 'diterima gateway (202)' : 'GAGAL (lihat storage/logs/whatsapp*.log)'
            ));
        }

        return collect($hasil)->every(fn ($item) => $item['success'])
            ? self::SUCCESS
            : self::FAILURE;
    }
}
