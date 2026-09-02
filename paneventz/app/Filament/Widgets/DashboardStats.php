<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Enquiries\EnquiryResource;
use App\Filament\Resources\Films\FilmResource;
use App\Filament\Resources\Stories\StoryResource;
use App\Models\Enquiry;
use App\Models\Film;
use App\Models\Story;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends StatsOverviewWidget
{
    protected function getColumns(): array | int | null
    {
        return [
            'default' => 1,
            'sm'      => 2,
            'lg'      => 3,
            'xl'      => 5,
        ];
    }

    protected function getStats(): array
    {
        $newEnquiries = Enquiry::where('status', 'new')->count();
        $totalStories = Story::count();
        $totalFilms = Film::count();
        $bookedEvents = Enquiry::where('status', 'booked')->count();
        $publishedTotal = Story::where('is_published', true)->count() + Film::where('is_published', true)->count();

        return [
            Stat::make('New Enquiries', $newEnquiries)
                ->description($newEnquiries > 0 ? 'Pending client leads' : 'No new leads')
                ->descriptionIcon('heroicon-o-envelope')
                ->icon('heroicon-o-envelope')
                ->color($newEnquiries > 0 ? 'warning' : 'gray')
                ->url(EnquiryResource::getUrl('index')),

            Stat::make('Wedding Stories', $totalStories)
                ->description('Photo & hybrid albums')
                ->descriptionIcon('heroicon-o-camera')
                ->icon('heroicon-o-camera')
                ->color('primary')
                ->url(StoryResource::getUrl('index')),

            Stat::make('Cinematic Films', $totalFilms)
                ->description('Wedding cinema & teasers')
                ->descriptionIcon('heroicon-o-video-camera')
                ->icon('heroicon-o-video-camera')
                ->color('danger')
                ->url(FilmResource::getUrl('index')),

            Stat::make('Confirmed Bookings', $bookedEvents)
                ->description('Booked wedding events')
                ->descriptionIcon('heroicon-o-check-badge')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->url(EnquiryResource::getUrl('index')),

            Stat::make('Published Works', $publishedTotal)
                ->description('Live on website')
                ->descriptionIcon('heroicon-o-globe-alt')
                ->icon('heroicon-o-globe-alt')
                ->color('info')
                ->url(url('/')),
        ];
    }
}