<?php 
namespace AdinanCenci\JsonLines\Generic;

/**
 * @property FileIterator $lines Iterator object to read the file line by line.
 * @property string $fileName
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
     * Will add content to the end of the file if $line is unset.
     * If $line is set the content will be added to the middle of the file.
     * If the file has less than $line lines, then the space in between will 
     * be filled with blank lines.
     * 
     * @param string $content 
     * @param int $line Optional
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
     */
    public function addLine(string $content, ?int $line = null) : void
    {
        if ($line === null) {
            $line = $this->countLines($lastLineEmpty);
            $line -= $lastLineEmpty && $line > 0 ? 1 : 0;
        }

        $this->addLines([$line => $content]);
    }

    /**
     * @param string[] $lines A numerical array: [ lineNumber => content ].
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
     */
    public function addLines(array $lines) : void
    {
        $write = new AddToFile($this->fileName, $lines);
        $write->writeDown();
    }

    /**
     * Will set the contents for the line $line, overwriting anything 
     * currently present.
     * If the file has less than $line lines, then the space in between will 
     * be filled with blank lines.
     * 
     * @param int $line
     * @param string $content 
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
     */
    public function setLine(int $line, string $content) : void
    {
        $this->setLines([$line => $content]);
    }

    /**
     * @param string[] $lines A numerical array: [ lineNumber => content ].
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
     */
    public function setLines(array $lines) : void
    {
        $write = new WriteToFile($this->fileName, $lines);
        $write->writeDown();
    }

    /**
     * Will return the contents in the $line line.
     * If the file has less than $line lines, it will return null.
     * 
     * @param int $line
     * @return string|null
     * @throws FileDoesNotExist
     * @throws FileIsNotReadable
     */
    public function getLine(int $line) : ?string
    {
        $result = $this->getLines([$line]);
        return $result ? reset($result) : null;
    }

    /**
     * @param int[] $lines
     * @return (string|null)[]
     * @throws FileDoesNotExist
     * @throws FileIsNotReadable
     */
    public function getLines(array $lines) : array
    {
        $read = new ReadFile($this->fileName, $lines);
        return $read->read();
    }

    /**
     * @param int $line
     * @throws FileDoesNotExist
     * @throws FileIsNotReadable
     */
    public function deleteLine(int $line) : void
    {
        $this->deleteLines([$line]);
    }

    /**
     * @param int[] $lines
     * @throws FileDoesNotExist
     * @throws FileIsNotReadable
     */
    public function deleteLines(array $lines) : void
    {
        $delete = new RemoveFromFile($this->fileName, $lines);
        $delete->remove();
    }

    /**
     * If the last line of the file is blank, $emptyLastLine will set to true
     * 
     * @param bool $emptyLastLine
     * @return int
     */
    public function countLines(&$emptyLastLine = false) : int
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
