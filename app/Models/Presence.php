<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    protected $table = 'presence'; // <- penting karena tidak pakai default 'presences'
    protected $fillable = ['user_id', 'tanggal', 'jam',  'jam_2', 'photo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function presences()
    {
        return $this->hasMany(Presence::class, 'user_id');
    }


    public function lembur()
    {
        return $this->hasOne(Overtime::class, 'presensi_id');
    }
}
