<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\ManageTermsAndConditions;
use App\Filament\Resources\Enquiries\EnquiryResource;
use App\Filament\Resources\Films\FilmResource;
use App\Filament\Resources\Stories\StoryResource;
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
            'lg'      => 5,
        ];
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Add Story', 'Upload')
                ->description('New wedding story')
                ->icon('heroicon-o-plus-circle')
                ->url(StoryResource::getUrl('create')),

            Stat::make('Add Film', 'Upload')
                ->description('New cinematic video')
                ->icon('heroicon-o-video-camera')
                ->url(FilmResource::getUrl('create')),

            Stat::make('Client Enquiries', 'Manage')
                ->description('View leads & bookings')
                ->icon('heroicon-o-envelope')
                ->url(EnquiryResource::getUrl('index')),

            Stat::make('Terms & Conditions', 'Edit')
                ->description('Client policies & clauses')
                ->icon('heroicon-o-document-text')
                ->url(ManageTermsAndConditions::getUrl()),

            Stat::make('Live Website', 'Open')
                ->description('Preview public website')
                ->icon('heroicon-o-globe-alt')
                ->url(url('/'))
                ->openUrlInNewTab(),
        ];
    }
}
