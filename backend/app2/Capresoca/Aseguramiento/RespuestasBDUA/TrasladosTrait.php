<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 17/09/2018
 * Time: 11:10 AM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA;


use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsSoltraslado;
use Illuminate\Support\Facades\Log;

trait TrasladosTrait
{
    /**
     * @param AsAfiliado $afiliado
     * @param $fecha_traslado
     * @return mixed
     * @throws \Exception
     */
    protected function getSolicitudTraslado(AsAfiliado $afiliado, $proceso)
    {

        $solicitudTraslado = AsSoltraslado::where('as_afiliado_id',$afiliado->id)->where('as_bduaproceso_id',$proceso)->first();

        if(!$solicitudTraslado)
            throw new \Exception('No se encontro La solicitud de traslado de el  afiliado identificado: ' . $afiliado->identificacion . ' Proceso: ' . $proceso);

        return $solicitudTraslado;
    }

    protected function getSolTrasladoTramiteContributivo(AsAfiliado $afiliado, $consecutivo, $proceso,$regimen){
        $solTraslado = AsSoltraslado::where('as_afiliado_id',$afiliado->id)
            ->where('as_regimen_id',$regimen)
            ->where('as_bduaproceso_id', $proceso)
            ->whereHas('tramite', function ($query) use ($consecutivo){
                $query->where('consecutivo_reporte', $consecutivo);
            })->first();
        if (!$solTraslado) {
            throw new \Exception('No se encontro el tramite de solicitud de traslado contributivo del afiliado: '
                .$afiliado->identificacion.' Consecutivo tramite: '.$consecutivo.' Proceso: '.$proceso.' Proceso: '.$afiliado->id);
        }
        return $solTraslado;
    }

    protected function getSolTrasladoTramiteSubsidiado(AsAfiliado $afiliado, $proceso){
        $solTraslado = AsSoltraslado::where('as_afiliado_id',$afiliado->id)
            ->where('as_bduaproceso_id', $proceso)
            ->where('as_regimen_id','2')->with('tramite')->first();
        if (!$solTraslado) {
            throw new \Exception('No se encontro el tramite de solicitud de traslado Subsidiado del afiliado: '.$afiliado->identificacion);
        }
        return $solTraslado;
    }
}