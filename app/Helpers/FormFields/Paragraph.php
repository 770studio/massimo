<?php

namespace App\Helpers\FormFields;



use Illuminate\Support\HtmlString;

class Paragraph extends AbstractFormField
{


    public function build(): Placeholder
    {
        return Placeholder::make('content')
            ->content(new HtmlString($this->content));

    }
}
