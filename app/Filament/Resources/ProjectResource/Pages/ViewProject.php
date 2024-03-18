<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    public function getBreadcrumbs(): array
    {
        $project = Project::withTrashed()->find($this->data['id']);

        return [
            '/investigators/' . $project->investigator->id => $project->investigator->name,
            '/projects' => 'Projects',
            '/projects/' . $project->id => 'View'
        ];
    }

    public function getHeading(): string|Htmlable
    {
        return 'View Project ' . $this->data['uid'];
    }
}
