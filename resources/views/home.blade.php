@extends('layouts.app')

@section('title', ($setting?->studio_name ?? 'Paneventz') . ' – Luxury Wedding Photography & Films')
@section('description', $setting?->meta_description ?? 'Paneventz creates timeless wedding photography and cinematic wedding films for unforgettable celebrations.')

@section('content')
@php
    $setting = \App\Models\WebsiteSetting::getCached();
    $stories = \App\Models\Story::where('is_published', true)->orderBy('sort_order')->get();
    $films = \App\Models\Film::where('is_published', true)->orderBy('sort_order')->get();
    $services = \App\Models\Service::where('is_published', true)->orderBy('sort_order')->get();
    $testimonials = \App\Models\Testimonial::where('is_published', true)->orderBy('sort_order')->get();
    $faqs = \App\Models\Faq::where('is_published', true)->orderBy('sort_order')->get();
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
            <a href="#stories">Stories</a>
            <a href="#films">Films</a>
            <a href="#services">Services</a>
            <a href="/client-portal" style="color: #00f0ff; font-weight: 600; text-shadow: 0 0 10px rgba(0,240,255,0.35);">Download Story 🔒</a>
            <a href="/galleries" style="color: #c4a472; font-weight: bold;">Guest Photos AI 📸</a>
            <a href="#about">About</a>
            <a href="javascript:void(0)" onclick="openEnquiryModal()" class="enquire">
                Enquire
            </a>
        </div>

        <button type="button" class="mobile-nav-toggle" onclick="toggleMobileNav()" aria-label="Open Mobile Menu">
            ☰
        </button>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero" style="{{ $setting?->hero_background_image ? "background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.7)), url('" . asset('storage/' . $setting->hero_background_image) . "'); background-size: cover; background-position: center;" : '' }}">
        @if($setting?->hero_background_video)
            <video autoplay loop muted playsinline style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: -1; opacity: 0.5;">
                <source src="{{ asset('storage/' . $setting->hero_background_video) }}" type="video/mp4">
            </video>
        @endif

        <div class="hero-content">
            <div class="eyebrow">
                {{ $setting?->hero_eyebrow ?? 'Wedding Photography & Films' }}
            </div>

            <h1>
                {{ $setting?->hero_heading ?? ($setting?->studio_name ?? 'Paneventz') }}
            </h1>

            <p class="hero-description">
                {{ $setting?->hero_description ?? 'We create timeless photographs and cinematic films for couples who want their wedding story to live far beyond the day itself.' }}
            </p>
        </div>

        <!-- LUXURY MINIMALIST SCROLL INDICATOR -->
        <div class="hero-scroll-indicator">
            <div class="scroll-mouse">
                <div class="scroll-wheel"></div>
            </div>
            <span>DISCOVER THE LEGACY</span>
        </div>
    </section>

    <!-- STUDIO BY THE NUMBERS (ANIMATED ROLLING COUNTER) -->
    <div class="stats-counter-section" id="statsCounterSection">
        <div class="stats-counter-grid">
            <div class="stat-counter-box">
                <div class="stat-item-number" 
                     data-target="{{ $setting?->stat_1_number ?? 250 }}" 
                     data-suffix="{{ $setting?->stat_1_suffix ?? '+' }}">
                    0{{ $setting?->stat_1_suffix ?? '+' }}
                </div>
                <div class="stat-item-label">
                    {{ $setting?->stat_1_label ?? 'Weddings & Marriages Documented' }}
                </div>
            </div>

            <div class="stat-counter-box">
                <div class="stat-item-number" 
                     data-target="{{ $setting?->stat_2_number ?? 10 }}" 
                     data-suffix="{{ $setting?->stat_2_suffix ?? '+' }}">
                    0{{ $setting?->stat_2_suffix ?? '+' }}
                </div>
                <div class="stat-item-label">
                    {{ $setting?->stat_2_label ?? 'Years of Artistic Experience' }}
                </div>
            </div>

            <div class="stat-counter-box">
                <div class="stat-item-number" 
                     data-target="{{ $setting?->stat_3_number ?? 35 }}" 
                     data-suffix="{{ $setting?->stat_3_suffix ?? '+' }}">
                    0{{ $setting?->stat_3_suffix ?? '+' }}
                </div>
                <div class="stat-item-label">
                    {{ $setting?->stat_3_label ?? 'Royal Palaces & Destinations' }}
                </div>
            </div>

            <div class="stat-counter-box">
                <div class="stat-item-number" 
                     data-target="{{ $setting?->stat_4_number ?? 100 }}" 
                     data-suffix="{{ $setting?->stat_4_suffix ?? '%' }}">
                    0{{ $setting?->stat_4_suffix ?? '%' }}
                </div>
                <div class="stat-item-label">
                    {{ $setting?->stat_4_label ?? 'Client Trust & Handcrafted Heirlooms' }}
                </div>
            </div>
        </div>
    </div>

    <!-- THE PAN EVENTZ APPROACH / ABOUT SPREAD -->
    <section class="intro" id="about">
        <div class="intro-container">
            <span class="intro-small" style="letter-spacing: 4px; color: #c4a472;">
                THE PAN EVENTZ APPROACH
            </span>

            <h2 style="font-size: clamp(34px, 5vw, 68px); line-height: 1.15; max-width: 960px; margin: 0 auto 16px;">
                Your wedding deserves more than photographs.
            </h2>

            <div style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: clamp(20px, 2.8vw, 28px); color: #c4a472; font-style: italic; font-weight: 400; margin-bottom: 24px;">
                It deserves to be remembered exactly as it felt.
            </div>

            <p style="max-width: 780px; font-size: 16px; color: #cbd5e1; line-height: 1.9; margin: 0 auto 18px;">
                The laughter. The tears. The quiet glances. The people you love.
            </p>

            <p style="max-width: 840px; font-size: 15px; color: #94a3b8; line-height: 1.9; margin: 0 auto;">
                At <strong style="color: #fff; font-weight: 600;">Pan Eventz</strong>, we turn these fleeting moments into photographs and films you'll want to relive for years to come.
            </p>

            <div class="intro-pillars" style="margin-top: 60px; display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px;">
                <!-- 01 NATURAL & CANDID -->
                <div class="intro-pillar-card" style="display: flex; flex-direction: column; justify-content: space-between; background: rgba(18, 24, 36, 0.7); border: 1px solid rgba(255, 255, 255, 0.09); border-radius: 8px; padding: 36px 30px; text-align: left;">
                    <div>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; border-bottom: 1px solid rgba(196, 164, 114, 0.18); padding-bottom: 10px;">
                            <span class="pillar-num" style="font-size: 12.5px; letter-spacing: 2px; text-transform: uppercase; color: #c4a472; font-weight: 600; font-family: 'Manrope', sans-serif;">
                                01 — NATURAL & CANDID
                            </span>
                        </div>
                        <h3 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 25px; color: #fff; margin-bottom: 14px; font-weight: 400; line-height: 1.25;">
                            Let the moments happen naturally.
                        </h3>
                        <p style="font-size: 14px; color: #cbd5e1; line-height: 1.8; margin-bottom: 14px;">
                            We believe the best photographs aren't always posed.
                        </p>
                        <p style="font-size: 14px; color: #94a3b8; line-height: 1.8; margin-bottom: 20px;">
                            We stay <strong style="color: #e2e8f0; font-weight: 600;">in the background</strong>, capturing genuine emotions as they unfold — without interrupting the moment.
                        </p>
                    </div>
                    <div style="margin-top: 15px; padding-top: 14px; border-top: 1px solid rgba(255,255,255,0.06); font-size: 13.5px; font-weight: 600; color: #c4a472; letter-spacing: 0.5px;">
                        Less posing. More living.
                    </div>
                </div>

                <!-- 02 TIMELESS & CINEMATIC -->
                <div class="intro-pillar-card" style="display: flex; flex-direction: column; justify-content: space-between; background: rgba(18, 24, 36, 0.7); border: 1px solid rgba(255, 255, 255, 0.09); border-radius: 8px; padding: 36px 30px; text-align: left;">
                    <div>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; border-bottom: 1px solid rgba(196, 164, 114, 0.18); padding-bottom: 10px;">
                            <span class="pillar-num" style="font-size: 12.5px; letter-spacing: 2px; text-transform: uppercase; color: #c4a472; font-weight: 600; font-family: 'Manrope', sans-serif;">
                                02 — TIMELESS & CINEMATIC
                            </span>
                        </div>
                        <h3 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 25px; color: #fff; margin-bottom: 14px; font-weight: 400; line-height: 1.25;">
                            Every frame, made to be felt.
                        </h3>
                        <p style="font-size: 14px; color: #94a3b8; line-height: 1.8; margin-bottom: 20px;">
                            Natural skin tones, elegant colours and thoughtful storytelling come together to create photographs and films that feel as beautiful years from now as they do today.
                        </p>
                    </div>
                    <div style="margin-top: 15px; padding-top: 14px; border-top: 1px solid rgba(255,255,255,0.06); font-size: 13.5px; font-weight: 600; color: #c4a472; letter-spacing: 0.5px;">
                        Beautiful today. Timeless tomorrow.
                    </div>
                </div>

                <!-- 03 MADE TO LAST -->
                <div class="intro-pillar-card" style="display: flex; flex-direction: column; justify-content: space-between; background: rgba(18, 24, 36, 0.7); border: 1px solid rgba(255, 255, 255, 0.09); border-radius: 8px; padding: 36px 30px; text-align: left;">
                    <div>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; border-bottom: 1px solid rgba(196, 164, 114, 0.18); padding-bottom: 10px;">
                            <span class="pillar-num" style="font-size: 12.5px; letter-spacing: 2px; text-transform: uppercase; color: #c4a472; font-weight: 600; font-family: 'Manrope', sans-serif;">
                                03 — MADE TO LAST
                            </span>
                        </div>
                        <h3 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 25px; color: #fff; margin-bottom: 14px; font-weight: 400; line-height: 1.25;">
                            Memories worth passing on.
                        </h3>
                        <p style="font-size: 14px; color: #94a3b8; line-height: 1.8; margin-bottom: 20px;">
                            From high-resolution photographs to cinematic 4K films, we preserve the moments you'll want to relive — and share with the generations that follow.
                        </p>
                    </div>
                    <div style="margin-top: 15px; padding-top: 14px; border-top: 1px solid rgba(255,255,255,0.06); font-size: 13.5px; font-weight: 600; color: #c4a472; letter-spacing: 0.5px;">
                        Today's moments. Tomorrow's memories.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MOMENTS THAT STAY / STORIES SPREAD -->
    <section class="stories" id="stories">
        <div class="stories-heading">
            <div class="intro-small" style="letter-spacing: 4px; color: #c4a472;">
                MOMENTS THAT STAY
            </div>

            <h2 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: clamp(38px, 6vw, 76px); line-height: 1.1; margin-bottom: 24px; letter-spacing: -1px;">
                One day. A thousand emotions.<br>
                A lifetime of memories.
            </h2>

            <div style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: clamp(20px, 2.6vw, 28px); color: #c4a472; font-style: italic; font-weight: 400; line-height: 1.4; margin-bottom: 20px;">
                The smiles. The tears. The stolen glances.<br>
                Every feeling, beautifully preserved.
            </div>

            <p style="color: #cbd5e1; font-size: 16px; line-height: 1.8; margin-top: 10px;">
                Stories we've had the honour of telling.
            </p>
        </div>

        @foreach($stories as $index => $story)
            @php
                $isReverse = ($index % 2 === 1);
                $isLarge = ($index % 2 === 0);
                $class = $isReverse ? 'story story-reverse' : ($isLarge ? 'story story-large' : 'story');
                $galleryData = $story->gallery ? json_encode(array_map(fn($img) => asset('storage/' . $img), $story->gallery)) : '[]';
                $coverUrl = $story->cover_image ? asset('storage/' . $story->cover_image) : asset('images/1.png');
                $ext = strtolower(pathinfo($story->cover_image ?? '', PATHINFO_EXTENSION));
                $isVideo = in_array($ext, ['mp4', 'webm', 'mov', 'ogg']);
                $directVideoUrl = $story->cover_image ? asset('storage/' . $story->cover_image) : $coverUrl;
                $altText = \App\Services\Seo\AltTextGenerator::for($story);
            @endphp

            <div class="{{ $class }}" style="content-visibility: auto; contain-intrinsic-size: 550px;">
                @if($isVideo)
                    <div class="story-image" style="cursor: pointer; position: relative; background: #0c1019;" onclick="openVideoModal('{{ $directVideoUrl }}', '{{ addslashes($story->couple_name) }}')">
                        <video 
                            class="fast-load-video lazy-story-video"
                            loop 
                            muted 
                            playsinline 
                            webkit-playsinline 
                            preload="metadata"
                            data-src="{{ $directVideoUrl }}"
                            style="width: 100%; height: 100%; object-fit: cover; background: #0c1019;"
                        >
                            <source src="" data-src="{{ $directVideoUrl }}" type="video/{{ $ext === 'mov' ? 'mp4' : $ext }}">
                            Your browser does not support HTML5 video.
                        </video>
                        <div class="story-video-badge">
                            <span>▶ Play Cinema</span>
                        </div>
                    </div>
                @else
                    <div class="story-image" style="cursor: pointer; background: #0c1019;" onclick="openStoryGallery('{{ addslashes($story->couple_name) }}', '{{ $coverUrl }}', {{ $galleryData }})">
                        <img 
                            src="{{ $coverUrl }}" 
                            alt="{{ $altText }}" 
                            @if($index === 0) fetchpriority="high" loading="eager" @else loading="lazy" decoding="async" @endif
                            style="width: 100%; height: 100%; object-fit: cover;"
                        >
                    </div>
                @endif

                <div class="story-info">
                    <span class="story-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3>{{ $story->couple_name }}</h3>
                    <span class="story-location">{{ $story->location }}</span>
                    @if($isVideo)
                        <a href="javascript:void(0)" onclick="openVideoModal('{{ $directVideoUrl }}', '{{ addslashes($story->couple_name) }}')" class="story-link">
                            Watch Cinema Story ▶
                        </a>
                    @else
                        <a href="javascript:void(0)" onclick="openStoryGallery('{{ addslashes($story->couple_name) }}', '{{ $coverUrl }}', {{ $galleryData }})" class="story-link">
                            View Photo Story →
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </section>

    <!-- FILMS SECTION -->
    <section class="films" id="films">
        <div class="films-content">
            <span class="section-label">Stories In Motion</span>
            <h2>Cinematic Wedding Films</h2>
            <p>
                Movement, music, and spoken vows woven into timeless motion pictures.
            </p>

            @if($films->count() > 0)
                <div class="films-grid">
                    @foreach($films as $f)
                        @php
                            $thumb = $f->thumbnail ? asset('storage/' . $f->thumbnail) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=800&q=80';
                        @endphp
                        <div class="film-card" onclick="openVideoModal('{{ $f->video_url }}', '{{ addslashes($f->title) }}')">
                            <div class="film-thumbnail-wrapper" style="background: #0c1019;">
                                <img src="{{ $thumb }}" alt="{{ $f->title }}" loading="lazy" decoding="async" style="width: 100%; height: 100%; object-fit: cover;">
                                <div class="film-play-btn">▶</div>
                            </div>
                            <div class="film-info">
                                <div class="film-meta">
                                    <span>{{ $f->location }}</span>
                                    <span>{{ $f->wedding_date ? \Carbon\Carbon::parse($f->wedding_date)->format('Y') : 'CINEMA' }}</span>
                                </div>
                                <h3 class="film-title">{{ $f->title }}</h3>
                                @if($f->couple_name)
                                    <div class="film-couple">{{ $f->couple_name }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <a href="javascript:void(0)" onclick="openEnquiryModal()" class="film-button">Enquire For Wedding Films →</a>
            @endif
        </div>
    </section>

    <!-- EMOTIONAL TRUST & PEACE OF MIND SECTION -->
    <section class="trust-letter-section">
        <div class="trust-letter-container">
            <span class="trust-letter-eyebrow">
                ✦ TO EVERY COUPLE WONDERING WHO TO TRUST
            </span>
            
            <h2 class="trust-letter-heading">
                When the celebration is over and everyone has gone home,<br>
                <span>the memories remain.</span>
            </h2>

            <div class="trust-letter-grid">
                <!-- WE UNDERSTAND THE WORRY -->
                <div class="trust-letter-col">
                    <div class="trust-col-badge">WE UNDERSTAND THE WORRY.</div>
                    <h3>You spend months planning a day you've dreamed about for years.</h3>
                    <p>
                        Every detail matters. Every tradition matters. Every person who stands beside you matters.
                    </p>
                    <p style="color: #e2e8f0; margin-bottom: 12px;">
                        And somewhere in all that excitement is a quiet thought:
                    </p>
                    <div style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 22px; color: #fff; font-weight: 500; font-style: italic; margin: 12px 0 18px; padding-left: 16px; border-left: 2px solid #c4a472; line-height: 1.35;">
                        Will the moments that matter most be captured?
                    </div>
                    <ul style="list-style: none; padding: 0; margin: 0 0 20px; color: #cbd5e1; font-size: 14.5px; line-height: 1.95;">
                        <li style="margin-bottom: 4px;"><span style="color: #c4a472; margin-right: 8px;">✦</span> Your mother's tears.</li>
                        <li style="margin-bottom: 4px;"><span style="color: #c4a472; margin-right: 8px;">✦</span> Your father's proud smile.</li>
                        <li style="margin-bottom: 4px;"><span style="color: #c4a472; margin-right: 8px;">✦</span> Your family's blessings.</li>
                        <li style="margin-bottom: 4px;"><span style="color: #c4a472; margin-right: 8px;">✦</span> The laughter shared between loved ones.</li>
                        <li style="margin-bottom: 4px;"><span style="color: #c4a472; margin-right: 8px;">✦</span> That quiet glance between the two of you when no one was watching.</li>
                    </ul>
                    <p style="color: #94a3b8; font-style: italic; font-size: 14px; margin-bottom: 0; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.06);">
                        The moments you never planned are often the ones you'll treasure forever.
                    </p>
                </div>

                <!-- THE PAN EVENTZ PROMISE -->
                <div class="trust-letter-col trust-col-promise">
                    <div class="trust-col-badge trust-col-badge-gold">THE PAN EVENTZ PROMISE</div>
                    <h3>You live the moment. We preserve it.</h3>
                    <p>
                        Your wedding happens once. There are no retakes and no second chances.
                    </p>
                    <p>
                        That's why we never ask you to perform for the camera. We work with your family, your traditions and your way of celebrating — staying in the background when the moment calls for it and stepping in when a memory deserves to be beautifully framed.
                    </p>
                    <div style="background: rgba(196, 164, 114, 0.08); border: 1px solid rgba(196, 164, 114, 0.2); border-radius: 6px; padding: 14px 18px; margin: 18px 0;">
                        <div style="color: #f0dcba; font-weight: 600; font-size: 14.5px; letter-spacing: 0.3px; margin-bottom: 4px;">
                            The emotions. The people. The little moments. The memories.
                        </div>
                        <div style="font-size: 13.5px; color: #cbd5e1;">
                            Not just how your wedding looked — <strong style="color: #fff; font-weight: 600;">how it felt.</strong>
                        </div>
                    </div>
                    <div class="trust-signature-bar">
                        <div style="font-size: 13px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 6px;">
                            Years from now, you won't just look at your wedding photographs.
                        </div>
                        <div class="trust-signature-quote" style="font-size: 24px; color: #f0dcba; font-weight: 400;">
                            You'll feel it all over again.
                        </div>
                        <div class="trust-signature-name" style="color: #c4a472; font-weight: 600; margin-top: 6px;">
                            — Pan Eventz
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- THE PANEVENTZ EXPERIENCE (PROCESS) -->
    <section class="experience-section">
        <div class="films-content">
            <span class="section-label">HOW WE CRAFT YOUR LEGACY</span>
            <h2>The Paneventz Experience</h2>
            <p class="subheading">
                From our first conversation to delivering your final handcrafted heirlooms, our approach is discreet, intentional, and deeply personal.
            </p>

            <div class="experience-grid">
                <div class="experience-card">
                    <div class="experience-num">01</div>
                    <h3 class="experience-title">The Discovery</h3>
                    <p class="experience-desc">
                        A collaborative consultation to understand your celebration timeline, family nuances, venue aesthetics, and lighting.
                    </p>
                </div>

                <div class="experience-card">
                    <div class="experience-num">02</div>
                    <h3 class="experience-title">Discreet Capture</h3>
                    <p class="experience-desc">
                        Multi-camera cinema and photojournalistic coverage. We blend into the celebration, documenting raw, unscripted emotions as they naturally unfold.
                    </p>
                </div>

                <div class="experience-card">
                    <div class="experience-num">03</div>
                    <h3 class="experience-title">Artisan Grading</h3>
                    <p class="experience-desc">
                        Every frame is curated with bespoke color grading, licensed emotional film scoring, and high-fashion portrait retouching.
                    </p>
                </div>

                <div class="experience-card">
                    <div class="experience-num">04</div>
                    <h3 class="experience-title">The Heirloom</h3>
                    <p class="experience-desc">
                        Delivered through an interactive 4K digital gallery and a custom flush-mount fine art album meant to live on for generations.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ARTISAN COLOR GRADING BEFORE & AFTER SHOWCASE -->
    <section class="color-grade-section">
        <div class="films-content">
            <span class="section-label">THE ART OF POST-PRODUCTION</span>
            <h2>{{ $setting?->color_grade_heading ?: 'Raw Capture vs. Signature Grade' }}</h2>
            <p class="subheading">
                {{ $setting?->color_grade_description ?: 'Every celebration is masterfully developed with custom color matrices, delicate highlight roll-offs, and authentic 35mm film emulation that stands the test of time.' }}
            </p>

            <div class="color-slider-wrapper">
                <div class="color-slider-container" id="colorGradeSlider">
                    @php
                        $afterImg = $setting?->color_grade_after_image 
                            ? asset('storage/' . $setting->color_grade_after_image) 
                            : asset('images/signature-color-grade.jpg');
                        $beforeImg = $setting?->color_grade_before_image 
                            ? asset('storage/' . $setting->color_grade_before_image) 
                            : null;
                    @endphp

                    <!-- AFTER IMAGE (Underneath: Signature Grade) -->
                    <img src="{{ $afterImg }}" alt="Paneventz Signature Film Grade" class="slider-img slider-img-after">
                    <span class="slider-badge slider-badge-right">✦ Paneventz Signature Grade</span>

                    <!-- BEFORE IMAGE (Top layer: RAW) -->
                    <div class="slider-before-wrap" id="sliderBeforeWrap">
                        @if($beforeImg)
                            <img src="{{ $beforeImg }}" alt="Camera RAW Flat" class="slider-img slider-img-before">
                        @else
                            <img src="{{ $afterImg }}" alt="Camera RAW Flat" class="slider-img slider-img-before" style="filter: saturate(0.35) contrast(0.82) brightness(1.1) sepia(0.08);">
                        @endif
                        <span class="slider-badge slider-badge-left">Camera RAW (Flat Sensor)</span>
                    </div>

                    <!-- DRAGGABLE DIVIDER HANDLE -->
                    <div class="slider-handle" id="sliderHandle">
                        <div class="slider-handle-button">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                <path d="M8.5 7l-5 5 5 5V7zm7 0v10l5-5-5-5z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="slider-drag-hint">
                    ◂ Drag slider horizontally to reveal color grading ▸
                </div>
            </div>

            <!-- THREE ARTISAN PILLARS -->
            <div class="grading-pillars">
                <div class="pillar-item">
                    <div class="pillar-icon">✦</div>
                    <div class="pillar-title">Skin Tone Radiance</div>
                    <p class="pillar-desc">Natural warmth with flattering luminance across all complexions and challenging Indian wedding lighting, from candlelit sangeets to midnight mandaps.</p>
                </div>
                <div class="pillar-item">
                    <div class="pillar-icon">✦</div>
                    <div class="pillar-title">Highlight Roll-Off</div>
                    <p class="pillar-desc">Preserves intricate gold zardozi embroidery and bright wedding sparklers without blowing out highlights or crushing royal velvet textures.</p>
                </div>
                <div class="pillar-item">
                    <div class="pillar-icon">✦</div>
                    <div class="pillar-title">Timeless Film Emulation</div>
                    <p class="pillar-desc">Organic 35mm film curve and micro-contrast that keeps your love story looking classic, artistic, and moving 50 years from now.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICES & PACKAGES -->
    <section class="services-section" id="services">
        <div class="films-content">
            <span class="section-label">INVESTMENT & SERVICES</span>
            <h2>Curated Collections</h2>
            <p class="subheading">
                Tailored photography and cinematic coverage designed for couples who value art, emotion, and legacy.
            </p>

            <div class="services-grid">
                @foreach($services as $index => $s)
                    <div class="service-card {{ $index === 1 ? 'featured' : '' }}">
                        @if($index === 1)
                            <div class="service-badge">Signature</div>
                        @endif

                        <div>
                            <h3 class="service-name">{{ $s->name }}</h3>
                            @if($s->short_description)
                                <p class="service-desc">{{ $s->short_description }}</p>
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
                                Enquire For This Package →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 50px;">
                <a href="/services" class="film-button">Explore All Deliverables & Details →</a>
            </div>
        </div>
    </section>




    <!-- TESTIMONIALS SECTION -->
    @if($testimonials->count() > 0)
    <section class="testimonials-section" id="testimonials">
        <div class="films-content">
            <span class="section-label">LOVE STORIES</span>
            <h2>Words from our Couples.</h2>
            <p class="subheading">
                The trust of our couples means everything to us. Here is what they have to say about their experience.
            </p>

            <div class="testimonials-grid">
                @foreach($testimonials as $t)
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            {!! str_repeat('★', $t->rating) !!}
                        </div>
                        <p class="testimonial-quote">
                            "{{ $t->review }}"
                        </p>
                        <div class="testimonial-name">{{ $t->couple_name }}</div>
                        <div class="testimonial-loc">{{ $t->location }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- FAQS ACCORDION -->
    @if($faqs->count() > 0)
    <section class="faqs-section" id="faqs">
        <div class="films-content">
            <div style="text-align: center; margin-bottom: 50px;">
                <span class="section-label">COMMON INQUIRIES</span>
                <h2>Frequently Asked Questions</h2>
                <p class="subheading">
                    Everything you need to know about our reservation process, travel, deliverables, and shooting style.
                </p>
            </div>

            <div class="faqs-container">
                @foreach($faqs as $f)
                    <div class="faq-item" onclick="toggleFaq(this)">
                        <div class="faq-question">
                            <span>{{ $f->question }}</span>
                            <span class="faq-icon">+</span>
                        </div>
                        <div class="faq-answer">
                            {{ $f->answer }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- INSTAGRAM SHOWCASE -->
    <section class="instagram-showcase">
        <div class="instagram-header">
            <span class="section-label">FOLLOW OUR JOURNEY</span>
            <h2 style="font-family: Georgia, serif; font-size: clamp(32px, 4vw, 55px); font-weight: normal; margin-top: 10px; color: #fff;">
                Moments in Real Time
            </h2>
            <div style="margin-top: 20px;">
                <a href="{{ $setting?->instagram_url ?? 'https://instagram.com/paneventz' }}" target="_blank" rel="noopener noreferrer" class="film-button" style="padding: 12px 28px;">
                    Follow on Instagram @paneventz ↗
                </a>
            </div>
        </div>

        <div class="instagram-grid">
            <a href="{{ $setting?->instagram_url ?? 'https://instagram.com/paneventz' }}" target="_blank" rel="noopener noreferrer" class="insta-card">
                <img src="https://images.unsplash.com/photo-1583939003579-730e3918a45a?w=600&q=80" alt="Paneventz Instagram Wedding Photo" loading="lazy" decoding="async">
                <div class="insta-overlay"><span>♥</span></div>
            </a>
            <a href="{{ $setting?->instagram_url ?? 'https://instagram.com/paneventz' }}" target="_blank" rel="noopener noreferrer" class="insta-card">
                <img src="https://images.unsplash.com/photo-1519741497674-611481863552?w=600&q=80" alt="Paneventz Instagram Wedding Portrait" loading="lazy" decoding="async">
                <div class="insta-overlay"><span>♥</span></div>
            </a>
            <a href="{{ $setting?->instagram_url ?? 'https://instagram.com/paneventz' }}" target="_blank" rel="noopener noreferrer" class="insta-card">
                <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?w=600&q=80" alt="Paneventz Instagram Wedding Film" loading="lazy" decoding="async">
                <div class="insta-overlay"><span>♥</span></div>
            </a>
            <a href="{{ $setting?->instagram_url ?? 'https://instagram.com/paneventz' }}" target="_blank" rel="noopener noreferrer" class="insta-card">
                <img src="https://images.unsplash.com/photo-1606800052052-a08af7148866?w=600&q=80" alt="Paneventz Instagram Wedding Couple" loading="lazy" decoding="async">
                <div class="insta-overlay"><span>♥</span></div>
            </a>
            <a href="{{ $setting?->instagram_url ?? 'https://instagram.com/paneventz' }}" target="_blank" rel="noopener noreferrer" class="insta-card">
                <img src="https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?w=600&q=80" alt="Paneventz Instagram Celebration" loading="lazy" decoding="async">
                <div class="insta-overlay"><span>♥</span></div>
            </a>
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
            <a href="#stories" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Stories</a>
            <a href="#films" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Films</a>
            <a href="/services" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Services</a>
            <a href="/terms" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Terms & Conditions</a>
            <a href="/client-portal" style="color: #00f0ff; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; font-weight: 600;">Download Story 🔒</a>
            <a href="/galleries" style="color: #c4a472; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; font-weight: 600;">Guest Photos AI 📸</a>
            <a href="#about" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">About</a>
            <a href="/blog" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Journal</a>
            <a href="#testimonials" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Reviews</a>
            <a href="#faqs" style="color: #888; text-decoration: none; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">FAQs</a>
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

    <!-- VIDEO MODAL -->
    <div id="videoModal" class="modal-overlay" onclick="closeModalOnBackdrop(event, 'videoModal')">
        <div class="modal-card" style="max-width: 900px; padding: 20px; background: #000;">
            <button type="button" class="modal-close" onclick="closeVideoModal()">&times;</button>
            <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;" id="videoContainer">
                <!-- Video iframe or tag will be injected here -->
            </div>
            <div id="videoTitle" style="color: #fff; font-family: Georgia, serif; font-size: 18px; margin-top: 15px; text-align: center;"></div>
        </div>
    </div>

    <!-- STORY MULTI-PHOTO GALLERY LIGHTBOX -->
    <div id="galleryModal" class="modal-overlay" onclick="closeModalOnBackdrop(event, 'galleryModal')">
        <div class="gallery-modal-content">
            <button type="button" class="modal-close" onclick="closeGalleryModal()" style="top: -35px; right: 0; font-size: 32px;">&times;</button>
            <div class="gallery-main-view">
                <button type="button" class="gallery-arrow prev" onclick="prevGalleryImg()">&larr;</button>
                <img src="" id="galleryActiveImg" class="gallery-main-img" alt="Wedding Gallery Photo">
                <button type="button" class="gallery-arrow next" onclick="nextGalleryImg()">&rarr;</button>
            </div>
            <div id="galleryTitle" style="color: #c4a472; font-family: Georgia, serif; font-size: 20px; margin-top: 15px;"></div>
            <div class="gallery-counter" id="galleryCounter">1 / 1</div>
        </div>
    </div>



@push('scripts')
<script>
    // FAQ Accordion
    function toggleFaq(element) {
        element.classList.toggle('active');
    }

    // Modal Control
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
        document.getElementById('enquiryModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    function closeModalOnBackdrop(e, modalId) {
        if (e.target.id === modalId) {
            if (modalId === 'enquiryModal') closeEnquiryModal();
            if (modalId === 'videoModal') closeVideoModal();
            if (modalId === 'galleryModal') closeGalleryModal();
        }
    }

    // Video Modal
    function openVideoModal(url, title) {
        const modal = document.getElementById('videoModal');
        const container = document.getElementById('videoContainer');
        const titleEl = document.getElementById('videoTitle');
        titleEl.innerText = title;

        let embedHtml = '';
        if (url.includes('youtube.com') || url.includes('youtu.be')) {
            let videoId = '';
            if (url.includes('v=')) {
                videoId = url.split('v=')[1].split('&')[0];
            } else if (url.includes('youtu.be/')) {
                videoId = url.split('youtu.be/')[1].split('?')[0];
            }
            embedHtml = `<iframe src="https://www.youtube.com/embed/${videoId}?autoplay=1" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border:0;" allow="autoplay; fullscreen" allowfullscreen></iframe>`;
        } else if (url.includes('vimeo.com')) {
            let vimeoId = url.split('vimeo.com/')[1].split('?')[0];
            embedHtml = `<iframe src="https://player.vimeo.com/video/${vimeoId}?autoplay=1" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border:0;" allow="autoplay; fullscreen" allowfullscreen></iframe>`;
        } else {
            embedHtml = `<video src="${url}" controls autoplay style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;"></video>`;
        }

        container.innerHTML = embedHtml;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeVideoModal() {
        const modal = document.getElementById('videoModal');
        document.getElementById('videoContainer').innerHTML = '';
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Multi-Photo Gallery Lightbox
    let currentGallery = [];
    let currentGalleryIndex = 0;

    function openStoryGallery(coupleName, coverImg, galleryImages) {
        currentGallery = [];
        if (coverImg) currentGallery.push(coverImg);
        if (Array.isArray(galleryImages)) {
            galleryImages.forEach(img => {
                if (!currentGallery.includes(img)) currentGallery.push(img);
            });
        }

        if (currentGallery.length === 0) return;

        currentGalleryIndex = 0;
        document.getElementById('galleryTitle').innerText = coupleName + ' — Wedding Gallery';
        updateGalleryView();

        const modal = document.getElementById('galleryModal');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function updateGalleryView() {
        document.getElementById('galleryActiveImg').src = currentGallery[currentGalleryIndex];
        document.getElementById('galleryCounter').innerText = (currentGalleryIndex + 1) + ' / ' + currentGallery.length;
    }

    function nextGalleryImg() {
        if (currentGallery.length <= 1) return;
        currentGalleryIndex = (currentGalleryIndex + 1) % currentGallery.length;
        updateGalleryView();
    }

    function prevGalleryImg() {
        if (currentGallery.length <= 1) return;
        currentGalleryIndex = (currentGalleryIndex - 1 + currentGallery.length) % currentGallery.length;
        updateGalleryView();
    }

    function closeGalleryModal() {
        document.getElementById('galleryModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    // Keyboard support for Gallery
    document.addEventListener('keydown', (e) => {
        const galleryModal = document.getElementById('galleryModal');
        if (galleryModal && galleryModal.classList.contains('active')) {
            if (e.key === 'ArrowRight') nextGalleryImg();
            if (e.key === 'ArrowLeft') prevGalleryImg();
            if (e.key === 'Escape') closeGalleryModal();
        }
    });

    // Enquiry AJAX Submission
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

    // Color Grading Before & After Interactive Slider
    function initColorSlider() {
        const slider = document.getElementById('colorGradeSlider');
        const beforeWrap = document.getElementById('sliderBeforeWrap');
        const handle = document.getElementById('sliderHandle');
        if (!slider || !beforeWrap || !handle) return;

        const beforeImg = beforeWrap.querySelector('.slider-img-before');
        let isDragging = false;

        function updateSlider(clientX) {
            const rect = slider.getBoundingClientRect();
            let offsetX = clientX - rect.left;
            let percent = (offsetX / rect.width) * 100;
            percent = Math.max(0, Math.min(100, percent));

            beforeWrap.style.width = percent + '%';
            handle.style.left = percent + '%';
            if (beforeImg) {
                beforeImg.style.width = rect.width + 'px';
            }
        }

        slider.addEventListener('mousedown', (e) => {
            isDragging = true;
            updateSlider(e.clientX);
        });

        window.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            updateSlider(e.clientX);
        });

        window.addEventListener('mouseup', () => {
            isDragging = false;
        });

        slider.addEventListener('touchstart', (e) => {
            isDragging = true;
            if (e.touches && e.touches[0]) {
                updateSlider(e.touches[0].clientX);
            }
        }, { passive: true });

        window.addEventListener('touchmove', (e) => {
            if (!isDragging || !e.touches || !e.touches[0]) return;
            updateSlider(e.touches[0].clientX);
        }, { passive: true });

        window.addEventListener('touchend', () => {
            isDragging = false;
        });

        function syncImgWidth() {
            if (beforeImg && slider) {
                beforeImg.style.width = slider.getBoundingClientRect().width + 'px';
            }
        }

        window.addEventListener('resize', syncImgWidth);
        syncImgWidth();
        setTimeout(syncImgWidth, 500);
    }

    document.addEventListener('DOMContentLoaded', initColorSlider);
    initColorSlider();

    // Animated Rolling Numbers Counter
    function initStatsCounter() {
        const section = document.getElementById('statsCounterSection');
        if (!section) return;

        let hasAnimated = false;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !hasAnimated) {
                    hasAnimated = true;
                    animateAllCounters();
                }
            });
        }, { threshold: 0.2 });

        observer.observe(section);

        function animateAllCounters() {
            const counters = section.querySelectorAll('.stat-item-number');
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target') || '0', 10);
                const suffix = counter.getAttribute('data-suffix') || '';
                const duration = 2200; // 2.2s silky smooth count
                const startTime = performance.now();

                function updateCount(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    
                    // Ease-out cubic curve: 1 - (1 - progress)^3
                    const easeOut = 1 - Math.pow(1 - progress, 3);
                    const current = Math.floor(easeOut * target);

                    counter.innerText = current + suffix;

                    if (progress < 1) {
                        requestAnimationFrame(updateCount);
                    } else {
                        counter.innerText = target + suffix;
                    }
                }

                requestAnimationFrame(updateCount);
            });
        }
    }

    document.addEventListener('DOMContentLoaded', initStatsCounter);
    initStatsCounter();

    // Ultra-Fast Lazy Video Playback Manager (Non-blocking initial page load)
    function initFastVideos() {
        const videos = document.querySelectorAll('.fast-load-video');
        if (!videos.length) return;

        const videoObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const vid = entry.target;
                if (entry.isIntersecting) {
                    if (!vid.dataset.loaded) {
                        const dataSrc = vid.dataset.src;
                        if (dataSrc) {
                            const source = vid.querySelector('source');
                            if (source) {
                                source.src = source.dataset.src || dataSrc;
                            } else {
                                vid.src = dataSrc;
                            }
                            vid.load();
                            vid.dataset.loaded = 'true';
                        }
                    }
                    const playPromise = vid.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(() => {});
                    }
                } else {
                    if (!vid.paused) {
                        vid.pause();
                    }
                }
            });
        }, { threshold: 0.1, rootMargin: '100px 0px' });

        videos.forEach(vid => {
            vid.muted = true;
            vid.setAttribute('muted', '');
            videoObserver.observe(vid);
        });
    }

    document.addEventListener('DOMContentLoaded', initFastVideos);
    initFastVideos();
</script>
@endpush
@endsection