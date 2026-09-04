<?php

namespace App\Filament\Resources\WeddingAlbums\Pages;

use App\Filament\Resources\WeddingAlbums\WeddingAlbumResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWeddingAlbums extends ListRecords
{
    protected static string $resource = WeddingAlbumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('+ New Wedding Album'),
        ];
    }
}