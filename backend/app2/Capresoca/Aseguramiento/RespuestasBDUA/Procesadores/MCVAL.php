<?php


namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;


use App\Capresoca\Aseguramiento\RespuestasBDUA\MSTrait;
use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesador;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Models\Aseguramiento\AsBduaaccione;
use App\Models\Aseguramiento\AsBduaregrespuesta;
use Illuminate\Support\Facades\Log;

class MCVAL extends Procesador implements ProcesadorCSVInterface
{
    use MSTrait;

    public function procesar()
    {
        $this->procesadas = 0;
        $this->aplicadas = 0;
        $this->respuesta = $this->crearRespuesta('Valido');
        $this->proceso = $this->respuesta->archivo->proceso;

        if(!$this->respuesta)
            return;

        $log = 'Se inicio el procesamiento de la respuesta ' . $this->respuesta->tipo_respuesta .': '. $this->respuesta->archivo->nombre;
        Log::info($log);
        $this->pushMonitor($log, $this->proceso,'info');


        foreach ($this->data as $afiliacion)
        {
            try {
                $this->procesadas++;
                $afiliado = $this->getAfiliado($afiliacion[3], $afiliacion[4]);

                $estado_activo = 3;
                $afiliado->estado = $estado_activo;
                $afiliado->save();
                $tramite = $this->getTramite($afiliado, $afiliacion[18]);

                $tramite->estado = 'Validado';
                $tramite->save();

                $filaRespuesta = implode(',', $afiliacion);

                $regRespuesta = AsBduaregrespuesta::create(
                    [
                        'as_bduarespuesta_id' => $this->respuesta->id,
                        'as_tramite_id' => $tramite->id,
                        'respuesta' => $filaRespuesta
                    ]
                );

                $accion = AsBduaaccione::create(
                    [
                        'as_bduaregrespuesta_id' => $regRespuesta->id,
                        'as_afiliado_id' => $afiliado->id,
                        'accion' => 'Se activo el afiliado'
                    ]
                );

                if ($accion) {
                    $this->aplicadas++;
                    $this->pushMonitor('Se activo el afiliado: ' . $afiliado->tipo_identificacion . ' ' .
                        $afiliado->identificacion, $this->proceso, 'console-success');
                }

                $this->pushAvance($this->proceso, $this->respuesta);
            } catch (\Exception $e){
                Log::error($e);
                $this->guardarPendiente($e->getMessage());
                $this->pushMonitor($e->getMessage(),$this->proceso,'error--text');
                continue;
            }


        }
    }
}