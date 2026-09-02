<?php

namespace App\Filament\Resources\WebsiteSettings;

use App\Filament\Resources\WebsiteSettings\Pages\CreateWebsiteSetting;
use App\Filament\Resources\WebsiteSettings\Pages\EditWebsiteSetting;
use App\Filament\Resources\WebsiteSettings\Pages\ListWebsiteSettings;
use App\Models\WebsiteSetting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class WebsiteSettingResource extends Resource
{
    protected static ?string $model = WebsiteSetting::class;
    protected static string|UnitEnum|null $navigationGroup = 'Website Content';
    protected static ?string $navigationLabel = 'Website Settings';
    protected static ?string $recordTitleAttribute = 'studio_name';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Studio Identity & Branding')
                ->description('Brand name, studio tagline, and visual assets used across headers and browser tabs.')
                ->icon('heroicon-o-identification')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make('studio_name')
                        ->label('Official Studio Name')
                        ->required()
                        ->placeholder('Paneventz')
                        ->helperText('Primary business identifier used in navigation, page meta titles, and notifications.')
                        ->columnSpan(1),

                    TextInput::make('tagline')
                        ->label('Studio Tagline')
                        ->placeholder('Luxury Wedding Photography & Cinematic Films')
                        ->helperText('Succinct discipline description displayed beneath the brand title.')
                        ->columnSpan(1),

                    FileUpload::make('logo')
                        ->label('Navigation Logo (PNG / SVG / WebP)')
                        ->image()
                        ->disk('public')
                        ->directory('branding')
                        ->visibility('public')
                        ->helperText('Transparent brandmark displayed in the top header. Text fallback is used if omitted.')
                        ->columnSpan(1),

                    FileUpload::make('favicon')
                        ->label('Browser Favicon Icon')
                        ->image()
                        ->disk('public')
                        ->directory('branding')
                        ->visibility('public')
                        ->helperText('Small icon rendered within browser tab titles and mobile home screen shortcuts.')
                        ->columnSpan(1),
                ]),

            Section::make('Hero Banner (Top of Homepage)')
                ->description('Manage typography, video loops, and imagery for the primary above-the-fold landing experience.')
                ->icon('heroicon-o-sparkles')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make('hero_eyebrow')
                        ->label('Category Eyebrow Tag')
                        ->placeholder('WEDDING PHOTOGRAPHY & FILMS')
                        ->helperText('Refined gold uppercase sub-header positioned directly above the studio title.')
                        ->columnSpan(1),

                    TextInput::make('hero_heading')
                        ->label('Primary Hero Headline')
                        ->placeholder('Paneventz')
                        ->helperText('Main display title rendered in large serif typography across the hero viewport.')
                        ->columnSpan(1),

                    Textarea::make('hero_description')
                        ->label('Introduction Narrative')
                        ->placeholder('We create timeless photographs and cinematic films for couples who want their wedding story to live far beyond the day itself.')
                        ->helperText('Core brand thesis and introductory overview presented beneath the hero title.')
                        ->rows(2)
                        ->columnSpanFull(),

                    TextInput::make('hero_button_label')
                        ->label('Primary Action Button Label')
                        ->placeholder('Explore Our Stories')
                        ->helperText('Display label for the primary hero action button.')
                        ->columnSpan(1),

                    TextInput::make('hero_button_url')
                        ->label('Action Button Target Link')
                        ->placeholder('#stories')
                        ->helperText('Anchor fragment or internal URL route triggered upon button click (e.g. #stories or /services).')
                        ->columnSpan(1),

                    FileUpload::make('hero_background_image')
                        ->label('Hero Background Cover Photo')
                        ->image()
                        ->disk('public')
                        ->directory('branding')
                        ->visibility('public')
                        ->helperText('Full-bleed landscape photograph displayed when no video background is uploaded.')
                        ->columnSpan(1),

                    FileUpload::make('hero_background_video')
                        ->label('Hero Cinema Video Loop (MP4 / WebM)')
                        ->acceptedFileTypes(['video/mp4', 'video/webm'])
                        ->disk('public')
                        ->directory('branding')
                        ->visibility('public')
                        ->helperText('Continuous muted video loop rendered behind the hero title for high visual impact.')
                        ->columnSpan(1),
                ]),

            Section::make('Color Grading & Post-Production Showcase')
                ->description('Customize the interactive Before & After slider displaying your RAW vs. Artisan Film Grade comparison.')
                ->icon('heroicon-o-paint-brush')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make('color_grade_heading')
                        ->label('Showcase Headline')
                        ->placeholder('Raw Capture vs. Signature Grade')
                        ->helperText('Main title for the interactive color grading split-screen showcase.')
                        ->columnSpan(1),

                    Textarea::make('color_grade_description')
                        ->label('Showcase Narrative')
                        ->placeholder('Drag the slider to experience how our colorists transform flat camera sensor footage into rich, velvety, heirloom-toned wedding cinema.')
                        ->helperText('Brief statement explaining your bespoke film grading process.')
                        ->rows(2)
                        ->columnSpan(1),

                    FileUpload::make('color_grade_before_image')
                        ->label('Before Image (Unedited / Camera RAW)')
                        ->image()
                        ->disk('public')
                        ->directory('branding')
                        ->visibility('public')
                        ->helperText('The un-graded / flat RAW wedding photo. If empty, a curated editorial showcase image is used.')
                        ->columnSpan(1),

                    FileUpload::make('color_grade_after_image')
                        ->label('After Image (Paneventz Signature Grade)')
                        ->image()
                        ->disk('public')
                        ->directory('branding')
                        ->visibility('public')
                        ->helperText('The final color-graded, retouched wedding masterpiece.')
                        ->columnSpan(1),
                ]),

            Section::make('Studio Milestones & Animated Counter')
                ->description('Showcase your years of experience, weddings documented, and accomplishments with rolling animated numbers on the homepage.')
                ->icon('heroicon-o-chart-bar')
                ->columnSpanFull()
                ->columns(3)
                ->schema([
                    TextInput::make('stat_1_number')
                        ->label('Stat 1 Target Number')
                        ->numeric()
                        ->placeholder('250')
                        ->helperText('Number to count up to (e.g. 250)'),
                    TextInput::make('stat_1_suffix')
                        ->label('Stat 1 Suffix')
                        ->placeholder('+')
                        ->helperText('e.g. +'),
                    TextInput::make('stat_1_label')
                        ->label('Stat 1 Title / Description')
                        ->placeholder('Weddings & Celebrations Documented'),

                    TextInput::make('stat_2_number')
                        ->label('Stat 2 Target Number (Experience)')
                        ->numeric()
                        ->placeholder('10')
                        ->helperText('e.g. 10 for 10 years experience'),
                    TextInput::make('stat_2_suffix')
                        ->label('Stat 2 Suffix')
                        ->placeholder('+')
                        ->helperText('e.g. +'),
                    TextInput::make('stat_2_label')
                        ->label('Stat 2 Title / Description')
                        ->placeholder('Years of Visual Legacy'),

                    TextInput::make('stat_3_number')
                        ->label('Stat 3 Target Number (Destinations)')
                        ->numeric()
                        ->placeholder('35'),
                    TextInput::make('stat_3_suffix')
                        ->label('Stat 3 Suffix')
                        ->placeholder('+'),
                    TextInput::make('stat_3_label')
                        ->label('Stat 3 Title / Description')
                        ->placeholder('Royal Palaces & Destinations'),

                    TextInput::make('stat_4_number')
                        ->label('Stat 4 Target Number')
                        ->numeric()
                        ->placeholder('100'),
                    TextInput::make('stat_4_suffix')
                        ->label('Stat 4 Suffix')
                        ->placeholder('%'),
                    TextInput::make('stat_4_label')
                        ->label('Stat 4 Title / Description')
                        ->placeholder('Artisan Color & Handcrafted Heirlooms'),
                ]),

            Section::make('Footer & Bottom Call-to-Action')
                ->description('Configure the call-to-action title, booking availability notice, and studio locations shown at the base of every page.')
                ->icon('heroicon-o-bars-3-bottom-left')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make('footer_heading')
                        ->label('Primary Footer Heading')
                        ->placeholder("Let's create something timeless.")
                        ->helperText('Prominent invitation headline rendered across the bottom of all website pages.')
                        ->columnSpanFull(),

                    Textarea::make('footer_description')
                        ->label('Availability & Booking Subtitle')
                        ->placeholder('Now reserving select dates for 2026 – 2027 wedding celebrations across India and destinations worldwide.')
                        ->helperText('Secondary statement providing context on season availability, destination readiness, and consultation requests.')
                        ->rows(2)
                        ->columnSpanFull(),

                    TextInput::make('footer_address')
                        ->label('Studio Cities & Destinations')
                        ->placeholder('Mumbai · Udaipur · Goa · Delhi NCR · Worldwide')
                        ->helperText('Geographic regions and operational hubs displayed with a location pin (📍) above contact buttons.')
                        ->columnSpan(1),

                    TextInput::make('footer_copyright')
                        ->label('Copyright Statement')
                        ->placeholder('© 2026 Paneventz Studio. Handcrafted for unforgettable celebrations.')
                        ->helperText('Official copyright attribution and ownership notice placed at the bottom bar.')
                        ->columnSpan(1),
                ]),

            Section::make('Direct Inquiries & Social Channels')
                ->description('Communication endpoints for real-time lead capture, phone inquiries, and portfolio channels.')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make('phone')
                        ->label('Telephone Line')
                        ->tel()
                        ->placeholder('+91 8082024787')
                        ->helperText('Direct voice line for client calls and telephone consultations.')
                        ->columnSpan(1),

                    TextInput::make('whatsapp')
                        ->label('WhatsApp Direct Link')
                        ->tel()
                        ->placeholder('+91 8082024787')
                        ->helperText('Direct chat destination powering the floating WhatsApp widget and quick-contact triggers. Include international country code.')
                        ->columnSpan(1),

                    TextInput::make('email')
                        ->label('Studio Correspondence Email')
                        ->email()
                        ->placeholder('hello@paneventz.com')
                        ->helperText('Primary address for client inquiry dispatches and administrative notifications.')
                        ->columnSpan(1),

                    TextInput::make('instagram_url')
                        ->label('Instagram Profile URL')
                        ->url()
                        ->placeholder('https://instagram.com/paneventz')
                        ->helperText('Official studio Instagram handle link for footer channels and gallery feeds.')
                        ->columnSpan(1),

                    TextInput::make('youtube_url')
                        ->label('YouTube Cinema Channel')
                        ->url()
                        ->placeholder('https://youtube.com/@paneventz')
                        ->helperText('Video hosting channel URL for cinematic films and long-format wedding stories.')
                        ->columnSpan(1),

                    TextInput::make('facebook_url')
                        ->label('Facebook Page URL')
                        ->url()
                        ->placeholder('https://facebook.com/paneventz')
                        ->helperText('Official Facebook business profile link.')
                        ->columnSpan(1),
                ]),

            Section::make('Studio Philosophy & Narrative')
                ->description('Artistic mission and narrative statement presented in the editorial introduction.')
                ->icon('heroicon-o-book-open')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make('about_eyebrow')
                        ->label('Philosophy Eyebrow Tag')
                        ->placeholder('THE PHILOSOPHY')
                        ->helperText('Gold category label positioned above the editorial narrative heading.')
                        ->columnSpan(1),

                    TextInput::make('about_heading')
                        ->label('Core Narrative Headline')
                        ->placeholder('We believe true luxury is subtle, emotional, and timeless.')
                        ->helperText('Signature philosophy quote summarizing the studio approach.')
                        ->columnSpan(1),

                    Textarea::make('about_description')
                        ->label('Extended Studio Narrative')
                        ->rows(4)
                        ->placeholder('Articulate your creative philosophy, discreet unobtrusive capture style, and dedication to heirloom craftsmanship.')
                        ->helperText('Comprehensive brand story describing your craft, bespoke service, and client relationship.')
                        ->columnSpanFull(),
                ]),

            Section::make('Publications, SEO & Analytics')
                ->description('Rate card downloads, search engine indexing directives, and tracking scripts.')
                ->icon('heroicon-o-document-chart-bar')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    FileUpload::make('brochure_pdf')
                        ->label('Rate Card / Investment Guide (PDF)')
                        ->acceptedFileTypes(['application/pdf'])
                        ->disk('public')
                        ->directory('brochures')
                        ->visibility('public')
                        ->helperText('Upload an updated PDF brochure. Automatically activates the gold "Brochure (PDF) ↓" download buttons on the public website.')
                        ->columnSpanFull(),

                    TextInput::make('meta_title')
                        ->label('SEO Meta Title')
                        ->placeholder('Paneventz — Luxury Wedding Photography & Cinematic Films')
                        ->helperText('Primary title tag indexed by Google search crawlers and rendered on WhatsApp card previews.')
                        ->columnSpanFull(),

                    Textarea::make('meta_description')
                        ->label('SEO Meta Description')
                        ->placeholder('Paneventz documents timeless luxury weddings and cinematic love stories across India and destinations worldwide.')
                        ->helperText('Summary snippet indexed by search algorithms and displayed in rich link embeds.')
                        ->rows(2)
                        ->columnSpanFull(),

                    Textarea::make('analytics_code')
                        ->label('Tracking & Analytics Embed (Google Analytics, Meta Pixel)')
                        ->placeholder('<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXX"></script>...')
                        ->helperText('Direct injection script block rendered in the HTML head for conversion tracking and web traffic metrics.')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('studio_name')->label('Studio')->searchable(),
            TextColumn::make('email')->searchable(),
            TextColumn::make('updated_at')->label('Last Updated')->dateTime()->sortable(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWebsiteSettings::route('/'),
            'create' => CreateWebsiteSetting::route('/create'),
            'edit' => EditWebsiteSetting::route('/{record}/edit'),
        ];
    }
}