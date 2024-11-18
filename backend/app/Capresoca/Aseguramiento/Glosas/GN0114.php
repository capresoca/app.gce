<?php

namespace App\Capresoca\Aseguramiento\Glosas;

use App\Models\Niif\NfCiiu;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GN0114 extends ProcesadorGlosaBase implements ProcesadorGlosasInterface {

	public function procesar() {
		try {
            Log::info('Glosa a GN0114');
            ///actualizar la base de datos de Capresoca EPS con los datos de la retroalimentación.
            /* 
                Los datos de la retroalimentacion no son claros, no se sabe que remplezar por que
            */
            $ciiu= NfCiiu::where('codigo',$this->columnas[2])->first();
            if(!is_null($ciiu)){
                $this->afiliado->nf_ciiu_id=$ciiu->id;
                return true;
            }
			return false;
		} catch (Exception $e) {
			return false;
		}
	}
}