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

class R4NEG extends ProcesadorNegados implements ProcesadorCSVInterface {

use TrasladosTrait;

	public function procesar() {

		$this->respuesta = $this->crearRespuesta('Glosas');
		if (!$this->respuesta) {
			$this->pushMonitor('No se pudo crear la respuesta. El archivo no existe: '.$this->fileName, $this->proceso, 'error--text');
			return;
		}
		$this->pushMonitor('Iniciando proceso de glosas del archivos: '.$this->fileName, $this->proceso, 'info--text');
		$this->procesadas = 0;
        $this->aplicadas = 0;
		foreach ($this->data as $fila) {
			try {
                $this->procesadas++;
				$afiliado = $this->getAfiliado($fila[2], $fila[3]);
				$regimen = substr($this->fileName,0,8) == 'R4EPS025' ? 2 : 1;

				$traslado = $this->getSolTrasladoTramiteContributivo($afiliado,(int)$fila[4],$this->proceso->id,$regimen);

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
				$this->aplicadas++;
				$this->pushMonitor('Proceso creado', $this->proceso, 'console--success');
				$this->pushAvance($this->proceso, $this->respuesta);
			} catch (\Exception $e) {
				$this->guardarPendiente($e->getMessage());
				$this->pushMonitor($e->getMessage(), $this->proceso, 'error--text');
				continue;
			}

		}
		$this->pushMonitor('Archivo procesado por completo: '.$this->fileName, $this->proceso, 'white--text');
	}

}