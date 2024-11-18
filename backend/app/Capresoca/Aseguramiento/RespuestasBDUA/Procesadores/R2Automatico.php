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
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsBduarespuesta;
use App\Models\General\GnEmpresa;
use App\Models\Aseguramiento\AsEstadoafiliado;
use App\Models\Aseguramiento\AsSoltraslado;

class R2Automatico extends Procesador implements ProcesadorCSVInterface
{
    protected $empresa_contributivo=null;
    protected $estado_activo=3;
    protected $estado_retirado=4;
    
    public function procesar()
    {
        $this->respuesta = $this->crearRespuestaR2Automatico('Valido');
        if(!$this->respuesta){
            $this->pushMonitor('No se puede crear respuesta archivo R2 no encontrado',$this->proceso,'error--text');
            return;
        }
        $this->empresa_contributivo=GnEmpresa::first()->codhabilitacion_contrib;
        $this->procesadas = 0;
        $this->aplicadas = 0;

        $this->pushMonitor('Iniciando proceso',$this->proceso,'info--text');
        
        foreach ($this->data as $datum) {

            try{
                $this->procesadas ++;
                $afiliado = $this->getAfiliado($datum[2], $datum[3]);
                // se trae de trasla tramite o de soltraslado?
                /*$tramite = AsTramite::where('as_bduaarchivo_id', $this->respuesta->as_bduaarchivo_id)
                    ->whereHas('traslado', function ($query) use ($afiliado){
                        $query->where('as_afiliado_id', $afiliado->id);
                    })->first();

                if(!$tramite) {
                    $log = 'No se encontro el tramite:'.$this->respuesta->as_bduaarchivo_id.' '.$datum[10];
                    $this->guardarPendiente($log);
                    $this->pushMonitor($log,$this->proceso,'error--text');
                    continue;
                }*/
                $this->actualizardatosR2($afiliado,$datum);
                $tramite = AsSoltraslado::where('as_afiliado_id',$afiliado->id)
                    ->whereNotNull('as_tramite_id')->first();
                    if($tramite){
                        $regRespuesta = AsBduaregrespuesta::create(
                            [
                                'as_bduarespuesta_id' => $this->respuesta->id,
                                'as_tramite_id' => $tramite->id,
                                'respuesta' => implode(',', $datum)
                            ]
                        );

                        $tramite->estado = 'Validado';
        
                        $tramite->save();
                        $this->pushMonitor('Se cambio el estado del tramite traslado contributivo a VALIDADO y se aplicaron los cambios correspondientes: '. $regRespuesta->respuesta, $this->proceso,'console-success');
                    }else{
                        $this->pushMonitor('Se aplicaron los cambios pero no se cambio el estado del tramite ya que no existe una solicitud de traslado para el afiliado: '. implode(',', $datum), $this->proceso,'console-success');
                    }


                $this->aplicadas ++;
            } catch (\Exception $e){
                $this->guardarPendiente($e->getMessage());
                $this->pushMonitor($e->getMessage(),$this->proceso,'error--text');
                continue;
            }

        }
        $this->pushMonitor('Proceso finalizado',$this->proceso,'info--text');
    }
    private function actualizardatosR2(AsAfiliado $afiliado, $row){
        
        
        if ($this->empresa_contributivo == $row[1]) {
            $afiliado->as_regimene_id = 1;
            $afiliado->estado = $this->estado_activo;
        }else{
            $afiliado->estado = $this->retirado;
        }
        $afiliado->save();
    }

    

	private function crearRespuestaR2Automatico($tipo) {
        /*
		$nombre_archivo_enviado = 'R1'.str_replace('EPS025','EPSC25',substr($this->fileName, 14, 16));

		$bduaArchivo = AsBduaarchivo::where('nombre', $nombre_archivo_enviado)->first();

		if (!$bduaArchivo) {
			return;
        }
        */
		$gnArchivo = $this->crearGnArchivo();

		return AsBduarespuesta::create([
            'tipo_respuesta' => $tipo,
            'as_bduaarchivo_id' => NULL,
			'gn_archivo_id' => $gnArchivo->id,
			'total_registros' => $this->numFilas,
			'avance' => $this->procesadas,
			'aplicadas' => $this->aplicadas,
		]);
	}
}