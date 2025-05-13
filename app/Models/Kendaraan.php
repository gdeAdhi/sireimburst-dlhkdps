<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'tb_kendaraan';
    protected $fillable = [
        'id_tipe',
        'id_merk',
        'nama',
        'no_polisi',
        'warna',
        'bahan_bakar',
        'konsumsi',
        'status'
    ];

    public function tipe()
    {
        return $this->belongsTo(TipeKendaraan::class, 'id_tipe');
    }

    public function merk()
    {
        return $this->belongsTo(MerkKendaraan::class, 'id_merk');
    }
}
