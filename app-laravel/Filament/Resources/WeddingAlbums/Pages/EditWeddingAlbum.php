<?php

namespace App\Filament\Resources\WeddingAlbums\Pages;

use App\Filament\Resources\WeddingAlbums\WeddingAlbumResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWeddingAlbum extends EditRecord
{
    protected static string $resource = WeddingAlbumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}