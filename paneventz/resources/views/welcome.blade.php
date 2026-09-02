<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Paneventz — Wedding Photography & Films</title>

    <meta name="description"
          content="Paneventz creates cinematic wedding photography and films for unforgettable celebrations.">

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

    </style>

</head>

<body>

    <nav>

        <div class="logo">
            Paneventz
        </div>

        <div class="nav-links">

            <a href="#stories">Stories</a>

            <a href="#about">About</a>

            <a href="#films">Films</a>

            <a href="#contact" class="enquire">
                Enquire
            </a>

        </div>

    </nav>


    <section class="hero">

        <div class="hero-content">

            <div class="eyebrow">
                Wedding Photography & Films
            </div>

            <h1>
                Paneventz
            </h1>

            <p class="hero-description">
                We create timeless photographs and cinematic films
                for couples who want their wedding story to live
                far beyond the day itself.
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
            The Paneventz Approach
        </div>

        <h2>
            Your story deserves
            more than a photograph.
        </h2>

        <p>
            We preserve the atmosphere, emotion and little moments
            that make your celebration uniquely yours.
            From intimate ceremonies to grand destination weddings,
            every frame is created with intention.
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


    <div class="story story-large">

        <div class="story-image">
           <img src="/images/1.png" alt="Wedding photography by Paneventz">
        </div>

        <div class="story-info">

            <span class="story-number">01</span>

            <h3>Rahul & Priya</h3>

            <span class="story-location">
                Udaipur · Rajasthan
            </span>

            <a href="#" class="story-link">
                View Story →
            </a>

        </div>

    </div>


    <div class="story story-reverse">

        <div class="story-image">
            <img src="/images/2.png" alt="Indian wedding photography by Paneventz">
        </div>

        <div class="story-info">

            <span class="story-number">02</span>

            <h3>Arjun & Meera</h3>

            <span class="story-location">
                Goa · India
            </span>

            <a href="#" class="story-link">
                View Story →
            </a>

        </div>

    </div>


    <div class="story story-large">

        <div class="story-image">
            <img src="/images/3.png" alt="Luxury wedding photography by Paneventz">
        </div>

        <div class="story-info">

            <span class="story-number">03</span>

            <h3>Kabir & Ananya</h3>

            <span class="story-location">
                Mumbai · India
            </span>

            <a href="#" class="story-link">
                View Story →
            </a>

        </div>

    </div>

</section>


    <footer id="contact">

        <h2>
            Let's tell your story.
        </h2>

        <p>
            Wedding Photography · Cinematography · Films
        </p>

        <a href="#" class="footer-button">
            Start A Conversation
        </a>

    </footer>


</body>
</html>