@extends('layouts.app')

@section('title', 'Terms & Conditions – ' . ($setting?->studio_name ?? 'Paneventz'))
@section('description', 'Client terms, payment schedules, cancellation policies, and deliverables agreement for wedding photography and films.')

@section('content')
@php
    $setting = \App\Models\WebsiteSetting::getCached();
    $terms = \App\Models\TermsAndCondition::current();
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
            <a href="/client-portal" style="color: #00f0ff; font-weight: 600;">Download Story 🔒</a>
            <a href="/galleries" style="color: #c4a472; font-weight: bold;">Guest Photos AI 📸</a>
            <a href="/#about">About</a>
            <a href="javascript:void(0)" onclick="openEnquiryModal()" class="enquire">
                Enquire
            </a>
        </div>

        <button type="button" class="mobile-nav-toggle" onclick="toggleMobileNav()" aria-label="Open Mobile Menu">
            ☰
        </button>
    </nav>

    <!-- TERMS HEADER -->
    <section class="intro" style="padding-top: 180px; padding-bottom: 70px;">
        <div class="intro-container">
            <span class="intro-small">CLIENT POLICIES & AGREEMENT</span>
            <h2>Terms & Conditions</h2>
            <p>
                Clear, transparent guidelines ensuring a seamless experience for your wedding celebrations, booking milestones, and cinematic deliverables.
            </p>
        </div>
    </section>

    <!-- TERMS HIGHLIGHT CARDS -->
    <section style="max-width: 1100px; margin: -30px auto 40px; padding: 0 5%;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">
            <div style="background: rgba(18, 24, 36, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); padding: 26px; border-radius: 8px; backdrop-filter: blur(10px);">
                <span style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #c4a472; display: block; margin-bottom: 8px;">Booking & Payment</span>
                <div style="font-family: Georgia, serif; font-size: 24px; color: #fff; font-weight: 500;">
                    {{ $terms->advance_percentage }}% Advance / {{ $terms->balance_percentage }}% Balance
                </div>
                <div style="font-size: 13px; color: #94a3b8; margin-top: 6px;">
                    Balance cleared on {{ $terms->balance_due }}.
                </div>
            </div>

            <div style="background: rgba(18, 24, 36, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); padding: 26px; border-radius: 8px; backdrop-filter: blur(10px);">
                <span style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #c4a472; display: block; margin-bottom: 8px;">Cancellation & Date</span>
                <div style="font-family: Georgia, serif; font-size: 24px; color: #fff; font-weight: 500;">
                    {{ $terms->advance_refundable ? 'Refundable' : 'Non-Refundable' }}
                </div>
                <div style="font-size: 13px; color: #94a3b8; margin-top: 6px;">
                    Advance secures team and dates.
                </div>
            </div>

            <div style="background: rgba(18, 24, 36, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); padding: 26px; border-radius: 8px; backdrop-filter: blur(10px);">
                <span style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #c4a472; display: block; margin-bottom: 8px;">Delivery Timeline</span>
                <div style="font-family: Georgia, serif; font-size: 24px; color: #fff; font-weight: 500;">
                    {{ $terms->estimated_delivery_period }}
                </div>
                <div style="font-size: 13px; color: #94a3b8; margin-top: 6px;">
                    Post-event handcrafted turnaround.
                </div>
            </div>

            <div style="background: rgba(18, 24, 36, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); padding: 26px; border-radius: 8px; backdrop-filter: blur(10px);">
                <span style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #c4a472; display: block; margin-bottom: 8px;">Extended Coverage</span>
                <div style="font-family: Georgia, serif; font-size: 24px; color: #fff; font-weight: 500;">
                    Past {{ $terms->extended_coverage_after }}
                </div>
                <div style="font-size: 13px; color: #94a3b8; margin-top: 6px;">
                    Late-night & overtime rates apply.
                </div>
            </div>
        </div>
    </section>

    <!-- FULL LEGAL CONTENT SECTION -->
    <section style="max-width: 1000px; margin: 0 auto 120px; padding: 0 5%;">
        <div style="background: #0e0f12; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; padding: 50px 45px; box-shadow: 0 20px 50px rgba(0,0,0,0.5);">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 20px; margin-bottom: 30px;">
                <span style="font-size: 11px; letter-spacing: 3px; text-transform: uppercase; color: #c4a472; font-weight: 600;">Studio Standard Agreement</span>
                <span style="font-size: 11px; letter-spacing: 1px; color: #71717a;">Version {{ $terms->version }} · Updated {{ $terms->updated_at->format('M Y') }}</span>
            </div>

            <div style="color: #d4d4d8; font-size: 15px; line-height: 1.85;">
                {!! \App\Services\TermsService::renderHtml($terms) !!}
            </div>

            <div style="margin-top: 45px; padding-top: 25px; border-top: 1px solid rgba(255,255,255,0.08); text-align: center;">
                <p style="font-size: 13px; color: #888; margin-bottom: 20px;">
                    Have questions about these terms or custom requirements for your celebration?
                </p>
                <a href="javascript:void(0)" onclick="openEnquiryModal()" class="footer-button" style="display: inline-block; background: #c4a472; color: #080809; font-weight: 600; padding: 14px 34px; text-decoration: none; border-radius: 6px; font-size: 12px; letter-spacing: 2px; text-transform: uppercase;">
                    Speak With Our Team
                </a>
            </div>
        </div>
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
            <a href="/terms" style="color: #c4a472; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; font-weight: 600;">Terms & Conditions</a>
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
