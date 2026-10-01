# Webhook WM Service — Hasil Watermark Akta BHPNU

Dokumen ini menjelaskan cara WM Service melaporkan hasil proses watermark akta
ke SIPINTER.

- **Endpoint** : `POST /api/webhook/wm/akta`
- **Controller** : `app/Http/Controllers/Api/WatermarkWebhookController.php`
- **Autentikasi** : Bearer token pada tabel `access_token` (`name = 'wm-service'`)
- **Format** : JSON (`Content-Type: application/json`)

Ambil token yang aktif dengan:

```sql
SELECT token FROM access_token WHERE name = 'wm-service';
```

Ganti token dengan:

```sql
UPDATE access_token SET token = UUID() WHERE name = 'wm-service';
```

---

## Alur

1. Admin menekan tombol approve di tab **SEDANG DIPROSES** pada halaman BHPNU.
2. SIPINTER mengirim request watermark ke WM Service
   (`POST {WM_SERVICE_URL}/wm/akta`), lalu menyimpan
   `akta_status = 'processing'` dan `akta_file` yang diharapkan.
3. WM Service memproses dan **menulis file hasil ke folder storage SIPINTER**
   (lihat bagian Path di bawah).
4. Setelah selesai, WM Service memanggil webhook ini untuk melaporkan hasilnya.
5. SIPINTER mengubah `akta_status` menjadi `success` atau `failed`. Pada halaman
   BHPNU, status `success` memunculkan tombol **Lihat** (modal PDF).

---

## Request Body

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `bhpnu_id` | integer | ya | `id_bhpnu` permohonan yang diproses |
| `status` | string | ya | Hanya `success` atau `failed` |
| `akta_path` | string | tidak | Path file hasil watermark. Boleh absolut atau relatif (lihat di bawah) |
| `message` | string | tidak | Keterangan tambahan; disimpan ke kolom `akta_note` |

### Contoh — success

```json
{
    "bhpnu_id": 181,
    "status": "success",
    "akta_path": "/var/www/sipinter/storage/app/bhpnu-doc/akta/akta-2184215-SMKS-MAARIF-NU-2-SIRAMPOG.pdf",
    "message": "Watermark akta berhasil"
}
```

### Contoh — failed

```json
{
    "bhpnu_id": 181,
    "status": "failed",
    "message": "Gagal watermark: file akta tidak ditemukan di path yang dikirim"
}
```

File contoh tersedia di folder ini:

| File | Isi |
|---|---|
| `request-success.json` | Status success dengan path absolut |
| `request-success-relative-path.json` | Status success dengan path relatif |
| `request-minimal.json` | Hanya field wajib (`bhpnu_id`, `status`) |
| `request-failed.json` | Status failed |
| `send.sh` | Script bash siap pakai |

---

## Path Akta

Nilai `akta_path` boleh dikirim dalam salah satu bentuk berikut — keduanya
dinormalkan otomatis oleh aplikasi:

| Bentuk | Contoh |
|---|---|
| Absolut (path di server SIPINTER) | `/var/www/sipinter/storage/app/bhpnu-doc/akta/akta-xxx.pdf` |
| Relatif terhadap `storage/app` | `bhpnu-doc/akta/akta-xxx.pdf` |
| Dikosongkan | Aplikasi memakai `akta_file` yang sudah tersimpan saat request dikirim |

**Yang penting:** `akta_path` hanya dipakai untuk mengambil **nama file**-nya.
File wajib benar-benar ada di:

```
storage/app/bhpnu-doc/akta/<nama-file>
```

WM Service menulis hasil watermark langsung ke folder tersebut, karena aplikasi
ini yang menentukan `output_path` saat mengirim request ke WM Service.

---

## Response

### 200 — berhasil diperbarui

```json
{
    "success": true,
    "message": "Status akta berhasil diperbarui menjadi success",
    "data": {
        "bhpnu_id": 181,
        "akta_status": "success",
        "akta_file": "akta-2184215-SMKS-MAARIF-NU-2-SIRAMPOG.pdf",
        "akta_processed_at": "2026-09-30 07:09:18"
    }
}
```

### 422 — laporan success tapi file tidak ditemukan

Aplikasi memverifikasi file sebelum menandai `success`, supaya halaman BHPNU
tidak pernah menampilkan tombol Lihat untuk dokumen yang tidak ada. Status pada
database **tidak diubah**.

```json
{
    "success": false,
    "message": "Laporan sukses diterima tetapi file akta belum ditemukan pada folder storage",
    "data": {
        "bhpnu_id": 181,
        "akta_status": "processing",
        "akta_file": "akta-2184215-SMKS-MAARIF-NU-2-SIRAMPOG.pdf"
    }
}
```

Pastikan WM Service sudah selesai menulis file **sebelum** memanggil webhook,
dan nama file hasilnya sama dengan `output_path` yang dikirim SIPINTER.

### 422 — data tidak valid

```json
{
    "success": false,
    "message": "Data yang dikirim tidak valid",
    "errors": {
        "status": ["The selected status is invalid."]
    }
}
```

### 401 — token salah atau tidak dikirim

```json
{
    "message": "Invalid authorization token"
}
```

### 404 — `bhpnu_id` tidak ditemukan

```json
{
    "success": false,
    "message": "Data permohonan BHPNU tidak ditemukan"
}
```

---

## Cara Menjalankan

### curl

```bash
BASE_URL=http://localhost:8000
TOKEN=$(mysql -u root -N -e "SELECT token FROM siap_lpmaarif.access_token WHERE name='wm-service'")

curl -X POST "${BASE_URL}/api/webhook/wm/akta" \
    -H "Authorization: Bearer ${TOKEN}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    --data-binary @request-success.json
```

### script bash

```bash
cd docs/webhook-wm-akta

# success (default)
BASE_URL=http://localhost:8000 TOKEN=xxxx ./send.sh

# failed
BASE_URL=http://localhost:8000 TOKEN=xxxx ./send.sh failed
```

### PowerShell

```powershell
$body = Get-Content ./request-success.json -Raw
Invoke-RestMethod -Method Post -Uri "http://localhost:8000/api/webhook/wm/akta" `
    -Headers @{ Authorization = "Bearer $env:TOKEN" } `
    -ContentType "application/json" -Body $body
```

---

## Verifikasi

Cek kolom akta di database:

```sql
SELECT id_bhpnu, akta_status, akta_file, akta_note, akta_processed_at
FROM bhpnu
WHERE id_bhpnu = 181;
```

Cek file hasil watermark:

```bash
ls -la storage/app/bhpnu-doc/akta/
```

Log setiap panggilan webhook maupun request ke WM Service tercatat dengan prefix
`[WATERMARK]`:

```bash
grep WATERMARK storage/logs/laravel.log | tail -20
```

Request body yang dikirim ke WM Service juga dicatat, sehingga tetap bisa
diperiksa walaupun koneksinya gagal (mis. masalah SSL/DNS/timeout).
