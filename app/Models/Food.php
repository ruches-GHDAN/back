<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'quantity', 'date'];

    public function hives()
    {
        return $this->belongsToMany(Hive::class);
    }
}
