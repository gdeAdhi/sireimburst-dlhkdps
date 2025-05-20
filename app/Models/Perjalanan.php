<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perjalanan extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'tb_perjalanan';
    protected $fillable = [
        'id_rute',
        'id_kendaraan',
        'id_user',
        'jarak',
        'kalkulasi',
        'bobot',
        'kategori',
        'faktor_beban',
        'status',
    ];

    public function rute()
    {
        return $this->belongsToMany(RutePerjalanan::class, 'tb_rute', 'id_perjalanan', 'id_rute');    
    }
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'id_kendaraan');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
