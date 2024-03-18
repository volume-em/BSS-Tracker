<?php

namespace App\Admin\Filament\Resources\ImagingApproachResource\Pages;

use App\Admin\Filament\Resources\ImagingApproachResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImagingApproaches extends ListRecords
{
    protected static string $resource = ImagingApproachResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
