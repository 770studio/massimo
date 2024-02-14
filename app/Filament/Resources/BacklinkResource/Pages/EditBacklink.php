<?php

namespace App\Filament\Resources\BacklinkResource\Pages;

use App\Filament\Resources\BacklinkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBacklink extends EditRecord
{
    protected static string $resource = BacklinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
