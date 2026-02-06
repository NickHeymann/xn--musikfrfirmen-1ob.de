<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'inquiry_type',
        'message',
        'status',
        'company_research',
    ];

    protected function casts(): array
    {
        return [
            'company_research' => 'array',
        ];
    }
}
