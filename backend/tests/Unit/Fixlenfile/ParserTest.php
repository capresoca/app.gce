<?php

namespace Tests\Unit\Fixlefile;


use App\Traits\FixLenfile\FixlenfileParser;
use Tests\TestCase;

class ParserTest extends TestCase
{

    public function testSetRangeParser()
    {
        $file = __DIR__ . '/file1.TXT';

        $parser = new FixlenfileParser($file,0,1);

        $this->assertEquals(0,$parser->getStartLine());
        $this->assertEquals(1,$parser->getLinesToParse());

        $parser = new FixlenfileParser($file,1,-4);

        $this->assertEquals(1,$parser->getStartLine());
        $this->assertEquals(2,$parser->getLinesToParse());

        $parser = new FixlenfileParser($file,-3);

        $this->assertEquals(4,$parser->getStartLine());
        $this->assertEquals(3,$parser->getLinesToParse());

    }

    /**
     * @var string
     */

    public function testOnlyFirstLineField()
    {
        $file = __DIR__ . '/file1.TXT';

        $parser = new FixlenfileParser($file,0,1);


        $parser->setFieldsMap([
            [
                'field'     => 'NoRegistro',
                'length'    => 5
            ],
            [
                'field' => 'tipoRegistro',
                'length'=> 2
            ]

        ]);

        $parser->parse();

        $content = $parser->getContent();

        $this->assertEquals(1,count($content));
        $this->assertEquals('00000',$content[0]['NoRegistro']);
        $this->assertEquals('10',$content[0]['tipoRegistro']);

    }

    public function testRangeToLines()
    {
        $file = __DIR__ . '/file1.TXT';

        $parser = new FixlenfileParser($file,1,-4);

        $parser->setFieldsMap([
            [
                'field'     => 'Secuencia',
                'length'    => 5
            ],
            [
                'field'     => 'tipoRegistro',
                'length'    => 1
            ]
        ]);


        $parser->parse();
        $content = $parser->getContent();
        $this->assertEquals(2,count($content));
        $this->assertEquals('00001',$content[0]['Secuencia']);
        $this->assertEquals('2',$content[0]['tipoRegistro']);


    }

    public function testLastThreeLines()
    {
        $file = __DIR__ . '/file1.TXT';

        $parser = new FixlenfileParser($file,-4);


        $parser->setFieldsMap([
            [
                'field'     => 'Secuencia',
                'length'    => '5'
            ]
        ]);


        $parser->parse();
        $content = $parser->getContent();
        $this->assertEquals(3,$parser->getStartLine());
        $this->assertEquals(4,$parser->getLinesToParse());
        $this->assertEquals(3,count($content));
        $this->assertEquals('00031',$content[0]['Secuencia']);

    }


    public function testRangeToLinesFile2()
    {
        $file = __DIR__ . '/file2.TXT';

        $parser = new FixlenfileParser($file,1,-4);

        $parser->setFieldsMap([
            [
                'field'     => 'Secuencia',
                'length'    => '5'
            ],
            [
                'field'     => 'tipoRegistro',
                'length'    => '1'
            ]
        ]);


        $parser->parse();
        $content = $parser->getContent();
        $this->assertEquals(1,count($content));
        $this->assertEquals('00001',$content[0]['Secuencia']);
        $this->assertEquals('2',$content[0]['tipoRegistro']);


    }

}
