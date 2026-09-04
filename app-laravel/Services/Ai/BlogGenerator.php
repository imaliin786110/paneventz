<?php

namespace App\Services\Ai;

use App\Models\BlogPost;
use App\Models\Story;
use App\Models\WeddingAlbum;
use App\Services\Seo\InternalLinkSuggester;
use Illuminate\Support\Str;

class BlogGenerator
{
    /**
     * Generate complete blog draft from structured wedding details
     */
    public static function generate(array $params): array
    {
        $couple = trim($params['couple_name'] ?? '');
        $venue = trim($params['venue'] ?? '');
        $city = trim($params['city'] ?? 'Mumbai');
        $weddingType = trim($params['wedding_type'] ?? 'Wedding Celebration');
        $eventDate = trim($params['event_date'] ?? '');
        $services = trim($params['services'] ?? 'Wedding Photography + Cinematic Films');
        $photographer = trim($params['photographer'] ?? 'Paneventz Lead Visual Artist');
        $videographer = trim($params['videographer'] ?? 'Paneventz Cinema Crew');
        $notes = trim($params['custom_notes'] ?? '');
        $targetKeyword = trim($params['target_keyword'] ?? '');

        // Generate SEO metadata first to establish focus keyword and slug
        $seoData = SeoAutomationService::generate([
            'title'        => "{$couple} — {$weddingType} at {$venue}, {$city}",
            'couple_name'  => $couple,
            'venue'        => $venue,
            'city'         => $city,
            'wedding_type' => $weddingType,
            'services'     => $services,
        ]);

        $focusKeyword = $targetKeyword ?: ($seoData['focus_keyword'] ?? "{$venue} wedding photography");

        $prompt = "Write an authentic, editorial luxury wedding journalism article based STRICTLY and ONLY on the following confirmed details:\n"
            . "- Couple Names: " . ($couple ?: 'The Couple') . "\n"
            . "- Venue: " . ($venue ?: 'Heritage Venue') . "\n"
            . "- City/Location: " . $city . "\n"
            . "- Wedding Type / Culture: " . $weddingType . "\n"
            . ($eventDate ? "- Event Date: {$eventDate}\n" : "")
            . "- Services Provided: {$services}\n"
            . "- Photography Team: {$photographer}\n"
            . "- Cinematography Team: {$videographer}\n"
            . ($notes ? "- Specific Verified Notes: {$notes}\n" : "")
            . "- Target Keyword: {$focusKeyword}\n\n"
            . "CRITICAL RULES:\n"
            . "1. DO NOT invent facts, fake love stories, or unconfirmed family names.\n"
            . "2. Write in a refined, high-end editorial voice (like Vogue Weddings / Architectural Digest).\n"
            . "3. Organize with clear H2 headings: The Setting, The Atmosphere & Documentation, The Visual Aesthetic.\n"
            . "4. Output JSON with:\n"
            . "   - title: string\n"
            . "   - category: 'Wedding Photography' or 'Destination Weddings' or 'Cinematic Films'\n"
            . "   - excerpt: 2-3 sentences teaser\n"
            . "   - content: full HTML formatted article (using <h2>, <p>, <blockquote>, <ul>)\n"
            . "   - read_time_minutes: integer\n";

        // Deterministic Luxury Fallback Template Engine
        $coupleHeading = $couple ? "The Celebration of {$couple}" : "A Timeless Celebration";
        $venueDesc = $venue ? "hosted at the prestigious {$venue} in {$city}" : "celebrated in {$city}";
        $dateMention = $eventDate ? "on " . date('F j, Y', strtotime($eventDate)) : "during a splendid celebration season";

        $fallbackTitle = $couple 
            ? "{$couple}: A Timeless {$weddingType} at {$venue}, {$city}"
            : "Editorial {$weddingType} Documentation at {$venue}, {$city}";

        $fallbackExcerpt = "A visual glimpse into {$coupleHeading} {$venueDesc}, masterfully documented through unobtrusive photojournalism and couture 35mm color grading.";

        $fallbackContent = "
<h2>The Setting & Atmosphere</h2>
<p>Every wedding carries an unspoken rhythm—the quiet anticipation before the rituals begin, the shared laughter between close kin, and the palpable romance that fills the air. {$coupleHeading} was {$venueDesc}, creating an unforgettable canvas of heritage and elegance {$dateMention}.</p>

<h2>Discreet Documentation & Pure Emotion</h2>
<p>Documented with our signature discreet approach, our visual team focused on capturing raw, authentic moments rather than staged interactions. Using high-speed prime lenses and subtle ambient lighting techniques, we safeguarded the genuine pulse of this {$weddingType}, ensuring every heartfelt glance, tender tear, and joyous celebration was preserved with editorial grace.</p>

<blockquote>“A wedding happens only once. It is not a stage to perform upon, but a sacred legacy to be lived and remembered for generations.”</blockquote>

<h2>Artisan Color Grading & Motion Picture Cinema</h2>
<p>In post-production, every photograph and cinematic sequence underwent bespoke master color development. Drawing inspiration from classic 35mm motion picture films, we emphasized natural, luminous skin tones, rich velvet undertones, and delicate highlight roll-offs that preserve the intricate gold embroidery and vibrant florals of the celebration.</p>

<h2>Services & Creative Commission</h2>
<p>The visual preservation for this celebration was curated through <strong>{$services}</strong> by the Paneventz creative studio. For couples planning their wedding celebration at {$venue} or destinations across {$city}, we invite you to explore our bespoke packages and reserve your dates in advance.</p>
";

        $defaultFallback = [
            'title'             => $fallbackTitle,
            'category'          => ($venue || $city !== 'Mumbai') ? 'Destination Weddings' : 'Wedding Photography',
            'excerpt'           => $fallbackExcerpt,
            'content'           => trim($fallbackContent),
            'read_time_minutes' => 4,
        ];

        $generated = AiManager::generateJson($prompt, $defaultFallback, 'You are an editorial wedding magazine director. Respond only with valid JSON containing real wedding story details.');

        // Merge and clean up
        $title = $generated['title'] ?? $fallbackTitle;
        $category = $generated['category'] ?? 'Wedding Photography';
        $excerpt = $generated['excerpt'] ?? $fallbackExcerpt;
        $content = $generated['content'] ?? $fallbackContent;
        $readTime = (int) ($generated['read_time_minutes'] ?? 4);

        // Inject contextual internal links
        $linkedContent = InternalLinkSuggester::injectLinks($content, $city, $services);

        // Quality check
        $quality = ContentQualityChecker::check($title, $linkedContent, $focusKeyword);

        return [
            'title'                => $title,
            'slug'                 => $seoData['slug'] ?? Str::slug($title),
            'category'             => $category,
            'excerpt'              => $excerpt,
            'content'              => $linkedContent,
            'read_time_minutes'    => $readTime,
            'focus_keyword'        => $focusKeyword,
            'secondary_keywords'   => $seoData['secondary_keywords'] ?? [],
            'seo_title'            => $seoData['seo_title'] ?? "{$title} | Paneventz",
            'meta_description'     => $seoData['meta_description'] ?? $excerpt,
            'og_title'             => $seoData['og_title'] ?? $title,
            'og_description'       => $seoData['og_description'] ?? $excerpt,
            'image_alt_text'       => $seoData['image_alt_text'] ?? "Wedding photography at {$venue} in {$city}",
            'quality_score'        => $quality['score'],
            'quality_warnings'     => $quality['warnings'],
            'status'               => 'ai_generated',
            'ai_provider'          => AiManager::getActiveProvider(),
            'generated_at'         => now()->toIso8601String(),
            'input_params'         => $params,
        ];
    }

    /**
     * Generate blog directly from a WeddingAlbum model
     */
    public static function generateFromAlbum(WeddingAlbum $album, array $additionalParams = []): array
    {
        $params = array_merge([
            'couple_name'        => $album->couple_names ?: $album->title,
            'venue'              => $album->location ?: 'Heritage Venue',
            'city'               => $album->location ?: 'Mumbai',
            'wedding_type'       => 'Wedding Celebration',
            'event_date'         => $album->event_date ? $album->event_date->format('Y-m-d') : '',
            'services'           => 'Wedding Photography & Fine Art Album Collection',
            'custom_notes'       => 'Client Wedding Album with ' . $album->photos()->count() . ' curated photographs.',
            'source_album_id'    => $album->id,
        ], $additionalParams);

        $result = static::generate($params);
        $result['featured_image'] = $album->cover_image;
        $result['source_wedding_album_id'] = $album->id;

        return $result;
    }

    /**
     * Generate blog directly from a Story model
     */
    public static function generateFromStory(Story $story, array $additionalParams = []): array
    {
        $params = array_merge([
            'couple_name'        => $story->couple_name,
            'venue'              => $story->location ?: 'Wedding Venue',
            'city'               => $story->location ?: 'Mumbai',
            'wedding_type'       => 'Wedding Celebration',
            'services'           => 'Wedding Photography + Cinematic Films',
            'custom_notes'       => $story->description ?: '',
            'source_story_id'    => $story->id,
        ], $additionalParams);

        $result = static::generate($params);
        $result['featured_image'] = $story->cover_image;
        $result['source_story_id'] = $story->id;

        return $result;
    }
}
