@props(['seo' => null, 'model' => null, 'route' => null, 'overrides' => []])

@php
    $seoData = $seo ?? \App\Services\Seo\SeoService::resolve($model ?? $route, $overrides);
    $setting = \App\Models\WebsiteSetting::first();
@endphp

    <!-- Primary SEO Meta Tags -->
    <title>{{ html_entity_decode($seoData['title'], ENT_QUOTES, 'UTF-8') }}</title>
    <meta name="title" content="{{ html_entity_decode($seoData['title'], ENT_QUOTES, 'UTF-8') }}">
    <meta name="description" content="{{ html_entity_decode($seoData['meta_description'], ENT_QUOTES, 'UTF-8') }}">
    @if(!empty($seoData['keywords']))
        <meta name="keywords" content="{{ html_entity_decode($seoData['keywords'], ENT_QUOTES, 'UTF-8') }}">
    @endif
    <meta name="robots" content="{{ $seoData['robots'] }}">
    <link rel="canonical" href="{{ $seoData['canonical_url'] }}">

    <!-- Favicon -->
    @if($setting?->favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $setting->favicon) }}">
        <link rel="apple-touch-icon" href="{{ asset('storage/' . $setting->favicon) }}">
    @endif

    <!-- Open Graph / WhatsApp / Facebook / LinkedIn Preview -->
    <meta property="og:type" content="{{ $seoData['og_type'] }}">
    <meta property="og:url" content="{{ $seoData['og_url'] }}">
    <meta property="og:title" content="{{ $seoData['og_title'] }}">
    <meta property="og:description" content="{{ $seoData['og_description'] }}">
    <meta property="og:image" content="{{ $seoData['og_image'] }}">
    <meta property="og:site_name" content="{{ $seoData['studio_name'] }}">
    <meta property="og:locale" content="en_IN">

    <!-- Twitter / X Cards -->
    <meta name="twitter:card" content="{{ $seoData['twitter_card'] }}">
    <meta name="twitter:url" content="{{ $seoData['og_url'] }}">
    <meta name="twitter:title" content="{{ $seoData['twitter_title'] }}">
    <meta name="twitter:description" content="{{ $seoData['twitter_description'] }}">
    <meta name="twitter:image" content="{{ $seoData['twitter_image'] }}">

    <!-- Structured Data (JSON-LD) for Search Engine Rich Snippets -->
    @if(!empty($seoData['json_ld']))
        <script type="application/ld+json">
        {!! json_encode($seoData['json_ld'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
    @endif

    <!-- Third-Party Analytics & Conversion Code -->
    @if($setting?->analytics_code)
        {!! $setting->analytics_code !!}
    @endif
