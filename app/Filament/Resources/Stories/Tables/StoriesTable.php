<?php

namespace App\Filament\Resources\Stories\Tables;

use App\Models\Story;
use Filament\Actions\Action;
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

                TextColumn::make('cover_image')
                    ->label('Media')
                    ->formatStateUsing(function ($state, Story $record) {
                        if (!$state) {
                            return '<span style="font-size:11px; color:#64748b; font-style:italic;">No media</span>';
                        }
                        $url = asset('storage/' . $state);
                        $ext = strtolower(pathinfo($state, PATHINFO_EXTENSION));
                        if (in_array($ext, ['mp4', 'mov', 'webm', 'ogg'])) {
                            return '<div style="position:relative; width:80px; height:50px; border-radius:6px; overflow:hidden; background:#0c1019; display:flex; align-items:center; justify-content:center; border:1px solid rgba(245,158,11,0.3);">
                                <video src="' . $url . '#t=0.001" preload="metadata" muted playsinline style="width:100%; height:100%; object-fit:cover; pointer-events:none;"></video>
                                <span style="position:absolute; background:rgba(0,0,0,0.7); color:#f59e0b; padding:2px 6px; border-radius:4px; font-size:10px; font-weight:600; display:flex; align-items:center; gap:2px;">▶ Video</span>
                            </div>';
                        }
                        return '<img src="' . $url . '" style="width:80px; height:50px; object-fit:cover; border-radius:6px; border:1px solid rgba(255,255,255,0.1);">';
                    })
                    ->html(),

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
                Action::make('generate_ai_blog')
                    ->label('AI Blog ✨')
                    ->icon('heroicon-o-sparkles')
                    ->url(fn (Story $record) => "/admin/ai-blog-writer?story_id={$record->id}"),

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
