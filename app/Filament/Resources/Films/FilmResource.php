<?php

namespace App\Filament\Resources\Films;

use App\Filament\Resources\Films\Pages\CreateFilm;
use App\Filament\Resources\Films\Pages\EditFilm;
use App\Filament\Resources\Films\Pages\ListFilms;
use App\Models\Film;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class FilmResource extends Resource
{
    protected static ?string $model = Film::class;
    protected static string|UnitEnum|null $navigationGroup = 'Portfolio';
    protected static ?string $navigationLabel = 'Films';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->required(), TextInput::make('couple_name')->label('Couple Name'), TextInput::make('location'), DatePicker::make('wedding_date')->label('Wedding Date'),
            FileUpload::make('thumbnail')->image()->disk('public')->directory('films')->visibility('public'), TextInput::make('video_url')->label('YouTube, Vimeo, or Video URL')->url()->columnSpanFull(),
            Textarea::make('description')->columnSpanFull(), Toggle::make('is_featured')->label('Feature on Website'), Toggle::make('is_published')->label('Published'), TextInput::make('sort_order')->numeric()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([ImageColumn::make('thumbnail')->disk('public')->label('Cover'), TextColumn::make('title')->searchable()->sortable(), TextColumn::make('couple_name')->label('Couple')->searchable(), TextColumn::make('location')->searchable(), IconColumn::make('is_featured')->label('Featured')->boolean(), IconColumn::make('is_published')->label('Published')->boolean(), TextColumn::make('sort_order')->label('Order')->sortable()])->recordActions([\Filament\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => ListFilms::route('/'), 'create' => CreateFilm::route('/create'), 'edit' => EditFilm::route('/{record}/edit')];
    }
}
