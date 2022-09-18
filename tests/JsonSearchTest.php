<?php
declare(strict_types=1);

namespace AdinanCenci\JsonLines\Tests;

use AdinanCenci\JsonLines\JsonLines;

class JsonSearchTest extends Base
{
    public function testSearch() 
    {
        $file = new JsonLines('./tests/template.jsonl');        
        $search = $file->search();
        
        $search->condition('title', 'Swing Doors', '=');
        
        /*$search->orConditionGroup()
            ->condition('title', 'Swing Doors')
            ->condition('title', 'Tale of Warriors');*/

        $results = $search->find();

        var_dump($results);

        $this->assertEquals(1, 1);
    }
}
