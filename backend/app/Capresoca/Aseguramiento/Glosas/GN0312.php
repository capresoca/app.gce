<?php

namespace App\Capresoca\Aseguramiento\Glosas;

use Illuminate\Support\Facades\Log;

class GN0312 extends ProcesadorGlosaBase implements ProcesadorGlosasInterface {

	public function procesar() {
		try {
			Log::info('Glosa a GN0312');

			// El nuevo sistema no puede Subsanar la glosa, validar al momento de realizar las novedades que la fecha no pueda ser inferior a 12 meses.

			// Se puede validar en modelo
			return false;

		} catch (\Exception $e) {
			Log::error($e);
			return false;
		}

	}
}