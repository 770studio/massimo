<?php

namespace App\Helpers\FormFields;

use App\Enums\FormFieldType;

class FormFieldBuilder
{

    private FormFieldType $type;
    private bool $required;
    private string $content;

    public function __construct(array $conf)
    {
        $this->type = FormFieldType::from($conf['type']);
        $this->content = data_get($conf, 'data.content', '');
        $this->required = data_get($conf, 'data.is_required', false);

    }

    public function build()
    {

        return $this->type->getField($this->content, $this->required);

    }

}
