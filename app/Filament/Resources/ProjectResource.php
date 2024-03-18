<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use App\Traits\HasFullTextSearch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    use HasFullTextSearch;

    protected static ?string $recordTitleAttribute = 'model_title';

    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getFormSchema());
    }

    public static function getFormSchema($withCreateForms = true): array
    {
        return [
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\Select::make('investigator_id')
                ->required()
                ->name('Investigator')
                ->searchable()
                ->relationship('investigator', 'name')
                ->preload()
                ->createOptionForm($withCreateForms ? InvestigatorResource::getFormSchema() : null)->createOptionModalHeading('Add Investigator')
                ->disablePlaceholderSelection(),
            Forms\Components\FileUpload::make('attachment')->openable()->columnSpan(2)->acceptedFileTypes([
                'text/plain',
                'application/pdf',
                'application/msword',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
                'application/vnd.ms-word.document.macroEnabled.12',
                'application/vnd.ms-word.template.macroEnabled.12',
                'application/vnd.ms-excel',
                'application/vnd.ms-excel',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
                'application/vnd.ms-excel.sheet.macroEnabled.12',
                'application/vnd.ms-excel.template.macroEnabled.12',
                'application/vnd.ms-excel.addin.macroEnabled.12',
                'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
                'application/vnd.ms-powerpoint',
                'application/vnd.ms-powerpoint',
                'application/vnd.ms-powerpoint',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'application/vnd.openxmlformats-officedocument.presentationml.template',
                'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
                'application/vnd.ms-powerpoint.addin.macroEnabled.12',
                'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
                'application/vnd.ms-powerpoint.template.macroEnabled.12',
                'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
            ]),
            Forms\Components\MarkdownEditor::make('description')
                ->label('Goals/Desired Outcomes')
                ->columnSpan(2)
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uid')->label('UID')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('investigator.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('Investigator')->relationship('investigator', 'name'),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
