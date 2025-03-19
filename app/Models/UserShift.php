<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserShift extends Model
{
    use HasFactory;

    protected $table = 'user_shifts';
    protected $fillable = ['user_id', 'shift_id', 'shift_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
