<?php 
namespace AdinanCenci\JsonLines\Search;

use AdinanCenci\FileEditor\Search\Search as BaseSearch;

class Search extends BaseSearch 
{
    /**
     * @inheritDoc
     */
    public function find() : array
    {
        $results = [];
        foreach ($this->jsonLines->objects as $line => $object) {
            if ($object && $this->evaluate($object)) {
                $results[ $line ] = $object;
            }
        }
        return $results;
    }
}
