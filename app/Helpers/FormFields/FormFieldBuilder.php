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
    private array $state;

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

    public function setCurrentState(array $state): static
    {
        $this->state = $state;


        return $this;
    }

    public function build(int $key, ?array $task_data = [], bool $completed = false): Component
    {
        $checked = (bool)data_get($this->state, self::taskKey($key));
        $content = $this->prepareContent((array)$task_data);

        $entityClass = "\App\Helpers\FormFields\\" . Str::studly($this->type->value);
        /** @var AbstractFormField $entity */
        $entity = new $entityClass($content, $this->required, $checked);

        return $entity->build(self::taskKey($key))
            ->disabled($completed);


    }

    private function prepareContent(array $task_data): string
    {
        $keys = array_map(fn($item) => '{{' . $item . '}}', array_keys($task_data));
        return Str::replace($keys, array_values($task_data), $this->content);
    }
}
