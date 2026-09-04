<?php

namespace App\Services\Ai;

use App\Models\BlogPost;
use App\Services\Seo\InternalLinkSuggester;

class ContentRefreshEngine
{
    /**
     * Identify older blogs that could benefit from an SEO and editorial refresh.
     */
    public static function getCandidates(): array
    {
        $posts = BlogPost::where('is_published', true)
            ->with('seo')
            ->orderBy('updated_at', 'asc')
            ->get();

        $candidates = [];

        foreach ($posts as $post) {
            $words = str_word_count(strip_tags($post->content));
            $daysSinceUpdate = (int) $post->updated_at->diffInDays(now());

            $needsRefresh = false;
            $reasons = [];

            if ($daysSinceUpdate > 90) {
                $needsRefresh = true;
                $reasons[] = "Not updated in {$daysSinceUpdate} days.";
            }

            if ($words < 350) {
                $needsRefresh = true;
                $reasons[] = "Short content depth ({$words} words).";
            }

            if (empty($post->seo?->meta_description)) {
                $needsRefresh = true;
                $reasons[] = "Missing custom meta description.";
            }

            if ($needsRefresh || $posts->count() <= 3) {
                $candidates[] = [
                    'id'                 => $post->id,
                    'title'              => $post->title,
                    'slug'               => $post->slug,
                    'category'           => $post->category,
                    'word_count'         => $words,
                    'days_since_update'  => $daysSinceUpdate,
                    'published_at'       => $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y'),
                    'seo_title'          => $post->seo?->title ?? $post->title,
                    'meta_description'   => $post->seo?->meta_description ?? $post->excerpt,
                    'reasons'            => $reasons,
                    'edit_url'           => "/admin/blog-posts/{$post->id}/edit",
                ];
            }
        }

        return $candidates;
    }

    /**
     * Generate concrete AI improvement suggestions for a specific blog post
     */
    public static function generateSuggestions(BlogPost $post): array
    {
        $words = str_word_count(strip_tags($post->content));
        
        $prompt = "Analyze this existing wedding photography blog post and suggest high-value editorial and SEO improvements:\n"
            . "Title: {$post->title}\n"
            . "Category: {$post->category}\n"
            . "Word Count: {$words}\n"
            . "Current Excerpt: {$post->excerpt}\n"
            . "Current Content Preview: " . substr(strip_tags($post->content), 0, 400) . "...\n\n"
            . "Provide JSON with:\n"
            . "- improved_title (more engaging & click-worthy)\n"
            . "- improved_meta_description (150-160 chars)\n"
            . "- suggested_new_section_heading (e.g. 'Golden Hour Timings for Taj Lands End')\n"
            . "- suggested_new_section_content (1-2 paragraphs of valuable wedding advice)\n"
            . "- internal_linking_recommendations (array of suggestions)";

        $defaultFallback = [
            'improved_title'                     => "{$post->title} — Complete Luxury Wedding Guide",
            'improved_meta_description'          => "Updated wedding photography guide for {$post->title}. Expert tips on lighting, timelines, and unobtrusive moments by Paneventz.",
            'suggested_new_section_heading'      => 'Key Photography Advice & Ceremony Timings',
            'suggested_new_section_content'      => '<p>When planning your ceremony schedule, allocating 45 minutes for dedicated couple portraits right before sunset ensures magnificent golden-hour light without delaying guest reception timelines.</p>',
            'internal_linking_recommendations'   => [
                'Link to /services for package investment details.',
                'Link to Mumbai destination hub /wedding-photographer-mumbai.',
                'Add direct link to Enquiry Concierge /#contact.',
            ],
        ];

        return AiManager::generateJson($prompt, $defaultFallback, 'You are an SEO content optimizer. Respond in JSON with actionable improvements.');
    }
}
