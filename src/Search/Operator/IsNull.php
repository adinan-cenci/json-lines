<?php 
namespace AdinanCenci\JsonLines\Search\Operator;

class IsNull extends OperatorBase implements OperatorInterface 
{
    public function matches() : bool
    {
        return is_null($this->actualValue);
    }

    protected static function normalizeScalar($data) 
    {
        if (is_null($data)) {
            return null;
        }

        return parent::normalizeScalar($data);
    }
}
