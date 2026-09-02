<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UrlRedirect extends Model
{
    protected $table = 'url_redirects';

    protected $fillable = [
        'source_path',
        'target_path',
        'status_code',
        'hits',
        'last_accessed_at',
    ];

    protected $casts = [
        'status_code'      => 'integer',
        'hits'             => 'integer',
        'last_accessed_at' => 'datetime',
    ];

    public static function findMatch(string $path): ?self
    {
        $normalized = '/' . trim(parse_url($path, PHP_URL_PATH), '/');
        return static::where('source_path', $normalized)->first();
    }

    public function recordHit(): void
    {
        $this->increment('hits');
        $this->update(['last_accessed_at' => now()]);
    }
}
