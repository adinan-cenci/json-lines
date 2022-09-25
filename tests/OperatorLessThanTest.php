<?php
declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\Search\Operator\LessThan;
use AdinanCenci\JsonLines\Search\Exception\InvalidData;

final class OperatorLessThanTest extends Base
{
    public function testCompareToGreaterNumber() 
    {
        $actualValue = 5;
        $toCompare = 10;

        $operator = new LessThan($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareToLesserNumber() 
    {
        $actualValue = 10;
        $toCompare = 5;

        $operator = new LessThan($actualValue, $toCompare);
        $this->assertFalse($operator->matches());
    }

    public function testCompareToBoolean() 
    {
        $actualValue = 10;
        $toCompare = false;
        $this->expectException('InvalidArgumentException');
        $operator = new LessThan($actualValue, $toCompare);
    }
}