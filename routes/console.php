<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule daily automated tasks
Schedule::command('blog:publish-scheduled')->hourly();
Schedule::command('seo:health-scan')->dailyAt('03:00');
Schedule::command('seo:refresh-check')->weeklyOn(1, '04:00');
