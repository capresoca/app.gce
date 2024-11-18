<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/11/2018
 * Time: 2:48 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;


use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesador;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Capresoca\Aseguramiento\RespuestasBDUA\S1Trait;
use App\Models\Aseguramiento\AsBduaregrespuesta;
use Illuminate\Support\Facades\Log;

class S1VAL extends Procesador implements ProcesadorCSVInterface
{
    use S1Trait;

    public function procesar()
    {
        $this->respuesta = $this->crearRespuesta('Valido');
        if(!$this->respuesta){
            return;
        }
        $this->procesadas = 0;
        $this->aplicadas = 0;

        foreach ($this->data as $datum) {

            try{
                $this->procesadas ++;

                $afiliado = $this->getAfiliado($datum[1],$datum[2]);

                $tramite = $this->getTramite($afiliado);

                $regRespuesta = AsBduaregrespuesta::create(
                    [
                        'as_bduarespuesta_id' => $this->respuesta->id,
                        'as_tramite_id' => $tramite->id,
                        'respuesta' => implode(',', $datum)
                    ]
                );

                $tramite->estado = 'Validado';

                $tramite->save();


                $this->aplicadas ++;
                $this->pushMonitor('Se cambio el estado del tramite traslado subsidiado a validado: '. $regRespuesta->respuesta, $this->proceso,'console-success');

                $this->pushAvance($this->proceso, $this->respuesta);


            } catch (\Exception $e){
                $this->procesadas ++;
                $this->guardarPendiente($e->getMessage());
                $this->pushMonitor($e->getMessage(),$this->proceso,'error--text');
                continue;
            }

        }
    }
}