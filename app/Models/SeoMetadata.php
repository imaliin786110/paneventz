<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMetadata extends Model
{
    protected $table = 'seo_metadatas';

    protected $fillable = [
        'seoable_type',
        'seoable_id',
        'route_name',
        'title',
        'meta_description',
        'keywords',
        'canonical_url',
        'robots',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'schema_type',
        'custom_json_ld',
        'change_frequency',
        'priority',
        'is_indexed',
    ];

    protected $casts = [
        'custom_json_ld' => 'array',
        'is_indexed'     => 'boolean',
        'priority'       => 'float',
    ];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function forRoute(string $routeName): ?self
    {
        return static::where('route_name', $routeName)->first();
    }
}
