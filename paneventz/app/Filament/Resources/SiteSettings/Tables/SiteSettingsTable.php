<?php

namespace App\Filament\Resources\SiteSettings\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SiteSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')
                    ->label('Section')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('key')
                    ->label('Setting')
                    ->searchable()
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('value')
                    ->label('Current Value')
                    ->limit(65)
                    ->searchable(),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->options([
                        'hero' => 'Hero Section',
                        'about' => 'About Section',
                        'contact' => 'Contact & Social',
                        'general' => 'General / Studio',
                    ]),
            ])
            ->recordActions([
                EditAction::make()->label('Change Value'),
            ])
            ->paginated(false);
    }
}