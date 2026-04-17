<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeMatchNotification extends Model
{
    protected $fillable = [
        'user_id',
        'stadium_id',
        'fixture_id',
        'match_date',
    ];

    protected $casts = [
        'match_date' => 'datetime',
    ];
}