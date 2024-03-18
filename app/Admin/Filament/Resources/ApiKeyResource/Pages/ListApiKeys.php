<?php

namespace App\Admin\Filament\Resources\ApiKeyResource\Pages;

use App\Admin\Filament\Resources\ApiKeyResource;
use App\Models\ApiKey;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Str;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class ListApiKeys extends ListRecords
{
    protected static string $resource = ApiKeyResource::class;

    protected $apiKey = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create-api-key')
                ->form([
                    TextInput::make('api_key')
                        ->label('API Key')
                        ->readOnly()
                ])
                ->modalSubmitAction(false)
                ->modalCancelAction(fn (Actions\StaticAction $action) => $action->label('Close'))
                ->modalDescription('Please copy this API key to a safe place, it will only be shown once!')
                ->label('Create API Key')
                ->mountUsing(function (Form $form) {
                    $apiKey = new ApiKey();

                    $apiKey->created_by = auth()->user()->id;
                    $apiKey->api_key = Str::random(64);

                    $apiKey->save();

                    $form->fill($apiKey->toArray());
                })
        ];
    }
}
