<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\TaskExecutionResource\Pages\CreateTaskExecution;
use App\Filament\Company\Resources\TaskExecutionResource\Pages\EditTaskExecution;
use App\Filament\Company\Resources\TaskExecutionResource\Pages\ListTaskExecutions;
use App\Filament\Resources\TaskExecutionResource\Pages;
use App\Models\Task;
use App\Models\TaskExecution;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TaskExecutionResource extends Resource
{
    protected static ?string $model = TaskExecution::class;

    //protected static ?string $navigationLabel = 'Start task';
    //protected static ?string $title = "Rekening";

    //protected static ?string $label = 'Start task';

    protected static ?string $slug = 'task-executions';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {

        $task = Task::find(request('task'));

        return $form
            ->schema(


                [
                    Section::make(
                        $task->buildForm()->toArray()
                    ),
                    Checkbox::make('status'),

                    Placeholder::make('created_at')
                        ->label('Created Date')
                        ->content(fn(?TaskExecution $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                    Placeholder::make('updated_at')
                        ->label('Last Modified Date')
                        ->content(fn(?TaskExecution $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id'),

                TextColumn::make('company_id'),

                TextColumn::make('task_id'),

                TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTaskExecutions::route('/'),
            'create' => CreateTaskExecution::route('/create'),
            'edit' => EditTaskExecution::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }


}
