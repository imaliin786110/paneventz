<?php

namespace App\Models;

use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasSeo;

    protected $fillable = [
        'couple_name',
        'location',
        'cover_image',
        'description',
        'sort_order',
        'is_published',
        'gallery',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'sort_order'   => 'integer',
        'gallery'      => 'array',
    ];
}
