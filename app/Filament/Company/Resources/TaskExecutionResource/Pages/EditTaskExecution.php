<?php

namespace App\Filament\Company\Resources\TaskExecutionResource\Pages;

use App\Filament\Company\Resources\TaskExecutionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTaskExecution extends EditRecord
{
    protected static string $resource = TaskExecutionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
