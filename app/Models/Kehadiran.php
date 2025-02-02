<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    use HasFactory;
    protected $table = "selfi_presensi";
    protected $primarykey = "id";
    protected $fillable = [
        'user_id',
        'tgl_presensi',
        'jam_in',
        'jam_out',
        'poto_in',
        'poto_out',
        'created_at',
        'updated_at'
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
