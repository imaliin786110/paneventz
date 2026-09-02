<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    protected $fillable = [
        'title',
        'couple_name',
        'location',
        'video_url',
        'video_file',
        'thumbnail',
        'duration',
        'description',
        'sort_order',
        'is_featured',
        'is_published',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Helper to get playable video URL (either uploaded file or external link)
     */
    public function getPlayableUrlAttribute(): ?string
    {
        if ($this->video_file) {
            return asset('storage/' . $this->video_file);
        }

        return $this->video_url;
    }
}
