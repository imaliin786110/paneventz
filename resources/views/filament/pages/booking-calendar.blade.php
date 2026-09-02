<x-filament-panels::page>
    @php
        $data = $this->getViewData();
    @endphp

    <!-- AVAILABILITY CHECKER BAR -->
    <div style="background: #18181b; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 24px; margin-bottom: 24px;">
        <h3 style="font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
            <span>🔍</span> Rapid Wedding Date Availability Checker
        </h3>
        <div style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap;">
            <input 
                type="date" 
                wire:model.live="checkDate" 
                wire:change="checkAvailability"
                style="background: #27272a; border: 1px solid rgba(255,255,255,0.15); color: #fff; padding: 10px 14px; border-radius: 8px; font-size: 14px; outline: none;"
            >
            <button 
                type="button" 
                wire:click="checkAvailability" 
                style="background: #c4a472; color: #18181b; font-weight: 600; padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-size: 13px;">
                Check Date
            </button>
            <div style="font-size: 14px; font-weight: 500; padding: 10px 16px; border-radius: 8px; flex: 1; min-width: 260px;
                {{ ($this->checkResult['status'] ?? '') === 'available' ? 'background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.3);' : '' }}
                {{ ($this->checkResult['status'] ?? '') === 'booked' ? 'background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3);' : '' }}
                {{ ($this->checkResult['status'] ?? '') === 'pending' ? 'background: rgba(234, 179, 8, 0.15); color: #facc15; border: 1px solid rgba(234,179,8,0.3);' : '' }}
                {{ ($this->checkResult['status'] ?? '') === 'blocked' ? 'background: rgba(168, 85, 247, 0.15); color: #c084fc; border: 1px solid rgba(168,85,247,0.3);' : '' }}
            ">
                {{ $this->checkResult['message'] ?? 'Select a date above to check studio schedule.' }}
            </div>
        </div>
    </div>

    <!-- CALENDAR HEADER & CONTROLS -->
    <div style="background: #18181b; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <button type="button" wire:click="prevMonth" style="background: #27272a; color: #fff; border: 1px solid rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 13px;">
                    &larr; Prev
                </button>
                <h2 style="font-size: 22px; font-weight: 700; color: #fff; min-width: 180px; text-align: center;">
                    {{ $data['monthTitle'] }}
                </h2>
                <button type="button" wire:click="nextMonth" style="background: #27272a; color: #fff; border: 1px solid rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 13px;">
                    Next &rarr;
                </button>
                <button type="button" wire:click="currentMonthReset" style="background: transparent; color: #a1a1aa; border: 1px solid rgba(255,255,255,0.1); padding: 8px 14px; border-radius: 6px; cursor: pointer; font-size: 12px;">
                    Today
                </button>
            </div>

            <!-- LEGEND -->
            <div style="display: flex; gap: 16px; align-items: center; font-size: 12px; color: #a1a1aa; flex-wrap: wrap;">
                <span style="display: inline-flex; align-items: center; gap: 6px;">
                    <span style="width: 10px; height: 10px; border-radius: 50%; background: #22c55e;"></span> Confirmed Wedding
                </span>
                <span style="display: inline-flex; align-items: center; gap: 6px;">
                    <span style="width: 10px; height: 10px; border-radius: 50%; background: #eab308;"></span> Pending Inquiry
                </span>
                <span style="display: inline-flex; align-items: center; gap: 6px;">
                    <span style="width: 10px; height: 10px; border-radius: 50%; background: #a855f7;"></span> Blocked / Travel
                </span>
            </div>
        </div>

        <!-- 7-DAY CALENDAR GRID -->
        <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; text-align: center; margin-bottom: 8px;">
            @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $dayName)
                <div style="padding: 10px 0; font-size: 12px; font-weight: 600; letter-spacing: 1px; color: #71717a; text-transform: uppercase;">
                    {{ $dayName }}
                </div>
            @endforeach
        </div>

        <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px;">
            {{-- Empty cells before the 1st day of the month --}}
            @for($i = 1; $i < $data['firstDayOfWeek']; $i++)
                <div style="background: rgba(255,255,255,0.02); min-height: 90px; border-radius: 8px; border: 1px dashed rgba(255,255,255,0.04);"></div>
            @endfor

            {{-- Month days --}}
            @for($day = 1; $day <= $data['daysInMonth']; $day++)
                @php
                    $dateString = $data['startOfMonth']->copy()->addDays($day - 1)->format('Y-m-d');
                    $isToday = ($dateString === \Carbon\Carbon::now()->format('Y-m-d'));
                    $booked = $data['bookedEnquiries']->get($dateString, collect());
                    $pending = $data['pendingEnquiries']->get($dateString, collect());
                    $blocked = $data['blockedDates']->get($dateString, collect());
                @endphp

                <div style="background: #27272a; min-height: 95px; border-radius: 8px; padding: 8px; border: 1px solid {{ $isToday ? '#c4a472' : 'rgba(255,255,255,0.06)' }}; display: flex; flex-direction: column; justify-content: space-between;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 13px; font-weight: {{ $isToday ? '700' : '500' }}; color: {{ $isToday ? '#c4a472' : '#e4e4e7' }};">
                            {{ $day }}
                        </span>
                        @if($isToday)
                            <span style="font-size: 9px; letter-spacing: 1px; background: rgba(196,164,114,0.2); color: #c4a472; padding: 1px 6px; border-radius: 10px;">TODAY</span>
                        @endif
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 4px; margin-top: 6px;">
                        {{-- Confirmed Bookings --}}
                        @foreach($booked as $b)
                            <div style="background: rgba(34, 197, 94, 0.2); border-left: 3px solid #22c55e; color: #4ade80; font-size: 11px; padding: 2px 6px; border-radius: 4px; text-align: left; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $b->name }} ({{ $b->wedding_location }})">
                                🟢 {{ $b->name }}
                            </div>
                        @endforeach

                        {{-- Pending Inquiries --}}
                        @foreach($pending as $p)
                            <div style="background: rgba(234, 179, 8, 0.2); border-left: 3px solid #eab308; color: #fde047; font-size: 11px; padding: 2px 6px; border-radius: 4px; text-align: left; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $p->name }} ({{ $p->wedding_location }})">
                                🟡 {{ $p->name }}
                            </div>
                        @endforeach

                        {{-- Blocked Dates --}}
                        @foreach($blocked as $blk)
                            <div style="background: rgba(168, 85, 247, 0.2); border-left: 3px solid #a855f7; color: #d8b4fe; font-size: 11px; padding: 2px 6px; border-radius: 4px; text-align: left; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $blk->title }}">
                                ⛔ {{ $blk->title }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <!-- LOWER SECTION: BLOCK A DATE + UPCOMING WEDDINGS -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 24px;">
        <!-- BLOCK A DATE -->
        <div style="background: #18181b; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 24px;">
            <h3 style="font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <span>⛔</span> Block Date / Travel Days
            </h3>
            <form wire:submit.prevent="saveBlockDate">
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; color: #a1a1aa; margin-bottom: 6px;">Select Date to Block *</label>
                    <input type="date" wire:model="blockDate" style="width: 100%; background: #27272a; border: 1px solid rgba(255,255,255,0.15); color: #fff; padding: 10px 14px; border-radius: 8px; font-size: 14px;" required>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; color: #a1a1aa; margin-bottom: 6px;">Reason / Title *</label>
                    <input type="text" wire:model="blockTitle" placeholder="e.g. Travel to Udaipur Destination Wedding" style="width: 100%; background: #27272a; border: 1px solid rgba(255,255,255,0.15); color: #fff; padding: 10px 14px; border-radius: 8px; font-size: 14px;" required>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12px; color: #a1a1aa; margin-bottom: 6px;">Notes (Optional)</label>
                    <input type="text" wire:model="blockNotes" placeholder="Flight details, team notes..." style="width: 100%; background: #27272a; border: 1px solid rgba(255,255,255,0.15); color: #fff; padding: 10px 14px; border-radius: 8px; font-size: 14px;">
                </div>
                <button type="submit" style="background: #a855f7; color: #fff; font-weight: 600; padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-size: 13px;">
                    Block This Date
                </button>
            </form>

            @if($data['allBlocked']->count() > 0)
                <div style="margin-top: 24px;">
                    <h4 style="font-size: 13px; font-weight: 600; color: #a1a1aa; margin-bottom: 10px;">Recently Blocked Dates:</h4>
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        @foreach($data['allBlocked'] as $b)
                            <div style="display: flex; justify-content: space-between; align-items: center; background: #27272a; padding: 8px 12px; border-radius: 6px; font-size: 12px;">
                                <span><strong>{{ $b->date->format('d M Y') }}</strong> — {{ $b->title }}</span>
                                <button type="button" wire:click="deleteBlock({{ $b->id }})" style="background: transparent; border: none; color: #f87171; cursor: pointer;">✕</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- UPCOMING CONFIRMED WEDDINGS -->
        <div style="background: #18181b; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 24px;">
            <h3 style="font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <span>💍</span> Upcoming Confirmed Celebrations
            </h3>

            @forelse($data['upcomingBookings'] as $ub)
                <div style="background: #27272a; border-left: 4px solid #22c55e; padding: 14px; border-radius: 6px; margin-bottom: 10px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h4 style="font-size: 14px; font-weight: 600; color: #fff;">{{ $ub->name }}</h4>
                        <span style="font-size: 12px; color: #4ade80; font-weight: 600;">
                            {{ $ub->wedding_date ? $ub->wedding_date->format('d M Y') : 'Date TBD' }}
                        </span>
                    </div>
                    <div style="font-size: 12px; color: #a1a1aa; margin-top: 4px;">
                        📍 {{ $ub->wedding_location ?: 'Venue TBD' }} · {{ $ub->service ?: 'Full Coverage' }}
                    </div>
                    @if($ub->phone)
                        <div style="font-size: 11px; color: #71717a; margin-top: 4px;">
                            📞 {{ $ub->phone }}
                        </div>
                    @endif
                </div>
            @empty
                <div style="text-align: center; color: #71717a; padding: 40px 0; font-size: 13px;">
                    No upcoming confirmed weddings recorded yet.<br>
                    Change an inquiry's status to <strong>"Booked & Confirmed"</strong> to see it here!
                </div>
            @endforelse
        </div>
    </div>
</x-filament-panels::page>