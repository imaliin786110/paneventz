<?php

namespace App\Filament\Resources\Stories\Schemas;

use App\Models\Story;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class StoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('couple_name')
                    ->required(),

                TextInput::make('location'),

                FileUpload::make('cover_image')
                    ->label('Cover Image / Video')
                    ->acceptedFileTypes([
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                        'image/gif',
                        'video/mp4',
                        'video/webm',
                        'video/quicktime',
                    ])
                    ->maxSize(512 * 1024)
                    ->previewable(false)
                    ->disk('public')
                    ->directory('stories')
                    ->visibility('public')
                    ->helperText(
                        'Upload a JPG, PNG, WebP, GIF, MP4, WebM, or MOV file. Maximum size: 512 MB.'
                    ),

                Placeholder::make('current_media_preview')
                    ->label('Current Media Preview')
                    ->content(function (?Story $record) {
                        if (! $record?->cover_image) {
                            return 'No media uploaded yet.';
                        }

                        $url = Storage::disk('public')->url($record->cover_image);

                        $extension = strtolower(
                            pathinfo($record->cover_image, PATHINFO_EXTENSION)
                        );

                        if (in_array($extension, ['mp4', 'webm', 'mov'])) {
                            return new HtmlString(
                                '<video controls playsinline style="width: 100%; max-width: 700px; max-height: 450px; border-radius: 12px; background: #000;">
                                    <source src="' . e($url) . '">
                                    Your browser does not support video playback.
                                </video>'
                            );
                        }

                        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                            return new HtmlString(
                                '<img src="' . e($url) . '" alt="Current media" style="width: 100%; max-width: 700px; max-height: 450px; object-fit: contain; border-radius: 12px;">'
                            );
                        }

                        return 'Preview not available for this file type.';
                    })
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->columnSpanFull(),

                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),

                Toggle::make('is_published')
                    ->required(),
            ]);
    }
}