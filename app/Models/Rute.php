<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'tb_rute';
    protected $fillable = [
        'id_rute',
        'id_perjalanan'
    ];
}
