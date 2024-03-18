<?php

namespace App\Livewire;

use App\Models\PasswordResetToken;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;

class ResetPassword extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = ['expired' => false];
    public PasswordResetToken $passwordResetToken;

    public function mount(): void
    {

        $this->form->fill($this->data + ['passwordResetToken' => $this->passwordResetToken->token]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->confirmed()
                    ->minLength(6),
                TextInput::make('password_confirmation')
                    ->password()
                    ->required()
                    ->label('Confirm Password')
            ])
            ->statePath('data');
    }

    public function create()
    {
        $passwordResetToken = PasswordResetToken::where('token', $this->data['passwordResetToken'])->firstOrFail();

        $user = User::where('email', $passwordResetToken->email)->firstOrFail();

        $user->password = $this->data['password'];

        $user->save();

        PasswordResetToken::where('token', $this->data['passwordResetToken'])->delete();

        return redirect()->to('/login');
    }

    public function render()
    {
        if (now()->greaterThan($this->passwordResetToken->created_at->addMinutes(30))) {
            $this->data['expired'] = true;
        }

        return view('livewire.reset-password', $this->data);
    }
}
