<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportPerjalanan extends Model
{
    use HasFactory;
    protected $table = 'tb_reportperjalanan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_user',
        'id_perjalanan',
        'tanggal',
        'bukti',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function perjalanan()
    {
        return $this->belongsTo(Perjalanan::class, 'id_perjalanan');
    }
}
