<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Presensi extends Model
{
    use HasFactory;
    protected $table = 'presensi';

    protected $fillable = [
        'datang',
        'pulang',
        'user_id'
    ];
}
