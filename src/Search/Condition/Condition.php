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
        $operator    = new $this->operatorClass($actualValue, $this->valueToCompare);
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
