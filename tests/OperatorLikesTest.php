<?php
declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\Search\Operator\Likes;

final class OperatorLikesTest extends Base
{
    public function testCompareScalarValues() 
    {
        $actualValue = 'Highland Glory';
        $toCompare = 'Highland Glory';

        $operator = new Likes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = 'Highland Glory';
        $toCompare = 'HIGHLAND GLORY';

        $operator = new Likes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = 'Highland Glory';
        $toCompare = 'glory';

        $operator = new Likes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());
    }

    public function testCompareArraysToScalarValues() 
    {
        $actualValue = ['Highland Glory'];
        $toCompare = 'Highland Glory';

        $operator = new Likes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = ['Highland Glory'];
        $toCompare = 'HIGHLAND GLORY';

        $operator = new Likes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = 'Highland Glory';
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

        //---------

        $actualValue = ['Highland Glory'];
        $toCompare = ['HIGHLAND GLORY'];

        $operator = new Likes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = ['Highland Glory', 'Gloryhammer'];
        $toCompare = ['Highland Glory'];

        $operator = new Likes($actualValue, $toCompare);
        $this->assertTrue($operator->matches());

        //---------

        $actualValue = ['Highland Glory'];
        $toCompare = ['Highland Glory', 'Gloryhammer'];

        $operator = new Likes($actualValue, $toCompare);
        $this->assertFalse($operator->matches());
    }
}
