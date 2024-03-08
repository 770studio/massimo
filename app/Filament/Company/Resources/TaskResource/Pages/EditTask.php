<?php

namespace App\Filament\Company\Resources\TaskResource\Pages;

use App\Filament\Company\Resources\TaskResource;
use App\Models\Task;
use Auth;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;
    protected static ?string $breadcrumb = 'execution';
    protected static ?string $title = 'Task execution';
    protected static ?string $slug = 'task-execution';
    private bool $_completed = true;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {

        /** @var Task $record */
        $record->execution_data = $data;
        if ($this->_completed && !$record->completed) {
            $record->completed = 1;
            $record->completed_at = now();
        }

        // task is still unassigned
        if (!$record->assigned_to) {
            $record->assigned_to = Auth::id();
        }
        
        $record->save();
        return $record;
    }


    protected function getFormActions(): array
    {
        #TODO mb try modal + headeractions
        /*        Action::make('save')
                    ->label(__('Complete'))
                    ->requiresConfirmation()
                    ->action(fn() =>     $this->data['completed'] = true )*/
        return !$this->getRecord()->completed
            ? [
                Action::make('Save')
                    ->action('SaveState'),

                /*                $this->getSaveFormAction()
                                     ->label('Complete'),*/
                Action::make('Complete')
                    ->submit('save'),

                $this->getCancelFormAction(),
            ] : [];
    }

    public function SaveState()
    {

        $this->_completed = false;
        // $this->data['completed'] = 0;
        $this->save();
    }


}
