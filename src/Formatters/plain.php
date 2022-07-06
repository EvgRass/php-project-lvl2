<?php

namespace Differ\Formatters\Plain;

use function Differ\Gendiff\getDiffTree;

function plain(array $tree): string
{
    $acc = [];
    foreach ($tree as $k => $item) {
        $parent = strlen($item['parent']) !== 0 ? $item['parent'] . "." . $item['name'] : $item['name'];
        switch ($item['type']) {
            case 'removed':
                $acc[] = "Property '" . $parent . "' was removed";
                break;
            case 'added':
                $valAdd = is_array($item['value']) ? "[complex value]" : stringify($item['value']);
                $acc[] = "Property '" . $parent . "' was added with value: " . $valAdd;
                break;
            case 'changed':
                if (is_array($item['valueFirst']) && is_array($item['valueSecond'])) {
                    $acc[] = plain(getDiffTree($item['valueFirst'], $item['valueSecond'], $parent));
                } else {
                    $valFirst = is_array($item['valueFirst']) ? "[complex value]" :
                            stringify($item['valueFirst']);
                    $valSecond = is_array($item['valueSecond']) ? "[complex value]" :
                            stringify($item['valueSecond']);
                    $acc[] = "Property '" . $parent . "' was updated. From " . $valFirst . " to " . $valSecond;
                }
                break;
        }
    }
    return implode(PHP_EOL, $acc);
}

function stringify($data)
{
    if (is_null($data)) {
        $str = 'null';
    } elseif (!is_array($data)) {
        $str = trim(var_export($data, true), "'");
    }
    if ($str === "true" || $str === "false" || $str === "null") {
        return $str;
    }
    return "'" . $str . "'";
}
