<?php

namespace App\Models\Validador4505;

class Unificacion {
	private $archivos;
	public function __construct($archivos) {
		$this->archivos = $archivos;
	}

	/**
	 * @return mixed
	 */
	public function getArchivos() {
		return $this->archivos;
	}

	/**
	 * @param mixed $archivos
	 *
	 * @return self
	 */
	public function setArchivos($archivos) {
		$this->archivos = $archivos;

		return $this;
	}
	function unificarArchivo() {
		if (!is_array($this->archivos)) {
			return false;
		}
		$archivosUnificados = null;
		for ($i = 0; $i < count($this->archivos); $i++) {
			$archivo = explode(PHP_EOL, $this->archivos[$i]);
			for ($j = 0; $j < count($archivo); $j++) {
				$registroDescomprimido = explode('|', trim($archivo[$j]));
				if (count($registroDescomprimido) == 5) {
				} else {
					if (count($registroDescomprimido) == 119) {
						$archivosUnificados[] = $this->generaCollecion($registroDescomprimido);
					}
				}
			}
		}
		return $this->unirDescomprimido($this->eliminarDatos(collect($archivosUnificados)));
	}
	function generaCollecion($datoDescomprimido) {
		$asociativo = null;
		$i = 0;
		foreach ($datoDescomprimido as $value) {
			$asociativo['variable' . $i] = $value;
			$i++;
		}
		return $asociativo;
	}
	function eliminarDatos($collection) {
		$collectionTemp = $collection;
		$arrayGeneral = null;
		$arrayDuplicados = null;
		$arrayFinal = null;
		foreach ($collectionTemp as $value) {
			$resultadoConsulta = $collection->where("variable3", $value['variable3'])->where("variable4", $value['variable4']);
			if ($resultadoConsulta->count() == 1) {
				$arrayGeneral[] = $resultadoConsulta->first();
			} else {
				foreach ($resultadoConsulta->all() as $duplicado) {
					$arrayDuplicados[] = $duplicado;
				}
				//$arrayGeneral[] = $this->unificarDuplicadoAfiliado($resultadoConsulta->all());
			}
		}
		if (!is_null($arrayDuplicados)) {
			$arrayFinal[] = [
				'alerta' => '/-//-//-//-//-//-//-//-//-//-//-//-//-//-//-//-//-//-//-//-/' . PHP_EOL . ' ATENCION AFILIADOS DUPLICADOS INICIO ' . PHP_EOL . '/-//-//-//-//-//-//-//-//-//-//-//-//-//-//-//-//-//-//-//-/' . PHP_EOL,
			];
			foreach ($arrayDuplicados as $value) {
				$value['variable1'] = 'ASIGNE CONSECUTIVO';
				$arrayFinal[] = $value;
			}
			$arrayFinal[] = [
				'alerta' => PHP_EOL . '/-//-//-//-//-//-//-//-//-//-//-//-//-//-//-//-//-//-//-//-/' . PHP_EOL . ' ATENCION AFILIADOS DUPLICADOS FIN ' . PHP_EOL . '/-//-//-//-//-//-//-//-//-//-//-//-//-//-//-//-//-//-//-//-/' . PHP_EOL,
			];
		}
		$consecutivo = 1;
		foreach ($arrayGeneral as $value) {
			$value['variable1'] = $consecutivo;
			$consecutivo++;
			$arrayFinal[] = $value;
		}
		return $arrayFinal;
	}
	function unirDescomprimido($decomprimido) {
		$arrayString = null;
		foreach ($decomprimido as $value) {
			$arrayString[] = implode('|', $value) . PHP_EOL;
		}
		return $arrayString;
	}
	function unificarDuplicadoAfiliado($duplicados) {
		$arrayFinal = null;
		$duplicadoTemporal = $duplicados;
		foreach ($duplicados as $aComporar) {
			foreach ($duplicados as $variables) {
				$i = 0;
				foreach ($variables as $value) {
					if ($i != 0 && $i != 1 && $i != 2) {
						if ($aComporar["variable" . $i] != $value) {
							dd($aComporar["variable" . $i] . "  DIFERENTES  " . $value);
						}
					}
					$i++;
				}
			}
		}
	}
}