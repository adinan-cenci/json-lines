<?php 
namespace AdinanCenci\JsonLines\Search\Condition;

interface ConditionInterface 
{
    /**
     * Will determine if $data passes the condition.
     * 
     * @param array|\stdClass $data
     * @return bool
     */
    public function evaluate($data) : bool;
}
