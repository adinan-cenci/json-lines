<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdinanCenci\JsonLines\JsonLines;
use AdinanCenci\JsonLines\Exception\FileDoesNotExist;
use AdinanCenci\JsonLines\Exception\FileIsNotReadable;

final class ReadTest extends TestCase
{
    public function testReadNonExistentFile() 
    {
        $file = new JsonLines('./tests/non-existent-file.jsonl');
        $this->expectException(FileDoesNotExist::class);
        $entry = $file->getObject(0);
    }

    public function testReadFileWithoutReadingPermission() 
    {
        $file = new JsonLines('./tests/non-readable-file.jsonl');
        $this->expectException(FileIsNotReadable::class);
        $entry = $file->getObject(0);
    }

    public function testGetNonExistentOject() 
    {
        $file = new JsonLines('./tests/readable-file.jsonl');
        $entries = $file->getObjects([5]);

        $this->assertEquals(null, $entries[5]);
    }

    public function testGetOjects() 
    {
        $file = new JsonLines('./tests/readable-file.jsonl', true);
        $entries = $file->getObjects([0, 1]);

        $this->assertEquals([
            0 => ["id" => 0, "name" => "foo"],
            1 => ["id" => 1, "name" => "bar"]
        ], $entries);
    }
}
