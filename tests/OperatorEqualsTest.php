<?php
declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\Search\Operator\Equals;

final class OperatorEqualsTest extends Base
{
    public function testCompareScalarValues() 
    {
        $actualValue = 'Highland Glory';
        $toCompare = 'Highland Glory';

        $operator = new Equals($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = 'Highland Glory';
        $toCompare = 'HIGHLAND GLORY';

        $operator = new Equals($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = null;
        $toCompare = '';

        $operator = new Equals($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = 0;
        $toCompare = '0';

        $operator = new Equals($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = 0;
        $toCompare = '';

        $operator = new Equals($actualValue, $toCompare);
        $this->assertFalse($operator->matches());

        //---------

        $actualValue = true;
        $toCompare = '0';

        $operator = new Equals($actualValue, $toCompare);
        $this->assertFalse($operator->matches());

        //---------

        $actualValue = true;
        $toCompare = '1';

        $operator = new Equals($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareArraysToScalarValues() 
    {
        $actualValue = ['Highland Glory'];
        $toCompare = 'Highland Glory';

        $operator = new Equals($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = ['Highland Glory'];
        $toCompare = 'HIGHLAND GLORY';

        $operator = new Equals($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareArrayToArray() 
    {
        $actualValue = ['Highland Glory'];
        $toCompare = ['Highland Glory'];

        $operator = new Equals($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = ['Highland Glory'];
        $toCompare = ['HIGHLAND GLORY'];

        $operator = new Equals($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = ['Highland Glory', 'Gloryhammer'];
        $toCompare = ['Highland Glory'];

        $operator = new Equals($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = ['Highland Glory'];
        $toCompare = ['Highland Glory', 'Gloryhammer'];

        $operator = new Equals($actualValue, $toCompare);
        $this->assertFalse($operator->matches());
    }
}
