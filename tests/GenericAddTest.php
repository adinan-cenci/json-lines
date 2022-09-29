<?php
declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\Generic\File;

final class GenericAddTest extends Base
{
    public function testAddSingleLine() 
    {
        $fileName = 'tests/files/' . __FUNCTION__ . '.txt';
        $this->resetTest($fileName);

        $file = new File($fileName);
        $file->addLine('Elvenking');

        $lastLine = $file->getLine(16);
        $this->assertEquals('Elvenking', $lastLine);
    }

    public function testAddMultipleLinesToTheEndOfTheFile() 
    {
        $fileName = 'tests/files/' . __FUNCTION__ . '.txt';
        $this->resetTest($fileName);

        $file = new File($fileName);
        $file->addLines(['Vis Mystica', 'Hammer King'], true);

        $lines = $file->getLines([16, 17]);
        $this->assertEquals([16 => 'Vis Mystica', 17 => 'Hammer King'], $lines);
    }

    public function testAddMultipleLines() 
    {
        $fileName = 'tests/files/' . __FUNCTION__ . '.txt';
        $this->resetTest($fileName);

        $file = new File($fileName);
        $file->addLines([5 => 'Vis Mystica', 8 => 'Hammer King'], false);

        $lines = $file->getLines([5, 8]);
        $this->assertEquals([5 => 'Vis Mystica', 8 => 'Hammer King'], $lines);
    }

    public function testAddPastEndOfTheFile() 
    {
        $fileName = 'tests/files/' . __FUNCTION__ . '.txt';
        $this->resetTest($fileName);

        $file = new File($fileName);
        $file->addLine('Elvenking', 25);

        $lastLine = $file->getLine(25);
        $this->assertEquals('Elvenking', $lastLine);
    }

    public function testAddLinesWithGaps() 
    {
        $fileName = 'tests/files/' . __FUNCTION__ . '.txt';
        $this->resetTest($fileName, '');

        $file = new File($fileName, true);

        $file->addLines([
            0 => 'Dreamtale',
            10 => 'Unleash The Archers'
        ], false);

        $lines = $file->getLines([0, 10]);
        $this->assertEquals([
            0 => 'Dreamtale',
            10 => 'Unleash The Archers'
        ], $lines);
    }
}
