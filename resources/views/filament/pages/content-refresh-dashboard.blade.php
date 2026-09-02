<x-filament-panels::page>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px;">
        
        <!-- CANDIDATES LIST -->
        <div style="background: rgba(24, 24, 27, 0.7); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding-bottom: 12px;">
                <div>
                    <h3 style="font-size: 16px; font-weight: 600; color: #fff; margin: 0;">Refresh Candidates</h3>
                    <p style="font-size: 12px; color: #a1a1aa; margin: 2px 0 0;">Published articles that may need freshness or depth updates.</p>
                </div>
                <button wire:click="refreshList" type="button" style="background: #27272a; border: 1px solid #3f3f46; color: #fff; padding: 6px 12px; border-radius: 6px; font-size: 12px; cursor: pointer;">
                    🔄 Re-scan
                </button>
            </div>

            @if(empty($candidates))
                <div style="text-align: center; padding: 50px 20px; color: #71717a;">
                    <div style="font-size: 32px; margin-bottom: 8px;">✨</div>
                    <p style="font-size: 13px;">All published articles are currently up to date!</p>
                </div>
            @else
                <div style="display: grid; gap: 14px;">
                    @foreach($candidates as $item)
                        <div style="background: #18181b; border: 1px solid {{ $analyzingPostId === $item['id'] ? '#c4a472' : 'rgba(255, 255, 255, 0.06)' }}; border-radius: 8px; padding: 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                                <h4 style="font-size: 14px; font-weight: 600; color: #fff; margin: 0; max-width: 80%;">{{ $item['title'] }}</h4>
                                <span style="font-size: 11px; color: #c4a472; background: rgba(196, 164, 114, 0.1); padding: 2px 8px; border-radius: 12px;">{{ $item['word_count'] }} words</span>
                            </div>

                            <div style="font-size: 11.5px; color: #eab308; margin-bottom: 12px;">
                                @foreach($item['reasons'] as $r)
                                    <div>• {{ $r }}</div>
                                @endforeach
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 10px;">
                                <span style="font-size: 11px; color: #71717a;">Updated: {{ $item['days_since_update'] }}d ago</span>
                                <div style="display: flex; gap: 8px;">
                                    <button wire:click="analyzePost({{ $item['id'] }})" type="button" style="background: #c4a472; color: #000; border: none; padding: 6px 12px; border-radius: 6px; font-size: 11.5px; font-weight: 600; cursor: pointer;">
                                        ✨ AI Optimize
                                    </button>
                                    <a href="{{ $item['edit_url'] }}" style="background: #27272a; color: #fff; border: 1px solid #3f3f46; padding: 6px 12px; border-radius: 6px; font-size: 11.5px; text-decoration: none;">
                                        Edit ↗
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- AI SUGGESTIONS PANEL -->
        <div style="background: rgba(24, 24, 27, 0.7); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 24px;">
            <div style="margin-bottom: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding-bottom: 12px;">
                <h3 style="font-size: 16px; font-weight: 600; color: #fff; margin: 0;">AI Recommendations</h3>
                <p style="font-size: 12px; color: #a1a1aa; margin: 2px 0 0;">Actionable suggestions to improve search ranking and engagement.</p>
            </div>

            @if($activeSuggestions)
                <div style="display: grid; gap: 16px;">
                    <div>
                        <label style="font-size: 11.5px; color: #a1a1aa; font-weight: 600; text-transform: uppercase;">Improved SEO Title</label>
                        <div style="background: #18181b; border: 1px solid #3f3f46; border-radius: 6px; padding: 10px 12px; font-size: 13px; color: #fff; margin-top: 4px;">
                            {{ $activeSuggestions['improved_title'] }}
                        </div>
                    </div>

                    <div>
                        <label style="font-size: 11.5px; color: #a1a1aa; font-weight: 600; text-transform: uppercase;">Improved Meta Description</label>
                        <div style="background: #18181b; border: 1px solid #3f3f46; border-radius: 6px; padding: 10px 12px; font-size: 13px; color: #fff; margin-top: 4px;">
                            {{ $activeSuggestions['improved_meta_description'] }}
                        </div>
                    </div>

                    <div>
                        <label style="font-size: 11.5px; color: #a1a1aa; font-weight: 600; text-transform: uppercase;">Suggested Section Expansion</label>
                        <div style="background: #18181b; border: 1px solid #3f3f46; border-radius: 6px; padding: 12px; font-size: 13px; color: #e4e4e7; margin-top: 4px;">
                            <strong style="color: #c4a472; display: block; margin-bottom: 6px;">{{ $activeSuggestions['suggested_new_section_heading'] }}</strong>
                            {!! $activeSuggestions['suggested_new_section_content'] !!}
                        </div>
                    </div>

                    <div>
                        <label style="font-size: 11.5px; color: #a1a1aa; font-weight: 600; text-transform: uppercase;">Internal Linking Opportunities</label>
                        <ul style="background: #18181b; border: 1px solid #3f3f46; border-radius: 6px; padding: 12px 12px 12px 28px; margin: 4px 0 0; font-size: 12.5px; color: #38bdf8;">
                            @foreach($activeSuggestions['internal_linking_recommendations'] as $rec)
                                <li style="margin-bottom: 4px;">{{ $rec }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div style="margin-top: 10px;">
                        <a href="/admin/blog-posts/{{ $analyzingPostId }}/edit" style="display: block; text-align: center; background: #22c55e; color: #000; padding: 12px; border-radius: 8px; font-weight: 600; font-size: 13px; text-decoration: none;">
                            Apply Changes in Blog Editor →
                        </a>
                    </div>
                </div>
            @else
                <div style="text-align: center; padding: 70px 20px; color: #71717a;">
                    <div style="font-size: 32px; margin-bottom: 8px;">💡</div>
                    <p style="font-size: 13px;">Select an article from the left and click <strong>AI Optimize</strong> to view customized recommendations.</p>
                </div>
            @endif
        </div>

    </div>
</x-filament-panels::page>
