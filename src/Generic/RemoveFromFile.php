<?php 
namespace AdinanCenci\JsonLines\Generic;

use AdinanCenci\JsonLines\Exception\FileDoesNotExist;
use AdinanCenci\JsonLines\Exception\FileIsNotWritable;

class RemoveFromFile 
{
    protected $fileName;
    protected $lines;

    public function __construct(string $fileName, $lines) 
    {
        $this->fileName = $fileName;
        $this->lines    = $lines;
        $this->validateFileForDeleting();
    }

    public function remove() 
    {
        $tempName   = uniqid() . '.tmp';
        $tempFile   = fopen($tempName, 'w');
        $iterator   = $this->getExistingLines();
        $lineN      = 0;
        
        foreach ($iterator as $lineKey => $line) {
            $newContent = in_array($lineKey, $this->lines) ?
                '' : 
                $line;

            fwrite($tempFile, $newContent);
            $lineN++;
        }

        fclose($tempFile);
        if (file_exists($this->fileName)) {
            unlink($this->fileName);
        }
        rename($tempName, $this->fileName);
    }

    protected function getExistingLines() 
    {
        return new FileIterator($this->fileName);
    }

    protected function validateFileForDeleting() 
    {
        if (!file_exists($this->fileName)) {
            throw new FileDoesNotExist($this->fileName));
        }

        if (!is_writable($this->fileName)) {
            throw new FileIsNotWritable($this->fileName);
        }
    }
}
