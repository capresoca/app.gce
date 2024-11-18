<?php


namespace App\Capresoca\Compensacion;


use App\Compensacion\CpAporte;
use App\Compensacion\CpLiquidacione;
use App\Models\Aseguramiento\AsAfiliadoPagador;
use Illuminate\Support\Facades\DB;

abstract class DepurarAportesDigitoVerificacion
{
    /**
     *
     */
    public static function run()
    {
        $aportesPerdidos = DB::select("select 
                as_afiliados.identificacion identifiacion_afiliado,
                p1.identificacion 			identificacion_mala, 
                p5.identificacion 			identificacion_buena, 
                p1.razon_social	 			razonsocial_mala, 
                p5.razon_social	 			razonsocial_buena, 
                p2.id 						relaboral_mala, 
                p6.id 						relaboral_buena,
                p3.id 						liquidacion_mala, 
                p7.id 						liquidacion_buena, 
                p3.periodo 					periodo_mala, 
                p7.periodo 					periodo_buena, 
                p4.id 						aporte_perdido, 
                p4.dias 					dias_malo,
                p4.retiro					retiro_malo,
                p4.ingreso					ingreso_malo, 
                p8.id 						aporte_bueno, 
                p8.dias 					dias_bueno, 
                p4.fecha_pago 				fecha_mal, 
                p8.fecha_pago 				fecha_bien
        
            from 
                    (as_pagadores as p1 join as_afiliado_pagador as p2 on p2.as_pagador_id = p1.id 
                    join cp_liquidaciones as p3 on p3.relacion_laboral_id = p2.id
                    join cp_aportes as p4 on p4.liquidacion_id = p3.id
                    join as_afiliados on p2.as_afiliado_id = as_afiliados.id)
            join 
                    (as_pagadores as p5 join as_afiliado_pagador as p6 on p6.as_pagador_id = p5.id 
                    join cp_liquidaciones as p7 on p7.relacion_laboral_id = p6.id
                    left join cp_aportes as p8 on p8.liquidacion_id = p7.id) 
            on 
                p5.identificacion = substr(p1.identificacion,1,length(p1.identificacion)-1) 
                and p2.as_afiliado_id = p6.as_afiliado_id 
            where 
                p3.periodo = p7.periodo and p8.id is null limit 1");

        $relaboral_mala = $aportesPerdidos[0]->relaboral_mala;
        $liquidacion_mala = $aportesPerdidos[0]->liquidacion_mala;

        foreach ($aportesPerdidos as $aportePerdido) {
            if($relaboral_mala != $aportePerdido->relaboral_mala){

                $relacion = AsAfiliadoPagador::find($relaboral_mala);
                try{

                    $relacion->delete();
                }catch (\Exception $e){}

                $relaboral_mala = $aportePerdido->relaboral_mala;
            }

            if($liquidacion_mala != $aportePerdido->liquidacion_mala){
                $liquidacionMala = CpLiquidacione::find($aportePerdido->liquidacion_mala);
                try{
                    if($liquidacionMala){
                        $liquidacionMala->delete();
                    }
                }catch (\Exception $e){}
                $liquidacion_mala = $aportePerdido->liquidacion_mala;
            }

            $aporteRecuperado = CpAporte::find($aportePerdido->aporte_perdido);

            $liquidacionBuena = CpLiquidacione::find($aportePerdido->liquidacion_buena);

            $liquidacionBuena->aportes()->create($aporteRecuperado->toArray());
            $liquidacionBuena->dias_pagados += $aporteRecuperado->dias;
            $liquidacionBuena->save();

        }
    }
}