<?php 
namespace AdinanCenci\JsonLines\Generic;

/**
 * @property FileIterator $lines
 * @property string $fileName
 * @property \Iterator $lines Iterator object to read the file line by line.
 * @property int $lineCount The number of lines in the file.
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
            case 'lineCount':
                return $this->countLines();
                break;
        }

        return null;
    }

    /**
     * @param int $line
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
     * @param string[] $lines A numerical array: [ lineNumber => content ].
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
     * @param int $line Optional
     * @param string $content 
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
     */
    public function addLine($line = null, $content = null) 
    {
        $args = func_get_args();
        if (count($args) == 2) {
            $line = $args[0];
            $content = $args[1];
        } else {
            $line = $this->countLines($lastLineEmpty);
            $line -= $lastLineEmpty ? 1 : 0;
            $content = $args[0];
        }

        $this->addLines([$line => $content]);
    }

    /**
     * @param string[] $lines A numerical array: [ lineNumber => content ].
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
     */
    public function addLines($lines) 
    {
        $write = new AddToFile($this->fileName, $lines);
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

    public function countLines(&$emptyLastLine = false) 
    {
        if (! file_exists($this->fileName)) {
            return 0;
        }

        $handle = fopen($this->fileName, "r");
        $lineCount = 1;
  
        while(! feof($handle)){
            $line = fgets($handle, 4096);
            $lineCount = $lineCount + substr_count($line, PHP_EOL);
        }

        $emptyLastLine = strlen($line) == 0;

        fclose($handle);
        return $lineCount;
    }
}
