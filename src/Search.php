<?php 
namespace AdinanCenci\JsonLines;

class Search 
{
    protected JsonLines $jsonLines;

    public function __construct($jsonLines) 
    {
        $this->jsonLines = $jsonLines;
    }

    public function find() 
    {
        foreach ($this->objects as $object) {

        }
        return $this->results;
    }

    public function condition(string $property, $valueToCompare, string $operatorId = '=') 
    {
        $this->defaultConditionGroup->condition($property, $valueToCompare, $operatorId);
        return $this;
    }

    public function andConditionGroup()
}
