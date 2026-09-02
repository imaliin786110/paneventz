<!DOCTYPE html>
<html lang="en">

<head>
    @hasSection('seo_override')
        @yield('seo_override')
    @else
        @php
            $overrideTitle = trim($__env->yieldContent('title'));
            $overrideDesc = trim($__env->yieldContent('description'));
            $overrides = array_filter([
                'title' => !empty($overrideTitle) ? $overrideTitle : null,
                'meta_description' => !empty($overrideDesc) ? $overrideDesc : null,
            ]);
        @endphp
        <x-seo-meta :model="$seoModel ?? null" :route="$seoRoute ?? null" :overrides="$overrides" />
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=Manrope:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: #0c0c0d;
            color: #d6d6d8;
            font-family: 'Manrope', -apple-system, BlinkMacSystemFont, sans-serif;
            font-weight: 300;
            line-height: 1.8;
            letter-spacing: 0.3px;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1, h2, h3, h4 {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-weight: 300;
            letter-spacing: -0.5px;
        }

        /* NAVIGATION */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 30px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        nav.nav-scrolled {
            padding: 16px 6%;
            background: rgba(10, 10, 12, 0.90);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(196, 164, 114, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
        }

        .logo {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 30px;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 400;
        }

        .logo a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s;
        }

        .logo a:hover {
            color: #c4a472;
        }

        .nav-links {
            display: flex;
            gap: 34px;
            align-items: center;
        }

        .nav-links a {
            color: #d1d1d6;
            text-decoration: none;
            font-size: 11px;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            font-weight: 400;
            opacity: 0.85;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            color: #c4a472;
            opacity: 1;
        }

        .enquire {
            border: 1px solid rgba(196, 164, 114, 0.8);
            color: #c4a472 !important;
            padding: 10px 22px;
            border-radius: 2px;
            letter-spacing: 2px;
            font-weight: 500;
            transition: all 0.3s ease !important;
        }

        .enquire:hover {
            background: #c4a472;
            color: #0c0c0d !important;
        }

        /* HERO */
        .hero {
            min-height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at center, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.75) 100%);
            z-index: 1;
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 1000px;
            padding: 30px;
        }

        .eyebrow {
            font-size: 10.5px;
            letter-spacing: 6px;
            text-transform: uppercase;
            margin-bottom: 25px;
            color: #c4a472;
            font-weight: 500;
        }

        h1 {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: clamp(65px, 10vw, 150px);
            font-weight: 300;
            line-height: 0.92;
            letter-spacing: -1px;
            margin-bottom: 30px;
            color: #ffffff;
            text-shadow: 0 4px 25px rgba(0,0,0,0.5);
        }

        .hero-description {
            max-width: 550px;
            margin: auto;
            font-size: 15px;
            line-height: 1.9;
            color: #c5c2bd;
            font-weight: 300;
        }

        .hero-button {
            display: inline-block;
            margin-top: 40px;
            padding: 16px 32px;
            border: 1px solid rgba(255,255,255,0.6);
            color: white;
            text-decoration: none;
            font-size: 10px;
            letter-spacing: 3px;
            text-transform: uppercase;
            font-weight: 500;
            border-radius: 2px;
            transition: all 0.35s ease;
        }

        .hero-button:hover {
            background: #ffffff;
            color: #0c0c0d;
            border-color: #ffffff;
            transform: translateY(-2px);
        }

        /* LUXURY MINIMALIST SCROLL INDICATOR */
        .hero-scroll-indicator {
            position: absolute;
            bottom: 35px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            z-index: 5;
            pointer-events: none;
        }
        .hero-scroll-indicator span {
            font-size: 9px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #94a3b8;
            font-weight: 500;
        }
        .scroll-mouse {
            width: 18px;
            height: 30px;
            border-radius: 10px;
            border: 1.5px solid rgba(255,255,255,0.4);
            position: relative;
        }
        .scroll-wheel {
            width: 3px;
            height: 6px;
            background: #c4a472;
            border-radius: 2px;
            position: absolute;
            top: 5px;
            left: 50%;
            transform: translateX(-50%);
            animation: mouseScroll 1.8s infinite;
        }
        @keyframes mouseScroll {
            0% { top: 5px; opacity: 1; }
            80% { top: 15px; opacity: 0; }
            100% { top: 5px; opacity: 0; }
        }

        /* INTRO / PHILOSOPHY SPREAD */
        .intro {
            background: radial-gradient(circle at 50% 30%, rgba(20, 26, 38, 0.9) 0%, rgba(8, 10, 15, 1) 100%);
            color: #f0f0f0;
            padding: 140px 8%;
            border-top: 1px solid rgba(255,255,255,0.06);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .intro-container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .intro-small {
            font-size: 10.5px;
            letter-spacing: 5px;
            text-transform: uppercase;
            margin-bottom: 25px;
            color: #c4a472;
            font-weight: 600;
            display: inline-block;
        }

        .intro h2 {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-weight: 300;
            font-size: clamp(38px, 5.5vw, 78px);
            line-height: 1.1;
            max-width: 980px;
            margin: auto;
            color: #fff;
            letter-spacing: -0.5px;
        }

        .intro p {
            max-width: 720px;
            margin: 30px auto 0;
            color: #94a3b8;
            line-height: 1.9;
            font-size: 15px;
            font-weight: 300;
        }

        .intro-pillars {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 28px;
            margin-top: 65px;
            text-align: left;
        }

        .intro-pillar-card {
            background: rgba(18, 24, 36, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 36px 30px;
            border-radius: 6px;
            position: relative;
            backdrop-filter: blur(10px);
            transition: transform 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease;
        }

        .intro-pillar-card:hover {
            transform: translateY(-5px);
            border-color: rgba(196, 164, 114, 0.45);
            box-shadow: 0 20px 40px rgba(0,0,0,0.6), 0 0 20px rgba(196, 164, 114, 0.1);
        }

        .intro-pillar-card .pillar-num {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 26px;
            color: #c4a472;
            display: block;
            margin-bottom: 12px;
            font-weight: 500;
        }

        .intro-pillar-card h3 {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 23px;
            color: #fff;
            margin-bottom: 10px;
            font-weight: 400;
            letter-spacing: -0.01em;
        }

        .intro-pillar-card p {
            font-size: 13.5px;
            color: #94a3b8;
            line-height: 1.8;
            margin: 0;
        }

        /* STORIES */

.stories {
    background: #111;
    color: #fff;
    padding: 150px 7%;
    overflow: hidden;
}

.stories-heading {
    max-width: 900px;
    margin-bottom: 130px;
}

.stories-heading .intro-small {
    color: #999;
}

.stories-heading h2 {
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(50px, 7vw, 100px);
    font-weight: normal;
    line-height: 0.95;
    letter-spacing: -3px;
    margin-bottom: 35px;
}

.stories-heading p {
    max-width: 500px;
    color: #999;
    font-size: 15px;
    line-height: 1.8;
}


/* STORY */

.story {
    margin-bottom: 180px;
}

.story-image {
    overflow: hidden;
    background: #222;
}

.story-image img,
.story-image video {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    transition: transform 1.2s cubic-bezier(.2,.6,.2,1);
}

.story:hover .story-image img,
.story:hover .story-image video {
    transform: scale(1.04);
}

.story-video-badge {
    position: absolute;
    bottom: 25px;
    right: 25px;
    background: rgba(0, 0, 0, 0.75);
    backdrop-filter: blur(6px);
    border: 1px solid rgba(196, 164, 114, 0.6);
    color: #c4a472;
    padding: 10px 20px;
    border-radius: 30px;
    font-size: 11px;
    letter-spacing: 2px;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: 0.3s;
    z-index: 2;
}

.story:hover .story-video-badge {
    background: #c4a472;
    color: #111;
    border-color: #c4a472;
    transform: scale(1.05);
}


/* LARGE STORY */

.story-large {
    width: 82%;
    margin-left: auto;
}

.story-large .story-image {
    height: 700px;
}


/* STORY INFORMATION */

.story-info {
    padding-top: 30px;
}

.story-number {
    display: block;
    font-size: 10px;
    letter-spacing: 4px;
    color: #777;
    margin-bottom: 18px;
}

.story-info h3 {
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(38px, 5vw, 70px);
    font-weight: normal;
    line-height: 1;
    margin-bottom: 15px;
}

.story-location {
    display: block;
    font-size: 10px;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #888;
}

.story-link {
    display: inline-block;
    margin-top: 25px;
    color: #fff;
    text-decoration: none;
    font-size: 10px;
    letter-spacing: 3px;
    text-transform: uppercase;
    border-bottom: 1px solid #555;
    padding-bottom: 8px;
}


/* REVERSE STORY */

.story-reverse {
    width: 70%;
    margin-right: auto;
}

.story-reverse .story-image {
    height: 600px;
}


/* MOBILE */

@media (max-width: 700px) {

    .stories {
        padding: 100px 20px;
    }

    .stories-heading {
        margin-bottom: 80px;
    }

    .stories-heading h2 {
        letter-spacing: -1px;
    }

    .story,
    .story-large,
    .story-reverse {
        width: 100%;
        margin-bottom: 100px;
    }

    .story-large .story-image,
    .story-reverse .story-image {
        height: 480px;
    }

}

/* FILMS */
.films {
    background: #0d0d0d;
    color: #fff;
    padding: 160px 8%;
    text-align: center;
    border-top: 1px solid rgba(255,255,255,0.06);
    position: relative;
}

.films-content {
    max-width: 1100px;
    margin: 0 auto;
}

.section-label {
    display: inline-block;
    font-size: 10px;
    letter-spacing: 5px;
    text-transform: uppercase;
    color: #c4a472;
    margin-bottom: 25px;
}

.films h2, .services-section h2, .testimonials-section h2, .faqs-section h2 {
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(40px, 5.5vw, 80px);
    font-weight: normal;
    line-height: 1.05;
    letter-spacing: -2px;
    margin-bottom: 25px;
}

.films p, .services-section p.subheading, .testimonials-section p.subheading, .faqs-section p.subheading {
    max-width: 550px;
    margin: 0 auto 50px;
    color: #a09d98;
    font-size: 15px;
    line-height: 1.9;
}

.film-button {
    display: inline-block;
    padding: 16px 34px;
    border: 1px solid rgba(196,164,114, 0.6);
    color: #c4a472;
    text-decoration: none;
    font-size: 10px;
    letter-spacing: 3px;
    text-transform: uppercase;
    transition: 0.3s;
}

.film-button:hover {
    background: #c4a472;
    color: #111;
}

.films-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 40px;
    margin-top: 40px;
    text-align: left;
}

.film-card {
    background: #141414;
    border: 1px solid rgba(255, 255, 255, 0.08);
    overflow: hidden;
    transition: transform 0.4s ease, border-color 0.4s ease;
    cursor: pointer;
    text-decoration: none;
    display: block;
}

.film-card:hover {
    transform: translateY(-5px);
    border-color: rgba(196, 164, 114, 0.5);
}

.film-thumbnail-wrapper {
    position: relative;
    aspect-ratio: 16 / 9;
    background: #000;
    overflow: hidden;
}

.film-thumbnail-wrapper img, .film-thumbnail-wrapper video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.85;
    transition: opacity 0.3s, transform 0.6s ease;
}

.film-card:hover .film-thumbnail-wrapper img, .film-card:hover .film-thumbnail-wrapper video {
    opacity: 1;
    transform: scale(1.03);
}

.film-play-btn {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 58px;
    height: 58px;
    background: rgba(14, 17, 24, 0.75);
    border: 1.5px solid rgba(196, 164, 114, 0.6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #c4a472;
    font-size: 17px;
    padding-left: 3px;
    backdrop-filter: blur(8px);
    box-shadow: 0 0 25px rgba(196, 164, 114, 0.25);
    transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}

.film-card:hover .film-play-btn {
    background: linear-gradient(135deg, #f0dcba 0%, #c4a472 100%);
    border-color: #f0dcba;
    color: #07090e;
    transform: translate(-50%, -50%) scale(1.15);
    box-shadow: 0 0 35px rgba(196, 164, 114, 0.65);
}

.film-info {
    padding: 24px;
}

.film-meta {
    font-size: 10px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #888;
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
}

.film-title {
    font-family: Georgia, serif;
    font-size: 22px;
    color: #fff;
    margin-bottom: 6px;
    font-weight: normal;
}

.film-couple {
    font-size: 13px;
    color: #b5b0a8;
}

/* SERVICES & PACKAGES */
.services-section {
    background: #111;
    color: #fff;
    padding: 160px 8%;
    text-align: center;
    border-top: 1px solid rgba(255,255,255,0.06);
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 35px;
    margin-top: 40px;
    text-align: left;
}

.service-card {
    background: linear-gradient(165deg, rgba(22, 27, 38, 0.75) 0%, rgba(11, 14, 20, 0.95) 100%);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    padding: 48px 36px;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    backdrop-filter: blur(15px);
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease, box-shadow 0.4s ease;
}

.service-card:hover {
    border-color: rgba(196, 164, 114, 0.5);
    transform: translateY(-8px);
    box-shadow: 0 25px 55px rgba(0, 0, 0, 0.75), 0 0 25px rgba(196, 164, 114, 0.12);
}

.service-card.featured {
    background: linear-gradient(165deg, rgba(34, 29, 20, 0.85) 0%, rgba(14, 12, 8, 0.95) 100%);
    border: 1px solid rgba(196, 164, 114, 0.6);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8), 0 0 35px rgba(196, 164, 114, 0.15);
}

.service-badge {
    position: absolute;
    top: -14px;
    right: 25px;
    background: linear-gradient(135deg, #f0dcba 0%, #c4a472 100%);
    color: #07090e;
    font-size: 9.5px;
    letter-spacing: 2px;
    text-transform: uppercase;
    padding: 6px 16px;
    font-weight: 700;
    border-radius: 20px;
    box-shadow: 0 4px 15px rgba(196, 164, 114, 0.4);
}

.service-name {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 30px;
    font-weight: 400;
    color: #fff;
    margin-bottom: 12px;
    letter-spacing: -0.01em;
}

.service-desc {
    color: #9e9a94;
    font-size: 14px;
    line-height: 1.8;
    margin-bottom: 25px;
}

.service-price {
    font-size: 24px;
    color: #c4a472;
    font-weight: 300;
    margin-bottom: 25px;
    letter-spacing: 1px;
}

.service-price small {
    font-size: 11px;
    color: #777;
    letter-spacing: 2px;
    text-transform: uppercase;
    display: block;
    margin-bottom: 4px;
}

.service-btn {
    display: block;
    text-align: center;
    padding: 14px 20px;
    border: 1px solid rgba(255,255,255,0.4);
    color: #fff;
    text-decoration: none;
    font-size: 10px;
    letter-spacing: 3px;
    text-transform: uppercase;
    transition: 0.3s;
    margin-top: 25px;
}

.service-card:hover .service-btn, .service-card.featured .service-btn {
    background: #c4a472;
    border-color: #c4a472;
    color: #111;
    font-weight: bold;
}

/* TESTIMONIALS */
.testimonials-section {
    background: #0d0d0d;
    color: #fff;
    padding: 160px 8%;
    text-align: center;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
}

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 35px;
    margin-top: 40px;
    text-align: left;
}

.testimonial-card {
    background: linear-gradient(160deg, rgba(20, 25, 36, 0.75) 0%, rgba(11, 14, 20, 0.95) 100%);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 8px;
    padding: 44px 34px;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(12px);
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease, box-shadow 0.4s ease;
}

.testimonial-card::before {
    content: '“';
    position: absolute;
    top: 10px;
    right: 25px;
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 80px;
    color: rgba(196, 164, 114, 0.12);
    line-height: 1;
    pointer-events: none;
}

.testimonial-card:hover {
    border-color: rgba(196, 164, 114, 0.45);
    transform: translateY(-6px);
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.7), 0 0 25px rgba(196, 164, 114, 0.1);
}

.testimonial-stars {
    color: #c4a472;
    font-size: 15px;
    margin-bottom: 18px;
    letter-spacing: 4px;
}

.testimonial-quote {
    font-size: 14.5px;
    line-height: 1.9;
    color: #cbd5e1;
    margin-bottom: 25px;
    font-style: italic;
    position: relative;
    z-index: 1;
}

.testimonial-name {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 22px;
    color: #fff;
    margin-bottom: 4px;
    letter-spacing: -0.01em;
}

.testimonial-loc {
    font-size: 10px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #888;
}

/* FAQS ACCORDION */
.faqs-section {
    background: #111;
    color: #fff;
    padding: 160px 8%;
    border-top: 1px solid rgba(255,255,255,0.06);
}

.faqs-container {
    max-width: 850px;
    margin: 0 auto;
}

.faq-item {
    border-bottom: 1px solid rgba(255,255,255,0.1);
    padding: 24px 0;
}

.faq-question {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    font-family: Georgia, serif;
    font-size: 20px;
    color: #fff;
    transition: color 0.3s;
}

.faq-question:hover {
    color: #c4a472;
}

.faq-icon {
    font-size: 22px;
    font-weight: 300;
    transition: transform 0.3s ease;
}

.faq-item.active .faq-icon {
    transform: rotate(45deg);
    color: #c4a472;
}

.faq-answer {
    display: none;
    padding-top: 16px;
    color: #a09d98;
    font-size: 15px;
    line-height: 1.85;
}

.faq-item.active .faq-answer {
    display: block;
}

/* FOOTER */
footer {
    background: #080808;
    color: #fff;
    padding: 140px 8% 80px;
    text-align: center;
    border-top: 1px solid rgba(255,255,255,0.08);
}

footer h2 {
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(40px, 6vw, 85px);
    font-weight: normal;
    line-height: 1;
    letter-spacing: -2px;
    margin-bottom: 25px;
}

footer p {
    font-size: 11px;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #888;
    margin-bottom: 45px;
}

.footer-button {
    display: inline-block;
    padding: 18px 38px;
    background: #fff;
    color: #111;
    text-decoration: none;
    font-size: 10px;
    letter-spacing: 3px;
    text-transform: uppercase;
    font-weight: bold;
    transition: 0.3s;
}

.footer-button:hover {
    background: #c4a472;
    color: #111;
}

.footer-socials {
    margin-top: 60px;
    display: flex;
    justify-content: center;
    gap: 30px;
}

.footer-socials a {
    color: #888;
    text-decoration: none;
    font-size: 11px;
    letter-spacing: 2px;
    text-transform: uppercase;
    transition: color 0.3s;
}

.footer-socials a:hover {
    color: #c4a472;
}

/* PREMIUM FOOTER CONTACT HUB */
.premium-contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(290px, 1fr));
    gap: 22px;
    max-width: 1080px;
    margin: 40px auto 45px;
    text-align: left;
}

.premium-contact-card {
    background: linear-gradient(180deg, rgba(16, 22, 34, 0.85) 0%, rgba(10, 14, 22, 0.95) 100%);
    border: 1px solid rgba(196, 164, 114, 0.24);
    border-radius: 12px;
    padding: 26px 28px;
    transition: all 0.35s ease;
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.6);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.premium-contact-card:hover {
    border-color: rgba(196, 164, 114, 0.65);
    transform: translateY(-4px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.85), 0 0 30px rgba(196, 164, 114, 0.16);
}

.premium-contact-label {
    font-size: 10.5px;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: #c4a472;
    font-weight: 700;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.premium-phone-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.premium-phone-link {
    color: #ffffff;
    text-decoration: none;
    font-size: 16px;
    font-weight: 600;
    font-family: 'Manrope', -apple-system, sans-serif;
    letter-spacing: 2px;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 8px 0;
    transition: all 0.3s ease;
}

.premium-phone-link:hover {
    color: #fce7b2;
    transform: translateX(4px);
}

.premium-phone-badge {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(196, 164, 114, 0.12);
    border: 1px solid rgba(196, 164, 114, 0.35);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    color: #c4a472;
    flex-shrink: 0;
}

.premium-contact-sub {
    font-size: 11.5px;
    letter-spacing: 1px;
    color: #94a3b8;
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
}

/* MODALS */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(8px);
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.modal-overlay.active {
    display: flex;
    opacity: 1;
}

.modal-card {
    background: #141414;
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 4px;
    width: 100%;
    max-width: 620px;
    max-height: 90vh;
    overflow-y: auto;
    padding: 40px;
    position: relative;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.8);
}

.modal-close {
    position: absolute;
    top: 20px;
    right: 20px;
    background: transparent;
    border: none;
    color: #aaa;
    font-size: 26px;
    cursor: pointer;
    line-height: 1;
    transition: 0.2s;
}

.modal-close:hover {
    color: #fff;
}

.form-group {
    margin-bottom: 20px;
    text-align: left;
}

.form-group label {
    display: block;
    font-size: 11px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #aaa;
    margin-bottom: 8px;
}

.form-control {
    width: 100%;
    background: #1c1c1c;
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #fff;
    padding: 14px 16px;
    font-size: 14px;
    outline: none;
    transition: 0.3s;
}

.form-control:focus {
    border-color: #c4a472;
    background: #222;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

@media (max-width: 600px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}

.btn-submit {
    width: 100%;
    padding: 16px;
    background: #c4a472;
    color: #111;
    border: none;
    font-size: 11px;
    letter-spacing: 3px;
    text-transform: uppercase;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
    margin-top: 10px;
}

.btn-submit:hover {
    background: #e2c494;
}

.toast-alert {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: #1a1a1a;
    border-left: 4px solid #c4a472;
    padding: 16px 24px;
    color: #fff;
    font-size: 14px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    z-index: 100000;
    display: flex;
    align-items: center;
    gap: 12px;
}

/* GALLERY LIGHTBOX */
.gallery-modal-content {
    max-width: 95vw;
    width: 1100px;
    background: transparent;
    border: none;
    box-shadow: none;
    padding: 0;
    text-align: center;
    position: relative;
}

.gallery-main-view {
    position: relative;
    max-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.gallery-main-img {
    max-height: 78vh;
    max-width: 100%;
    object-fit: contain;
    border-radius: 3px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.9);
}

.gallery-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0,0,0,0.6);
    border: 1px solid rgba(255,255,255,0.2);
    color: #fff;
    font-size: 22px;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.3s;
}

.gallery-arrow:hover {
    background: #c4a472;
    color: #111;
    border-color: #c4a472;
}

.gallery-arrow.prev { left: 15px; }
.gallery-arrow.next { right: 15px; }

.gallery-counter {
    margin-top: 15px;
    color: #aaa;
    font-size: 11px;
    letter-spacing: 2px;
}

/* FLOATING WHATSAPP BUTTON */
.whatsapp-float-container {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 99998;
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
}

.whatsapp-float-btn {
    width: 60px;
    height: 60px;
    background: #25D366;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(37, 211, 102, 0.45);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
}

.whatsapp-float-btn svg {
    width: 34px;
    height: 34px;
    fill: #ffffff;
}

.whatsapp-float-btn:hover {
    transform: scale(1.08);
    box-shadow: 0 12px 32px rgba(37, 211, 102, 0.65);
}

.whatsapp-tooltip {
    background: #141414;
    border: 1px solid rgba(196, 164, 114, 0.5);
    color: #fff;
    font-size: 12px;
    letter-spacing: 1px;
    padding: 8px 16px;
    border-radius: 20px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.6);
    white-space: nowrap;
    opacity: 0;
    transform: translateX(10px);
    transition: opacity 0.3s ease, transform 0.3s ease;
    pointer-events: none;
    font-family: Arial, Helvetica, sans-serif;
}

.whatsapp-float-container:hover .whatsapp-tooltip {
    opacity: 1;
    transform: translateX(0);
}

/* Pulsing wave */
.whatsapp-pulse {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: #25D366;
    opacity: 0.5;
    animation: wa-pulse-anim 2.2s infinite;
    z-index: -1;
}

@keyframes wa-pulse-anim {
    0% {
        transform: scale(1);
        opacity: 0.6;
    }
    70% {
        transform: scale(1.4);
        opacity: 0;
    }
    100% {
        transform: scale(1.4);
        opacity: 0;
    }
}

@media (max-width: 768px) {
    .whatsapp-float-container {
        bottom: 20px;
        right: 20px;
    }
    .whatsapp-float-btn {
        width: 52px;
        height: 52px;
    }
    .whatsapp-float-btn svg {
        width: 28px;
        height: 28px;
    }
    .whatsapp-tooltip {
        display: none;
    }
}

/* PRESS & RECOGNITION BAR */
.press-bar {
    background: #0a0a0a;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    padding: 35px 8%;
    text-align: center;
}

.press-label {
    font-size: 9px;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: #666;
    margin-bottom: 20px;
}

.press-logos {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: clamp(25px, 5vw, 65px);
    flex-wrap: wrap;
}

.press-item {
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(14px, 1.8vw, 20px);
    letter-spacing: 3px;
    color: #777;
    text-transform: uppercase;
    transition: color 0.3s ease;
}

.press-item:hover {
    color: #c4a472;
}

/* EMOTIONAL TRUST & PEACE OF MIND SECTION */
.trust-letter-section {
    background: radial-gradient(circle at 50% 20%, rgba(28, 22, 16, 0.75) 0%, rgba(8, 9, 13, 1) 100%);
    border-top: 1px solid rgba(196, 164, 114, 0.18);
    border-bottom: 1px solid rgba(196, 164, 114, 0.18);
    padding: 135px 8%;
    color: #fff;
    position: relative;
    overflow: hidden;
}

.trust-letter-container {
    max-width: 1180px;
    margin: 0 auto;
    text-align: center;
}

.trust-letter-eyebrow {
    font-size: 11px;
    letter-spacing: 4.5px;
    text-transform: uppercase;
    color: #c4a472;
    font-weight: 600;
    margin-bottom: 22px;
    display: inline-block;
}

.trust-letter-heading {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: clamp(34px, 5vw, 65px);
    font-weight: 300;
    line-height: 1.18;
    color: #fff;
    margin-bottom: 65px;
    letter-spacing: -0.5px;
}

.trust-letter-heading span {
    background: linear-gradient(135deg, #f0dcba 0%, #c4a472 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-style: italic;
}

.trust-letter-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 38px;
    text-align: left;
}

.trust-letter-col {
    background: rgba(18, 23, 34, 0.65);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    padding: 46px 40px;
    backdrop-filter: blur(15px);
    position: relative;
}

.trust-col-promise {
    background: linear-gradient(165deg, rgba(30, 25, 18, 0.8) 0%, rgba(12, 14, 21, 0.95) 100%);
    border-color: rgba(196, 164, 114, 0.38);
    box-shadow: 0 25px 50px rgba(0,0,0,0.65), 0 0 25px rgba(196,164,114,0.08);
}

.trust-col-badge {
    display: inline-block;
    font-size: 10px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #94a3b8;
    background: rgba(255,255,255,0.06);
    padding: 5px 14px;
    border-radius: 20px;
    margin-bottom: 22px;
    font-weight: 600;
}

.trust-col-badge-gold {
    color: #c4a472;
    background: rgba(196, 164, 114, 0.12);
    border: 1px solid rgba(196, 164, 114, 0.3);
}

.trust-letter-col h3 {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 27px;
    color: #fff;
    margin-bottom: 18px;
    font-weight: 400;
    line-height: 1.25;
    letter-spacing: -0.01em;
}

.trust-letter-col p {
    font-size: 14.5px;
    line-height: 1.9;
    color: #cbd5e1;
    margin-bottom: 18px;
}

.trust-signature-bar {
    margin-top: 30px;
    padding-top: 22px;
    border-top: 1px solid rgba(196, 164, 114, 0.25);
}

.trust-signature-quote {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-style: italic;
    font-size: 19px;
    color: #e2c99b;
    margin-bottom: 6px;
}

.trust-signature-name {
    font-size: 11px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #94a3b8;
}

/* THE PANEVENTZ EXPERIENCE (4-STEP PROCESS) */
.experience-section {
    background: #0f0f10;
    color: #fff;
    padding: 160px 8%;
    border-top: 1px solid rgba(255,255,255,0.06);
    text-align: center;
}

.experience-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 30px;
    margin-top: 50px;
    text-align: left;
}

.experience-card {
    background: linear-gradient(160deg, rgba(20, 25, 36, 0.8) 0%, rgba(10, 13, 19, 0.95) 100%);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 8px;
    padding: 42px 32px;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(12px);
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease, box-shadow 0.4s ease;
}

.experience-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 0%;
    height: 2px;
    background: linear-gradient(90deg, #c4a472 0%, #00f0ff 100%);
    transition: width 0.45s ease;
}

.experience-card:hover {
    transform: translateY(-8px);
    border-color: rgba(196, 164, 114, 0.4);
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.7), 0 0 25px rgba(196, 164, 114, 0.12);
}

.experience-card:hover::after {
    width: 100%;
}

.experience-num {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 44px;
    font-weight: 300;
    background: linear-gradient(135deg, #f0dcba 0%, #c4a472 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 16px;
    line-height: 1;
}

.experience-title {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 24px;
    color: #fff;
    margin-bottom: 12px;
    font-weight: 400;
    letter-spacing: -0.01em;
}

.experience-desc {
    color: #94a3b8;
    font-size: 13.5px;
    line-height: 1.85;
}

/* DESTINATIONS WE COVER */
.destinations-section {
    background: #111;
    color: #fff;
    padding: 160px 8%;
    border-top: 1px solid rgba(255,255,255,0.06);
    text-align: center;
}

.destinations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 25px;
    margin-top: 50px;
    text-align: left;
}

.destination-card {
    position: relative;
    height: 380px;
    border-radius: 4px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 30px;
    border: 1px solid rgba(255,255,255,0.1);
    background-size: cover;
    background-position: center;
    transition: transform 0.5s ease;
}

.destination-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.85) 100%);
    transition: background 0.3s;
}

.destination-card:hover {
    transform: translateY(-5px);
}

.destination-card:hover::before {
    background: linear-gradient(180deg, rgba(0,0,0,0.0) 0%, rgba(0,0,0,0.92) 100%);
}

.destination-content {
    position: relative;
    z-index: 2;
}

.destination-type {
    font-size: 10px;
    letter-spacing: 3px;
    color: #c4a472;
    text-transform: uppercase;
    margin-bottom: 6px;
}

.destination-title {
    font-family: Georgia, serif;
    font-size: 24px;
    color: #fff;
    margin-bottom: 8px;
    font-weight: normal;
}

.destination-places {
    font-size: 12px;
    color: #b5b0a8;
    line-height: 1.6;
}

/* STATS COUNTER BAR */
.stats-counter-section {
    background: #0c0c0c;
    border-top: 1px solid rgba(255,255,255,0.06);
    border-bottom: 1px solid rgba(255,255,255,0.06);
    padding: 70px 8%;
}

.stats-counter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 40px;
    text-align: center;
}

.stat-counter-box {
    padding: 10px 15px;
    transition: transform 0.3s ease;
}

.stat-counter-box:hover {
    transform: translateY(-4px);
}

.stat-item-number {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: clamp(48px, 5.5vw, 70px);
    font-weight: 500;
    color: #c4a472;
    background: linear-gradient(135deg, #f7dfaf 0%, #c4a472 50%, #9a7844 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 8px;
    line-height: 1;
    letter-spacing: -0.02em;
    filter: drop-shadow(0 2px 12px rgba(196, 164, 114, 0.25));
}

.stat-item-label {
    font-size: 11.5px;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: #94a3b8;
    line-height: 1.5;
}

/* INSTAGRAM SHOWCASE */
.instagram-showcase {
    background: #0d0d0d;
    padding: 100px 0 0;
    text-align: center;
}

.instagram-header {
    padding: 0 8% 40px;
}

.instagram-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 4px;
    margin-top: 30px;
}

@media (max-width: 900px) {
    .instagram-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 600px) {
    .instagram-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.insta-card {
    position: relative;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    display: block;
    background: #111;
}

.insta-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}

.insta-card:hover img {
    transform: scale(1.08);
}

.insta-overlay {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.55);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 24px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.insta-card:hover .insta-overlay {
    opacity: 1;
}

/* COLOR GRADING BEFORE & AFTER SLIDER */
.color-grade-section {
    background: #09090b;
    color: #fff;
    padding: 160px 8%;
    text-align: center;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    position: relative;
    overflow: hidden;
}

.color-slider-wrapper {
    max-width: 1050px;
    margin: 45px auto 0;
    position: relative;
}

.color-slider-container {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;
    overflow: hidden;
    border-radius: 6px;
    border: 1px solid rgba(196, 164, 114, 0.35);
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.8), 0 0 30px rgba(196, 164, 114, 0.08);
    user-select: none;
    touch-action: none;
    cursor: ew-resize;
    background: #000;
}

.slider-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    pointer-events: none;
}

.slider-before-wrap {
    position: absolute;
    top: 0;
    left: 0;
    width: 50%;
    height: 100%;
    overflow: hidden;
    z-index: 2;
    pointer-events: none;
    border-right: 2px solid #c4a472;
}

.slider-before-wrap .slider-img-before {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    max-width: none;
    object-fit: cover;
}

.slider-badge {
    position: absolute;
    bottom: 22px;
    padding: 10px 20px;
    border-radius: 30px;
    font-size: 11px;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    font-weight: 700;
    z-index: 5;
    pointer-events: none;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    white-space: nowrap;
}

.slider-badge-left {
    left: 22px;
    background: rgba(8, 11, 18, 0.92);
    color: #f1f5f9;
    border: 1.5px solid rgba(255, 255, 255, 0.4);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.9);
}

.slider-badge-right {
    right: 22px;
    background: rgba(10, 14, 24, 0.94);
    color: #fce7b2;
    border: 1.5px solid #c4a472;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.9), 0 0 20px rgba(196, 164, 114, 0.45);
}

.slider-handle {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 50%;
    width: 2px;
    background: transparent;
    z-index: 4;
    transform: translateX(-50%);
    pointer-events: none;
}

.slider-handle-button {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: #0d0d0f;
    border: 2px solid #c4a472;
    color: #c4a472;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 25px rgba(196, 164, 114, 0.7);
    pointer-events: auto;
    cursor: ew-resize;
}

.slider-drag-hint {
    margin-top: 18px;
    font-size: 11px;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #888;
}

.grading-pillars {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    margin-top: 60px;
    text-align: left;
    max-width: 1050px;
    margin-left: auto;
    margin-right: auto;
}

.pillar-item {
    background: #111114;
    border: 1px solid rgba(255, 255, 255, 0.07);
    padding: 35px 30px;
    border-radius: 4px;
    transition: transform 0.3s, border-color 0.3s;
}

.pillar-item:hover {
    transform: translateY(-4px);
    border-color: rgba(196, 164, 114, 0.4);
}

.pillar-icon {
    color: #c4a472;
    font-size: 18px;
    margin-bottom: 12px;
}

.pillar-title {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 22px;
    color: #fff;
    margin-bottom: 10px;
    font-weight: 400;
}

.pillar-desc {
    color: #888;
    font-size: 13.5px;
    line-height: 1.8;
}

/* MOBILE HAMBURGER & RESPONSIVE DRAWER */
.mobile-nav-toggle {
    display: none;
    background: transparent;
    border: none;
    color: #fff;
    font-size: 28px;
    cursor: pointer;
    z-index: 100001;
}

.mobile-nav-drawer {
    position: fixed;
    top: 0;
    right: -100%;
    width: 85%;
    max-width: 360px;
    height: 100vh;
    background: #141414;
    border-left: 1px solid rgba(255, 255, 255, 0.1);
    z-index: 100002;
    padding: 90px 35px 40px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: right 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: -15px 0 50px rgba(0, 0, 0, 0.9);
}

.mobile-nav-drawer.active {
    right: 0;
}

.mobile-drawer-close {
    position: absolute;
    top: 25px;
    right: 25px;
    background: transparent;
    border: none;
    color: #aaa;
    font-size: 32px;
    cursor: pointer;
}

.mobile-nav-links {
    display: flex;
    flex-direction: column;
    gap: 22px;
}

.mobile-nav-links a {
    color: #fff;
    text-decoration: none;
    font-size: 17px;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-family: Georgia, serif;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    transition: color 0.3s;
}

.mobile-nav-links a:hover {
    color: #c4a472;
}

.mobile-drawer-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.75);
    backdrop-filter: blur(4px);
    z-index: 100000;
    display: none;
}

.mobile-drawer-overlay.active {
    display: block;
}

@media (max-width: 900px) {
    nav {
        padding: 24px 6% !important;
    }
    .nav-links {
        display: none !important;
    }
    .mobile-nav-toggle {
        display: block !important;
    }
}

/* ========================================================= */
/* COMPREHENSIVE LUXURY MOBILE & TABLET OPTIMIZATIONS        */
/* ========================================================= */
html, body {
    overflow-x: hidden;
    max-width: 100vw;
}

.fast-load-video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    background: #111;
}

@media (max-width: 768px) {
    /* GLOBAL PADDING */
    .intro,
    .stories,
    .films,
    .experience-section,
    .color-grade-section,
    .collections-section,
    .testimonials-section,
    .faqs-section,
    .stats-counter-section {
        padding-left: 20px !important;
        padding-right: 20px !important;
    }

    /* HERO */
    .hero {
        min-height: 85vh !important;
        padding: 110px 20px 60px !important;
    }
    .hero h1 {
        font-size: clamp(42px, 11vw, 76px) !important;
        line-height: 1.05 !important;
    }
    .hero-description {
        font-size: 14px !important;
        line-height: 1.7 !important;
        max-width: 96% !important;
    }

    /* ABOUT / INTRO */
    .intro {
        padding: 85px 20px !important;
    }
    .intro h2 {
        font-size: clamp(28px, 6.5vw, 42px) !important;
    }
    .intro p {
        font-size: 14.5px !important;
        line-height: 1.8 !important;
    }

    /* STORIES & VIDEOS */
    .stories {
        padding: 70px 20px !important;
    }
    .stories-heading {
        margin-bottom: 45px !important;
    }
    .stories-heading h2 {
        font-size: clamp(34px, 8vw, 50px) !important;
    }
    .story,
    .story-large,
    .story-reverse {
        width: 100% !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        margin-bottom: 55px !important;
    }
    .story-image,
    .story-large .story-image,
    .story-reverse .story-image {
        width: 100% !important;
        height: auto !important;
        aspect-ratio: 4 / 5 !important;
        border-radius: 6px !important;
    }
    .story-info {
        padding-top: 18px !important;
    }
    .story-info h3 {
        font-size: clamp(28px, 7.5vw, 42px) !important;
        margin-top: 6px !important;
    }
    .story-link {
        margin-top: 14px !important;
        font-size: 12px !important;
    }
    .story-video-badge {
        bottom: 15px !important;
        right: 15px !important;
        padding: 6px 14px !important;
        font-size: 11px !important;
    }

    /* FILMS */
    .films {
        padding: 85px 20px !important;
    }
    .films-grid {
        grid-template-columns: 1fr !important;
        gap: 32px !important;
        margin-top: 30px !important;
    }
    .film-thumb {
        aspect-ratio: 16 / 9 !important;
        border-radius: 6px !important;
    }

    /* THE PANEVENTZ EXPERIENCE (PROCESS 01-04) */
    .experience-section {
        padding: 85px 20px !important;
    }
    .experience-grid {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
        margin-top: 30px !important;
    }
    .experience-card {
        padding: 26px 20px !important;
    }

    /* COLOR GRADING BEFORE & AFTER SLIDER */
    .color-grade-section {
        padding: 75px 16px !important;
    }
    .color-slider-wrapper {
        margin-top: 25px !important;
    }
    .color-slider-container {
        aspect-ratio: 16 / 10 !important;
        border-radius: 6px !important;
    }
    .slider-badge {
        bottom: 14px !important;
        padding: 6px 14px !important;
        font-size: 10px !important;
        letter-spacing: 1px !important;
        font-weight: 700 !important;
    }
    .slider-badge-left {
        left: 12px !important;
    }
    .slider-badge-right {
        right: 12px !important;
    }
    .slider-handle-button {
        width: 38px !important;
        height: 38px !important;
        font-size: 12px !important;
    }
    .grading-pillars {
        grid-template-columns: 1fr !important;
        gap: 16px !important;
        margin-top: 35px !important;
    }
    .pillar-item {
        padding: 24px 20px !important;
    }

    /* CURATED COLLECTIONS / PACKAGES */
    .collections-section {
        padding: 80px 20px !important;
    }
    .collections-grid {
        grid-template-columns: 1fr !important;
        gap: 24px !important;
    }

    /* STUDIO MILESTONES (ANIMATED COUNTER 2x2 GRID ON MOBILE) */
    .stats-counter-section {
        padding: 50px 16px !important;
    }
    .stats-counter-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 24px 12px !important;
    }
    .stat-item-number {
        font-size: clamp(34px, 8.5vw, 48px) !important;
    }
    .stat-item-label {
        font-size: 10px !important;
        letter-spacing: 1.5px !important;
        line-height: 1.4 !important;
    }

    /* TESTIMONIALS */
    .testimonials-section {
        padding: 80px 20px !important;
    }
    .testimonials-grid {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
    }
    .testimonial-card {
        padding: 28px 22px !important;
    }

    /* FAQS */
    .faqs-section {
        padding: 80px 20px !important;
    }

    /* INSTAGRAM */
    .instagram-showcase {
        padding: 70px 20px !important;
    }
    .instagram-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 10px !important;
    }

    /* FOOTER */
    footer {
        padding: 70px 20px 40px !important;
    }
    .footer-grid {
        grid-template-columns: 1fr !important;
        gap: 35px !important;
        text-align: center !important;
    }
    .footer-brand {
        display: flex;
        flex-direction: column;
        align-items: center !important;
    }
    .footer-social {
        justify-content: center !important;
    }
}

    </style>

</head>

<body>

    @yield('content')

    @php
        $rawWa = \App\Models\WebsiteSetting::first()?->whatsapp ?? '+91 8082024787';
        $cleanWa = preg_replace('/[^0-9]/', '', (string)$rawWa) ?: '918082024787';
        $waMessage = rawurlencode("Hello Paneventz! I visited your website and would like to inquire about your wedding photography & films.");
    @endphp

    <!-- MOBILE NAVIGATION DRAWER -->
    <div id="mobileDrawerOverlay" class="mobile-drawer-overlay" onclick="toggleMobileNav()"></div>
    <div id="mobileNavDrawer" class="mobile-nav-drawer">
        <button type="button" class="mobile-drawer-close" onclick="toggleMobileNav()">&times;</button>
        <div class="mobile-nav-links">
            <a href="/client-portal" onclick="toggleMobileNav()" style="color: #00f0ff; font-weight: bold; border-color: rgba(0,240,255,0.2);">Download Your Story 🔒</a>
            <a href="/#stories" onclick="toggleMobileNav()">Stories</a>
            <a href="/#films" onclick="toggleMobileNav()">Films</a>
            <a href="/services" onclick="toggleMobileNav()">Services & Packages</a>
            <a href="/galleries" onclick="toggleMobileNav()" style="color: #c4a472; font-weight: bold;">Guest Photos AI 📸</a>
            <a href="/#about" onclick="toggleMobileNav()">About Paneventz</a>
        </div>
        <div>
            <a href="javascript:void(0)" onclick="toggleMobileNav(); openEnquiryModal();" class="film-button" style="display: block; text-align: center; margin-bottom: 15px;">
                Start A Conversation
            </a>
            <div style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #666; text-align: center;">
                Paneventz Studio · India & Worldwide
            </div>
        </div>
    </div>

    <!-- FLOATING WHATSAPP BUTTON -->
    <a href="https://wa.me/{{ $cleanWa }}?text={{ $waMessage }}" target="_blank" rel="noopener noreferrer" class="whatsapp-float-container" aria-label="Chat on WhatsApp">
        <span class="whatsapp-tooltip">Chat with us on WhatsApp</span>
        <div class="whatsapp-float-btn">
            <span class="whatsapp-pulse"></span>
            <svg viewBox="0 0 32 32" aria-hidden="true">
                <path d="M16.002 2C8.269 2 2 8.27 2 16.004c0 2.585.696 5.097 2.016 7.306L2.06 30.142l7.027-1.92a13.924 13.924 0 0 0 6.915 1.786h.006c7.73 0 14-6.27 14-14.004 0-3.74-1.455-7.255-4.101-9.9A13.914 13.914 0 0 0 16.002 2zm8.17 19.827c-.34.96-1.704 1.76-2.394 1.874-.636.105-1.467.15-4.26-1.008-3.57-1.48-5.87-5.11-6.05-5.35-.175-.24-1.439-1.916-1.439-3.655 0-1.74.91-2.597 1.233-2.955.324-.358.706-.448.941-.448.236 0 .47.002.678.012.219.01.512-.083.8.61.306.735 1.042 2.545 1.134 2.73.093.186.155.405.031.649-.123.243-.186.395-.369.608-.184.214-.388.477-.554.64-.185.18-.377.377-.162.747.215.37 1.007 1.66 2.164 2.69 1.488 1.326 2.743 1.737 3.13 1.93.387.194.614.162.842-.1.228-.261.977-1.14 1.24-1.53.262-.39.524-.325.88-.195.357.13 2.257 1.064 2.646 1.258.388.194.648.29.74.453.093.163.093.945-.247 1.905z"/>
            </svg>
        </div>
    </a>

    <script>
        function toggleMobileNav() {
            const drawer = document.getElementById('mobileNavDrawer');
            const overlay = document.getElementById('mobileDrawerOverlay');
            if (drawer && overlay) {
                drawer.classList.toggle('active');
                overlay.classList.toggle('active');
                document.body.style.overflow = drawer.classList.contains('active') ? 'hidden' : '';
            }
        }

        window.addEventListener('scroll', function () {
            const nav = document.querySelector('nav');
            if (nav) {
                if (window.scrollY > 40) {
                    nav.classList.add('nav-scrolled');
                } else {
                    nav.classList.remove('nav-scrolled');
                }
            }
        });

        // Media Protection (Right-click & Drag Prevention on Portfolio Photos/Videos)
        document.addEventListener('contextmenu', function (e) {
            if (e.target.tagName === 'IMG' || e.target.tagName === 'VIDEO' || e.target.closest('.story-image') || e.target.closest('.film-thumbnail-wrapper') || e.target.closest('.color-slider-container')) {
                e.preventDefault();
            }
        });
        document.addEventListener('dragstart', function (e) {
            if (e.target.tagName === 'IMG' || e.target.tagName === 'VIDEO') {
                e.preventDefault();
            }
        });

        // Global Modal & Toast Helpers
        function openEnquiryModal(serviceName = '') {
            const modal = document.getElementById('enquiryModal');
            if (!modal) return;
            if (serviceName) {
                const input = document.getElementById('modalServiceInput');
                if (input) input.value = serviceName;
            }
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeEnquiryModal() {
            const modal = document.getElementById('enquiryModal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }

        function closeModalOnBackdrop(e, modalId) {
            if (e.target.id === modalId) {
                const el = document.getElementById(modalId);
                if (el) {
                    el.classList.remove('active');
                    document.body.style.overflow = '';
                }
            }
        }

        function showToast(msg) {
            const toast = document.getElementById('toastNotification');
            const toastMsg = document.getElementById('toastMsg');
            if (toast && toastMsg) {
                toastMsg.innerText = msg;
                toast.style.display = 'flex';
                toast.classList.add('show');
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.style.display = 'none', 300);
                }, 4000);
            }
        }

        function submitEnquiry(e) {
            e.preventDefault();
            const form = document.getElementById('enquiryForm');
            const submitBtn = document.getElementById('submitBtn');
            if (!form) return;

            const formData = new FormData(form);
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerText = 'Submitting...';
            }

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
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Submit Inquiry';
                }
                if (data.success) {
                    form.reset();
                    closeEnquiryModal();
                    showToast('Thank you! Your inquiry has been sent to our studio.');
                } else {
                    alert('Something went wrong. Please try again.');
                }
            })
            .catch(err => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Submit Inquiry';
                }
                alert('Something went wrong. Please try again.');
            });
        }
    </script>

    <!-- GLOBAL TOAST NOTIFICATION -->
    <div id="toastNotification" class="toast-alert" style="display: none;">
        <span style="color: #c4a472; font-size: 18px;">✓</span>
        <span id="toastMsg">Inquiry sent successfully!</span>
    </div>

    @stack('scripts')

</body>

</html>
