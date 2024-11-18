<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 6/11/2018
 * Time: 9:27 AM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;


use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesador;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Models\Aseguramiento\AsBduaarchivo;
use App\Models\Aseguramiento\AsBduaregrespuesta;
use App\Models\Aseguramiento\AsTramite;
use Illuminate\Support\Facades\Log;

class R1VAL extends Procesador implements ProcesadorCSVInterface
{
    public function procesar()
    {
        $this->respuesta = $this->crearRespuesta('Valido');
        if (!$this->respuesta) {
            return;
        }
        $this->procesadas = 0;
        $this->aplicadas = 0;

        foreach ($this->data as $datum) {

            try {
                $this->procesadas++;
                $tramite = AsTramite::where('as_bduaarchivo_id', $this->respuesta->as_bduaarchivo_id)
                    ->where('consecutivo_reporte', $datum[10])->first();

                if (!$tramite) {
                    $log = 'No se encontro el tramite:' . $this->respuesta->as_bduaarchivo_id . ' ' . $datum[10];
                    $this->guardarPendiente($log);
                    $this->pushMonitor($log, $this->proceso, 'error--text');
                    continue;
                }

                $regRespuesta = AsBduaregrespuesta::create(
                    [
                        'as_bduarespuesta_id' => $this->respuesta->id,
                        'as_tramite_id' => $tramite->id,
                        'respuesta' => implode(',', $datum)
                    ]
                );

                $tramite->estado = 'Validado';

                $tramite->save();

                $this->aplicadas++;

                $this->pushMonitor('Se cambio el estado del tramite traslado contributivo a validado: ' . $regRespuesta->respuesta, $this->proceso, 'console-success');

                $this->pushAvance($this->proceso, $this->respuesta);

            } catch (\Exception $e) {
                $this->pushMonitor($e->getMessage(), $this->proceso, 'error--text');
                continue;
            }

        }
    }
}