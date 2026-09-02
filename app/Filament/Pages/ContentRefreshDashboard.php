<?php

namespace App\Filament\Pages;

use App\Models\BlogPost;
use App\Services\Ai\ContentRefreshEngine;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ContentRefreshDashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowPath;

    protected static UnitEnum|string|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'SEO Content Refresh';

    protected static ?string $slug = 'content-refresh';

    protected static ?int $navigationSort = 96;

    protected static ?string $title = 'SEO Content Refresh & Optimization';

    protected string $view = 'filament.pages.content-refresh-dashboard';

    public array $candidates = [];
    public ?array $activeSuggestions = null;
    public ?int $analyzingPostId = null;

    public function mount(): void
    {
        $this->refreshList();
    }

    public function refreshList(): void
    {
        $this->candidates = ContentRefreshEngine::getCandidates();
    }

    public function analyzePost(int $postId): void
    {
        $this->analyzingPostId = $postId;
        $post = BlogPost::find($postId);
        if ($post) {
            $this->activeSuggestions = ContentRefreshEngine::generateSuggestions($post);
            Notification::make()
                ->title('AI Refresh Suggestions Ready')
                ->body("Generated editorial and SEO optimizations for '{$post->title}'")
                ->success()
                ->send();
        }
    }
}
