<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 24px;">

        <!-- TOP HEALTH METRICS STATS -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
            <div style="background: rgba(18, 24, 38, 0.7); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
                <span style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #94a3b8; font-weight: 600;">Overall SEO Health</span>
                <div style="font-size: 38px; font-weight: bold; color: {{ $auditStats['health_score'] >= 80 ? '#10b981' : ($auditStats['health_score'] >= 60 ? '#f59e0b' : '#ef4444') }}; margin: 8px 0;">
                    {{ $auditStats['health_score'] }}%
                </div>
                <span style="font-size: 12px; color: #64748b;">Based on {{ $auditStats['total_evaluated'] }} evaluated pages</span>
            </div>

            <div style="background: rgba(18, 24, 38, 0.7); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
                <span style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #94a3b8; font-weight: 600;">Issues Breakdown</span>
                <div style="display: flex; gap: 12px; margin: 10px 0;">
                    <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 6px; padding: 4px 10px; font-size: 13px; font-weight: 700; color: #ef4444;">
                        {{ $auditStats['critical_count'] }} Critical
                    </div>
                    <div style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 6px; padding: 4px 10px; font-size: 13px; font-weight: 700; color: #f59e0b;">
                        {{ $auditStats['warning_count'] }} Warnings
                    </div>
                </div>
                <span style="font-size: 12px; color: #64748b;">Automated Multi-Point Diagnostic</span>
            </div>

            <div style="background: rgba(18, 24, 38, 0.7); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
                <span style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #94a3b8; font-weight: 600;">Active 301 Redirects</span>
                <div style="font-size: 38px; font-weight: bold; color: #38bdf8; margin: 8px 0;">
                    {{ $auditStats['total_redirects'] }}
                </div>
                <span style="font-size: 12px; color: #64748b;">{{ $auditStats['total_redirect_hits'] }} total link clicks preserved</span>
            </div>

            <div style="background: rgba(18, 24, 38, 0.7); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
                <span style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #94a3b8; font-weight: 600;">Search Engine Crawlers</span>
                <div style="display: flex; gap: 8px; margin-top: 12px;">
                    <a href="{{ $auditStats['sitemap_url'] }}" target="_blank" style="background: rgba(196,164,114,0.2); border: 1px solid #c4a472; color: #c4a472; padding: 6px 12px; border-radius: 6px; font-size: 11px; letter-spacing: 1px; text-decoration: none; font-weight: 600;">
                        sitemap.xml ↗
                    </a>
                    <a href="{{ $auditStats['robots_url'] }}" target="_blank" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.2); color: #fff; padding: 6px 12px; border-radius: 6px; font-size: 11px; letter-spacing: 1px; text-decoration: none;">
                        robots.txt ↗
                    </a>
                </div>
                <span style="font-size: 12px; color: #10b981; margin-top: 8px;">✓ Automatically synced & verified</span>
            </div>
        </div>

        <!-- SEO OPPORTUNITIES ENGINE -->
        <div style="background: rgba(18, 24, 38, 0.7); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div>
                    <h3 style="font-size: 18px; font-weight: 600; color: #fff; margin: 0;">🚀 SEO Opportunities Engine</h3>
                    <p style="font-size: 13px; color: #94a3b8; margin-top: 4px;">High-impact rankings & content gap actions prioritized by commercial intent.</p>
                </div>
                <button wire:click="runAuditNow" type="button" style="background: #c4a472; color: #000; border: none; padding: 10px 18px; border-radius: 8px; font-size: 12.5px; font-weight: 600; cursor: pointer;">
                    ⚡ Run Full SEO Audit Now
                </button>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px;">
                @foreach($opportunities as $opp)
                    <div style="background: rgba(10, 14, 24, 0.8); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <span style="font-size: 10.5px; font-weight: 700; letter-spacing: 1px; color: {{ $opp['badge_color'] === 'danger' ? '#ef4444' : '#f59e0b' }};">
                                    {{ $opp['priority'] }}
                                </span>
                                <span style="font-size: 11px; color: #94a3b8;">{{ $opp['type'] }}</span>
                            </div>
                            <h4 style="font-size: 15px; font-weight: 600; color: #fff; margin: 0 0 8px;">{{ $opp['title'] }}</h4>
                            <p style="font-size: 12.5px; color: #94a3b8; line-height: 1.6; margin: 0 0 16px;">{{ $opp['detail'] }}</p>
                        </div>
                        <a href="{{ $opp['action_url'] }}" style="display: block; text-align: center; background: #27272a; border: 1px solid #3f3f46; color: #38bdf8; text-decoration: none; padding: 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                            {{ $opp['action_label'] }} →
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- SEARCH CONSOLE INTEGRATION STATUS -->
        <div style="background: rgba(18, 24, 38, 0.7); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <span style="font-size: 28px;">📊</span>
                <div>
                    <h4 style="font-size: 15px; font-weight: 600; color: #fff; margin: 0;">Google Search Console Status</h4>
                    <p style="font-size: 12.5px; color: #94a3b8; margin: 4px 0 0;">
                        @if($searchConsoleConfigured)
                            <span style="color: #22c55e;">● Connected</span> — Live clicks, impressions, and query tracking active.
                        @else
                            <span style="color: #eab308;">● API Setup Standby</span> — Add <code>GOOGLE_SEARCH_CONSOLE_KEY_PATH</code> in <code>.env</code> to stream live query data.
                        @endif
                    </p>
                </div>
            </div>
            <div>
                <a href="/admin/content-refresh" style="background: #27272a; border: 1px solid #3f3f46; color: #fff; text-decoration: none; padding: 8px 16px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                    Content Refresh Tool →
                </a>
            </div>
        </div>

        <!-- AUDIT RECOMMENDATIONS & 1-CLICK FIXES -->
        <div style="background: rgba(18, 24, 38, 0.7); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 24px;">
            <div style="margin-bottom: 20px;">
                <h3 style="font-size: 18px; font-weight: 600; color: #fff; margin: 0;">Detected Issues & Diagnostics</h3>
                <p style="font-size: 13px; color: #94a3b8; margin-top: 4px;">Items below are missing specific metadata or have thin content.</p>
            </div>

            @if(empty($itemsWithIssues))
                <div style="text-align: center; padding: 30px; color: #10b981;">
                    ✓ Brilliant! All public pages have custom high-fidelity SEO metadata.
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach($itemsWithIssues as $item)
                        <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(10, 14, 24, 0.8); border: 1px solid rgba(255, 255, 255, 0.06); padding: 14px 18px; border-radius: 8px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: {{ $item['severity'] === 'critical' ? '#ef4444' : ($item['severity'] === 'warning' ? '#f59e0b' : '#38bdf8') }};"></span>
                                <div>
                                    <div style="font-size: 14px; font-weight: 600; color: #fff;">{{ $item['item_title'] }}</div>
                                    <div style="font-size: 12px; color: #94a3b8;">{{ $item['item_type'] }} · {{ $item['message'] }}</div>
                                </div>
                            </div>
                            @if(!empty($item['edit_url']))
                                <a href="{{ $item['edit_url'] }}" style="background: #c4a472; color: #080a0f; font-weight: 600; font-size: 11px; letter-spacing: 1px; text-transform: uppercase; text-decoration: none; padding: 6px 14px; border-radius: 6px;">
                                    Fix SEO →
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- ACTIVE 301 REDIRECTS AUDIT -->
        @if(!empty($redirects))
            <div style="background: rgba(18, 24, 36, 0.7); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 24px;">
                <h3 style="font-size: 18px; font-weight: 600; color: #fff; margin-bottom: 15px;">Active 301 Permanent Redirects (Slug Change Protection)</h3>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.1); color: #94a3b8;">
                                <th style="padding: 10px 12px;">Old Legacy URL</th>
                                <th style="padding: 10px 12px;">New Live Destination</th>
                                <th style="padding: 10px 12px;">Status</th>
                                <th style="padding: 10px 12px;">Visitors Protected</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($redirects as $r)
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.04); color: #d1d5db;">
                                    <td style="padding: 10px 12px; font-family: monospace; color: #f87171;">{{ $r['source_path'] }}</td>
                                    <td style="padding: 10px 12px; font-family: monospace; color: #34d399;">{{ $r['target_path'] }}</td>
                                    <td style="padding: 10px 12px;"><span style="background: rgba(16,185,129,0.2); color: #10b981; padding: 2px 8px; border-radius: 4px; font-size: 11px;">HTTP {{ $r['status_code'] }}</span></td>
                                    <td style="padding: 10px 12px; font-weight: bold; color: #fff;">{{ $r['hits'] }} clicks</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</x-filament-panels::page>
