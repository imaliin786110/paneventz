@extends('layouts.app')

@section('title', 'Paneventz – Luxury Wedding Photography & Films')

@section('description', 'Paneventz creates timeless wedding photography and cinematic wedding films.')

@section('content')
 <nav>

        <div class="logo">
            Paneventz
        </div>

        <div class="nav-links">
            <a href="#stories">Stories</a>
            <a href="#films">Films</a>
            <a href="#about">About</a>
            <a href="#testimonials">Reviews</a>
            <a href="javascript:void(0)" onclick="openEnquiryModal()" class="enquire">
                Enquire
            </a>
        </div>

    </nav>


    <section class="hero">

        <div class="hero-content">

            <div class="eyebrow">
                {{ \App\Models\SiteSetting::get('hero_eyebrow', 'Wedding Photography & Films') }}
            </div>

            <h1>
                {{ \App\Models\SiteSetting::get('hero_title', 'Paneventz') }}
            </h1>

            <p class="hero-description">
                {{ \App\Models\SiteSetting::get('hero_description', 'We create timeless photographs and cinematic films for couples who want their wedding story to live far beyond the day itself.') }}
            </p>

            <a href="#stories" class="hero-button">
                Explore Our Stories
            </a>

        </div>

        <div class="scroll">
            SCROLL TO DISCOVER
        </div>

    </section>


    <section class="intro" id="about">

        <div class="intro-small">
            {{ \App\Models\SiteSetting::get('about_small_title', 'The Paneventz Approach') }}
        </div>

        <h2>
            {!! nl2br(e(\App\Models\SiteSetting::get('about_heading', "Your story deserves\nmore than a photograph."))) !!}
        </h2>

        <p>
            {{ \App\Models\SiteSetting::get('about_paragraph', 'We preserve the atmosphere, emotion and little moments that make your celebration uniquely yours. From intimate ceremonies to grand destination weddings, every frame is created with intention.') }}
        </p>

    </section>

<section class="stories" id="stories">

    <div class="stories-heading">
        <div class="intro-small">
            Selected Stories
        </div>

        <h2>
            Celebrations<br>
            beautifully remembered.
        </h2>

        <p>
            A collection of weddings, emotions and fleeting moments
            captured with an editorial eye.
        </p>
    </div>

    @php
        $stories = \App\Models\Story::where('is_published', true)
            ->orderBy('sort_order')
            ->get();
    @endphp

    @foreach($stories as $story)
        <div class="story {{ $loop->iteration % 2 == 0 ? 'story-reverse' : 'story-large' }}">

            <div class="story-image">
    @if($story->cover_image)
        @php
            $extension = strtolower(pathinfo($story->cover_image, PATHINFO_EXTENSION));
        @endphp

        @if(in_array($extension, ['mp4', 'webm', 'mov', 'm4v']))
            <video
                src="{{ asset('storage/' . $story->cover_image) }}"
                autoplay
                muted
                loop
                playsinline
                preload="metadata"
            ></video>
        @else
            <img
                src="{{ asset('storage/' . $story->cover_image) }}"
                alt="{{ $story->couple_name }} wedding photography"
            >
        @endif
    @else
        <img
            src="{{ asset('images/1.png') }}"
            alt="{{ $story->couple_name }} wedding photography"
        >
    @endif
</div>

            <div class="story-info">

                <span class="story-number">
                    {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                </span>

                <h3>{{ $story->couple_name }}</h3>

                @if($story->location)
                    <span class="story-location">
                        {{ $story->location }}
                    </span>
                @endif

                <a
                    href="{{ $story->cover_image ? asset('storage/' . $story->cover_image) : '#' }}"
                    class="story-link"
                    target="_blank"
                >
                    View Story →
                </a>

            </div>

        </div>
    @endforeach

</section>


    <section class="films" id="films">
        <div class="films-content">
            <span class="section-label">WEDDING CINEMATOGRAPHY</span>

            <h2>Stories in Motion.</h2>

            <p>
                Cinematic wedding films crafted to preserve
                the emotion, atmosphere and unforgettable moments
                of your celebration.
            </p>

            @php
                $films = \App\Models\Film::where('is_published', true)
                    ->orderBy('sort_order')
                    ->get();
            @endphp

            @if($films->count() > 0)
                <div class="films-grid">
                    @foreach($films as $film)
                        @php
                            $playUrl = $film->video_file ? asset('storage/' . $film->video_file) : ($film->video_url ?? '');
                            $isVideoFile = !empty($film->video_file);
                        @endphp
                        <div class="film-card" onclick="playFilm('{{ $playUrl }}', '{{ addslashes($film->title) }}', {{ $isVideoFile ? 'true' : 'false' }})">
                            <div class="film-thumbnail-wrapper">
                                @if($film->thumbnail)
                                    <img src="{{ asset('storage/' . $film->thumbnail) }}" alt="{{ $film->title }}">
                                @elseif($film->video_file)
                                    <video src="{{ asset('storage/' . $film->video_file) }}#t=1" preload="metadata" muted></video>
                                @else
                                    <div style="width:100%;height:100%;background:#1b1b1b;display:flex;align-items:center;justify-content:center;color:#666;font-size:11px;letter-spacing:2px;">PANEVENTZ FILMS</div>
                                @endif
                                <div class="film-play-btn">▶</div>
                            </div>
                            <div class="film-info">
                                <div class="film-meta">
                                    <span>{{ $film->location ?? 'WEDDING FILM' }}</span>
                                    <span>{{ $film->duration ?? 'CINEMATIC' }}</span>
                                </div>
                                <h3 class="film-title">{{ $film->title }}</h3>
                                @if($film->couple_name)
                                    <div class="film-couple">{{ $film->couple_name }}</div>
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

    @php
        $testimonials = \App\Models\Testimonial::where('is_featured', true)
            ->orderBy('sort_order')
            ->get();
    @endphp

    @if($testimonials->count() > 0)
    <section class="testimonials-section" id="testimonials">
        <div class="films-content">
            <span class="section-label">LOVE STORIES</span>
            <h2>Words from our Couples.</h2>
            <p>
                The trust of our couples means everything to us. Here is what they have to say about their experience.
            </p>

            <div class="testimonials-grid">
                @foreach($testimonials as $item)
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            {!! str_repeat('★', $item->rating) !!}
                        </div>
                        <p class="testimonial-quote">
                            "{{ $item->quote }}"
                        </p>
                        <div class="testimonial-author">
                            @if($item->photo)
                                <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->couple_name }}" class="testimonial-avatar">
                            @endif
                            <div>
                                <div class="testimonial-name">{{ $item->couple_name }}</div>
                                <div class="testimonial-loc">{{ $item->wedding_location }}{{ $item->wedding_date ? ' · ' . $item->wedding_date : '' }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <footer id="contact">
        <h2>
            {{ \App\Models\SiteSetting::get('footer_heading', "Let's tell your story.") }}
        </h2>

        <p>
            {{ \App\Models\SiteSetting::get('footer_subheading', 'Wedding Photography · Cinematography · Films') }}
        </p>

        <a href="javascript:void(0)" onclick="openEnquiryModal()" class="footer-button">
            Start A Conversation
        </a>
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

            <div id="formAlert" style="display:none; padding:14px; margin-bottom:20px; font-size:13px; border-radius:3px;"></div>

            <form id="enquiryForm" action="{{ route('enquiries.store') }}" method="POST" onsubmit="submitEnquiry(event)">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Your Name(s) *</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="e.g. Rohan & Priya" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone / WhatsApp *</label>
                        <input type="tel" id="phone" name="phone" class="form-control" placeholder="+91 98765 43210" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="hello@yourdomain.com" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="event_date">Wedding / Event Date</label>
                        <input type="date" id="event_date" name="event_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="event_location">Venue / Location</label>
                        <input type="text" id="event_location" name="event_location" class="form-control" placeholder="e.g. Udaipur, Mumbai, Goa">
                    </div>
                </div>

                <div class="form-group">
                    <label>Services Interested In</label>
                    <div class="services-checkboxes">
                        <label class="checkbox-item">
                            <input type="checkbox" name="services[]" value="Wedding Photography" checked>
                            <span>Wedding Photography</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" name="services[]" value="Cinematic Wedding Film" checked>
                            <span>Cinematic Film</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" name="services[]" value="Drone & Aerial Cinematography">
                            <span>Drone Cinematography</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" name="services[]" value="Pre-Wedding / Engagement Shoot">
                            <span>Pre-Wedding Shoot</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="budget">Estimated Budget</label>
                    <select id="budget" name="budget" class="form-control">
                        <option value="Flexible">Flexible / Still Planning</option>
                        <option value="Under ₹1.5 Lakh">Under ₹1.5 Lakh</option>
                        <option value="₹1.5 Lakh - ₹3 Lakh">₹1.5 Lakh - ₹3 Lakh</option>
                        <option value="₹3 Lakh - ₹5 Lakh">₹3 Lakh - ₹5 Lakh</option>
                        <option value="₹5 Lakh+">₹5 Lakh+</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="message">Your Vision & Message</label>
                    <textarea id="message" name="message" class="form-control" rows="3" placeholder="Tell us about your celebration, wedding vibe, or any specific requests..."></textarea>
                </div>

                <button type="submit" id="submitBtn" class="btn-submit">Submit Enquiry</button>
            </form>
        </div>
    </div>

    <!-- VIDEO PLAYER LIGHTBOX MODAL -->
    <div id="videoModal" class="modal-overlay" onclick="closeModalOnBackdrop(event, 'videoModal')">
        <div class="modal-card" style="max-width: 900px; padding: 25px; background: #000;">
            <button type="button" class="modal-close" onclick="closeVideoModal()">&times;</button>
            <h3 id="videoModalTitle" style="font-family: Georgia, serif; font-weight: normal; margin-bottom: 15px; color: #fff; font-size: 20px;"></h3>
            <div id="videoContainer" style="position: relative; width: 100%; aspect-ratio: 16 / 9; background: #000; border-radius: 4px; overflow: hidden;">
                <!-- Video or iframe injected dynamically -->
            </div>
        </div>
    </div>

    @if(session('success'))
        <div id="sessionToast" class="toast-alert">
            <span style="color: #c4a472; font-size: 18px;">✓</span>
            <div>{{ session('success') }}</div>
        </div>
        <script>
            setTimeout(function() {
                var toast = document.getElementById('sessionToast');
                if (toast) toast.remove();
            }, 6000);
        </script>
    @endif

@endsection

@push('scripts')
<script>
    function openEnquiryModal() {
        var modal = document.getElementById('enquiryModal');
        if (modal) {
            modal.style.display = 'flex';
            setTimeout(function() { modal.classList.add('active'); }, 10);
            document.body.style.overflow = 'hidden';
        }
    }

    function closeEnquiryModal() {
        var modal = document.getElementById('enquiryModal');
        if (modal) {
            modal.classList.remove('active');
            setTimeout(function() { modal.style.display = 'none'; }, 300);
            document.body.style.overflow = 'auto';
        }
    }

    function closeModalOnBackdrop(event, modalId) {
        if (event.target.id === modalId) {
            if (modalId === 'enquiryModal') closeEnquiryModal();
            if (modalId === 'videoModal') closeVideoModal();
        }
    }

    function playFilm(url, title, isVideoFile) {
        var modal = document.getElementById('videoModal');
        var container = document.getElementById('videoContainer');
        var titleElem = document.getElementById('videoModalTitle');
        if (!modal || !container) return;

        titleElem.textContent = title || 'Wedding Film';
        container.innerHTML = '';

        if (isVideoFile || url.endsWith('.mp4') || url.endsWith('.webm') || url.endsWith('.mov')) {
            var video = document.createElement('video');
            video.src = url;
            video.controls = true;
            video.autoplay = true;
            video.playsInline = true;
            video.style.width = '100%';
            video.style.height = '100%';
            container.appendChild(video);
        } else if (url.includes('youtube.com') || url.includes('youtu.be')) {
            var videoId = '';
            if (url.includes('youtu.be/')) {
                videoId = url.split('youtu.be/')[1].split('?')[0];
            } else if (url.includes('watch?v=')) {
                videoId = url.split('watch?v=')[1].split('&')[0];
            }
            container.innerHTML = '<iframe src="https://www.youtube.com/embed/' + videoId + '?autoplay=1" style="width:100%;height:100%;border:0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        } else if (url.includes('vimeo.com')) {
            var vimeoId = url.split('/').pop().split('?')[0];
            container.innerHTML = '<iframe src="https://player.vimeo.com/video/' + vimeoId + '?autoplay=1" style="width:100%;height:100%;border:0;" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
        } else if (url && url !== '#') {
            window.open(url, '_blank');
            return;
        } else {
            container.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#888;">Film link coming soon.</div>';
        }

        modal.style.display = 'flex';
        setTimeout(function() { modal.classList.add('active'); }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeVideoModal() {
        var modal = document.getElementById('videoModal');
        var container = document.getElementById('videoContainer');
        if (modal) {
            modal.classList.remove('active');
            setTimeout(function() { 
                modal.style.display = 'none';
                if (container) container.innerHTML = '';
            }, 300);
            document.body.style.overflow = 'auto';
        }
    }

    function submitEnquiry(e) {
        e.preventDefault();
        var form = document.getElementById('enquiryForm');
        var btn = document.getElementById('submitBtn');
        var alertBox = document.getElementById('formAlert');
        var formData = new FormData(form);

        btn.disabled = true;
        btn.textContent = 'Submitting...';

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            btn.disabled = false;
            btn.textContent = 'Submit Enquiry';

            if (data.success) {
                alertBox.style.display = 'block';
                alertBox.style.background = 'rgba(46, 125, 50, 0.2)';
                alertBox.style.border = '1px solid #2e7d32';
                alertBox.style.color = '#81c784';
                alertBox.textContent = data.message;
                form.reset();

                setTimeout(function() {
                    closeEnquiryModal();
                    alertBox.style.display = 'none';
                }, 3500);
            } else {
                alertBox.style.display = 'block';
                alertBox.style.background = 'rgba(198, 40, 40, 0.2)';
                alertBox.style.border = '1px solid #c62828';
                alertBox.style.color = '#ef9a9a';
                alertBox.textContent = data.message || 'Something went wrong. Please check your information.';
            }
        })
        .catch(function(err) {
            btn.disabled = false;
            btn.textContent = 'Submit Enquiry';
            alertBox.style.display = 'block';
            alertBox.style.background = 'rgba(198, 40, 40, 0.2)';
            alertBox.style.border = '1px solid #c62828';
            alertBox.style.color = '#ef9a9a';
            alertBox.textContent = 'Could not submit your enquiry. Please try again.';
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEnquiryModal();
            closeVideoModal();
        }
    });
</script>
@endpush