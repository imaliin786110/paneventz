<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// XML Sitemap & Robots.txt
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [RobotsController::class, 'index'])->name('robots');

// Core Public Pages
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/services', function () {
    return view('services');
})->name('services');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

// High-Value Destination Wedding SEO Pages
Route::get('/wedding-photographer-{slug}', [LocationController::class, 'show'])->name('locations.show');

// Wedding Journal & Articles
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::post('/enquire', [EnquiryController::class, 'store'])->name('enquiries.store');

// Client VIP Portal (Download Your Story via PIN)
Route::get('/client-portal', [GalleryController::class, 'clientPortalGate'])->name('client.portal');
Route::post('/client-portal/unlock', [GalleryController::class, 'unlockClientPortal'])->name('client.portal.unlock');

// Client Wedding Galleries & AI Face Finder
Route::get('/galleries', [GalleryController::class, 'index'])->name('galleries.index');
Route::get('/gallery/{slug}', [GalleryController::class, 'show'])->name('gallery.show');
Route::post('/gallery/{slug}/verify-pin', [GalleryController::class, 'verifyPin'])->name('gallery.verify-pin');
Route::get('/gallery/{slug}/photos-data', [GalleryController::class, 'getPhotosData'])->name('gallery.photos-data');
Route::get('/gallery/download/{photoId}', [GalleryController::class, 'downloadFile'])->name('gallery.download');
Route::post('/admin/photos/{photoId}/descriptors', [GalleryController::class, 'saveDescriptors'])->name('admin.photos.descriptors');

// Ultra-fast HTTP 206 Partial Content Media Streaming (Videos & Audio)
Route::get('/media/stream/{path}', function (string $path) {
    $fullPath = public_path('storage/' . $path);
    if (!file_exists($fullPath) || !is_file($fullPath)) {
        abort(404);
    }

    return response()->file($fullPath, [
        'Cache-Control' => 'public, max-age=31536000, immutable',
        'Accept-Ranges' => 'bytes',
    ]);
})->where('path', '.*')->name('media.stream');