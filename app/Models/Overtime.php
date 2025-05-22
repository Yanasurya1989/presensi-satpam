<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'presensi_id',
        'start_time',
        'end_time',
        'total_minutes',
        'start_time_shift1',
        'end_time_shift1',
        'total_minutes_shift1',
        'start_time_shift2',
        'end_time_shift2',
        'total_minutes_shift2',
        'start_time_shift3',
        'end_time_shift3',
        'total_minutes_shift3',
    ];

    protected $dates = [
        'start_time_shift1',
        'end_time_shift1',
        'start_time_shift2',
        'end_time_shift2',
        'start_time_shift3',
        'end_time_shift3',
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
