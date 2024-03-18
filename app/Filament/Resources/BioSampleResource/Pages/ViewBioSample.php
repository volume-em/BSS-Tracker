<?php

namespace App\Filament\Resources\BioSampleResource\Pages;

use App\Filament\Resources\BioSampleResource;
use App\Models\BioSample;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewBioSample extends ViewRecord
{
    protected static string $resource = BioSampleResource::class;

    public function getBreadcrumbs(): array
    {
        $bioSample = BioSample::withTrashed()->find($this->data['id']);

        return [
            '/investigators/' . $bioSample->project->investigator->id => $bioSample->project->investigator->name,
            '/projects/' . $bioSample->project->id => $bioSample->project->name,
            '/bio-samples' => 'Bio Sample',
            '/bio-samples/' . $bioSample->id => 'View'
        ];
    }

    public function getHeading(): string|Htmlable
    {
        return 'View Bio Sample ' . $this->data['uid'];
    }
}
