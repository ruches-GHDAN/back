<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transhumance extends Model
{
    use HasFactory;

    protected $fillable = [
        'oldLatitude',
        'oldLongitude',
        'reason',
        'date',
        'apiary_id'
    ];

    public function apiary()
    {
        return $this->belongsTo(Apiary::class);
    }
}
