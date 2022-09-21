<?php 
namespace AdinanCenci\JsonLines\Search\Operator\Exception;

class InvalidOperatorData extends \Exception 
{
    public static function invalidType($operatorName, $expected, $actual) 
    {
        $message = 'Invalid data given to ' . $operatorName . ' operator, expected ' . 
        $expected . ', ' . $actual . ' given.';
        return new self($message);
    }
}
