<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Gendiff\gendiff;

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