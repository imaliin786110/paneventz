@extends('layouts.app')

@section('seo_override')
    <x-seo-meta :seo="$seo" />
@endsection

@section('content')
@php
    $setting = \App\Models\WebsiteSetting::first();
    $breadcrumbs = [
        ['name' => 'Home', 'url' => '/'],
        ['name' => 'Destinations', 'url' => '/#about'],
        ['name' => $location->name, 'url' => ''],
    ];
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
            <a href="javascript:void(0)" onclick="openEnquiryModal('{{ addslashes($location->name) }} Wedding')" class="enquire">
                Enquire
            </a>
        </div>

        <button type="button" class="mobile-nav-toggle" onclick="toggleMobileNav()" aria-label="Open Mobile Menu">
            ☰
        </button>
    </nav>

    <!-- LOCATION HERO HEADER -->
    <section class="intro" style="padding-top: 170px; padding-bottom: 80px; position: relative; {{ $location->hero_image ? "background: linear-gradient(180deg, rgba(10,14,24,0.85) 0%, rgba(8,10,15,0.98) 100%), url('" . asset('storage/' . $location->hero_image) . "') center/cover;" : '' }}">
        <div class="intro-container">
            <x-breadcrumbs :items="$breadcrumbs" />

            <span class="intro-small" style="margin-top: 15px;">
                DESTINATION WEDDING PHOTOGRAPHY & FILMS · {{ strtoupper($location->name) }}
            </span>

            <h2>
                {{ $location->headline ?: ("Luxury Wedding Photography in " . $location->name) }}
            </h2>

            <p style="font-size: 16px; color: #cbd5e1; max-width: 780px;">
                {{ $location->intro ?: ("Documenting timeless moments, royal palace celebrations, and intimate destination weddings across " . $location->name . " and surrounding heritage regions.") }}
            </p>

            <div style="margin-top: 35px; display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">
                <a href="javascript:void(0)" onclick="openEnquiryModal('{{ addslashes($location->name) }} Wedding')" class="hero-button" style="margin-top: 0; background: #c4a472; color: #080a0f; border-color: #c4a472; font-weight: 600;">
                    Check Availability For {{ $location->name }} →
                </a>
                <a href="#venues" class="hero-button" style="margin-top: 0; border: 1px solid rgba(255,255,255,0.3); color: #fff;">
                    Iconic Venues ↓
                </a>
            </div>
        </div>
    </section>

    <!-- POPULAR WEDDING VENUES IN LOCATION -->
    @if(!empty($location->popular_venues) && is_array($location->popular_venues))
        <section id="venues" style="max-width: 1200px; margin: 60px auto 40px; padding: 0 6%;">
            <div style="text-align: center; margin-bottom: 40px;">
                <span style="font-size: 11px; letter-spacing: 3px; text-transform: uppercase; color: #c4a472; font-weight: 600;">
                    CURATED HERITAGE & LUXURY LOCATIONS
                </span>
                <h3 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: clamp(28px, 4vw, 44px); font-weight: 300; color: #fff; margin-top: 10px;">
                    Iconic Venues We Document in {{ $location->name }}
                </h3>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
                @foreach($location->popular_venues as $venue)
                    <div style="background: rgba(18, 24, 36, 0.6); border: 1px solid rgba(255, 255, 255, 0.08); padding: 26px 22px; border-radius: 8px; backdrop-filter: blur(10px); transition: border-color 0.3s;" onmouseover="this.style.borderColor='rgba(196,164,114,0.5)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.08)'">
                        <span style="color: #c4a472; font-size: 18px; display: block; margin-bottom: 8px;">🏛️</span>
                        <h4 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 20px; color: #fff; margin-bottom: 6px; font-weight: 400;">
                            {{ $venue }}
                        </h4>
                        <span style="font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; color: #94a3b8;">
                            {{ $location->name }}, {{ $location->state }}
                        </span>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- FEATURED WEDDING STORIES -->
    <section class="stories" style="padding-top: 60px; padding-bottom: 80px;">
        <div class="stories-heading" style="margin-bottom: 70px;">
            <div class="intro-small">PORTFOLIO SPOTLIGHT</div>
            <h2>Celebrations In {{ $location->name }}</h2>
            <p>A glimpse into our discreet editorial documentation and couture color-graded cinema.</p>
        </div>

        @foreach($stories as $index => $story)
            @php
                $isReverse = ($index % 2 === 1);
                $isLarge = ($index % 2 === 0);
                $class = $isReverse ? 'story story-reverse' : ($isLarge ? 'story story-large' : 'story');
                $coverUrl = $story->cover_image ? asset('storage/' . $story->cover_image) : asset('images/1.png');
                $galleryData = $story->gallery ? json_encode(array_map(fn($img) => asset('storage/' . $img), $story->gallery)) : '[]';
                $altText = \App\Services\Seo\AltTextGenerator::for($story);
            @endphp

            <div class="{{ $class }}">
                <div class="story-image" style="cursor: pointer;" onclick="openStoryGallery('{{ addslashes($story->couple_name) }}', '{{ $coverUrl }}', {{ $galleryData }})">
                    <img src="{{ $coverUrl }}" alt="{{ $altText }}" loading="lazy" decoding="async">
                </div>

                <div class="story-info">
                    <span class="story-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3>{{ $story->couple_name }}</h3>
                    <span class="story-location">{{ $story->location ?: $location->name }}</span>
                    <a href="javascript:void(0)" onclick="openStoryGallery('{{ addslashes($story->couple_name) }}', '{{ $coverUrl }}', {{ $galleryData }})" class="story-link">
                        View Photo Story →
                    </a>
                </div>
            </div>
        @endforeach
    </section>

    <!-- LOCALIZED FAQS ACCORDION -->
    @if(!empty($location->faqs) && is_array($location->faqs))
        <section style="max-width: 900px; margin: 40px auto 100px; padding: 0 6%;">
            <div style="text-align: center; margin-bottom: 45px;">
                <span style="font-size: 11px; letter-spacing: 3px; text-transform: uppercase; color: #c4a472; font-weight: 600;">
                    COMMONLY ASKED QUESTIONS
                </span>
                <h3 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: clamp(28px, 4.5vw, 48px); font-weight: 300; color: #fff; margin-top: 10px;">
                    Planning Your Wedding in {{ $location->name }}
                </h3>
            </div>

            <div style="display: flex; flex-direction: column; gap: 16px;">
                @foreach($location->faqs as $faq)
                    <div style="background: #0e1118; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 24px 28px;">
                        <h4 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 21px; color: #fff; margin-bottom: 12px; font-weight: 500;">
                            {{ $faq['q'] ?? ($faq['question'] ?? '') }}
                        </h4>
                        <p style="color: #94a3b8; font-size: 14.5px; line-height: 1.8; margin: 0;">
                            {{ $faq['a'] ?? ($faq['answer'] ?? '') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- OTHER DESTINATIONS (INTERNAL LINKING) -->
    @if($allLocations->isNotEmpty())
        <section style="background: #090b10; border-top: 1px solid rgba(255, 255, 255, 0.06); padding: 70px 6%; text-align: center;">
            <span style="font-size: 11px; letter-spacing: 3px; text-transform: uppercase; color: #c4a472; font-weight: 600;">
                PAN-INDIA & WORLDWIDE DESTINATIONS
            </span>
            <h4 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 32px; color: #fff; margin: 12px 0 30px; font-weight: 300;">
                Explore Other Wedding Destinations
            </h4>

            <div style="display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">
                @foreach($allLocations as $loc)
                    <a href="{{ $loc->url }}" style="background: rgba(18,24,36,0.7); border: 1px solid rgba(255,255,255,0.1); color: #e2e8f0; padding: 12px 24px; border-radius: 30px; font-size: 12px; letter-spacing: 1.5px; text-transform: uppercase; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.borderColor='#c4a472'; this.style.color='#c4a472'" onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.color='#e2e8f0'">
                        📍 {{ $loc->name }}
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- FOOTER -->
    <footer id="contact" style="background: #080809; border-top: 1px solid rgba(255, 255, 255, 0.08); padding: 90px 8% 40px; text-align: center;">
        <!-- LUXURY CONTACT CARDS HUB -->
        <div class="premium-contact-grid" style="margin-bottom: 35px;">
            <!-- CARD 1: DIRECT CALLS -->
            <div class="premium-contact-card">
                <div>
                    <div class="premium-contact-label">
                        <span style="color: #c4a472;">📞</span> Direct Studio Lines
                    </div>
                    <div class="premium-phone-group">
                        <a href="tel:+918082024787" class="premium-phone-link">
                            <span class="premium-phone-badge">✦</span>
                            <span>+91 &nbsp;80820 &nbsp;24787</span>
                        </a>
                        <a href="tel:+919821337523" class="premium-phone-link">
                            <span class="premium-phone-badge">✦</span>
                            <span>+91 &nbsp;98213 &nbsp;37523</span>
                        </a>
                    </div>
                </div>
                <div class="premium-contact-sub">
                    Available 9:00 AM – 9:00 PM IST · Direct Concierge
                </div>
            </div>

            <!-- CARD 2: WHATSAPP -->
            <div class="premium-contact-card">
                <div>
                    <div class="premium-contact-label">
                        <span style="color: #25D366;">💬</span> WhatsApp
                    </div>
                    <div style="margin-top: 6px;">
                        <a href="https://wa.me/918082024787?text=Hello%20Paneventz!%20I%20would%20like%20to%20inquire%20about%20your%20wedding%20photography%20%26%20films." target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 10px; background: rgba(37, 211, 102, 0.12); border: 1px solid rgba(37, 211, 102, 0.4); color: #25D366; text-decoration: none; padding: 12px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; letter-spacing: 1px; transition: all 0.3s;" onmouseover="this.style.background='rgba(37, 211, 102, 0.22)'" onmouseout="this.style.background='rgba(37, 211, 102, 0.12)'">
                            <span>💬</span> Chat On WhatsApp ↗
                        </a>
                    </div>
                </div>
                <div class="premium-contact-sub">
                    Fastest Response · Dates & Brochure Inquiries
                </div>
            </div>

            <!-- CARD 3: EMAIL -->
            <div class="premium-contact-card">
                <div>
                    <div class="premium-contact-label">
                        <span style="color: #38bdf8;">✉</span> Studio Correspondence
                    </div>
                    <div style="margin-top: 6px;">
                        <a href="mailto:imaliinmirza@gmail.com" style="color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 600; letter-spacing: 0.5px; display: inline-flex; align-items: center; gap: 10px; padding: 8px 0; transition: color 0.3s;" onmouseover="this.style.color='#c4a472'" onmouseout="this.style.color='#ffffff'">
                            <span class="premium-phone-badge" style="color: #38bdf8; border-color: rgba(56, 189, 248, 0.35); background: rgba(56, 189, 248, 0.1);">✉</span>
                            <span>imaliinmirza@gmail.com</span>
                        </a>
                    </div>
                </div>
                <div class="premium-contact-sub">
                    Formal Wedding Proposals & Commissions
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: center; gap: 28px; flex-wrap: wrap; margin-bottom: 35px;">
            <a href="/#stories" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Stories</a>
            <a href="/#films" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Films</a>
            <a href="/services" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Services</a>
            <a href="/terms" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Terms & Conditions</a>
            <a href="/client-portal" style="color: #00f0ff; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; font-weight: 600;">Download Story 🔒</a>
            <a href="/galleries" style="color: #c4a472; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; font-weight: 600;">Guest Photos AI 📸</a>
            <a href="/blog" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Journal</a>
        </div>
        <div style="font-size: 11px; letter-spacing: 1.5px; color: #555; text-transform: uppercase;">
            {{ $setting?->footer_copyright ?: ('© ' . date('Y') . ' Paneventz Studio. Mumbai · Pan-India & Worldwide Destination Celebrations.') }}
        </div>
    </footer>

    <!-- ENQUIRY MODAL -->
    <div id="enquiryModal" class="modal-overlay" onclick="closeModalOnBackdrop(event, 'enquiryModal')">
        <div class="modal-card">
            <button type="button" class="modal-close" onclick="closeEnquiryModal()">&times;</button>
            <div style="text-align: center; margin-bottom: 30px;">
                <span class="intro-small" style="color: #c4a472;">RESERVE YOUR DATE</span>
                <h2 style="font-family: Georgia, serif; font-size: 30px; font-weight: normal; margin-top: 8px; color: #fff;">Let's Tell Your Story</h2>
                <p style="color: #888; font-size: 13px; margin-top: 8px;">Fill out the form below and we will be in touch to discuss capturing your wedding in {{ $location->name }}.</p>
            </div>

            <form id="enquiryForm" onsubmit="submitEnquiry(event)">
                @csrf
                <input type="hidden" name="service" id="modalServiceInput" value="{{ $location->name }} Wedding Photography & Cinema">

                <div class="form-row">
                    <div class="form-group">
                        <label>Your Name(s) *</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Aditi & Kabir" required>
                    </div>
                    <div class="form-group">
                        <label>Phone / WhatsApp *</label>
                        <input type="tel" name="phone" class="form-control" placeholder="+91 98765 43210" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="aditi@example.com">
                    </div>
                    <div class="form-group">
                        <label>Wedding Date</label>
                        <input type="date" name="wedding_date" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label>Wedding Venue / City</label>
                    <input type="text" name="wedding_location" class="form-control" value="{{ $location->name }}, {{ $location->state }}" placeholder="e.g. {{ $location->name }}">
                </div>

                <div class="form-group">
                    <label>Tell Us About Your Celebration</label>
                    <textarea name="message" class="form-control" rows="3" placeholder="Ceremonies planned, guest count, your vision..."></textarea>
                </div>

                <button type="submit" id="submitBtn" class="btn-submit">Submit Inquiry</button>
            </form>
        </div>
    </div>
@endsection
