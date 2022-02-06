<?php 
namespace AdinanCenci\JsonLines;

class JsonLinesSearch 
{
    protected $jsonLines;
    protected $results = [];
    protected $conditions = [];

    public function __construct($jsonLines) 
    {
        $this->jsonLines = $jsonLines;
    }

    public function find() 
    {
        $this->execute();
        return $this->results;
    }

    public function includes($field, $value) 
    {
        $this->conditions[] = [
            'operator' => 'includes', 
            'field'    => $field, 
            'value'    => $value
        ];

        return $this;
    }

    protected function execute() 
    {
        foreach($this->jsonLines->objects as $line => $obj) {
            if (! $this->matchConditions($obj)) {
                continue;
            }
            $this->results[ $line ] = $obj;
        }
    }

    protected function matchConditions($obj) 
    {
        foreach($this->conditions as $cond) {
            if (! $this->matchCondition($obj, $cond)) {
                return false;
            }
        }

        return true;
    }

    protected function matchCondition($obj, $cond) 
    {
        if ($cond['operator'] == 'includes') {
            if (! $this->matchIncludesOperator($obj, $cond['field'], $cond['value'])) {
                return false;
            }
        }

        return true;
    }

    protected function matchIncludesOperator($obj, $field, $value) 
    {
        if (empty($obj->{$field})) {
            return false;
        }

        $objValue = (array) $obj->{$field};
        $value    = (array) $value;

        return (bool) array_intersect($objValue, $value);
    }
}
