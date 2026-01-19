<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'name',
        'role',
        'role_second_line',
        'image',
        'bio_title',
        'bio_text',
        'image_class',
        'position',
        'display_order',
        'status',
    ];

    /**
     * Scope to get only active team members ordered by display_order
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->orderBy('display_order');
    }
}
