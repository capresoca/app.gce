<?php
namespace App\Models\Enums;

use App\Libraries\Util;

abstract class AEnumerado {
	
	protected $id					= NULL;
	protected $descripcion			= NULL;
	protected $auxiliar				= NULL;
		
	public function __construct($id, $descripcion, $auxiliar = NULL) {
		$this->id				= $id;
		$this->descripcion		= $descripcion;
		$this->auxiliar			= $auxiliar;
	}
	
	public abstract static function getValores();
	
	public static function JSon() {
		$array		= array(array('value'=>'','label'=>Util::EMTPY_SELECT_OPTION));
		foreach(static::getValores() as $dat) {
			$array[]	= array('value'=>$dat->getId(),'label'=>$dat->getDescripcion());
		}
		return json_encode($array);
	}
	
	/**
	 *
	 * @tutorial Metodo Descripcion:
	 * @version 1.0
	 * @author miguel.carmona 30/05/2014
	 */
	public static function getResultado($buscar) {
		foreach (static::getValores() as $dato) {
			if($dato->getId()==$buscar) {
				$resultado	= $dato;
				break;
			}
		}
		return $resultado;
	}
	
	public static function getResultadoDescripcion($buscar) {
	    foreach (static::getValores() as $dato) {
	        if(mb_strtoupper($dato->getDescripcion())==mb_strtoupper($buscar)) {
	            $resultado	= $dato;
	            break;
	        }
	    }
	    return $resultado;
	}
	
	/**
	 *
	 * @tutorial Metodo Descripcion:
	 * @version 1.0
	 * @author Eminson Mendoza Martinez 30/05/2014
	 * @return array bare_field_name
	 * @param return_type
	 */
	public static function getDatos() {
		return self::getValores();
	}
	
	final public function getId() {
		return $this->id;
	}
	
	final public function setId($id) {
		$this->id		= $id;
	}
	
	final public function getDescripcion() {
		return $this->descripcion;
	} 	
	
	final public function setDescripcion($descripcion) {
		$this->descripcion		= $descripcion;
	}
	
	/**
	 *
	 * @tutorial Metodo Descripcion:
	 * @version 1.0
	 * @author Miguel Carmona 16/05/2014
	 * @return AEnumerado bare_field_name
	 * @param return_type
	 */
	public static function getIndice($buscar) {
		return static::getValores()[$buscar];
	}
	
	/**
	 * @tutorial Metodo Descripcion:
	 * @version 1.0
	 * @author Miguel Carmona 16/05/2014
	 * @return string $auxiliar
	 * @param NULL
	 */
	final public function getAuxiliar() {
		return $this->auxiliar;
	}

	/**
	 * @tutorial Metodo Descripcion:
	 * @version 1.0
	 * @author Miguel Carmona 16/05/2014
	 * @return string $auxiliar
	 * @param NULL
	 */
	final public function setAuxiliar($auxiliar) {
		$this->auxiliar = $auxiliar;
	}
	
	/**
	 * @tutorial Metodo Descripcion:
	 * @version 1.0
	 * @author Miguel Carmona 16/05/2014
	 * @return array $array
	 * @param field_type
	 */
	final public function getArray() {
		return $this->array;
	}

	/**
	 * @tutorial Metodo Descripcion:
	 * @version 1.0
	 * @author Miguel Carmona 16/05/2014
	 * @return array $array
	 * @param field_type
	 */
	final public function setArray($array) {
		$this->array = $array;
	}
}
?>