<?php

namespace App\Filament\Resources\Faqs;

use App\Filament\Resources\Faqs\Pages\CreateFaq;
use App\Filament\Resources\Faqs\Pages\EditFaq;
use App\Filament\Resources\Faqs\Pages\ListFaqs;
use App\Models\Faq;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;
    protected static string|UnitEnum|null $navigationGroup = 'Business';
    protected static ?string $navigationLabel = 'FAQs';
    protected static ?string $recordTitleAttribute = 'question';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('question')->required()->columnSpanFull(),
            Textarea::make('answer')->required()->columnSpanFull(),
            Toggle::make('is_published')->label('Published'),
            TextInput::make('sort_order')->numeric()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('question')->searchable()->wrap(), IconColumn::make('is_published')->label('Published')->boolean(), TextColumn::make('sort_order')->label('Order')->sortable()])->recordActions([\Filament\Actions\EditAction::make()]);
    }

    public static function getPages(): array { return ['index' => ListFaqs::route('/'), 'create' => CreateFaq::route('/create'), 'edit' => EditFaq::route('/{record}/edit')]; }
}
