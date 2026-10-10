-- ---------------------------------------------------------------------------
-- Fitur Watermark Akta BHPNU (WM Service)
-- Tanggal   : 2026-09-30
--
-- Berisi seluruh perubahan database yang dibutuhkan fitur watermark akta:
--   1. Kolom baru pada tabel `bhpnu`
--   2. Pengaturan aplikasi baru pada tabel `settings` (file akta + URL WM Service)
--   3. Token untuk WM Service memanggil webhook
--
-- Jalankan dengan:
--   mysql -u root siap_lpmaarif < database/sql/2026_09_30_add_akta_watermark.sql
--
-- Aman dijalankan berulang kali (idempotent): kolom dan baris dicek lebih dulu.
-- ---------------------------------------------------------------------------

-- 1. Kolom hasil watermark pada tabel bhpnu -----------------------------------
-- akta_file         : nama file hasil watermark di storage/app/bhpnu-doc/akta
--                     NULL berarti belum siap -> UI menampilkan "File is Processing"
-- akta_status       : processing | success | failed (NULL = belum pernah diminta)
-- akta_note         : pesan terakhir dari proses watermark
-- akta_requested_at : waktu permintaan watermark dikirim ke WM Service
-- akta_processed_at : waktu watermark berhasil

SET @dbname = DATABASE();

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'bhpnu' AND COLUMN_NAME = 'akta_file') = 0,
    'ALTER TABLE `bhpnu` ADD COLUMN `akta_file` VARCHAR(255) COLLATE utf8mb4_unicode_ci NULL AFTER `bukti_bayar`',
    'SELECT ''kolom akta_file sudah ada'' AS info'));
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'bhpnu' AND COLUMN_NAME = 'akta_status') = 0,
    'ALTER TABLE `bhpnu` ADD COLUMN `akta_status` ENUM(''processing'',''success'',''failed'') COLLATE utf8mb4_unicode_ci NULL AFTER `akta_file`',
    'SELECT ''kolom akta_status sudah ada'' AS info'));
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'bhpnu' AND COLUMN_NAME = 'akta_note') = 0,
    'ALTER TABLE `bhpnu` ADD COLUMN `akta_note` VARCHAR(255) COLLATE utf8mb4_unicode_ci NULL AFTER `akta_status`',
    'SELECT ''kolom akta_note sudah ada'' AS info'));
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'bhpnu' AND COLUMN_NAME = 'akta_requested_at') = 0,
    'ALTER TABLE `bhpnu` ADD COLUMN `akta_requested_at` TIMESTAMP NULL DEFAULT NULL AFTER `akta_note`',
    'SELECT ''kolom akta_requested_at sudah ada'' AS info'));
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'bhpnu' AND COLUMN_NAME = 'akta_processed_at') = 0,
    'ALTER TABLE `bhpnu` ADD COLUMN `akta_processed_at` TIMESTAMP NULL DEFAULT NULL AFTER `akta_requested_at`',
    'SELECT ''kolom akta_processed_at sudah ada'' AS info'));
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

/*
 * Kolom akta_path dibuang. Sebelumnya dipakai untuk mencatat path file akta
 * asli, tetapi nilainya hanya ditulis dan tidak pernah dibaca aplikasi.
 * Penghapusan ini juga membersihkan database yang sempat menjalankan versi
 * awal file ini.
 */
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'bhpnu' AND COLUMN_NAME = 'akta_path') > 0,
    'ALTER TABLE `bhpnu` DROP COLUMN `akta_path`',
    'SELECT ''kolom akta_path sudah tidak ada'' AS info'));
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 2. Pengaturan aplikasi ------------------------------------------------------
-- akta_template : nama file template akta di storage/app/templates,
--                 diunggah melalui halaman Pengaturan (admin).
--                 Path sumber, path output, dan URL WM Service dibaca
--                 aplikasi ini langsung karena WM Service mengakses folder
--                 storage aplikasi.

INSERT INTO `settings` (`describe`, `lookup`, `value`, `created_at`, `updated_at`)
SELECT 'FILE AKTA BHPNU', 'akta_template', '', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `settings` WHERE `lookup` = 'akta_template');

-- 3. Token webhook untuk WM Service ------------------------------------------
-- GANTI nilai token di bawah dengan UUID milik Anda, atau gunakan nilai yang
-- dihasilkan baris ini. Token dipakai WM Service pada header:
--   Authorization: Bearer <token>
-- saat memanggil POST /api/webhook/wm/akta (lihat app/Http/Controllers/Api/WatermarkWebhookController.php).
--
-- Untuk melihat/mengganti token setelah dijalankan:
--   SELECT token FROM access_token WHERE name = 'wm-service';
--   UPDATE access_token SET token = UUID() WHERE name = 'wm-service';

INSERT INTO `access_token` (`name`, `token`, `hashtype`, `expires_at`, `created_at`, `updated_at`)
SELECT 'wm-service', UUID(), 'uuid', NULL, NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `access_token` WHERE `name` = 'wm-service');

SELECT `describe`, `lookup`, `value` FROM `settings` WHERE `lookup` = 'akta_template';
SELECT `name`, `token` FROM `access_token` WHERE `name` = 'wm-service';

-- ---------------------------------------------------------------------------
-- Verifikasi
-- ---------------------------------------------------------------------------
-- SHOW COLUMNS FROM `bhpnu` LIKE 'akta%';
