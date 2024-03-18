<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class VersionCheck extends Widget
{
    protected static ?int $sort = -4;
    protected static string $view = 'filament.widgets.version-check';
    protected int | string | array $columnSpan = 'full';
}
