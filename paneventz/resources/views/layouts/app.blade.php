<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Paneventz — Wedding Photography & Films')
    </title>

    <meta name="description"
          content="@yield('description', 'Paneventz creates timeless wedding photography and cinematic wedding films.')">

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
            background: #111;
            color: #fff;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* NAVIGATION */

        nav {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 30px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
        }

        .logo {
            font-family: Georgia, serif;
            font-size: 28px;
            letter-spacing: 1px;
        }

        .nav-links {
            display: flex;
            gap: 35px;
            align-items: center;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            opacity: 0.85;
            transition: 0.3s;
        }

        .nav-links a:hover {
            opacity: 1;
        }

        .enquire {
            border: 1px solid rgba(255,255,255,0.7);
            padding: 12px 20px;
        }

        /* HERO */

        .hero {
            min-height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;

            background:
    linear-gradient(
        rgba(0,0,0,0.35),
        rgba(0,0,0,0.68)
    ),
    url('/images/hero.jpg.png');

background-size: cover;
background-position: center;
background-repeat: no-repeat;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 1000px;
            padding: 30px;
        }

        .eyebrow {
            font-size: 11px;
            letter-spacing: 6px;
            text-transform: uppercase;
            margin-bottom: 35px;
            color: #d4d0ca;
        }

        h1 {
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(70px, 12vw, 170px);
            font-weight: normal;
            line-height: 0.82;
            letter-spacing: -7px;
            margin-bottom: 40px;
        }

        .hero-description {
            max-width: 520px;
            margin: auto;
            font-size: 15px;
            line-height: 1.8;
            color: #d0ccc8;
        }

        .hero-button {
            display: inline-block;
            margin-top: 45px;
            padding: 17px 30px;
            border: 1px solid rgba(255,255,255,0.7);
            color: white;
            text-decoration: none;
            font-size: 10px;
            letter-spacing: 3px;
            text-transform: uppercase;
            transition: 0.4s;
        }

        .hero-button:hover {
            background: white;
            color: #111;
        }

        .scroll {
            position: absolute;
            bottom: 35px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 9px;
            letter-spacing: 4px;
            color: #aaa;
        }

        /* INTRO */

        .intro {
            background: #f1eee9;
            color: #171717;
            padding: 150px 10%;
            text-align: center;
        }

        .intro-small {
            font-size: 10px;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 35px;
            color: #777;
        }

        .intro h2 {
            font-family: Georgia, serif;
            font-weight: normal;
            font-size: clamp(40px, 6vw, 80px);
            line-height: 1;
            max-width: 950px;
            margin: auto;
        }

        .intro p {
            max-width: 600px;
            margin: 40px auto 0;
            color: #666;
            line-height: 1.9;
            font-size: 15px;
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

.story-image img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    transition: transform 1.2s cubic-bezier(.2,.6,.2,1);
}

.story:hover .story-image img {
    transform: scale(1.04);
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
    padding: 160px 10%;
    text-align: center;
    border-top: 1px solid rgba(255,255,255,0.06);
    position: relative;
}

.films-content {
    max-width: 800px;
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

.films h2 {
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(45px, 6vw, 85px);
    font-weight: normal;
    line-height: 1.05;
    letter-spacing: -2px;
    margin-bottom: 30px;
}

.films p {
    max-width: 550px;
    margin: 0 auto 45px;
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

/* FOOTER */
footer {
    background: #080808;
    color: #fff;
    padding: 140px 10% 80px;
    text-align: center;
    border-top: 1px solid rgba(255,255,255,0.08);
}

footer h2 {
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(45px, 6vw, 90px);
    font-weight: normal;
    line-height: 1;
    letter-spacing: -3px;
    margin-bottom: 25px;
}

footer p {
    font-size: 12px;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #888;
    margin-bottom: 50px;
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

/* ENQUIRY & VIDEO MODAL */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.88);
    backdrop-filter: blur(8px);
    z-index: 9999;
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
    font-size: 24px;
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
    border: 1px solid rgba(255, 255, 255, 0.1);
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

.services-checkboxes {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 8px;
}

.checkbox-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #ccc;
    cursor: pointer;
}

.checkbox-item input {
    accent-color: #c4a472;
    cursor: pointer;
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
    z-index: 10000;
    display: flex;
    align-items: center;
    gap: 12px;
}

/* FILMS GRID STYLES */
.films-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 40px;
    margin-top: 60px;
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

.film-thumbnail-wrapper img,
.film-thumbnail-wrapper video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.85;
    transition: opacity 0.3s, transform 0.6s ease;
}

.film-card:hover .film-thumbnail-wrapper img,
.film-card:hover .film-thumbnail-wrapper video {
    opacity: 1;
    transform: scale(1.03);
}

.film-play-btn {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 50px;
    height: 50px;
    background: rgba(0, 0, 0, 0.65);
    border: 1px solid rgba(255, 255, 255, 0.6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 16px;
    transition: 0.3s;
}

.film-card:hover .film-play-btn {
    background: #c4a472;
    border-color: #c4a472;
    color: #111;
    transform: translate(-50%, -50%) scale(1.1);
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

/* TESTIMONIALS */
.testimonials-section {
    background: #0f0f0f;
    color: #fff;
    padding: 160px 10%;
    text-align: center;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
}

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 40px;
    margin-top: 60px;
    text-align: left;
}

.testimonial-card {
    background: #141414;
    border: 1px solid rgba(255, 255, 255, 0.08);
    padding: 40px;
    position: relative;
    transition: transform 0.3s ease, border-color 0.3s ease;
}

.testimonial-card:hover {
    border-color: rgba(196, 164, 114, 0.4);
    transform: translateY(-4px);
}

.testimonial-stars {
    color: #c4a472;
    font-size: 16px;
    margin-bottom: 20px;
    letter-spacing: 3px;
}

.testimonial-quote {
    font-size: 15px;
    line-height: 1.8;
    color: #d0ccc8;
    margin-bottom: 30px;
    font-style: italic;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 15px;
}

.testimonial-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #c4a472;
}

.testimonial-name {
    font-family: Georgia, serif;
    font-size: 17px;
    color: #fff;
    margin-bottom: 3px;
}

.testimonial-loc {
    font-size: 10px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #888;
}

    </style>

</head>

<body>

    @yield('content')

    @stack('scripts')

</body>

</html>
