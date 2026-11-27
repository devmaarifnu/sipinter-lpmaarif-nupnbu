<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Notifikasi WhatsApp
    |--------------------------------------------------------------------------
    |
    | Integrasi ke WhatsApp Gateway (kontrak API: docs/API.md). Nomor admin
    | dibaca dari .env agar bisa diubah tanpa deploy; lebih dari satu nomor
    | dipisahkan dengan koma. Nomor operator diambil dari kolom satpen.telpon.
    |
    */

    /*
    | filter_var dipakai agar string "false"/"0"/"off" benar-benar mematikan
    | fitur — `(bool) env(...)` akan menganggap "false" sebagai true. Nilai
    | kosong (WA_NOTIFICATION_ENABLED=) tetap dianggap aktif.
    */
    'enabled' => filter_var(
        blank(env('WA_NOTIFICATION_ENABLED')) ? true : env('WA_NOTIFICATION_ENABLED'),
        FILTER_VALIDATE_BOOL
    ),

    'base_url' => env('WA_GATEWAY_URL'),

    'token' => env('WA_GATEWAY_TOKEN'),

    'timeout' => (int) env('WA_GATEWAY_TIMEOUT', 10),

    /*
    | Daftar nomor WhatsApp admin (penerima notifikasi pengajuan/registrasi).
    | Contoh: WA_ADMIN_NUMBERS=08123456789,08129876543
    */
    'admin_numbers' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('WA_ADMIN_NUMBERS', ''))
    ))),

    /*
    | Channel log khusus notifikasi WhatsApp (lihat config/logging.php).
    */
    'log_channel' => env('WA_LOG_CHANNEL', 'whatsapp'),

    'app_name' => env('WA_APP_NAME', "SIPINTER LP Ma'arif NU"),

];
