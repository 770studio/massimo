<?php

namespace App\Filament\Company\Resources\TaskExecutionResource\Pages;

use App\Filament\Company\Resources\TaskExecutionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTaskExecution extends CreateRecord
{
    protected static string $resource = TaskExecutionResource::class;
    protected static ?string $breadcrumb = 'Task execution';
    protected static ?string $title = 'Task execution';

    protected function getHeaderActions(): array
    {
        return [
            //  \Filament\Actions\CreateAction::make()->label('Start execution')
        ];
    }
}
