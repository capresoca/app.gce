<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 4/10/2018
 * Time: 8:36 AM
 */

namespace App\Capresoca\Aseguramiento\Glosas;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GN0069 extends ProcesadorGlosaBase implements ProcesadorGlosasInterface {

	public function procesar() {
		try {
			Log::info('Glosa a GN069');
			///El sistema no puede Subsanar la glosa, se debe mostrar la glosa al usuario del módulo para que realice las correcciones de manera manual en una nueva novedad.
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}