<?php 
namespace AdinanCenci\JsonLines\Generic;

class WriteToFile 
{
    protected $fileName;
    protected $lines;

    public function __construct(string $fileName, $lines) 
    {
        $this->fileName = $fileName;
        $this->lines    = $lines;
        ksort($this->lines);
        $this->validateFileForWriting();
    }

    public function writeDown() 
    {
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
        rename($tempName, $this->fileName);
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
        if (file_exists($this->fileName)) {
            $this->validateDirectoryForWriting();
        }

        if (! $this->fileIsWritable()) {
            throw new \Exception('File not writable');
        }
    }

    protected function validateDirectoryForWriting() 
    {
        if (! $this->directoryIsWritable()) {
            throw new \Exception('Directory not writable');
        }
    }

    protected function fileIsWritable() 
    {
        return file_exists($this->fileName) ? 
            is_writable($this->fileName) : 
            is_writable($this->getDirectory());
    }

    protected function directoryIsWritable() 
    {
        return is_writable($this->getDirectory());
    }

    protected function getDirectory() 
    {
        return dirname($this->fileName) . '/';
    }
}
