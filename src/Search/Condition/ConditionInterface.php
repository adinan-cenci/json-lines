<?php 
namespace AdinanCenci\JsonLines\Search\Condition;

interface ConditionInterface 
{
    /**
     * @param array|\stdClass
     * @return bool
     */
    public function evaluate($data) : bool;
}
