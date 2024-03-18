<?php

namespace App\Filament\Resources\SampleResource\Pages;

use App\Filament\Resources\SampleResource;
use App\Models\Sample;
use App\Models\Specimen;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditSample extends EditRecord
{
    protected static string $resource = SampleResource::class;

    public function getBreadcrumbs(): array
    {
        $sample = Sample::withTrashed()->find($this->data['id']);

        return [
            '/investigators/' . $sample->bioSample->project->investigator->id . '/edit' => $sample->bioSample->project->investigator->name,
            '/projects/' . $sample->bioSample->project->id . '/edit' => $sample->bioSample->project->name,
            '/bio-samples/' . $sample->bioSample->id . '/edit' => $sample->bioSample->label,
            '/samples' => 'Sample',
            '/samples/' . $sample->id . '/edit' => 'Edit'
        ];
    }

    public function getHeading(): string|Htmlable
    {
        return 'Edit Sample ' . $this->data['uid'];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
