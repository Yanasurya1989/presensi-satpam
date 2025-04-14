<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'user_id',
        'email',
        'password',
        'foto',
        'role_id',
        'rekap_id',
        'divisi'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }


    public function shifts()
    {
        return $this->belongsToMany(Shift::class, 'user_shifts')->withPivot('shift_date')->withTimestamps();
    }

    // public function shifts()
    // {
    //     return $this->belongsToMany(Shift::class, 'user_shifts')
    //         ->withPivot('shift_date')
    //         ->withTimestamps();
    // }

    // public function shifts()
    // {
    //     return $this->belongsToMany(Shift::class, 'user_shifts')
    //         ->withPivot('week_start', 'week_end');
    // }


    public function report()
    {
        return $this->hasMany(Report::class, 'id_user');
        // return $this->hasMany(Report::class);
    }

    /**
     * Get the user that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */


    // public function rekap()
    // {
    //     return $this->hasMany(Rekap::class);
    // }


    /**
     * Get the user that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class);
    }

    public function presences()
    {
        return $this->hasMany(Presence::class, 'user_id');
    }

    public function overtimes()
    {
        return $this->hasMany(\App\Models\Overtime::class, 'user_id');
    }
}
