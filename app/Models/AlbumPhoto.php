<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlbumPhoto extends Model
{
    protected $fillable = [
        'wedding_album_id',
        'photo_url',
        'thumbnail_url',
        'file_name',
        'is_video',
        'file_size',
        'face_descriptors',
        'faces_count',
    ];

    protected $casts = [
        'is_video'         => 'boolean',
        'face_descriptors' => 'array',
        'faces_count'      => 'integer',
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(WeddingAlbum::class, 'wedding_album_id');
    }

    public function getFullUrlAttribute(): string
    {
        if (str_starts_with($this->photo_url, 'http://') || str_starts_with($this->photo_url, 'https://')) {
            return $this->photo_url;
        }
        return asset('storage/' . $this->photo_url);
    }

    public function getDirectDownloadUrlAttribute(): string
    {
        return url('/gallery/download/' . $this->id);
    }
}