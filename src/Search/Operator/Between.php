<?php 
namespace AdinanCenci\JsonLines\Search\Operator;

class Between extends OperatorBase implements OperatorInterface 
{
    public function matches() : bool
    {
        if (! is_numeric($this->actualValue)) {
            return false;
        }

        $min = reset($this->valueToCompare);
        $max = end($this->valueToCompare);

        return $this->actualValue >= $min && $this->actualValue <= $max;
    }

    protected function validate() 
    {
        if (!is_array($this->valueToCompare)) {
            throw new \Exception('Invalid data to compare given to BETWEEN operator, expected array, ' . gettype($this->valueToCompare) . ' given');
        }

        if (count($this->valueToCompare) < 2) {
            throw new \Exception('Invalid data to compare given to BETWEEN operator, expected array with two numeric values');
        }

        if (!is_numeric(reset($this->valueToCompare)) || !is_numeric(end($this->valueToCompare))) {
            throw new \Exception('Invalid data to compare given to BETWEEN operator, expected array with two numeric values');
        }
    }

    protected static function normalizeScalar($data) 
    {
        if (is_numeric($data)) {
            return $data;
        }

        return parent::normalizeScalar($data);
    }
}
