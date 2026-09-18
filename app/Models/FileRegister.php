<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileRegister extends Model
{
    use HasFactory;
    protected $table = 'file_register';
    protected $primaryKey = 'id_file';
    protected $hidden = ['created_at', 'updated_at'];
    protected $fillable = [
        'id_satpen',
        'daerah',
        'mapfile',
        'nm_lembaga',
        'nomor_surat',
        'tgl_surat',
        'filesurat',
    ];

    /**
     * Ambil satu file register berdasarkan jenis dokumennya.
     *
     * Dipakai untuk menggantikan akses berdasarkan urutan ($filereg[0], $filereg[1], ...)
     * karena urutan baris berubah ketika rekomendasi cabang/wilayah dinonaktifkan.
     *
     * @param  \Illuminate\Support\Collection|array  $filereg
     */
    public static function dataUntuk($filereg, string $mapfile): ?self
    {
        return collect($filereg)->firstWhere('mapfile', $mapfile);
    }
}
