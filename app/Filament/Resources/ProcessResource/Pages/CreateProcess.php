<?php

namespace App\Filament\Resources\ProcessResource\Pages;

use App\Filament\Resources\ProcessResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateProcess extends CreateRecord
{
    protected static string $resource = ProcessResource::class;

    protected function handleRecordCreation(array $data): Model
    {

        $data['user_id'] = Auth::id();
        return parent::handleRecordCreation($data);


    }
}
