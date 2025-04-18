<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inval extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'time_start',
        'time_end',
        'pengganti',
    ];
}
