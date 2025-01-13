<?php

namespace AdinanCenci\JsonLines;

use AdinanCenci\FileEditor\File;
use AdinanCenci\JsonLines\Search\Search;

/**
 * @property \Iterator $objects
 *   Iterator object to read the objects line by line.
 * @property bool $associative
 *   Wether the objects are returned as associative arrays or objects.
 * @property string $fileName
 *   The filename.
 * @property FileIterator $lines
 *   Iterator object to read the file line by line.
 * @property int $lineCount
 *   The number of lines in the file.
 */
class JsonLines extends File
{
    /**
     * @var bool
     *   When true JSON objects will be returned as associative arrays
     *   when false, JSON objects will be returned as objects.
     */
    protected bool $associative = false;

    /**
     * @param string $fileName
     *   Absolute path to the file.
     * @param bool $associative
     *   If true, JSON objects will be returned as associative arrays,
     *   otherwise they will be returned as objects.
     */
    public function __construct(string $fileName, bool $associative = false)
    {
        parent::__construct($fileName);
        $this->associative = $associative;
    }

    /**
     * {@inheritdoc}
     */
    public function __get(string $var)
    {
        if ($var == 'objects') {
            return $this->objects();
        }

        if ($var == 'associative') {
            return $this->associative;
        }

        return parent::__get($var);
    }

    /**
     * Returns an object to iterate through the JSON objects in the file.
     *
     * @return \Iterator
     *   The iterator object.
     */
    public function objects(): \Iterator
    {
        return new JsonLinesIterator($this->fileName, $this->associative);
    }

    /**
     * Adds an object to the file.
     *
     * @param array|\stdClass $object
     *   An array or object to be added to the file.
     * @param int $line
     *   The position in the file.
     *   If not informed the object will be added to the end of the file.
     *
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
     */
    public function addObject($object = null, int $line = -1): void
    {
        $this->addObjects([$line => $object], $line == -1);
    }

    /**
     * Adds a list of objects to the file.
     *
     * @param array $objects
     *   An array of objects.
     * @param bool $toTheEndOfTheFile
     *   If true, the objects will be added to the end of the file,
     *   otherwise their positions in the file will reflect their array keys.
     *
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
     */
    public function addObjects(array $objects, bool $toTheEndOfTheFile = true): void
    {
        array_walk($objects, function (&$object) {
            $object = json_encode($object);
        });

        $this->addLines($objects, $toTheEndOfTheFile);
    }

    /**
     * Sets an object to the specified line.
     *
     * Erasing whatever was present at said line.
     *
     * @param array|\stdClass $object
     *   An array or object to be added to the file.
     * @param int $line
     *   The position in the file.
     *
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
     */
    public function setObject(int $line, $object): void
    {
        $this->setObjects([$line => $object]);
    }

    /**
     * Sets a list of objects to the file.
     *
     * Erasing whatever was present at the defined positions.
     *
     * @param array $objects
     *   An numerical array: [ lineNumber => object ].
     *
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
     */
    public function setObjects(array $objects): void
    {
        array_walk($objects, function (&$object) {
            $object = json_encode($object);
        });

        $this->setLines($objects);
    }

    /**
     * Retrieves the object at the specified line.
     *
     * @param int $line
     *   The targted line.
     *
     * @return array|object|null
     *   The decoded object, null if the line cannot be decoded.
     *
     * @throws FileDoesNotExist
     * @throws FileIsNotReadable
     */
    public function getObject(int $line)
    {
        $result = $this->getObjects([$line]);
        return reset($result);
    }

    /**
     * Retrieves a list of objects.
     *
     * @param int[] $lines
     *   An array of line numbers.
     *
     * @return (array|object|null)[]
     *   The objects in the specified lines.
     *
     * @throws FileDoesNotExist
     * @throws FileIsNotReadable
     */
    public function getObjects(array $lines): array
    {
        $entries = $this->getLines($lines);
        $associative = $this->associative;
        array_walk($entries, function (&$line, $lineNumber) use ($associative) {
            $line = self::jsonDecode($line, $associative, $lineNumber);
        });

        return $entries;
    }

    /**
     * Deletes object in the specified line.
     *
     * @param int $line
     *   The line to be erased.
     *
     * @throws FileDoesNotExist
     * @throws FileIsNotReadable
     */
    public function deleteObject(int $line): void
    {
        $this->deleteLines([$line]);
    }

    /**
     * Delete objects in the specified lines.
     *
     * @param int[] $lines
     *   Array of line numbers.
     *
     * @throws FileDoesNotExist
     * @throws FileIsNotReadable
     */
    public function deleteObjects(array $lines): void
    {
        $this->deleteLines($lines);
    }

    /**
     * Returns random objects from the file.
     *
     * @param int $count
     *   How many lines to return.
     * @param int|null $from
     *   Limits the pool of lines available.
     * @param int|null $to
     *   Limits the pool of lines available.
     *
     * @return string[]
     *   The lines we retrieved.
     */
    public function getRandomObjects(int $count, ?int $from = null, ?int $to = null): array
    {
        $lines = $this->getRandomLines($count, $from, $to);
        $associative = $this->associative;

        array_walk($lines, function (&$content, $lineNumber) use ($associative) {
            $content = JsonLines::jsonDecode($content, $associative);
        });

        return $lines;
    }

    /**
     * {@inheritdoc}
     */
    public function search(string $operator = 'AND'): Search
    {
        return new Search($this, $operator);
    }

    /**
     * Decodes a line of JSON data.
     *
     * @param string|null $json
     *   The JSON encoded string.
     * @param bool $associative
     *   Returns an array if true,
     *   otherwise returns an array.
     *
     * @return array|\stdClass|null
     *   The decoded object.
     */
    public static function jsonDecode($json, bool $associative = false, $lineNumber = 'unknown')
    {
        if ($json === null) {
            return null;
        }

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
