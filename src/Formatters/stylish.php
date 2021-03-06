<?php

namespace Differ\Formatters\Stylish;

use function Differ\Differ\getDiffTree;

function stylish(array $tree, int $int = 0): string
{
    $plus   = "  + ";
    $minus  = "  - ";
    $equal1  = str_repeat(" ", $int);
    $equal2  = str_repeat(" ", $int + 4);

    $res = array_reduce($tree, function ($acc, $item) use ($int, $plus, $minus, $equal1, $equal2) {
        switch ($item['type']) {
            case 'removed':
                return  $acc . PHP_EOL . $equal1 . $minus . $item['name'] . ": " .
                        stringify($item['value'], " ", 4, $int + 4);
            case 'added':
                return  $acc . PHP_EOL . $equal1 . $plus . $item['name'] . ": " .
                        stringify($item['value'], " ", 4, $int + 4);
            case 'nested':
                return  $acc . PHP_EOL . $equal2 . $item['name'] . ": " .
                        stylish($item['children'], $int + 4);
            case 'changed':
                return  $acc . PHP_EOL . $equal1 . $minus . $item['name'] . ": " .
                        stringify($item['valueFirst'], " ", 4, $int + 4) .
                        PHP_EOL . $equal1 . $plus . $item['name'] . ": " .
                        stringify($item['valueSecond'], " ", 4, $int + 4);
            case 'unchanged':
                return  $acc . PHP_EOL . $equal2 . $item['name'] . ": " .
                        stringify($item['value'], " ", 4, $int + 4);
        }
        return $acc;
    }, "{");

    return $res . PHP_EOL . $equal1 . "}";
}

function stringify(mixed $data, string $replacer = " ", int $spacesCount = 1, int $startSpace = 0): string
{
    $strfn = function ($data, $spCount) use (&$strfn, $replacer, $spacesCount, $startSpace) {
        if (is_null($data)) {
            return 'null';
        }
        if (!is_array($data)) {
            return trim(var_export($data, true), "'");
        }
        $arr = array_map(fn ($key, $value) =>
            str_repeat($replacer, $startSpace + $spCount) . $key . ": " .
            $strfn($value, $spCount + $spacesCount), array_keys($data), array_values($data));
        $array = array_merge(["{"], $arr, [str_repeat($replacer, $startSpace + $spCount - $spacesCount) . "}"]);

        return implode(PHP_EOL, $array);
    };

    return $strfn($data, $spacesCount);
}
