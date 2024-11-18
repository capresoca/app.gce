<?php


namespace App\Capresoca\RecaudosSOI\Historico;


use App\Capresoca\RecaudosSOI\PersistirIP;
use App\Capresoca\RecaudosSOI\PersistirPlanilla;

class ClasificadorIP
{

    protected $ruta;

    protected $tipos = [
        'IP' => [
            'marks' => ['_IP_','_IPR_'],
            'to' => 'app/ACHExtracted/I/'
        ]
    ];

    protected $estractTo;

    protected $files = [
        'A' => [],
        'I' => []
    ];

    public function __construct($ruta)
    {
        $this->ruta = $ruta;
    }

    public function handle()
    {
        $directorio = opendir($this->ruta); //ruta actual
        while (false !== ($archivo = readdir($directorio))) //obtenemos un archivo y luego otro sucesivamente
        {
            if (!is_dir($archivo))//verificamos si es o no un directorio
            {
                if($this->esValido($archivo,'IP'))
                {
                    $planilla = new PersistirIP($this->ruta.'\\'.$archivo);
                    $planilla->handle();
                }
            }
        }

    }

    private function esValido($file, $tipo)
    {
        $re = '/'.implode('|',$this->tipos[$tipo]['marks']).'/m';
        $str = $file;

        preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);

        return count($matches) > 0;
    }
}