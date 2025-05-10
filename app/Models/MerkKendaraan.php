<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerkKendaraan extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'tb_merkkendaraan';
    protected $fillable = [
        'nama',
        'status'
    ];
}
