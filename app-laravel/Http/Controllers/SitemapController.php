<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Location;
use App\Models\Service;
use App\Models\WeddingAlbum;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];

        // 1. Static Pages
        $urls[] = [
            'loc'        => url('/'),
            'lastmod'    => now()->format('Y-m-d'),
            'changefreq' => 'daily',
            'priority'   => '1.0',
            'image'      => asset('images/1.png'),
            'title'      => 'Paneventz Luxury Wedding Photography & Films',
        ];

        $urls[] = [
            'loc'        => url('/services'),
            'lastmod'    => Service::max('updated_at') ? date('Y-m-d', strtotime(Service::max('updated_at'))) : now()->format('Y-m-d'),
            'changefreq' => 'weekly',
            'priority'   => '0.9',
            'image'      => null,
            'title'      => 'Wedding Photography Collections & Services',
        ];

        $urls[] = [
            'loc'        => url('/terms'),
            'lastmod'    => now()->format('Y-m-d'),
            'changefreq' => 'monthly',
            'priority'   => '0.5',
            'image'      => null,
            'title'      => 'Client Terms & Conditions',
        ];

        $urls[] = [
            'loc'        => url('/galleries'),
            'lastmod'    => WeddingAlbum::where('is_public', true)->max('updated_at') ? date('Y-m-d', strtotime(WeddingAlbum::where('is_public', true)->max('updated_at'))) : now()->format('Y-m-d'),
            'changefreq' => 'daily',
            'priority'   => '0.9',
            'image'      => null,
            'title'      => 'Public Wedding Galleries & Heirlooms',
        ];

        $urls[] = [
            'loc'        => url('/blog'),
            'lastmod'    => BlogPost::where('is_published', true)->max('updated_at') ? date('Y-m-d', strtotime(BlogPost::where('is_published', true)->max('updated_at'))) : now()->format('Y-m-d'),
            'changefreq' => 'daily',
            'priority'   => '0.8',
            'image'      => null,
            'title'      => 'Wedding Journal & Editorial Advice',
        ];

        // 2. Public Wedding Galleries (Exclude private password PIN collections)
        $albums = WeddingAlbum::where('is_public', true)
            ->whereNull('pin_code')
            ->whereNotNull('slug')
            ->with('seo')
            ->get();

        foreach ($albums as $album) {
            if ($album->seo && !$album->seo->is_indexed) {
                continue;
            }

            $urls[] = [
                'loc'        => url('/gallery/' . $album->slug),
                'lastmod'    => $album->updated_at->format('Y-m-d'),
                'changefreq' => $album->seo?->change_frequency ?? 'weekly',
                'priority'   => (string) ($album->seo?->priority ?? 0.8),
                'image'      => $album->cover_image ? asset('storage/' . $album->cover_image) : null,
                'title'      => $album->title,
            ];
        }

        // 3. High-Value Location Pages
        $locations = Location::where('is_published', true)
            ->whereNotNull('slug')
            ->with('seo')
            ->get();

        foreach ($locations as $location) {
            if ($location->seo && !$location->seo->is_indexed) {
                continue;
            }

            $urls[] = [
                'loc'        => url('/wedding-photographer-' . $location->slug),
                'lastmod'    => $location->updated_at->format('Y-m-d'),
                'changefreq' => $location->seo?->change_frequency ?? 'weekly',
                'priority'   => (string) ($location->seo?->priority ?? 0.9),
                'image'      => $location->hero_image ? asset('storage/' . $location->hero_image) : null,
                'title'      => "Wedding Photographer {$location->name}",
            ];
        }

        // 4. Published Blog Posts
        $posts = BlogPost::where('is_published', true)
            ->whereNotNull('slug')
            ->with('seo')
            ->get();

        foreach ($posts as $post) {
            if ($post->seo && !$post->seo->is_indexed) {
                continue;
            }

            $urls[] = [
                'loc'        => url('/blog/' . $post->slug),
                'lastmod'    => $post->updated_at->format('Y-m-d'),
                'changefreq' => $post->seo?->change_frequency ?? 'monthly',
                'priority'   => (string) ($post->seo?->priority ?? 0.7),
                'image'      => $post->featured_image ? asset('storage/' . $post->featured_image) : null,
                'title'      => $post->title,
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . PHP_EOL;

        foreach ($urls as $u) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . $u['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '    <changefreq>' . $u['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '    <priority>' . $u['priority'] . '</priority>' . PHP_EOL;
            if (!empty($u['image'])) {
                $xml .= '    <image:image>' . PHP_EOL;
                $xml .= '      <image:loc>' . htmlspecialchars($u['image'], ENT_XML1, 'UTF-8') . '</image:loc>' . PHP_EOL;
                if (!empty($u['title'])) {
                    $xml .= '      <image:title>' . htmlspecialchars($u['title'], ENT_XML1, 'UTF-8') . '</image:title>' . PHP_EOL;
                }
                $xml .= '    </image:image>' . PHP_EOL;
            }
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type'  => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
