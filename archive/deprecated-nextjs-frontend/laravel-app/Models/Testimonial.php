<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Testimonial Model (Stub)
 * 
 * Represents client testimonials and reviews.
 * Full implementation will be completed in Day 4.
 * 
 * @property int $id
 * @property string $client_name
 * @property string|null $client_role
 * @property string $content
 * @property int $rating (1-5)
 * @property string $status (published|pending)
 */
class Testimonial extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'client_name',
        'client_role',
        'content',
        'rating',
        'status',
        'display_order',
        'featured',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'rating' => 'integer',
        'display_order' => 'integer',
        'featured' => 'boolean',
    ];

    /**
     * Scope: Only published testimonials
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope: Featured testimonials
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    /**
     * Scope: Ordered by display order
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order');
    }
}
