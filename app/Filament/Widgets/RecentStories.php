<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Stories\StoryResource;
use App\Models\Story;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Filament\Actions\Action;

class RecentStories extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Story::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('couple_name')
                    ->label('Couple')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('location')
                    ->label('Location')
                    ->placeholder('—'),

                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime('d M Y, h:i A'),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->url(
                        fn (Story $record): string =>
                            StoryResource::getUrl('edit', ['record' => $record])
                    ),
            ])
            ->paginated(false);
    }
}