<?php
declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\Search\Operator\GreaterThan;

final class OperatorGreaterThanTest extends Base
{
    public function testCompareToLesserNumber() 
    {
        $actualValue = 10;
        $toCompare = 5;

        $operator = new GreaterThan($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareToGreaterNumber() 
    {
        $actualValue = 5;
        $toCompare = 10;

        $operator = new GreaterThan($actualValue, $toCompare);
        $this->assertFalse($operator->matches());
    }
}
