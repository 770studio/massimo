<?php

namespace App\Enums;


use App\Helpers\FormFields\AbstractFormField;
use Filament\Forms\Components\Component;
use Illuminate\Support\Str;

enum FormFieldType: string
{


    case TASK = 'task';
    case PARAGRAPH = 'paragraph';


    public function getField(string $content, bool $required): Component
    {
        /** @var AbstractFormField $entity */
        $entity = new ("\App\Helpers\FormFields\\" . Str::studly($this->value))($content, $required);

        return $entity->build();
    }

    /*    public static function getText(self $value): string
        {
            return match ($value) {
                self::TASK => 'TASK',
                self::PARAGRAPH => 'PARAGRAPH',

            };
        }*/
}
