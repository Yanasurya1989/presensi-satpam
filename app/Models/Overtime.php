<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_time',
        'end_time',
        'total_minutes',
    ];

    protected $dates = [
        'start_time',
        'end_time',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function presensi()
    {
        return $this->belongsTo(Presence::class, 'presensi_id');
    }
}
