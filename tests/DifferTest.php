<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Gendiff\gendiff;

class DifferTest extends TestCase
{
    public function testJson()
    {
        $correctDiff = $this->getPathToFixture('correctDiff');

        $firstPathFile = $this->getPathToFixture('file1.json');
        $secondPathFile = $this->getPathToFixture('file2.json');

        $result = gendiff($firstPathFile, $secondPathFile);

        $this->assertStringEqualsFile($correctDiff, $result);
    }

    public function testYml()
    {
        $correctDiff = $this->getPathToFixture('correctDiff');

        $firstPathFile = $this->getPathToFixture('file1.yml');
        $secondPathFile = $this->getPathToFixture('file2.yml');

        $result = genDiff($firstPathFile, $secondPathFile);
        
        $this->assertStringEqualsFile($correctDiff, $result);
    }

    private function getPathToFixture($fixtureName)
    {
        return __DIR__ . "/fixtures/{$fixtureName}";
    }
}