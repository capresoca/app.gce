<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 27/02/2019
 * Time: 5:58 PM
 */

namespace App\Capresoca\Contratos;


use App\Models\ContratacionEstatal\CeProconminuta;
use App\Models\RedServicios\RsPlanescobertura;

abstract class MoveDataFromRsContratosToCeProconminuta
{


    public static function mover()
    {
        $contratos = RsPlanescobertura::all();
        foreach ($contratos as $contrato) {
            //return $contrato->servicios_contratados->toArray();
            $minuta = CeProconminuta::firstOrCreate([
                'numero_contrato' => $contrato->numero_contrato
            ],$contrato->toArray());

            $contrato->ce_proconminuta_id = $minuta->id;

            if($contrato->subsidiado == 1 && $contrato->contributivo == 1){
                $contrato->regimen_atendido = 2;
                $contratoContributivo = RsPlanescobertura::create($contrato->toArray());

                if($contrato->servicios_contratados->count()){
                    $contratoContributivo->servicios_contratados()->createMany($contrato->servicios_contratados->map(function ($item) {
                        return [
                            'porcentaje_afiliados' => $item['porcentaje_afiliados'],
                            'rs_servicio_id' => $item['rs_servicio_id']

                        ];
                    })->toArray());

                }

                $contratoContributivo->regimen_atendido = 1;
                $contratoContributivo->nombre = $minuta->numero_contrato.' '.$minuta->tipo.' Contributivo';
                $contratoContributivo->save();
            }else if($contrato->contributivo == 1) {
                $contrato->regimen_atendido = 1;
            }else{
                $contrato->regimen_atendido = 2;
            }
            $contrato->nombre = $contrato->numero_contrato.' '.$contrato->tipo. ' Subsidiado';
            $contrato->save();

        }
    }
}