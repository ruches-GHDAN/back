<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'temperature',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function harvests()
    {
        return $this->hasMany(Harvest::class);
    }

    public function transhumances()
    {
        return $this->hasMany(Transhumance::class);
    }

    public function hives()
    {
        return $this->hasMany(Hive::class);
    }
}
