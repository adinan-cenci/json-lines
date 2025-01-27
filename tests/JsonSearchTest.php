<?php

declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\JsonLines;

class JsonSearchTest extends Base
{
    public function testSearchObjects()
    {
        $file = new JsonLines('./tests/template-search.jsonl');
        $search = $file->search();
        $search->condition('score', [2, 5], 'BETWEEN');
        $results = $search->find();

        $this->assertEquals(3, reset($results)->score);
        $this->assertEquals(4, end($results)->score);

        //-------------

        $search = $file->search();
        $search->condition('artist', 'Beast in Black');
        $results = $search->find();

        $this->assertEquals(2, count($results));
    }

    public function testSearchByNestedProperty()
    {
        $file = new JsonLines('./tests/template-search.jsonl');
        $search = $file->search();
        $search->condition(['source', 'youtube'], null, 'NOT NULL');
        $results = $search->find();

        $this->assertEquals('Easy Living', reset($results)->title);
        $this->assertEquals('Into Each Life Some Rain Must Fall', end($results)->title);
    }

    public function testOrderSearchResultsByProperty()
    {
        $file = new JsonLines('./tests/template-search.jsonl');
        $search = $file->search();
        $search->orderBy('score', 'DESC');
        $results = $search->find();

        $this->assertEquals(9, reset($results)->score);
        $this->assertNull(end($results)->score ?? null);

        //----------

        $search = $file->search();
        $search->orderBy('title', 'ASC');
        $results = $search->find();

        $this->assertEquals('Angus Mcfife', reset($results)->title);
        $this->assertEquals('The Bard\'s song', end($results)->title);
    }

    public function testOrderSearchResultsRandomly()
    {
        $file = new JsonLines('./tests/template-search.jsonl');

        $search1 = $file->search();
        $search1->orderRandomly();
        $results1 = $search1->find();

        $search2 = $file->search();
        $search2->orderRandomly();
        $results2 = $search2->find();

        $first1 = reset($results1);
        $first2 = reset($results2);

        $this->assertNotEquals($first1->title, $first2->title);
    }

    public function testOrderSearchResultsRandomlyWithSeed()
    {
        $file = new JsonLines('./tests/template-search.jsonl');
        $seed = 'foo-bar' . rand(0, 1000);

        $search1 = $file->search();
        $search1->orderRandomly($seed);
        $results1 = $search1->find();

        $search2 = $file->search();
        $search2->orderRandomly($seed);
        $results2 = $search2->find();

        $first1 = reset($results1);
        $first2 = reset($results2);

        $this->assertEquals($first1->title, $first2->title);
    }
}
