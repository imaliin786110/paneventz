<?php

namespace App\Filament\Resources\Enquiries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EnquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('status')
                    ->label('Lead Status')
                    ->options([
                        'new' => '🟡 New Lead',
                        'contacted' => '🔵 Contacted / Followed Up',
                        'meeting_scheduled' => '🟣 Meeting Scheduled',
                        'booked' => '🟢 Booked & Confirmed',
                        'archived' => '⚪ Archived',
                    ])
                    ->default('new')
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('name')
                    ->label('Client / Couple Name')
                    ->required(),

                TextInput::make('phone')
                    ->label('Phone / WhatsApp')
                    ->tel()
                    ->required(),

                TextInput::make('email')
                    ->label('Email Address')
                    ->email(),

                DatePicker::make('wedding_date')
                    ->label('Wedding / Event Date'),

                TextInput::make('wedding_location')
                    ->label('Venue / City Location'),

                TextInput::make('service')
                    ->label('Service / Package Requested'),

                Textarea::make('message')
                    ->label('Client Message & Details')
                    ->rows(4)
                    ->columnSpanFull(),

                Textarea::make('admin_notes')
                    ->label('Studio Private Notes')
                    ->placeholder('Notes from phone calls, pricing discussed, meetings...')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
