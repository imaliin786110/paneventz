<?php

namespace App\Models;

use App\Traits\HasSeo;
use App\Traits\HasSlugHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BlogPost extends Model
{
    use HasSeo, HasSlugHistory;

    protected $slugSource = 'title';

    protected $fillable = [
        'title',
        'slug',
        'category',
        'excerpt',
        'content',
        'featured_image',
        'author_name',
        'read_time_minutes',
        'is_published',
        'published_at',
        'status', // draft, ai_generated, under_review, published, archived
        'focus_keyword',
        'secondary_keywords',
        'ai_generation_meta',
        'quality_score',
        'quality_warnings',
        'source_story_id',
        'source_wedding_album_id',
    ];

    protected $casts = [
        'is_published'        => 'boolean',
        'read_time_minutes'   => 'integer',
        'published_at'        => 'datetime',
        'secondary_keywords'  => 'array',
        'ai_generation_meta'  => 'array',
        'quality_score'       => 'integer',
        'quality_warnings'    => 'array',
    ];

    public function sourceStory(): BelongsTo
    {
        return $this->belongsTo(Story::class, 'source_story_id');
    }

    public function sourceWeddingAlbum(): BelongsTo
    {
        return $this->belongsTo(WeddingAlbum::class, 'source_wedding_album_id');
    }

    public function calendarSchedule(): HasOne
    {
        return $this->hasOne(ContentCalendar::class, 'blog_post_id');
    }

    public function getUrlAttribute(): string
    {
        return url('/blog/' . $this->slug);
    }

    public function getIsAiGeneratedAttribute(): bool
    {
        return !empty($this->ai_generation_meta) || in_array($this->status, ['ai_generated', 'under_review']);
    }

    /**
     * Contextual Dynamic Related Content Resolver
     */
    public function getRelatedBlogsAttribute()
    {
        return static::where('id', '!=', $this->id)
            ->where('is_published', true)
            ->where(function ($query) {
                $query->where('category', $this->category)
                    ->orWhere('focus_keyword', 'like', "%{$this->focus_keyword}%");
            })
            ->take(3)
            ->get();
    }

    public function getRelatedLocationsAttribute()
    {
        return Location::where('is_published', true)
            ->where(function ($query) {
                $query->where('name', 'like', "%{$this->title}%")
                    ->orWhere('description', 'like', "%{$this->category}%");
            })
            ->take(3)
            ->get();
    }

    public function getRelatedServicesAttribute()
    {
        return Service::where('is_published', true)
            ->take(3)
            ->get();
    }
}
