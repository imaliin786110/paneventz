@extends('layouts.app')

@section('seo_override')
    <x-seo-meta :seo="$seo" />
@endsection

@section('content')
@php
    $setting = \App\Models\WebsiteSetting::first();
    $breadcrumbs = [
        ['name' => 'Home', 'url' => '/'],
        ['name' => 'Journal', 'url' => '/blog'],
        ['name' => $post->title, 'url' => ''],
    ];
    $cover = $post->featured_image ? asset('storage/' . $post->featured_image) : asset('images/1.png');
    $alt = \App\Services\Seo\AltTextGenerator::for($post);
    $shareUrl = urlencode($post->url);
    $shareTitle = urlencode($post->title);
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
            <a href="/blog" style="color: #c4a472;">Journal</a>
            <a href="javascript:void(0)" onclick="openEnquiryModal()" class="enquire">
                Enquire
            </a>
        </div>

        <button type="button" class="mobile-nav-toggle" onclick="toggleMobileNav()" aria-label="Open Mobile Menu">
            ☰
        </button>
    </nav>

    <!-- ARTICLE HEADER -->
    <header style="padding-top: 170px; padding-bottom: 40px; text-align: center; max-width: 900px; margin: 0 auto; padding-left: 6%; padding-right: 6%;">
        <x-breadcrumbs :items="$breadcrumbs" />

        <span style="font-size: 11px; letter-spacing: 3px; text-transform: uppercase; color: #c4a472; font-weight: 600; display: inline-block; margin-top: 15px; margin-bottom: 12px;">
            {{ $post->category }}
        </span>

        <h1 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: clamp(34px, 5.5vw, 62px); font-weight: 300; line-height: 1.15; color: #fff; margin-bottom: 25px;">
            {{ $post->title }}
        </h1>

        <div style="display: flex; justify-content: center; align-items: center; gap: 20px; font-size: 12px; letter-spacing: 1px; color: #94a3b8; text-transform: uppercase;">
            <span>By {{ $post->author_name }}</span>
            <span>·</span>
            <span>{{ $post->published_at ? $post->published_at->format('F d, Y') : $post->created_at->format('F d, Y') }}</span>
            <span>·</span>
            <span>{{ $post->read_time_minutes }} Min Read</span>
        </div>
    </header>

    <!-- FEATURED HERO IMAGE -->
    <div style="max-width: 1050px; margin: 0 auto 50px; padding: 0 6%;">
        <div style="aspect-ratio: 16/9; overflow: hidden; border-radius: 12px; background: #000; box-shadow: 0 20px 50px rgba(0,0,0,0.6);">
            <img src="{{ $cover }}" alt="{{ $alt }}" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
    </div>

    <!-- ARTICLE BODY -->
    <main style="max-width: 820px; margin: 0 auto 80px; padding: 0 6%; color: #d1d5db; font-size: 16.5px; line-height: 1.95;">
        @if(!empty($post->excerpt))
            <p style="font-size: 20px; font-family: 'Cormorant Garamond', Georgia, serif; font-style: italic; color: #e2e8f0; line-height: 1.6; border-left: 2px solid #c4a472; padding-left: 22px; margin-bottom: 35px;">
                {{ $post->excerpt }}
            </p>
        @endif

        <div class="article-rich-text" style="color: #cbd5e1;">
            {!! nl2br(e($post->content)) !!}
        </div>

        <!-- SOCIAL SHARING ICONS -->
        <div style="margin-top: 60px; padding: 30px 0; border-top: 1px solid rgba(255,255,255,0.08); border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <span style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #c4a472; font-weight: 600;">
                Share This Article
            </span>
            <div style="display: flex; gap: 12px;">
                <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" style="background: #25D366; color: #fff; padding: 8px 16px; border-radius: 20px; font-size: 11px; letter-spacing: 1px; text-decoration: none; font-weight: 600;">
                    WhatsApp
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" style="background: #1877F2; color: #fff; padding: 8px 16px; border-radius: 20px; font-size: 11px; letter-spacing: 1px; text-decoration: none; font-weight: 600;">
                    Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" style="background: #111; border: 1px solid #444; color: #fff; padding: 8px 16px; border-radius: 20px; font-size: 11px; letter-spacing: 1px; text-decoration: none; font-weight: 600;">
                    X / Twitter
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" style="background: #0A66C2; color: #fff; padding: 8px 16px; border-radius: 20px; font-size: 11px; letter-spacing: 1px; text-decoration: none; font-weight: 600;">
                    LinkedIn
                </a>
            </div>
        </div>

        <!-- CALL TO ACTION BOX -->
        <div style="margin-top: 50px; background: rgba(18,24,36,0.7); border: 1px solid rgba(196,164,114,0.3); border-radius: 10px; padding: 40px 30px; text-align: center;">
            <span style="font-size: 11px; letter-spacing: 3px; text-transform: uppercase; color: #c4a472; font-weight: 600;">
                COMMISSION PANEVENTZ FOR YOUR CELEBRATION
            </span>
            <h3 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 32px; color: #fff; margin: 12px 0 15px; font-weight: 300;">
                Preserving Your Heirloom Legacy
            </h3>
            <p style="color: #94a3b8; font-size: 14.5px; max-width: 550px; margin: 0 auto 25px;">
                Now reserving select wedding dates across Mumbai, Udaipur, Goa, Delhi NCR, and worldwide destinations.
            </p>
            <a href="javascript:void(0)" onclick="openEnquiryModal()" class="hero-button" style="margin-top: 0; background: #c4a472; color: #080a0f; border-color: #c4a472; font-weight: 600;">
                Inquire For Your Wedding →
            </a>
        </div>
    </main>

    <!-- RELATED ARTICLES -->
    @if($relatedPosts->isNotEmpty())
        <section style="background: #090b10; border-top: 1px solid rgba(255, 255, 255, 0.06); padding: 70px 6% 90px;">
            <div style="max-width: 1100px; margin: 0 auto;">
                <div style="text-align: center; margin-bottom: 40px;">
                    <span style="font-size: 11px; letter-spacing: 3px; text-transform: uppercase; color: #c4a472; font-weight: 600;">
                        MORE FROM THE JOURNAL
                    </span>
                    <h3 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 34px; color: #fff; margin-top: 8px; font-weight: 300;">
                        Related Articles & Stories
                    </h3>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 28px;">
                    @foreach($relatedPosts as $rel)
                        @php
                            $relCover = $rel->featured_image ? asset('storage/' . $rel->featured_image) : asset('images/1.png');
                            $relAlt = \App\Services\Seo\AltTextGenerator::for($rel);
                        @endphp
                        <div style="background: #0e1118; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; overflow: hidden;">
                            <a href="{{ $rel->url }}" style="display: block; aspect-ratio: 16/10; overflow: hidden;">
                                <img src="{{ $relCover }}" alt="{{ $relAlt }}" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                            </a>
                            <div style="padding: 22px;">
                                <span style="font-size: 10.5px; letter-spacing: 1.5px; text-transform: uppercase; color: #c4a472; display: block; margin-bottom: 8px;">
                                    {{ $rel->category }}
                                </span>
                                <h4 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 20px; margin-bottom: 10px;">
                                    <a href="{{ $rel->url }}" style="color: #fff; text-decoration: none;">
                                        {{ $rel->title }}
                                    </a>
                                </h4>
                                <a href="{{ $rel->url }}" style="color: #c4a472; font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; text-decoration: none; font-weight: 600;">
                                    Read Story →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
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
            <a href="/blog" style="color: #c4a472; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; font-weight: 600;">Journal</a>
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
                <p style="color: #888; font-size: 13px; margin-top: 8px;">Fill out the form below and we will be in touch to discuss capturing your wedding.</p>
            </div>

            <form id="enquiryForm" onsubmit="submitEnquiry(event)">
                @csrf
                <input type="hidden" name="service" id="modalServiceInput">

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
                    <input type="text" name="wedding_location" class="form-control" placeholder="e.g. Udaipur, Rajasthan">
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
