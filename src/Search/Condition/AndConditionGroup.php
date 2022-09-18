<?php 
namespace AdinanCenci\JsonLines\Search\Condition;

use AdinanCenci\JsonLines\Search\Condition\OrConditionGroup;

class AndConditionGroup implements ConditionInterface, ConditionGroupInterface 
{
    /**
     * @param ConditionInterface[] $conditions
     */
    protected array $conditions = [];

    /**
     * @inheritDoc
     */
    public function condition(string $property, $valueToCompare, string $operatorId = '=') : self
    {
        $condition = new Condition($property, $valueToCompare, $operatorId);
        $this->conditions[] = $condition;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function evaluate($data) : bool
    {
        foreach ($this->conditions as $condition) {
            if (! $condition->evaluate($data)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function andConditionGroup() : AndConditionGroup
    {
        $group = new AndConditionGroup();
        $this->conditions[] = $group;
        return $group;
    }

    /**
     * @inheritDoc
     */
    public function orConditionGroup() : OrConditionGroup
    {
        $group = new OrConditionGroup();
        $this->conditions[] = $group;
        return $group;
    }
}
