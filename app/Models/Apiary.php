<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apiary extends Model
{
    use HasFactory;

    protected $fillable = ['latitude', 'longitude', 'temperature'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function harvests()
    {
        return $this->belongsToMany(Harvest::class);
    }

    public function transhumances()
    {
        return $this->belongsToMany(Transhumance::class);
    }

    public function hives()
    {
        return $this->hasMany(Hive::class);
    }
}
