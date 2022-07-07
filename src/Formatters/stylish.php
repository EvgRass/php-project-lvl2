<?php

namespace Differ\Formatters\Stylish;

use function Differ\Differ\getDiffTree;

function stylish(array $tree, $int = 0): string
{
    $int += 4;
    $plus   = "  + ";
    $minus  = "  - ";
    $equal1  = str_repeat(" ", $int - 4);
    $equal2  = str_repeat(" ", $int);

    $acc = "{";
    foreach ($tree as $k => $item) {
        switch ($item['type']) {
            case 'removed':
                $acc .= PHP_EOL . $equal1 . $minus . $item['name'] . ": " . stringify($item['value'], " ", 4, $int);
                break;
            case 'added':
                $acc .= PHP_EOL . $equal1 . $plus . $item['name'] . ": " . stringify($item['value'], " ", 4, $int);
                break;
            case 'nested':
                $acc .= PHP_EOL . $equal2 . $item['name'] . ": " . stylish($item['children'], $int);
                break;
            case 'changed':
                $acc .= PHP_EOL . $equal1 . $minus . $item['name'] . ": " .
                        stringify($item['valueFirst'], " ", 4, $int);
                $acc .= PHP_EOL . $equal1 . $plus . $item['name'] . ": " .
                        stringify($item['valueSecond'], " ", 4, $int);
                break;
            case 'unchanged':
                $acc .= PHP_EOL . $equal2 . $item['name'] . ": " . stringify($item['value'], " ", 4, $int);
                break;
        }
    }
    $acc .= PHP_EOL . $equal1 . "}";

    return $acc;
}

function stringify($data, string $replacer = " ", int $spacesCount = 1, $startSpace = 0): string
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
        $arr = array_merge(["{"], $arr, [str_repeat($replacer, $startSpace + $spCount - $spacesCount) . "}"]);

        return implode(PHP_EOL, $arr);
    };

    return $strfn($data, $spacesCount);
}
