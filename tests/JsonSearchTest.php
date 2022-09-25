<?php
declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\JsonLines;

class JsonSearchTest extends Base
{
    public function testSearchEqualsOperator() 
    {
        $file = new JsonLines('./tests/template-search.jsonl');
        $search = $file->search();
        $search->condition('title', 'The Bard\'s song', '=');
        $results = $search->find();
        $this->assertEquals(2, count($results));

        $search = $file->search();
        $search->condition('score', 1, '=');
        $results = $search->find();
        $this->assertEquals(1, count($results));
    }

    public function testSearchGreaterThanOperator() 
    {
        $file = new JsonLines('./tests/template-search.jsonl');
        $search = $file->search();

        $search->condition('score', 5, '>');

        $results = $search->find();

        $this->assertEquals(4, count($results));
    }

    public function testSearchGreaterOrEqualToOperator() 
    {
        $file = new JsonLines('./tests/template-search.jsonl');
        $search = $file->search();

        $search->condition('score', 5, '>=');

        $results = $search->find();

        $this->assertEquals(5, count($results));
    }

    public function testSearchLessThanOperator() 
    {
        $file = new JsonLines('./tests/template-search.jsonl');
        $search = $file->search();

        $search->condition('score', 3, '<');

        $results = $search->find();

        $this->assertEquals(2, count($results));
    }

    public function testSearchLessOrEqualToOperator() 
    {
        $file = new JsonLines('./tests/template-search.jsonl');
        $search = $file->search();

        $search->condition('score', 3, '<=');

        $results = $search->find();

        $this->assertEquals(3, count($results));
    }

    public function testSearchBetweenOperator() 
    {
        $file = new JsonLines('./tests/template-search.jsonl');
        $search = $file->search();

        $search->condition('score', [1, 4], 'BETWEEN');

        $results = $search->find();

        $this->assertEquals(2, count($results));
    }

    public function testSearchIncludeOperator() 
    {
        $file = new JsonLines('./tests/template-search.jsonl');
        $search = $file->search();

        $search->condition('artist', ['Ella Fitzgerald', 'Van Canto'], 'IN');

        $results = $search->find();

        $this->assertEquals(2, count($results));
    }

    public function testSearchLikeOperator() 
    {
        $file = new JsonLines('./tests/template-search.jsonl');
        $search = $file->search();

        $search->condition('artist', 'Teddy Wilson', 'LIKE');

        $results = $search->find();

        $this->assertEquals(1, count($results));
    }

    public function testSearchIsNullOperator() 
    {
        $file = new JsonLines('./tests/template-search.jsonl');
        $search = $file->search();

        $search->condition('score', null, 'IS NULL');

        $results = $search->find();

        $this->assertEquals(2, count($results));
    }

    public function testSearchInvalidOperator() 
    {
        $file = new JsonLines('./tests/template-search.jsonl');
        $search = $file->search();

        $this->expectException('InvalidArgumentException');
        $search->condition('score', 'test', 'foo-bar');
    }

    //--------------------------

    public function testAndSearchWithTwoConditions() 
    {
        $file = new JsonLines('./tests/template-search.jsonl');
        $search = $file->search();

        $search
            ->condition('title', 'The Bard\'s song', '=')
            ->condition('artist', 'Blind Guardian', '=');

        $results = $search->find();

        $this->assertEquals(1, count($results));
    }

    public function testOrSearchWithTwoConditions() 
    {
        $file = new JsonLines('./tests/template-search.jsonl');
        $search = $file->search('OR');

        $search
            ->condition('artist', 'Blind Guardian', '=')
            ->condition('artist', 'Roy Brown', '=');

        $results = $search->find();

        $this->assertEquals(4, count($results));
    }

    public function testOrSearchMultiLevelConditions() 
    {
        $file = new JsonLines('./tests/template-search.jsonl');
        $search = $file->search('OR');

        $search
            ->andConditionGroup()
                ->condition('artist', 'Blind Guardian', '=')
                ->condition('title', 'Nightfall', '=');

        $search
            ->andConditionGroup()
                ->condition('artist', 'Roy Brown', '=')
                ->condition('title', 'Big Town', '=');

        $results = $search->find();

        $this->assertEquals(2, count($results));
    }

    public function testSearchForNestedProperty() 
    {
        $file = new JsonLines('./tests/template-search.jsonl');
        $search = $file->search('OR');

        $search->condition(['source', 'youtube'], 'PJ9IaplRrm4');

        $results = $search->find();
        $result = reset($results);

        $this->assertEquals('Into Each Life Some Rain Must Fall', $result->title);
    }
}
