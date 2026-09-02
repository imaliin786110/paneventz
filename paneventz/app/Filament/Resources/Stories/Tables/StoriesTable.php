<?php

namespace App\Filament\Resources\Stories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('couple_name')
                    ->label('Couple Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('location')
                    ->searchable()
                    ->sortable(),

                ImageColumn::make('cover_image')
                    ->label('Media')
                    ->disk('public')
                    ->height(60)
                    ->width(80),

                ToggleColumn::make('is_published')
                    ->label('Published')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                SelectFilter::make('is_published')
                    ->label('Published Status')
                    ->options([
                        1 => 'Published',
                        0 => 'Unpublished',
                    ]),

                SelectFilter::make('location')
                    ->label('Location')
                    ->options(function () {
                        return \App\Models\Story::query()
                            ->whereNotNull('location')
                            ->where('location', '!=', '')
                            ->distinct()
                            ->orderBy('location')
                            ->pluck('location', 'location')
                            ->toArray();
                    }),
            ])

            ->recordActions([
                EditAction::make(),

                DeleteAction::make()
                    ->requiresConfirmation(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Delete selected stories?')
                        ->modalDescription(
                            'This will permanently delete the selected stories and their uploaded media. This action cannot be undone.'
                        ),
                ]),
            ]);
    }
}