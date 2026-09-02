<?php

namespace App\Filament\Widgets;

use App\Models\Enquiry;
use App\Models\Film;
use App\Models\Service;
use App\Models\Story;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $newEnquiries = Enquiry::where('status', 'new')->count();
        $totalStories = Story::count();
        $totalFilms = Film::count();
        $totalServices = Service::count();
        $bookedWeddings = Enquiry::where('status', 'booked')->count();

        return [
            Stat::make('New Enquiries', $newEnquiries)
                ->description($newEnquiries > 0 ? 'Pending responses!' : 'All leads followed up')
                ->descriptionIcon($newEnquiries > 0 ? 'heroicon-o-exclamation-triangle' : 'heroicon-o-check-badge')
                ->icon('heroicon-o-inbox-arrow-down')
                ->color($newEnquiries > 0 ? 'warning' : 'success')
                ->url('/admin/enquiries'),

            Stat::make('Wedding Stories', $totalStories)
                ->description('Photo & hybrid albums')
                ->descriptionIcon('heroicon-o-camera')
                ->icon('heroicon-o-camera')
                ->color('primary')
                ->url('/admin/stories'),

            Stat::make('Cinematic Films', $totalFilms)
                ->description('Wedding cinema & teasers')
                ->descriptionIcon('heroicon-o-film')
                ->icon('heroicon-o-film')
                ->color('info')
                ->url('/admin/films'),

            Stat::make('Packages Offered', $totalServices)
                ->description('Active collections & rates')
                ->descriptionIcon('heroicon-o-sparkles')
                ->icon('heroicon-o-sparkles')
                ->color('success')
                ->url('/admin/services'),

            Stat::make('Confirmed Bookings', $bookedWeddings)
                ->description('Reserved weddings')
                ->descriptionIcon('heroicon-o-check-circle')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->url('/admin/enquiries'),
        ];
    }
}