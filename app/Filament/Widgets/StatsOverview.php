<?php

namespace App\Filament\Widgets;

use App\Models\BioSample;
use App\Models\Investigator;
use App\Models\Project;
use App\Models\Sample;
use App\Models\Specimen;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 2;

    protected function getColumns(): int
    {
        return 5;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Investigators', Investigator::count())->url('/investigators'),
            Stat::make('Projects', Project::count())->url('/projects'),
            Stat::make('Bio Samples', BioSample::count())->url('/bio-samples'),
            Stat::make('Samples', Sample::count())->url('/samples'),
            Stat::make('Specimens', Specimen::count())->url('/specimens'),
        ];
    }
}
