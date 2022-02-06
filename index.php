<?php 
require 'vendor/autoload.php';

use AdinanCenci\JsonLines\JsonLines;

$f = new JsonLines('aaa.txt');
echo $f->getLine(0);