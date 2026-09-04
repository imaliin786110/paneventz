<?php

namespace App\Filament\Components;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class SeoFields
{
    public static function make(string $relationship = 'seo'): Section
    {
        return Section::make('Search Engine Optimization (SEO) & Social Sharing')
            ->description('Customize Google search result titles, meta descriptions, canonical links, and social preview cards.')
            ->icon('heroicon-o-magnifying-glass')
            ->collapsible()
            ->collapsed()
            ->relationship($relationship)
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('title')
                        ->label('Custom SEO Title')
                        ->placeholder('e.g. Royal Udaipur Palace Wedding Photography | Paneventz')
                        ->helperText('Recommended: 50–60 characters. Leave blank for dynamic auto-generated title.')
                        ->maxLength(70)
                        ->columnSpan(1),

                    TextInput::make('canonical_url')
                        ->label('Canonical URL Override')
                        ->placeholder('https://paneventz.com/gallery/your-story')
                        ->helperText('Leave empty for automatic normalized canonical URL.')
                        ->url()
                        ->columnSpan(1),

                    Textarea::make('meta_description')
                        ->label('Meta Description')
                        ->placeholder('A concise, compelling summary shown beneath your title in Google search results.')
                        ->helperText('Recommended: 150–160 characters. Leave blank for dynamic auto-generated summary.')
                        ->maxLength(170)
                        ->rows(3)
                        ->columnSpanFull(),

                    TextInput::make('keywords')
                        ->label('Meta Keywords (Optional)')
                        ->placeholder('luxury wedding photography, destination wedding, Udaipur, cinematic films')
                        ->columnSpanFull(),

                    Select::make('robots')
                        ->label('Robots Indexing Directive')
                        ->options([
                            'index, follow'     => 'Index & Follow (Default - Recommended)',
                            'noindex, follow'   => 'Noindex & Follow (Hide from Google, allow links)',
                            'index, nofollow'   => 'Index & Nofollow',
                            'noindex, nofollow' => 'Noindex & Nofollow (Completely Private)',
                        ])
                        ->default('index, follow')
                        ->columnSpan(1),

                    Select::make('change_frequency')
                        ->label('XML Sitemap Update Frequency')
                        ->options([
                            'always'  => 'Always',
                            'hourly'  => 'Hourly',
                            'daily'   => 'Daily',
                            'weekly'  => 'Weekly (Recommended)',
                            'monthly' => 'Monthly',
                            'yearly'  => 'Yearly',
                        ])
                        ->default('weekly')
                        ->columnSpan(1),

                    Select::make('priority')
                        ->label('Sitemap Priority')
                        ->options([
                            '1.0' => '1.0 (Highest - Homepage/Top Pages)',
                            '0.9' => '0.9 (High - Services, Destination Hubs)',
                            '0.8' => '0.8 (Standard - Stories & Albums)',
                            '0.7' => '0.7 (Medium - Blog Articles)',
                            '0.5' => '0.5 (Low - Policies & Terms)',
                        ])
                        ->default('0.8')
                        ->columnSpan(1),

                    Toggle::make('is_indexed')
                        ->label('Include in XML Sitemap')
                        ->default(true)
                        ->columnSpan(1),
                ]),

                Section::make('Open Graph & Social Sharing Cards (WhatsApp, Facebook, LinkedIn)')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('og_title')
                                ->label('Social Card Title')
                                ->placeholder('Custom title for WhatsApp & Facebook preview cards')
                                ->columnSpan(1),

                            FileUpload::make('og_image')
                                ->label('Social Preview Image (1200x630 px)')
                                ->image()
                                ->disk('public')
                                ->directory('seo/og')
                                ->visibility('public')
                                ->columnSpan(1),

                            Textarea::make('og_description')
                                ->label('Social Card Description')
                                ->placeholder('Custom description for social feeds and WhatsApp share cards')
                                ->rows(2)
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
