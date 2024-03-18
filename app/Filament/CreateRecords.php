<?php

namespace App\Filament;

use Filament\Actions\Action;
use Filament\Support\Exceptions\Halt;

class CreateRecords extends \Filament\Resources\Pages\CreateRecord
{
    protected function getCreateAndClone(): Action
    {
        return Action::make('create')
            ->label('Create & clone')
            ->action('createAndClone')
            ->color('gray');
    }

    public function createAndClone(): void
    {
        $this->create(another: false, clone: true);
    }

    public function create(bool $another = false, bool $clone = false): void
    {
        $this->authorizeAccess();

        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeCreate($data);

            /** @internal Read the DocBlock above the following method. */
            // $this->createRecordAndCallHooks($data);
        } catch (Halt $exception) {
            return;
        }

        /** @internal Read the DocBlock above the following method. */
        $this->sendCreatedNotificationAndRedirect(shouldCreateAnotherInsteadOfRedirecting: $another, shouldCloneInsteadOfRedirecting: $clone);
    }

    protected function sendCreatedNotificationAndRedirect(bool $shouldCreateAnotherInsteadOfRedirecting = true, bool $shouldCloneInsteadOfRedirecting = false): void
    {
        $this->getCreatedNotification()?->send();

        if ($shouldCreateAnotherInsteadOfRedirecting) {
            // Ensure that the form record is anonymized so that relationships aren't loaded.
            $this->form->model($this->getRecord()::class);
            $this->record = null;

            $this->fillForm();

            return;
        }

        if (! $shouldCloneInsteadOfRedirecting) {
            $this->redirect($this->getRedirectUrl());
        }
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCreateAndClone(),
            ...(static::canCreateAnother() ? [$this->getCreateAnotherFormAction()] : []),
            $this->getCancelFormAction(),
        ];
    }
}
