<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/10/2018
 * Time: 2:32 PM
 */

namespace App\Capresoca\Aseguramiento\Glosas;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GN0258 extends ProcesadorGlosaBase implements ProcesadorGlosasInterface
{

    public function procesar()
    {
		Log::info('Glosa a GN0258');
        return false;
    }
}