<?php 
namespace AdinanCenci\JsonLines\Search\Condition;

class OrConditionGroup extends AndConditionGroup
{
    public function evaluate($data) : bool
    {
        foreach ($this->conditions as $condition) {
            if ($condition->evaluate($data)) {
                return true;
            }
        }

        return false;
    }
}