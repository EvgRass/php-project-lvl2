<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Gendiff\gendiff;

$autoloadPath1 = __DIR__ . '/../../../autoload.php';
$autoloadPath2 = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath1)) {
    require_once $autoloadPath1;
} else {
    require_once $autoloadPath2;
}

class DifferTest extends TestCase
{
    public function testJson()
    {
        $correctDiff = $this->getPathToFixture('correctDiff');

        $firstPathJsonFile = $this->getPathToFixture('file1.json');
        $secondPathJsonFile = $this->getPathToFixture('file2.json');

        $result = gendiff($firstPathJsonFile, $secondPathJsonFile);

        $this->assertStringEqualsFile($correctDiff, $result);
    }

    private function getPathToFixture($fixtureName)
    {
        return __DIR__ . "/fixtures/{$fixtureName}";
    }
}