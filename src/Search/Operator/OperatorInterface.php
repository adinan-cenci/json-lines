<?php 
namespace AdinanCenci\JsonLines\Search\Operator;

interface OperatorInterface 
{
    /**
     * @param mixed $actualValue
     * @param mixed $valueToCompare
     */
    public function __construct($actualValue, $valueToCompare);

    /**
     * 
     * @return bool
     */
    public function matches() : bool;
}
