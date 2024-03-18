<?php

namespace App\Services;

use Illuminate\Support\Str;

class SpecimenExport
{
    public function handle($record): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $name = Str::slug($record->uid, '_');

        return response()->streamDownload(function () use ($record) {
            $record = $this->transform($record->load([
                'sample.bioSample.project.investigator'
            ]))->toArray();

            echo json_encode($record, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        }, $name . '.json');
    }

    public function transform($record)
    {
        $record = collect($record->toArray());

        return $record;
    }
}
