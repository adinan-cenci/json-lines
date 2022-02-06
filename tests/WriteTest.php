<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdinanCenci\JsonLines\JsonLines;

use AdinanCenci\JsonLines\Exception\DirectoryDoesNotExist;
use AdinanCenci\JsonLines\Exception\DirectoryIsNotWritable;
use AdinanCenci\JsonLines\Exception\FileIsNotWritable;

final class WriteTest extends TestCase
{
    public function testWriteToNonExistentDirectory() 
    {
        $file = new JsonLines('./tests/non-existent-directory/foo-bar.jsonl');
        $this->expectException(DirectoryDoesNotExist::class);
        $file->setObject(0, ['foo' => 'bar']);
    }

    public function testWriteToNonWritableDirectory() 
    {
        $file = new JsonLines('./tests/non-writable-directory/foo-bar.jsonl');
        $this->expectException(DirectoryIsNotWritable::class);
        $file->setObject(0, ['foo' => 'bar']);
    }

    public function testWriteToNonWritableFile() 
    {
        $file = new JsonLines('./tests/non-writable-file.jsonl');
        $this->expectException(FileIsNotWritable::class);
        $file->setObject(0, ['foo' => 'bar']);
    }

    public function testWriteToFile() 
    {
        $path = './tests/testWriteToFile.jsonl';
        $file = new JsonLines($path);
        $file->setObjects([
            0 => ['foo' => 'bar'],
            1 => ['bar' => 'foo']
        ]);
        
        $this->assertEquals('{"foo":"bar"}
{"bar":"foo"}
', file_get_contents($path));
    }

    public function testWriteFileWithGaps() 
    {
        $path = './tests/testWriteFileWithGaps.jsonl';
        $file = new JsonLines($path);
        $file->setObjects([
            0 => ['foo' => 'bar'],
            1 => ['bar' => 'foo'],
            4 => ['baz' => 'barfoo']
        ]);
        
        $this->assertEquals('{"foo":"bar"}
{"bar":"foo"}


{"baz":"barfoo"}
', file_get_contents($path));
    }
}
