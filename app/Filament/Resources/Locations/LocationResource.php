<?php

namespace App\Filament\Resources\Locations;

use App\Filament\Components\SeoFields;
use App\Filament\Resources\Locations\Pages\CreateLocation;
use App\Filament\Resources\Locations\Pages\EditLocation;
use App\Filament\Resources\Locations\Pages\ListLocations;
use App\Models\Location;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static string|UnitEnum|null $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'Destination SEO Pages';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Destination Profile')
                ->description('Manage city details, SEO landing headline, and iconic wedding venue mentions.')
                ->icon('heroicon-o-map-pin')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('City / Destination Name')
                        ->required()
                        ->placeholder('e.g. Udaipur')
                        ->columnSpan(1),

                    TextInput::make('slug')
                        ->label('SEO URL Slug')
                        ->placeholder('e.g. udaipur')
                        ->helperText('Public link: /wedding-photographer-udaipur')
                        ->columnSpan(1),

                    TextInput::make('state')
                        ->label('State / Region')
                        ->placeholder('Rajasthan')
                        ->columnSpan(1),

                    TextInput::make('country')
                        ->label('Country')
                        ->default('India')
                        ->columnSpan(1),

                    TextInput::make('headline')
                        ->label('Primary Landing Headline')
                        ->placeholder('Luxury Royal Palace Wedding Photography in Udaipur')
                        ->columnSpanFull(),

                    Textarea::make('intro')
                        ->label('Destination Narrative Intro')
                        ->rows(3)
                        ->placeholder('Udaipur offers a breathtaking backdrop of shimmering lakes and regal Mewar heritage. Our photography captures this timeless splendor.')
                        ->columnSpanFull(),

                    FileUpload::make('hero_image')
                        ->label('Location Cover Photo')
                        ->image()
                        ->disk('public')
                        ->directory('locations')
                        ->visibility('public')
                        ->columnSpanFull(),

                    TagsInput::make('popular_venues')
                        ->label('Iconic Wedding Venues in this Destination')
                        ->placeholder('Add venue (e.g. Taj Lake Palace, The Oberoi Udaivilas, Leela Palace)')
                        ->helperText('Type venue name and press Enter.')
                        ->columnSpanFull(),

                    Repeater::make('faqs')
                        ->label('Localized FAQs for this Destination')
                        ->schema([
                            TextInput::make('q')->label('Question')->required(),
                            Textarea::make('a')->label('Answer')->required()->rows(2),
                        ])
                        ->columns(1)
                        ->collapsible()
                        ->columnSpanFull(),

                    Toggle::make('is_published')
                        ->label('Published on Website')
                        ->default(true),

                    TextInput::make('sort_order')
                        ->label('Display Sort Order')
                        ->numeric()
                        ->default(0),
                ]),

            SeoFields::make('seo'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Destination')->searchable()->sortable()->weight('bold'),
                TextColumn::make('state')->label('State')->searchable(),
                TextColumn::make('slug')->label('Landing URL')->formatStateUsing(fn ($state) => "/wedding-photographer-{$state}"),
                IconColumn::make('is_published')->label('Published')->boolean(),
                TextColumn::make('sort_order')->label('Order')->sortable(),
            ])
            ->recordActions([
                Action::make('preview')
                    ->label('View Page ↗')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Location $record) => $record->url)
                    ->openUrlInNewTab(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListLocations::route('/'),
            'create' => CreateLocation::route('/create'),
            'edit'   => EditLocation::route('/{record}/edit'),
        ];
    }
}
