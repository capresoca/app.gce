<?php

namespace App\Capresoca\RecaudosSOI;


use App\Traits\Fixlenfile\FixlenfileParser;

class CuerpoPlanilla
{
    use CuerpoFieldsMap;
    private $file;

    public function __construct($filePath)
    {
        $this->file = $filePath;
    }

    /**
     * @throws \App\Traits\FixLenfile\ParserException
     */
    public function toArray()
    {
        $parser = new FixlenfileParser($this->file,1,-4);
        $parser->setFieldsMap($this->fieldsMap);
        $parser->parse();
        return $parser->getContent();
    }
}