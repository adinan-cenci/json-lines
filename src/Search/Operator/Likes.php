<?php 
namespace AdinanCenci\JsonLines\Search\Operator;

class Likes extends OperatorBase implements OperatorInterface 
{
    public function matches() : bool
    {
        if (self::isScalar($this->actualValue) && self::isScalar($this->valueToCompare)) {
            return substr_count($this->actualValue, $this->valueToCompare);
        }

        if (is_array($this->actualValue) && self::isScalar($this->valueToCompare)) {
            foreach ($this->actualValue as $av) {
                if (is_scalar($av) && substr_count($av, $this->valueToCompare)) {
                    return true;
                }
            }

            return false;
        }

        if (is_array($this->actualValue) && is_array($this->valueToCompare)) {
            $matches = 0;
            foreach ($this->valueToCompare as $cv) {
                foreach ($this->actualValue as $av) {
                    $matches += is_scalar($cv) && is_scalar($av) && substr_count($av, $cv);
                }
            }

            return $matches >= count($this->valueToCompare);
        }

        return false;
    }
}