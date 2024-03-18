<?php

namespace App\Admin\Filament\Resources\UserResource\Pages;

use App\Admin\Filament\Resources\UserResource;
use App\Mail\PasswordReset;
use App\Models\PasswordResetToken;
use App\Models\Project;
use Filament\Actions;
use Filament\Forms\Get;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = Str::random(64);

        return $data;
    }

    public function afterCreate(): void
    {
        $passwordResetToken = PasswordResetToken::create(['email' => $this->record->email, 'token' => Str::random(64)]);

        Mail::to($this->record)->send(new PasswordReset($this->record, $passwordResetToken));
    }
}
