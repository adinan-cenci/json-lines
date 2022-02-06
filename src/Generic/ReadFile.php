<?php 
namespace AdinanCenci\JsonLines\Generic;

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
        $return   = [];

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
            throw new \Exception('File ' . $this->fileName . ' does not exist.');
        }

        if (! is_writable($this->fileName)) {
            throw new \Exception('File ' . $this->fileName . ' is not writable.');
        }
    }
}
