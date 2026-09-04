<?php

namespace App\Http\Middleware;

use App\Models\UrlRedirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class HandleUrlRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = '/' . trim($request->path(), '/');

        // Ignore static assets and admin paths for redirect checks
        if (str_starts_with($path, '/admin') || str_starts_with($path, '/storage') || str_starts_with($path, '/livewire') || str_starts_with($path, '/filament')) {
            return $next($request);
        }

        // Cache all active redirects map for 1 hour to avoid DB queries on every request
        $redirectsMap = Cache::remember('active_url_redirects_map', 3600, function () {
            return UrlRedirect::select(['id', 'source_path', 'target_path', 'status_code'])
                ->get()
                ->keyBy('source_path')
                ->toArray();
        });

        if (isset($redirectsMap[$path])) {
            $redirectData = $redirectsMap[$path];
            // Async hit count or background update
            UrlRedirect::where('id', $redirectData['id'])->increment('hits');
            return redirect($redirectData['target_path'], (int) $redirectData['status_code']);
        }

        return $next($request);
    }
}
