<?php 
namespace AdinanCenci\JsonLines\Generic;

use AdinanCenci\JsonLines\Exception\FileDoesNotExist;
use AdinanCenci\JsonLines\Exception\FileIsNotReadable;

class ReadFile 
{
    protected $fileName;
    protected $lines;

    public function __construct(string $fileName, $lines) 
    {
        $this->fileName = $fileName;
        $this->lines    = $lines;
        $this->validateFileForReading();
    }

    public function read() 
    {
        $entries  = $this->getExistingLines();
        $return   = array_combine($this->lines, array_fill(0, count($this->lines), null));

        foreach ($entries as $lineKey => $line) {
            if (in_array($lineKey, $this->lines)) {
                $return[$lineKey] = $line;
            }
        }

        return $return;
    }

    protected function getExistingLines() 
    {
        return new FileIterator($this->fileName);
    }

    protected function validateFileForReading() 
    {
        if (! file_exists($this->fileName)) {
            throw new FileDoesNotExist($this->fileName);
        }

        if (! is_writable($this->fileName)) {
            throw new FileIsNotReadable($this->fileName);
        }
    }
}
