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
use App\Models\Aseguramiento\AsBduaarchivo;
use App\Models\Aseguramiento\AsBduaregrespuesta;
use App\Models\Aseguramiento\AsBduarespuesta;
use App\Models\Aseguramiento\AsTramite;
use Illuminate\Support\Facades\Log;

class R5 extends Procesador implements ProcesadorCSVInterface
{

    public function procesar()
    {
        $this->respuesta = $this->crearRespuestaR5('Valido');
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

                $afiliado = $this->getAfiliado($datum[2],$datum[3]);

                $tramite = AsTramite::where('as_bduaarchivo_id', $this->respuesta->as_bduaarchivo_id)
                    ->where('consecutivo_reporte',(int)$datum[5])->whereHas('traslado', function ($query) use ($afiliado){
                        $query->where('as_afiliado_id', $afiliado->id);
                    })->first();

                if(!$tramite){
                    throw new \Exception(
                        'No se encontro el tramite: '.
                        $this->respuesta->as_bduaarchivo_id.' Consecutivo tramite: '.$datum[5].
                        ' Afiliado: '.$afiliado->with('tipo_id')->first()->tipo_id->abreviatura.' '.$afiliado->identificacion
                    );
                }
                if (intval($datum[9])==1) {
                    $tramite->estado = 'Aprobado';
                    $afiliado->as_regimene_id = 1;
                    $afiliado->save();
                    $tramite->save();
                    $this->pushMonitor('Traslado Contributivo Aprobado por entidad: '.$datum[4], $this->proceso, 'console-success');
                }
                if (intval($datum[9])==0) {
                    $tramite->estado = 'Negado';
                    $tramite->save();
                    $filaRespuesta = implode(',',$datum);
    
                    $regRespuesta = AsBduaregrespuesta::create(
                        [
                            'as_bduarespuesta_id' => $this->respuesta->id,
                            'as_tramite_id' => $tramite->id,
                            'respuesta' => $filaRespuesta
                        ]
                    );
                    $this->pushMonitor('Traslado Contributivo Negado por entidad 2. Consecutivo tramite: '.$datum[5].' Respuesta: '.$regRespuesta->respuesta, $this->proceso, 'error--text');
                }
                $this->aplicadas ++;
                $this->pushAvance($this->proceso, $this->respuesta);


            }catch (\Exception $e){
                $this->guardarPendiente($e->getMessage());
                $this->pushMonitor($e->getMessage(),$this->proceso,'error--text');
                continue;
            }
        }
    }

    private function crearRespuestaR5($tipo)
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