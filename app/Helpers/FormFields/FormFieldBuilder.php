<?php

namespace App\Helpers\FormFields;

use App\Enums\FormFieldType;
use Filament\Forms\Components\Component;
use Illuminate\Support\Str;

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

    public function build(?array $execution_data = []): Component
    {
        $content = $this->prepareContent((array)$execution_data);
        /** @var AbstractFormField $entity */
        $entity = new ("\App\Helpers\FormFields\\" . Str::studly($this->type->value))($content, $this->required);
        return $entity->build();


    }

    private function prepareContent(array $execution_data): string
    {
        return Str::replace(array_keys($execution_data), array_values($execution_data), $this->content);
    }
}
