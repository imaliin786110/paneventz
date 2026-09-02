<?php

namespace App\Filament\Resources\Enquiries\Schemas;

use Filament\Forms\Components\CheckboxList;
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
                        'contacted' => '🔵 Contacted / Followed-up',
                        'meeting_scheduled' => '🟣 Meeting / Call Scheduled',
                        'booked' => '🟢 Booked & Confirmed',
                        'archived' => '⚪ Archived',
                    ])
                    ->default('new')
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('name')
                    ->label('Client / Couple Name')
                    ->required(),

                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required(),

                TextInput::make('phone')
                    ->label('Phone / WhatsApp')
                    ->tel()
                    ->required(),

                DatePicker::make('event_date')
                    ->label('Wedding / Event Date'),

                TextInput::make('event_location')
                    ->label('Venue / Location'),

                Select::make('budget')
                    ->label('Estimated Budget')
                    ->options([
                        'Under ₹1.5 Lakh' => 'Under ₹1.5 Lakh',
                        '₹1.5 Lakh - ₹3 Lakh' => '₹1.5 Lakh - ₹3 Lakh',
                        '₹3 Lakh - ₹5 Lakh' => '₹3 Lakh - ₹5 Lakh',
                        '₹5 Lakh - ₹10 Lakh' => '₹5 Lakh - ₹10 Lakh',
                        '₹10 Lakh+' => '₹10 Lakh+',
                        'Flexible' => 'Flexible',
                    ]),

                CheckboxList::make('services')
                    ->label('Services Requested')
                    ->options([
                        'Wedding Photography' => 'Wedding Photography',
                        'Cinematic Wedding Film' => 'Cinematic Wedding Film',
                        'Drone & Aerial Cinematography' => 'Drone & Aerial Cinematography',
                        'Pre-Wedding / Engagement Shoot' => 'Pre-Wedding / Engagement Shoot',
                        'Destination Wedding Coverage' => 'Destination Wedding Coverage',
                    ])
                    ->columnSpanFull(),

                Textarea::make('message')
                    ->label('Client Message & Details')
                    ->rows(4)
                    ->columnSpanFull(),

                Textarea::make('admin_notes')
                    ->label('Studio Internal Notes')
                    ->placeholder('e.g. Called on 30th Aug, interested in 3-day Udaipur package...')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
