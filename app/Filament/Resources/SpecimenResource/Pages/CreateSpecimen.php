<?php

namespace App\Filament\Resources\SpecimenResource\Pages;

use App\Filament\Resources\SpecimenResource;
use App\Models\BioSample;
use App\Models\Sample;
use App\Models\Specimen;
use Filament\Actions;
// use App\Filament\CreateRecord;
use App\Filament\CreateRecord;

class CreateSpecimen extends CreateRecord
{
    protected static string $resource = SpecimenResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $sample = Sample::find($data['sample_id']);
        $specimenCount = Specimen::where('sample_id', $data['sample_id'])->count();
        $specimenCount = $specimenCount > 0 ? $specimenCount + 1 : 1;

        $uid = $sample->uid . '_' . $specimenCount;

        $data['uid'] = $uid;

        return $data;
    }
}
