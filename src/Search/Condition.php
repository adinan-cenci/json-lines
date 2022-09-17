<?php 
namespace AdinanCenci\JsonLines\Search;

use AdinanCenci\JsonLines\Search\Operator\OperatorInterface;
use AdinanCenci\JsonLines\Search\Operator\Equals;
use AdinanCenci\JsonLines\Search\Operator\Includes;

class Condition 
{
    protected string $property;
    protected $valueToCompare;
    protected string $operatorId;
    protected bool $negate = false;

    public function __construct($property, $valueToCompare, string $operatorId = '=') 
    {
        $this->property = $property;
        $this->valueToCompare = $valueToCompare;
        $this->operatorId = $operatorId;
    }

    public function evaluate($data) : bool
    {
        $actualValue = $this->getValue($data, $this->property);
        $operator = $this->getOperador($operatorId, $actualValue, $this->valueToCompare);
        return $operator->matches();
    }

    protected function getOperador(string $operatorId, $actualValue, $valueToCompare) 
    {
        switch ($operatorId) {
            case '=' :
            case '!=' :
                return new Equals($actualValue, $valueToCompare);
                break;
            case 'IN' :
            case 'NOT IN' :
                return new Includes($actualValue, $valueToCompare);
                break;
            case 'LIKE' :

                break;
        }
    }
}
