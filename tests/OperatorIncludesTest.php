<?php
declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\Search\Operator\Includes;

final class OperatorIncludesTest extends Base
{
    public function testCompareScalarValues() 
    {
        $actualValue = 'Highland Glory';
        $toCompare = 'Highland Glory';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = 'Highland Glory';
        $toCompare = 'HIGHLAND GLORY';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = 'Rhapsody of Fire';
        $toCompare = 'Rhapsody';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertFalse($operator->matches());

        //---------

        $actualValue = null;
        $toCompare = '';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = 0;
        $toCompare = '0';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = 0;
        $toCompare = '';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertFalse($operator->matches());

        //---------

        $actualValue = true;
        $toCompare = '0';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertFalse($operator->matches());

        //---------

        $actualValue = true;
        $toCompare = '1';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareArraysToScalarValues() 
    {
        $actualValue = ['Highland Glory'];
        $toCompare = 'Highland Glory';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = ['Highland Glory'];
        $toCompare = 'HIGHLAND GLORY';

        $operator = new Includes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

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

        //---------

        $actualValue = ['Highland Glory'];
        $toCompare = ['HIGHLAND GLORY'];

        $operator = new Includes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

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
