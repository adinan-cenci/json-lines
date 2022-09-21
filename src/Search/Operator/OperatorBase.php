<?php 
namespace AdinanCenci\JsonLines\Search\Operator;

abstract class OperatorBase implements OperatorInterface 
{
    protected $actualValue;
    protected $valueToCompare;

    public function __construct($actualValue, $valueToCompare) 
    {
        $this->actualValue = self::normalize($actualValue);
        $this->valueToCompare = self::normalize($valueToCompare);
        $this->validateValueToCompare();
    }

    /**
     * @inheritDoc
     */
    public function matches() : bool
    {

    }

    protected function validateValueToCompare() 
    {
        return true;
    }

    protected static function isScalar($data) : bool
    {
        if (is_bool($data)) {
            return true;
        }

        if (is_null($data)) {
            return true;
        }

        return is_scalar($data);
    }

    protected static function normalize($data) 
    {
        $class = get_called_class();

        $data = is_object($data) 
            ? (array) $data 
            : $data;

        return is_array($data) 
            ? $class::normalizeArray($data)
            : $class::normalizeScalar($data);
    }

    protected static function normalizeScalar($data) 
    {
        if (is_bool($data)) {
            return $data;
        }

        return trim( strtolower( (string) $data) );
    }

    protected static function normalizeArray(array $data) : array
    {
        $class = get_called_class();

        foreach ($data as $key => $value) {
            if (self::isScalar($value)) {
                $data[$key] = $class::normalizeScalar($value);
            }
        }

        if (self::isNumericalArray($data)) {
            sort($data);
        }

        return $data;
    }

    protected static function strToLower($data) 
    {
        if (is_string($data)) {
            return strtolower($data);
        }

        return $data;
    }

    protected static function isNumericalArray(array $array) : bool
    {
        $keys = array_keys($array);
        return $keys === array_keys($keys);
    }
}
