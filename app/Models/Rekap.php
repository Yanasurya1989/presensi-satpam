<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rekap extends Model
{
    use HasFactory;
    protected $table = 'rekap';

    protected $fillable = [
        'id_user',
        'shalat_wajib',
        'qiyamul_lail',
        'tilawah',
        'duha'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
