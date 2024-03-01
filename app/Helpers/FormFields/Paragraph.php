<?php

namespace App\Helpers\FormFields;


use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;

class Paragraph extends AbstractFormField
{


    public function build(): Placeholder
    {
        return Placeholder::make('')
            ->content(new HtmlString($this->content));

    }
}
