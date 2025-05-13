<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutePerjalanan extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'tb_ruteperjalanan';
    protected $fillable = [
        'nama',
        'alamat',
        'longitude',
        'latitude',
        'status'
    ];

    public function perjalanan()
    {
        return $this->belongsToMany(Perjalanan::class, 'tb_rute', 'id_rute', 'id_perjalanan');
    }

}
