<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_time', 'end_time'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_shifts')->withPivot('shift_date')->withTimestamps();
    }
}
