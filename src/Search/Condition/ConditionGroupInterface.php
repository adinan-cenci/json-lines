<?php 
namespace AdinanCenci\JsonLines\Search\Condition;

interface ConditionGroupInterface extends ConditionInterface 
{
    public function condition(string $property, $valueToCompare, string $operatorId = '=') : ConditionGroupInterface;

    public function andConditionGroup() : ConditionGroupInterface;

    public function orConditionGroup() : ConditionGroupInterface;
}
