<?php

namespace App\Filament\Pages;

use App\Models\ContentCalendar;
use App\Services\Ai\BlogIdeasEngine;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class BlogIdeasDashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLightBulb;

    protected static UnitEnum|string|null $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'AI Blog Ideas';

    protected static ?string $slug = 'blog-ideas';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'AI Content Strategy & Topic Ideas';

    protected string $view = 'filament.pages.blog-ideas-dashboard';

    public array $ideas = [];

    public function mount(): void
    {
        $this->refreshIdeas();
    }

    public function refreshIdeas(): void
    {
        $this->ideas = BlogIdeasEngine::generateIdeas(8);
    }

    public function addToCalendar(int $index): void
    {
        $idea = $this->ideas[$index] ?? null;
        if (!$idea) return;

        ContentCalendar::create([
            'topic'          => $idea['topic'],
            'target_keyword' => $idea['target_keyword'],
            'category'       => $idea['category'] ?? 'Wedding Photography',
            'author_name'    => 'Paneventz Editorial',
            'scheduled_for'  => now()->addDays(rand(3, 14))->toDateString(),
            'status'         => 'planned',
            'notes'          => $idea['reasoning'] ?? '',
        ]);

        Notification::make()
            ->title('Added to Content Calendar')
            ->body("Scheduled topic '{$idea['topic']}' in editorial calendar.")
            ->success()
            ->send();
    }
}
