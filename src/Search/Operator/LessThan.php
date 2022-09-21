<?php 
namespace AdinanCenci\JsonLines\Search\Operator;

use AdinanCenci\JsonLines\Search\Operator\Exception\InvalidOperatorData;

class LessThan extends OperatorBase implements OperatorInterface 
{
    public function matches() : bool
    {
        if (! is_numeric($this->actualValue)) {
            return false;
        }

        return $this->actualValue < $this->valueToCompare;
    }

    protected function validateValueToCompare() 
    {
        if (!is_numeric($this->valueToCompare)) {
            throw InvalidOperatorData::invalidType('LESS THAN', 'numeric', gettype($this->valueToCompare));
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
