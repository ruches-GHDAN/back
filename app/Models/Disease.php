<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'treatment', 'dateStart', 'dateEnd'];

    public function hives()
    {
        return $this->belongsToMany(Hive::class);
    }
}
