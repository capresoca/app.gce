<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 4/10/2018
 * Time: 4:12 PM
 */

namespace App\Capresoca\Aseguramiento\Glosas;


use App\Capresoca\Aseguramiento\Retramite;
use App\Traits\ToolsTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GN0084 extends ProcesadorGlosaBase implements ProcesadorGlosasInterface
{

    public function procesar()
    {
        try{
            Log::info('Glosa a GN084');
            //Tomar la fecha real de afiliación que viene en la retroalimentación de la glosa, sumarle un dia
            // y volver a generar la novedad glosada para su posterior envío.
            $fecha_afiliacion_bdua = new Carbon(ToolsTrait::homologarFecha($this->columnas[1]));
            $this->afiliado->fecha_afiliacion = $fecha_afiliacion_bdua->addDays(1)->toDateString();
            $this->afiliado->save();

            new Retramite($this->regRespuesta->tramite);
            
            return true;

        }catch (\Exception $e){
            Log::error($e);
            return false;
        }


    }
}