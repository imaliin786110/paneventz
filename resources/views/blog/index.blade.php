@extends('layouts.app')

@section('seo_override')
    <x-seo-meta :seo="$seo" />
@endsection

@section('content')
@php
    $setting = \App\Models\WebsiteSetting::first();
    $breadcrumbs = [
        ['name' => 'Home', 'url' => '/'],
        ['name' => 'Journal', 'url' => ''],
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
            <a href="/blog" style="color: #c4a472;">Journal</a>
            <a href="javascript:void(0)" onclick="openEnquiryModal()" class="enquire">
                Enquire
            </a>
        </div>

        <button type="button" class="mobile-nav-toggle" onclick="toggleMobileNav()" aria-label="Open Mobile Menu">
            ☰
        </button>
    </nav>

    <!-- BLOG HERO -->
    <section class="intro" style="padding-top: 170px; padding-bottom: 70px;">
        <div class="intro-container">
            <x-breadcrumbs :items="$breadcrumbs" />

            <span class="intro-small" style="margin-top: 15px;">
                WEDDING JOURNAL & CINEMATIC INSIGHTS
            </span>

            <h2>The Paneventz Journal</h2>

            <p style="font-size: 16px; color: #94a3b8; max-width: 700px;">
                Editorial guides, destination planning advice, color science insights, and stories behind our heirloom celebrations.
            </p>

            <!-- CATEGORY CHIPS -->
            @if($categories->isNotEmpty())
                <div style="margin-top: 35px; display: flex; justify-content: center; gap: 12px; flex-wrap: wrap;">
                    <a href="/blog" style="background: {{ empty($selectedCategory) ? '#c4a472' : 'rgba(255,255,255,0.06)' }}; color: {{ empty($selectedCategory) ? '#080a0f' : '#cbd5e1' }}; border: 1px solid rgba(255,255,255,0.1); padding: 8px 20px; border-radius: 20px; font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; text-decoration: none; font-weight: 600;">
                        All Articles
                    </a>
                    @foreach($categories as $cat)
                        <a href="/blog?category={{ urlencode($cat) }}" style="background: {{ $selectedCategory === $cat ? '#c4a472' : 'rgba(255,255,255,0.06)' }}; color: {{ $selectedCategory === $cat ? '#080a0f' : '#cbd5e1' }}; border: 1px solid rgba(255,255,255,0.1); padding: 8px 20px; border-radius: 20px; font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; text-decoration: none; font-weight: 500;">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- BLOG GRID -->
    <section style="max-width: 1200px; margin: 0 auto 100px; padding: 0 6%;">
        @if($posts->isEmpty())
            <div style="text-align: center; padding: 80px 20px; color: #888;">
                <p style="font-size: 18px;">No journal entries published yet. Stay tuned for upcoming wedding stories.</p>
            </div>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 35px;">
                @foreach($posts as $post)
                    @php
                        $cover = $post->featured_image ? asset('storage/' . $post->featured_image) : asset('images/1.png');
                        $alt = \App\Services\Seo\AltTextGenerator::for($post);
                    @endphp
                    <article style="background: rgba(14, 18, 28, 0.6); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.3s, border-color 0.3s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.borderColor='rgba(196,164,114,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(255,255,255,0.08)'">
                        <a href="{{ $post->url }}" style="display: block; aspect-ratio: 16/10; overflow: hidden; background: #000;">
                            <img src="{{ $cover }}" alt="{{ $alt }}" loading="lazy" decoding="async" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        </a>

                        <div style="padding: 28px 24px; flex-grow: 1; display: flex; flex-direction: column;">
                            <div style="display: flex; justify-content: space-between; font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; color: #c4a472; margin-bottom: 12px;">
                                <span>{{ $post->category }}</span>
                                <span style="color: #71717a;">{{ $post->read_time_minutes }} MIN READ</span>
                            </div>

                            <h3 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 24px; line-height: 1.25; margin-bottom: 12px; font-weight: 400;">
                                <a href="{{ $post->url }}" style="color: #fff; text-decoration: none;">
                                    {{ $post->title }}
                                </a>
                            </h3>

                            <p style="color: #94a3b8; font-size: 14px; line-height: 1.7; margin-bottom: 20px; flex-grow: 1;">
                                {{ $post->excerpt ?: str(strip_tags($post->content))->limit(120) }}
                            </p>

                            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 15px;">
                                <span style="font-size: 12px; color: #71717a;">
                                    {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                                </span>
                                <a href="{{ $post->url }}" style="color: #c4a472; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; text-decoration: none; font-weight: 600;">
                                    Read Article →
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div style="margin-top: 50px;">
                {{ $posts->links() }}
            </div>
        @endif
    </section>

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
