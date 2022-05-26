<?php 
namespace AdinanCenci\JsonLines\Generic;

/**
 * Unlike WriteToFile, AddToFile will not remove existing content.
 */
class AddToFile extends WriteToFile 
{
    protected $overalLine = 0;
    protected $lastLine = 0;

    protected function run() 
    {
        $this->iterator->next();
        $keys = array_keys($this->newLines);
        $this->lastLine = end($keys);
        $this->processIterator();
    }

    protected function processIterator() 
    {
        while (!isset($this->newLines[ $this->overalLine ]) && $this->iterator->current()) {
            $newContent = $this->iterator->current();
            $newContent = $this->sanitizeLine($newContent);
            fwrite($this->tempFile, $newContent);
            $this->iterator->next();
            $this->overalLine++;
        }

        if (isset($this->newLines[ $this->overalLine ])) {
            $this->processNewLines();
        } else if ($this->overalLine < $this->lastLine) {
            $this->proccessBlankLines();
        }
    }

    protected function processNewLines() 
    {
        do {
            $nextLine = $this->overalLine + 1;
            $newContent = $this->newLines[ $this->overalLine ];
            $newContent = $this->sanitizeLine($newContent);
            fwrite($this->tempFile, $newContent);
            $this->overalLine++;
        } while( isset($this->newLines[ $nextLine ]) );

        if ($this->iterator->current()) {
            $this->processIterator();
        } else if ($this->overalLine < $this->lastLine) {
            $this->proccessBlankLines();
        }
    }

    protected function proccessBlankLines() 
    {
        while (!isset($this->newLines[ $this->overalLine ]) && $this->overalLine <= $this->lastLine) {
            $newContent = "\n";
            fwrite($this->tempFile, $newContent);
            $this->overalLine++;
        }

        if (isset($this->newLines[ $this->overalLine ])) {
            $this->processNewLines();
        }
    }
}
