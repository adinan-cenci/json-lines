<?php 
namespace AdinanCenci\JsonLines\Search\Operator;

class LessOrEqualTo extends LessThen implements OperatorInterface 
{
    public function matches() : bool
    {
        if (! is_numeric($this->actualValue)) {
            return false;
        }

        return $this->actualValue <= $this->valueToCompare;
    }
}
