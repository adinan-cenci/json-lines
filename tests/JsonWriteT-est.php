<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdinanCenci\JsonLines\JsonLines;

final class WriteTest extends TestCase
{
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
