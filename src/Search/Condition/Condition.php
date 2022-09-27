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
    const LESS_THEN_OPERATOR = 'AdinanCenci\JsonLines\Search\Operator\LessThan';
    const GREATER_THEN_OPERATOR = 'AdinanCenci\JsonLines\Search\Operator\GreaterThan';
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
        $class                = $this->getOperatorClass($operatorId, $negation);

        if (is_null($class)) {
            throw new \InvalidArgumentException('Unrecognized operator ' . $operatorId);
        }

        $this->operatorClass = $class;
        $this->negation      = $negation;
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
     * Retrieves from $data the property we are going to evaluate.
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

    /**
     * @param mixed $acualValue
     * @param mixed $valueToCompare
     * @param string $operatorClass The class to be instantiated.
     * @return OperatorInterface
     */
    protected function instantiateOperator($actualValue, $valueToCompare, $operatorClass) : OperatorInterface
    {
        return new $operatorClass($actualValue, $valueToCompare);
    }

    /**
     * @param string $operatorId A string representing an operation.
     * @param bool $negation Turns true if $operatorId is negating the operation.
     * @return string|null The class name for the operation.
     */
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
