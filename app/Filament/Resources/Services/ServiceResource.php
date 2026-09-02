<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\Pages\CreateService;
use App\Filament\Resources\Services\Pages\EditService;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Models\Service;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static string|UnitEnum|null $navigationGroup = 'Business';
    protected static ?string $navigationLabel = 'Services & Packages';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required(),
            TextInput::make('short_description')->label('Short Description')->columnSpanFull(),
            Textarea::make('description')->columnSpanFull(),
            TextInput::make('price_from')->label('Starting Price')->numeric()->prefix('₹'),
            Toggle::make('is_published')->label('Published'),
            TextInput::make('sort_order')->numeric()->default(0),
            \App\Filament\Components\SeoFields::make('seo'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('name')->searchable()->sortable(), TextColumn::make('price_from')->label('Starting Price')->money('INR')->sortable(), IconColumn::make('is_published')->label('Published')->boolean(), TextColumn::make('sort_order')->label('Order')->sortable()])->recordActions([\Filament\Actions\EditAction::make()]);
    }

    public static function getPages(): array { return ['index' => ListServices::route('/'), 'create' => CreateService::route('/create'), 'edit' => EditService::route('/{record}/edit')]; }
}
