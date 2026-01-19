<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Musician extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'bio',
        'instruments',
        'music_styles',
        'hourly_rate',
        'photo',
        'status',
    ];

    protected $casts = [
        'instruments' => 'array',
        'music_styles' => 'array',
        'hourly_rate' => 'decimal:2',
    ];
}
