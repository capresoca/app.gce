<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 4/10/2018
 * Time: 3:46 PM
 */

namespace App\Capresoca\Aseguramiento\Glosas;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GN0059 extends ProcesadorGlosaBase implements ProcesadorGlosasInterface
{

    public function procesar()
    {
        Log::info('Glosa a GN059');
        return false;
    }
}