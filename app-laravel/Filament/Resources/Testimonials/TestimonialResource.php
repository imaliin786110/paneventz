<?php

namespace App\Filament\Resources\Testimonials;

use App\Filament\Resources\Testimonials\Pages\CreateTestimonial;
use App\Filament\Resources\Testimonials\Pages\EditTestimonial;
use App\Filament\Resources\Testimonials\Pages\ListTestimonials;
use App\Models\Testimonial;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;
    protected static string|UnitEnum|null $navigationGroup = 'Business';
    protected static ?string $navigationLabel = 'Testimonials';
    protected static ?string $recordTitleAttribute = 'couple_name';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('couple_name')->label('Couple Name')->required(), TextInput::make('location'),
            Select::make('rating')->options([5 => '5 stars', 4 => '4 stars', 3 => '3 stars', 2 => '2 stars', 1 => '1 star'])->default(5)->required(),
            FileUpload::make('photo')->image()->disk('public')->directory('testimonials')->visibility('public'), Textarea::make('review')->required()->columnSpanFull(),
            Toggle::make('is_published')->label('Published'), TextInput::make('sort_order')->numeric()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('couple_name')->label('Couple')->searchable(), TextColumn::make('location')->searchable(), TextColumn::make('rating')->suffix(' / 5')->sortable(), IconColumn::make('is_published')->label('Published')->boolean(), TextColumn::make('sort_order')->label('Order')->sortable()])->recordActions([\Filament\Actions\EditAction::make()]);
    }

    public static function getPages(): array { return ['index' => ListTestimonials::route('/'), 'create' => CreateTestimonial::route('/create'), 'edit' => EditTestimonial::route('/{record}/edit')]; }
}
