<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;
    protected $table = 'report';

    protected $fillable = [
        'id_user',
        'tanggal',
        'shalat_wajib',
        'qiyamul_lail',
        'tilawah',
        'duha',
        'mendoakan_siswa'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
