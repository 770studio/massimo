<?php

namespace App\Filament\Company\Resources\TaskResource\Pages;

use App\Filament\Company\Resources\TaskResource;
use App\Models\Task;
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
    private bool $_completed = false;

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
        $record->task_data = $data;
        if ($this->_completed && !$record->completed) {
            $record->completed = 1;
            $record->completed_at = now();
        }
        $record->save();
        return $record;
    }


    protected function getFormActions(): array
    {

        return !$this->getRecord()->completed
            ? [
                $this->getSaveFormAction(),

                Action::make('Complete')
                    ->action('Complete')
                ,

                $this->getCancelFormAction(),
            ] : [];
    }

    public function Complete()
    {
        $this->data['completed'] = true;
        $this->_completed = true;
        $this->save();
    }
}
