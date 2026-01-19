<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingListItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'blog_post_id',
        'added_at',
    ];

    protected $casts = [
        'added_at' => 'datetime',
    ];

    /**
     * Get the blog post associated with this reading list item.
     */
    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class);
    }
}
