<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harvest extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'quantity', 'apiary_id'];

    public function apiary()
    {
        return $this->belongsTo(Apiary::class);
    }
}
