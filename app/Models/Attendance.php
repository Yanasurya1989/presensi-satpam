<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'presensi'; // Sesuaikan dengan nama tabel
    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'photo',
        'created_at',
        'updated_at'
    ]; // Pastikan kolom yang dapat diisi

}
