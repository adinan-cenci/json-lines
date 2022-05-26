<?php 
namespace AdinanCenci\JsonLines;

use AdinanCenci\JsonLines\Generic\File;

/**
 * @property \Iterator $objects
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
     * Adds an object to the end of the file.
     * 
     * @param int $line
     * @param array|object $object
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
     */
    public function addObject($line = null, $object = null) 
    {
        $args = func_get_args();
        if (count($args) == 2) {
            $line = $args[0];
            $object = $args[1];
        } else {
            $line = $this->countLines($lastLineEmpty);
            $line -= $lastLineEmpty ? 1 : 0;
            $object = $args[0];
        }

        $this->addObjects([$line => $object]);
    }

    /**
     * @param array $objects An numerical array: [ lineNumber => object ].
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
     */
    public function addObjects($objects) 
    {
        array_walk($objects, function(&$object) 
        {
            $object = json_encode($object);
        });

        $this->addLines($objects);
    }

    /**
     * @param int $line
     * @param array|object $object
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
     */
    public function setObject($line, $object) 
    {
        $this->setObjects([$line => $object]);
    }

    /**
     * @param array $objects An numerical array: [ lineNumber => object ].
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
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
     * @throws FileDoesNotExist
     * @throws FileIsNotReadable
     */
    public function getObject($line) 
    {
        $result = $this->getObjects([$line]);
        return reset($result);
    }

    /**
     * @param int[] $lines
     * @return (array|object|null)[]
     * @throws FileDoesNotExist
     * @throws FileIsNotReadable
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

    /**
     * @param int[] $lines
     * @throws FileDoesNotExist
     * @throws FileIsNotReadable
     */
    public function deleteObjects($lines) 
    {
        return $this->deleteLines($lines);
    }

    /**
     * @param int $line
     * @throws FileDoesNotExist
     * @throws FileIsNotReadable
     */
    public function deleteObject($lines) 
    {
        return $this->deleteLine([$lines]);
    }

    public function search() 
    {
        return new Search($this);
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
