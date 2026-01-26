<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Video extends Model
{
    protected $fillable = [
        'youtube_id',
        'title',
        'description',
        'category',
        'published_at',
        'thumbnail_url',
        'views',
        'featured',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'featured' => 'boolean',
        'views' => 'integer',
    ];

    /**
     * Scope: Filter by category
     */
    public function scopeByCategory(Builder $query, ?string $category): Builder
    {
        if ($category === 'all' || empty($category)) {
            return $query;
        }
        
        return $query->where('category', $category);
    }

    /**
     * Scope: Published videos ordered by date
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
                     ->orderBy('published_at', 'desc');
    }

    /**
     * Scope: Featured videos
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true)
                     ->orderBy('published_at', 'desc');
    }

    /**
     * Scope: Search in title and description
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Accessor: YouTube watch URL
     */
    public function getYoutubeUrlAttribute(): string
    {
        return "https://www.youtube.com/watch?v={$this->youtube_id}";
    }

    /**
     * Accessor: YouTube embed URL
     */
    public function getEmbedUrlAttribute(): string
    {
        return "https://www.youtube.com/embed/{$this->youtube_id}";
    }

    /**
     * Accessor: Thumbnail URL (default quality)
     */
    public function getThumbnailAttribute(): string
    {
        if ($this->thumbnail_url) {
            return $this->thumbnail_url;
        }
        
        // YouTube default thumbnail (mqdefault = medium quality)
        return "https://img.youtube.com/vi/{$this->youtube_id}/mqdefault.jpg";
    }

    /**
     * Accessor: High quality thumbnail
     */
    public function getThumbnailHqAttribute(): string
    {
        return "https://img.youtube.com/vi/{$this->youtube_id}/hqdefault.jpg";
    }

    /**
     * Accessor: German category label
     */
    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'beziehung' => 'Beziehung',
            'selbstfindung' => 'Selbstfindung',
            'hochsensibel' => 'Hochsensibel',
            'koerper' => 'Körper',
            default => ucfirst($this->category),
        };
    }

    /**
     * Accessor: Formatted publish date
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->published_at?->format('d.m.Y') ?? '';
    }
}
