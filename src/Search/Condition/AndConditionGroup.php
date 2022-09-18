<?php 
namespace AdinanCenci\JsonLines\Search\Condition;

use AdinanCenci\JsonLines\Search\Condition\OrConditionGroup;

class AndConditionGroup implements ConditionInterface 
{
    protected array $conditions = [];

    public function condition(string $property, $valueToCompare, string $operatorId = '=') : self
    {
        $condition = new Condition($property, $valueToCompare, $operatorId);
        $this->conditions[] = $condition;
        return $this;
    }

    public function evaluate($data) : bool
    {
        foreach ($this->conditions as $condition) {
            if (! $condition->evaluate($data)) {
                return false;
            }
        }

        return true;
    }

    public function andConditionGroup() : AndConditionGroup
    {
        $group = new AndConditionGroup();
        $this->conditions[] = $group;
        return $group;
    }

    public function orConditionGroup() : OrConditionGroup
    {
        $group = new OrConditionGroup();
        $this->conditions[] = $group;
        return $group;
    }
}
