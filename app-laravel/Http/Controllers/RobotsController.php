<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function index(): Response
    {
        $sitemapUrl = url('/sitemap.xml');

        $content = <<<TXT
User-agent: *
Disallow: /admin/
Disallow: /admin
Disallow: /client-portal/
Disallow: /client-portal
Disallow: /gallery/*/verify-pin
Disallow: /enquire
Disallow: /media/stream/

# Allow Googlebot and search crawlers to access public assets
Allow: /storage/
Allow: /images/
Allow: /css/
Allow: /js/
Allow: /build/
Allow: /

Sitemap: {$sitemapUrl}
TXT;

        return response($content, 200, [
            'Content-Type'  => 'text/plain; charset=utf-8',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
