<?php

namespace App\Helpers\FormFields;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Component;

class Task extends AbstractFormField
{

    public function build(string $key): Component
    {

        return Checkbox::make($key)
            ->label($this->content)
            ->formatStateUsing(fn() => $this->checked)
            ->required($this->required);
    }
}
