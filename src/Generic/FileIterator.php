<?php 
namespace AdinanCenci\JsonLines\Generic;

class FileIterator implements \Iterator 
{
    protected $fileName          = null;
    protected $handle            = null;
    protected $currentLine       = null;
    protected $currentLineNumber = 0;

    public function __construct($fileName) 
    {
        $this->fileName = $fileName;
    }

    public function current() 
    {
        if (! $this->getHandle()) {
            return null;
        }

        return $this->currentLine;
    }

    public function key() 
    {
        return $this->currentLineNumber;
    }

    public function next() 
    {
        if (! $this->getHandle()) {
            return;
        }

        $this->currentLine = fgets($this->handle);
        $this->currentLineNumber++;
        return $this->currentLine;
    }

    public function rewind() 
    {
        if (! $this->getHandle()) {
            return;
        }

        fclose($this->handle);
        $this->handle = fopen($this->fileName, 'r');
        $this->currentLine = fgets($this->handle);
        $this->currentLineNumber = 0;
    }

    public function valid() 
    {
        if (! $this->getHandle()) {
            return false;
        }

        $valid = $this->currentLine !== false;

        if (! $valid) {
            fclose($this->handle);
        }

        return $valid;
    }

    protected function getHandle() 
    {
        if ($this->handle) {
            return $this->handle;
        }

        if (! file_exists($this->fileName)) {
            return false;
        }
        
        return $this->handle = fopen($this->fileName, 'r');
    }
}
