<?php

namespace App\Models;

use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasSeo;

    protected $fillable = ['title', 'couple_name', 'location', 'wedding_date', 'thumbnail', 'video_url', 'description', 'is_featured', 'is_published', 'sort_order'];

    protected function casts(): array
    {
        return ['wedding_date' => 'date', 'is_featured' => 'boolean', 'is_published' => 'boolean'];
    }
}
