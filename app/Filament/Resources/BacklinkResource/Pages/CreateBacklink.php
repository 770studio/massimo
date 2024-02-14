<?php

namespace App\Filament\Resources\BacklinkResource\Pages;

use App\Filament\Resources\BacklinkResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBacklink extends CreateRecord
{
    protected static string $resource = BacklinkResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
