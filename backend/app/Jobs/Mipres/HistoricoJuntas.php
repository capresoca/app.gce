<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21/03/2019
 * Time: 9:15 AM
 */

namespace App\Jobs\Mipres;


use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class HistoricoJuntas
{
    public function descargar($desde = null)
    {
        if(!$desde){
            $desde = today()->subMonth()->toDateString();
        }
        $fecha = Carbon::parse($desde);

        while (today()->greaterThan($fecha)){
            try {

                $j = new JuntasMedicasXFecha($fecha->toDateString(),'Subsidiado');
                $j->store();

                $jc = new JuntasMedicasXFecha($fecha->toDateString(),'Contributivo');
                $jc->store();

                $fecha->addDay();
            } catch (GuzzleException $e) {
                Log::error($e);

            }
        }
    }
}