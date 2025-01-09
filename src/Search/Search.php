<?php

namespace AdinanCenci\JsonLines\Search;

use AdinanCenci\JsonLines\Search\Iterator\MetadataIterator;
use AdinanCenci\FileEditor\Search\Search as FileSearch;

class Search extends FileSearch
{
    /**
     * {@inheritdoc}
     */
    public function find(): array
    {
        $results = $this->retrieveAndOrder();
        array_walk($results, function (&$item) {
            $item = $item->data;
        });

        return $results;
    }

    /**
     * {@inheritdoc}
     */
    protected function getIterator(): \Iterator
    {
        return new MetadataIterator($this->file->fileName, $this->file->associative);
    }
}
