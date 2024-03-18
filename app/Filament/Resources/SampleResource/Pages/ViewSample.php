<?php

namespace App\Filament\Resources\SampleResource\Pages;

use App\Filament\Resources\SampleResource;
use App\Models\Sample;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewSample extends ViewRecord
{
    protected static string $resource = SampleResource::class;

    public function getBreadcrumbs(): array
    {
        $sample = Sample::withTrashed()->find($this->data['id']);

        return [
            '/investigators/' . $sample->bioSample->project->investigator->id => $sample->bioSample->project->investigator->name,
            '/projects/' . $sample->bioSample->project->id => $sample->bioSample->project->name,
            '/bio-samples/' . $sample->bioSample->id => $sample->bioSample->label,
            '/samples' => 'Sample',
            '/samples/' . $sample->id => 'View'
        ];
    }

    public function getHeading(): string|Htmlable
    {
        return 'View Sample ' . $this->data['uid'];
    }
}
