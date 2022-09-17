<?php 
namespace AdinanCenci\JsonLines\Search;

class AndConditionGroup 
{
    protected array $conditions;

    public function condition(string $property, $valueToCompare, string $operatorId = '=') 
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
}
