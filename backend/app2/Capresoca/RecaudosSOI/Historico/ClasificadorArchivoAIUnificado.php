<?php


namespace App\Capresoca\RecaudosSOI\Historico;


use App\Capresoca\RecaudosSOI\PersistirPlanilla;
use Illuminate\Support\Facades\Storage;

class ClasificadorArchivoAIUnificado
{

    protected $ruta;
    protected $tipos = [
        'I' => [
            'marks' => ['_I_','_IR_'],
            'to' => 'app/ACHExtracted/I/'
        ]
    ];
    protected $estractTo;
    protected $files = [
        'A' => [],
        'I' => []
    ];

    protected $carga_id;

    public function __construct($ruta,$carga_id=null)
    {
        $this->ruta = $ruta;
        $this->carga_id = $carga_id;
    }
    public function handle()
    {
       	$directorio = opendir($this->ruta); //ruta actual
        while (false !== ($archivo = readdir($directorio))) //obtenemos un archivo y luego otro sucesivamente
        {
            if (!is_dir($archivo))//verificamos si es o no un directorio
            {
                if($this->esValido($archivo,'I')){
                    $planilla = new PersistirPlanilla($this->ruta.'/'.$archivo,$this->carga_id);
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
