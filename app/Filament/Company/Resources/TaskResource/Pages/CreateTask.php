<?php

namespace App\Filament\Company\Resources\TaskResource\Pages;

use App\Filament\Company\Resources\TaskResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
