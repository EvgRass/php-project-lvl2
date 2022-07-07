<?php

namespace Differ\Formatters\Formater;

use function Differ\Formatters\Stylish\stylish;
use function Differ\Formatters\Plain\plain;
use function Differ\Formatters\Json\json;

function formater(string $format, array $tree): string
{
    switch ($format) {
        case 'stylish':
            return stylish($tree);
        case 'plain':
            return plain($tree);
        case 'json':
            return json($tree);
        default:
            throw new \Exception("Unknown format: '{$format}'!");
    }
}
