<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24/05/2018
 * Time: 5:52 PM
 */

namespace App\Traits;

use Carbon\Carbon;

trait ToolsTrait {
	//Para remover el elemento de un array buscando por valor
	public static function removerElement($valor, $arr) {
		if (($key = array_search($valor, $arr)) !== false) {
			unset($arr[$key]);
		}
		return $arr;
	}

	//Para validar que rangos no se superpongan entre si.
	public function validarSuperpocisionRangos($array) {
		$rangos_validos = [];
		foreach ($array as $rango) {
			if (count($rangos_validos)) {
				foreach ($rangos_validos as $rango_valido) {
					if ($this->superpone($rango_valido, $rango) or $this->superpone($rango, $rango_valido)) {
						return false;
					};
					array_push($rangos_validos, $rango);
				}
			} else {
				array_push($rangos_validos, $rango);
			}
		}

		return true;
	}

	//Para validar si un rango esta parcial o totalmente contenido en otro.
	private function superpone($rango_valido, $rango) {
		return $this->enRango($rango_valido, $rango['valor_inicial'])
		or $this->enRango($rango_valido, $rango['valor_final']);
	}

	//Para validar si un valor se encuentra en un rango dado.
	private function enRango($rango, $valor) {
		return $valor >= $rango['valor_inicial']
			and $valor <= $rango['valor_final'];
	}

	//Convertir una fecha ('YYYY-MM-DD') en un arreglo de 8 elementos '[A,A,A,A,D,D,M,M]', para visualización en formularios

	public static function fechaArray($fecha) {
		$fecha_string = substr($fecha, 0, 4) . substr($fecha, 5, 2) . substr($fecha, 8, 2);

		return str_split($fecha_string);

	}

	//Recibe fecha, si es d/m/Y, d.m.Y, d-m-Y, devuelve Y-m-d
	public static function homologarFecha(String $fecha) {
		$re = '/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/';

		preg_match($re, $fecha, $matches, PREG_OFFSET_CAPTURE, 0);
		if ($matches) {
			$separador = substr($fecha, 2, 1);
			return Carbon::createFromFormat('d' . $separador . 'm' . $separador . 'Y', $fecha)->toDateString();
		}

		return $fecha;
	}

	public static function quitarEspacios($string) {
		$trimmed = str_replace(' ', '', $string);
		$trimmed = rtrim($trimmed);

		return $trimmed;
	}

	public static function calcularDigitoVerificacion(String $nit) {
		try {

			$badDigits = ['/,/', '.', ' ', '-', ','];

			$nit = str_replace($badDigits, '', $nit);

			$nit = rtrim($nit);

			$digitos = array_reverse(str_split($nit));

			if (!$nit) {
				return;
			}

			$primos = [3, 7, 13, 17, 19, 23, 29, 37, 41, 43, 47, 53, 59, 67, 71];

			$sum = 0;

			foreach ($digitos as $key => $digito) {
				$sum += $digito * $primos[$key];
			}

			$residuo = $sum % 11;

			return ($residuo > 1) ? 11 - $residuo : $residuo;
		} catch (\Exception $e) {
			return;
		}

	}
	public static function monthBySpanish($mes, $format = "n") {
		switch ($mes) {
		case "Enero":
			if ($format == "n") {return "1";}
			if ($format == "m") {return "01";}
			break;
		case "Febrero":
			if ($format == "n") {return "2";}
			if ($format == "m") {return "02";}
			break;
		case "Marzo":
			if ($format == "n") {return "3";}
			if ($format == "m") {return "03";}
			break;
		case "Abril":
			if ($format == "n") {return "4";}
			if ($format == "m") {return "04";}
			break;
		case "Mayo":
			if ($format == "n") {return "5";}
			if ($format == "m") {return "05";}
			break;
		case "Junio":
			if ($format == "n") {return "6";}
			if ($format == "m") {return "06";}
			break;
		case "Julio":
			if ($format == "n") {return "7";}
			if ($format == "m") {return "07";}
			break;
		case "Agosto":
			if ($format == "n") {return "8";}
			if ($format == "m") {return "08";}
			break;
		case "Septiembre":
			if ($format == "n") {return "9";}
			if ($format == "m") {return "09";}
			break;
		case "Octubre":
			if ($format == "n") {return "10";}
			if ($format == "m") {return "10";}
			break;
		case "Noviembre":
			if ($format == "n") {return "11";}
			if ($format == "m") {return "11";}
			break;
		case "Diciembre":
			if ($format == "n") {return "12";}
			if ($format == "m") {return "12";}
			break;
		default:
			return "0";
			break;
		}
	}
	public static function monthTraslateSpanish($month) {
		switch (intval($month)) {
		case 1:
			return "Enero";
			break;
		case 2:
			return "Febrero";
			break;
		case 3:
			return "Marzo";
			break;
		case 4:
			return "Abril";
			break;
		case 5:
			return "Mayo";
			break;
		case 6:
			return "Junio";
			break;
		case 7:
			return "Julio";
			break;
		case 8:
			return "Agosto";
			break;
		case 9:
			return "Septiembre";
			break;
		case 10:
			return "Octubre";
			break;
		case 11:
			return "Noviembre";
			break;
		case 12:
			return "Diciembre";
			break;

		default:
			return "invalid";
			break;
		}
	}

    public static function checkCollation($file,$collationRequired)
    {
        $fileCollation = mb_detect_encoding($file,array(
            'UTF-8','ASCII',
            'ISO-8859-1', 'ISO-8859-2', 'ISO-8859-3', 'ISO-8859-4', 'ISO-8859-5',
            'ISO-8859-6', 'ISO-8859-7', 'ISO-8859-8', 'ISO-8859-9', 'ISO-8859-10',
            'ISO-8859-13', 'ISO-8859-14', 'ISO-8859-15', 'ISO-8859-16',
            'Windows-1251', 'Windows-1252', 'Windows-1254',
        ));

        if($fileCollation != $collationRequired){
            return false;
        }

        return true;
	}

}