<?php

namespace App\Filament\Company\Resources\ApiTokenResource\Pages;

use App\Filament\Company\Resources\ApiTokenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProcess extends EditRecord
{
    protected static string $resource = ApiTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
