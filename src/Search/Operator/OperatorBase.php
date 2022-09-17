<?php 
namespace AdinanCenci\JsonLines\Search\Operator;

abstract class OperatorBase 
{
    protected $actualValue;
    protected $valueToCompare;

    public function __construct($actualValue, $valueToCompare) 
    {
        $this->actualValue = self::normalize($actualValue);
        $this->valueToCompare = self::normalize($valueToCompare);
    }

    public function matches() : bool
    {

    }

    public static function isScalar($data) : bool
    {
        if (is_bool($data)) {
            return true;
        }

        if (is_null($data)) {
            return true;
        }

        return is_scalar($data);
    }

    public static function normalize($data) 
    {
        $data = is_object($data) 
            ? (array) $data 
            : $data;

        return is_array($data) 
            ? self::normalizeArray($data)
            : self::normalizeScalar($data);
    }

    public static function strToLower($data) 
    {
        if (is_string($data)) {
            return strtolower($data);
        }

        return $data;
    }

    public static function normalizeScalar($data) 
    {
        if (is_bool($data)) {
            return $data;
        }

        return trim( strtolower( (string) $data) );
    }

    public static function normalizeArray(array $data) : array
    {
        foreach ($data as $key => $value) {
            if (self::isScalar($value)) {
                $data[$key] = self::normalizeScalar($value);
            }
        }

        return $data;
    }
}
