<?php

namespace App\Helpers\FormFields;

use Filament\Forms\Components\Component;

abstract class AbstractFormField
{
    protected string $content;
    protected bool $required;
    protected string $code;
    protected bool $checked;

    public function __construct(string $content, bool $required, bool $checked)
    {
        $this->content = $content;
        $this->required = $required;
        $this->checked = $checked;
        $this->code = md5($content . $required);
    }


    abstract public function build(string $key): Component;
}
