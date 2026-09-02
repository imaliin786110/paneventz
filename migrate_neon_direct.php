<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

$directHost = 'ep-broad-queen-axttn4de.c-4.us-east-2.aws.neon.tech';

Config::set('database.default', 'pgsql');
Config::set('database.connections.pgsql', [
    'driver'         => 'pgsql',
    'host'           => $directHost,
    'port'           => 5432,
    'database'       => 'neondb',
    'username'       => 'neondb_owner',
    'password'       => 'npg_OkM6bBY9cUtp',
    'charset'        => 'utf8',
    'prefix'         => '',
    'prefix_indexes' => true,
    'search_path'    => 'public',
    'sslmode'        => 'require',
]);

echo "Running fresh migrations on Neon PostgreSQL..." . PHP_EOL;
Artisan::call('migrate:fresh', ['--force' => true]);
echo Artisan::output();

echo "Seeding data into Neon PostgreSQL..." . PHP_EOL;
Artisan::call('db:seed', ['--class' => 'ProductionDataSeeder', '--force' => true]);
echo Artisan::output();

echo "=== VERIFYING NEON DATABASE RECORDS ===" . PHP_EOL;
$tables = ['users', 'website_settings', 'stories', 'films', 'services', 'testimonials', 'blog_posts', 'wedding_albums'];
foreach ($tables as $t) {
    $c = DB::connection('pgsql')->table($t)->count();
    echo "  - Table {$t}: {$c} rows" . PHP_EOL;
}