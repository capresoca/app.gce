<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15/02/2019
 * Time: 3:07 PM
 */

namespace App\Jobs\Mipres;


use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class HistoricoMipres
{
    public function descargar()
    {
        $fecha = Carbon::create(2019,2,19);
        while (today()->greaterThan($fecha)){
            try {
                $fechaString = $fecha->toDateString();

                DescargarPrescripciones::dispatch($fechaString)->onQueue('archivos');

                $fecha->addDay();
            } catch (GuzzleException $e) {
                Log::error($e);
            }
        }
    }
}