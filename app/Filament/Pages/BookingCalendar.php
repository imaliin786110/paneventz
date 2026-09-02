<?php

namespace App\Filament\Pages;

use App\Models\BlockedDate;
use App\Models\Enquiry;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;

class BookingCalendar extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;
    protected static ?string $navigationLabel = 'Wedding Calendar';
    protected static ?string $title = 'Studio Wedding & Availability Calendar';
    protected static string|UnitEnum|null $navigationGroup = 'Bookings & Leads';
    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.booking-calendar';

    public string $currentMonth;
    public ?string $checkDate = null;
    public ?array $checkResult = null;

    public ?string $blockDate = null;
    public ?string $blockTitle = null;
    public ?string $blockNotes = null;

    public function mount(): void
    {
        $this->currentMonth = Carbon::now()->format('Y-m');
        $this->checkDate = Carbon::now()->format('Y-m-d');
        $this->checkAvailability();
    }

    public function prevMonth(): void
    {
        $this->currentMonth = Carbon::createFromFormat('Y-m', $this->currentMonth)->subMonth()->format('Y-m');
    }

    public function nextMonth(): void
    {
        $this->currentMonth = Carbon::createFromFormat('Y-m', $this->currentMonth)->addMonth()->format('Y-m');
    }

    public function currentMonthReset(): void
    {
        $this->currentMonth = Carbon::now()->format('Y-m');
    }

    public function checkAvailability(): void
    {
        if (!$this->checkDate) {
            $this->checkResult = null;
            return;
        }

        $date = $this->checkDate;

        // Check confirmed bookings
        $booked = Enquiry::where('status', 'booked')
            ->whereDate('wedding_date', $date)
            ->first();

        if ($booked) {
            $this->checkResult = [
                'status' => 'booked',
                'message' => "🔴 BOOKED: Wedding for {$booked->name} in " . ($booked->wedding_location ?: 'TBD') . " ({$booked->service}).",
            ];
            return;
        }

        // Check blocked dates
        $blocked = BlockedDate::whereDate('date', $date)->first();
        if ($blocked) {
            $this->checkResult = [
                'status' => 'blocked',
                'message' => "⛔ BLOCKED / UNAVAILABLE: {$blocked->title}" . ($blocked->notes ? " ({$blocked->notes})" : ''),
            ];
            return;
        }

        // Check pending leads
        $pending = Enquiry::whereIn('status', ['new', 'contacted', 'meeting_scheduled'])
            ->whereDate('wedding_date', $date)
            ->get();

        if ($pending->count() > 0) {
            $names = $pending->pluck('name')->join(', ');
            $this->checkResult = [
                'status' => 'pending',
                'message' => "🟡 PENDING INQUIRY: {$pending->count()} inquiry on this date ({$names}). Not yet booked.",
            ];
            return;
        }

        $formatted = Carbon::parse($date)->format('l, d F Y');
        $this->checkResult = [
            'status' => 'available',
            'message' => "🟢 AVAILABLE: The studio is fully free on {$formatted}!",
        ];
    }

    public function saveBlockDate(): void
    {
        $this->validate([
            'blockDate'  => 'required|date',
            'blockTitle' => 'required|string|max:255',
            'blockNotes' => 'nullable|string|max:500',
        ]);

        BlockedDate::create([
            'date'  => $this->blockDate,
            'title' => $this->blockTitle,
            'notes' => $this->blockNotes,
        ]);

        $this->blockDate = null;
        $this->blockTitle = null;
        $this->blockNotes = null;

        $this->dispatch('close-modal', id: 'block-date-modal');
        $this->checkAvailability();
    }

    public function deleteBlock(int $id): void
    {
        BlockedDate::destroy($id);
        $this->checkAvailability();
    }

    public function getViewData(): array
    {
        $startOfMonth = Carbon::createFromFormat('Y-m', $this->currentMonth)->startOfMonth();
        $endOfMonth = (clone $startOfMonth)->endOfMonth();

        // Query confirmed bookings for this month
        $bookedEnquiries = Enquiry::where('status', 'booked')
            ->whereBetween('wedding_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->get()
            ->groupBy(fn ($item) => $item->wedding_date ? $item->wedding_date->format('Y-m-d') : '');

        // Query pending inquiries for this month
        $pendingEnquiries = Enquiry::whereIn('status', ['new', 'contacted', 'meeting_scheduled'])
            ->whereBetween('wedding_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->get()
            ->groupBy(fn ($item) => $item->wedding_date ? $item->wedding_date->format('Y-m-d') : '');

        // Query blocked dates for this month
        $blockedDates = BlockedDate::whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->get()
            ->groupBy(fn ($item) => $item->date ? $item->date->format('Y-m-d') : '');

        // Upcoming 5 confirmed weddings
        $upcomingBookings = Enquiry::where('status', 'booked')
            ->where('wedding_date', '>=', Carbon::today())
            ->orderBy('wedding_date')
            ->take(6)
            ->get();

        return [
            'monthTitle'       => $startOfMonth->format('F Y'),
            'startOfMonth'     => $startOfMonth,
            'daysInMonth'      => $startOfMonth->daysInMonth,
            'firstDayOfWeek'   => $startOfMonth->dayOfWeekIso, // 1 for Monday .. 7 for Sunday
            'bookedEnquiries'  => $bookedEnquiries,
            'pendingEnquiries' => $pendingEnquiries,
            'blockedDates'     => $blockedDates,
            'upcomingBookings' => $upcomingBookings,
            'allBlocked'       => BlockedDate::orderBy('date', 'desc')->take(10)->get(),
        ];
    }
}