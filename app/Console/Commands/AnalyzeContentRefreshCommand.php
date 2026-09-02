<?php

namespace App\Console\Commands;

use App\Services\Ai\ContentRefreshEngine;
use Illuminate\Console\Command;

class AnalyzeContentRefreshCommand extends Command
{
    protected $signature = 'seo:refresh-check';
    protected $description = 'Identify published blog articles in need of SEO and content refresh';

    public function handle(): int
    {
        $this->info('Scanning published blogs for content refresh candidates...');

        $candidates = ContentRefreshEngine::getCandidates();

        $this->info("Found " . count($candidates) . " candidate(s) for content refresh:");

        foreach ($candidates as $c) {
            $this->line("- [ID: {$c['id']}] {$c['title']} ({$c['word_count']} words, last updated {$c['days_since_update']} days ago)");
            foreach ($c['reasons'] as $r) {
                $this->line("    * {$r}");
            }
        }

        return self::SUCCESS;
    }
}
