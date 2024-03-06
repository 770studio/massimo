<?php

namespace App\Helpers\FormFields;



use Illuminate\Support\HtmlString;

class Paragraph extends AbstractFormField
{


    public function build(string $key): Placeholder
    {
        return Placeholder::make('')
            ->content(new HtmlString($this->content));

    }
}
