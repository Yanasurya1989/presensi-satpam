<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lembur extends Model
{
    use HasFactory;

    protected $table = 'lembur';

    protected $fillable = [
        'user_id',
        'tanggal',
        'mulai_lembur_satu',
        'akhir_lembur_satu',
        'mulai_lembur_dua',
        'akhir_lembur_dua',
        'mulai_lembur_tiga',
        'akhir_lembur_tiga',
        'total_lembur',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
