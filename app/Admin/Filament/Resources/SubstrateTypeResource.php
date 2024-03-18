<?php

namespace App\Admin\Filament\Resources;

use App\Admin\Filament\Resources\SubstrateTypeResource\Pages;
use App\Admin\Filament\Resources\SubstrateTypeResource\RelationManagers;
use App\Models\SubstrateType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubstrateTypeResource extends Resource
{
    protected static ?string $model = SubstrateType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('type'),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSubstrateTypes::route('/'),
            'create' => Pages\CreateSubstrateType::route('/create'),
            'edit' => Pages\EditSubstrateType::route('/{record}/edit'),
        ];
    }
}
