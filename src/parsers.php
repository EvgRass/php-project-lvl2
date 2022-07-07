<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseData(array $dataFile): array
{
    ['extension' => $extension, 'data' => $data] = $dataFile;
    switch ($extension) {
        case 'yml':
        case 'yaml':
            return Yaml::parse($data, Yaml::PARSE_CONSTANT);
        case 'json':
            return json_decode($data, true);
        default:
            throw new \Exception("Extension {$extension} not supported!");
    }
}
