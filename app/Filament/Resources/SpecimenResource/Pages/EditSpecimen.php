<?php

namespace App\Filament\Resources\SpecimenResource\Pages;

use App\Filament\Resources\SpecimenResource;
use App\Models\Specimen;
use App\Services\SpecimenExport;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

class EditSpecimen extends EditRecord
{
    protected static string $resource = SpecimenResource::class;

    protected static string $view = 'filament.resources.specimen.edit';

    public function getBreadcrumbs(): array
    {
        $specimen = Specimen::withTrashed()->find($this->data['id']);

        return [
            '/investigators/' . $specimen->sample->bioSample->project->investigator->id . '/edit' => $specimen->sample->bioSample->project->investigator->name,
            '/projects/' . $specimen->sample->bioSample->project->id . '/edit' => $specimen->sample->bioSample->project->name,
            '/bio-samples/' . $specimen->sample->bioSample->id . '/edit' => $specimen->sample->bioSample->label,
            '/samples/' . $specimen->sample->id . '/edit' => $specimen->sample->label,
            '/specimens' => 'Specimens',
            '/specimens/' . $specimen->id . '/edit' => 'Edit'
        ];
    }

    public function getHeading(): string|Htmlable
    {
        return 'Edit Specimen ' . $this->data['uid'];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
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
