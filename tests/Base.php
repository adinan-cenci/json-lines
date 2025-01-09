<?php

namespace AdinanCenci\JsonLines\Tests;

use PHPUnit\Framework\TestCase;

abstract class Base extends TestCase
{
    protected function resetTest($file = null, $template = './tests/template.txt')
    {
        if (!file_exists('tests/files/')) {
            mkdir('tests/files/');
        }

        $contents = $template == '' ? '' : file_get_contents($template);
        file_put_contents($file, $contents);
    }

    protected function testExample()
    {
        $file = 'tests/files/' . __FUNCTION__ . '.txt';
        $this->resetTest($file);

        // $this->assertEquals('foo', 'bar');
    }
}
