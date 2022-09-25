<?php 
namespace AdinanCenci\JsonLines\Search\Operator;

class GreaterOrEqualTo extends LessThan implements OperatorInterface 
{
    /**
     * @inheritDoc
     */
    public function matches() : bool
    {
        if (! is_numeric($this->actualValue)) {
            return false;
        }

        return $this->actualValue >= $this->valueToCompare;
    }
}