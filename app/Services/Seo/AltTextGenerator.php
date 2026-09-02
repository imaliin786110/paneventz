<?php

namespace App\Services\Seo;

use App\Models\AlbumPhoto;
use App\Models\BlogPost;
use App\Models\Location;
use App\Models\Story;
use App\Models\WeddingAlbum;

class AltTextGenerator
{
    /**
     * Generate descriptive, non-spammy, natural alt text for images.
     */
    public static function for($context, string $defaultFallback = 'Paneventz Luxury Wedding Photography'): string
    {
        if ($context instanceof WeddingAlbum) {
            $names = $context->couple_names ?: $context->title;
            $loc = !empty($context->location) ? " in {$context->location}" : '';
            return "Candid luxury wedding photograph of {$names}{$loc} by Paneventz";
        }

        if ($context instanceof AlbumPhoto) {
            $album = $context->weddingAlbum;
            $names = $album ? ($album->couple_names ?: $album->title) : 'Wedding celebration';
            $loc = ($album && !empty($album->location)) ? " in {$album->location}" : '';
            return "Wedding photograph of {$names}{$loc} documented by Paneventz";
        }

        if ($context instanceof Story) {
            $couple = $context->couple_name ?: 'Bride and groom';
            $loc = !empty($context->location) ? " in {$context->location}" : '';
            return "Timeless wedding moments of {$couple}{$loc} captured by Paneventz";
        }

        if ($context instanceof Location) {
            return "Bespoke wedding photography and cinematic films in {$context->name}, {$context->state} by Paneventz";
        }

        if ($context instanceof BlogPost) {
            return "{$context->title} — Editorial wedding guide by Paneventz";
        }

        if (is_string($context) && !empty($context)) {
            return e($context) . ' — Paneventz Luxury Wedding Photography';
        }

        return $defaultFallback;
    }
}
