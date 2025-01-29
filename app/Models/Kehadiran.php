<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    use HasFactory;
    protected $table = "kehadiran";
    protected $primarykey = "id";
    protected $fillable = [
        'id',
        'user_id',
        'tanggal',
        'jam_masuk',
        'Jam_keluar',
        'jam_kerja',
    ];

    /**
     * Get the user that owns the Kehadiran
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
