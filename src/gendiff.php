<?php

namespace Gendiff;

use function Functional\flatten;

function gendiff(string $pathFirstFile, string $pathSecondFile, string $format)
{
    $firstFileData = getFileData($pathFirstFile);
    $secondFileData = getFileData($pathSecondFile);

    $tree = getDiffTree($firstFileData, $secondFileData);

    return format($format, $tree);
}

function getFileData(string $pathFile): array
{
    return json_decode(file_get_contents($pathFile), 1);
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

function format(string $format, array $tree)
{
    if ($format === 'stylish') {
        $acc = "{";
        foreach ($tree as $k => $item) {
            switch ($item['type']) {
                case 'removed':
                    $acc .= "\n  - {$item['name']}: {$item['value']}";
                    break;
                case 'added':
                    $acc .= "\n  + {$item['name']}: {$item['value']}";
                    break;
                case 'changed':
                    $acc .= "\n  - {$item['name']}: {$item['valueFirst']}"; 
                    $acc .= "\n  + {$item['name']}: {$item['valueSecond']}";
                    break;
                case 'unchanged':
                    $acc .= "\n    {$item['name']}: {$item['value']}";
                    break;
            }
        }
        $acc .= "\n}\n";

        return $acc;
    }
    
}