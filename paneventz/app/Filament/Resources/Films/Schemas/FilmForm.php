<?php

namespace App\Filament\Resources\Films\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FilmForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Film Title')
                    ->placeholder('e.g. A Venetian Romance')
                    ->required(),

                TextInput::make('couple_name')
                    ->label('Couple Name')
                    ->placeholder('e.g. Rohan & Priya'),

                TextInput::make('location')
                    ->label('Location / Venue')
                    ->placeholder('e.g. Lake Como, Italy or Udaipur'),

                TextInput::make('duration')
                    ->label('Duration')
                    ->placeholder('e.g. 04:30 or Full Film'),

                TextInput::make('video_url')
                    ->label('Video Embed / Link (YouTube or Vimeo)')
                    ->placeholder('https://www.youtube.com/watch?v=...')
                    ->helperText('Paste a YouTube or Vimeo link, or upload an MP4 below.')
                    ->columnSpanFull(),

                FileUpload::make('video_file')
                    ->label('Upload Video File (MP4, MOV, WebM)')
                    ->acceptedFileTypes([
                        'video/mp4',
                        'video/webm',
                        'video/quicktime',
                    ])
                    ->maxSize(512 * 1024)
                    ->disk('public')
                    ->directory('films')
                    ->visibility('public')
                    ->columnSpanFull(),

                FileUpload::make('thumbnail')
                    ->label('Poster / Cover Image')
                    ->image()
                    ->disk('public')
                    ->directory('films/thumbnails')
                    ->visibility('public')
                    ->helperText('Preview poster image shown before the film plays.')
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->label('Story / Film Description')
                    ->rows(3)
                    ->columnSpanFull(),

                TextInput::make('sort_order')
                    ->label('Display Order')
                    ->numeric()
                    ->default(0),

                Toggle::make('is_featured')
                    ->label('Featured on Homepage')
                    ->default(true),

                Toggle::make('is_published')
                    ->label('Published')
                    ->default(true),
            ]);
    }
}
