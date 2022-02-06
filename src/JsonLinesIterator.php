<?php 
namespace AdinanCenci\JsonLines;

use AdinanCenci\JsonLines\Generic\FileIterator;

class JsonLinesIterator extends FileIterator 
{
    protected $associative = false;

    public function __construct($fileName, $associative = false) 
    {
        parent::__construct($fileName);
        $this->associative = $associative;
    }

    public function current() 
    {
        if (! $this->getHandle()) {
            return null;
        }

        return JsonLines::jsonDecode($this->currentLine, $this->associative, $this->currentLineNumber);
    }
}
