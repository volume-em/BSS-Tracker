<?php

namespace App\Filament\Pages;

use App\Models\Investigator;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static string $view = 'dashboard';

    protected function getViewData(): array
    {
        try {
            $data = Investigator::with('projects.bioSamples.samples.specimens')->get();
        } catch (\ErrorException $e) {
            $data = collect([]);
        }

        return ['data' => $data];
    }
}
