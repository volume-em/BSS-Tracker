<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class BssInfo extends Widget
{
    protected static string $view = 'filament.widgets.bss-info';

    public function getColumnSpan(): int|string|array
    {
        return 1;
    }
}
