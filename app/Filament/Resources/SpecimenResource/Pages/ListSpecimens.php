<?php

namespace App\Filament\Resources\SpecimenResource\Pages;

use App\Filament\Resources\SpecimenResource;
use App\Models\Specimen;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Collection;

class ListSpecimens extends ListRecords
{
    protected static string $resource = SpecimenResource::class;

    protected ?string $subheading = 'Refers to the substance that is introduced into the microscope, typically after trimming, sectioning and mounting on a substrate.';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportAllAsJson')
                ->label(__('Export All'))
                ->action(function () {
                    $records = Specimen::all();

                    $archive = new \ZipArchive;

                    $archive->open('specimens.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                    foreach ($records as $record) {
                        $name = \Str::slug($record->uid, '_') . '.json';
                        $return = $record->load('sample.bioSample.project.investigator')->toArray();
                        $content = json_encode($return, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
                        $archive->addFromString($name, $content);
                    }

                    $archive->close();

                    return response()->download('specimens.zip');
                })->hidden(fn() => Specimen::count() === 0),

            Actions\CreateAction::make(),
        ];
    }
}
