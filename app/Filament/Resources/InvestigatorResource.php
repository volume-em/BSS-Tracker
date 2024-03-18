<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvestigatorResource\Pages;
use App\Filament\Resources\InvestigatorResource\RelationManagers;
use App\Models\Investigator;
use App\Traits\HasFullTextSearch;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvestigatorResource extends Resource
{
    use HasFullTextSearch;

    protected static ?string $model = Investigator::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'model_title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getFormSchema())->columns(6);
    }

    public static function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('first_name')->required()->columnSpan(2),
            Forms\Components\TextInput::make('middle_initial')->columnSpan(2),
            Forms\Components\TextInput::make('last_name')->required()->columnSpan(2),
            Forms\Components\TextInput::make('institute')->required()->columnSpan(3),
            Forms\Components\TextInput::make('email')->email()->unique(ignorable: fn ($record) => $record)->required()->columnSpan(3),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('last_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('institute')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListInvestigators::route('/'),
            'create' => Pages\CreateInvestigator::route('/create'),
            'view' => Pages\ViewInvestigator::route('/{record}'),
            'edit' => Pages\EditInvestigator::route('/{record}/edit'),
        ];
    }
}
