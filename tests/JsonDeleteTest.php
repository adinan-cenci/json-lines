<?php

declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\JsonLines;

class JsonDeleteTest extends Base
{
    public function testDeleteSingleObject() 
    {
        $filename = 'tests/files/' . __FUNCTION__ . '.jsonl';
        $this->resetTest($filename, './tests/template.jsonl');

        $file = new JsonLines($filename, true);
        $file->deleteObject(2);

        $third = $file->getObject(2);
        $this->assertEquals(['artist' => 'Omri Lahav', 'title' => 'Tale of Warriors'], $third);
    }

    public function testDeleteMultipleObjects() 
    {
        $filename = 'tests/files/' . __FUNCTION__ . '.jsonl';
        $this->resetTest($filename, './tests/template.jsonl');

        $file = new JsonLines($filename, true);
        $file->deleteObjects([0, 2]);

        $lines = $file->getObjects([0, 1]);
        $this->assertEquals([
            0 => ['artist' => 'Kamelot', 'title' => 'Forever'],
            1 => ['artist' => 'Omri Lahav', 'title' => 'Tale of Warriors']
        ], $lines);
    }
}
