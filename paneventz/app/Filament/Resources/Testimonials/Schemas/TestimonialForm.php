<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('couple_name')
                    ->label('Couple / Client Names')
                    ->placeholder('e.g. Priya & Arjun')
                    ->required(),

                TextInput::make('wedding_location')
                    ->label('Wedding Location / Destination')
                    ->placeholder('e.g. The Leela Palace, Udaipur'),

                TextInput::make('wedding_date')
                    ->label('Wedding Date')
                    ->placeholder('e.g. December 2025'),

                Select::make('rating')
                    ->label('Rating')
                    ->options([
                        5 => '⭐⭐⭐⭐⭐ (5 Stars)',
                        4 => '⭐⭐⭐⭐ (4 Stars)',
                    ])
                    ->default(5)
                    ->required(),

                Textarea::make('quote')
                    ->label('Review / Words from the Couple')
                    ->rows(4)
                    ->required()
                    ->columnSpanFull(),

                FileUpload::make('photo')
                    ->label('Couple Photo / Portrait')
                    ->image()
                    ->disk('public')
                    ->directory('testimonials')
                    ->visibility('public')
                    ->columnSpanFull(),

                TextInput::make('sort_order')
                    ->label('Display Order')
                    ->numeric()
                    ->default(0),

                Toggle::make('is_featured')
                    ->label('Feature on Website')
                    ->default(true),
            ]);
    }
}