<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'has_link',
        'display_order',
        'status',
    ];

    protected $casts = [
        'has_link' => 'boolean',
    ];

    /**
     * Scope to get only active FAQs ordered by display_order
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->orderBy('display_order');
    }
}
