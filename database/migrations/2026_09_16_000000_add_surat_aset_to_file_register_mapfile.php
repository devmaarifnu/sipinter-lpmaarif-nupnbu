<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan nilai 'surat_aset' pada enum mapfile untuk menampung
     * file Surat Keterangan Status Aset.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE file_register MODIFY mapfile ENUM('surat_permohonan', 'rekom_pc', 'rekom_pw', 'surat_aset') NOT NULL");
    }

    /**
     * Reverse the migrations.
     * Sengaja dibiarkan kosong agar rollback tidak menghapus nilai enum
     * 'surat_aset' yang datanya sudah tersimpan.
     */
    public function down(): void
    {
        //
    }
};
