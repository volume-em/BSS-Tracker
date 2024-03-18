<?php

namespace App\Admin\Filament\Resources;

use App\Admin\Filament\Resources\UserResource\Pages;
use App\Mail\PasswordReset;
use App\Models\PasswordResetToken;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('email')->required(),
                Forms\Components\Select::make('role')->options([
                    '1' => 'User',
                    '2' => 'Administrator'
                ])->default('1')->columnSpan(2)->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('resetPassword')
                    ->requiresConfirmation()
                    ->modalDescription('An email will be sent to this user with password reset instructions')
                    ->color('danger')
                    ->action(function (Model $record) {
                        PasswordResetToken::where('email', $record->email)->delete();
                        $passwordResetToken = PasswordResetToken::create(['email' => $record->email, 'token' => Str::random(64)]);

                        Mail::to($record)->send(new PasswordReset($record, $passwordResetToken));
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
