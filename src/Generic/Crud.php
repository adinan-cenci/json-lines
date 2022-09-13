<?php 
namespace AdinanCenci\JsonLines\Generic;

use AdinanCenci\JsonLines\Exception\DirectoryDoesNotExist;
use AdinanCenci\JsonLines\Exception\DirectoryIsNotWritable;
use AdinanCenci\JsonLines\Exception\FileIsNotWritable;
use AdinanCenci\JsonLines\Exception\FileDoesNotExist;
use AdinanCenci\JsonLines\Exception\FileIsNotReadable;

class Crud 
{
    protected string $fileName = '';
    protected int $finalLine = 0;
    protected FileIterator $iterator;

    //-----------------------------

    protected array $linesToGet = [];
    protected array $linesToAdd = [];
    protected array $linesToSet = [];
    protected array $linesToDelete = [];

    //-----------------------------

    protected string $tempName = '';
    protected $tempFile = null;

    //-----------------------------

    protected int $newFileLine = 0;
    protected array $linesRetrieved = [];

    //-----------------------------

    public function __construct(string $fileName) 
    {
        $this->fileName = $fileName;
    }

    public function __get(string $propertyName) 
    {
        if ($propertyName == 'linesRetrieved') {
            return $this->linesRetrieved;
        }

        \trigger_error('Trying to retrieve unknown property ' . $propertyName, \E_USER_ERROR);
    }

    public function get(array $lines) : self
    {
        $this->linesToGet = $lines;
        return $this;
    }

    public function add(array $lines) : self
    {
        $this->linesToAdd = $lines;
        return $this;
    }

    public function set(array $lines) : self
    {
        $this->linesToSet = $lines;
        return $this;
    }

    public function delete(array $lines) : self
    {
        $this->linesToDelete = $lines;
        return $this;
    }

    public function commit() : self
    {
        $this->validate();
        $this->prepare();
        $this->iterate();
        $this->ended();
        return $this;
    }

    protected function validate() : void
    {
        if ($this->linesToDelete) {
            $this->validateFileForDeleting();
        }

        if ($this->linesToAdd || $this->linesToSet) {
            $this->validateFileForWriting();
        }

        if ($this->linesToGet) {
            $this->validateFileForReading();
        }
    }

    protected function prepare() : void 
    {
        $this->iterator  = new FileIterator($this->fileName);
        $this->finalLine = $this->getNumberOfLinesToProcess();

        if ($this->linesToAdd || $this->linesToSet || $this->linesToDelete) {
            $this->tempName   = uniqid() . '.tmp';
            $this->tempFile   = fopen($this->tempName, 'w');
        }

        if (! $this->linesToGet) {
            return;
        }

        foreach ($this->linesToGet as $line) {
            $this->linesRetrieved[ $line ] = null;
        }
    }

    public function iterate() : void
    {
        $this->iterator->rewind();

        while ($this->newFileLine <= $this->finalLine) {

            if (in_array($this->iterator->currentLine, $this->linesToDelete)) {
                $this->read();
                $this->iterator->next();
            } elseif (isset($this->linesToSet[ $this->iterator->currentLine ])) {
                $this->read();
                $this->writeToTempFile($this->linesToSet[ $this->iterator->currentLine ]);
                $this->iterator->next();
                $this->newFileLine++;
            } elseif (isset($this->linesToSet[ $this->newFileLine ]) && !$this->iterator->valid()) {
                $this->writeToTempFile($this->linesToSet[ $this->newFileLine ]);
                $this->newFileLine++;
            }

            if (isset($this->linesToAdd[ $this->newFileLine ])) {
                $this->writeToTempFile($this->linesToAdd[ $this->newFileLine ]);
                $this->newFileLine++;
            } else if ($this->tempFile) {
                $this->read();
                $this->writeToTempFile((string) $this->iterator->current());
                $this->iterator->next();
                $this->newFileLine++;
            } else {
                $this->read();
                $this->iterator->next();
                $this->newFileLine++;
            }
        }
    }

    protected function ended() : void
    {
        if ($this->tempName) {
            fclose($this->tempFile);

            if (file_exists($this->fileName)) {
                unlink($this->fileName);
            }

            rename($this->tempName, $this->fileName);
        }
    }

    protected function read() 
    {
        $xx = $this->iterator->currentLine;
        if (in_array($this->iterator->currentLine, $this->linesToGet)) {
            $content = $this->iterator->current();
            $content = rtrim($content, "\n");
            $this->linesRetrieved[ $this->iterator->currentLine ] = $content;
        }
    }

    protected function getNumberOfLinesToProcess() : int
    {
        if (empty($this->linesToAdd) && empty($this->linesToSet) && empty($this->linesToDelete)) {
            return max($this->linesToGet);
        }

        return max(
            File::getLastLine($this->fileName, true),
            $this->linesToGet ? max($this->linesToGet) : 0,
            $this->linesToAdd ? max(array_keys($this->linesToAdd)) : 0,
            $this->linesToSet ? max(array_keys($this->linesToSet)) : 0
        );
    }

    protected function writeToTempFile(string $newContent) : void
    {
        $lastLine = $this->newFileLine == $this->finalLine;
        $newContent = $this->sanitizeLine($newContent, !$lastLine);
        fwrite($this->tempFile, $newContent);
    }

    protected function sanitizeLine(string $content, bool $appendLineBreak = true) : string
    {
        $string = (string) $content;
        $string = str_replace(["\n", "\r"], '', $string);
        $string .= $appendLineBreak ? "\n" : '';
        return $string;
    }

    protected function validateFileForWriting() 
    {
        $dir = dirname($this->fileName) . '/';

        if (! file_exists($dir)) {
            throw new DirectoryDoesNotExist($dir);
        }

        if (! is_writable($dir)) {
            throw new DirectoryIsNotWritable($dir);
        }

        if (file_exists($this->fileName) && !is_writable($this->fileName)) {
            throw new FileIsNotWritable($this->fileName);
        }
    }

    protected function validateFileForReading() 
    {
        if (! file_exists($this->fileName)) {
            throw new FileDoesNotExist($this->fileName);
        }

        if (! is_readable($this->fileName)) {
            throw new FileIsNotReadable($this->fileName);
        }
    }

    protected function validateFileForDeleting() 
    {
        if (!file_exists($this->fileName)) {
            throw new FileDoesNotExist($this->fileName);
        }

        if (!is_writable($this->fileName)) {
            throw new FileIsNotWritable($this->fileName);
        }
    }
}
