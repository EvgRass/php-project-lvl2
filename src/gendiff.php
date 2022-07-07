<?php

namespace Differ\Differ;

use function Differ\Parsers\parseData;
use function Differ\Formatters\Formater\formater;

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
        if (is_array($firstFileData[$key]) && is_array($secondFileData[$key])) {
            return [
                'name' => $key,
                'type' => 'nested',
                'children' => getDiffTree($firstFileData[$key], $secondFileData[$key])
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
