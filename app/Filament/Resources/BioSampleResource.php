<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BioSampleResource\Pages;
use App\Filament\Resources\BioSampleResource\RelationManagers;
use App\Models\BioSample;
use App\Traits\HasFullTextSearch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BioSampleResource extends Resource
{
    use HasFullTextSearch;

    protected static ?string $model = BioSample::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'model_title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                self::getFormSchema()
            );
    }

    public static function getFormSchema()
    {
        return [
            Forms\Components\Select::make('project_id')
                ->columnSpan(2)
                ->required()
                ->name('Project')
                ->searchable()
                ->relationship('project', 'name')
                ->preload()
                ->createOptionForm(
                    ProjectResource::getFormSchema()
                )->createOptionModalHeading('Add Project')
                ->disablePlaceholderSelection(),
            Forms\Components\DateTimePicker::make('received_at')->label('Date Received')->withoutTime()->native(false)->required(),
            Forms\Components\Select::make('logger_name_id')
                ->required()
                ->name('Logged By')
                ->searchable()
                ->relationship('loggerName', 'name')
                ->preload()
                ->required()
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')
                        ->required()
                ])->createOptionModalHeading('Add Logger')
                ->disablePlaceholderSelection(),
            Forms\Components\Select::make('location_id')
                ->columnSpan(1)
                ->required()
                ->name('Location')
                ->label('Storage')
                ->searchable()
                ->relationship('location', 'location')
                ->preload()
                ->createOptionForm([
                    Forms\Components\TextInput::make('location')
                        ->label('Storage')
                        ->required()
                ])->createOptionModalHeading('Add Storage')
                ->disablePlaceholderSelection(),
            Forms\Components\TextInput::make('location')->columnSpan(1),
            Forms\Components\TextInput::make('location_note')->columnSpan(2),
            Forms\Components\TextInput::make('label')->required()->columnSpan(1),
            Forms\Components\Select::make('exhausted')->options([true => 'Yes', false => 'No'])->columnSpan(1),
            Forms\Components\Textarea::make('description')->required()->columnSpan(2),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uid')->label('UID')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('label')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('fullLocation')->label('Location'),
                Tables\Columns\TextColumn::make('exhausted')->alignCenter()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '0' => 'No',
                        default => 'Yes'
                    }),
                Tables\Columns\TextColumn::make('created_at')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('Project')->relationship('project', 'name'),
                Tables\Filters\SelectFilter::make('Logger Name')->relationship('loggerName', 'name'),
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
            'index' => Pages\ListBioSamples::route('/'),
            'create' => Pages\CreateBioSample::route('/create'),
            'view' => Pages\ViewBioSample::route('/{record}'),
            'edit' => Pages\EditBioSample::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
