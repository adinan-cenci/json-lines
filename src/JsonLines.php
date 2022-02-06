<?php 
namespace AdinanCenci\JsonLines;

use AdinanCenci\JsonLines\Generic\File;

/**
 * @property object[] $objects
 */
class JsonLines extends File 
{
    protected $associative = false;

    public function __construct($fileName, $associative = false) 
    {
        parent::__construct($fileName);
        $this->associative = $associative;
    }

    public function __get($var) 
    {
        if ($var == 'objects') {
            return new JsonLinesIterator($this->fileName, $this->associative);
        }

        return parent::__get($var);
    }

    /**
     * @param string $line
     * @param array|object $object
     */
    public function setObject($line, $object) 
    {
        $this->setObjects([$line => $object]);
    }

    /**
     * @param array $objects An numerical array: [ line => object ].
     */
    public function setObjects($objects) 
    {
        array_walk($objects, function(&$object) 
        {
            $object = json_encode($object);
        });

        $this->setLines($objects);
    }

    /**
     * @param int $line
     * @return array|object|null
     */
    public function getObject($line) 
    {
        $result = $this->getObjects([$line]);
        return reset($result);
    }

    /**
     * @param int[] $lines
     * @return (array|object|null)[]
     */
    public function getObjects($lines)
    {
        $entries = $this->getLines($lines);
        $associative = $this->associative;
        array_walk($entries, function(&$line, $lineNumber) use($associative) 
        {
            $line = self::jsonDecode($line, $associative, $lineNumber);
        });

        return $entries;
    }

    public function search() 
    {
        return new JsonLinesSearch($this);
    }

    public static function jsonDecode($json, $associative = false, $lineNumber) 
    {
        $json = rtrim($json, "\n");

        if ($json == '') {
            return null;
        }

        $data = json_decode($json, $associative);

        if ($data === null) {
            trigger_error('Could not parse object on line ' . $lineNumber . ': ' . $json);
        }

        return $data;
    }
}
