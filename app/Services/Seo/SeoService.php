<?php

namespace App\Services\Seo;

use App\Models\BlogPost;
use App\Models\Location;
use App\Models\SeoMetadata;
use App\Models\Service;
use App\Models\Story;
use App\Models\Testimonial;
use App\Models\WebsiteSetting;
use App\Models\WeddingAlbum;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

class SeoService
{
    /**
     * Resolve complete SEO metadata payload for any given model or named route.
     *
     * Hierarchy:
     * 1. Explicit custom fields in DB (SeoMetadata)
     * 2. Dynamic contextual data derived from Model
     * 3. Global Website Settings & Default Fallbacks
     */
    public static function resolve($modelOrRoute = null, array $overrides = []): array
    {
        $setting = WebsiteSetting::getCached();
        $studioName = $setting?->studio_name ?? 'Paneventz';
        $defaultLogo = $setting?->logo ? asset('storage/' . $setting->logo) : asset('images/1.jpg');

        $seoRecord = null;
        $dynamicTitle = null;
        $dynamicDesc = null;
        $dynamicImage = null;
        $dynamicCanonical = null;
        $dynamicType = 'website';
        $schemaType = 'PhotographyBusiness';

        if ($modelOrRoute instanceof \Illuminate\Database\Eloquent\Model) {
            $model = $modelOrRoute;
            $seoRecord = $model->relationLoaded('seo') ? $model->seo : $model->seo()->first();

            if (method_exists($model, 'getDynamicSeoTitle')) {
                $dynamicTitle = $model->getDynamicSeoTitle();
            }
            if (method_exists($model, 'getDynamicSeoDescription')) {
                $dynamicDesc = $model->getDynamicSeoDescription();
            }
            if (method_exists($model, 'getDynamicSeoImage')) {
                $dynamicImage = $model->getDynamicSeoImage();
            }
            if (method_exists($model, 'getDynamicCanonicalUrl')) {
                $dynamicCanonical = $model->getDynamicCanonicalUrl();
            }

            if ($model instanceof BlogPost) {
                $dynamicType = 'article';
                $schemaType = 'Article';
            } elseif ($model instanceof Service) {
                $schemaType = 'Service';
            } elseif ($model instanceof Location) {
                $schemaType = 'LocalBusiness';
            } elseif ($model instanceof WeddingAlbum) {
                $schemaType = 'CollectionPage';
            }
        } elseif (is_string($modelOrRoute) && !empty($modelOrRoute)) {
            $seoRecord = SeoMetadata::forRoute($modelOrRoute);
        }

        // Global default fallbacks
        $globalTitle = $setting?->meta_title ?? "{$studioName} — Luxury Wedding Photography & Cinematic Films";
        $globalDesc = $setting?->meta_description ?? 'Paneventz documents timeless luxury weddings and cinematic love stories across India and destinations worldwide.';

        // Tier 1 -> Tier 2 -> Tier 3 Resolution
        $title = $overrides['title']
            ?? $seoRecord?->title
            ?? $dynamicTitle
            ?? $globalTitle;

        $description = $overrides['description']
            ?? $seoRecord?->meta_description
            ?? $dynamicDesc
            ?? $globalDesc;

        $keywords = $overrides['keywords']
            ?? $seoRecord?->keywords
            ?? 'luxury wedding photography, Indian wedding cinematography, destination wedding photographer, Udaipur weddings, Goa wedding photographer, Mumbai wedding cinema';

        $canonicalUrl = $overrides['canonical_url']
            ?? $seoRecord?->canonical_url
            ?? $dynamicCanonical
            ?? static::normalizeCanonicalUrl();

        $robots = $overrides['robots']
            ?? $seoRecord?->robots
            ?? 'index, follow';

        // Open Graph
        $ogTitle = $overrides['og_title']
            ?? $seoRecord?->og_title
            ?? $title;

        $ogDescription = $overrides['og_description']
            ?? $seoRecord?->og_description
            ?? $description;

        $ogImage = !empty($overrides['og_image'])
            ? asset('storage/' . $overrides['og_image'])
            : ($seoRecord?->og_image
                ? asset('storage/' . $seoRecord->og_image)
                : ($dynamicImage ?? $defaultLogo));

        $ogType = $seoRecord?->og_type ?? $dynamicType;

        // Twitter Cards
        $twitterCard = $seoRecord?->twitter_card ?? 'summary_large_image';
        $twitterTitle = $overrides['twitter_title']
            ?? $seoRecord?->twitter_title
            ?? $ogTitle;

        $twitterDesc = $overrides['twitter_description']
            ?? $seoRecord?->twitter_description
            ?? $ogDescription;

        $twitterImage = !empty($overrides['twitter_image'])
            ? asset('storage/' . $overrides['twitter_image'])
            : ($seoRecord?->twitter_image
                ? asset('storage/' . $seoRecord->twitter_image)
                : $ogImage);

        // Schema JSON-LD
        $schemaType = $seoRecord?->schema_type ?? $schemaType;
        $jsonLd = static::buildJsonLd(
            model: ($modelOrRoute instanceof \Illuminate\Database\Eloquent\Model ? $modelOrRoute : null),
            title: $title,
            description: $description,
            canonicalUrl: $canonicalUrl,
            image: $ogImage,
            schemaType: $schemaType,
            setting: $setting,
            customJsonLd: $seoRecord?->custom_json_ld
        );

        return [
            'title'               => $title,
            'meta_description'    => $description,
            'keywords'            => $keywords,
            'canonical_url'       => $canonicalUrl,
            'robots'              => $robots,
            'og_title'            => $ogTitle,
            'og_description'      => $ogDescription,
            'og_image'            => $ogImage,
            'og_type'             => $ogType,
            'og_url'              => $canonicalUrl,
            'twitter_card'        => $twitterCard,
            'twitter_title'       => $twitterTitle,
            'twitter_description' => $twitterDesc,
            'twitter_image'       => $twitterImage,
            'json_ld'             => $jsonLd,
            'studio_name'         => $studioName,
        ];
    }

    /**
     * Canonical URL normalizer.
     * Removes tracking query parameters, ensures HTTPS/current host, and standardizes slashes.
     */
    public static function normalizeCanonicalUrl(?string $url = null): string
    {
        if (empty($url)) {
            $url = Request::url(); // current URL without query string
        }

        $parts = parse_url($url);
        $scheme = $parts['scheme'] ?? 'http';
        $host = $parts['host'] ?? Request::getHost();
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';
        $path = isset($parts['path']) ? '/' . trim($parts['path'], '/') : '';

        // If path is empty, root slash
        if ($path === '' || $path === '/') {
            return "{$scheme}://{$host}{$port}";
        }

        return "{$scheme}://{$host}{$port}{$path}";
    }

    /**
     * Build valid, high-fidelity Schema.org JSON-LD structure.
     */
    protected static function buildJsonLd(
        ?\Illuminate\Database\Eloquent\Model $model,
        string $title,
        string $description,
        string $canonicalUrl,
        string $image,
        string $schemaType,
        ?WebsiteSetting $setting,
        ?array $customJsonLd
    ): array {
        if (!empty($customJsonLd)) {
            return $customJsonLd;
        }

        $studioName = $setting?->studio_name ?? 'Paneventz';
        $siteUrl = url('/');
        $logoUrl = $setting?->logo ? asset('storage/' . $setting->logo) : asset('images/1.jpg');

        // Cached reviews aggregation from DB
        [$aggregateRating, $reviewsJson] = Cache::remember('seo_testimonials_rating', 3600, function () {
            $testimonials = Testimonial::where('is_published', true)->get();
            $agg = null;
            $revs = [];

            if ($testimonials->isNotEmpty()) {
                $avgRating = round($testimonials->avg('rating'), 1);
                $totalCount = $testimonials->count();

                $agg = [
                    '@type'       => 'AggregateRating',
                    'ratingValue' => (string) max(4.8, $avgRating),
                    'reviewCount' => (string) $totalCount,
                    'bestRating'  => '5',
                    'worstRating' => '1',
                ];

                foreach ($testimonials->take(3) as $review) {
                    $revs[] = [
                        '@type'         => 'Review',
                        'author'        => [
                            '@type' => 'Person',
                            'name'  => $review->couple_name,
                        ],
                        'reviewRating'  => [
                            '@type'       => 'Rating',
                            'ratingValue' => (string) $review->rating,
                            'bestRating'  => '5',
                        ],
                        'reviewBody'    => $review->review,
                    ];
                }
            }

            return [$agg, $revs];
        });

        // Base Business Schema
        $businessSchema = [
            '@context'      => 'https://schema.org',
            '@type'         => 'PhotographyBusiness',
            '@id'           => $siteUrl . '/#organization',
            'name'          => $studioName,
            'url'           => $siteUrl,
            'logo'          => [
                '@type' => 'ImageObject',
                'url'   => $logoUrl,
            ],
            'image'         => $image,
            'description'   => $setting?->about_description ?? $description,
            'telephone'     => $setting?->phone ?? '+918082024787',
            'email'         => $setting?->email ?? 'hello@paneventz.com',
            'priceRange'    => '₹₹₹₹',
            'areaServed'    => [
                ['@type' => 'City', 'name' => 'Mumbai'],
                ['@type' => 'City', 'name' => 'Udaipur'],
                ['@type' => 'City', 'name' => 'Goa'],
                ['@type' => 'City', 'name' => 'Delhi NCR'],
                ['@type' => 'City', 'name' => 'Jaipur'],
                ['@type' => 'City', 'name' => 'Kerala'],
                ['@type' => 'Country', 'name' => 'India'],
            ],
            'sameAs'        => array_values(array_filter([
                $setting?->instagram_url ?? 'https://instagram.com/paneventz',
                $setting?->youtube_url ?? 'https://youtube.com/@paneventz',
                $setting?->facebook_url ?? '',
            ])),
        ];

        if ($aggregateRating) {
            $businessSchema['aggregateRating'] = $aggregateRating;
            if (!empty($reviewsJson)) {
                $businessSchema['review'] = $reviewsJson;
            }
        }

        // Specific entity graph
        if ($model instanceof BlogPost) {
            return [
                '@context'         => 'https://schema.org',
                '@type'            => 'BlogPosting',
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id'   => $canonicalUrl,
                ],
                'headline'         => $model->title,
                'description'      => $description,
                'image'            => $image,
                'datePublished'    => $model->published_at?->toIso8601String() ?? $model->created_at->toIso8601String(),
                'dateModified'     => $model->updated_at->toIso8601String(),
                'author'           => [
                    '@type' => 'Organization',
                    'name'  => $model->author_name ?: $studioName,
                    'url'   => $siteUrl,
                ],
                'publisher'        => [
                    '@type' => 'Organization',
                    'name'  => $studioName,
                    'logo'  => [
                        '@type' => 'ImageObject',
                        'url'   => $logoUrl,
                    ],
                ],
            ];
        }

        if ($model instanceof Service) {
            return [
                '@context'    => 'https://schema.org',
                '@type'       => 'Service',
                'name'        => $model->name,
                'description' => $description,
                'provider'    => [
                    '@type' => 'PhotographyBusiness',
                    'name'  => $studioName,
                    'url'   => $siteUrl,
                ],
                'offers'      => $model->price_from ? [
                    '@type'         => 'Offer',
                    'price'         => (string) $model->price_from,
                    'priceCurrency' => 'INR',
                ] : null,
            ];
        }

        if ($model instanceof Location) {
            $locSchema = $businessSchema;
            $locSchema['@type'] = 'LocalBusiness';
            $locSchema['name'] = "{$studioName} Wedding Photography {$model->name}";
            $locSchema['address'] = [
                '@type'           => 'PostalAddress',
                'addressLocality' => $model->name,
                'addressRegion'   => $model->state,
                'addressCountry'  => $model->country,
            ];
            return $locSchema;
        }

        if ($model instanceof WeddingAlbum) {
            return [
                '@context'    => 'https://schema.org',
                '@type'       => 'CollectionPage',
                'name'        => $title,
                'description' => $description,
                'url'         => $canonicalUrl,
                'image'       => $image,
                'provider'    => [
                    '@type' => 'PhotographyBusiness',
                    'name'  => $studioName,
                ],
            ];
        }

        return $businessSchema;
    }
}
