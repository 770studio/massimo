<?php

namespace App\Filament\Company\Resources\TaskResource\Pages;

use App\Filament\Company\Resources\TaskResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Todo' => Tab::make('Todo')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereNull('completed'))
            ,
            'Completed' => Tab::make('Completed')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereNotNull('completed')),
        ];
    }
}
