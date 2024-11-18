<?php


namespace App\Traits\Fixlenfile;

class FixlenfileParser
{
    /**
     * @var string
     */
    private $file;
    /**
     * @var array
     */
    private $fieldMap;
    private $content;
    private $preflightCheck;
    private $callback;
    /**
     * @var int
     */
    private $startLine;
    /**
     * @var int
     */
    private $linesToParse;
    /**
     * @var int
     */
    private $lines;

    /**
     * FixlenfileParser constructor.
     * @param string $pathToFile
     * @param int|null $start_line
     * @param int|null $lines_to_parse
     */
    public function __construct(string $pathToFile,int $start_line=null, int $lines_to_parse = null)
    {
        $this->file = (string) $pathToFile;

        $this->setRange($start_line, $lines_to_parse);
    }



    /**
     * @param array $map
     */
    public function setFieldsMap(array $map)
    {
        $this->fieldMap = $map;
    }

    /**
     * @param \Closure $callback
     */
    public function setCallback(\Closure $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Returns all lines of the parsed content.
     *
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Main method for parsing.
     *
     * @return void
     * @throws ParserException
     */
    public function parse()
    {
        //Check for file parameter
        if (!isset($this->file)) {
            throw new ParserException('No file was specified!');
        }

        //Check for chopping map
        if (!isset($this->fieldMap)) {
            throw new ParserException('A Field Map MUST be specified!');
        }
        //Save pre check as local variable (as PHP does not recognize closures as class members)
        $preflightCheck = $this->preflightCheck;

        //Parse file line by line
        $this->content = array();

        $filePointer = fopen($this->file, "r");

        $line = 0;
        $parsed = 0;


        while (!feof($filePointer)) {

            $buffer = fgets($filePointer, 4096);
            if($this->inRange($line,$parsed)) {
                $line ++;
                continue;
            };


            if (!empty($buffer)) {
                // If a pre check was registered and it returns not true - the current line
                // does not need to be parsed
                if ($preflightCheck instanceof \Closure && $preflightCheck($buffer) !== true) {
                    continue;
                }

                //Pass the current string buffer
                $this->content[] = $this->parseLine($buffer);
            }
            $parsed++;
            $line++;

        }

        fclose($filePointer);
    }

    private function parseLine($buffer)
    {
        $currentLine = array();
        $lastPosition = 0;
        $mapEntryCount = count($this->fieldMap);

        //Extract each field from the current line
        for ($i = 0; $i < $mapEntryCount; $i++) {

            // if start option was set, use it. otherwise use last known position
            $start = isset($this->fieldMap[$i]['start']) ? $this->fieldMap[$i]['start'] : $lastPosition;

            // last entry of map, reset position
            $lastPosition = $i === $mapEntryCount-1 ? 0 : $lastPosition = $start + $this->fieldMap[$i]['length'];

            $name = $this->fieldMap[$i]['field'];
            $currentLine[$name] = substr($buffer,
                $start,
                $this->fieldMap[$i]['length']);
            $currentLine[$name] = trim($currentLine[$name]);

        }

        //Store callback as local variable (as PHP does not recognize closures as class members)
        $callback = $this->callback;

        /**
         * If a call back function was registered - apply it to the current line
         */
        if ($callback instanceof \Closure) {
            $currentLine = $callback($currentLine);
        }

        return $currentLine;
    }

    private function counFileLines()
    {
        $filePointer = fopen($this->file, "r");
        $linecount = 0;
        while(!feof($filePointer)){
            $line = fgets($filePointer, 4096);
            $linecount = $linecount + substr_count($line, PHP_EOL);
        }
        fclose($filePointer);
        return $linecount;
    }

    private function inRange($line,$parsed)
    {
        return !($parsed < $this->linesToParse && $line >=$this->startLine);
    }

    /**
     * @param int $start_line
     * @param int $lines_to_parse
     */
    private function setRange(int $start_line = null, int $lines_to_parse = null): void
    {
        if ($start_line < 0 || $lines_to_parse < 0) {
            $this->lines = $this->counFileLines();
        }

        $this->startLine = $this->defineStartLine($start_line);
        $this->linesToParse = $this->defineLinesToParse($lines_to_parse);
    }

    public function getStartLine()
    {
        return $this->startLine;
    }

    public function getLinesToParse()
    {
        return $this->linesToParse;
    }

    private function defineStartLine($start_line)
    {
        if($start_line < 0){
            return $this->lines + $start_line + 1;
        }
        return $start_line;
    }

    private function defineLinesToParse($lines_to_parse)
    {
        if(!$lines_to_parse){
            return ($this->lines - $this->startLine) + 1;
        }

        if($lines_to_parse < 0){
            return $this->lines + $lines_to_parse + $this->startLine - 1;
        }

        return $lines_to_parse;
    }

}