<?php

namespace App\Filament\Company\Resources\ApiTokenResource\Pages;

use App\Filament\Company\Resources\ApiTokenResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateProcess extends CreateRecord
{
    protected static string $resource = ApiTokenResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $data['user_id'] = Auth::id();
        return parent::handleRecordCreation($data);


    }
}
