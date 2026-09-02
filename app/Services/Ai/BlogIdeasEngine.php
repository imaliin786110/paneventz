<?php

namespace App\Services\Ai;

use App\Models\BlogPost;
use App\Models\Location;
use App\Models\Service;
use App\Models\Story;

class BlogIdeasEngine
{
    /**
     * Generate content ideas based on existing services, locations, and content gaps.
     */
    public static function generateIdeas(int $count = 8): array
    {
        $existingBlogs = BlogPost::pluck('title')->toArray();
        $locations = Location::where('is_published', true)->pluck('name')->toArray();
        $services = Service::where('is_published', true)->pluck('name')->toArray();

        $prompt = "Suggest {$count} unique, high-intent, editorial blog topic ideas for a luxury Indian wedding photography studio based in Mumbai with destination coverage in Rajasthan & Goa.\n"
            . "Existing Articles to avoid duplicating:\n" . implode("\n- ", array_slice($existingBlogs, 0, 10)) . "\n\n"
            . "Target Locations: " . implode(', ', $locations) . "\n"
            . "Services Offered: " . implode(', ', $services) . "\n\n"
            . "Provide JSON with array of objects containing:\n"
            . "- topic (compelling human title)\n"
            . "- target_keyword (realistic search term)\n"
            . "- category ('Wedding Photography', 'Destination Weddings', 'Cinematic Films', 'Bridal Style', 'Planning Guides')\n"
            . "- reasoning (why this topic captures prospective couples)\n"
            . "- estimated_intent ('High Commercial', 'Informational Guide', 'Inspirational')";

        $defaultIdeas = [
            [
                'topic'            => 'Taj Lands End Wedding Photography Guide: Iconic Backdrops & Sunset Timings',
                'target_keyword'   => 'Taj Lands End wedding photography Mumbai',
                'category'         => 'Destination Weddings',
                'reasoning'        => 'Captures affluent couples searching for venue-specific photography advice and lighting tips at Bandra’s premier seaside palace.',
                'estimated_intent' => 'High Commercial',
            ],
            [
                'topic'            => 'The Complete Guide to Muslim Wedding Photography: Nikah, Walima & Family Portraits',
                'target_keyword'   => 'Muslim wedding photographer Mumbai',
                'category'         => 'Wedding Photography',
                'reasoning'        => 'Addresses cultural nuances, respectful documentation, and intimate family moments for Muslim celebrations.',
                'estimated_intent' => 'High Commercial',
            ],
            [
                'topic'            => 'Best Time for Outdoor Golden-Hour Wedding Photography in Mumbai & Goa',
                'target_keyword'   => 'outdoor wedding photoshoot Mumbai lighting',
                'category'         => 'Planning Guides',
                'reasoning'        => 'Educates brides on coastal humidity, harsh midday glare, and optimizing ceremony timelines for cinematic portraits.',
                'estimated_intent' => 'Informational Guide',
            ],
            [
                'topic'            => 'Heritage Palace Wedding Venues in Rajasthan: Photography Logistics for Udaipur & Jaipur',
                'target_keyword'   => 'Rajasthan palace wedding photographer',
                'category'         => 'Destination Weddings',
                'reasoning'        => 'High-ticket destination wedding guide covering royal architecture, candlelight setups, and multi-day coverage.',
                'estimated_intent' => 'High Commercial',
            ],
            [
                'topic'            => 'Wedding Day Photography Checklist: What to Prepare for Unobtrusive Moments',
                'target_keyword'   => 'wedding photography checklist India',
                'category'         => 'Planning Guides',
                'reasoning'        => 'Practical advice that builds immense trust and establishes studio authority before the consultation call.',
                'estimated_intent' => 'Informational Guide',
            ],
            [
                'topic'            => 'Why 35mm Master Color Grading Matters for Indian Wedding Skin Tones & Velvet Lehengas',
                'target_keyword'   => 'luxury wedding color grading',
                'category'         => 'Cinematic Films',
                'reasoning'        => 'Showcases Paneventz signature post-production mastery, educating clients on quality over generic filters.',
                'estimated_intent' => 'Inspirational',
            ],
        ];

        $response = AiManager::generateJson($prompt, ['ideas' => $defaultIdeas], 'You are a luxury wedding content strategist. Return only clean JSON array of ideas.');

        return $response['ideas'] ?? $response ?? $defaultIdeas;
    }
}
