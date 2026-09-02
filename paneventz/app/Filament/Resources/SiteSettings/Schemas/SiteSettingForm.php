<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label('Setting Key')
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('group')
                    ->label('Section / Group')
                    ->disabled()
                    ->dehydrated(false),

                Textarea::make('value')
                    ->label('Setting Value')
                    ->rows(5)
                    ->helperText('Update this text or link to immediately reflect on your live website.')
                    ->columnSpanFull(),
            ]);
    }
}