<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role',
        'firstName',
        'lastName',
        'email',
        'password',
    ];

    public function apiaries()
    {
        return $this->hasMany(Apiary::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function hives(): HasManyThrough {
        return $this->hasManyThrough(Hive::class, Apiary::class);
    }

    public function harvests(): HasManyThrough {
        return $this->hasManyThrough(Harvest::class, Apiary::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
