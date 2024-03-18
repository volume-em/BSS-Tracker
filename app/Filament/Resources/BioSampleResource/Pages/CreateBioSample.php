<?php

namespace App\Filament\Resources\BioSampleResource\Pages;

use App\Filament\Resources\BioSampleResource;
use App\Models\Project;
use Filament\Actions;
// // use App\Filament\CreateRecord;
use App\Filament\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateBioSample extends CreateRecord
{
    protected static string $resource = BioSampleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $biosampleCount = Project::find($data['project_id'])->bioSamples()->count();
        $biosampleCount = $biosampleCount > 0 ? $biosampleCount + 1 : 1;

        $uid = env('PROJECT_PREFIX') . $data['project_id'] . id_to_alpha($biosampleCount);

        $data['uid'] = $uid;

        return $data;
    }
}
