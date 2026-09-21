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
     * Get a single file register entry by its document type.
     *
     * Replaces order-based access ($filereg[0], $filereg[1], ...) because row
     * order is no longer stable now that the cabang/wilayah recommendations
     * are disabled.
     *
     * @param  \Illuminate\Support\Collection|array  $filereg
     */
    public static function findByMapfile($filereg, string $mapfile): ?self
    {
        return collect($filereg)->firstWhere('mapfile', $mapfile);
    }
}
