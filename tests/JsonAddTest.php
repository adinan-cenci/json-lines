<?php

declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\JsonLines;

class JsonAddTest extends Base
{
    public function testAddSingleObjectToFile()
    {
        $fileName = 'tests/files/' . __FUNCTION__ . '.jsonl';
        $this->resetTest($fileName, './tests/template.jsonl');

        $file = new JsonLines($fileName, true);
        $file->addObject(['artist' => 'Alpine Universe', 'title' => 'The Empire of Winds']);

        $last = $file->getObject(7);
        $this->assertEquals(['artist' => 'Alpine Universe', 'title' => 'The Empire of Winds'], $last);
    }

    public function testAddMultipleObjectToFile()
    {
        $fileName = 'tests/files/' . __FUNCTION__ . '.jsonl';
        $this->resetTest($fileName, './tests/template.jsonl');

        $file = new JsonLines($fileName, true);
        $file->addObjects([
            ['artist' => 'Atlantean Kodex', 'title' => 'Sol Invictus'],
            ['artist' => 'Space Cadets', 'title' => 'Kill All Xenos']
        ], true);

        $last = $file->getObjects([7, 8]);
        $this->assertEquals([
            7 => ['artist' => 'Atlantean Kodex', 'title' => 'Sol Invictus'],
            8 => ['artist' => 'Space Cadets', 'title' => 'Kill All Xenos']
        ], $last);
    }

    public function testAddObjectsWithGaps()
    {
        $fileName = 'tests/files/' . __FUNCTION__ . '.jsonl';
        $this->resetTest($fileName, '');

        $file = new JsonLines($fileName, true);

        $file->addObjects([
            0 => ['artist' => 'Dreamtale', 'title' => 'Angel of Light'],
            10 => ['artist' => 'Unleash The Archers', 'title' => 'Dreamcrusher']
        ], false);

        $lines = $file->getObjects([0, 10]);

        $this->assertEquals([
            0 => ['artist' => 'Dreamtale', 'title' => 'Angel of Light'],
            10 => ['artist' => 'Unleash The Archers', 'title' => 'Dreamcrusher']
        ], $lines);
    }
}
