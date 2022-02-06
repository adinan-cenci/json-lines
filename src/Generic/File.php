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
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
     */
    public function setLine($line, $content) 
    {
        $this->setLines([$line => $content]);
    }

    /**
     * @param string[] $lines An numerical array: [ lineNumber => content ].
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
     */
    public function setLines($lines) 
    {
        $write = new WriteToFile($this->fileName, $lines);
        $write->writeDown();
    }

    /**
     * @param int $line
     * @return string|null
     * @throws FileDoesNotExist
     * @throws FileIsNotReadable
     */
    public function getLine($line) 
    {
        $result = $this->getLines([$line]);
        return reset($result);
    }

    /**
     * @param int[] $lines
     * @return (string|null)[]
     * @throws FileDoesNotExist
     * @throws FileIsNotReadable
     */
    public function getLines($lines) 
    {
        $read = new ReadFile($this->fileName, $lines);
        return $read->read();
    }

    /**
     * @param int[] $lines
     * @throws FileDoesNotExist
     * @throws FileIsNotReadable
     */
    public function deleteLines($lines) 
    {
        $delete = new RemoveFromFile($this->fileName, $lines);
        return $delete->remove();
    }

    /**
     * @param int $line
     * @throws FileDoesNotExist
     * @throws FileIsNotReadable
     */
    public function deleteLine($line) 
    {
        return $this->deleteLines([$line]);
    }
}
