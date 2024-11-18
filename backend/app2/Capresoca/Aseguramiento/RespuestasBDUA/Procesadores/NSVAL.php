<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 18/09/2018
 * Time: 8:49 AM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;

use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesador;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Models\Aseguramiento\{
    AsBduaaccione,
    AsBduaregrespuesta,
    AsTramite
};
use Illuminate\Support\Facades\Log;
use App\Capresoca\Aseguramiento\RespuestasBDUA\NovedadesValTrait;

class NSVAL extends Procesador implements ProcesadorCSVInterface
{
    use NovedadesValTrait;

    public function procesar()
    {
        $this->respuesta = $this->crearRespuesta('Valido');
        if(!$this->respuesta){
            return;
        }
        $this->procesadas = 0;
        $this->aplicadas = 0;



        $log = 'Se inicio el procesamiento de la respuesta ' . $this->respuesta->tipo_respuesta .': '. $this->respuesta->archivo->nombre;

        $this->pushMonitor($log, $this->proceso,'info');


        foreach ($this->data as $novedad) {
            try{
                $this->procesadas ++;
                $log = 'Procesando novedad Subsidiado: '.$novedad[0];
                $this->pushAvance($this->proceso, $this->respuesta);
                $this->pushMonitor($log,$this->proceso,'white--text');

                $afiliado = $this->getAfiliado($novedad[2],$novedad[3]);


                $tramite = AsTramite::where('as_bduaarchivo_id', $this->respuesta->as_bduaarchivo_id)
                    ->where('consecutivo_reporte', $novedad[0])->first();
                if(!$tramite) {
                    $log = 'No se encontro el tramite:'.$this->respuesta->as_bduaarchivo_id.' '.$novedad[0];
                    $this->guardarPendiente($log);
                    $this->pushMonitor($log,$this->proceso,'error--text');
                    continue;
                }
                $tramite->estado = 'Validado';
                $tramite->save();

                $regRespuesta = AsBduaregrespuesta::create(
                    [
                        'as_bduarespuesta_id' => $this->respuesta->id,
                        'as_tramite_id' => $tramite->id,
                        'respuesta' => implode(',', $novedad)
                    ]
                );

                $tipNove = $novedad[11];

                if(!method_exists($this, $tipNove)) continue;

                $this->$tipNove($afiliado, $novedad, $regRespuesta);

                $this->pushMonitor('Se proceso la novedad Subsidiada: '.$novedad[0],$this->proceso,'console-success');

            }catch (\Exception $e)
            {
                $this->pushMonitor($e->getMessage(),$this->proceso,'error--text');
                continue;
            }
        }


    }

    private function guardarAccion($afiliado, $regRespuesta, $oldValues = array(), $newValues = array(), $accion = 'Se actualizaron los datos del afiliado')
    {
        $accion = AsBduaaccione::create(
            [
                'as_bduaregrespuesta_id' => $regRespuesta->id,
                'as_afiliado_id' => $afiliado->id,
                'accion' => $accion,
                'new_values' => json_encode($newValues, JSON_UNESCAPED_UNICODE),
                'old_values' => json_encode($oldValues, JSON_UNESCAPED_UNICODE)
            ]
        );

        if($accion){
            $this->aplicadas ++;
            $this->pushMonitor('Se actualizaron los datos del afiliado: '.$afiliado->tipo_identificacion.' '.$afiliado->identificacion ,$this->proceso,'console-success');
        }

        $this->pushAvance($this->proceso, $this->respuesta);

    }


    private function novedadSinAlgoritmo($novedad)
    {
        $log = 'No se ha implementado la función para procesar esta novedad: ' .$novedad[11];
        $this->guardarPendiente($log);
        $this->pushMonitor($log,$this->proceso,'error--text');
    }


}