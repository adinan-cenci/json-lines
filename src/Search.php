<?php 
namespace AdinanCenci\JsonLines;

class Search 
{
    protected $jsonLines;
    protected $results = [];
    protected $conditions = [];

    public function __construct(JsonLines $jsonLines) 
    {
        $this->jsonLines = $jsonLines;
    }

    public function find() : array
    {
        $this->execute();
        return $this->results;
    }

    public function includes(string $field, $value) 
    {
        $this->conditions[] = [
            'operator' => 'includes', 
            'field'    => $field, 
            'value'    => $value
        ];

        return $this;
    }

    public function like(string $field, $value) 
    {
        $this->conditions[] = [
            'operator' => 'likes', 
            'field'    => $field, 
            'value'    => $value
        ];

        return $this;
    }

    public function equals(string $field, $value) 
    {
        $this->conditions[] = [
            'operator' => 'equals', 
            'field'    => $field, 
            'value'    => $value
        ];

        return $this;
    }

    protected function execute() : void
    {
        foreach($this->jsonLines->objects as $line => $obj) {
            if (! $this->matchConditions($obj)) {
                continue;
            }
            $this->results[ $line ] = $obj;
        }
    }

    protected function matchConditions($obj) : bool
    {
        foreach($this->conditions as $cond) {
            if (! $this->matchCondition($obj, $cond)) {
                return false;
            }
        }

        return true;
    }

    protected function matchCondition($obj, $cond) : bool
    {
        if ($cond['operator'] == 'includes') {
            if (! $this->matchIncludesOperator($obj, $cond['field'], $cond['value'])) {
                return false;
            }
        }

        if ($cond['operator'] == 'likes') {
            if (! $this->matchLikesOperator($obj, $cond['field'], $cond['value'])) {
                return false;
            }
        }

        if ($cond['operator'] == 'equals') {
            if (! $this->matchEqualsOperator($obj, $cond['field'], $cond['value'])) {
                return false;
            }
        }

        return true;
    }

    protected function matchIncludesOperator($obj, $field, $value) : bool
    {
        if (!$objValue = $this->getValue($obj, $field)) {
            return false;
        }

        $objValue = (array) $objValue;
        $value    = (array) $value;

        array_walk($objValue, function(&$v) { $v = strtolower($v); });
        array_walk($value, function(&$v) { $v = strtolower($v); });

        return (bool) array_intersect($objValue, $value);
    }

    protected function matchLikesOperator($obj, $field, $value) : bool
    {
        if (!$objValue = $this->getValue($obj, $field)) {
            return false;
        }

        $objValue = (array) $objValue;
        $value    = (array) $value;

        array_walk($objValue, function(&$v) { $v = strtolower($v); });
        array_walk($value, function(&$v) { $v = strtolower($v); });

        foreach ($objValue as $obv) {
            foreach ($value as $v) {
                if (substr_count($obv, $v)) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function matchEqualsOperator($obj, $field, $value) : bool
    {
        if (!$objValue = $this->getValue($obj, $field)) {
            return false;
        }

        return $objValue == $value;
    }

    protected function getValue($object, $field) 
    {
        if (is_array($object)) {
            return empty($object[$field]) ? null : $object[$field];
        }

        if (is_object($object)) {
            return empty($object->{$field}) ? null : $object->{$field};
        }
    }
}
