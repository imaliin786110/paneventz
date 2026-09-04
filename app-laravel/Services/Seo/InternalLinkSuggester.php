<?php

namespace App\Services\Seo;

use App\Models\Location;
use App\Models\Service;
use App\Models\Story;

class InternalLinkSuggester
{
    /**
     * Analyze text and inject natural contextual internal links without overwhelming the reader.
     */
    public static function injectLinks(string $content, ?string $cityName = null, ?string $serviceType = null): string
    {
        $locations = Location::where('is_published', true)->get();
        $services = Service::where('is_published', true)->get();

        $linkedLocations = 0;
        $linkedServices = 0;

        // Match Locations (max 2 internal location links)
        foreach ($locations as $loc) {
            if ($linkedLocations >= 2) break;
            $name = $loc->name;
            if (stripos($content, $name) !== false && !str_contains($content, "href=\"{$loc->url}\"")) {
                // Replace first occurrence only
                $pattern = '/\b(' . preg_quote($name, '/') . ')\b(?![^<]*>|[^<>]*<\/a>)/i';
                $content = preg_replace($pattern, "<a href=\"{$loc->url}\" class=\"internal-seo-link\">$1</a>", $content, 1);
                $linkedLocations++;
            }
        }

        // Match Services (max 2 internal service links)
        if ($linkedServices < 1 && (stripos($content, 'wedding photography') !== false || stripos($content, 'cinematography') !== false)) {
            $servicesUrl = url('/services');
            $pattern = '/\b(wedding photography|cinematic films|cinematography)\b(?![^<]*>|[^<>]*<\/a>)/i';
            $content = preg_replace($pattern, "<a href=\"{$servicesUrl}\" class=\"internal-seo-link\">$1</a>", $content, 1);
            $linkedServices++;
        }

        return $content;
    }

    /**
     * Get list of relevant internal link opportunities for an admin preview
     */
    public static function getSuggestions(?string $cityName = null, ?string $serviceName = null): array
    {
        $links = [];

        $services = Service::where('is_published', true)->take(3)->get();
        foreach ($services as $s) {
            $links[] = [
                'type'  => 'Service Collection',
                'title' => $s->name,
                'url'   => url('/services'),
            ];
        }

        if ($cityName) {
            $location = Location::where('name', 'like', "%{$cityName}%")->where('is_published', true)->first();
            if ($location) {
                $links[] = [
                    'type'  => 'Destination Hub',
                    'title' => "Wedding Photographer {$location->name}",
                    'url'   => $location->url,
                ];
            }
        }

        $links[] = [
            'type'  => 'Enquiry Concierge',
            'title' => 'Reserve Your Wedding Date',
            'url'   => url('/#contact'),
        ];

        return $links;
    }
}
