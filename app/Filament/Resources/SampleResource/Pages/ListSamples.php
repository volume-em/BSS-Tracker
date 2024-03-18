<?php

namespace App\Filament\Resources\SampleResource\Pages;

use App\Filament\Resources\SampleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSamples extends ListRecords
{
    protected static string $resource = SampleResource::class;

    protected ?string $subheading = 'Refers to a (typically fixed, heavy metal stained and resin embedded) substance generated at the end of a sample preparation protocol, which can be stored stably for a long time under the right conditions.';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
