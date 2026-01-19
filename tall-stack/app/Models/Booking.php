<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'num_musicians',
        'total_price',
        'status',
        'special_requests',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
