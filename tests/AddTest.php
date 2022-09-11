<?php
declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\Generic\File;

final class AddTest extends Base
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
}
