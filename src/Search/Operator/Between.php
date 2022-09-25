<?php 
namespace AdinanCenci\JsonLines\Search\Operator;

class Between extends OperatorBase implements OperatorInterface 
{
    /**
     * @inheritDoc
     */
    public function matches() : bool
    {
        if (! is_numeric($this->actualValue)) {
            return false;
        }

        $min = reset($this->valueToCompare);
        $max = end($this->valueToCompare);

        return $this->actualValue >= $min && $this->actualValue <= $max;
    }

    /**
     * @inheritDoc
     */
    protected function validateValueToCompare() : void
    {
        if (! is_array($this->valueToCompare)) {
            throw new \InvalidArgumentException($this->invalidDataError('BETWEEN', 'array', gettype($this->valueToCompare)));
        }

        if (
            count($this->valueToCompare) < 2 ||
            (!is_numeric(reset($this->valueToCompare)) || !is_numeric(end($this->valueToCompare)))
        ) {
            throw new \InvalidArgumentException($this->invalidDataError('BETWEEN', 'array with two numeric values', ''));
        }
    }

    protected function normalizeScalar($data) 
    {
        if (is_numeric($data)) {
            return $data;
        }

        return parent::normalizeScalar($data);
    }
}
