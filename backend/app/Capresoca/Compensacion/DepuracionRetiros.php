<?php


namespace App\Capresoca\Compensacion;


use App\Compensacion\CpLiquidacione;
use App\Models\Aseguramiento\AsAfiliadoPagador;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

abstract class DepuracionRetiros
{
    public static function run(){
        $relaboralesConflictos = DB::select("SELECT distinct relacion.id,
            ( SELECT COUNT(*) FROM
                as_afiliado_pagador INNER JOIN cp_liquidaciones
                ON cp_liquidaciones.relacion_laboral_id = as_afiliado_pagador.id
                where
                as_afiliado_pagador.as_afiliado_id = as_afiliados.id AND as_afiliado_pagador.id = relacion.id AND cp_liquidaciones.dias_pagados = 0
            ) AS periodos_mora ,
            (SELECT count(*) from cp_liquidaciones WHERE cp_liquidaciones.retiro = 1  and cp_liquidaciones.relacion_laboral_id = relacion.id ) as CANT_RETIROS
            FROM as_afiliados INNER JOIN as_afiliado_pagador as relacion ON relacion.as_afiliado_id = as_afiliados.id
            LEFT JOIN cp_liquidaciones ON relacion.id = cp_liquidaciones.relacion_laboral_id
            WHERE cp_liquidaciones.retiro = 1

            HAVING periodos_mora > 0 and CANT_RETIROS  > 0");

        $afectados = 0;
        foreach ($relaboralesConflictos as $conflicto){

            $relaboral = AsAfiliadoPagador::find($conflicto->id);

            if(!$relaboral) continue;

            $liquidacionesRetiro = $relaboral->liquidaciones_unorder()->whereRetiro(1)->orderBy('periodo')->get();

            foreach ( $liquidacionesRetiro as  $liquidacionRetiro) {

                $periodoRetiroCarbon = Carbon::parse($liquidacionRetiro->periodo);

                $aporteRetiro = $liquidacionRetiro->aportes()->orderBy('fecha_pago','desc')->first();

                if(!$aporteRetiro) continue;
                $fechaPagoLiquidacionRetiro = Carbon::parse($aporteRetiro->fecha_pago);
                $periodoCorte = $liquidacionRetiro->periodo;

                $periodoRetiroCarbonMasUnMes = clone $periodoRetiroCarbon;
                $periodoRetiroCarbonMasUnMes->addMonth()->lastOfMonth();


                if($fechaPagoLiquidacionRetiro->greaterThan($periodoRetiroCarbonMasUnMes) ) {
                    $periodoCorte = $fechaPagoLiquidacionRetiro->format('Y-m');
                }

                $liquidacionesPostRetiro = $relaboral->liquidaciones_unorder()->where('periodo', '>', $periodoCorte)->orderBy('periodo')
                    ->orderBy('periodo')->get();

                $estadoRelaboral = 'Inactivo';

                foreach ($liquidacionesPostRetiro as $liquidacionPostRetiro){
                    if($liquidacionPostRetiro->ingreso || $liquidacionPostRetiro->dias_pagados > 0){
                        $estadoRelaboral = 'Activo';
                        break;
                    };

                    $liquidacionPostRetiro->delete();
                    $afectados ++;
                }

                $relaboral->estado = $estadoRelaboral;
                $relaboral->save();
            }


        }

        return $afectados;
    }
}