<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarBooking extends Model
{
    protected $fillable = [
        'selected_date',
        'selected_time',
        'name',
        'company',
        'email',
        'phone',
        'message',
        'status',
        'company_research',
    ];

    protected function casts(): array
    {
        return [
            'selected_date' => 'date',
            'selected_time' => 'datetime',
            'company_research' => 'array',
        ];
    }
}
