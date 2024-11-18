<?php
namespace App\Libraries;

use Illuminate\Support\Facades\Log;

class Util {
	
	const TABLE_PAGINATOR		= 10;
	const EMTPY_SELECT_OPTION	= '------------';
	const DECIMALES_SISTEMA		= 2;
	
	const USUARIO               = 1;
	const CAMPOS_ADICIONALES_S1     = FALSE;
	
	private static $UNIDADES = array(
		'',
		'UN ',
		'DOS ',
		'TRES ',
		'CUATRO ',
		'CINCO ',
		'SEIS ',
		'SIETE ',
		'OCHO ',
		'NUEVE ',
		'DIEZ ',
		'ONCE ',
		'DOCE ',
		'TRECE ',
		'CATORCE ',
		'QUINCE ',
		'DIECISEIS ',
		'DIECISIETE ',
		'DIECIOCHO ',
		'DIECINUEVE ',
		'VEINTE '
	);
	
	private static $DECENAS = array(
		'VEINTI',
		'TREINTA ',
		'CUARENTA ',
		'CINCUENTA ',
		'SESENTA ',
		'SETENTA ',
		'OCHENTA ',
		'NOVENTA ',
		'CIEN '
	);
	
	private static $CENTENAS = array(
		'CIENTO ',
		'DOSCIENTOS ',
		'TRESCIENTOS ',
		'CUATROCIENTOS ',
		'QUINIENTOS ',
		'SEISCIENTOS ',
		'SETECIENTOS ',
		'OCHOCIENTOS ',
		'NOVECIENTOS '
	);
	
	private static $MONEDAS = array(
		array(
			'country' => 'Colombia',
			'currency' => 'COP',
			'singular' => 'PESO COLOMBIANO',
			'plural' => 'PESOS COLOMBIANOS',
			'symbol',
			'$'
		),
		array(
			'country' => 'Estados Unidos',
			'currency' => 'USD',
			'singular' => 'D�LAR',
			'plural' => 'D�LARES',
			'symbol',
			'US$'
		),
		array(
			'country' => 'El Salvador',
			'currency' => 'USD',
			'singular' => 'D�LAR',
			'plural' => 'D�LARES',
			'symbol',
			'US$'
		),
		array(
			'country' => 'Europa',
			'currency' => 'EUR',
			'singular' => 'EURO',
			'plural' => 'EUROS',
			'symbol',
			'�'
		),
		array(
			'country' => 'M�xico',
			'currency' => 'MXN',
			'singular' => 'PESO MEXICANO',
			'plural' => 'PESOS MEXICANOS',
			'symbol',
			'$'
		),
		array(
			'country' => 'Per�',
			'currency' => 'PEN',
			'singular' => 'NUEVO SOL',
			'plural' => 'NUEVOS SOLES',
			'symbol',
			'S/'
		),
		array(
			'country' => 'Reino Unido',
			'currency' => 'GBP',
			'singular' => 'LIBRA',
			'plural' => 'LIBRAS',
			'symbol',
			'�'
		),
		array(
			'country' => 'Argentina',
			'currency' => 'ARS',
			'singular' => 'PESO',
			'plural' => 'PESOS',
			'symbol',
			'$'
		)
	);
	
	private static $separator = '.';
	private static $decimal_mark = ',';
	private static $glue = ' CON ';
	
	/**
	 * 
	 */
	public static function getPerToDto($per,$dto) {
		$reflector 		= new \ReflectionClass($per);
		$properties 	= $reflector->getProperties();
		
		foreach($properties as $property) {
			$dto->{$property->getName()}		= $per->getVal($property->getName());
		}
	}
	
	public static function getArrayToDto($array,$dto) {
		foreach ($array as $key => $value) {
			$dto->{$key}	= $value;
		}
		
	}
	
	/**
	 * * @author miguel.carmona caring 2019.05.16
	 * @tutorial metodo que separa para validar si es una edicion con su respectivo id o findo que traiga
	 * @param string $edit
	 * @return array|NULL[]
	 */
	public static function getEditForm($edit) {
		$vector		= explode('-',$edit);
		if(count($vector)>1) {
			return $vector;
		}
		return array(NULL,NULL);
	}
	
	/**
	 * @author miguel.carmona caring 2019.05.16
	 * @tutorial metodo que valida si una cadena viene vacia
	 * @return boolean
	 */
	public static function isEmpty($string) {
		$isEmpty		= FALSE;
		if($string=='0') {
			$isEmpty	= FALSE;
		} elseif(is_null($string)) {
			$isEmpty	= TRUE;
		} elseif(strlen(trim($string))<=0) {
			$isEmpty	= TRUE;
		} elseif(empty($string)) {
		    $isEmpty	= TRUE;
		}
		return $isEmpty;
	}
	
	/**
	 * @author miguel.carmona Carmona Ingenieria 2019.05.08
	 * 
	 * @tutorial metodo que crea una excepcion de necesitarla
	 * 
	 * @param \Exception $e
	 * @throws \Exception
	 */
	public static function ThrowsException(\Exception $e) {
		throw new \Exception($e->getMessage(),$e->getCode(),$e->getPrevious());
	}
	
	/**
	 * @author miguel.carmona Carmona Ingenieria 2019.05.08
	 * @tutorial metodo que valida si un array es lleno o tiene un indice especifico 
	 * @param multitype:object $array
	 * @param string $key
	 * @return boolean
	 */
	public static function EmptyArray($array,$key = NULL) {
		$empty		= FALSE;
		if(is_null($key)) {
			if(empty($array)) {
				$empty		= TRUE;
			}
		} else {
			if(empty($array)) {
				$empty		= TRUE;
			} else {
				if(array_key_exists($key,$array)) {
					if(is_null($array[$key])) {
						$empty		= TRUE;
					}
				} else {
					$empty		= TRUE;
				}
			}
		}
		return $empty;
	}
	
	public static function getRedondearNumeroDecimal($num) {
		return round($num,self::DECIMALES_SISTEMA, PHP_ROUND_HALF_EVEN);
	}
	
	public static function getFechaActual($formato = 'Y-m-d') {
		return date($formato);
	}
	
	public static function getFormatearNumero($number,$decimales = Util::DECIMALES_SISTEMA) {
		return number_format($number, $decimales, ',', '.');
	}
	
	public static function getLetraNumero($number, $miMoneda = null) {
		if (strpos($number, Util::$decimal_mark) === FALSE) {
			$convertedNumber = array(
				self::convertNumber($number, $miMoneda, 'entero')
			);
		} else {
			$number = explode(Util::$decimal_mark, str_replace(Util::$separator, '', trim($number)));
			$convertedNumber = array(
				self::convertNumber($number[0], $miMoneda, 'entero'),
				self::convertNumber($number[1], $miMoneda, 'decimal')
			);
		}
		return implode(Util::$glue, array_filter($convertedNumber));
	}
	
	/**
	 * Convierte n�mero a letras
	 *
	 * @param
	 *        	$number
	 * @return $converted string convertido
	 */
	private static function convertNumber($number, $miMoneda = null, $type) {
		$converted = '';
		if ($miMoneda !== null) {
			try {
				
				$moneda = array_filter(Util::$MONEDAS, function ($m) use ($miMoneda) {
					return ($m['currency'] == $miMoneda);
				});
					$moneda = array_values($moneda);
					if (count($moneda) <= 0) {
						throw new \Exception("Tipo de moneda inv�lido");
						return;
					}
					($number < 2 ? $moneda = $moneda[0]['singular'] : $moneda = $moneda[0]['plural']);
			} catch (\Exception $e) {
				echo $e->getMessage();
				return;
			}
		} else {
			$moneda = '';
		}
		if (($number < 0) || ($number > 999999999)) {
			return false;
		}
		$numberStr = (string) $number;
		$numberStrFill = str_pad($numberStr, 9, '0', STR_PAD_LEFT);
		$millones = substr($numberStrFill, 0, 3);
		$miles = substr($numberStrFill, 3, 3);
		$cientos = substr($numberStrFill, 6);
		if (intval($millones) > 0) {
			if ($millones == '001') {
				$converted .= 'UN MILLON ';
			} else if (intval($millones) > 0) {
				$converted .= sprintf('%sMILLONES ', self::convertGroup($millones));
			}
		}
		
		if (intval($miles) > 0) {
			if ($miles == '001') {
				$converted .= 'MIL ';
			} else if (intval($miles) > 0) {
				$converted .= sprintf('%sMIL ', self::convertGroup($miles));
			}
		}
		if (intval($cientos) > 0) {
			if ($cientos == '001') {
				$converted .= 'UN ';
			} else if (intval($cientos) > 0) {
				$converted .= sprintf('%s ', self::convertGroup($cientos));
			}
		}
		$converted .= $moneda;
		return $converted;
	}
	
	/**
	 * Define el tipo de representaci�n decimal (centenas/millares/millones)
	 *
	 * @param
	 *        	$n
	 * @return $output
	 */
	private static function convertGroup($n) {
		$output = '';
		$centenas = Util::$CENTENAS;
		$unidades = Util::$UNIDADES;
		$decenas = Util::$DECENAS;
		if ($n == '100') {
			$output = "CIEN ";
		} else if ($n[0] !== '0') {
			
			$output = $centenas[$n[0] - 1];
		}
		$k = intval(substr($n, 1));
		if ($k <= 20) {
			$output .= $unidades[$k];
		} else {
			if (($k > 30) && ($n[2] !== '0')) {
				$output .= sprintf('%sY %s', $decenas[intval($n[1]) - 2], $unidades[intval($n[2])]);
			} else {
				$output .= sprintf('%s%s', $decenas[intval($n[1]) - 2], $unidades[intval($n[2])]);
			}
		}
		return $output;
	}
	
	public static function getMayusculaTodo($texto) {
		return mb_strtoupper($texto);
	}
	
	public static function setError($field,$error) {
	}
	
	public static function isErrors() {
	}
	
	/**
	 * 
	 * @param string $modulo
	 * @param string $accion
	 */
	public static function getSinPermiso($modulo,$accion=NULL) {
		$sinPermiso		= FALSE;
		/**
		 */
		$user			= self::getSessionDto()->usuarioSession;
		if($user==NULL) {
			$sinPermiso	= TRUE;
		} else {
			if($accion!=NULL) {
			} elseif(!$sinPermiso) {
				$sinPermiso = !$user->getPermiteModulo($modulo);
			}
		}

		if($sinPermiso && $accion==NULL) {
		}
		return $sinPermiso;
	}
	
	public static function getSinAccionPermiso($accion) {
		$sinPermiso		= FALSE;
		$user			= self::getSessionDto()->usuarioSession;
	}
	
	/**
	 */
	public static function getSessionDto() {
	}
	
	public static function getLogger() {
	}
	
	public static function getOpcionCaja($tipo,$validacion,$name='',$value='') {
		$retorna			= NULL;
		$retorna			= '<input type="text" is="p-inputtext" onkeyup="mayus(this);" value="'.$value.'"  name="'.$name.'"/>';
		
		return $retorna;
	}
	
	public static function getMeses() {
	    $meses     = [
	        1=>'Enero',
	        2=>'Febrero',
	        3=>'Marzo',
	        4=>'Abril',
	        5=>'Mayo',
	        6=>'Junio',
	        
	        7=>'Julio',
	        8=>'Agosto',
	        9=>'Septimbre',
	        10=>'Octubre',
	        11=>'Noviembre',
	        12=>'Diciembre',
	    ];
	    return $meses;
	}
	
	public static function getNombreMes($mes) {
	   return self::getMeses()[(int) $mes];
	}
	
	public static function getDescomprimirArchivo(string $rutaArchivoEntrada, string $cpraiz, string $usuario) {
	    $rutaBase          = substr($rutaArchivoEntrada, 0,strrpos($rutaArchivoEntrada,'/'));// $rutaArchivoEntrada.substring(0, $rutaArchivoEntrada.lastIndexOf("/"));
	    $nombreCarpeta     = "" . str_replace(array(' ','.'), '', microtime()) . $usuario;
	    $carpetaDestino    = $rutaBase . DIRECTORY_SEPARATOR . $cpraiz . DIRECTORY_SEPARATOR . "zip" . DIRECTORY_SEPARATOR . $nombreCarpeta.DIRECTORY_SEPARATOR;
	    $zip = new \ZipArchive();
	    if ($zip->open($rutaArchivoEntrada) === TRUE) {
	        $zip->extractTo($carpetaDestino);
	        $zip->close();
	        //Log::info('Se descomprime ruta: ',array($carpetaDestino));
	    } else {
	        Log::info('No se puede descomprimir ruta: ',array($carpetaDestino));
	    }
	    return $carpetaDestino;
	}
	
	public static function getBorrarDirectorioCompleto($carpeta)
	{
	    foreach(glob($carpeta . "/*") as $archivos_carpeta){
	        if (is_dir($archivos_carpeta)){
	            self::getBorrarDirectorioCompleto($archivos_carpeta);
	        } else {
	            unlink($archivos_carpeta);
	        }
	    }
	    rmdir($carpeta);
	}
}

