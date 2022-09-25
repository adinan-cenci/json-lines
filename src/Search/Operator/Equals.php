<?php 
namespace AdinanCenci\JsonLines\Search\Operator;

class Equals extends OperatorBase implements OperatorInterface 
{
    /**
     * @inheritDoc
     */
    public function matches() : bool
    {
        return $this->actualValue == $this->valueToCompare;
    }
}
