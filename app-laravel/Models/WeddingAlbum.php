<?php

namespace App\Models;

use App\Traits\HasSeo;
use App\Traits\HasSlugHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class WeddingAlbum extends Model
{
    use HasSeo, HasSlugHistory;

    protected $slugSource = 'title';
    protected $fillable = [
        'title',
        'slug',
        'couple_names',
        'cover_image',
        'event_date',
        'location',
        'pin_code',
        'google_drive_folder_id',
        'guest_google_drive_folder_id',
        'is_public',
        'allow_downloads',
        'enable_face_ai',
    ];

    protected $casts = [
        'event_date'      => 'date',
        'is_public'       => 'boolean',
        'allow_downloads' => 'boolean',
        'enable_face_ai'  => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (WeddingAlbum $album) {
            if (empty($album->slug)) {
                $album->slug = Str::slug($album->title ?: ($album->couple_names ?: 'wedding-' . time()));
            }
        });
    }

    public function photos(): HasMany
    {
        return $this->hasMany(AlbumPhoto::class);
    }

    public function getGuestUrlAttribute(): string
    {
        return url('/gallery/' . $this->slug);
    }

    public function getTotalFacesAttribute(): int
    {
        return (int) $this->photos()->sum('faces_count');
    }
}