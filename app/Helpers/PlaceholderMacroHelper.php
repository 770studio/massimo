<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class PlaceholderMacroHelper
{

    public static function replaceTextMacro(?array $data, string $content): string
    {
        $data = (array)$data;
        $keys = array_map(fn($item) => '{{' . $item . '}}', array_keys($data));
        return Str::replace($keys, array_values($data), $content);
    }
}
