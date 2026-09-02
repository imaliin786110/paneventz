<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected static ?self $cachedInstance = null;

    protected $fillable = [
        'studio_name', 'tagline', 'email', 'phone', 'whatsapp', 'instagram_url', 'facebook_url', 'youtube_url',
        'hero_eyebrow', 'hero_heading', 'hero_description', 'hero_button_label', 'hero_button_url',
        'about_eyebrow', 'about_heading', 'about_description', 'meta_title', 'meta_description',
        'footer_heading', 'footer_description', 'footer_address', 'footer_copyright',
        'color_grade_heading', 'color_grade_description', 'color_grade_before_image', 'color_grade_after_image',
        'stat_1_number', 'stat_1_suffix', 'stat_1_label',
        'stat_2_number', 'stat_2_suffix', 'stat_2_label',
        'stat_3_number', 'stat_3_suffix', 'stat_3_label',
        'stat_4_number', 'stat_4_suffix', 'stat_4_label',
        'logo', 'favicon', 'hero_background_image', 'hero_background_video', 'analytics_code', 'brochure_pdf', 'google_drive_api_key',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            static::$cachedInstance = null;
        });

        static::deleted(function () {
            static::$cachedInstance = null;
        });
    }

    /**
     * Get the single in-memory cached website setting model instance for zero-latency lookups.
     */
    public static function getCached(): ?self
    {
        if (static::$cachedInstance === null) {
            static::$cachedInstance = static::first();
        }
        return static::$cachedInstance;
    }
}
