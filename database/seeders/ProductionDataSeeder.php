<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductionDataSeeder extends Seeder
{
    public function run(): void
    {
        // Seed users
        DB::table('users')->insertOrIgnore(array (
  0 => 
  array (
    'id' => 1,
    'name' => 'Paneventz',
    'email' => 'imaliinmirza@gmail.com',
    'email_verified_at' => NULL,
    'password' => '$2y$12$WPsDvETbhK2cRWYLiNYkauI7rFQwelktDLvwcBm0laWUQTdDZqb2e',
    'remember_token' => 'DJW176Ky1SP8sPcqnaVOQqzGbDIRvUGttizp7eBq02p7XIutgP2MCmzZNHn3',
    'created_at' => '2026-08-26 17:33:07',
    'updated_at' => '2026-08-26 17:33:07',
  ),
));

        // Seed website_settings
        DB::table('website_settings')->insertOrIgnore(array (
  0 => 
  array (
    'id' => 1,
    'studio_name' => 'Paneventz',
    'tagline' => 'Luxury Wedding Photography & Films',
    'email' => 'imaliinmirza@gmail.com',
    'phone' => '+91 8082024787, +91 9821337523',
    'whatsapp' => '+91 8082024787',
    'instagram_url' => 'https://instagram.com/paneventz',
    'facebook_url' => NULL,
    'youtube_url' => NULL,
    'hero_eyebrow' => 'Wedding Photography & Films',
    'hero_heading' => 'Paneventz',
    'hero_description' => 'We create timeless photographs and cinematic films for couples who want their wedding story to live far beyond the day itself.',
    'hero_button_label' => 'Explore Our Stories',
    'hero_button_url' => '#stories',
    'about_eyebrow' => 'THE PAN EVENTZ APPROACH',
    'about_heading' => 'Your wedding deserves more than photographs.',
    'about_description' => 'It deserves to be remembered exactly as it felt.',
    'meta_title' => NULL,
    'meta_description' => NULL,
    'created_at' => '2026-08-29 17:21:48',
    'updated_at' => '2026-09-02 11:37:34',
    'logo' => NULL,
    'favicon' => NULL,
    'hero_background_image' => NULL,
    'hero_background_video' => NULL,
    'analytics_code' => NULL,
    'brochure_pdf' => NULL,
    'google_drive_api_key' => NULL,
    'footer_heading' => 'Let\'s create something timeless.',
    'footer_description' => 'Now reserving select dates for wedding celebrations across India and destinations worldwide.',
    'footer_address' => 'Mumbai · Available Pan-India & Worldwide',
    'footer_copyright' => '© 2026 Paneventz Studio. Handcrafted for unforgettable love stories.',
    'color_grade_heading' => 'Raw Capture vs. Signature Grade',
    'color_grade_description' => 'Every celebration is masterfully developed with custom color matrices, delicate highlight roll-offs, and authentic 35mm film emulation that stands the test of time.',
    'color_grade_before_image' => NULL,
    'color_grade_after_image' => 'settings/signature-color-grade.jpg',
    'stat_1_number' => 250,
    'stat_1_suffix' => '+',
    'stat_1_label' => 'Weddings & Marriages Documented',
    'stat_2_number' => 10,
    'stat_2_suffix' => '+',
    'stat_2_label' => 'Years of Artistic Experience',
    'stat_3_number' => 35,
    'stat_3_suffix' => '+',
    'stat_3_label' => 'Royal Palaces & Destinations',
    'stat_4_number' => 100,
    'stat_4_suffix' => '%',
    'stat_4_label' => 'Client Trust & Handcrafted Heirlooms',
  ),
));

        // Seed stories
        DB::table('stories')->insertOrIgnore(array (
  0 => 
  array (
    'id' => 1,
    'created_at' => '2026-08-26 12:10:58',
    'updated_at' => '2026-09-02 11:09:46',
    'couple_name' => 'test 1',
    'location' => 'mumbai',
    'cover_image' => 'stories/01M0Z2AVPQQK84997EQRYNWS5M.jpg',
    'description' => 'testing i am doing',
    'sort_order' => 1,
    'is_published' => 1,
    'gallery' => NULL,
  ),
  1 => 
  array (
    'id' => 2,
    'created_at' => '2026-08-26 12:56:18',
    'updated_at' => '2026-09-02 11:09:46',
    'couple_name' => '2',
    'location' => 'mumbai',
    'cover_image' => 'stories/01M0Z293DVNKPPBQVN248FV8NX.jpg',
    'description' => 'test 2',
    'sort_order' => 2,
    'is_published' => 1,
    'gallery' => NULL,
  ),
  2 => 
  array (
    'id' => 5,
    'created_at' => '2026-08-26 14:10:06',
    'updated_at' => '2026-08-26 14:10:18',
    'couple_name' => 'video 2',
    'location' => 'mumbai',
    'cover_image' => 'stories/01M0Z6GM3AN0VKYEA3QGKZ9T4T.mp4',
    'description' => 'video 3',
    'sort_order' => 4,
    'is_published' => 1,
    'gallery' => NULL,
  ),
  3 => 
  array (
    'id' => 7,
    'created_at' => '2026-08-26 14:34:22',
    'updated_at' => '2026-08-26 14:36:43',
    'couple_name' => 'video 4',
    'location' => 'mumbai',
    'cover_image' => 'stories/01M0Z7WNQJ3VQ4SF6ZZ5TQD8KK.mp4',
    'description' => 'mum',
    'sort_order' => 6,
    'is_published' => 1,
    'gallery' => NULL,
  ),
  4 => 
  array (
    'id' => 8,
    'created_at' => '2026-09-02 10:44:13',
    'updated_at' => '2026-09-02 10:44:13',
    'couple_name' => 'Aditi & Kabir Test',
    'location' => 'Udaipur, Rajasthan',
    'cover_image' => NULL,
    'description' => 'Royal celebration at Jagmandir Island Palace.',
    'sort_order' => 0,
    'is_published' => 1,
    'gallery' => NULL,
  ),
));

        // Seed films
        DB::table('films')->insertOrIgnore(array (
  0 => 
  array (
    'id' => 1,
    'title' => 'A Royal Symphony in Udaipur',
    'couple_name' => 'Aditi & Kabir',
    'location' => 'Udaipur, Rajasthan',
    'wedding_date' => '2025-12-10 00:00:00',
    'thumbnail' => NULL,
    'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
    'description' => 'A three-day celebration of heritage, music, and eternal promises captured in royal frames.',
    'is_featured' => 1,
    'is_published' => 1,
    'sort_order' => 1,
    'created_at' => '2026-08-29 18:14:26',
    'updated_at' => '2026-08-29 18:14:26',
  ),
));

        // Seed services
        DB::table('services')->insertOrIgnore(array (
  0 => 
  array (
    'id' => 1,
    'name' => 'Signature Wedding Photography',
    'short_description' => 'Comprehensive full-day candid and traditional wedding coverage.',
    'description' => 'Includes 2 lead photographers, candid & traditional portraiture, 600+ retouched high-resolution images, online private gallery, and a custom luxury album.',
    'price_from' => 150000,
    'is_published' => 1,
    'sort_order' => 1,
    'created_at' => '2026-08-29 17:21:06',
    'updated_at' => '2026-08-29 17:21:06',
  ),
  1 => 
  array (
    'id' => 2,
    'name' => 'Cinematic Wedding Films',
    'short_description' => 'Editorial 4K wedding cinema crafted with emotion and timeless music.',
    'description' => 'Includes 1 cinematic teaser (3-5 minutes), 1 documentary feature film (30-45 minutes), drone aerial cinematography, and color grading.',
    'price_from' => 200000,
    'is_published' => 1,
    'sort_order' => 2,
    'created_at' => '2026-08-29 17:21:06',
    'updated_at' => '2026-08-29 17:21:06',
  ),
  2 => 
  array (
    'id' => 3,
    'name' => 'The Royal Collection (Photo + Cinema)',
    'short_description' => 'All-inclusive photo and video coverage for multi-day destination celebrations.',
    'description' => 'Full photography & cinematography team covering Mehndi, Sangeet, Wedding, and Reception with same-day edits and aerial drone footage.',
    'price_from' => 350000,
    'is_published' => 1,
    'sort_order' => 3,
    'created_at' => '2026-08-29 17:21:06',
    'updated_at' => '2026-08-29 17:21:06',
  ),
));

        // Seed testimonials
        DB::table('testimonials')->insertOrIgnore(array (
  0 => 
  array (
    'id' => 1,
    'couple_name' => 'Priya & Rohan',
    'location' => 'The Leela Palace, Udaipur',
    'rating' => 5,
    'review' => 'Paneventz captured our celebration beyond what we ever dreamed. Looking back at our wedding films and photographs brings every tear, laugh, and emotion right back.',
    'photo' => NULL,
    'is_published' => 1,
    'sort_order' => 1,
    'created_at' => '2026-08-29 18:14:22',
    'updated_at' => '2026-08-29 18:14:22',
  ),
  1 => 
  array (
    'id' => 2,
    'couple_name' => 'Ananya & Kabir',
    'location' => 'Taj Falaknuma Palace, Hyderabad',
    'rating' => 5,
    'review' => 'The wedding film they created felt like watching a luxury cinematic movie. Truly discreet, editorial, and breathtakingly beautiful.',
    'photo' => NULL,
    'is_published' => 1,
    'sort_order' => 2,
    'created_at' => '2026-08-29 18:14:22',
    'updated_at' => '2026-08-29 18:14:22',
  ),
));

        // Seed faqs
        DB::table('faqs')->insertOrIgnore(array (
  0 => 
  array (
    'id' => 1,
    'question' => 'Do you travel for destination weddings across India and internationally?',
    'answer' => 'Yes, absolutely! We regularly travel for destination weddings across Udaipur, Jaipur, Goa, Kerala, and internationally (Italy, Thailand, Dubai). Our travel and stay logistics are seamlessly handled as part of custom destination collections.',
    'is_published' => 1,
    'sort_order' => 1,
    'created_at' => '2026-08-29 18:14:17',
    'updated_at' => '2026-08-29 18:14:17',
  ),
  1 => 
  array (
    'id' => 2,
    'question' => 'How long after our wedding celebration do we receive our films and photos?',
    'answer' => 'We deliver a curated sneak peek gallery within 7-10 days of your celebration. The complete high-resolution gallery is delivered within 4-6 weeks, followed by your cinematic teaser and documentary wedding film within 8-10 weeks.',
    'is_published' => 1,
    'sort_order' => 2,
    'created_at' => '2026-08-29 18:14:17',
    'updated_at' => '2026-08-29 18:14:17',
  ),
  2 => 
  array (
    'id' => 3,
    'question' => 'What is your filming and photography aesthetic?',
    'answer' => 'Our style is editorial, atmospheric, and emotive. We believe in capturing real, unscripted moments as they naturally unfold, paired with subtle, flattering guidance for your portraits so you always look effortless.',
    'is_published' => 1,
    'sort_order' => 3,
    'created_at' => '2026-08-29 18:14:17',
    'updated_at' => '2026-08-29 18:14:17',
  ),
  3 => 
  array (
    'id' => 4,
    'question' => 'How do we secure our date with Paneventz?',
    'answer' => 'To maintain our high artistic standard, we take on a strictly limited number of weddings each season. Dates are confirmed upon signing our booking agreement and receiving an initial reservation retainer.',
    'is_published' => 1,
    'sort_order' => 4,
    'created_at' => '2026-08-29 18:14:17',
    'updated_at' => '2026-08-29 18:14:17',
  ),
));

        // Seed terms_and_conditions
        DB::table('terms_and_conditions')->insertOrIgnore(array (
  0 => 
  array (
    'id' => 1,
    'version' => 1,
    'advance_percentage' => 50,
    'balance_percentage' => 50,
    'balance_due' => 'Event Date',
    'advance_refundable' => 0,
    'cancellation_policy' => 'The 50% advance payment is non-refundable if the client cancels the wedding/event after the booking has been confirmed. The advance payment is used to reserve the date and cover planning and scheduling resources. Any date change is strictly subject to team availability and mutual written agreement.',
    'estimated_delivery_period' => '1–2 months',
    'delivery_policy' => 'Final edited wedding photographs and cinematic films normally take 1 to 2 months following the event date. Delivery timelines may vary depending on the size of the celebration, total volume of media captured, bespoke editing requirements, and timely client selections.',
    'extra_pendrive' => 'Chargeable',
    'extended_coverage_after' => '12:30 AM',
    'late_night_transportation' => 'Chargeable',
    'hotel_coverage' => 'Additional',
    'home_coverage' => 'Additional',
    'extra_hours' => 'Chargeable',
    'content' => '1. BOOKING & PAYMENT
• Advance Payment: A 50% advance payment is required to confirm and secure your booking.
• Balance Payment: The remaining 50% balance payment is due on the event/wedding date.
• Confirmation: The booking and event date reservation are officially confirmed only upon receipt of the advance payment.

2. CANCELLATION & REFUND
• Non-Refundable Advance: The 50% advance payment is strictly non-refundable should the client cancel the wedding or event after booking confirmation.
• Reservation of Date: The advance payment guarantees reservation of the date and covers all pre-production planning and scheduling.
• Date Rescheduling: Any change of date is subject to calendar availability and mutual written agreement.

3. PHOTO & VIDEO DELIVERY
• Delivery Timeline: Final edited photographs and cinematic wedding films are typically delivered within 1 to 2 months after the event date.
• Variation Factors: Delivery schedules may vary based on event scale, total quantity of photos/videos, artistic post-production requirements, and client selection turnaround.

4. PENDRIVE / PHYSICAL STORAGE COPIES
• Package Inclusions: The pendrive/USB drive quantity specified in the agreed package will be provided.
• Additional Units: Any additional pendrives or physical storage media requested by the client are chargeable separately.
• Extra Physical Deliverables: Extra albums, photo prints, or bespoke packaging requests will incur additional fees.

5. EXTENDED & LATE-NIGHT COVERAGE
• Package Hours: Standard coverage will strictly adhere to the agreed schedule and package duration.
• Late-Night Threshold: If event coverage extends beyond 12:30 AM, additional hourly charges will apply.
• Transportation: If regular public transportation is unavailable due to late-night conclusion and the crew requires alternate transport, applicable transportation expenses will be payable by the client.

6. HOTEL & RESIDENCE COVERAGE
• Outside Agreed Venue: Photography and videography at hotels, private residences, or auxiliary locations outside the agreed primary venue schedule may incur additional charges.
• Travel & Accommodation: Any additional travel, parking fees, entry permits, or accommodation expenses outside the contracted scope are payable by the client.

7. ADDITIONAL HOURS & BESPOKE SERVICES
• Overtime Hours: Any shooting hours beyond the contracted package duration are chargeable at standard overtime rates.
• Supplementary Services: Extra crew members (photographers, cinematographers, drone pilots), additional albums, raw footage drives, or specialized equipment not included in the original quotation are chargeable separately.

8. FINAL AGREEMENT & ACCEPTANCE
• Scope of Work: All deliverables and services are provided in accordance with the package and quotation agreed upon at booking.
• Scope Alterations: Any subsequent changes or additions requested after confirmation may result in revised quotations and charges.
• Confirmation: Payment of the booking advance constitutes client acknowledgment, acceptance, and agreement to all terms and conditions outlined herein.',
    'created_at' => '2026-08-31 06:22:57',
    'updated_at' => '2026-08-31 06:22:57',
  ),
));

        // Seed locations
        DB::table('locations')->insertOrIgnore(array (
  0 => 
  array (
    'id' => 1,
    'name' => 'Mumbai',
    'slug' => 'mumbai',
    'state' => 'Maharashtra',
    'country' => 'India',
    'headline' => 'Luxury Wedding Photography in Mumbai & Alibaug',
    'intro' => 'Capturing opulent celebrations, sea-facing pheras, and intimate soirees across Mumbai’s most storied heritage hotels and contemporary seaside venues.',
    'content' => NULL,
    'hero_image' => NULL,
    'popular_venues' => '["The Taj Mahal Palace (Colaba)","JW Marriott Sahar","The St. Regis Mumbai","Taj Lands End","Sofitel BKC"]',
    'faqs' => '[{"q":"Do you document destination weddings in Alibaug and Lonavala?","a":"Yes, our team frequently documents luxury destination celebrations across Alibaug, Lonavala, Khandala, and Karjat with full crew mobilization."},{"q":"How far in advance should we reserve our Mumbai wedding dates?","a":"Peak Mumbai wedding season (November through February) fills up quickly. We recommend reserving 6 to 12 months in advance."}]',
    'is_published' => 1,
    'sort_order' => 1,
    'created_at' => '2026-09-02 05:30:19',
    'updated_at' => '2026-09-02 05:30:19',
  ),
  1 => 
  array (
    'id' => 2,
    'name' => 'Udaipur',
    'slug' => 'udaipur',
    'state' => 'Rajasthan',
    'country' => 'India',
    'headline' => 'Royal Palace Wedding Photography in Udaipur',
    'intro' => 'The City of Lakes provides a fairytale stage of marble courtyards, glistening waters, and royal Mewar grandeur. Our cinema and photography immortalize every opulent ceremony.',
    'content' => NULL,
    'hero_image' => NULL,
    'popular_venues' => '["The Oberoi Udaivilas","Taj Lake Palace","The Leela Palace Udaipur","Jagmandir Island Palace","Raffles Udaipur","Fateh Prakash Palace"]',
    'faqs' => '[{"q":"How do you handle evening palace lighting in Udaipur?","a":"We utilize ultra-fast prime cine lenses and discreet wireless continuous lighting to honor the natural palace candle-glow without washing out historic architecture."},{"q":"Are drone permits included for lake palaces?","a":"We coordinate all licensed 4K aerial drone operations in compliance with local Udaipur administration and heritage site guidelines."}]',
    'is_published' => 1,
    'sort_order' => 2,
    'created_at' => '2026-09-02 05:30:19',
    'updated_at' => '2026-09-02 05:30:19',
  ),
  2 => 
  array (
    'id' => 3,
    'name' => 'Goa',
    'slug' => 'goa',
    'state' => 'Goa',
    'country' => 'India',
    'headline' => 'Bespoke Coastal & Beach Wedding Photography in Goa',
    'intro' => 'Sun-drenched coastal ceremonies, golden hour sunset vows, and barefoot beach revelry captured with high-fashion editorial aesthetics.',
    'content' => NULL,
    'hero_image' => NULL,
    'popular_venues' => '["W Goa (Vagator)","The St. Regis Goa Resort","Taj Exotica Resort & Spa","Alila Diwa Goa","Grand Hyatt Goa (Bambolim)"]',
    'faqs' => '[{"q":"How do you protect equipment against tropical beach humidity?","a":"Our professional camera bodies and lenses are weather-sealed, and our crew carries dedicated climate-controlled gear for uninterrupted beachside capture."},{"q":"Do you capture pre-wedding sunset portraits in Goa?","a":"Yes, all destination Goa commissions include dedicated sunset couple portraits across secluded cliffs and heritage Latin quarters."}]',
    'is_published' => 1,
    'sort_order' => 3,
    'created_at' => '2026-09-02 05:30:19',
    'updated_at' => '2026-09-02 05:30:19',
  ),
  3 => 
  array (
    'id' => 4,
    'name' => 'Jaipur',
    'slug' => 'jaipur',
    'state' => 'Rajasthan',
    'country' => 'India',
    'headline' => 'Grand Heritage Wedding Photography in Jaipur',
    'intro' => 'Magnificent forts, royal jharokhas, and vibrant royal celebrations documented with rich cinematic contrast and heirloom color grading.',
    'content' => NULL,
    'hero_image' => NULL,
    'popular_venues' => '["Rambagh Palace","Jai Mahal Palace","Fairmont Jaipur","Samode Palace","The Leela Palace Jaipur","JW Marriott Jaipur"]',
    'faqs' => '[{"q":"Can your team document multi-day royal events across multiple palace venues?","a":"Yes, we provide multi-camera photo and cinema crews with dedicated coordinators for seamless coverage across multiple venues."}]',
    'is_published' => 1,
    'sort_order' => 4,
    'created_at' => '2026-09-02 05:30:19',
    'updated_at' => '2026-09-02 05:30:19',
  ),
));

        // Seed wedding_albums
        DB::table('wedding_albums')->insertOrIgnore(array (
  0 => 
  array (
    'id' => 1,
    'title' => 'Aditi & Kabir — Royal Udaipur Wedding',
    'slug' => 'aditi-kabir-2026',
    'couple_names' => 'Aditi & Kabir',
    'cover_image' => 'stories/01M0Z2AVPQQK84997EQRYNWS5M.png',
    'event_date' => '2026-11-20 00:00:00',
    'location' => 'Jagmandir Island Palace, Udaipur',
    'pin_code' => '2026',
    'google_drive_folder_id' => NULL,
    'is_public' => 0,
    'allow_downloads' => 1,
    'created_at' => '2026-08-29 18:42:01',
    'updated_at' => '2026-08-30 11:00:41',
    'enable_face_ai' => 1,
    'guest_google_drive_folder_id' => NULL,
  ),
  1 => 
  array (
    'id' => 2,
    'title' => 'Riya & Rohan Test Wedding',
    'slug' => 'riya-rohan-test',
    'couple_names' => 'Riya & Rohan',
    'cover_image' => NULL,
    'event_date' => NULL,
    'location' => 'Goa',
    'pin_code' => NULL,
    'google_drive_folder_id' => NULL,
    'is_public' => 1,
    'allow_downloads' => 1,
    'created_at' => '2026-09-02 10:44:13',
    'updated_at' => '2026-09-02 10:44:13',
    'enable_face_ai' => 1,
    'guest_google_drive_folder_id' => NULL,
  ),
));

        // Seed blog_posts
        DB::table('blog_posts')->insertOrIgnore(array (
  0 => 
  array (
    'id' => 1,
    'title' => 'The Ultimate Guide to Planning a Royal Palace Wedding in Udaipur',
    'slug' => 'ultimate-guide-royal-palace-wedding-udaipur',
    'category' => 'Destination Weddings',
    'excerpt' => 'From iconic island venues like Jagmandir to golden hour lighting on Lake Pichola, discover insider secrets for documenting a regal celebration.',
    'content' => 'A wedding in Udaipur is unlike any celebration on earth. The shimmering reflection of ancient marble against Lake Pichola, the regal courtyard arches of the City Palace, and the gentle arrival of heritage boat processions create a cinematic canvas that demands intention and mastery.

### 1. Timing the Pheras with Pichola Golden Hour
Because Udaipur palaces face west across the lake, the 45 minutes preceding sunset provide an unrepeatable golden illumination. We always recommend coordinating your mandap alignment to capture this warm, natural back-light.

### 2. Discreet Sound & Cine Capture
Palace courtyards have unique acoustic acoustics. Our cinema team deploys wireless low-profile lapels on the couple and priest to preserve sacred Vedic chants with studio clarity while remaining completely unobtrusive.

### 3. Preserving Heirlooms for Generations
Your wedding photographs are the only investment from your day that increases in value over time. Treat your celebration with the heirloom documentation it deserves.',
    'featured_image' => NULL,
    'author_name' => 'Paneventz Editorial',
    'read_time_minutes' => 6,
    'is_published' => 1,
    'published_at' => '2026-08-31 05:30:19',
    'created_at' => '2026-09-02 05:30:19',
    'updated_at' => '2026-09-02 05:30:19',
    'status' => 'draft',
    'focus_keyword' => NULL,
    'secondary_keywords' => NULL,
    'ai_generation_meta' => NULL,
    'quality_score' => NULL,
    'quality_warnings' => NULL,
    'source_story_id' => NULL,
    'source_wedding_album_id' => NULL,
  ),
  1 => 
  array (
    'id' => 2,
    'title' => 'Couture Color Science: Why Authentic Indian Skin Tones Require Bespoke Film Grading',
    'slug' => 'couture-color-science-indian-wedding-skin-tones',
    'category' => 'Cinematic Films',
    'excerpt' => 'How Paneventz creates rich 35mm film emulation that honors authentic Indian skin tones and royal zardozi fabrics.',
    'content' => 'Standard camera sensor color profiles are engineered for Western lighting and often turn vibrant turmeric haldi tones into harsh neon yellows and rich royal velvet lehengas into oversaturated blocks.

At Paneventz, we built our own proprietary color transformation curves inspired by Kodak and Fujifilm 35mm motion picture negative stocks.

### The Paneventz Film Pipeline
1. True-to-life skin tones with velvety shadow rolloff
2. Authentic preservation of bridal reds, rani pinks, and gold embroidery
3. Archival grade color longevity that never looks dated

Experience the difference of artisan film grading on your wedding day.',
    'featured_image' => NULL,
    'author_name' => 'Paneventz Color Suite',
    'read_time_minutes' => 5,
    'is_published' => 1,
    'published_at' => '2026-08-28 05:30:19',
    'created_at' => '2026-09-02 05:30:19',
    'updated_at' => '2026-09-02 05:30:19',
    'status' => 'draft',
    'focus_keyword' => NULL,
    'secondary_keywords' => NULL,
    'ai_generation_meta' => NULL,
    'quality_score' => NULL,
    'quality_warnings' => NULL,
    'source_story_id' => NULL,
    'source_wedding_album_id' => NULL,
  ),
));

        // Seed url_redirects
        DB::table('url_redirects')->insertOrIgnore(array (
  0 => 
  array (
    'id' => 1,
    'source_path' => '/gallery/old-slug-123',
    'target_path' => '/gallery/new-slug-456',
    'status_code' => 301,
    'hits' => 3,
    'last_accessed_at' => '2026-09-02 05:30:40',
    'created_at' => '2026-09-02 05:28:35',
    'updated_at' => '2026-09-02 05:30:41',
  ),
));


    }
}