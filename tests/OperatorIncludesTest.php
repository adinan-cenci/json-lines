<?php
declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\Search\Operator\Includes;

final class OperatorIncludesTest extends Base
{
    public function testCompareStrings() 
    {
        $actualValue = 'Highland Glory';
        $toCompare = 'Highland Glory';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareStringsCaseInsensitive() 
    {
        $actualValue = 'Highland Glory';
        $toCompare = 'HIGHLAND GLORY';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareSubstring() 
    {
        $actualValue = 'Rhapsody of Fire';
        $toCompare = 'Rhapsody';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertFalse($operator->matches());
    }

    public function testCompareStringToArray() 
    {
        $actualValue = 'Rhapsody of Fire';
        $toCompare = ['Rhapsody of Fire'];

        $operator = new Includes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareNullToEmptyString()
    {
        $actualValue = null;
        $toCompare = '';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }
    
    public function testCompareIntegerToNumericalString() 
    {
        $actualValue = 0;
        $toCompare = '0';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareZeroToEmptyString() 
    {
        $actualValue = 0;
        $toCompare = '';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertFalse($operator->matches());
    }

    public function testCompareTrueToZeroString() 
    {
        $actualValue = true;
        $toCompare = '0';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertFalse($operator->matches());
    }

    public function testCompareTrueToOneString() 
    {
        $actualValue = true;
        $toCompare = '1';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareArrayToString() 
    {
        $actualValue = ['Highland Glory'];
        $toCompare = 'Highland Glory';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareArrayToStringCaseInsensitive() 
    {
        $actualValue = ['Highland Glory'];
        $toCompare = 'HIGHLAND GLORY';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }
    
    public function testCompareArrayToSubString() 
    {
        $actualValue = ['Rhapsody of Fire'];
        $toCompare = 'Rhapsody';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertFalse($operator->matches());
    }

    public function testCompareArrayToArray() 
    {
        $actualValue = ['Highland Glory'];
        $toCompare = ['Highland Glory'];

        $operator = new Includes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }
    
    public function testCompareArrayToArrayCaseInsensitive() 
    {
        $actualValue = ['Highland Glory'];
        $toCompare = ['HIGHLAND GLORY'];

        $operator = new Includes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareIntersectingArrays()
    {
        $actualValue = ['Highland Glory', 'Gloryhammer'];
        $toCompare = ['Highland Glory'];

        $operator = new Includes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = ['Highland Glory'];
        $toCompare = ['Highland Glory', 'Gloryhammer'];

        $operator = new Includes($actualValue, $toCompare);
        $this->assertFalse($operator->matches());
    }
}
