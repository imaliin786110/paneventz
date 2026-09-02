<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Story;
use App\Models\WeddingAlbum;
use App\Services\Seo\SeoService;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function show(string $slug): View
    {
        $location = Location::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Get related stories or wedding albums for this location
        $stories = Story::where('is_published', true)
            ->where(function ($q) use ($location) {
                $q->where('location', 'like', '%' . $location->name . '%')
                  ->orWhere('location', 'like', '%' . $location->state . '%');
            })
            ->take(6)
            ->get();

        // Fallback to latest stories if none match location explicitly
        if ($stories->isEmpty()) {
            $stories = Story::where('is_published', true)->take(4)->get();
        }

        $allLocations = Location::where('is_published', true)
            ->where('id', '!=', $location->id)
            ->orderBy('sort_order')
            ->get();

        $seo = SeoService::resolve($location);

        return view('locations.show', [
            'location'     => $location,
            'stories'      => $stories,
            'allLocations' => $allLocations,
            'seo'          => $seo,
        ]);
    }
}
