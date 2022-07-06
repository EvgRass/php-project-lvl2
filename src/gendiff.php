<?php

namespace Differ\Gendiff;

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

function getDiffTree(array $firstFileData, array $secondFileData, string $parent = ''): array
{
    $uniqKeys = array_unique(array_merge(array_keys($firstFileData), array_keys($secondFileData)));
    sort($uniqKeys);

    return array_map(function ($key) use ($firstFileData, $secondFileData, $parent) {
        if (!array_key_exists($key, $secondFileData)) {
            return [
                'name' => $key,
                'parent' => $parent,
                'type' => 'removed',
                'value' => $firstFileData[$key]
            ];
        }
        if (!array_key_exists($key, $firstFileData)) {
            return [
                'name' => $key,
                'parent' => $parent,
                'type' => 'added',
                'value' => $secondFileData[$key]
            ];
        }
        if ($firstFileData[$key] !== $secondFileData[$key]) {
            return [
                'name' => $key,
                'parent' => $parent,
                'type' => 'changed',
                'valueFirst' => $firstFileData[$key],
                'valueSecond' => $secondFileData[$key]
            ];
        }
        return [
            'name' => $key,
            'parent' => $parent,
            'type' => 'unchanged',
            'value' => $firstFileData[$key]
        ];
    }, $uniqKeys);
}
