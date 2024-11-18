<?php

namespace App\Capresoca\Aseguramiento\Glosas;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GN0028 extends ProcesadorGlosaBase implements ProcesadorGlosasInterface {

	public function procesar() {
		Log::info('Glosa a GN028');
        return false;
	}
}