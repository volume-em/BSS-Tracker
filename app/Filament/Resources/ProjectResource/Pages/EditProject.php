<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\BioSample;
use App\Models\Project;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    public function getBreadcrumbs(): array
    {
        $project = Project::withTrashed()->find($this->data['id']);

        return [
            '/investigators/' . $project->investigator->id . '/edit' => $project->investigator->name,
            '/projects' => 'Projects',
            '/projects/' . $project->id . '/edit' => 'Edit'
        ];
    }

    public function getHeading(): string|Htmlable
    {
        return 'Edit Project ' . $this->data['uid'];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
