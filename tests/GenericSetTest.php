<?php
declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\Generic\File;

final class GenericSetTest extends Base
{
    public function testSetSingleLine() 
    {
        $fileName = 'tests/files/' . __FUNCTION__ . '.txt';
        $this->resetTest($fileName);

        $file = new File($fileName);
        $file->setLine(9, 'Elvenking');

        $nine = $file->getLine(9);
        $this->assertEquals('Elvenking', $nine);
    }

    public function testSetMultipleLines() 
    {
        $fileName = 'tests/files/' . __FUNCTION__ . '.txt';
        $this->resetTest($fileName);

        $file = new File($fileName);
        $file->setLines([5 => 'Vis Mystica', 8 => 'Hammer King'], false);

        $lines = $file->getLines([5, 8]);
        $this->assertEquals([5 => 'Vis Mystica', 8 => 'Hammer King'], $lines);
    }

    public function testSetPastEndOfTheFile() 
    {
        $fileName = 'tests/files/' . __FUNCTION__ . '.txt';
        $this->resetTest($fileName);

        $file = new File($fileName);
        $file->setLine(25, 'Elvenking');

        $lastLine = $file->getLine(25);
        $this->assertEquals('Elvenking', $lastLine);
    }
}
