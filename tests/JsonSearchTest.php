<?php
declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\JsonLines;

class JsonSearchTest extends Base
{
    public function testSearch() 
    {
        $file = new JsonLines('./tests/template-search.jsonl');        
        $search = $file->search();
        
        $search->condition('title', 'The Bard\'s song');
        
        $results = $search->find();

        $this->assertEquals(2, count($results));
    }

    public function testSearchAnd() 
    {
        $file = new JsonLines('./tests/template-search.jsonl');        
        $search = $file->search();
        
        $search
            ->condition('title', 'The Bard\'s song', '=')
            ->condition('artist', 'Blind Guardian');
        
        $results = $search->find();

        $this->assertEquals(1, count($results));
    }

    public function testSearchAnd2() 
    {
        $file = new JsonLines('./tests/template-search.jsonl');        
        $search = $file->search();
        
        $search
            ->orConditionGroup()
                ->condition('artist', 'Blind Guardian')
                ->condition('artist', 'Roy Brown');
        
        $results = $search->find();

        $this->assertEquals(4, count($results));
    }
}
