<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12/09/2018
 * Time: 11:26 AM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA;


use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsBduaregrespuesta;
use App\Models\Aseguramiento\AsBduatipglosa;
use Illuminate\Support\Facades\Log;

abstract class ProcesadorNegados extends Procesador
{

    protected function crearGlosas($fila)
    {
        $glosas = explode(';',$fila[count($fila)-1]);

        $glosasBdua = array();

        if($glosas[count($glosas) - 1] === '') {
            array_pop($glosas);
        }

        Log::info('Glosas: ',array($glosas));
            
        foreach ($glosas as $glosa) {
            $codigoGlosa = substr($glosa,0,6);


            $tipGlosa = AsBduatipglosa::where('codigo',$codigoGlosa)->first();
            if(!$tipGlosa){
                $tipGlosa = AsBduatipglosa::create([
                    'codigo' => $codigoGlosa
                ]);
            }
            array_push($glosasBdua, [
                'as_bduatipglosa_id' => $tipGlosa->id,
                'glosa' => $glosa,
                'estado' => 'No Corregida'
            ]);
            

        }
        return $glosasBdua;
    }

    protected function procesarGlosas(AsAfiliado $afiliado, AsBduaregrespuesta $regRespuesta)
    {
        $conErrores     = FALSE;
        foreach ($regRespuesta->glosas as $glosa) {
            try{
                if(!$glosa->tipo->clase_procesador){
                    $log = 'No se ha implementado un algoritmo para procesar la glosa: '. $glosa->tipo->codigo;
                    $this->pushMonitor($log,$this->proceso,'error--text');
                    $this->guardarPendiente($log);
                    $conErrores = TRUE;
                    continue;
                }

                if(!class_exists ($glosa->tipo->clase_procesador)) {
                    $log = 'La clase no existe ' .$glosa->tipo->clase_procesador;
                    $this->pushMonitor($log,$this->proceso,'error--text');
                    $this->guardarPendiente($log);
                    $conErrores = TRUE;
                    continue;
                }

                $procesadorGlosa = new $glosa->tipo->clase_procesador($glosa, $afiliado, $regRespuesta);

                if(!$procesadorGlosa->procesar()){
                    $log = 'Se proceso, pero no se aplicó ningún cambio para la glosa '.$glosa->tipo->codigo. ', afiliado: '.$afiliado->tipo_identificacion. $afiliado->identificacion;
                    $this->pushMonitor($log,$this->proceso,'white--text');
                    $this->guardarPendiente($log);
                    $conErrores = TRUE;
                    continue;
                }

                $this->pushMonitor('Se proceso la glosa '.$glosa->tipo->codigo. ', afiliado: '. $afiliado->identificacion,$this->proceso,'white--text');
            }catch (\Exception $e)
            {
                $this->pushMonitor('Ocurrio un error procesando la glosa: '.$glosa->tipo->codigo,$this->proceso,'white--text');
                $this->guardarPendiente('Ocurrio un error procesando la glosa: '.$glosa->tipo->codigo.' Error: '.$e->getMessage());
                continue;
            }
        }
        return $conErrores;
    }

}