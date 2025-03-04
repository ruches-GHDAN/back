<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Hive extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration',
        'status',
        'size',
        'race',
        'queenYear',
        'temperature',
        'latitude',
        'longitude',
        'apiary_id'
    ];

    public function apiary()
    {
        return $this->belongsTo(Apiary::class);
    }

    public function diseases()
    {
        return $this->belongsToMany(Disease::class);
    }

    public function foods()
    {
        return $this->belongsToMany(Food::class);
    }

    public function histories(): HasManyThrough
    {
        return $this->hasManyThrough(History::class, Apiary::class);
    }
}
