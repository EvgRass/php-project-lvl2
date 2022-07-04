<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseData(array $dataFile): array
{
    ['extension' => $extension, 'data' => $data] = $dataFile;
    switch ($extension) {
        case 'yml':
        case 'yaml':
            return (array) Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
        case 'json':
            return json_decode($data, 1);
        default:
            throw new \Exception("Extension {$extension} not supported!");
    }
}