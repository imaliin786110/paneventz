<x-filament-widgets::widget>
    <section class="paneventz-welcome">
        <div class="paneventz-welcome__copy">
            <span class="paneventz-welcome__eyebrow">PANEVENTZ STUDIO</span>
            <h2>Everything for your stories, in one place.</h2>
            <p>Publish your latest celebrations, keep your portfolio current, and manage the work that represents your brand.</p>

            <div class="paneventz-welcome__actions">
                <a class="paneventz-welcome__primary" href="{{ \App\Filament\Resources\Stories\StoryResource::getUrl('create') }}">Add a new story</a>
                <a class="paneventz-welcome__secondary" href="{{ url('/') }}" target="_blank" rel="noopener noreferrer">View website</a>
            </div>
        </div>

        <div class="paneventz-welcome__accent" aria-hidden="true"><span>✦</span></div>
    </section>
</x-filament-widgets::widget>
