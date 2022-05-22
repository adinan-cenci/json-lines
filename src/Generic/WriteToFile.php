<?php 
namespace AdinanCenci\JsonLines\Generic;

use AdinanCenci\JsonLines\Exception\DirectoryDoesNotExist;
use AdinanCenci\JsonLines\Exception\DirectoryIsNotWritable;
use AdinanCenci\JsonLines\Exception\FileIsNotWritable;

class WriteToFile 
{
    protected $fileName;
    protected $lines;
    protected $error = null;

    public function __construct(string $fileName, $lines) 
    {
        $this->fileName = $fileName;
        $this->lines    = $lines;
        ksort($this->lines);

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

        $tempName   = uniqid() . '.tmp';
        $tempFile   = fopen($tempName, 'w');
        $iterator   = $this->getExistingLines();
        $lineN      = 0;
        
        foreach ($iterator as $lineKey => $line) {
            $newContent = empty($this->lines[$lineKey]) ? 
                $line : 
                $this->lines[$lineKey];

            $newContent = $this->sanitizeLine($newContent);
            fwrite($tempFile, $newContent);
            $lineN++;
        }
        
        $keys = array_keys($this->lines);
        $lastOne = end($keys);

        while ($lineN <= $lastOne) {
            $newContent = empty($this->lines[$lineN]) ? 
                '' : 
                $this->lines[$lineN];

            $newContent = $this->sanitizeLine($newContent);
            fwrite($tempFile, $newContent);
            $lineN++;
        }

        fclose($tempFile);
        if (file_exists($this->fileName)) {
            unlink($this->fileName);
        }

        $success = rename($tempName, $this->fileName);

        if (! $success) {
            unlink($tempFile);
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
