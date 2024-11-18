<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/09/2018
 * Time: 9:02 AM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;

use App\Capresoca\Aseguramiento\RespuestasBDUA\TrasladosTrait;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorNegados;
use App\Models\Aseguramiento\AsBduaregrespuesta;
use Illuminate\Support\Facades\Log;

class S4NEG extends ProcesadorNegados implements ProcesadorCSVInterface {

use TrasladosTrait;

	public function procesar() {
		$this->respuesta = $this->crearRespuesta('Glosas');
		if (!$this->respuesta) {
			$this->pushMonitor('No se pudo crear la respuesta. El archivo no existe: '.$this->fileName, $this->proceso, 'error--text');
			return;
		}
		
		$this->pushMonitor('Iniciando proceso de glosas del archivos: '.$this->fileName, $this->proceso, 'white--text');
		foreach ($this->data as $fila) {
			try {
                $this->procesadas++;
				$afiliado = $this->getAfiliado($fila[2], $fila[3]);
				$traslado = $this->getSolTrasladoTramiteSubsidiado($afiliado,$this->proceso->id);

				$traslado->estado = 'Glosado';
				$traslado->save();

				$filaRespuesta = implode(',', $fila);

				$regRespuesta = AsBduaregrespuesta::create(
					[
						'as_bduarespuesta_id' => $this->respuesta->id,
						'as_tramite_id' => $traslado->as_tramite_id,
						'respuesta' => $filaRespuesta,
					]
				);
				$bduaGlosas = $this->crearGlosas($fila);

				$regRespuesta->glosas()->createMany($bduaGlosas);

				$this->procesarGlosas($afiliado, $regRespuesta);
				$this->pushMonitor('Procesando glosas', $this->proceso, 'console--success');
				$this->pushAvance($this->proceso, $this->respuesta);
				$this->aplicadas++;
			} catch (\Exception $e) {
				$this->guardarPendiente($e->getMessage());
				$this->pushMonitor($e->getMessage(), $this->proceso, 'error--text');
				continue;
			}

		}
		
		$this->pushMonitor('Archivo procesado por completo: '.$this->fileName, $this->proceso, 'info--text');
	}

}