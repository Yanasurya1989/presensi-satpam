<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserShift extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'shift_id', 'shift_date'];
}
