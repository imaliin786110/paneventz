<div class="space-y-6 text-sm">
    <!-- Quick Parameters Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 p-4 rounded-lg bg-gray-900/60 border border-white/10 text-xs">
        <div>
            <span class="text-gray-400 block">Payment Ratio:</span>
            <span class="font-semibold text-amber-400">{{ $terms->advance_percentage }}% Advance / {{ $terms->balance_percentage }}% Balance</span>
        </div>
        <div>
            <span class="text-gray-400 block">Balance Due:</span>
            <span class="font-semibold text-gray-200">{{ $terms->balance_due }}</span>
        </div>
        <div>
            <span class="text-gray-400 block">Advance Refundable:</span>
            <span class="font-semibold {{ $terms->advance_refundable ? 'text-emerald-400' : 'text-rose-400' }}">
                {{ $terms->advance_refundable ? 'Yes' : 'No (Strictly Non-refundable)' }}
            </span>
        </div>
        <div>
            <span class="text-gray-400 block">Delivery Turnaround:</span>
            <span class="font-semibold text-gray-200">{{ $terms->estimated_delivery_period }}</span>
        </div>
        <div>
            <span class="text-gray-400 block">Extended Hours:</span>
            <span class="font-semibold text-gray-200">After {{ $terms->extended_coverage_after }} ({{ $terms->extra_hours }})</span>
        </div>
        <div>
            <span class="text-gray-400 block">Terms Version:</span>
            <span class="font-semibold text-amber-400">v{{ $terms->version }}</span>
        </div>
    </div>

    <!-- Rendered Legal Content -->
    <div class="p-6 rounded-lg bg-gray-950/80 border border-white/10 text-gray-300 max-h-[60vh] overflow-y-auto leading-relaxed">
        {!! \App\Services\TermsService::renderHtml($terms) !!}
    </div>
</div>
