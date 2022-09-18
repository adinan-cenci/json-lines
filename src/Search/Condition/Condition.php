<?php 
namespace AdinanCenci\JsonLines\Search\Condition;

use AdinanCenci\JsonLines\Search\Operator\OperatorInterface;
use AdinanCenci\JsonLines\Search\Operator\Equals;
use AdinanCenci\JsonLines\Search\Operator\Includes;

class Condition implements ConditionInterface 
{
    protected string $property;
    protected $valueToCompare;
    protected string $operatorClass;
    protected bool $negate = false;

    public function __construct(string $property, $valueToCompare, string $operatorId = '=') 
    {
        $this->property = $property;
        $this->valueToCompare = $valueToCompare;
        $this->operatorClass = $this->getOperatorClass($operatorId, $negation);
        $this->negation = $negation;
    }

    /**
     * @inheritDoc
     */
    public function evaluate($data) : bool
    {
        $actualValue = $this->getValue($data, $this->property);
        $operator = new $this->operatorClass($actualValue, $this->valueToCompare);
        $result = $operator->matches();

        return $this->negation
            ? !$result
            : $result;
    }

    /**
     * @param array|\stdClass $data
     * @param string $property
     * @return string|int|float|bool|null|array|\stdClass
     */
    protected function getValue($data, string $property) 
    {
        return is_object($data)
            ? $data->{$property}
            : $data[ $property ];
    }

    protected function getOperatorClass(string $operatorId, &$negation = false) : ?string
    {
        $negation = substr_count($operatorId, '!') || substr_count($operatorId, 'NOT');

        switch ($operatorId) {
            case '=' :
            case '!=' :
                return 'AdinanCenci\JsonLines\Search\Operator\Equals';
                break;
            case 'IN' :
            case 'NOT IN' :
                return 'AdinanCenci\JsonLines\Search\Operator\Includes';
                break;
            case 'LIKE' :
            case 'NOT LIKE' :
                return 'AdinanCenci\JsonLines\Search\Operator\Likes';
                break;
        }

        return null;
    }
}
