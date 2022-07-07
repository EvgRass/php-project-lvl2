<?php

namespace Differ\Formatters\Json;

use function Differ\Differ\getDiffTree;

function json(array $tree): string
{
    return json_encode($tree, JSON_THROW_ON_ERROR);
}
