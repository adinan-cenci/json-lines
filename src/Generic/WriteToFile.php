<?php 
namespace AdinanCenci\JsonLines\Generic;

use AdinanCenci\JsonLines\Exception\DirectoryDoesNotExist;
use AdinanCenci\JsonLines\Exception\DirectoryIsNotWritable;
use AdinanCenci\JsonLines\Exception\FileIsNotWritable;

class WriteToFile 
{
    protected $fileName = null;
    protected $newLines = null;
    protected $error    = null;

    protected $tmpName  = null;
    protected $tempFile = null;
    protected $iterator = null;

    public function __construct(string $fileName, $newLines) 
    {
        $this->fileName = $fileName;
        $this->newLines = $newLines;
        ksort($this->newLines);

        try {
            $this->validateFileForWriting();
        } catch (\Exception $e) {
            $this->erro = $e;
            throw $e;
        }
    }

    public function writeDown() 
    {
        if ($this->error) {
            throw $this->error;
            return;
        }

        $this->tempName   = uniqid() . '.tmp';
        $this->tempFile   = fopen($this->tempName, 'w');
        $this->iterator   = $this->getExistingLines();
        
        $this->run();

        fclose($this->tempFile);
        if (file_exists($this->fileName)) {
            unlink($this->fileName);
        }

        rename($this->tempName, $this->fileName);
    }

    protected function run() 
    {
        $lineN = 0;
        
        foreach ($this->iterator as $lineKey => $line) {
            $newContent = empty($this->newLines[$lineKey]) ? 
                $line : 
                $this->newLines[$lineKey];

            $newContent = $this->sanitizeLine($newContent);
            fwrite($this->tempFile, $newContent);
            $lineN++;
        }
        
        $keys = array_keys($this->newLines);
        $lastOne = end($keys);

        while ($lineN <= $lastOne) {
            $newContent = empty($this->newLines[$lineN]) ? 
                '' : 
                $this->newLines[$lineN];

            $newContent = $this->sanitizeLine($newContent);
            fwrite($this->tempFile, $newContent);
            $lineN++;
        }
    }

    protected function getExistingLines() 
    {
        return new FileIterator($this->fileName);
    }

    protected function sanitizeLine($content) 
    {
        $string = (string) $content;
        $string = str_replace(["\n", "\r"], '', $string);
        $string .= "\n";
        return $string;
    }

    protected function validateFileForWriting() 
    {
        $dir = dirname($this->fileName) . '/';

        if (!file_exists($dir)) {
            throw new DirectoryDoesNotExist($dir);
        }

        if (!is_writable($dir)) {
            throw new DirectoryIsNotWritable($dir);
        }

        if (file_exists($this->fileName) && !is_writable($this->fileName)) {
            throw new FileIsNotWritable($this->fileName);
        }
    }
}
