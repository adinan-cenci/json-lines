<?php 
namespace AdinanCenci\JsonLines\Generic;

/**
 * @property FileIterator $lines
 * @property string $fileName
 */
class File 
{
    protected $fileName;

    public function __construct(string $fileName) 
    {
        $this->fileName = $fileName;
    }

    public function __get($var) 
    {
        switch ($var) {
            case 'lines':
                return new FileIterator($this->fileName);
                break;
            case 'fileName':
                return new $this->fileName;
                break;
        }

        return null;
    }

    /**
     * @param integer $line
     * @param string $content
     */
    public function setLine($line, $content) 
    {
        $this->setLines([$line => $content]);
    }

    /**
     * @param string[] $lines An numerical array: [ lineNumber => content ].
     */
    public function setLines($lines) 
    {
        $write = new WriteToFile($this->fileName, $lines);
        $write->writeDown();
    }

    /**
     * @param int $line
     * @return string|null
     */
    public function getLine($line) 
    {
        $result = $this->getLines([$line]);
        return reset($result);
    }

    /**
     * @param int[] $lines
     * @return (string|null)[]
     */
    public function getLines($lines) 
    {
        $read = new ReadFile($this->fileName, $lines);
        return $read->read();
    }
}
