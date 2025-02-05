<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'nbToClean',
        'nbClean',
        'nbInUse',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
