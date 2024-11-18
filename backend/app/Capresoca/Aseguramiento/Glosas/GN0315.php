<?php

namespace App\Capresoca\Aseguramiento\Glosas;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Traits\ToolsTrait;

class GN0315 extends ProcesadorGlosaBase implements ProcesadorGlosasInterface {

	public function procesar() {
		try {
            Log::info('Glosa a GN0315');
            $fechaString='';
            $fechaSolicitud = new Carbon(ToolsTrait::homologarFecha($this->columnas[0]));
            if($fechaSolicitud->day <= 5){
                // Si la solicitud se realizó entre los 5 primeros días calendario, extender la fecha factible del traslado hasta por un me y reenviar la novedad.
                $fechaString = $fechaSolicitud->addMonth()->toDateString();
            }else{
                // Si la solicitud se realizó después de los 5 primeros días calendario, extender la fecha factible de traslado hasta por dos meses y reenviar la novedad.
                $fechaString = $fechaSolicitud->addMonths(2)->toDateString();
            }
            /* 
                Cual campo de la base de datos hay que remplazar la fecha y el tipo de tramite se cambia o se verifica que es en traslado?
                REENVIAR, crear un registro en bdua_    acciones?                
                if($this->afiliado->regimen->nombre=='Contributivo'){
                    $this->regRespuesta->tramite->tipo_tramite='Traslado Contributivo';
                }
                if($this->afiliado->regimen->nombre=='Subsidiado'){
                    $this->regRespuesta->tramite->tipo_tramite='Traslado Subsidiado';
                }
                $this->regRespuesta->tramite->fecha_radicacion=$fechaString;
            */
            if($fechaString!=''){
                $this->crearRetramite();
                $this->corregida();
                return true;
            }
            return false;

		} catch (\Exception $e) {
			Log::error($e);
			return false;
		}

	}
}