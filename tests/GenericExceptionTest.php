<?php
declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\Generic\File;
use AdinanCenci\JsonLines\Exception\DirectoryDoesNotExist;
use AdinanCenci\JsonLines\Exception\DirectoryIsNotWritable;
use AdinanCenci\JsonLines\Exception\FileIsNotWritable;
use AdinanCenci\JsonLines\Exception\FileDoesNotExist;
use AdinanCenci\JsonLines\Exception\FileIsNotReadable;

final class GenericExceptionTest extends Base
{
    public function testWriteToNonExistentDirectory() 
    {
        $file = new File('./tests/exception-tests/non-existent-directory/foo-bar.txt');
        $this->expectException(DirectoryDoesNotExist::class);
        $file->setLine(0, 'foo-bar');
    }

    public function testWriteToNonWritableDirectory() 
    {
        $file = new File('./tests/exception-tests/non-writable-directory/foo-bar.txt');
        $this->expectException(DirectoryIsNotWritable::class);
        $file->setLine(0, 'foo-bar');
    }

    public function testWriteToNonWritableFile() 
    {
        $file = new File('./tests/exception-tests/non-writable-file.txt');
        $this->expectException(FileIsNotWritable::class);
        $file->setLine(0, 'foo-bar');
    }

    public function testReadNonExistentFile() 
    {
        $file = new File('./tests/exception-tests/non-existent-file.txt');
        $this->expectException(FileDoesNotExist::class);
        $entry = $file->getLine(0);
    }

    public function testReadFileWithoutReadingPermission() 
    {
        $file = new File('./tests/exception-tests/non-readable-file.txt');
        $this->expectException(FileIsNotReadable::class);
        $entry = $file->getLine(0);
    }
}
