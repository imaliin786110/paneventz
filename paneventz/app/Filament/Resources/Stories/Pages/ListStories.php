<?php

namespace App\Filament\Resources\Stories\Pages;

use App\Filament\Resources\Stories\StoryResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStories extends ListRecords
{
    protected static string $resource = StoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Story')
                ->icon('heroicon-o-plus'),

            Action::make('selectAll')
                ->label('Select All')
                ->icon('heroicon-o-check-circle')
                ->color('gray')
                ->action(function () {
                    $this->selectAllTableRecords();
                }),

            Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(function () {
                    $this->resetTable();
                }),

            Action::make('deleteSelected')
                ->label('Delete')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->disabled(function () {
                    return $this->getSelectedTableRecords()->isEmpty();
                })
                ->requiresConfirmation()
                ->modalHeading('Delete selected story?')
                ->modalDescription(
                    'This will permanently delete the selected story and its uploaded media. This action cannot be undone.'
                )
                ->modalSubmitActionLabel('Yes, Delete')
                ->action(function () {
                    foreach ($this->getSelectedTableRecords() as $record) {
                        $record->delete();
                    }

                    $this->deselectAllTableRecords();
                    $this->resetTable();
                }),
        ];
    }
}