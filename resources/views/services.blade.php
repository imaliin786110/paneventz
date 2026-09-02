@extends('layouts.app')

@section('title', 'Services & Packages – Paneventz Luxury Wedding Photography & Films')
@section('description', 'Explore our luxury wedding photography, cinematic wedding films, and comprehensive destination collections.')

@section('content')
@php
    $setting = \App\Models\WebsiteSetting::getCached();
    $services = \App\Models\Service::where('is_published', true)->orderBy('sort_order')->get();
@endphp

    <nav>
        <div class="logo">
            <a href="/" style="color: #fff; text-decoration: none;">
                @if($setting?->logo)
                    <img src="{{ asset('storage/' . $setting->logo) }}" alt="Paneventz" style="max-height: 38px;">
                @else
                    {{ $setting?->studio_name ?? 'Paneventz' }}
                @endif
            </a>
        </div>

        <div class="nav-links">
            <a href="/#stories">Stories</a>
            <a href="/#films">Films</a>
            <a href="/services" style="color: #c4a472;">Services</a>
            <a href="/#testimonials">Reviews</a>
            <a href="/#faqs">FAQs</a>
            <a href="javascript:void(0)" onclick="openEnquiryModal()" class="enquire">
                Enquire
            </a>
        </div>

        <button type="button" class="mobile-nav-toggle" onclick="toggleMobileNav()" aria-label="Open Mobile Menu">
            ☰
        </button>
    </nav>

    <!-- SERVICES HERO -->
    <section class="intro" style="padding-top: 180px; padding-bottom: 100px;">
        <div class="intro-small" style="color: #c4a472;">INVESTMENT & PACKAGES</div>
        <h1 style="font-family: Georgia, serif; font-size: clamp(38px, 6vw, 75px); font-weight: normal; margin-top: 15px; line-height: 1.1;">
            Curated Collections for Unforgettable Celebrations
        </h1>
        <p style="max-width: 650px; margin: 30px auto 0; color: #666; line-height: 1.9; font-size: 15px;">
            Every love story is distinct. We provide customized, high-touch photography and cinematic film experiences for weddings across India and destination venues worldwide.
        </p>

        @if($setting?->brochure_pdf)
            <div style="margin-top: 30px;">
                <a href="{{ asset('storage/' . $setting->brochure_pdf) }}" target="_blank" class="film-button">
                    Download Full Pricing & Inclusions Guide (PDF) ↓
                </a>
            </div>
        @endif
    </section>

    <!-- SERVICES CARDS -->
    <section class="services-section" style="padding-top: 50px;">
        <div class="films-content">
            <div class="services-grid">
                @forelse($services as $index => $s)
                    <div class="service-card {{ $index === 1 ? 'featured' : '' }}">
                        @if($index === 1)
                            <div class="service-badge">Most Popular</div>
                        @endif

                        <div>
                            <h3 class="service-name">{{ $s->name }}</h3>
                            @if($s->short_description)
                                <p class="service-desc">{{ $s->short_description }}</p>
                            @endif

                            @if($s->description)
                                <div style="color: #888; font-size: 13px; line-height: 1.8; margin-bottom: 25px; border-left: 2px solid rgba(196,164,114,0.4); padding-left: 15px;">
                                    {{ $s->description }}
                                </div>
                            @endif
                        </div>

                        <div>
                            @if($s->price_from)
                                <div class="service-price">
                                    <small>Starting Investment</small>
                                    ₹{{ number_format($s->price_from) }}
                                </div>
                            @else
                                <div class="service-price">
                                    <small>Investment</small>
                                    Bespoke Quotation
                                </div>
                            @endif

                            <a href="javascript:void(0)" onclick="openEnquiryModal('{{ addslashes($s->name) }}')" class="service-btn">
                                Enquire For This Collection →
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; color: #888; padding: 60px 0;">
                        No packages published yet. Please check back soon!
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- FOOTER / CONTACT -->
    <footer id="contact" style="background: #080809; border-top: 1px solid rgba(255, 255, 255, 0.08); padding: 120px 8% 50px; text-align: center;">
        <span class="intro-small" style="color: #c4a472;">LET'S CONNECT</span>
        <h2 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: clamp(38px, 6vw, 75px); font-weight: 300; margin: 15px 0 15px; color: #fff; letter-spacing: -0.5px;">
            {{ $setting?->footer_heading ?: "Let's create something timeless." }}
        </h2>
        <p style="color: #8e8e93; font-size: 15px; max-width: 650px; margin: 0 auto 25px; line-height: 1.9; font-weight: 300;">
            {{ $setting?->footer_description ?: 'Now reserving select dates for wedding celebrations across India and destinations worldwide.' }}
        </p>

        <!-- PAN-INDIA & MUMBAI LOCATION BADGE -->
        <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(196, 164, 114, 0.08); border: 1px solid rgba(196, 164, 114, 0.3); padding: 8px 24px; border-radius: 30px; margin-bottom: 30px; font-size: 11.5px; letter-spacing: 2px; text-transform: uppercase; color: #c4a472;">
            <span>📍</span> Based in Mumbai · Services Available Pan-India & Worldwide
        </div>

        <!-- LUXURY CONTACT CARDS HUB -->
        <div class="premium-contact-grid">
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

        <!-- CORPORATE & PRIVATE EVENTS NOTICE -->
        <div style="margin-bottom: 45px;">
            <a href="https://www.paneventz.com" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 8px; color: #00f0ff; text-decoration: none; font-size: 12.5px; letter-spacing: 1.5px; text-transform: uppercase; background: rgba(0, 240, 255, 0.06); border: 1px solid rgba(0, 240, 255, 0.3); padding: 10px 22px; border-radius: 30px; transition: all 0.3s;" onmouseover="this.style.background='rgba(0, 240, 255, 0.12)'" onmouseout="this.style.background='rgba(0, 240, 255, 0.06)'">
                <span>✨</span> For Corporate Events, Concerts & Event Management Visit: <strong style="text-decoration: underline;">www.paneventz.com ↗</strong>
            </a>
        </div>

        <!-- ACTION BUTTONS -->
        <div style="display: flex; gap: 18px; justify-content: center; flex-wrap: wrap; margin-bottom: 60px;">
            <a href="javascript:void(0)" onclick="openEnquiryModal()" class="footer-button" style="margin: 0; background: #c4a472; color: #080809; border: none; font-weight: 600;">
                Start A Wedding Conversation →
            </a>
            <a href="https://wa.me/918082024787?text=Hello%20Paneventz!%20I%20would%20like%20to%20inquire%20about%20your%20wedding%20photography%20%26%20films." target="_blank" class="footer-button" style="margin: 0; border: 1px solid rgba(196,164,114,0.5); color: #c4a472;">
                WhatsApp ↗
            </a>
        </div>

        <div style="display: flex; justify-content: center; gap: 28px; flex-wrap: wrap; margin-bottom: 50px; padding-bottom: 40px; border-bottom: 1px solid rgba(255, 255, 255, 0.06);">
            <a href="/#stories" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Stories</a>
            <a href="/#films" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Films</a>
            <a href="/services" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Services</a>
            <a href="/terms" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Terms & Conditions</a>
            <a href="/client-portal" style="color: #00f0ff; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; font-weight: 600;">Download Story 🔒</a>
            <a href="/galleries" style="color: #c4a472; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; font-weight: 600;">Guest Photos AI 📸</a>
            <a href="/#about" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">About</a>
            <a href="/blog" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Journal</a>
            <a href="/#testimonials" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Reviews</a>
            <a href="/#faqs" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">FAQs</a>
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
                    <textarea name="message" class="form-control" rows="3" placeholder="Number of guests, ceremonies planned, your vision..."></textarea>
                </div>

                <button type="submit" id="submitBtn" class="btn-submit">Submit Inquiry</button>
            </form>
        </div>
    </div>



@push('scripts')
<script>
    function openEnquiryModal(serviceName = '') {
        const modal = document.getElementById('enquiryModal');
        if (serviceName) {
            const input = document.getElementById('modalServiceInput');
            if (input) input.value = serviceName;
        }
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeEnquiryModal() {
        const modal = document.getElementById('enquiryModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    function closeModalOnBackdrop(e, modalId) {
        if (e.target.id === modalId) {
            if (modalId === 'enquiryModal') closeEnquiryModal();
        }
    }

    function submitEnquiry(e) {
        e.preventDefault();
        const form = document.getElementById('enquiryForm');
        const submitBtn = document.getElementById('submitBtn');
        const formData = new FormData(form);

        submitBtn.disabled = true;
        submitBtn.innerText = 'Submitting...';

        fetch('/enquire', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: formData,
        })
        .then(res => res.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerText = 'Submit Inquiry';
            if (data.success) {
                form.reset();
                closeEnquiryModal();
                showToast('Thank you! Your inquiry has been sent to our studio.');
            } else {
                alert('Something went wrong. Please try again.');
            }
        })
        .catch(err => {
            submitBtn.disabled = false;
            submitBtn.innerText = 'Submit Inquiry';
            alert('Something went wrong. Please check your network and try again.');
        });
    }

    function showToast(msg) {
        const toast = document.getElementById('toastNotification');
        const toastMsg = document.getElementById('toastMsg');
        toastMsg.innerText = msg;
        toast.style.display = 'flex';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 5000);
    }
</script>
@endpush
@endsection