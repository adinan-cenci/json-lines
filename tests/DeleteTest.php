<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdinanCenci\JsonLines\JsonLines;

use AdinanCenci\JsonLines\Exception\DirectoryDoesNotExist;
use AdinanCenci\JsonLines\Exception\DirectoryIsNotWritable;
use AdinanCenci\JsonLines\Exception\FileIsNotWritable;

final class DeleteTest extends TestCase
{
    public function testDeleteFromFileFile() 
    {
        $path = './tests/testDeleteFromFileFile.jsonl';
        $content = '{"foo0":"bar0"}
{"bar1":"foo1"}
{"baz2":"foobar2"}
{"foobar3":"baz3"}
{"lorem4":"ipsum4"}
';

        file_put_contents($path, $content);
        $file = new JsonLines($path);
        $file->deleteObjects([1, 3]);
        
        $this->assertEquals(file_get_contents($path), '{"foo0":"bar0"}
{"baz2":"foobar2"}
{"lorem4":"ipsum4"}
');
    }

    public function testDeleteSingleLineFromFileFile() 
    {
        $path = './tests/testDeleteFromFileFile.jsonl';
        $content = '{"foo0":"bar0"}
{"bar1":"foo1"}
{"baz2":"foobar2"}
{"foobar3":"baz3"}
{"lorem4":"ipsum4"}
';

        file_put_contents($path, $content);
        $file = new JsonLines($path);
        $file->deleteObject(1);
        
        $this->assertEquals(file_get_contents($path), '{"foo0":"bar0"}
{"baz2":"foobar2"}
{"foobar3":"baz3"}
{"lorem4":"ipsum4"}
');
    }
}
