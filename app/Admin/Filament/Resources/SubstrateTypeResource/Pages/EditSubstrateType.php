<?php

namespace App\Admin\Filament\Resources\SubstrateTypeResource\Pages;

use App\Admin\Filament\Resources\SubstrateTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubstrateType extends EditRecord
{
    protected static string $resource = SubstrateTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
