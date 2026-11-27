# WhatsApp Gateway — API Contract

Kontrak integrasi HTTP untuk aplikasi lain (backend service, worker, dashboard) yang ingin
mengirim pesan WhatsApp melalui engine ini.

**Versi:** 1.0 · **Base URL:** `https://<host>/<base_path>` (mis. `https://wa.example.com/wa`)

> Dokumen ini diturunkan langsung dari implementasi (`main.go`, `handler/*.go`, `service/*.go`).
> Semua contoh status code dan bentuk `error` di bawah adalah output aktual kode, bukan asumsi.

---

## 1. Ringkasan Endpoint

| Method | Path | Fungsi | Sukses |
|---|---|---|---|
| `POST` | `/send/message` | Kirim pesan teks langsung | `202` |
| `POST` | `/send/template` | Kirim pesan dari template + variabel | `202` |
| `GET` | `/messages/:id` | Cek status satu pesan | `200` |
| `GET` | `/messages` | Riwayat / daftar pesan keluar | `200` |
| `GET` | `/status` | Status koneksi WhatsApp | `200` |
| `GET` | `/qr` | QR code untuk menautkan perangkat | `200` / `503` |
| `POST` | `/logout` | Logout sesi WhatsApp | `200` |

Endpoint `/qr`, `/status`, `/logout` bersifat **operasional** — dipakai admin/ops untuk menautkan
dan memantau perangkat. Aplikasi bisnis biasanya hanya perlu `POST /send/*` dan `GET /messages*`.

---

## 2. Autentikasi

Semua endpoint di atas memakai **Bearer token statis** yang disimpan di tabel MySQL `access_token`.

```
Authorization: Bearer <token>
```

Token diverifikasi terhadap tabel `access_token`; kolom `hashtype` menentukan cara verifikasi:

| `hashtype` | Cara verifikasi |
|---|---|
| `NULL` | token dibandingkan apa adanya (plain) |
| `sha256` | `sha256(token)` dicocokkan dengan isi kolom |
| `bcrypt` | `bcrypt.CompareHashAndPassword` |

Jika kolom `expires_at` tidak `NULL` dan sudah lewat, request ditolak `401 token expired`.

### Kegagalan autentikasi

| Kondisi | Status | Body |
|---|---|---|
| Header tidak ada / bukan `Bearer ` | `401` | `{"error":"missing token"}` |
| Token tidak dikenal | `401` | `{"error":"invalid token"}` |
| Token kadaluarsa | `401` | `{"error":"token expired"}` |
| Error database saat cek token | `500` | `{"error":"db error"}` |

### Menambahkan token baru (admin / DBA)

Belum ada endpoint manajemen token — token dibuat langsung di database:

```sql
-- Plain (paling sederhana, tapi token tersimpan apa adanya)
INSERT INTO access_token (name, token, hashtype, expires_at, created_at, updated_at)
VALUES ('app-penjualan', 'REPLACE_WITH_RANDOM_32_BYTES_HEX', NULL, NULL, NOW(), NOW());

-- sha256 (yang dikirim klien = token asli)
INSERT INTO access_token (name, token, hashtype, expires_at, created_at, updated_at)
VALUES ('app-warehouse', SHA2('REPLACE_WITH_RANDOM_TOKEN', 256), 'sha256', NULL, NOW(), NOW());

-- bcrypt: hash dibuat di luar MySQL, mis. `htpasswd -nbBC 10 x '<token>' | cut -d: -f2`
INSERT INTO access_token (name, token, hashtype, expires_at, created_at, updated_at)
VALUES ('app-crm', '<bcrypt-hash>', 'bcrypt', '2027-01-01 00:00:00', NOW(), NOW());
```

**Rekomendasi:** satu token per aplikasi (`name` = nama aplikasi) supaya bisa dicabut/rotasi
tanpa mengganggu integrasi lain.

---

## 3. Konvensi Umum

### Content-Type

- Request body: `Content-Type: application/json`
- Semua respons: `application/json; charset=utf-8` — kecuali 404/405 yang dikembalikan Gin sebagai
  `text/plain` (lihat §8).

### Format nomor telepon

Field `to` menerima format bebas; server menormalkan ke format Indonesia tanpa tanda plus:

| Input | Disimpan sebagai |
|---|---|
| `08123456789` | `628123456789` |
| `+62 812-3456-789` | `628123456789` |
| `8123456789` | `628123456789` |
| `628123456789` | `628123456789` |

Semua karakter non-digit dibuang. Jika hasilnya kosong, request gagal.
Normalisasi ini **khusus Indonesia** — nomor negara lain akan ikut diberi prefix `62`.

### Model pengiriman: asynchronous

`POST /send/*` **tidak mengirim pesan secara langsung**. Alur:

1. Nomor dinormalisasi + request divalidasi.
2. Baris `messages` dibuat dengan `status = "pending"`.
3. `message_id` dikembalikan dengan HTTP `202`.
4. Worker goroutine mengambil dari queue, mengirim via WhatsApp, lalu mengubah status menjadi
   `sent` atau `failed`.

Konsekuensi untuk integrator:

- `202` berarti **diterima & masuk antrian**, bukan terkirim.
- Untuk memastikan hasil akhir, polling `GET /messages/:id` sampai `status` bukan `pending`.
- Antrian berkapasitas 100 job in-memory; bila penuh, request akan **block** sampai ada ruang
  (bukan ditolak). Timeout/retry di sisi klien sebaiknya diset lebih besar dari waktu tunggu wajar.

### Status pesan

| Status | Arti |
|---|---|
| `pending` | Masih di antrian / sedang dikirim |
| `sent` | WhatsApp client menerima pesan tanpa error |
| `failed` | Gagal kirim; alasan ada di `error_msg` |

Catatan: `sent` berarti pesan sudah diserahkan ke WhatsApp, **bukan** konfirmasi dibaca.
Status delivered/read tidak dilacak engine ini.

### Idempotensi

Tidak ada. Memanggil `POST /send/message` dua kali dengan body sama akan membuat **dua** pesan
dan mengirim dua kali. Jika integrasi butuh idempotensi, simpan `message_id` di sisi aplikasi
dan jangan retry request yang sudah menerima `202`.

### Timezone

Semua timestamp adalah `time.Time` Go dengan encoding JSON default (RFC 3339), memakai zona waktu
server. Bandingkan sebagai waktu absolut, jangan mengandalkan offset `Z`.

---

## 4. `POST /send/message`

Kirim satu pesan teks.

```http
POST /send/message
Authorization: Bearer <token>
Content-Type: application/json
```

```json
{
  "to": "08123456789",
  "message": "Halo, ini pesan dari sistem penjualan."
}
```

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `to` | string | ya | Nomor tujuan, lihat §3 |
| `message` | string | ya | Isi pesan teks |

**Sukses — `202 Accepted`**

```json
{
  "message_id": 42,
  "status": "pending"
}
```

**Gagal — `400 Bad Request`**

```json
{ "error": "Key: 'sendMessageReq.To' Error:Field validation for 'To' failed on the 'required' tag" }
```

Gin mengembalikan pesan validator apa adanya (mengandung nama struct internal seperti
`sendMessageReq`); jangan parsing isi `error` — gunakan status code saja.

**Gagal — `500 Internal Server Error`**

Terjadi bila insert ke MySQL error (`{"error": "<pesan driver mysql>"}`). Aman untuk di-retry.

---

## 5. `POST /send/template`

Kirim pesan yang isinya diambil dari tabel `templates` dengan substitusi variabel `{{key}}`.

```http
POST /send/template
Authorization: Bearer <token>
Content-Type: application/json
```

```json
{
  "to": "08123456789",
  "template": "order_ready",
  "variables": {
    "name": "Budi",
    "order_id": "ORD-001"
  }
}
```

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `to` | string | ya | Nomor tujuan, lihat §3 |
| `template` | string | ya | `templates.name` yang `is_active = 1` |
| `variables` | object[string]string | tidak | Nilai untuk placeholder `{{key}}` |

**Sukses — `202 Accepted`**

```json
{
  "message_id": 43,
  "status": "pending"
}
```

**Gagal — `400 Bad Request`**

Semua kegagalan validasi bisnis di endpoint ini memakai `400`:

```json
{ "error": "template \"order_ready\" not found" }
```

```json
{ "error": "nomor tidak valid: \"\"" }
```

`variables` yang tidak dipakai template diabaikan. Placeholder yang tidak punya nilai di
`variables` **dibiarkan apa adanya** (mis. `{{name}}` tetap muncul di pesan). Pastikan aplikasi
mengirim semua variabel yang dibutuhkan template.

> `400` pada endpoint ini ambigu — bisa berarti "input salah", "template tidak ada", atau
> "nomor tidak valid". Bedakan lewat isi `error`, dan hanya untuk kasus yang memang bisa
> diperbaiki dengan mengganti request. **Jangan retry `400` tanpa perubahan request.**

### Pengelolaan template (DBA)

Tidak ada CRUD template via API. Template dikelola langsung di MySQL:

```sql
INSERT INTO templates (name, body, is_active, created_at, updated_at)
VALUES (
  'order_ready',
  'Halo {{name}}, pesanan {{order_id}} Anda sudah siap diambil.',
  1, NOW(), NOW()
);
```

Nonaktifkan dengan `UPDATE templates SET is_active = 0 WHERE name = 'order_ready';` —
setelah itu `POST /send/template` dengan nama tersebut akan mengembalikan `400 not found`.

---

## 6. `GET /messages/:id`

Cek status satu pesan. Ini cara utama memantau hasil pengiriman async.

```http
GET /messages/42
Authorization: Bearer <token>
```

**Sukses — `200 OK`** (status `pending`)

```json
{
  "id": 42,
  "type": "single",
  "to_number": "628123456789",
  "body": "Halo, ini pesan dari sistem penjualan.",
  "status": "pending",
  "created_at": "2026-09-02T07:50:59+07:00"
}
```

**Sukses — `200 OK`** (status `sent`)

```json
{
  "id": 42,
  "type": "single",
  "to_number": "628123456789",
  "body": "Halo, ini pesan dari sistem penjualan.",
  "status": "sent",
  "sent_at": "2026-09-02T07:51:00+07:00",
  "created_at": "2026-09-02T07:50:59+07:00"
}
```

**Sukses — `200 OK`** (status `failed`)

```json
{
  "id": 42,
  "type": "single",
  "to_number": "628123456789",
  "body": "Halo, ini pesan dari sistem penjualan.",
  "status": "failed",
  "error_msg": "invalid JID: ...",
  "created_at": "2026-09-02T07:50:59+07:00"
}
```

**Field**

| Field | Tipe | Ada kapan |
|---|---|---|
| `id` | number | selalu |
| `type` | `"single"` \| `"template"` | selalu |
| `template_id` | number | hanya pesan bertipe `template` |
| `to_number` | string | selalu — nomor **setelah** normalisasi |
| `body` | string | selalu — body final setelah substitusi variabel |
| `status` | `"pending"` \| `"sent"` \| `"failed"` | selalu |
| `error_msg` | string | hanya saat `failed` |
| `sent_at` | string (RFC 3339) | hanya saat `sent` |
| `created_at` | string (RFC 3339) | selalu |

**Gagal**

| Kondisi | Status | Body |
|---|---|---|
| `:id` bukan angka | `400` | `{"error":"invalid id"}` |
| Tidak ada baris | `404` | `{"error":"not found"}` |
| Error MySQL | `500` | `{"error":"<pesan driver>"}` |

### Pola polling yang disarankan

```text
kirim  ──202──► simpan message_id
                    │
                    ▼
        GET /messages/:id  (tunggu 1–2 detik)
                    │
        ┌───────────┴───────────┐
     pending                 sent/failed
        │                       │
        └─ ulangi (maks ~30×)   └─ selesai, simpan hasil
```

Bila setelah ±60 detik masih `pending`, hentikan polling dan tandai "unknown" di aplikasi —
bisa jadi worker sedang menunggu (antrian penuh) atau WhatsApp sedang reconnect. Tidak ada
endpoint untuk membatalkan pesan yang sudah masuk antrian.

---

## 7. `GET /messages`

Riwayat pesan keluar, urut `created_at DESC`.

```http
GET /messages?to=628123456789&status=sent&limit=20
Authorization: Bearer <token>
```

| Query | Tipe | Default | Keterangan |
|---|---|---|---|
| `to` | string | — | Filter `to_number`, **harus sudah ternormalisasi** (`628...`, bukan `0812...`) |
| `status` | string | — | `pending` / `sent` / `failed`; nilai lain menghasilkan daftar kosong |
| `limit` | int | `50` | Nilai `<= 0` atau `> 100` diganti `50` |

**Sukses — `200 OK`**

```json
{
  "count": 2,
  "data": [
    {
      "id": 43,
      "type": "template",
      "template_id": 1,
      "to_number": "628123456789",
      "body": "Halo Budi, pesanan ORD-001 Anda sudah siap diambil.",
      "status": "sent",
      "sent_at": "2026-09-02T07:52:00+07:00",
      "created_at": "2026-09-02T07:51:59+07:00"
    },
    {
      "id": 42,
      "type": "single",
      "to_number": "628123456789",
      "body": "Halo, ini pesan dari sistem penjualan.",
      "status": "sent",
      "sent_at": "2026-09-02T07:51:00+07:00",
      "created_at": "2026-09-02T07:50:59+07:00"
    }
  ]
}
```

- `count` = jumlah elemen `data`, bukan total di database.
- Tidak ada pagination berbasis cursor/offset — hanya `limit`. Untuk data lama, sempitkan dengan
  filter `to` / `status`.
- Error MySQL → `500` dengan `{"error":"<pesan driver>"}`.

---

## 8. `GET /status`

Cek apakah perangkat WhatsApp sedang tertaut dan terhubung.

```json
{
  "connected": true,
  "phone": "628123456789"
}
```

```json
{
  "connected": false,
  "phone": ""
}
```

- `phone` adalah nomor perangkat setelah `+` (tanpa awalan `+`), kosong bila belum tertaut.
- Kondisi `connected: false` **tidak** selalu berarti QR perlu di-scan; bisa juga sedang reconnect.

---

## 9. `GET /qr`

Ambil QR code untuk menautkan perangkat baru. Memakai autentikasi Bearer yang sama.

**Belum tertaut, QR tersedia — `200 OK`**

```json
{
  "connected": false,
  "code": "2@AbCdEf...=="
}
```

`code` adalah **string mentah** yang harus dirender jadi QR (bukan data-URI PNG).
Halaman renderer bawaan ada di `GET /qr.html`.

**Sudah terhubung — `200 OK`**

```json
{ "connected": true }
```

**QR belum tersedia — `503 Service Unavailable`**

```json
{
  "connected": false,
  "error": "QR not available — not logged in or already connected"
}
```

QR di-generate ulang berkala oleh WhatsApp; polling tiap ~5–10 detik pada halaman renderer adalah
perilaku normal.

---

## 10. `POST /logout`

Memutus sesi WhatsApp (menghapus kredensial perangkat).

```json
{ "message": "logged out — restart server to scan new QR" }
```

Setelah logout, **QR tidak otomatis muncul** — server harus direstart agar `GetQRChannel`
dibuka kembali. Endpoint ini untuk ops, bukan untuk aplikasi bisnis. Error → `500`
`{"error":"<pesan>"}`.

---

## 11. Error yang Berasal dari Router (bukan JSON)

Beberapa jalur gagal tidak melewati handler, sehingga body-nya bukan JSON. Integrator harus
tetap memeriksa status code, dan hanya parsing JSON bila `Content-Type` memang JSON.

| Kondisi | Status | Body / perilaku |
|---|---|---|
| Path tidak dikenal | `404` | `404 page not found` (`text/plain`) |
| Method salah untuk path yang ada (mis. `GET /send/message`) | `404` — **bukan 405** | `404 page not found` (`text/plain`) |
| Path dengan trailing slash (mis. `POST /send/message/`) | `301` (GET) / `307` (POST lain) | Redirect ke path tanpa slash; banyak HTTP client mengikuti otomatis |
| CORS preflight `OPTIONS` | `204` | Header CORS dikembalikan; preflight **tidak** butuh token |

Karena method salah mengembalikan `404`, jangan simpulkan "endpoint tidak ada" dari 404 saja —
periksa juga method yang dipakai.

### CORS

CORS diaktifkan untuk semua origin (`Access-Control-Allow-Origin: *`), method
`GET, POST, OPTIONS`, header `Authorization, Content-Type`. Cookie/kredensial tidak dipakai —
autentikasi murni lewat header `Authorization`. Integrasi server-to-server tidak terpengaruh CORS.

### Retry

| Status | Retry? | Catatan |
|---|---|---|
| `202` | **jangan** | Sudah masuk antrian; retry = pesan ganda |
| `400` | jangan | Perbaiki request dulu |
| `401` | jangan | Perbaiki/rotasi token |
| `404` | jangan | Cek path & method |
| `500` | ya | Backoff eksponensial, idempoten aman (tidak ada pesan terkirim) |
| `503` (`/qr`) | ya | Normal saat QR belum siap |
| Timeout / connection reset | hati-hati | Request mungkin sudah diproses; cek `GET /messages` sebelum retry |

---

## 12. Kontrak untuk Pesan Masuk (inbound)

Engine juga menyimpan pesan WhatsApp masuk ke tabel `incoming_messages` (MySQL), tetapi
**belum ada endpoint HTTP untuk membacanya** dan belum ada webhook ke aplikasi lain.

Saat ini satu-satunya cara aplikasi membaca pesan masuk adalah query langsung ke database:

```sql
SELECT id, wa_message_id, sender, chat, body, is_group, received_at
FROM incoming_messages
WHERE id > ?                 -- cursor berbasis id, aman untuk polling inkremental
ORDER BY id ASC
LIMIT 100;
```

| Kolom | Keterangan |
|---|---|
| `wa_message_id` | ID pesan dari WhatsApp, `UNIQUE` — dipakai untuk deduplikasi |
| `sender` | JID pengirim, mis. `628123456789@s.whatsapp.net` |
| `chat` | JID chat; untuk grup berformat `...@g.us` |
| `body` | Teks pesan (pesan non-teks tidak disimpan) |
| `is_group` | `true` bila berasal dari grup |
| `received_at` | Waktu engine menerima pesan |

Karakteristik yang perlu diketahui integrator:

- Hanya pesan teks yang masuk; media/audio/dokumen diabaikan.
- Pesan yang dikirim dari nomor gateway sendiri (`IsFromMe`) tidak disimpan.
- Insert bersifat idempotent per `wa_message_id` (`ON DUPLICATE KEY`), jadi event berulang aman.
- `sender`/`chat` berisi JID, bukan nomor polos — perlu di-strip sendiri (`split('@')[0]`).
- Engine juga membalas perintah bot (`!ping`, `!waktu`, `!echo`, `!stat`, `!help`) pada chat
  1-on-1. Ini perilaku bawaan yang **tidak bisa dimatikan lewat API** — kalau aplikasi juga
  memproses pesan masuk, pastikan tidak bentrok dengan prefix `!`.

> **Roadmap:** webhook/callback HTTP untuk pesan masuk belum tersedia. Bila dibutuhkan,
> integrasi lewat polling tabel di atas sambil menunggu endpoint inbound disediakan.

---

## 13. Contoh Integrasi

### curl

```bash
BASE=https://wa.example.com/wa
TOKEN=your-token

# Kirim pesan
curl -sS -X POST "$BASE/send/message" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"to":"08123456789","message":"Halo dari API"}'
# → {"message_id":42,"status":"pending"}

# Cek status
curl -sS "$BASE/messages/42" -H "Authorization: Bearer $TOKEN"
# → {"id":42,"type":"single","to_number":"628123456789","body":"Halo dari API","status":"sent",...,"sent_at":"..."}
```

### PHP (Laravel-ish)

```php
$resp = Http::withToken(config('wa.token'))
    ->post(config('wa.base_url').'/send/message', [
        'to'      => $order->phone,
        'message' => "Pesanan {$order->code} sedang diproses.",
    ]);

if ($resp->status() !== 202) {
    throw new RuntimeException('WA gateway: '.$resp->status().' '.$resp->body());
}

$order->update(['wa_message_id' => $resp->json('message_id')]);
// status akhir diambil job terjadwal: GET /messages/{id}
```

### Node.js

```js
const res = await fetch(`${BASE}/send/template`, {
  method: 'POST',
  headers: {
    Authorization: `Bearer ${TOKEN}`,
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    to: '08123456789',
    template: 'order_ready',
    variables: { name: 'Budi', order_id: 'ORD-001' },
  }),
});

if (res.status !== 202) throw new Error(`WA gateway ${res.status}: ${await res.text()}`);
const { message_id } = await res.json();
```

---

## 14. Batasan & Hal yang Belum Ada

Daftar ini agar integrator tidak mengasumsikan fitur yang belum diimplementasikan:

- **Satu nomor WhatsApp** per instance — tidak ada multi-tenant/multi-perangkat dalam satu server.
- **Tidak ada endpoint CRUD template** dan **tidak ada endpoint manajemen token**.
- **Tidak ada webhook/event push**; status harus di-polling.
- **Tidak ada status delivered/read**, tidak ada media (gambar/dokumen/audio), tidak ada pesan grup keluar.
- **Tidak ada batching**, prioritas, penjadwalan, atau pembatalan pesan.
- **Tidak ada rate limiting** di sisi gateway — pengaturan laju pengiriman menjadi tanggung jawab aplikasi pemanggil
  (WhatsApp dapat membatasi/memblokir nomor yang mengirim terlalu agresif).
- Hanya pesan **teks** (`Conversation`); format WhatsApp (bold/italic) harus dikirim sebagai
  karakter markup biasa di dalam `message`.
