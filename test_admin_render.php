<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);
$setting = \App\Models\WebsiteSetting::first();

$request = \Illuminate\Http\Request::create("/admin/website-settings/{$setting->id}/edit", 'GET');
$response = $app->handle($request);
echo "Edit Page Status: " . $response->getStatusCode() . PHP_EOL;