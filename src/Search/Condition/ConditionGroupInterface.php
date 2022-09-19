<?php 
namespace AdinanCenci\JsonLines\Search\Condition;

interface ConditionGroupInterface extends ConditionInterface 
{
    /**
     * @param array|string[] $property Either a simle string or an array of strings to reache nested properties.
     * @param mixed $valueToCompare
     * @param string $operatorId
     * @return self
     */
    public function condition($property, $valueToCompare, string $operatorId = '=') : ConditionGroupInterface;

    public function andConditionGroup() : ConditionGroupInterface;

    public function orConditionGroup() : ConditionGroupInterface;
}
