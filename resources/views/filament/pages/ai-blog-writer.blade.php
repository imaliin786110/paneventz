<x-filament-panels::page>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(420px, 1fr)); gap: 24px;">
        
        <!-- LEFT: WEDDING INPUTS -->
        <div style="background: rgba(24, 24, 27, 0.7); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 24px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding-bottom: 12px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 20px;">💍</span>
                    <h3 style="font-size: 16px; font-weight: 600; color: #fff; margin: 0;">Wedding & Event Data</h3>
                </div>
                <span style="font-size: 11px; letter-spacing: 1px; text-transform: uppercase; background: rgba(196, 164, 114, 0.15); color: #c4a472; border: 1px solid rgba(196, 164, 114, 0.3); padding: 4px 10px; border-radius: 20px;">
                    {{ $aiProvider }}
                </span>
            </div>

            <div style="display: grid; gap: 16px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 500; color: #a1a1aa; margin-bottom: 6px;">Couple Name(s) *</label>
                    <input type="text" wire:model="couple_name" placeholder="e.g. Ali & Sana" style="width: 100%; background: #18181b; border: 1px solid #3f3f46; border-radius: 8px; color: #fff; padding: 10px 14px; font-size: 14px;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 500; color: #a1a1aa; margin-bottom: 6px;">Wedding Venue</label>
                        <input type="text" wire:model="venue" placeholder="e.g. Taj Lands End" style="width: 100%; background: #18181b; border: 1px solid #3f3f46; border-radius: 8px; color: #fff; padding: 10px 14px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 500; color: #a1a1aa; margin-bottom: 6px;">City / Destination</label>
                        <input type="text" wire:model="city" placeholder="e.g. Mumbai, Bandra" style="width: 100%; background: #18181b; border: 1px solid #3f3f46; border-radius: 8px; color: #fff; padding: 10px 14px; font-size: 14px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 500; color: #a1a1aa; margin-bottom: 6px;">Wedding Type / Rituals</label>
                        <input type="text" wire:model="wedding_type" placeholder="e.g. Muslim Wedding / Nikah" style="width: 100%; background: #18181b; border: 1px solid #3f3f46; border-radius: 8px; color: #fff; padding: 10px 14px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 500; color: #a1a1aa; margin-bottom: 6px;">Event Date (Optional)</label>
                        <input type="date" wire:model="event_date" style="width: 100%; background: #18181b; border: 1px solid #3f3f46; border-radius: 8px; color: #fff; padding: 10px 14px; font-size: 14px;">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 500; color: #a1a1aa; margin-bottom: 6px;">Services Used</label>
                    <input type="text" wire:model="services" placeholder="Wedding Photography + Cinematic Videography" style="width: 100%; background: #18181b; border: 1px solid #3f3f46; border-radius: 8px; color: #fff; padding: 10px 14px; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 500; color: #a1a1aa; margin-bottom: 6px;">Target SEO Keyword (Optional)</label>
                    <input type="text" wire:model="target_keyword" placeholder="e.g. Taj Lands End wedding photography Mumbai" style="width: 100%; background: #18181b; border: 1px solid #3f3f46; border-radius: 8px; color: #fff; padding: 10px 14px; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 500; color: #a1a1aa; margin-bottom: 6px;">Verified Notes & Nuances (No fake claims will be generated)</label>
                    <textarea wire:model="custom_notes" rows="3" placeholder="Special moments: seaside sunset couple portraits, royal velvet bridal lehenga, emotional nikah rituals..." style="width: 100%; background: #18181b; border: 1px solid #3f3f46; border-radius: 8px; color: #fff; padding: 10px 14px; font-size: 14px;"></textarea>
                </div>

                <div style="margin-top: 10px;">
                    <button wire:click="generate" wire:loading.attr="disabled" type="button" style="width: 100%; background: linear-gradient(135deg, #c4a472 0%, #a6844b 100%); color: #000; border: none; padding: 14px; border-radius: 8px; font-weight: 600; font-size: 14px; letter-spacing: 0.5px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <span wire:loading.remove>✨ Generate AI Wedding Blog & SEO Metadata</span>
                        <span wire:loading>⚡ Crafting Editorial Story & SEO...</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- RIGHT: GENERATED ARTICLE & SEO PREVIEW -->
        <div style="background: rgba(24, 24, 27, 0.7); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 24px;">
            @if($isGenerated)
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding-bottom: 12px;">
                    <div>
                        <span style="font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; color: #c4a472;">DRAFT PREVIEW</span>
                        <h2 style="font-size: 18px; font-weight: 600; color: #fff; margin: 4px 0 0;">{{ $generatedTitle }}</h2>
                    </div>

                    <!-- QUALITY SCORE BADGE -->
                    <div style="text-align: right;">
                        <span style="font-size: 11px; color: #a1a1aa;">Quality Score</span>
                        <div style="font-size: 18px; font-weight: 700; color: {{ $qualityScore >= 80 ? '#22c55e' : ($qualityScore >= 60 ? '#eab308' : '#ef4444') }};">
                            {{ $qualityScore }}%
                        </div>
                    </div>
                </div>

                @if(!empty($qualityWarnings))
                    <div style="background: rgba(234, 179, 8, 0.12); border: 1px solid rgba(234, 179, 8, 0.3); border-radius: 8px; padding: 12px; margin-bottom: 16px;">
                        <div style="font-weight: 600; font-size: 12px; color: #eab308; margin-bottom: 4px;">Quality Audit Feedback:</div>
                        <ul style="margin: 0; padding-left: 18px; font-size: 12px; color: #fef08a;">
                            @foreach($qualityWarnings as $w)
                                <li>{{ $w }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- EDITABLE ARTICLE CONTENT -->
                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 12px; font-weight: 500; color: #a1a1aa; margin-bottom: 6px;">Article Title</label>
                    <input type="text" wire:model="generatedTitle" style="width: 100%; background: #18181b; border: 1px solid #3f3f46; border-radius: 8px; color: #fff; padding: 8px 12px; font-size: 14px; margin-bottom: 12px;">

                    <label style="display: block; font-size: 12px; font-weight: 500; color: #a1a1aa; margin-bottom: 6px;">Excerpt</label>
                    <textarea wire:model="generatedExcerpt" rows="2" style="width: 100%; background: #18181b; border: 1px solid #3f3f46; border-radius: 8px; color: #fff; padding: 8px 12px; font-size: 13px; margin-bottom: 12px;"></textarea>

                    <label style="display: block; font-size: 12px; font-weight: 500; color: #a1a1aa; margin-bottom: 6px;">Article Body (HTML/Markdown)</label>
                    <textarea wire:model="generatedContent" rows="10" style="width: 100%; background: #18181b; border: 1px solid #3f3f46; border-radius: 8px; color: #e4e4e7; padding: 10px 12px; font-family: monospace; font-size: 12px; line-height: 1.6;"></textarea>
                </div>

                <!-- INTEGRATED SEO METADATA ACCORDION -->
                <div style="background: rgba(0, 0, 0, 0.4); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 14px; margin-bottom: 20px;">
                    <div style="font-size: 13px; font-weight: 600; color: #c4a472; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                        <span>🔍</span> Generated Phase 1 Polymorphic SEO Metadata
                    </div>
                    <div style="display: grid; gap: 8px; font-size: 12px;">
                        <div><strong style="color: #a1a1aa;">Focus Keyword:</strong> <span style="color: #fff;">{{ $generatedFocusKeyword }}</span></div>
                        <div><strong style="color: #a1a1aa;">SEO Title:</strong> <span style="color: #fff;">{{ $generatedSeoTitle }}</span></div>
                        <div><strong style="color: #a1a1aa;">Meta Description:</strong> <span style="color: #fff;">{{ $generatedMetaDescription }}</span></div>
                        <div><strong style="color: #a1a1aa;">URL Slug:</strong> <code style="color: #38bdf8;">/blog/{{ $generatedSlug }}</code></div>
                        <div><strong style="color: #a1a1aa;">Image Alt Text:</strong> <span style="color: #fff;">{{ $generatedAltText }}</span></div>
                    </div>
                </div>

                <!-- WORKFLOW ACTIONS -->
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <button wire:click="saveDraft" type="button" style="flex: 1; background: #27272a; color: #fff; border: 1px solid #3f3f46; padding: 12px; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer;">
                        💾 Save as Draft
                    </button>
                    <button wire:click="approveAndPublish" type="button" style="flex: 1; background: #22c55e; color: #000; border: none; padding: 12px; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer;">
                        ✓ Approve & Publish Live
                    </button>
                </div>
            @else
                <div style="text-align: center; padding: 80px 20px; color: #71717a;">
                    <div style="font-size: 40px; margin-bottom: 12px;">✨</div>
                    <h3 style="font-size: 16px; font-weight: 500; color: #d4d4d8; margin-bottom: 8px;">No Article Generated Yet</h3>
                    <p style="font-size: 13px; max-width: 320px; margin: 0 auto; line-height: 1.6;">
                        Fill in the verified couple or wedding details on the left and click <strong>Generate AI Wedding Blog</strong>.
                    </p>
                </div>
            @endif
        </div>

    </div>
</x-filament-panels::page>
