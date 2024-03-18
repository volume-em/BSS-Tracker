<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
// // use App\Filament\CreateRecord;
use App\Filament\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $statement = DB::select("SHOW TABLE STATUS LIKE 'projects'");

        $next_id = $statement[0]->Auto_increment;

        $uid = env('PROJECT_PREFIX') . $next_id;

        $data['uid'] = $uid;

        return $data;
    }
}
