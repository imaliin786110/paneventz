<?php

namespace App\Filament\Pages;

use App\Models\BlogPost;
use App\Models\Location;
use App\Models\SeoHealthIssue;
use App\Models\SeoMetadata;
use App\Models\Service;
use App\Models\Story;
use App\Models\UrlRedirect;
use App\Models\WebsiteSetting;
use App\Models\WeddingAlbum;
use App\Services\Seo\SearchConsoleService;
use App\Services\Seo\SeoHealthScanner;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class SeoAuditDashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static UnitEnum|string|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'SEO Health & Opportunities';

    protected static ?string $slug = 'seo-audit';

    protected static ?int $navigationSort = 95;

    protected static ?string $title = 'Search Engine Optimization (SEO) Health & Opportunities';

    protected string $view = 'filament.pages.seo-audit-dashboard';

    public array $auditStats = [];
    public array $itemsWithIssues = [];
    public array $redirects = [];
    public array $opportunities = [];
    public bool $searchConsoleConfigured = false;

    public function mount(): void
    {
        $this->refreshAudit();
    }

    public function refreshAudit(): void
    {
        $scan = SeoHealthScanner::runFullAudit();

        $this->auditStats = [
            'total_evaluated'      => $scan['scanned_count'],
            'health_score'         => $scan['health_score'],
            'critical_count'       => $scan['critical_count'],
            'warning_count'        => $scan['warning_count'],
            'info_count'           => $scan['info_count'],
            'sitemap_url'          => url('/sitemap.xml'),
            'robots_url'           => url('/robots.txt'),
            'total_redirects'      => UrlRedirect::count(),
            'total_redirect_hits'  => (int) UrlRedirect::sum('hits'),
        ];

        $this->itemsWithIssues = SeoHealthIssue::where('is_resolved', false)->take(15)->get()->toArray();
        $this->redirects = UrlRedirect::orderBy('hits', 'desc')->take(10)->get()->toArray();
        $this->opportunities = SearchConsoleService::getOpportunities();
        $this->searchConsoleConfigured = SearchConsoleService::isConfigured();
    }

    public function runAuditNow(): void
    {
        $this->refreshAudit();
        Notification::make()
            ->title('SEO Health Scan Completed')
            ->body("Scanned {$this->auditStats['total_evaluated']} pages. Overall Health Score: {$this->auditStats['health_score']}%")
            ->success()
            ->send();
    }
}
