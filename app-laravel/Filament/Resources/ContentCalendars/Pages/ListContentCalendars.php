<?php

namespace App\Filament\Resources\ContentCalendars\Pages;

use App\Filament\Resources\ContentCalendars\ContentCalendarResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContentCalendars extends ListRecords
{
    protected static string $resource = ContentCalendarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}