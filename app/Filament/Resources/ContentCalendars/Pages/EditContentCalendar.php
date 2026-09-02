<?php

namespace App\Filament\Resources\ContentCalendars\Pages;

use App\Filament\Resources\ContentCalendars\ContentCalendarResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContentCalendar extends EditRecord
{
    protected static string $resource = ContentCalendarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}