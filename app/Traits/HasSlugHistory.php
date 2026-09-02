<?php

namespace App\Traits;

use App\Models\UrlRedirect;
use Illuminate\Support\Str;

trait HasSlugHistory
{
    public static function bootHasSlugHistory(): void
    {
        static::creating(function ($model) {
            $slugField = $model->getSlugSourceColumn();
            if (empty($model->slug) && !empty($model->{$slugField})) {
                $model->slug = static::generateUniqueSlug($model->{$slugField}, $model);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('slug')) {
                $oldSlug = $model->getOriginal('slug');
                $newSlug = $model->slug;

                if (!empty($oldSlug) && !empty($newSlug) && $oldSlug !== $newSlug) {
                    $prefix = $model->getSlugUrlPrefix();
                    $sourcePath = $prefix . '/' . $oldSlug;
                    $targetPath = $prefix . '/' . $newSlug;

                    UrlRedirect::updateOrCreate(
                        ['source_path' => $sourcePath],
                        [
                            'target_path' => $targetPath,
                            'status_code' => 301,
                        ]
                    );
                }
            }
        });
    }

    public function getSlugSourceColumn(): string
    {
        return property_exists($this, 'slugSource') ? $this->slugSource : 'title';
    }

    public function getSlugUrlPrefix(): string
    {
        if ($this instanceof \App\Models\WeddingAlbum) {
            return '/gallery';
        }
        if ($this instanceof \App\Models\Location) {
            return '/wedding-photographer';
        }
        if ($this instanceof \App\Models\BlogPost) {
            return '/blog';
        }

        return '';
    }

    public static function generateUniqueSlug(string $title, $model, string $column = 'slug'): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (static::where($column, $slug)->when($model->exists, fn ($q) => $q->where('id', '!=', $model->id))->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }
}
