<?php

namespace App\Filament\Resources\SpecimenResource\Pages;

use App\Filament\Resources\SpecimenResource;
use App\Models\Specimen;
use App\Services\SpecimenExport;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewSpecimen extends ViewRecord
{
    protected static string $resource = SpecimenResource::class;

    public function getBreadcrumbs(): array
    {
        $specimen = Specimen::withTrashed()->find($this->data['id']);

        return [
            '/investigators/' . $specimen->sample->bioSample->project->investigator->id => $specimen->sample->bioSample->project->investigator->name,
            '/projects/' . $specimen->sample->bioSample->project->id => $specimen->sample->bioSample->project->name,
            '/bio-samples/' . $specimen->sample->bioSample->id => $specimen->sample->bioSample->label,
            '/samples/' . $specimen->sample->id => $specimen->sample->label,
            '/specimens' => 'Specimens',
            '/specimens/' . $specimen->id => 'View'
        ];
    }

    public function getHeading(): string|Htmlable
    {
        return 'View Specimen ' . $this->data['uid'];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportAsJson')
                ->label(__('Export'))
                ->action(function ($record) {
                    return (new SpecimenExport())->handle($record);
                })
                ->tooltip(__('Export'))
                ->color('primary')
        ];
    }
}
