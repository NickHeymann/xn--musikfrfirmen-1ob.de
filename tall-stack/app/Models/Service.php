<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title',
        'text',
        'highlight',
        'description',
        'display_order',
        'status',
    ];

    /**
     * Scope to get only active services ordered by display_order
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->orderBy('display_order');
    }
}
