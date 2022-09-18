<?php 
namespace AdinanCenci\JsonLines\Search\Operator;

class Equals extends OperatorBase implements OperatorInterface 
{
    public function matches() : bool
    {
        return $this->actualValue == $this->valueToCompare;
    }
}
