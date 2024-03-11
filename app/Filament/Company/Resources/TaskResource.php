<?php

namespace App\Filament\Company\Resources;

use App\Helpers\PlaceholderMacroHelper;
use App\Models\Task;
use Auth;
use Exception;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\UnauthorizedException;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $slug = 'tasks';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    private static ?Task $modalTask = null;

    public static function form(Form $form): Form
    {
        /** @var Task $task */
        $task = $form->getRecord();


        if ($task->assigned_to && $task->assigned_to !== Auth::id()) {
            throw new UnauthorizedException('Task is already assigned to another user');
            // return redirect('/dashboard')->with('success', 'You have set your password successfully.');
        }


        return $form
            ->schema([]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {

        $modalStaticFields = [
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
                ->disabled()

        ];
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('process.name')
                    ->formatStateUsing(fn(Task $task) => PlaceholderMacroHelper::replaceTextMacro($task->task_data, $task->process->name))
                    ->url(fn(Task $task): string => ProcessResource::getUrl('view', ['record' => $task->process_id, 'tenant' => $task->company]))
                    ->sortable()
                    ->openUrlInNewTab(),
                TextColumn::make('assignedUser.name')
                    ->default(new HtmlString('<i>Unassigned</i>')),


            ])
            ->filters([
            /*    Filter::make('completed')
                    ->query(fn(Builder $query): Builder => $query->where('completed', true))
                    ->toggle()*/
            ])
            ->actions([


                Action::make('run')
                    ->modalHeading(function (Action $action) {
                        /** @var Task $task */
                        $task = $action->getRecord();
                        return PlaceholderMacroHelper::replaceTextMacro($task->task_data, $task->process->name);
                    })
                    ->icon('heroicon-o-play-pause')
                    ->modalWidth(MaxWidth::FiveExtraLarge)
                    ->mountUsing(fn(Form $form, Task $task) => $form->fill($task->execution_data))
                    ->action(function (Task $task, array $data, array $arguments) {

                        if ($task->completed) {
                            Notification::make()
                                ->title('Task is already completed')
                                ->duration(5000)
                                ->danger()
                                ->send();
                            return;
                        }
                        $task->execution_data = $data;
                        $completed = data_get($arguments, 'completed');
                        if ($completed) {
                            $task->completed = 1;
                            $task->completed_at = now();
                        }
                        // task is still unassigned
                        if (!$task->assigned_to) {
                            $task->assigned_to = Auth::id();
                        }

                        $task->save();


                    })
                    ->modalSubmitAction(fn(StaticAction $action) => $action->label('Save'))
                    ->extraModalFooterActions(fn(Action $action): array => [
                        $action->makeModalSubmitAction('Complete', arguments: ['completed' => true]),
                    ])
                    ->form(function (Form $form, Task $task) use ($modalStaticFields) {
                        return array_merge(
                            [
                                Section::make(
                                    $task->buildForm()->toArray()
                                )
                            ]
                            , $modalStaticFields);
                    }),


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
