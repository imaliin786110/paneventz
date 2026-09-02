<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentCalendar extends Model
{
    protected $fillable = [
        'topic',
        'target_keyword',
        'category',
        'author_name',
        'scheduled_for',
        'status', // planned, generated, published, cancelled
        'blog_post_id',
        'notes',
    ];

    protected $casts = [
        'scheduled_for' => 'date',
    ];

    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id');
    }
}
