<?php
declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\Search\Operator\Between;

final class OperatorBetweenTest extends Base
{
    public function testCompare() 
    {
        $actualValue = 5;
        $minMax = [0, 10];

        $operator = new Between($actualValue, $minMax);
        $this->assertTrue($operator->matches());
    }

    public function testCompareFalse() 
    {
        $actualValue = 15;
        $minMax = [0, 10];

        $operator = new Between($actualValue, $minMax);
        $this->assertFalse($operator->matches());
    }

    public function testNonsense() 
    {
        $actualValue = 15;
        $minMax = [10, 0];

        $operator = new Between($actualValue, $minMax);
        $this->assertFalse($operator->matches());
    }
}
