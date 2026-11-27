# Notifikasi WhatsApp

Integrasi notifikasi WhatsApp untuk seluruh alur layanan SIPINTER. Pesan dikirim melalui
WhatsApp Gateway (kontrak API di [`API.md`](API.md), endpoint `POST /send/message`).

## Konfigurasi

Tambahkan ke `.env`:

```dotenv
WA_NOTIFICATION_ENABLED=true          # false = matikan seluruh notifikasi
WA_GATEWAY_URL=https://wa.example.com/wa
WA_GATEWAY_TOKEN=<bearer-token>
WA_GATEWAY_TIMEOUT=10                 # detik
WA_ADMIN_NUMBERS=08123456789,08129876543   # bisa lebih dari satu, pisah koma
WA_APP_NAME="SIPINTER LP Ma'arif NU"
```

Nomor admin boleh ditulis bebas (`08..`, `+62..`, `62..`); dinormalisasi otomatis ke format
gateway (`628..`). Nomor operator **tidak** diambil dari env, melainkan dari kolom
`satpen.telpon` milik satuan pendidikan terkait.

Setelah mengubah `.env` pada server yang memakai `config:cache`, jalankan
`php artisan config:clear` (atau `config:cache` ulang).

Uji cepat:

```bash
php artisan wa:test                     # tampilkan konfigurasi yang terbaca
php artisan wa:test 08123456789         # kirim pesan uji ke satu nomor
php artisan wa:test --admin             # kirim ke semua nomor admin
php artisan wa:test --satpen=1          # kirim ke nomor telpon satpen id 1
```

## Matriks notifikasi

| Fitur | Event | Penerima |
|---|---|---|
| Registrasi satpen | operator menyelesaikan registrasi | **admin** + operator |
| Satpen | submit perbaikan revisi / perpanjangan / ganti NPSN | **admin** |
| Satpen | admin mengubah status (revisi, proses dokumen, expired, perpanjangan, terima, setujui) | operator |
| Satpen | admin mengirim pengingat revisi (tombol notif email) | operator |
| OSS | operator submit pengajuan / submit perbaikan | **admin** |
| OSS | diterima, ditolak (disertai catatan), izin terbit | operator |
| BHPNU | operator submit pengajuan / submit perbaikan | **admin** |
| BHPNU | diterima, ditolak (disertai catatan), dokumen dikirim (resi & masa berlaku) | operator |
| Coretax | operator submit pengajuan / submit perbaikan | **admin** |
| Coretax | permohonan buka expiry diajukan operator | **admin** |
| Coretax | diterima, ditolak, disetujui (NITKU & expiry) | operator |
| PTK | pengajuan baru / submit perbaikan / ajukan ulang | **admin** |
| PTK | terima/proses, minta revisi, approve, SK dikeluarkan | operator |
| NPSN Virtual | permohonan baru | **admin** |

Notifikasi admin hanya untuk pengajuan **masuk** (registrasi, pengajuan baru, submit
perbaikan). Aksi admin yang mengubah keputusan pengajuan (terima/tolak/terbit) dikirim ke
operator.

Aksi **delete/update data tidak dikirim notifikasi** sama sekali — mis. menghapus
permohonan OSS/BHPNU/Coretax, menarik dokumen akta, reset/blokir/buka-blokir akun operator,
serta membuka expiry Coretax.

## Sifat non-blocking

Pengiriman tidak pernah menghentikan atau menggagalkan proses bisnis:

1. Pesan dijadwalkan lewat `dispatch(...)->afterResponse()`, sehingga HTTP call ke gateway
   baru berjalan **setelah** response dikirim ke pengguna (Laravel `terminating`).
2. Seluruh kegagalan (URL kosong, timeout, `4xx/5xx`, exception) ditelan di
   `App\Services\WhatsAppService` dan hanya dicatat ke log.
3. Bila `satpen.telpon` kosong/tidak valid, atau `WA_ADMIN_NUMBERS` kosong, pesan untuk
   penerima tersebut dilewati tanpa error.
4. Saat berjalan di console (command/cron), callback `afterResponse` tidak pernah
   dieksekusi, sehingga notifier langsung mengirim dalam blok `try/catch`.

Catatan: gateway memiliki antrian in-memory berkapasitas 100 job dan akan menahan request
bila penuh (lihat `API.md` §3). Karena pengiriman berjalan setelah response, penumpukan
antrian tidak memperlambat pengguna; batas `WA_GATEWAY_TIMEOUT` mencegah proses PHP
tertahan terlalu lama.

## Log

Kegagalan dan jejak pengiriman berada di channel terpisah (bukan `laravel.log`):

```
storage/logs/whatsapp-YYYY-MM-DD.log
```

Contoh:

```
[warning] Notifikasi WhatsApp ditolak gateway {"to":"628123456789","status":401,...}
[error]   Notifikasi WhatsApp gagal dikirim {"to":"628123456789","error":"cURL error 28 ..."}
```

## Struktur kode

| Berkas | Isi |
|---|---|
| `config/whatsapp.php` | konfigurasi env (URL, token, timeout, nomor admin) |
| `app/Services/WhatsAppService.php` | klien gateway: normalisasi nomor, kirim pesan, logging |
| `app/Services/WhatsAppNotifier.php` | penyusun pesan per fitur + routing admin/operator |
| `app/Console/Commands/TestWhatsAppNotification.php` | command `wa:test` |

Menambah notifikasi baru cukup menambahkan method statis di `WhatsAppNotifier` lalu
memanggilnya setelah proses bisnis selesai (setelah `DB::commit()` / `save()`).
