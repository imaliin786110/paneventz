<?php

namespace App\Console\Commands;

use App\Services\Seo\SeoHealthScanner;
use Illuminate\Console\Command;

class RunSeoHealthScanCommand extends Command
{
    protected $signature = 'seo:health-scan';
    protected $description = 'Perform scheduled site-wide SEO health check and broken link detection';

    public function handle(): int
    {
        $this->info('Starting comprehensive SEO health scan...');

        $result = SeoHealthScanner::runFullAudit();

        $this->info("Scanned {$result['scanned_count']} items.");
        $this->info("Overall Health Score: {$result['health_score']}%");
        $this->line("Critical Issues: {$result['critical_count']}");
        $this->line("Warnings: {$result['warning_count']}");
        $this->line("Info notices: {$result['info_count']}");

        return self::SUCCESS;
    }
}
