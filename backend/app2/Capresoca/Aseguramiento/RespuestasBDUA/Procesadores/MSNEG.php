<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/09/2018
 * Time: 9:02 AM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;

use App\Capresoca\Aseguramiento\RespuestasBDUA\MSTrait;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorNegados;
use App\Models\Aseguramiento\AsBduaregrespuesta;
use Illuminate\Support\Facades\Log;

class MSNEG extends ProcesadorNegados implements ProcesadorCSVInterface {
	use MSTrait;

	public function procesar() {
		$this->respuesta = $this->crearRespuesta('Glosas');
		if (!$this->respuesta) {
			Log::info('No crear glosas');
			return;
		}
		foreach ($this->data as $fila) {
			try {
				$this->procesadas++;
				$afiliado = $this->getAfiliado($fila[1], $fila[2]);
				$tramite = $this->getTramite($afiliado, $fila[12]);

				$tramite->estado = 'Glosado';
				$tramite->save();

				$filaRespuesta = implode(',', $fila);

				$regRespuesta = AsBduaregrespuesta::create(
					[
						'as_bduarespuesta_id' => $this->respuesta->id,
						'as_tramite_id' => $tramite->id,
						'respuesta' => $filaRespuesta,
					]
				);
				$bduaGlosas = $this->crearGlosas($fila);

				$regRespuesta->glosas()->createMany($bduaGlosas);

				$this->procesarGlosas($afiliado, $regRespuesta);
				$this->pushMonitor('Proceso creado', $this->proceso, 'console--success');
				$this->pushAvance($this->proceso, $this->respuesta);
				$this->aplicadas++;
			} catch (\Exception $e) {
				//Log::error($e);
				$this->guardarPendiente($e->getMessage());
				$this->pushMonitor($e->getMessage(), $this->proceso, 'error--text');
				continue;
			}

		}

		$this->pushAvance($this->proceso, $this->respuesta);

	}

}