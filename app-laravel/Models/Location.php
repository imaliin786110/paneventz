<?php

namespace App\Models;

use App\Traits\HasSeo;
use App\Traits\HasSlugHistory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasSeo, HasSlugHistory;

    protected $slugSource = 'name';

    protected $fillable = [
        'name',
        'slug',
        'state',
        'country',
        'headline',
        'intro',
        'content',
        'hero_image',
        'popular_venues',
        'faqs',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'popular_venues' => 'array',
        'faqs'           => 'array',
        'is_published'   => 'boolean',
        'sort_order'     => 'integer',
    ];

    public function getUrlAttribute(): string
    {
        return url('/wedding-photographer-' . $this->slug);
    }
}
