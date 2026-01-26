<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Service Model (Stub)
 * 
 * Represents coaching services offered.
 * Full implementation will be completed in Day 4.
 * 
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $short_description
 * @property string $full_description
 * @property string|null $icon
 * @property float|null $price
 * @property int $display_order
 * @property string $status (active|inactive)
 */
class Service extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'full_description',
        'icon',
        'price',
        'display_order',
        'status',
        'duration',
        'includes',
        'target_audience',
        'meta_description',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'price' => 'decimal:2',
        'display_order' => 'integer',
        'includes' => 'json',
    ];

    /**
     * Scope: Only active services
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Services ordered by display order
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order');
    }
}
