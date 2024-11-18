<?php

namespace App\Capresoca\Aseguramiento\Glosas;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GN0313 extends ProcesadorGlosaBase implements ProcesadorGlosasInterface {

	public function procesar() {
        return false;
	}
}