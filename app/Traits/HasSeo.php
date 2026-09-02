<?php

namespace App\Traits;

use App\Models\SeoMetadata;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeo
{
    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }

    /**
     * Generate dynamic SEO Title fallback if no custom title is set in DB.
     */
    public function getDynamicSeoTitle(): string
    {
        if (isset($this->title) && !empty($this->title)) {
            return $this->title . ' — Paneventz';
        }
        if (isset($this->couple_name) && !empty($this->couple_name)) {
            $loc = !empty($this->location) ? " in {$this->location}" : '';
            return "{$this->couple_name} Wedding Photography{$loc} — Paneventz";
        }
        if (isset($this->couple_names) && !empty($this->couple_names)) {
            $loc = !empty($this->location) ? " in {$this->location}" : '';
            return "{$this->couple_names} Wedding Gallery{$loc} — Paneventz";
        }
        if (isset($this->name) && !empty($this->name)) {
            return "{$this->name} — Luxury Wedding Photography & Films | Paneventz";
        }

        return 'Paneventz — Luxury Wedding Photography & Cinematic Films';
    }

    /**
     * Generate dynamic Meta Description fallback if no custom description is set in DB.
     */
    public function getDynamicSeoDescription(): string
    {
        if (!empty($this->excerpt)) {
            return str($this->excerpt)->limit(160)->toString();
        }
        if (!empty($this->short_description)) {
            return str($this->short_description)->limit(160)->toString();
        }
        if (!empty($this->intro)) {
            return str($this->intro)->limit(160)->toString();
        }
        if (!empty($this->description)) {
            return str(strip_tags($this->description))->limit(160)->toString();
        }
        if (isset($this->couple_name) || isset($this->couple_names)) {
            $names = $this->couple_name ?? $this->couple_names;
            $loc = !empty($this->location) ? " in {$this->location}" : '';
            return "Experience the luxury wedding photography and cinematic moments of {$names}{$loc}, captured by Paneventz.";
        }

        return 'Paneventz creates timeless wedding photography and cinematic films for couples across India and destinations worldwide.';
    }

    /**
     * Generate dynamic Open Graph / Twitter image fallback.
     */
    public function getDynamicSeoImage(): ?string
    {
        if (!empty($this->featured_image)) {
            return asset('storage/' . $this->featured_image);
        }
        if (!empty($this->cover_image)) {
            return asset('storage/' . $this->cover_image);
        }
        if (!empty($this->hero_image)) {
            return asset('storage/' . $this->hero_image);
        }
        if (!empty($this->thumbnail)) {
            return asset('storage/' . $this->thumbnail);
        }

        return null;
    }

    /**
     * Generate canonical URL fallback.
     */
    public function getDynamicCanonicalUrl(): string
    {
        if (isset($this->slug)) {
            if ($this instanceof \App\Models\WeddingAlbum) {
                return url('/gallery/' . $this->slug);
            }
            if ($this instanceof \App\Models\Location) {
                return url('/wedding-photographer-' . $this->slug);
            }
            if ($this instanceof \App\Models\BlogPost) {
                return url('/blog/' . $this->slug);
            }
        }

        return url()->current();
    }
}
