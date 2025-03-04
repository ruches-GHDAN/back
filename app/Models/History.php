<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = ['apiary_id', 'title', 'date', 'description'];

    public function apiary()
    {
        return $this->belongsTo(Apiary::class);
    }
}
