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


    // protected $dates = [
    //     'start_time',
    //     'end_time',
    // ];

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

    // use HasFactory;
    // protected $fillable = [
    //     'user_id',
    //     'presensi_id',

    //     'start_time_shift1',
    //     'end_time_shift1',
    //     'total_minutes_shift1',

    //     'start_time_shift2',
    //     'end_time_shift2',
    //     'total_minutes_shift2',

    //     'start_time_shift3',
    //     'end_time_shift3',
    //     'total_minutes_shift3',
    // ];


    // protected $dates = [
    //     'start_time_shift1',
    //     'end_time_shift1',
    //     'start_time_shift2',
    //     'end_time_shift2',
    //     'start_time_shift3',
    //     'end_time_shift3',
    // ];

    // // Relasi ke User
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // public function presensi()
    // {
    //     return $this->belongsTo(Presence::class, 'presensi_id');
    // }

    // Konstanta untuk shift (update utk lembur pershift)
    // const SHIFT_1 = 1;
    // const SHIFT_2 = 2;
    // const SHIFT_3 = 3;

    // protected $fillable = [
    //     'user_id',
    //     'presensi_id',

    //     'start_time_shift1',
    //     'end_time_shift1',
    //     'total_minutes_shift1',

    //     'start_time_shift2',
    //     'end_time_shift2',
    //     'total_minutes_shift2',

    //     'start_time_shift3',
    //     'end_time_shift3',
    //     'total_minutes_shift3',
    // ];

    // protected $dates = [
    //     'start_time_shift1',
    //     'end_time_shift1',
    //     'start_time_shift2',
    //     'end_time_shift2',
    //     'start_time_shift3',
    //     'end_time_shift3',
    // ];

    // Relasi ke User
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // // Relasi ke tabel presensi
    // public function presensi()
    // {
    //     return $this->belongsTo(Presence::class, 'presensi_id');
    // }

    // // Helper function: Total semua menit lembur
    // public function totalMinutesAllShifts()
    // {
    //     return ($this->total_minutes_shift1 ?? 0) +
    //         ($this->total_minutes_shift2 ?? 0) +
    //         ($this->total_minutes_shift3 ?? 0);
    // }

    // // Optional: Helper untuk ambil data per shift
    // public function getShiftData($shift)
    // {
    //     switch ($shift) {
    //         case self::SHIFT_1:
    //             return [
    //                 'start_time' => $this->start_time_shift1,
    //                 'end_time' => $this->end_time_shift1,
    //                 'total_minutes' => $this->total_minutes_shift1,
    //             ];
    //         case self::SHIFT_2:
    //             return [
    //                 'start_time' => $this->start_time_shift2,
    //                 'end_time' => $this->end_time_shift2,
    //                 'total_minutes' => $this->total_minutes_shift2,
    //             ];
    //         case self::SHIFT_3:
    //             return [
    //                 'start_time' => $this->start_time_shift3,
    //                 'end_time' => $this->end_time_shift3,
    //                 'total_minutes' => $this->total_minutes_shift3,
    //             ];
    //         default:
    //             return null;
    //     }
    // }
}
