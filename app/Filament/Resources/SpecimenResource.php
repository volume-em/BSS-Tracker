<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpecimenResource\Pages;
use App\Models\Sample;
use App\Models\Specimen;
use App\Traits\HasFullTextSearch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Yepsua\Filament\Forms\Components\Rating;

class SpecimenResource extends Resource
{
    use HasFullTextSearch;

    protected static ?string $model = Specimen::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'model_title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sample_id')
                    ->name('Sample')
                    ->searchable()
                    ->relationship('sample', 'label')
                    ->getOptionLabelFromRecordUsing(fn (Model $model) => $model->uid . ' : ' . $model->label)
                    ->searchable(['uid', 'label'])
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, $old, Forms\Set $set) {
                        $set('label', Sample::find($state)->label);
                    })
                    ->createOptionForm(
                        SampleResource::getFormSchema()
                    )->createOptionModalHeading('Add Sample')
                    ->disablePlaceholderSelection()
                    ->columnSpan(2)
                    ->required(),

                Forms\Components\DateTimePicker::make('date')
                    ->native(false)
                    ->displayFormat('m/d/Y')
                    ->columnSpan(2)
                    ->label('Start date of specimen prep')
                    ->columnSpan(1)
                    ->required(),

                Forms\Components\Select::make('logger_name_id')
                    ->name('Logged By')
                    ->searchable()
                    ->relationship('loggerName', 'name')
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                    ])->createOptionModalHeading('Add Logger')
                    ->disablePlaceholderSelection()
                    ->columnSpan(1)
                    ->required(),

                Forms\Components\TextInput::make('label')
                    ->columnSpan(2),

                Forms\Components\Select::make('substrate_type_id')
                    ->columnSpan(1)
                    ->name('Type')
                    ->label('Substrate Type')
                    ->searchable()
                    ->relationship('substrateType', 'type')
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('Type')->required()
                    ])->createOptionModalHeading('Add Type')
                    ->disablePlaceholderSelection()
                    ->required(),

                Forms\Components\TextInput::make('type_note')->label('Substrate Type Note')->columnSpan(1),

                Forms\Components\Select::make('location_id')
                    ->columnSpan(1)
                    ->label('Storage')
                    ->searchable()
                    ->relationship('location', 'location')
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('location')
                    ])->createOptionModalHeading('Add Storage')
                    ->disablePlaceholderSelection()
                    ->required(),

                Forms\Components\TextInput::make('location')->columnSpan(1),

                Forms\Components\TextInput::make('location_note')->columnSpan(2),

                Forms\Components\TextInput::make('section_thickness')
                    ->numeric()
                    ->alpha(false)
                    ->suffix('nm')
                    ->columnSpan(1),

                Forms\Components\Select::make('imaged')->options([true => 'Yes', false => 'No'])->columnSpan(1),

                Forms\Components\Select::make('imaging_approach_id')
                    ->columnSpan(1)
                    ->name('Imaging Approach')
                    ->label('Imaging Approach')
                    ->searchable()
                    ->relationship('imagingApproach', 'imaging_approach')
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('imaging_approach')
                    ])->createOptionModalHeading('Add Imaging Approach')
                    ->disablePlaceholderSelection(),

                Forms\Components\TextInput::make('imaging_approach_note')->columnSpan(1),

                Forms\Components\RichEditor::make('notes')
                    ->columnSpan(2),

                Rating::make('quality')
                    ->options([
                        'Unusable',
                        'Acceptable',
                        'Good',
                        'Very Good',
                        'Excellent'
                    ])
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uid')->label('UID'),
                Tables\Columns\TextColumn::make('substrateType.type')->label('Substrate Type'),
                Tables\Columns\TextColumn::make('fullLocation')->label('Location'),
                Tables\Columns\TextColumn::make('sample.label'),
                Tables\Columns\TextColumn::make('created_at')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('Sample')->relationship('sample', 'uid'),
                Tables\Filters\SelectFilter::make('Logger Name')->relationship('loggerName', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\BulkActionGroup::make([

                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('exportAllAsJson')
                        ->label(__('Export All'))
                        ->action(function (Collection $records) {
                            $archive = new \ZipArchive;
                            $archive->open('specimens.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                            foreach ($records as $record) {
                                $name = \Str::slug($record->uid, '_') . '.json';
                                $return = $record->load('sample.bioSample.project.investigator')->toArray();
                                $content = json_encode($return, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
                                $archive->addFromString($name, $content);
                            }
                            $archive->close();
                            return response()->download('specimens.zip');
                        })
                        ->deselectRecordsAfterCompletion()
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
            'index' => Pages\ListSpecimens::route('/'),
            'create' => Pages\CreateSpecimen::route('/create'),
            'view' => Pages\ViewSpecimen::route('/{record}'),
            'edit' => Pages\EditSpecimen::route('/{record}/edit'),
        ];
    }
}
