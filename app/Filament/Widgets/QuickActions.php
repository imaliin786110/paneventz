<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\ManageTermsAndConditions;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QuickActions extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getColumns(): array | int | null
    {
        return [
            'default' => 1,
            'sm'      => 2,
            'md'      => 3,
            'lg'      => 6,
        ];
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Upload Story', '+ Add')
                ->description('Add wedding photos & gallery')
                ->icon('heroicon-o-camera')
                ->url('/admin/stories/create')
                ->color('warning'),

            Stat::make('Add Wedding Film', '+ Add')
                ->description('Upload or link new video')
                ->icon('heroicon-o-video-camera')
                ->url('/admin/films/create')
                ->color('info'),

            Stat::make('Client Inquiries', 'Open')
                ->description('View leads & WhatsApp')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->url('/admin/enquiries')
                ->color('success'),

            Stat::make('Wedding Calendar', 'Open')
                ->description('Dates & availability')
                ->icon('heroicon-o-calendar-days')
                ->url('/admin/booking-calendar')
                ->color('primary'),

            Stat::make('Terms & Conditions', 'Edit')
                ->description('Client policies & clauses')
                ->icon('heroicon-o-document-text')
                ->url('/admin/manage-terms-and-conditions')
                ->color('warning'),

            Stat::make('Preview Website', 'Live ↗')
                ->description('Open public site in new tab')
                ->icon('heroicon-o-globe-alt')
                ->url(url('/'))
                ->openUrlInNewTab()
                ->color('gray'),
        ];
    }
}
