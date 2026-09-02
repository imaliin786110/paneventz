<?php

namespace App\Filament\Resources\Films;

use App\Filament\Resources\Films\Pages\CreateFilm;
use App\Filament\Resources\Films\Pages\EditFilm;
use App\Filament\Resources\Films\Pages\ListFilms;
use App\Filament\Resources\Films\Schemas\FilmForm;
use App\Filament\Resources\Films\Tables\FilmsTable;
use App\Models\Film;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;
use Filament\Support\Icons\Heroicon;

class FilmResource extends Resource
{
    protected static ?string $model = Film::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedVideoCamera;

    protected static UnitEnum|string|null $navigationGroup = 'Portfolio';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return FilmForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FilmsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFilms::route('/'),
            'create' => CreateFilm::route('/create'),
            'edit' => EditFilm::route('/{record}/edit'),
        ];
    }
}
