<?php

namespace App\Admin\Filament\Resources\LoggerNameResource\Pages;

use App\Admin\Filament\Resources\LoggerNameResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoggerNames extends ListRecords
{
    protected static string $resource = LoggerNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
