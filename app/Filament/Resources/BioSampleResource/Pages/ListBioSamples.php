<?php

namespace App\Filament\Resources\BioSampleResource\Pages;

use App\Filament\Resources\BioSampleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBioSamples extends ListRecords
{
    protected static string $resource = BioSampleResource::class;

    protected ?string $subheading = 'Refers to a (typically live) substance to which a sample preparation protocol, starting from first fixation, is applied.';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
