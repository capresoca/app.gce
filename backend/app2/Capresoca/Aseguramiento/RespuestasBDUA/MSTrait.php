<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 17/09/2018
 * Time: 11:10 AM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA;


use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsAfitramite;
use App\Traits\ToolsTrait;

trait MSTrait
{
    /**
     * @param AsAfiliado $afiliado
     * @param $fecha_radicacion
     * @return mixed
     * @throws \Exception
     */
    protected function getTramite(AsAfiliado $afiliado, $fecha_radicacion)
    {
        $fecha_radicacion = ToolsTrait::homologarFecha($fecha_radicacion);

        $afitramite = AsAfitramite::
        whereHas('afiliado', function ($query) use ($afiliado){
            $query->where('id', $afiliado->id);
        })->whereHas('tramite', function ($query) use ($fecha_radicacion){
            $query->where('fecha_radicacion', $fecha_radicacion);
        })->with('tramite')->orderBy('created_at','desc')->first();

        if(!$afitramite)
            throw new \Exception('No se encontro el tramite del afiliado identificado: ' . $afiliado->identificacion . ' Radicada: ' . $fecha_radicacion);

        $tramite = $afitramite->tramite;

        return $tramite;
    }
}