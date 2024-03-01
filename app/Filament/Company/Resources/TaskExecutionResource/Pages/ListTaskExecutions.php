<?php

namespace App\Filament\Company\Resources\TaskExecutionResource\Pages;

use App\Filament\Company\Resources\TaskExecutionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTaskExecutions extends ListRecords
{
    protected static string $resource = TaskExecutionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
