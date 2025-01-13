<?php

declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\JsonLines;

class JsonGetTest extends Base
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
            0 => ['artist' => 'Dragon Force', 'title' => 'Soldiers of the wasteland'],
            1 => ['artist' => 'Kamelot', 'title' => 'Forever'],
        ], $entries);
    }

    public function testGetRandomObjects()
    {
        $file = new JsonLines('./tests/template.jsonl', true);

        $objects = $file->getRandomObjects(5);

        $this->assertEquals(5, count($objects));
    }
}
