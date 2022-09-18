<?php 
namespace AdinanCenci\JsonLines\Search;

use AdinanCenci\JsonLines\JsonLines;
use AdinanCenci\JsonLines\Search\Condition\ConditionGroupInterface;
use AdinanCenci\JsonLines\Search\Condition\AndConditionGroup;
use AdinanCenci\JsonLines\Search\Condition\OrConditionGroup;

class Search implements ConditionGroupInterface 
{
    protected JsonLines $jsonLines;
    protected AndConditionGroup $mainConditionGroup;

    public function __construct(JsonLines $jsonLines) 
    {
        $this->jsonLines = $jsonLines;
        $this->mainConditionGroup = new AndConditionGroup();
    }

    /**
     * It will iterate through the file and return the objects that match
     * the specified criteria.
     */
    public function find() : array
    {
        $results = [];
        foreach ($this->jsonLines->objects as $line => $object) {
            if ($this->evaluate($object)) {
                $results[ $line ] = $object;
            }
        }
        return $results;
    }

    /**
     * @inheritDoc
     */
    public function evaluate($data) : bool
    {
        return $this->mainConditionGroup->evaluate($data);
    }

    /**
     * @inheritDoc
     */
    public function condition(string $property, $valueToCompare, string $operatorId = '=') : self
    {
        $this->mainConditionGroup->condition($property, $valueToCompare, $operatorId);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function andConditionGroup() : AndConditionGroup
    {
        return $this->mainConditionGroup->andConditionGroup();
    }

    /**
     * @inheritDoc
     */
    public function orConditionGroup() : OrConditionGroup
    {
        return $this->mainConditionGroup->orConditionGroup();
    }
}
