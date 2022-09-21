<?php 
namespace AdinanCenci\JsonLines\Search\Condition;

use AdinanCenci\JsonLines\Search\Operator\OperatorInterface;
use AdinanCenci\JsonLines\Search\Operator\Equals;
use AdinanCenci\JsonLines\Search\Operator\Includes;

class Condition implements ConditionInterface 
{
    protected array $property;
    protected $valueToCompare;
    protected string $operatorClass;
    protected bool $negate = false;

    const EQUALS_OPERATOR = 'AdinanCenci\JsonLines\Search\Operator\Equals';
    const INCLUDES_OPERATOR = 'AdinanCenci\JsonLines\Search\Operator\Includes';
    const LIKES_OPERATOR = 'AdinanCenci\JsonLines\Search\Operator\Likes';
    const BETWEEM_OPERATOR = 'AdinanCenci\JsonLines\Search\Operator\Between';
    const IS_NULL_OPERATOR = 'AdinanCenci\JsonLines\Search\Operator\IsNull';
    const LESS_THEN_OPERATOR = 'AdinanCenci\JsonLines\Search\Operator\LessThen';
    const GREATER_THEN_OPERATOR = 'AdinanCenci\JsonLines\Search\Operator\GreaterThen';
    const LESS_OR_EQUAL_TO_OPERATOR = 'AdinanCenci\JsonLines\Search\Operator\LessOrEqualTo';
    const GREATER_OR_EQUAL_TO_OPERATOR = 'AdinanCenci\JsonLines\Search\Operator\GreaterOrEqualTo';

    /**
     * @param array|string[] $property Either a simle string or an array of strings to reache nested properties.
     * @param mixed $valueToCompare
     * @param string $operatorId
     */
    public function __construct($property, $valueToCompare, string $operatorId = '=') 
    {
        $this->property       = (array) $property;
        $this->valueToCompare = $valueToCompare;
        $this->operatorClass  = $this->getOperatorClass($operatorId, $negation);
        $this->negation       = $negation;
    }

    /**
     * @inheritDoc
     */
    public function evaluate($data) : bool
    {
        $actualValue = $this->getValue($data, $this->property);
        $operator    = $this->instantiateOperator($actualValue, $this->valueToCompare, $this->operatorClass);
        $result      = $operator->matches();

        return $this->negation
            ? !$result
            : $result;
    }

    /**
     * Retrieves the property from $data we are going to evaluate.
     * 
     * @param array|\stdClass $data
     * @param array $property
     * @return string|int|float|bool|null|array|\stdClass
     */
    protected function getValue($data, array $property) 
    {
        foreach ($property as $part) {
            if (is_object($data) && isset($data->{$part})) {
                $data = $data->{$part};
            } elseif (is_array($data) && isset($data[$part])) {
                $data = $data[$part];
            } else {
                return null;
            }
        }

        return $data;
    }

    protected function instantiateOperator($actualValue, $valueToCompare, $operatorClass) 
    {
        return new $operatorClass($actualValue, $valueToCompare);
    }

    protected function getOperatorClass(string $operatorId, &$negation = false) : ?string
    {
        $negation = substr_count($operatorId, '!') || substr_count($operatorId, 'NOT');

        switch ($operatorId) {
            case '=' :
            case '!=' :
                return self::EQUALS_OPERATOR;
                break;
            case 'IN' :
            case 'NOT IN' :
            case '!IN' :
                return self::INCLUDES_OPERATOR;
                break;
            case 'LIKE' :
            case 'NOT LIKE' :
            case '!LIKE' :
                return self::LIKES_OPERATOR;
                break;
            case 'BETWEEN' :
            case 'NOT BETWEEN' :
            case '!BETWEEN' :
                return self::BETWEEM_OPERATOR;
                break;
            case 'IS NULL':
            case 'NOT NULL':
            case '!NULL':
                return self::IS_NULL_OPERATOR;
                break;
            case '<':
                return self::LESS_THEN_OPERATOR;
                break;
            case '>':
                return self::GREATER_THEN_OPERATOR;
                break;
            case '<=':
                return self::LESS_OR_EQUAL_TO_OPERATOR;
                break;
            case '>=':
                return self::GREATER_OR_EQUAL_TO_OPERATOR;
                break;
        }

        return null;
    }
}
