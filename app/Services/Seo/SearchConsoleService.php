<?php

namespace App\Services\Seo;

use App\Models\Location;
use App\Models\SearchConsoleMetric;
use App\Models\Service;

class SearchConsoleService
{
    /**
     * Check if Search Console credentials or Service Account key is configured
     */
    public static function isConfigured(): bool
    {
        return !empty(env('GOOGLE_SEARCH_CONSOLE_KEY_PATH')) || !empty(env('GOOGLE_SEARCH_CONSOLE_CLIENT_ID'));
    }

    /**
     * Analyze search performance patterns and surface high/medium/content opportunities.
     */
    public static function getOpportunities(): array
    {
        $opportunities = [];

        // 1. Content Gaps (Locations without dedicated articles)
        $locations = Location::where('is_published', true)->get();
        foreach ($locations as $loc) {
            $opportunities[] = [
                'priority'    => 'MEDIUM PRIORITY',
                'badge_color' => 'warning',
                'type'        => 'Content Opportunity',
                'title'       => "Create Venue Guide for {$loc->name}",
                'detail'      => "{$loc->name} destination page is published but has no supporting venue guides or blog articles.",
                'action_label'=> 'Generate Blog With AI',
                'action_url'  => "/admin/ai-blog-writer?city=" . urlencode($loc->name),
            ];
        }

        // 2. High CTR Striking Distance (Simulated or from DB metrics if stored)
        $metrics = SearchConsoleMetric::orderBy('impressions', 'desc')->take(5)->get();
        if ($metrics->isNotEmpty()) {
            foreach ($metrics as $m) {
                if ($m->position >= 5 && $m->position <= 20) {
                    $opportunities[] = [
                        'priority'    => 'HIGH PRIORITY',
                        'badge_color' => 'danger',
                        'type'        => 'Striking Distance (Positions 5–20)',
                        'title'       => "Keyword: '{$m->query}' (Rank #{$m->position})",
                        'detail'      => "Page {$m->page_url} has {$m->impressions} impressions and {$m->clicks} clicks. Boosting internal links could lift it to Top 3.",
                        'action_label'=> 'Optimize Page',
                        'action_url'  => "/admin/content-refresh",
                    ];
                }
            }
        } else {
            $opportunities[] = [
                'priority'    => 'HIGH PRIORITY',
                'badge_color' => 'danger',
                'type'        => 'Target High-Intent Keyword',
                'title'       => "Rank for 'Taj Lands End Wedding Photography'",
                'detail'      => "High monthly search volume in Mumbai. An in-depth venue breakdown will capture high-intent couples directly.",
                'action_label'=> 'Write Guide With AI',
                'action_url'  => "/admin/ai-blog-writer?venue=" . urlencode('Taj Lands End') . "&city=Mumbai",
            ];
            $opportunities[] = [
                'priority'    => 'MEDIUM PRIORITY',
                'badge_color' => 'warning',
                'type'        => 'Editorial Authority',
                'title'       => "Publish Wedding Day Photography Checklist",
                'detail'      => "Frequently searched planning keyword in India that builds strong backlinks and client trust.",
                'action_label'=> 'Schedule Article',
                'action_url'  => "/admin/ai-blog-writer?topic=" . urlencode('Wedding Day Photography Checklist for Couples'),
            ];
        }

        return $opportunities;
    }
}
