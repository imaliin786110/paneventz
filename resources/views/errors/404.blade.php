@extends('layouts.app')

@section('title', 'Page Not Found — Paneventz Luxury Wedding Photography')
@section('description', 'The requested page could not be located. Explore our luxury wedding stories, films, and photography collections.')

@section('content')
@php
    $setting = \App\Models\WebsiteSetting::first();
@endphp

    <nav>
        <div class="logo">
            <a href="/" style="color: #fff; text-decoration: none;">
                @if($setting?->logo)
                    <img src="{{ asset('storage/' . $setting->logo) }}" alt="{{ $setting->studio_name }}" style="max-height: 38px;">
                @else
                    {{ $setting?->studio_name ?? 'Paneventz' }}
                @endif
            </a>
        </div>

        <div class="nav-links">
            <a href="/#stories">Stories</a>
            <a href="/#films">Films</a>
            <a href="/services">Services</a>
            <a href="/galleries" style="color: #c4a472;">Guest Photos AI 📸</a>
            <a href="/blog">Journal</a>
            <a href="javascript:void(0)" onclick="openEnquiryModal()" class="enquire">
                Enquire
            </a>
        </div>

        <button type="button" class="mobile-nav-toggle" onclick="toggleMobileNav()" aria-label="Open Mobile Menu">
            ☰
        </button>
    </nav>

    <section style="min-height: 80vh; display: flex; align-items: center; justify-content: center; text-align: center; padding: 120px 8% 80px; background: radial-gradient(circle at center, rgba(20, 26, 38, 0.7) 0%, rgba(8, 10, 15, 1) 100%);">
        <div style="max-width: 700px; margin: 0 auto;">
            <span style="font-size: 11px; letter-spacing: 5px; text-transform: uppercase; color: #c4a472; font-weight: 600; display: block; margin-bottom: 20px;">
                ERROR 404 · MOMENT NOT FOUND
            </span>
            <h1 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: clamp(48px, 8vw, 100px); font-weight: 300; line-height: 1; color: #fff; margin-bottom: 25px;">
                The Path Has Moved.
            </h1>
            <p style="color: #94a3b8; font-size: 16px; line-height: 1.8; margin-bottom: 40px; max-width: 550px; margin-left: auto; margin-right: auto;">
                The wedding story or page you are looking for might have been archived or renamed. Explore our collections below.
            </p>

            <div style="display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">
                <a href="/" class="hero-button" style="margin-top: 0; background: #c4a472; color: #080a0f; border-color: #c4a472; font-weight: 600;">
                    Return Home →
                </a>
                <a href="/galleries" class="hero-button" style="margin-top: 0; border: 1px solid rgba(255,255,255,0.3); color: #fff;">
                    Browse Galleries 📸
                </a>
                <a href="/services" class="hero-button" style="margin-top: 0; border: 1px solid rgba(255,255,255,0.3); color: #fff;">
                    View Collections
                </a>
            </div>
        </div>
    </section>
@endsection
