<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hive extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration', 'status', 'size', 'race',
        'queenYear', 'temperature', 'latitude', 'longitude'
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

}
