<?php 
namespace AdinanCenci\JsonLines\Search\Operator;

class Includes extends OperatorBase implements OperatorInterface 
{
    public function matches() : bool
    {
        if (self::isScalar($this->actualValue) && self::isScalar($this->valueToCompare)) {
            return $this->actualValue == $this->valueToCompare;
        }

        if (is_array($this->actualValue) && self::isScalar($this->valueToCompare)) {
            return in_array($this->valueToCompare, $this->actualValue);
        }

        if (is_array($this->actualValue) && is_array($this->valueToCompare)) {
            return count(array_intersect($this->actualValue, $this->valueToCompare)) == count($this->valueToCompare);
        }

        return false;
    }
}
