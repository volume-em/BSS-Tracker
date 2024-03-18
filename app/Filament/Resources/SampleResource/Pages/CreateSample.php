<?php

namespace App\Filament\Resources\SampleResource\Pages;

use App\Filament\Resources\SampleResource;
use App\Models\BioSample;
use App\Models\Sample;
use Filament\Actions;
// use App\Filament\CreateRecord;
use App\Filament\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateSample extends CreateRecord
{
    protected static string $resource = SampleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $bioSample = BioSample::withTrashed()->find($data['bio_sample_id']);
        $samplesCount = Sample::withTrashed()->where('bio_sample_id', $data['bio_sample_id'])->count();
        $samplesCount = $samplesCount > 0 ? $samplesCount + 1 : 1;

        $uid = $bioSample->uid . '_' . $samplesCount;

        $data['uid'] = $uid;

        return $data;
    }
}
