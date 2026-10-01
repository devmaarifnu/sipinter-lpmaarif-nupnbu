<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BHPNU extends Model
{
    use HasFactory;
    protected $table = 'bhpnu';
    protected $primaryKey = 'id_bhpnu';
    protected $hidden = ['created_at', 'updated_at'];
    protected $fillable = [
        'id_user',
        'bukti_bayar',
        'akta_file',
        'akta_status',
        'akta_note',
        'akta_requested_at',
        'akta_processed_at',
        'no_resi',
        'tanggal',
        'tgl_dikirim',
        'tgl_expired',
        'status',
    ];
    protected $casts = [
        'akta_processed_at' => 'datetime',
        'akta_requested_at' => 'datetime',
    ];
    public function satpen()
    {
        return $this->belongsTo(Satpen::class, 'id_user', 'id_user');
    }
    public function bhpnustatus()
    {
        return $this->hasMany(BHPNUStatus::class, 'id_bhpnu');
    }
}
