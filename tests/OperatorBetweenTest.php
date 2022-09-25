<?php
declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\Search\Operator\Between;

final class OperatorBetweenTest extends Base
{
    public function testCompareValueInRange() 
    {
        $actualValue = 5;
        $minMax = [0, 10];

        $operator = new Between($actualValue, $minMax);
        $this->assertTrue($operator->matches());
    }

    public function testCompareValueOutOfRange() 
    {
        $actualValue = 15;
        $minMax = [0, 10];

        $operator = new Between($actualValue, $minMax);
        $this->assertFalse($operator->matches());
    }

    public function testDescendingRange() 
    {
        $actualValue = 15;
        $minMax = [10, 0];

        $operator = new Between($actualValue, $minMax);
        $this->assertFalse($operator->matches());
    }

    public function testInvalidRange() 
    {
        $actualValue = 15;
        $minMax = ['foo', 'bar'];

        $this->expectException('InvalidArgumentException');
        $operator = new Between($actualValue, $minMax);
    }
}
