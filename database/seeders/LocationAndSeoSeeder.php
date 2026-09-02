<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Location;
use App\Models\SeoMetadata;
use Illuminate\Database\Seeder;

class LocationAndSeoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed High-Value Destination Photography Hubs
        $locations = [
            [
                'name'           => 'Mumbai',
                'slug'           => 'mumbai',
                'state'          => 'Maharashtra',
                'country'        => 'India',
                'headline'       => 'Luxury Wedding Photography in Mumbai & Alibaug',
                'intro'          => 'Capturing opulent celebrations, sea-facing pheras, and intimate soirees across Mumbai’s most storied heritage hotels and contemporary seaside venues.',
                'popular_venues' => ['The Taj Mahal Palace (Colaba)', 'JW Marriott Sahar', 'The St. Regis Mumbai', 'Taj Lands End', 'Sofitel BKC'],
                'faqs'           => [
                    ['q' => 'Do you document destination weddings in Alibaug and Lonavala?', 'a' => 'Yes, our team frequently documents luxury destination celebrations across Alibaug, Lonavala, Khandala, and Karjat with full crew mobilization.'],
                    ['q' => 'How far in advance should we reserve our Mumbai wedding dates?', 'a' => 'Peak Mumbai wedding season (November through February) fills up quickly. We recommend reserving 6 to 12 months in advance.'],
                ],
                'sort_order'     => 1,
            ],
            [
                'name'           => 'Udaipur',
                'slug'           => 'udaipur',
                'state'          => 'Rajasthan',
                'country'        => 'India',
                'headline'       => 'Royal Palace Wedding Photography in Udaipur',
                'intro'          => 'The City of Lakes provides a fairytale stage of marble courtyards, glistening waters, and royal Mewar grandeur. Our cinema and photography immortalize every opulent ceremony.',
                'popular_venues' => ['The Oberoi Udaivilas', 'Taj Lake Palace', 'The Leela Palace Udaipur', 'Jagmandir Island Palace', 'Raffles Udaipur', 'Fateh Prakash Palace'],
                'faqs'           => [
                    ['q' => 'How do you handle evening palace lighting in Udaipur?', 'a' => 'We utilize ultra-fast prime cine lenses and discreet wireless continuous lighting to honor the natural palace candle-glow without washing out historic architecture.'],
                    ['q' => 'Are drone permits included for lake palaces?', 'a' => 'We coordinate all licensed 4K aerial drone operations in compliance with local Udaipur administration and heritage site guidelines.'],
                ],
                'sort_order'     => 2,
            ],
            [
                'name'           => 'Goa',
                'slug'           => 'goa',
                'state'          => 'Goa',
                'country'        => 'India',
                'headline'       => 'Bespoke Coastal & Beach Wedding Photography in Goa',
                'intro'          => 'Sun-drenched coastal ceremonies, golden hour sunset vows, and barefoot beach revelry captured with high-fashion editorial aesthetics.',
                'popular_venues' => ['W Goa (Vagator)', 'The St. Regis Goa Resort', 'Taj Exotica Resort & Spa', 'Alila Diwa Goa', 'Grand Hyatt Goa (Bambolim)'],
                'faqs'           => [
                    ['q' => 'How do you protect equipment against tropical beach humidity?', 'a' => 'Our professional camera bodies and lenses are weather-sealed, and our crew carries dedicated climate-controlled gear for uninterrupted beachside capture.'],
                    ['q' => 'Do you capture pre-wedding sunset portraits in Goa?', 'a' => 'Yes, all destination Goa commissions include dedicated sunset couple portraits across secluded cliffs and heritage Latin quarters.'],
                ],
                'sort_order'     => 3,
            ],
            [
                'name'           => 'Jaipur',
                'slug'           => 'jaipur',
                'state'          => 'Rajasthan',
                'country'        => 'India',
                'headline'       => 'Grand Heritage Wedding Photography in Jaipur',
                'intro'          => 'Magnificent forts, royal jharokhas, and vibrant royal celebrations documented with rich cinematic contrast and heirloom color grading.',
                'popular_venues' => ['Rambagh Palace', 'Jai Mahal Palace', 'Fairmont Jaipur', 'Samode Palace', 'The Leela Palace Jaipur', 'JW Marriott Jaipur'],
                'faqs'           => [
                    ['q' => 'Can your team document multi-day royal events across multiple palace venues?', 'a' => 'Yes, we provide multi-camera photo and cinema crews with dedicated coordinators for seamless coverage across multiple venues.'],
                ],
                'sort_order'     => 4,
            ],
        ];

        foreach ($locations as $locData) {
            $loc = Location::updateOrCreate(
                ['slug' => $locData['slug']],
                $locData
            );

            $loc->seo()->updateOrCreate(
                [],
                [
                    'title'            => "{$loc->name} Wedding Photographer & Cinematic Films — Paneventz",
                    'meta_description' => "Award-winning luxury wedding photography and royal palace cinema in {$loc->name}, {$loc->state}. Preserving heirloom moments for unforgettable celebrations.",
                    'keywords'         => "wedding photographer {$loc->name}, luxury wedding photography {$loc->name}, destination wedding {$loc->name}, best wedding cinematographer {$loc->name}",
                    'canonical_url'    => url('/wedding-photographer-' . $loc->slug),
                    'robots'           => 'index, follow',
                    'change_frequency' => 'weekly',
                    'priority'         => 0.9,
                    'is_indexed'       => true,
                ]
            );
        }

        // 2. Seed Initial Editorial Blog Posts
        $posts = [
            [
                'title'             => 'The Ultimate Guide to Planning a Royal Palace Wedding in Udaipur',
                'slug'              => 'ultimate-guide-royal-palace-wedding-udaipur',
                'category'          => 'Destination Weddings',
                'author_name'       => 'Paneventz Editorial',
                'read_time_minutes' => 6,
                'excerpt'           => 'From iconic island venues like Jagmandir to golden hour lighting on Lake Pichola, discover insider secrets for documenting a regal celebration.',
                'content'           => "A wedding in Udaipur is unlike any celebration on earth. The shimmering reflection of ancient marble against Lake Pichola, the regal courtyard arches of the City Palace, and the gentle arrival of heritage boat processions create a cinematic canvas that demands intention and mastery.\n\n### 1. Timing the Pheras with Pichola Golden Hour\nBecause Udaipur palaces face west across the lake, the 45 minutes preceding sunset provide an unrepeatable golden illumination. We always recommend coordinating your mandap alignment to capture this warm, natural back-light.\n\n### 2. Discreet Sound & Cine Capture\nPalace courtyards have unique acoustic acoustics. Our cinema team deploys wireless low-profile lapels on the couple and priest to preserve sacred Vedic chants with studio clarity while remaining completely unobtrusive.\n\n### 3. Preserving Heirlooms for Generations\nYour wedding photographs are the only investment from your day that increases in value over time. Treat your celebration with the heirloom documentation it deserves.",
                'is_published'      => true,
                'published_at'      => now()->subDays(2),
            ],
            [
                'title'             => 'Couture Color Science: Why Authentic Indian Skin Tones Require Bespoke Film Grading',
                'slug'              => 'couture-color-science-indian-wedding-skin-tones',
                'category'          => 'Cinematic Films',
                'author_name'       => 'Paneventz Color Suite',
                'read_time_minutes' => 5,
                'excerpt'           => 'How Paneventz creates rich 35mm film emulation that honors authentic Indian skin tones and royal zardozi fabrics.',
                'content'           => "Standard camera sensor color profiles are engineered for Western lighting and often turn vibrant turmeric haldi tones into harsh neon yellows and rich royal velvet lehengas into oversaturated blocks.\n\nAt Paneventz, we built our own proprietary color transformation curves inspired by Kodak and Fujifilm 35mm motion picture negative stocks.\n\n### The Paneventz Film Pipeline\n1. True-to-life skin tones with velvety shadow rolloff\n2. Authentic preservation of bridal reds, rani pinks, and gold embroidery\n3. Archival grade color longevity that never looks dated\n\nExperience the difference of artisan film grading on your wedding day.",
                'is_published'      => true,
                'published_at'      => now()->subDays(5),
            ],
        ];

        foreach ($posts as $pData) {
            $post = BlogPost::updateOrCreate(
                ['slug' => $pData['slug']],
                $pData
            );

            $post->seo()->updateOrCreate(
                [],
                [
                    'title'            => "{$post->title} — Paneventz Journal",
                    'meta_description' => $post->excerpt,
                    'canonical_url'    => url('/blog/' . $post->slug),
                    'robots'           => 'index, follow',
                    'change_frequency' => 'monthly',
                    'priority'         => 0.7,
                    'is_indexed'       => true,
                ]
            );
        }
    }
}
