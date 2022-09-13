<?php
declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\JsonLines;

class JsonReadTest extends Base
{
    public function testGetObject() 
    {
        $file = new JsonLines('./tests/template.jsonl');
        $entry = $file->getObject(0);

        $this->assertEquals('Soldiers of the wasteland', $entry->title);
    }

    public function testGetNonExistentOject() 
    {
        $file = new JsonLines('./tests/template.jsonl');
        $entry = $file->getObject(50);

        $this->assertEquals(null, $entry);
    }

    public function testGetOjects() 
    {
        $file = new JsonLines('./tests/template.jsonl', true);
        $entries = $file->getObjects([0, 1]);

        $this->assertEquals([
            0 => ['artist' => 'Dragon Force', 'title' => 'Soldiers of the wasteland', 'genre' => 'Metal'],
            1 => ['artist' => 'Kamelot', 'title' => 'Forever', 'genre' => 'Metal'],
        ], $entries);
    }
}
