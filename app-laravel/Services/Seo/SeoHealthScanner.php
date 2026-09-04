<?php

namespace App\Services\Seo;

use App\Models\BlogPost;
use App\Models\Location;
use App\Models\SeoHealthIssue;
use App\Models\SeoMetadata;
use App\Models\Service;
use App\Models\Story;
use App\Models\WebsiteSetting;
use App\Models\WeddingAlbum;
use Illuminate\Support\Facades\Route;

class SeoHealthScanner
{
    /**
     * Perform comprehensive site-wide SEO audit and update DB records.
     */
    public static function runFullAudit(): array
    {
        $setting = WebsiteSetting::first();
        $issues = [];
        $scannedCount = 0;

        // 1. Global Settings
        $scannedCount++;
        if (empty($setting?->meta_title)) {
            $issues[] = [
                'item_type'   => 'Global Setting',
                'item_title'  => 'Studio Meta Title',
                'url'         => url('/'),
                'edit_url'    => '/admin/website-settings/1/edit',
                'issue_code'  => 'missing_title',
                'message'     => 'Global meta title is empty in Website Settings.',
                'severity'    => 'critical',
            ];
        }
        if (empty($setting?->meta_description)) {
            $issues[] = [
                'item_type'   => 'Global Setting',
                'item_title'  => 'Studio Meta Description',
                'url'         => url('/'),
                'edit_url'    => '/admin/website-settings/1/edit',
                'issue_code'  => 'missing_description',
                'message'     => 'Global meta description is empty in Website Settings.',
                'severity'    => 'critical',
            ];
        }

        // 2. Blog Posts
        $posts = BlogPost::with('seo')->get();
        $seenBlogTitles = [];
        foreach ($posts as $post) {
            $scannedCount++;
            $words = str_word_count(strip_tags($post->content));
            
            if ($words < 250 && $post->is_published) {
                $issues[] = [
                    'item_type'   => 'Blog Post',
                    'item_title'  => $post->title,
                    'url'         => $post->url,
                    'edit_url'    => "/admin/blog-posts/{$post->id}/edit",
                    'issue_code'  => 'thin_content',
                    'message'     => "Thin Content: Only {$words} words. Search engines prefer 400+ words.",
                    'severity'    => 'warning',
                ];
            }

            if (empty($post->featured_image) && empty($post->seo?->og_image)) {
                $issues[] = [
                    'item_type'   => 'Blog Post',
                    'item_title'  => $post->title,
                    'url'         => $post->url,
                    'edit_url'    => "/admin/blog-posts/{$post->id}/edit",
                    'issue_code'  => 'missing_og_image',
                    'message'     => 'No featured or OpenGraph social sharing image set.',
                    'severity'    => 'warning',
                ];
            }

            if (isset($seenBlogTitles[$post->title])) {
                $issues[] = [
                    'item_type'   => 'Blog Post',
                    'item_title'  => $post->title,
                    'url'         => $post->url,
                    'edit_url'    => "/admin/blog-posts/{$post->id}/edit",
                    'issue_code'  => 'duplicate_title',
                    'message'     => 'Duplicate Title detected across multiple blog posts.',
                    'severity'    => 'critical',
                ];
            }
            $seenBlogTitles[$post->title] = true;
        }

        // 3. Destination Hubs (Locations)
        $locations = Location::with('seo')->get();
        foreach ($locations as $loc) {
            $scannedCount++;
            if (empty($loc->hero_image) && empty($loc->seo?->og_image)) {
                $issues[] = [
                    'item_type'   => 'Destination Page',
                    'item_title'  => "Wedding Photographer {$loc->name}",
                    'url'         => $loc->url,
                    'edit_url'    => "/admin/locations/{$loc->id}/edit",
                    'issue_code'  => 'missing_og_image',
                    'message'     => 'Missing hero/OG preview image for destination landing page.',
                    'severity'    => 'warning',
                ];
            }
            if (strlen($loc->description ?? '') < 150) {
                $issues[] = [
                    'item_type'   => 'Destination Page',
                    'item_title'  => "Wedding Photographer {$loc->name}",
                    'url'         => $loc->url,
                    'edit_url'    => "/admin/locations/{$loc->id}/edit",
                    'issue_code'  => 'thin_content',
                    'message'     => 'Destination description is brief. Expand with iconic venues and photography tips.',
                    'severity'    => 'info',
                ];
            }
        }

        // 4. Wedding Albums & Galleries
        $albums = WeddingAlbum::with('seo')->get();
        foreach ($albums as $album) {
            $scannedCount++;
            if (empty($album->cover_image)) {
                $issues[] = [
                    'item_type'   => 'Wedding Album',
                    'item_title'  => $album->title,
                    'url'         => $album->guest_url,
                    'edit_url'    => "/admin/wedding-albums/{$album->id}/edit",
                    'issue_code'  => 'missing_og_image',
                    'message'     => 'Wedding album is missing a cover image.',
                    'severity'    => 'warning',
                ];
            }
        }

        // Persist detected issues into DB
        SeoHealthIssue::where('is_resolved', false)->delete();
        foreach ($issues as $issue) {
            SeoHealthIssue::create(array_merge($issue, [
                'last_detected_at' => now(),
            ]));
        }

        $criticalCount = count(array_filter($issues, fn($i) => $i['severity'] === 'critical'));
        $warningCount  = count(array_filter($issues, fn($i) => $i['severity'] === 'warning'));
        $infoCount     = count(array_filter($issues, fn($i) => $i['severity'] === 'info'));

        $penalty = ($criticalCount * 20) + ($warningCount * 6) + ($infoCount * 2);
        $healthScore = max(50, min(100, (int) (100 - ($penalty / max(1, $scannedCount) * 10))));

        return [
            'scanned_count'    => $scannedCount,
            'health_score'     => $healthScore,
            'critical_count'   => $criticalCount,
            'warning_count'    => $warningCount,
            'info_count'       => $infoCount,
            'total_issues'     => count($issues),
            'issues'           => $issues,
        ];
    }
}
