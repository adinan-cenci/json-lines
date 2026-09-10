<?php

namespace AdinanCenci\JsonLines;

use AdinanCenci\FileEditor\FileIterator;

class JsonLinesIterator extends FileIterator
{
    /**
     * @param string $filename
     *   Absolute path to the file.
     * @param bool $associative
     *   If true, JSON objects will be returned as associative arrays,
     *   otherwise they will be returned as objects.
     */
    public function __construct(
        protected string $filename,
        protected bool $associative = false
    ) {
        parent::__construct($filename);
        $this->associative = $associative;
    }

    /**
     * \Iterator::current()
     */
    public function current(): mixed
    {
        if (! $this->getHandle()) {
            return null;
        }

        return JsonLines::jsonDecode($this->currentContent, $this->associative, $this->currentLineNumber);
    }
}
