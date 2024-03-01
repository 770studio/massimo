<?php

namespace App\Helpers\FormFields;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Component;

class Task extends AbstractFormField
{

    public function build(): Component
    {
        return Checkbox::make($this->content)
            ->required($this->required);
    }
}
