<?php 
namespace AdinanCenci\JsonLines;

use AdinanCenci\FileEditor\FileIterator;

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

        return JsonLines::jsonDecode($this->currentContent, $this->associative, $this->currentLineNumber);
    }
}
