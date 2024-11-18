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

trait S1Trait
{

    /**
     * @param AsAfiliado $afiliado
     * @throws \Exception
     */
    protected function getTramite(AsAfiliado $afiliado)
    {
        $tramite =  AsTramite::where('as_bduaarchivo_id', $this->respuesta->as_bduaarchivo_id)
            ->whereHas('traslado', function ($query) use ($afiliado){
                $query->where('as_afiliado_id', $afiliado->id);
            })->first();

        if(!$tramite){
            $afiliadoDatosRelacionados = $afiliado->with('tipo_id')->first();
            throw new \Exception('No se encontro el tramite de traslado subsidiado del afiliado: '.$afiliadoDatosRelacionados->tipo_id->abreviatura.' '.$afiliadoDatosRelacionados->identificacion.' del proceso: '.$this->respuesta->as_bduaarchivo_id);

        }
        
        return $tramite;
    }
}