<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeKendaraan extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'tb_tipeKendaraan';
    protected $fillable = [
        'nama',
        'status'
    ];
}
