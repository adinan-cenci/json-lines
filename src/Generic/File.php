<?php 
namespace AdinanCenci\JsonLines\Generic;

/**
 * @property string $fileName
 * @property FileIterator $lines Iterator object to read the file line by line.
 * @property int $lineCount The number of lines in the file.
 */
class File 
{
    protected string $fileName;

    public function __construct(string $fileName) 
    {
        $this->fileName = $fileName;
    }

    public function __get(string $propertyName) 
    {
        switch ($propertyName) {
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

        \trigger_error('Trying to retrieve unknown property ' . $propertyName, \E_USER_ERROR);
        return null;
    }

    /**
     * If a line is not specified, 
     * $content will be added at the end of the file.
     * 
     * @param string $content
     * @param int $line
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
     */
    public function addLine(string $content, int $line = -1) : void
    {
        $this->addLines([$line => $content], $line < 0);
    }

    /**
     * If $toTheEndOfTheFile is set to false, the placement of the lines
     * will reflect their keys.
     * 
     * @param string[] $lines A numerical array: [ lineNumber => content ].
     * @param bool $toTheEndOfTheFile
     * @throws DirectoryDoesNotExist
     * @throws DirectoryIsNotWritable
     * @throws FileIsNotWritable
     */
    public function addLines(array $lines, bool $toTheEndOfTheFile = true) : void
    {
        if ($toTheEndOfTheFile) {
            $lastLine = $this->nameLastLine(true);
            $keys = range($lastLine, $lastLine + count($lines) - 1);
            $lines = array_combine($keys, array_values($lines));
        }

        $this->omniThingy()
            ->add($lines)
            ->commit();
    }

    /**
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
        $this->omniThingy()
            ->set($lines)
            ->commit();
    }

    /**
     * @param int $line
     * @return string|null
     * @throws FileDoesNotExist
     * @throws FileIsNotReadable
     */
    public function getLine(int $line) : ?string
    {
        $contents = $this->getLines([$line]);
        return $contents ? reset($contents) : null;
    }

    /**
     * @param int[] $lines
     * @return (string|null)[]
     * @throws FileDoesNotExist
     * @throws FileIsNotReadable
     */
    public function getLines(array $lines) : array
    {
        return $this->omniThingy()
            ->get($lines)
            ->commit()
            ->linesRetrieved;
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
        $this->omniThingy()
            ->delete($lines)
            ->commit();
    }

    public function omniThingy() 
    {
        return new OmniThing($this->fileName);
    }

    /**
     * @param bool $emptyLastLine Will indicate if the last line is empty or not.
     * @return int
     */
    public function countLines(&$emptyLastLine = false) : int
    {
        return self::countLinesOnFile($this->fileName, $emptyLastLine);
    }

    public static function countLinesOnFile(string $fileName, &$emptyLastLine = false) : int
    {
        if (! file_exists($fileName)) {
            return 0;
        }

        $handle = fopen($fileName, 'r');
        $lineCount = 1;
  
        while(! feof($handle)){
            $line = fgets($handle, 4096);
            $lineCount = $lineCount + substr_count($line, PHP_EOL);
        }

        $emptyLastLine = strlen($line) == 0;

        fclose($handle);
        return $lineCount;
    }

    /**
     * @param bool $ignoreEmptyLine If set to true and the last line is empty, it will return the second to last line.
     * @return int
     */
    public function nameLastLine(bool $ignoreEmptyLine = false) : int
    {
        return self::getLastLine($this->fileName, $ignoreEmptyLine);
    }

    public static function getLastLine(string $fileName, bool $ignoreEmptyLine = false) : int
    {
        $lastLine = self::countLinesOnFile($fileName, $emptyLastLine);
        $lastLine -= $ignoreEmptyLine && $emptyLastLine ? 1 : 0;
        return $lastLine;
    }
}
