<?php 
namespace AdinanCenci\JsonLines\Search;

use AdinanCenci\JsonLines\JsonLines;
use AdinanCenci\JsonLines\Search\Condition\AndConditionGroup;
use AdinanCenci\JsonLines\Search\Condition\OrConditionGroup;

class Search 
{
    protected JsonLines $jsonLines;
    protected AndConditionGroup $mainConditionGroup;

    public function __construct(JsonLines $jsonLines) 
    {
        $this->jsonLines = $jsonLines;
        $this->mainConditionGroup = new AndConditionGroup();
    }

    public function find() : array
    {
        $results = [];
        foreach ($this->jsonLines->objects as $line => $object) {
            if ($this->mainConditionGroup->evaluate($object)) {
                $results[ $line ] = $object;
            }
        }
        return $results;
    }

    public function condition(string $property, $valueToCompare, string $operatorId = '=') : self
    {
        $this->mainConditionGroup->condition($property, $valueToCompare, $operatorId);
        return $this;
    }

    public function andConditionGroup() : AndConditionGroup
    {
        return $this->mainConditionGroup->andConditionGroup();
    }

    public function orConditionGroup() : OrConditionGroup
    {
        return $this->mainConditionGroup->orConditionGroup();
    }
}
