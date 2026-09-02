<?php

namespace App\Filament\Pages;

use App\Models\BlogPost;
use App\Models\SeoMetadata;
use App\Models\Story;
use App\Models\WeddingAlbum;
use App\Services\Ai\AiManager;
use App\Services\Ai\BlogGenerator;
use App\Services\Ai\ContentQualityChecker;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;
use UnitEnum;

class AiBlogWriter extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static UnitEnum|string|null $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'AI Blog Writer';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'AI Editorial Blog & Story Generator';

    protected string $view = 'filament.pages.ai-blog-writer';

    // Form inputs
    public string $couple_name = '';
    public string $venue = '';
    public string $city = 'Mumbai';
    public string $wedding_type = 'Luxury Wedding Celebration';
    public string $event_date = '';
    public string $services = 'Wedding Photography + Cinematic Films';
    public string $photographer = 'Paneventz Lead Visual Artist';
    public string $videographer = 'Paneventz Cinema Crew';
    public string $custom_notes = '';
    public string $target_keyword = '';
    public ?int $source_story_id = null;
    public ?int $source_wedding_album_id = null;

    // Generation output state
    public bool $isGenerated = false;
    public string $generatedTitle = '';
    public string $generatedSlug = '';
    public string $generatedCategory = 'Wedding Photography';
    public string $generatedExcerpt = '';
    public string $generatedContent = '';
    public int $generatedReadTime = 4;
    public string $generatedFocusKeyword = '';
    public array $generatedSecondaryKeywords = [];
    public string $generatedSeoTitle = '';
    public string $generatedMetaDescription = '';
    public string $generatedOgTitle = '';
    public string $generatedOgDescription = '';
    public string $generatedAltText = '';
    public int $qualityScore = 100;
    public array $qualityWarnings = [];
    public string $aiProvider = '';
    public ?int $savedPostId = null;

    public function mount(): void
    {
        $this->aiProvider = AiManager::getActiveProvider();

        if (request()->has('story_id')) {
            $story = Story::find(request('story_id'));
            if ($story) {
                $this->source_story_id = $story->id;
                $this->couple_name = $story->couple_name;
                $this->venue = $story->location ?: 'Heritage Venue';
                $this->city = $story->location ?: 'Mumbai';
                $this->custom_notes = $story->description ?: '';
            }
        }

        if (request()->has('album_id')) {
            $album = WeddingAlbum::find(request('album_id'));
            if ($album) {
                $this->source_wedding_album_id = $album->id;
                $this->couple_name = $album->couple_names ?: $album->title;
                $this->venue = $album->location ?: 'Wedding Venue';
                $this->city = $album->location ?: 'Mumbai';
                if ($album->event_date) {
                    $this->event_date = $album->event_date->format('Y-m-d');
                }
            }
        }

        if (request()->has('venue')) {
            $this->venue = request('venue');
        }
        if (request()->has('city')) {
            $this->city = request('city');
        }
        if (request()->has('topic')) {
            $this->wedding_type = request('topic');
        }
    }

    public function generate(): void
    {
        $params = [
            'couple_name'    => $this->couple_name,
            'venue'          => $this->venue,
            'city'           => $this->city,
            'wedding_type'   => $this->wedding_type,
            'event_date'     => $this->event_date,
            'services'       => $this->services,
            'photographer'   => $this->photographer,
            'videographer'   => $this->videographer,
            'custom_notes'   => $this->custom_notes,
            'target_keyword' => $this->target_keyword,
        ];

        $output = BlogGenerator::generate($params);

        $this->generatedTitle = $output['title'];
        $this->generatedSlug = $output['slug'];
        $this->generatedCategory = $output['category'];
        $this->generatedExcerpt = $output['excerpt'];
        $this->generatedContent = $output['content'];
        $this->generatedReadTime = $output['read_time_minutes'];
        $this->generatedFocusKeyword = $output['focus_keyword'];
        $this->generatedSecondaryKeywords = $output['secondary_keywords'];
        $this->generatedSeoTitle = $output['seo_title'];
        $this->generatedMetaDescription = $output['meta_description'];
        $this->generatedOgTitle = $output['og_title'];
        $this->generatedOgDescription = $output['og_description'];
        $this->generatedAltText = $output['image_alt_text'];
        $this->qualityScore = $output['quality_score'];
        $this->qualityWarnings = $output['quality_warnings'];
        $this->aiProvider = $output['ai_provider'];
        $this->isGenerated = true;

        Notification::make()
            ->title('AI Wedding Blog Generated')
            ->body("Created high-intent editorial draft with Quality Score: {$this->qualityScore}%")
            ->success()
            ->send();
    }

    public function saveDraft(): void
    {
        $this->savePost('draft', false);
    }

    public function approveAndPublish(): void
    {
        $this->savePost('published', true);
    }

    protected function savePost(string $status, bool $isPublished): void
    {
        if (empty($this->generatedTitle)) {
            Notification::make()->title('Cannot save empty article')->danger()->send();
            return;
        }

        $post = $this->savedPostId ? BlogPost::find($this->savedPostId) : new BlogPost();
        if (!$post) $post = new BlogPost();

        $post->title = $this->generatedTitle;
        $post->slug = $this->generatedSlug ?: Str::slug($this->generatedTitle);
        $post->category = $this->generatedCategory;
        $post->excerpt = $this->generatedExcerpt;
        $post->content = $this->generatedContent;
        $post->author_name = 'Paneventz Editorial';
        $post->read_time_minutes = $this->generatedReadTime;
        $post->status = $status;
        $post->is_published = $isPublished;
        $post->published_at = $isPublished ? now() : null;
        $post->focus_keyword = $this->generatedFocusKeyword;
        $post->secondary_keywords = $this->generatedSecondaryKeywords;
        $post->quality_score = $this->qualityScore;
        $post->quality_warnings = $this->qualityWarnings;
        $post->source_story_id = $this->source_story_id;
        $post->source_wedding_album_id = $this->source_wedding_album_id;
        $post->ai_generation_meta = [
            'provider'     => $this->aiProvider,
            'generated_at' => now()->toIso8601String(),
            'inputs'       => [
                'couple'  => $this->couple_name,
                'venue'   => $this->venue,
                'city'    => $this->city,
            ],
        ];

        $post->save();
        $this->savedPostId = $post->id;

        // Persist Polymorphic SEO Metadata
        SeoMetadata::updateOrCreate(
            ['seoable_type' => BlogPost::class, 'seoable_id' => $post->id],
            [
                'title'               => $this->generatedSeoTitle,
                'meta_description'    => $this->generatedMetaDescription,
                'keywords'            => implode(', ', array_merge([$this->generatedFocusKeyword], $this->generatedSecondaryKeywords)),
                'canonical_url'       => $post->url,
                'og_title'            => $this->generatedOgTitle,
                'og_description'      => $this->generatedOgDescription,
                'robots'              => 'index, follow',
                'schema_type'         => 'Article',
            ]
        );

        $label = $isPublished ? 'Approved & Published' : 'Saved as Draft';
        Notification::make()
            ->title("Article {$label}")
            ->body("Successfully saved to database with integrated SEO metadata. Slug: /blog/{$post->slug}")
            ->success()
            ->send();
    }
}
