@extends('layouts.app')

@section('title', 'Find Your Wedding Photos – Paneventz AI Client Galleries')
@section('description', 'Browse wedding celebrations or search by couple name to find and download all your photos using facial recognition AI.')

@section('content')
<div style="background: #0d0d0d; color: #fff; min-height: 100vh; padding-bottom: 100px;">

    <!-- TOP NAVIGATION -->
    <nav style="position: relative; background: #141414; border-bottom: 1px solid rgba(255,255,255,0.08); padding: 20px 8%; display: flex; justify-content: space-between; align-items: center;">
        <div class="logo">
            <a href="/" style="color: #fff; text-decoration: none;">Paneventz</a>
        </div>
        <div class="nav-links">
            <a href="/#stories">Stories</a>
            <a href="/#films">Films</a>
            <a href="/services">Services</a>
            <a href="/galleries" style="color: #c4a472;">Find Your Photos 📸</a>
            <a href="/#contact">Contact</a>
        </div>

        <button type="button" class="mobile-nav-toggle" onclick="toggleMobileNav()" aria-label="Open Mobile Menu">
            ☰
        </button>
    </nav>

    <!-- HERO HEADER -->
    <section style="text-align: center; padding: 80px 8% 50px; background: linear-gradient(180deg, #141414 0%, #0d0d0d 100%);">
        <span class="intro-small" style="color: #c4a472;">CLIENT GALLERIES & GUEST PORTAL</span>
        <h1 style="font-family: Georgia, serif; font-size: clamp(34px, 5vw, 65px); font-weight: normal; margin: 15px 0 12px; color: #fff;">
            Find Your Celebration Photos
        </h1>
        <p style="color: #888; font-size: 15px; max-width: 650px; margin: 0 auto 35px; line-height: 1.8;">
            Select your couple's wedding below, take a quick 1-second selfie, and our AI will automatically find every photo you appear in!
        </p>

        <!-- SEARCH BAR -->
        <form method="GET" action="/galleries" style="max-width: 550px; margin: 0 auto; display: flex; gap: 10px;">
            <input 
                type="text" 
                name="q" 
                value="{{ $search }}" 
                placeholder="Search by couple name or city (e.g. Aditi, Udaipur)..." 
                class="form-control" 
                style="background: #1c1c1e; border: 1px solid rgba(255,255,255,0.15); color: #fff; padding: 14px 20px; border-radius: 30px; font-size: 14px; flex: 1; outline: none;"
            >
            <button type="submit" class="film-button" style="background: #c4a472; color: #111; border: none; font-weight: 700; border-radius: 30px; padding: 14px 28px; cursor: pointer;">
                Search 🔍
            </button>
        </form>
    </section>

    <!-- ALBUMS GRID -->
    <section style="padding: 20px 8%; max-width: 1400px; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px;">
            @forelse($albums as $album)
                <div style="background: #161617; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.4s ease, border-color 0.4s ease;" onmouseover="this.style.transform='translateY(-6px)'; this.style.borderColor='rgba(196,164,114,0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(255,255,255,0.08)';">
                    
                    <!-- ALBUM COVER -->
                    <div style="position: relative; aspect-ratio: 16/10; overflow: hidden; background: #222;">
                        @if($album->cover_image)
                            <img src="{{ asset('storage/' . $album->cover_image) }}" alt="{{ $album->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #555; font-size: 32px;">
                                💍
                            </div>
                        @endif

                        @if(!empty($album->pin_code))
                            <span style="position: absolute; top: 12px; right: 12px; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); color: #c4a472; font-size: 11px; padding: 4px 10px; border-radius: 20px; border: 1px solid rgba(196,164,114,0.4);">
                                🔒 Passcode Protected
                            </span>
                        @endif

                        <span style="position: absolute; bottom: 12px; left: 12px; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); color: #fff; font-size: 11px; padding: 4px 10px; border-radius: 20px;">
                            📸 {{ $album->photos_count }} {{ Str::plural('Photo', $album->photos_count) }}
                        </span>
                    </div>

                    <!-- ALBUM DETAILS -->
                    <div style="padding: 24px; display: flex; flex-direction: column; flex: 1; justify-content: space-between;">
                        <div>
                            <span style="font-size: 10px; letter-spacing: 2px; text-transform: uppercase; color: #c4a472; font-weight: 600;">
                                {{ $album->couple_names ?: 'CELEBRATION' }}
                            </span>
                            <h3 style="font-family: Georgia, serif; font-size: 21px; font-weight: normal; color: #fff; margin: 6px 0 10px;">
                                {{ $album->title }}
                            </h3>
                            <div style="font-size: 12.5px; color: #888; display: flex; gap: 14px; flex-wrap: wrap;">
                                <span>📍 {{ $album->location ?: 'Location TBD' }}</span>
                                <span>📅 {{ $album->event_date ? $album->event_date->format('d M Y') : 'Date TBD' }}</span>
                            </div>
                        </div>

                        <div style="margin-top: 24px;">
                            <a href="{{ $album->guest_url }}" class="film-button" style="display: block; text-align: center; background: #c4a472; color: #111; font-weight: 700; text-decoration: none; border-radius: 6px; padding: 12px 20px;">
                                Open Gallery & Face Finder ➔
                            </a>
                        </div>
                    </div>

                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 80px 20px; color: #71717a;">
                    <div style="font-size: 36px; margin-bottom: 12px;">🔍</div>
                    <h3 style="font-size: 20px; color: #fff; font-weight: normal; margin-bottom: 8px;">No Wedding Galleries Found</h3>
                    <p style="font-size: 14px; max-width: 400px; margin: 0 auto 20px;">
                        @if($search)
                            We couldn't find any wedding matching "{{ $search }}". Try searching for another name.
                        @else
                            No public wedding galleries have been created yet.
                        @endif
                    </p>
                    @if($search)
                        <a href="/galleries" class="film-button">View All Galleries</a>
                    @endif
                </div>
            @endforelse
        </div>
    </section>

</div>
@endsection