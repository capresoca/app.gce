<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 6/11/2018
 * Time: 9:27 AM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;


use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesador;
use App\Capresoca\Aseguramiento\RespuestasBDUA\TrasladosTrait;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Models\Aseguramiento\AsBduaaccione;
use App\Models\Aseguramiento\AsBduaregrespuesta;

class R4VAL extends Procesador implements ProcesadorCSVInterface
{
    use TrasladosTrait;
    
    public function procesar()
    {
        $this->respuesta = $this->crearRespuesta('Valido');
        if(!$this->respuesta){
			$this->pushMonitor('No se pudo crear la respuesta. El archivo no existe: '.$this->fileName, $this->proceso, 'error--text');
            return;
        }
        $this->procesadas = 0;
        $this->aplicadas = 0;

        foreach ($this->data as $datum) {

            try{
                $this->procesadas ++;
                
                $afiliado = $this->getAfiliado($datum[2],$datum[3]);

                $regimen = substr($this->fileName,0,8) == 'R4EPS025' ? 2 : 1;

                $solTraslado =$this->getSolTrasladoTramiteContributivo($afiliado,ltrim($datum[4],'0'),$this->proceso->id,$regimen);

                $regRespuesta = AsBduaregrespuesta::create(
                    [
                        'as_bduarespuesta_id' => $this->respuesta->id,
                        'as_tramite_id' => $solTraslado->as_tramite_id,
                        'respuesta' => implode(',', $datum)
                    ]
                );

                $solTraslado->estado = 'Validado';

                $solTraslado->save();


                $this->aplicadas ++;

                $this->pushMonitor('Se cambio el estado del tramite traslado contributivo a validado: '. $regRespuesta->respuesta, $this->proceso,'console-success');

                $this->pushAvance($this->proceso, $this->respuesta);

            } catch (\Exception $e){
                $this->pushMonitor($e->getMessage(),$this->proceso,'error--text');
                continue;
            }

        }
		$this->pushMonitor('Archivo procesado por completo: '.$this->fileName, $this->proceso, 'white--text');
    }

}