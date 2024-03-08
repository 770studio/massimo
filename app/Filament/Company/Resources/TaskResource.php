<?php

namespace App\Filament\Company\Resources;

use App\Models\Task;
use Auth;
use Exception;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\UnauthorizedException;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $slug = 'tasks';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        /** @var Task $task */
        $task = $form->getRecord();

        if ($task->assigned_to && $task->assigned_to !== Auth::id()) {
            throw new UnauthorizedException('Task is already assigned to another user');
            // return redirect('/dashboard')->with('success', 'You have set your password successfully.');
        }


        return $form
            ->schema(
                [
                    Section::make(
                        $task->buildForm()->toArray()
                    ),

                    Placeholder::make('created_at')
                        ->label('Created Date')
                        ->content(fn(?Task $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                    Placeholder::make('updated_at')
                        ->label('Last Modified Date')
                        ->content(fn(?Task $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                    Toggle::make('completed')
                        ->hidden(
                            fn(callable $get): bool => $get('completed') == false
                        )
                        ->disabled(),


                ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                TextColumn::make('process.name')
                    ->url(fn(Task $task): string => ProcessResource::getUrl('view', ['record' => $task->process_id, 'tenant' => $task->company]))
                    ->sortable()
                    ->openUrlInNewTab(),
                TextColumn::make('assignedUser.name')
                    ->default('Unassigned'),
                TextColumn::make('execution')
                    ->default('run')
                    ->url(fn(Task $task): string => TaskResource::getUrl('edit', ['record' => $task->id]))
                    //  ->url(fn(Task $task): string => TaskExecutionResource::getUrl('create', ['task' => $task->id]))
                    ->sortable()
                    ->openUrlInNewTab(),


            ])
            ->filters([
                Filter::make('completed')
                    ->query(fn(Builder $query): Builder => $query->where('completed', true))
                    ->toggle()
            ])
            ->actions([
                //    EditAction::make(),
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
            'index' => TaskResource\Pages\ListTasks::route('/'),
            'create' => TaskResource\Pages\CreateTask::route('/create'),
            'edit' => TaskResource\Pages\EditTask::route('/{record}/execute'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return !$record->assigned_to || $record->assigned_to === Auth::id();
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    /*    public static function canView(Model $record): bool
        {
            return !$record->assigned_to || $record->assigned_to === \Auth::id();
        }*/


}
