<?php

namespace Differ\Formatters\Plain;

use function Differ\Differ\getDiffTree;

function plain(array $tree, string $parents = ''): string
{
    $res = array_reduce($tree, function ($acc, $item) use ($parents) {
        $newParents = ($parents !== '') ? $parents . "." . $item['name'] : $item['name'];
        switch ($item['type']) {
            case 'removed':
                $acc[] = "Property '" . $newParents . "' was removed";
                break;
            case 'added':
                $valAdd = is_array($item['value']) ? "[complex value]" : stringify($item['value']);
                $acc[] = "Property '" . $newParents . "' was added with value: " . $valAdd;
                break;
            case 'nested':
                $acc[] = plain($item['children'], $newParents);
                break;
            case 'changed':
                $valFirst = is_array($item['valueFirst']) ? "[complex value]" :
                        stringify($item['valueFirst']);
                $valSecond = is_array($item['valueSecond']) ? "[complex value]" :
                        stringify($item['valueSecond']);
                $acc[] = "Property '" . $newParents . "' was updated. From " . $valFirst . " to " . $valSecond;
                break;
        }
        return $acc;
    }, []);

    return implode(PHP_EOL, $res);
}

function stringify($data): string
{
    $str = '';
    if (is_null($data)) {
        $str = 'null';
    } elseif (!is_array($data)) {
        $str = trim(var_export($data, true), "'");
    }
    if ($str === "true" || $str === "false" || $str === "null" || is_numeric($data)) {
        return $str;
    }
    return "'" . $str . "'";
}
