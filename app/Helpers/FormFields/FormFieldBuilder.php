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
    private bool $checked = false;

    public function __construct(array $conf)
    {
        $this->type = FormFieldType::from($conf['type']);
        $this->content = data_get($conf, 'data.content', '');
        $this->required = data_get($conf, 'data.is_required', false);

    }

    public static function taskKey($key): string
    {
        return 'task' . $key;
    }

    public function setCurrentState(bool $checked): static
    {
        $this->checked = $checked;
        return $this;
    }

    public function build(int $key, ?array $task_data = [], bool $completed = false): Component
    {

        $content = $this->prepareContent((array)$task_data);
        /** @var AbstractFormField $entity */
        $entity = new ("\App\Helpers\FormFields\\" . Str::studly($this->type->value))($content, $this->required, $this->checked);

        return $entity->build(self::taskKey($key))
            ->disabled($completed);


    }

    private function prepareContent(array $task_data): string
    {
        return Str::replace(array_keys($task_data), array_values($task_data), $this->content);
    }
}
