<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SampleResource\Pages;
use App\Filament\Resources\SampleResource\RelationManagers;
use App\Livewire\FileUpload;
use App\Models\BioSample;
use App\Models\Sample;
use App\Traits\HasFullTextSearch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Livewire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Query\Builder;

class SampleResource extends Resource
{
    use HasFullTextSearch;

    protected static ?string $model = Sample::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'model_title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                self::getFormSchema()
            )->columns(2);
    }

    public static function getFormSchema()
    {
        return [
            Forms\Components\Select::make('bio_sample_id')
                ->name('Bio Sample')
                ->required()
                ->searchable()
                ->relationship('bioSample', 'label')
                ->getOptionLabelFromRecordUsing(fn (Model $model) => $model->uid . ' : ' . $model->label)
                ->searchable(['uid', 'label'])
                ->preload()
                ->live()
                ->afterStateUpdated(function ($state, $old, Forms\Set $set) {
                    $set('label', BioSample::withTrashed()->find($state)->label);
                })
                ->columnSpan(2)
                ->createOptionForm(
                    BioSampleResource::getFormSchema()
                )->createOptionModalHeading('Add Bio Sample')
                ->disablePlaceholderSelection(),

            Forms\Components\DateTimePicker::make('start_date')
                ->required()
                ->native(false)
                ->displayFormat('m/d/Y')
                ->columnSpan(2)
                ->label('Start date of sample prep in lab'),

            FileUpload::make('protocol')->openable()->columnSpan(2)
                ->removeUploadedFileButtonPosition('right')
                ->deletable(fn ($livewire) => get_class($livewire) === 'App\Filament\Resources\SampleResource\Pages\EditSample'),

            Forms\Components\Select::make('logger_name_id')
                ->columnSpan(2)
                ->name('Logged By')
                ->required()
                ->searchable()
                ->relationship('loggerName', 'name')
                ->preload()
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')
                ])->createOptionModalHeading('Add Sample')
                ->disablePlaceholderSelection(),

            Forms\Components\Select::make('location_id')
                ->required()
                ->columnSpan(1)
                ->name('Storage')
                ->label('Storage')
                ->searchable()
                ->relationship('location', 'location')
                ->preload()
                ->createOptionForm([
                    Forms\Components\TextInput::make('location')
                        ->label('Storage')
                ])->createOptionModalHeading('Add Storage')
                ->disablePlaceholderSelection(),

            Forms\Components\TextInput::make('location')->columnSpan(1),

            Forms\Components\TextInput::make('location_note')->columnSpan(2),

            Forms\Components\TextInput::make('label')
                ->columnSpan(1),

            Forms\Components\Select::make('exhausted')->options([true => 'Yes', false => 'No'])->columnSpan(1),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uid')->label('UID'),
                Tables\Columns\TextColumn::make('label'),
                Tables\Columns\TextColumn::make('fullLocation')->label('Location'),
                Tables\Columns\TextColumn::make('bioSample.label'),
                Tables\Columns\TextColumn::make('exhausted')->alignCenter()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '0' => 'No',
                        default => 'Yes'
                    }),
                Tables\Columns\TextColumn::make('created_at')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('Bio Sample')->relationship('bioSample', 'label'),
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
            'index' => Pages\ListSamples::route('/'),
            'create' => Pages\CreateSample::route('/create'),
            'view' => Pages\ViewSample::route('/{record}'),
            'edit' => Pages\EditSample::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
