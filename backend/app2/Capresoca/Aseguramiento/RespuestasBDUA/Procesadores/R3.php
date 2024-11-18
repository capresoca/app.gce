<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 6/11/2018
 * Time: 11:08 AM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;


use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesador;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorNegados;
use App\Models\Aseguramiento\AsBduaarchivo;
use App\Models\Aseguramiento\AsBduaregrespuesta;
use App\Models\Aseguramiento\AsBduarespuesta;
use App\Models\Aseguramiento\AsTramite;
use Illuminate\Support\Facades\Log;

class R3 extends ProcesadorNegados implements ProcesadorCSVInterface
{

    public function procesar()
    {
        Log::info('Procecando archivo R3');
        $this->respuesta = $this->crearRespuestaR3('Glosas');
        if(!$this->respuesta){
            $log = 'No se encuentra R1';
            $this->pushMonitor($log,$this->proceso,'error--text');
            return;
        }
        $this->procesadas = 0;
        $this->aplicadas = 0;

        foreach ($this->data as $datum) {
            try{

                $this->procesadas ++;
                $afiliado = $this->getAfiliado($datum[1],$datum[2]);

                $tramite = AsTramite::where('as_bduaarchivo_id', $this->respuesta->as_bduaarchivo_id)
                    ->where('consecutivo_reporte',(int)$datum[10])->first();

                if(!$tramite){
                    throw new \Exception(
                        'No se encontro el tramite: '.
                        $this->respuesta->as_bduaarchivo_id.' '.$datum[10]
                    );
                }

                $tramite->estado = 'Glosado';
                $tramite->save();

                $filaRespuesta = implode(',',$datum);

                $regRespuesta = AsBduaregrespuesta::create(
                    [
                        'as_bduarespuesta_id' => $this->respuesta->id,
                        'as_tramite_id' => $tramite->id,
                        'respuesta' => $filaRespuesta
                    ]
                );
                $bduaGlosas = $this->crearGlosas($datum);

                $regRespuesta->glosas()->createMany($bduaGlosas);

                $this->aplicadas ++;
                $this->procesarGlosas($afiliado, $regRespuesta);
                $this->pushAvance($this->proceso, $this->respuesta);


            }catch (\Exception $e){
                $this->procesadas ++;
                $this->guardarPendiente($e->getMessage());
                $this->pushMonitor($e->getMessage(),$this->proceso,'error--text');
                continue;
            }
        }
    }

    private function crearRespuestaR3($tipo)
    {
        $nombre_archivo_enviado = 'R1'.substr($this->fileName,2,14);
        Log::info('Archivo enviado r1: '.$nombre_archivo_enviado);

        $bduaArchivo = AsBduaarchivo::where('nombre', $nombre_archivo_enviado)->first();

        if(!$bduaArchivo)
            return;

        $gnArchivo = $this->crearGnArchivo();

        return AsBduarespuesta::create([
            'as_bduaarchivo_id' => $bduaArchivo->id,
            'tipo_respuesta' =>  $tipo,
            'gn_archivo_id' => $gnArchivo->id,
            'total_registros' => $this->numFilas,
            'avance' => $this->procesadas,
            'aplicadas' => $this->aplicadas
        ]);
    }
}