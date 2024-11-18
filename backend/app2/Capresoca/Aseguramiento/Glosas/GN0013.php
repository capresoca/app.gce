<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 4/10/2018
 * Time: 8:36 AM
 */

namespace App\Capresoca\Aseguramiento\Glosas;

use Illuminate\Support\Facades\Log;

class GN0013 extends ProcesadorGlosaBase implements ProcesadorGlosasInterface
{

    public function procesar()
    {
        Log::info('Glosa a GN013');
        return false;
    }
}