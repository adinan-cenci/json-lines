<?php 
namespace AdinanCenci\JsonLines\Search\Operator;

interface OperatorInterface 
{
    public function __construct($actualValue, $valueToCompare);

    public function matches() : bool;
}
