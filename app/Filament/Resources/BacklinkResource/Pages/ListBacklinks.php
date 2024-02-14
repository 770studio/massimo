<?php

namespace App\Filament\Resources\BacklinkResource\Pages;

use App\Filament\Resources\BacklinkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBacklinks extends ListRecords
{
    protected static string $resource = BacklinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
