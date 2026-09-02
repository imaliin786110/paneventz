<?php

namespace App\Services\Ai;

use Illuminate\Support\Str;

class SeoAutomationService
{
    /**
     * Generate full SEO metadata payload for an article or wedding story.
     */
    public static function generate(array $params): array
    {
        $title = $params['title'] ?? 'Wedding Photography Story';
        $couple = $params['couple_name'] ?? '';
        $venue = $params['venue'] ?? '';
        $city = $params['city'] ?? 'Mumbai';
        $weddingType = $params['wedding_type'] ?? 'Wedding Celebration';
        $services = $params['services'] ?? 'Wedding Photography & Cinematic Films';

        $prompt = "Generate complete luxury wedding SEO metadata for the following wedding article:\n"
            . "Title: {$title}\n"
            . "Couple: {$couple}\n"
            . "Venue: {$venue}\n"
            . "City: {$city}\n"
            . "Wedding Type: {$weddingType}\n"
            . "Services: {$services}\n\n"
            . "Provide JSON with:\n"
            . "- focus_keyword (e.g. 'Taj Lands End wedding photography')\n"
            . "- secondary_keywords (array of 4 relevant terms)\n"
            . "- seo_title (50-60 chars, luxury and punchy, ending with '| Paneventz')\n"
            . "- meta_description (145-160 chars, compelling with location and luxury photography details)\n"
            . "- slug (clean URL slug)\n"
            . "- og_title\n"
            . "- og_description\n"
            . "- image_alt_text (descriptive natural alt text for the main photo)\n";

        // Deterministic Luxury Fallback
        $venuePart = $venue ? " at {$venue}" : "";
        $couplePart = $couple ? "{$couple}'s " : "";
        $slugBase = $couple ? "{$couple}-wedding-{$venue}-{$city}" : $title;
        $cleanSlug = Str::slug($slugBase);

        $defaultFallback = [
            'focus_keyword'       => trim("{$venue} {$city} wedding photography"),
            'secondary_keywords'  => [
                "{$city} luxury wedding photographer",
                "destination wedding cinematography {$city}",
                "{$weddingType} photography",
                "royal wedding films {$venue}",
            ],
            'seo_title'           => Str::limit("{$couplePart}{$weddingType}{$venuePart}, {$city} | Paneventz", 60),
            'meta_description'    => Str::limit("Discover the timeless {$weddingType} of {$couple}{$venuePart}, {$city}. Curated by Paneventz with master 35mm color grading and cinematic coverage.", 158),
            'slug'                => $cleanSlug,
            'og_title'            => "{$couplePart}{$weddingType}{$venuePart}, {$city} — Paneventz",
            'og_description'      => "Emotional wedding photography and cinematic film documenting {$couple}{$venuePart} in {$city}.",
            'image_alt_text'      => "{$couplePart}wedding celebration{$venuePart} in {$city} photographed by Paneventz",
        ];

        return AiManager::generateJson($prompt, $defaultFallback, 'You are a luxury wedding photography SEO specialist. Generate high-intent keywords and metadata strictly based on provided inputs.');
    }
}
