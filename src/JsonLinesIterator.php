<?php

namespace AdinanCenci\JsonLines;

use AdinanCenci\FileEditor\FileIterator;

class JsonLinesIterator extends FileIterator
{
    /**
     * @var bool
     *   When true JSON objects will be returned as associative arrays
     *   when false, JSON objects will be returned as objects.
     */
    protected bool $associative = false;

    /**
     * @param string $fileName
     *   Absolute path to the file.
     * @param bool $associative
     *   If true, JSON objects will be returned as associative arrays,
     *   otherwise they will be returned as objects.
     */
    public function __construct(string $fileName, bool $associative = false)
    {
        parent::__construct($fileName);
        $this->associative = $associative;
    }

    /**
     * \Iterator::current()
     */
    public function current()
    {
        if (! $this->getHandle()) {
            return null;
        }

        return JsonLines::jsonDecode($this->currentContent, $this->associative, $this->currentLineNumber);
    }
}
