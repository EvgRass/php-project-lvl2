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

        $correctDiff2 = $this->getPathToFixture('correctDiff2');
        $firstPathFile = $this->getPathToFixture('filepath1.json');
        $secondPathFile = $this->getPathToFixture('filepath2.json');
        $result2 = gendiff($firstPathFile, $secondPathFile);
        $this->assertStringEqualsFile($correctDiff2, $result2);

        $correctDiff3 = $this->getPathToFixture('correctDiff3');
        $firstPathFile = $this->getPathToFixture('filepath1.json');
        $secondPathFile = $this->getPathToFixture('filepath2.json');
        $result3 = gendiff($firstPathFile, $secondPathFile, 'plain');
        $this->assertStringEqualsFile($correctDiff3, $result3);
    }

    public function testYml()
    {
        $correctDiff = $this->getPathToFixture('correctDiff');
        $firstPathFile = $this->getPathToFixture('file1.yml');
        $secondPathFile = $this->getPathToFixture('file2.yml');
        $result = genDiff($firstPathFile, $secondPathFile);
        $this->assertStringEqualsFile($correctDiff, $result);

        $correctDiff = $this->getPathToFixture('correctDiff2');
        $firstPathFile = $this->getPathToFixture('filepath1.yml');
        $secondPathFile = $this->getPathToFixture('filepath2.yml');
        $result = genDiff($firstPathFile, $secondPathFile);
        $this->assertStringEqualsFile($correctDiff, $result);

        $correctDiff3 = $this->getPathToFixture('correctDiff3');
        $firstPathFile = $this->getPathToFixture('filepath1.yml');
        $secondPathFile = $this->getPathToFixture('filepath2.yml');
        $result3 = gendiff($firstPathFile, $secondPathFile, 'plain');
        $this->assertStringEqualsFile($correctDiff3, $result3);
    }

    private function getPathToFixture($fixtureName)
    {
        return __DIR__ . "/fixtures/{$fixtureName}";
    }
}