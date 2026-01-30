<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarBooking extends Model
{
    protected $fillable = [
        'selected_date',
        'selected_time',
        'name',
        'email',
        'phone',
        'message',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'selected_date' => 'date',
            'selected_time' => 'datetime',
        ];
    }
}
