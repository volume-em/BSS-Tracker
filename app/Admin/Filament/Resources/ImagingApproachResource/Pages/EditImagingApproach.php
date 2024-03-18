<?php

namespace App\Admin\Filament\Resources\ImagingApproachResource\Pages;

use App\Admin\Filament\Resources\ImagingApproachResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImagingApproach extends EditRecord
{
    protected static string $resource = ImagingApproachResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
