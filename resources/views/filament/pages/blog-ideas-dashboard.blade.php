<x-filament-panels::page>
    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 18px; font-weight: 600; color: #fff; margin: 0;">Editorial Topic Opportunities</h2>
            <p style="font-size: 13px; color: #a1a1aa; margin-top: 4px;">
                Topic gaps dynamically calculated from studio locations, collections, and seasonal Indian wedding search trends.
            </p>
        </div>
        <button wire:click="refreshIdeas" type="button" style="background: #27272a; border: 1px solid #3f3f46; color: #fff; padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <span>🔄</span> Refresh AI Ideas
        </button>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px;">
        @foreach($ideas as $index => $idea)
            <div style="background: rgba(24, 24, 27, 0.7); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <span style="font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; color: #c4a472; font-weight: 600;">
                            {{ $idea['category'] ?? 'WEDDING PHOTOGRAPHY' }}
                        </span>
                        <span style="font-size: 10.5px; background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); padding: 3px 8px; border-radius: 12px; font-weight: 600;">
                            {{ $idea['estimated_intent'] ?? 'High Intent' }}
                        </span>
                    </div>

                    <h3 style="font-size: 16px; font-weight: 600; line-height: 1.4; color: #fff; margin: 0 0 10px;">
                        {{ $idea['topic'] }}
                    </h3>

                    <div style="font-size: 12px; color: #94a3b8; line-height: 1.6; margin-bottom: 14px;">
                        {{ $idea['reasoning'] ?? '' }}
                    </div>

                    <div style="background: rgba(0,0,0,0.3); border-radius: 6px; padding: 8px 12px; margin-bottom: 16px; font-size: 11.5px;">
                        <span style="color: #a1a1aa;">Target Keyword:</span>
                        <code style="color: #38bdf8; margin-left: 4px;">{{ $idea['target_keyword'] }}</code>
                    </div>
                </div>

                <div style="display: flex; gap: 10px; border-top: 1px solid rgba(255, 255, 255, 0.08); padding-top: 14px;">
                    <a href="/admin/ai-blog-writer?topic={{ urlencode($idea['topic']) }}&target_keyword={{ urlencode($idea['target_keyword']) }}" style="flex: 1; text-align: center; background: #c4a472; color: #000; text-decoration: none; padding: 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                        ✨ Write With AI
                    </a>
                    <button wire:click="addToCalendar({{ $index }})" type="button" style="flex: 1; background: #27272a; border: 1px solid #3f3f46; color: #fff; padding: 8px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer;">
                        📅 Add to Calendar
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
