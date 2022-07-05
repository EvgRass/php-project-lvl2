<?php

namespace Differ\Gendiff;

use function Differ\Parsers\parseData;

function gendiff(string $pathFirstFile, string $pathSecondFile, string $format = 'stylish'): string
{
    $firstFileData = parseData(getFileData($pathFirstFile));
    $secondFileData = parseData(getFileData($pathSecondFile));

    $tree = getDiffTree($firstFileData, $secondFileData);

    return formater($format, $tree);
}

function getFileData(string $pathFile): array
{
    if (!file_exists($pathFile)) {
        throw new \Exception("File {$pathFile} does not exist!");
    }
    $extension = pathinfo($pathFile, PATHINFO_EXTENSION);
    $data = file_get_contents($pathFile);

    return ['extension' => $extension, 'data' => $data];
}

function getDiffTree(array $firstFileData, array $secondFileData): array
{
    $uniqKeys = array_unique(array_merge(array_keys($firstFileData), array_keys($secondFileData)));
    sort($uniqKeys);

    return array_map(function ($key) use ($firstFileData, $secondFileData) {
        if (!array_key_exists($key, $secondFileData)) {
            return [
                'name' => $key,
                'type' => 'removed',
                'value' => $firstFileData[$key]
            ];
        }
        if (!array_key_exists($key, $firstFileData)) {
            return [
                'name' => $key,
                'type' => 'added',
                'value' => $secondFileData[$key]
            ];
        }
        if ($firstFileData[$key] !== $secondFileData[$key]) {
            return [
                'name' => $key,
                'type' => 'changed',
                'valueFirst' => $firstFileData[$key],
                'valueSecond' => $secondFileData[$key]
            ];
        }
        return [
            'name' => $key,
            'type' => 'unchanged',
            'value' => $firstFileData[$key]
        ];
    }, $uniqKeys);
}

function formater(string $format, array $tree): string
{
    switch ($format) {
        // case 'plain':
        //     return;
        // case 'json':
        //     return;
        case 'stylish':
            return stylish($tree);
        default:
            throw new \Exception("Unknown format: '{$format}'!");
    }
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
            case 'changed':
                if (is_array($item['valueFirst']) && is_array($item['valueSecond'])) {
                    $acc .= PHP_EOL . $equal2 . $item['name'] . ": " .
                            stylish(getDiffTree($item['valueFirst'], $item['valueSecond']), $int);
                } else {
                    $acc .= PHP_EOL . $equal1 . $minus . $item['name'] . ": " .
                            stringify($item['valueFirst'], " ", 4, $int);
                    $acc .= PHP_EOL . $equal1 . $plus . $item['name'] . ": " .
                            stringify($item['valueSecond'], " ", 4, $int);
                }
                break;
            case 'unchanged':
                $acc .= PHP_EOL . $equal2 . $item['name'] . ": " . stringify($item['value'], " ", 4, $int);
                break;
        }
    }
    $acc .= PHP_EOL . $equal1 . "}";

    return $acc;
}
