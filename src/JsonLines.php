<?php 
namespace AdinanCenci\JsonLines;

use AdinanCenci\JsonLines\Generic\File;

/**
 * @property \Iterator $objects
 */
class JsonLines extends File 
{
    protected $associative = false;

    public function __construct(string $fileName, bool $associative = false) 
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
     * @param array|object $object
     * @param int $line Optional
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
     */
    public function addObject($object, ?int $line = null) : void
    {
        if ($line === null) {
            $line = $this->countLines($lastLineEmpty);
            $line -= $lastLineEmpty && $line > 0 ? 1 : 0;
        }

        $this->addObjects([$line => $object]);
    }

    /**
     * @param array $objects An numerical array: [ lineNumber => object ].
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
     */
    public function addObjects(array $objects) : void
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
    public function setObject(int $line, $object) : void
    {
        $this->setObjects([$line => $object]);
    }

    /**
     * @param array $objects An numerical array: [ lineNumber => object ].
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
     */
    public function setObjects(array $objects) : void
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
    public function getObject(int $line) 
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
    public function getObjects(array $lines) : array
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
    public function deleteObjects(array $lines) : void
    {
        $this->deleteLines($lines);
    }

    /**
     * @param int $line
     * @throws FileDoesNotExist
     * @throws FileIsNotReadable
     */
    public function deleteObject(int $line) : void
    {
        $this->deleteLine($line);
    }

    public function search() : Search
    {
        return new Search($this);
    }

    public static function jsonDecode(?string $json, bool $associative = false, int $lineNumber) 
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
