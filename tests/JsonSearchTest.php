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
    }

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

    public function testOrSearchMultilevalConditions() 
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
}
