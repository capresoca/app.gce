<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 5/02/2019
 * Time: 3:20 PM
 */

namespace App\Capresoca\Compensacion;


use App\Compensacion\CpLiquidacione;
use App\Models\Aseguramiento\AsAfiliadoPagador;
use App\Models\Compensacion\CpFechaslimite;
use App\Traits\CarbonColombia;
use Illuminate\Support\Facades\DB;

class GenerarLiquidacionesVecidas
{
    public function __invoke(){
        $fecha_corte = CarbonColombia::today()->toDateString();

        $this->generar($fecha_corte);

    }

    public function generar($fecha_corte)
    {
        $fecha_corte = CarbonColombia::parse($fecha_corte);
        $diaHabilMes = $fecha_corte->bussinessDayOfMonth();

        if(!$diaHabilMes) return;

        $digitos = CpFechaslimite::whereDiaHabil($diaHabilMes)->pluck('hasta')->first();

        if(!$digitos) return;

        $primer_dia_pago = CpFechaslimite::min('dia_habil');

        if($diaHabilMes < $primer_dia_pago ) return;


        $periodoActual =$fecha_corte->subMonth()->format('Y-m');

        $vencidas = AsAfiliadoPagador::vencidasMesSegunUltimosDosDigitos($digitos,$periodoActual)
            ->select(
                DB::raw("as_afiliado_pagador.id as relacion_laboral_id, 
                                    '$periodoActual' as periodo, 
                                    as_afiliado_pagador.ibc as ibc")
            )->get();

        $liquidaciones = $vencidas->map(function ($vencida) use ($periodoActual){
            return [
                'ibc' => $vencida->ibc,
                'periodo' => $periodoActual,
                'relacion_laboral_id'=> $vencida->relacion_laboral_id
            ];
        });

        CpLiquidacione::insert($liquidaciones->toArray());
    }
}