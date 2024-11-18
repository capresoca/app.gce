<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/11/2018
 * Time: 3:13 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA;


use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsTramite;
use Illuminate\Support\Facades\Log;

trait S1Trait
{

    /**
     * @param AsAfiliado $afiliado
     * @throws \Exception
     */
    protected function getTramite(AsAfiliado $afiliado)
    {
        //where('as_bduaarchivo_id', $this->respuesta->as_bduaarchivo_id)
        $tramite =  AsTramite::whereHas('traslado', function ($query) use ($afiliado){
            $query->where('as_afiliado_id', $afiliado->id)->whereHas('afiliado', function ($query2) {
                $query2->where('estado_traslado','S1');
            });
            })->first();
            Log::info('Tramite Consulta: ',array($afiliado));
        if(!$tramite){
            $afiliadoDatosRelacionados = $afiliado->with('tipo_id')->first();
            throw new \Exception('No se encontro el tramite de traslado subsidiado del afiliado: '.$afiliadoDatosRelacionados->tipo_id->abreviatura.' '.$afiliadoDatosRelacionados->identificacion.' del proceso: '.$this->respuesta->as_bduaarchivo_id);

        }
        
        return $tramite;
    }
    
    protected function getTramiteS3(AsAfiliado $afiliado)
    {
        //where('as_bduaarchivo_id', $this->respuesta->as_bduaarchivo_id)
        $tramite =  AsTramite::whereHas('traslado', function ($query) use ($afiliado){
            $query->where('as_afiliado_id', $afiliado->id)->whereHas('afiliado', function ($query2) {
                $query2->where('estado_traslado','S3');
            });
        })->first();
        Log::info('Tramite Consulta: ',array($afiliado));
        if(!$tramite){
            $afiliadoDatosRelacionados = $afiliado->with('tipo_id')->first();
            throw new \Exception('No se encontro el tramite de traslado subsidiado del afiliado: '.$afiliadoDatosRelacionados->tipo_id->abreviatura.' '.$afiliadoDatosRelacionados->identificacion.' del proceso: '.$this->respuesta->as_bduaarchivo_id);
            
        }
        
        return $tramite;
    }
    
    protected function getTramiteR3(AsAfiliado $afiliado)
    {
        //where('as_bduaarchivo_id', $this->respuesta->as_bduaarchivo_id)
        $tramite =  AsTramite::whereHas('traslado', function ($query) use ($afiliado){
            $query->where('as_afiliado_id', $afiliado->id)->whereHas('afiliado', function ($query2) {
                $query2->where('estado_traslado','R3');
            });
        })->first();
        Log::info('Tramite Consulta: ',array($afiliado));
        if(!$tramite){
            $afiliadoDatosRelacionados = $afiliado->with('tipo_id')->first();
            throw new \Exception('No se encontro el tramite de traslado subsidiado del afiliado: '.$afiliadoDatosRelacionados->tipo_id->abreviatura.' '.$afiliadoDatosRelacionados->identificacion.' del proceso: '.$this->respuesta->as_bduaarchivo_id);
            
        }
        
        return $tramite;
    }
}