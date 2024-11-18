<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 26/09/2018
 * Time: 5:26 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;

use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesador;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Capresoca\Aseguramiento\RespuestasBDUA\S1Trait;
use App\Models\Aseguramiento\AsBduaregrespuesta;
use App\Models\Aseguramiento\AsBduarespuesta;
use App\Models\Aseguramiento\AsBduaarchivo;
use Illuminate\Support\Facades\Log;


class S5 extends Procesador implements ProcesadorCSVInterface
{
    use S1Trait;
    public function procesar()
    {
        
		$this->respuesta = $this->crearRespuestaS5('Valido');
		if (!$this->respuesta) {
			$log = 'No se encuentra archivo S1 en este proceso';
			$this->pushMonitor($log, $this->proceso, 'error--text');
			return;
		}
		$this->procesadas = 0;
		$this->aplicadas = 0;

		foreach ($this->data as $datum) {
			try {

				$this->procesadas++;
				$afiliado = $this->getAfiliado($datum[2], $datum[3]);

				$tramite = $this->getTramite($afiliado);
                if (intval($datum[7])==1) {
                    $tramite->estado = 'Aprobado';
                    $afiliado->as_regimene_id = 2;
                    $afiliado->save();
                    $tramite->save(); 
                    $this->pushMonitor('Traslado Subsidiado Aprobado por entidad: '.$datum[4], $this->proceso, 'console-success');
                }
                if (intval($datum[7])==0) {
                    $tramite->estado = 'Negado';
                    $tramite->save();

                    $filaRespuesta = implode(',', $datum);
    
                    $regRespuesta = AsBduaregrespuesta::create(
                        [
                            'as_bduarespuesta_id' => $this->respuesta->id,
                            'as_tramite_id' => $tramite->id,
                            'respuesta' => $filaRespuesta,
                        ]
                    );
                    $this->pushMonitor('Traslado Subsidiado Negado por entidad 2. Consecutivo tramite: '.$tramite->consecutivo.' Respuesta: '.$regRespuesta->respuesta, $this->proceso, 'white--text');
                }
				$this->aplicadas++;
				$this->pushAvance($this->proceso, $this->respuesta);
			} catch (\Exception $e) {
				$this->guardarPendiente($e->getMessage());
				$this->pushMonitor($e->getMessage(), $this->proceso, 'error--text');
				continue;
			}
		}
    }

	private function crearRespuestaS5($tipo) {
		$nombre_archivo_enviado = 'S1' . substr($this->fileName, 2, 14);

		$bduaArchivo = AsBduaarchivo::where('nombre', $nombre_archivo_enviado)->first();

		if (!$bduaArchivo) {
			return;
		}

		$gnArchivo = $this->crearGnArchivo();

		return AsBduarespuesta::create([
			'as_bduaarchivo_id' => $bduaArchivo->id,
			'tipo_respuesta' => $tipo,
			'gn_archivo_id' => $gnArchivo->id,
			'total_registros' => $this->numFilas,
			'avance' => $this->procesadas,
			'aplicadas' => $this->aplicadas,
		]);
	}
}