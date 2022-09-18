<?php
declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\Search\Operator\Likes;

final class OperatorLikesTest extends Base
{
    public function testCompareStrings() 
    {
        $actualValue = 'Highland Glory';
        $toCompare = 'Highland Glory';

        $operator = new Likes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }
    
    public function testCompareStringsCaseInsensitive()
    {
        $actualValue = 'Highland Glory';
        $toCompare = 'HIGHLAND GLORY';

        $operator = new Likes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareSubstring() 
    {
        $actualValue = 'Highland Glory';
        $toCompare = 'glory';

        $operator = new Likes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareStringToArray() 
    {
        $actualValue = 'Highland Glory';
        $toCompare = ['glory'];

        $operator = new Likes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareArrayToString() 
    {
        $actualValue = ['Highland Glory'];
        $toCompare = 'Highland Glory';

        $operator = new Likes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareArrayToStringCaseInsensitive() 
    {
        $actualValue = ['Highland Glory'];
        $toCompare = 'HIGHLAND GLORY';

        $operator = new Likes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareArrayToSubstring() 
    {
        $actualValue = ['Highland Glory'];
        $toCompare = 'glory';

        $operator = new Likes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareArrayToArray() 
    {
        $actualValue = ['Highland Glory'];
        $toCompare = ['Highland Glory'];

        $operator = new Likes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareArrayToArraySubstring() 
    {
        $actualValue = ['Highland Glory'];
        $toCompare = ['Glory'];

        $operator = new Likes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareArrayToArrayCaseInsensitive() 
    {
        $actualValue = ['Highland Glory'];
        $toCompare = ['HIGHLAND GLORY'];

        $operator = new Likes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareIntersectingArrays() 
    {
        $actualValue = ['Highland Glory', 'Gloryhammer'];
        $toCompare = ['Highland Glory'];

        $operator = new Likes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = ['Highland Glory', 'Gloryhammer'];
        $toCompare = ['hammer'];

        $operator = new Likes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = ['Highland Glory'];
        $toCompare = ['Highland Glory', 'Gloryhammer'];

        $operator = new Likes($actualValue, $toCompare);
        $this->assertFalse($operator->matches());
    }
}
