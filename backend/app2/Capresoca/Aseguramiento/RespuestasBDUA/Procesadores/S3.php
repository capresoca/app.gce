<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 6/11/2018
 * Time: 5:02 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;

use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorNegados;
use App\Capresoca\Aseguramiento\RespuestasBDUA\S1Trait;
use App\Models\Aseguramiento\AsBduaarchivo;
use App\Models\Aseguramiento\AsBduaregrespuesta;
use App\Models\Aseguramiento\AsBduarespuesta;
use Illuminate\Support\Facades\Log;

class
S3 extends ProcesadorNegados implements ProcesadorCSVInterface {
	use S1Trait;

	public function procesar() {
		$this->respuesta = $this->crearRespuestaS3('Glosas');
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
				$afiliado = $this->getAfiliado($datum[1], $datum[2]);

				$tramite = $this->getTramite($afiliado);

				$tramite->estado = 'Glosado';
				$tramite->save();

				$filaRespuesta = implode(',', $datum);

				$regRespuesta = AsBduaregrespuesta::create(
					[
						'as_bduarespuesta_id' => $this->respuesta->id,
						'as_tramite_id' => $tramite->id,
						'respuesta' => $filaRespuesta,
					]
				);

				$bduaGlosas = $this->crearGlosas($datum);

				$regRespuesta->glosas()->createMany($bduaGlosas);

				$this->procesarGlosas($afiliado, $regRespuesta);
				$this->pushAvance($this->proceso, $this->respuesta);
				$this->aplicadas++;
			} catch (\Exception $e) {
				$this->procesadas++;
				$this->guardarPendiente($e->getMessage());
				$this->pushMonitor($e->getMessage(), $this->proceso, 'error--text');
				continue;
			}
		}
	}

	private function crearRespuestaS3($tipo) {
		$nombre_archivo_enviado = 'S1' . substr($this->fileName, 2, 14);
		Log::info($nombre_archivo_enviado);

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