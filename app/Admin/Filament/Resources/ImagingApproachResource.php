<?php

namespace App\Admin\Filament\Resources;

use App\Admin\Filament\Resources\ImagingApproachResource\Pages;
use App\Admin\Filament\Resources\ImagingApproachResource\RelationManagers;
use App\Models\ImagingApproach;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ImagingApproachResource extends Resource
{
    protected static ?string $model = ImagingApproach::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('imaging_approach')
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('imaging_approach')
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
            'index' => Pages\ListImagingApproaches::route('/'),
            'create' => Pages\CreateImagingApproach::route('/create'),
            'edit' => Pages\EditImagingApproach::route('/{record}/edit'),
        ];
    }
}
