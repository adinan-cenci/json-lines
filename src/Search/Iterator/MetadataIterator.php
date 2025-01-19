<?php

namespace AdinanCenci\JsonLines\Search\Iterator;

use AdinanCenci\JsonLines\JsonLines;
use AdinanCenci\JsonLines\JsonLinesIterator;

/**
 * Iterator object to scrutinize the document line by line.
 */
class MetadataIterator extends JsonLinesIterator implements \Iterator
{
    /**
     * \Iterator::current()
     */
    public function current()
    {
        if (! $this->getHandle()) {
            return null;
        }

        $object = JsonLines::jsonDecode($this->currentContent, $this->associative, $this->currentLineNumber);
        return $object
            ? new MetadataWrapper($this->currentLine, $object)
            : null;
    }
}
