<?php

namespace App\Admin\Filament\Resources\ApiKeyResource\Pages;

use App\Admin\Filament\Resources\ApiKeyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateApiKey extends CreateRecord
{
    protected static string $resource = ApiKeyResource::class;
}
