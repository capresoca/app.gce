<?php


namespace App\Capresoca\RecaudosSOI\Historico;


use Illuminate\Support\Facades\Storage;

class ExtractorZipAportanteDirectory
{
    protected $rutaZip;
    protected $tipos = [
        'I' => [
            'marks' => ['_I_','_IR_'],
            'to' => 'app/ACHExtracted/I/'
        ],
        'A' => [
            'marks' => ['_A_','_AR_'],
            'to' => 'app/ACHExtracted/A/'
        ]
    ];
    protected $estractTo;
    protected $files = [
        'A' => [],
        'I' => []
    ];


    public function __construct($rutaZip)
    {
        $this->rutaZip = $rutaZip;
    }

    public function run()
    {
        $this->extract();
        return $this->files;

    }

    private function extract()
    {
        $zip = new \ZipArchive;
        if ($zip->open($this->rutaZip) === true) {
            for($i = 0; $i < $zip->numFiles; $i++) {

                $filename = $zip->getNameIndex($i);
                $fileinfo = pathinfo($filename);
                $zipName = explode('/',$this->rutaZip)[3];

                foreach ($this->tipos as $key => $tipo){
                    $extractTo = storage_path($this->tipos[$key]['to']);
                    if ($this->esValido($fileinfo['basename'],$key)){
                        $dest = $extractTo.$zipName.$fileinfo['basename'];
                        copy("zip://".$this->rutaZip."#".$filename, $dest);
                        array_push($this->files[$key],$dest);
                    }
                }

            }
            $zip->close();
        }
        return $this->files;
    }

    private function esValido($file, $tipo)
    {
        $re = '/'.implode('|',$this->tipos[$tipo]['marks']).'/m';
        $str = $file;

        preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);

        return count($matches) > 0;
    }
}