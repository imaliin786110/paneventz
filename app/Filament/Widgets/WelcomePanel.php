<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomePanel extends Widget
{
    protected static ?int $sort = -10;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.welcome-panel';
}
