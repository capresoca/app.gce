<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 4/10/2018
 * Time: 8:36 AM
 */

namespace App\Capresoca\Aseguramiento\Glosas;

use Illuminate\Support\Facades\Log;
use App\Traits\ToolsTrait;
use Illuminate\Support\Facades\DB;

class GN0161 extends ProcesadorGlosaBase implements ProcesadorGlosasInterface {

	public function procesar() {
		Log::info('Glosa a GN0161');
		// Finalizar trámite de la novedad y actualizar la base de datos de Capresoca EPS, activando al afiliado con la fecha de la retroalimentación.
		//activando usuario, base de datos id 3 de tabla as_estadosAfiliado 
		$this->afiliado->estado = 3;
		//Se remplaza la fecha de afiliacion con la fecha de la glosa. /////ATENCION existen dos fechas en la glosa, campo 2 y campo 7
		$this->afiliado->fecha_afiliacion=ToolsTrait::homologarFecha($this->columnas[2]);
		$this->afiliado->save();

		return true;
	}
}