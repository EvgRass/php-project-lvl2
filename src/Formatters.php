<?php

namespace Differ\Formatters\Formater;

use function Differ\Formatters\Stylish\stylish;
use function Differ\Formatters\Plain\plain;

function formater(string $format, array $tree): string
{
    switch ($format) {
        case 'stylish':
            return stylish($tree);
        case 'plain':
            return plain($tree);
        // case 'json':
        //     return;
        default:
            throw new \Exception("Unknown format: '{$format}'!");
    }
}
