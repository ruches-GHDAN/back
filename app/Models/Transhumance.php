<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transhumance extends Model
{
    use HasFactory;

    protected $fillable = ['locate', 'reason', 'date'];

    public function apiaries()
    {
        return $this->belongsToMany(Apiary::class);
    }
}
