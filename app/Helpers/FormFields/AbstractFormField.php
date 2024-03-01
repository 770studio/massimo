<?php

namespace App\Helpers\FormFields;

use Filament\Forms\Components\Component;

abstract class AbstractFormField
{
    protected string $content;
    protected bool $required;
    protected string $code;

    public function __construct(string $content, bool $required)
    {
        $this->content = $content;
        $this->required = $required;
        $this->code = md5($content . $required);
    }

    abstract public function build(): Component;
}
