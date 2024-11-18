<?php


namespace App\Capresoca\Compensacion;


use App\Compensacion\CpAporte;
use App\Compensacion\CpLiquidacione;
use App\Models\Aseguramiento\AsAfiliadoPagador;
use Illuminate\Support\Facades\DB;

class UnificacionAportesPagador
{
    //Tocar
    public static function touch(){
        $datosIniciales = DB::select("SELECT 
                a.identificacion as pagador_fail_doc, 
                a.id as pagador_fail,
                x.as_afiliado_id,
                x.id as relacion_fail,razon_social,
                (SELECT concat_ws(' ',b.id) 
	                from as_pagadores as b 
                    where b.identificacion = SUBSTR(a.identificacion,1, LENGTH(a.identificacion)-1) 
                ) as pagador_ok,
                (SELECT concat_ws(' ',d.id) 
	                from as_pagadores as c INNER JOIN as_afiliado_pagador as d ON c.id = d.as_pagador_id
	                where c.identificacion = SUBSTR(a.identificacion,1, LENGTH(a.identificacion)-1) 
                    AND d.as_afiliado_id = x.as_afiliado_id
                ) as relacion_ok
                FROM as_pagadores as a 
                INNER JOIN as_afiliado_pagador as x ON a.id = x.as_pagador_id
                HAVING  pagador_ok IS NOT NULL  AND relacion_ok is not null
                ORDER BY a.identificacion,x.as_afiliado_id");

        DB::beginTransaction();
        $aporteMaloBueno = 0;
        $liquidacionBorrada = 0;
        $liquidacionMalabuena = 0;
        $relacionesBorradas = 0;
        $relacionesInactivadas = 0;
        try{

            foreach ($datosIniciales as $item){
                $liquidacionesOk = CpLiquidacione::where('relacion_laboral_id', $item->relacion_ok)->get();
                foreach ($liquidacionesOk as $liquidacion){
                    $liquidacionFail = CpLiquidacione::where('relacion_laboral_id', $item->relacion_fail)->where('periodo', $liquidacion->periodo)->first();
                    // dd($liquidacionFail);
                    if($liquidacionFail != null){
                        $aportesPerdidos = CpAporte::where('liquidacion_id', $liquidacionFail->id)->get();
                        $diasPagados = 0;
                        foreach ($aportesPerdidos as $aporte){
                            $aporte->touch();
                            //$aporte->liquidacion_id = $liquidacion->id;
                            //$aporte->save();
                            //$diasPagados += $aporte->dias;
                            $aporteMaloBueno++;
                        }
                        //$liquidacion->dias_pagados = $liquidacion->dias_pagados + $diasPagados;
                        //$liquidacion->save();
                        $liquidacion->touch();
                        //$liquidacionFail->delete();
                        $liquidacionFail->touch();
                        $liquidacionBorrada++;
                    }
                }

                $liquidacionesFailPendientes = CpLiquidacione::where('relacion_laboral_id', $item->relacion_fail)->get();
                foreach ($liquidacionesFailPendientes as $liquidacion) {
                    if(CpLiquidacione::where('periodo', $liquidacion->periodo)->where('relacion_laboral_id',$item->relacion_ok)->count() == 0){
                        if($liquidacion->dias_pagados > 0){
                            //$liquidacion->relacion_laboral_id = $item->relacion_ok;
                            //$liquidacion->save();
                            $liquidacion->touch();
                            $liquidacionMalabuena++;
                        }else{
                            //$liquidacion->delete();
                            $liquidacion->touch();
                            $liquidacionBorrada++;
                        }
                    }

                }
                $relacionFail = AsAfiliadoPagador::find($item->relacion_fail);
                if($relacionFail->liquidaciones->count() > 0){
                    //$relacionFail->estado = 'Inactivo';
                    //$relacionFail->save();
                    $relacionFail->touch();
                    $relacionesInactivadas++;
                }else{
                    //$relacionFail->delete();
                    $relacionFail->touch();
                    $relacionesBorradas++;
                }



            }
            DB::commit();
            return [
                'aportes_a_cambiar' => $aporteMaloBueno,
                'liquidaciones_a_borradas' => $liquidacionBorrada,
                'liquidaciones_mala_a_buena' => $liquidacionMalabuena,
                'relaciones_a_inactivadar' => $relacionesInactivadas,
                'relaciones_a_borrar' => $relacionesBorradas
            ];
        }catch (\Exception $exception){
            DB::rollBack();
            return $exception;
        }
    }

    //Procesar
    public static function  run (){
        $datosIniciales = DB::select("SELECT 
                a.identificacion as pagador_fail_doc, 
                a.id as pagador_fail,
                x.as_afiliado_id,
                x.id as relacion_fail,razon_social,
                (SELECT concat_ws(' ',b.id) 
	                from as_pagadores as b 
                    where b.identificacion = SUBSTR(a.identificacion,1, LENGTH(a.identificacion)-1) limit 1
                ) as pagador_ok,
                (SELECT concat_ws(' ',d.id) 
	                from as_pagadores as c INNER JOIN as_afiliado_pagador as d ON c.id = d.as_pagador_id
	                where c.identificacion = SUBSTR(a.identificacion,1, LENGTH(a.identificacion)-1) 
                    AND d.as_afiliado_id = x.as_afiliado_id limit 1
                ) as relacion_ok
                FROM as_pagadores as a 
                INNER JOIN as_afiliado_pagador as x ON a.id = x.as_pagador_id
                HAVING  pagador_ok IS NOT NULL  AND relacion_ok is not null
                ORDER BY a.identificacion,x.as_afiliado_id");

        DB::beginTransaction();
        $aporteMaloBueno = 0;
        $liquidacionBorrada = 0;
        $liquidacionMalabuena = 0;
        $relacionesBorradas = 0;
        $relacionesInactivadas = 0;
        try{

            foreach ($datosIniciales as $item){
                $liquidacionesOk = CpLiquidacione::where('relacion_laboral_id', $item->relacion_ok)->get();
                foreach ($liquidacionesOk as $liquidacion){
                    $liquidacionFail = CpLiquidacione::where('relacion_laboral_id', $item->relacion_fail)->where('periodo', $liquidacion->periodo)->first();
                    // dd($liquidacionFail);
                    if($liquidacionFail != null){
                        $aportesPerdidos = CpAporte::where('liquidacion_id', $liquidacionFail->id)->get();
                        $diasPagados = 0;
                        $retiro = false;
                        $ingreso = false;
                        foreach ($aportesPerdidos as $aporte){
                            $aporte->liquidacion_id = $liquidacion->id;
                            $aporte->save();
                            $diasPagados += $aporte->dias;
                            $aporteMaloBueno++;
                            $retiro = $retiro || $aporte->retiro == 1;
                            $ingreso = $ingreso || $aporte->ingreso == 1;
                        }
                        $liquidacion->dias_pagados = $liquidacion->dias_pagados + $diasPagados;
                        if($retiro){
                            $liquidacion->retiro = 1;
                        }
                        if($ingreso){
                            $liquidacion->ingreso = 1;
                        }
                        $liquidacion->save();

                        $liquidacionFail->delete();
                        $liquidacionBorrada++;
                    }
                }

                $liquidacionesFailPendientes = CpLiquidacione::where('relacion_laboral_id', $item->relacion_fail)->get();
                foreach ($liquidacionesFailPendientes as $liquidacion) {
                    if(CpLiquidacione::where('periodo', $liquidacion->periodo)->where('relacion_laboral_id',$item->relacion_ok)->count() == 0){
                        if($liquidacion->dias_pagados > 0){
                            $liquidacion->relacion_laboral_id = $item->relacion_ok;
                            $liquidacion->save();
                            $liquidacionMalabuena++;
                        }else{
                            $liquidacion->delete();
                            $liquidacionBorrada++;
                        }
                    }

                }
                $relacionFail = AsAfiliadoPagador::find($item->relacion_fail);
                if($relacionFail->liquidaciones->count() > 0){
                    $relacionFail->estado = 'Inactivo';
                    $relacionFail->save();
                    $relacionesInactivadas++;
                }else{
                    $relacionFail->delete();
                    $relacionesBorradas++;
                }



            }
            DB::commit();
            return [
                'aportes_cambiados' => $aporteMaloBueno,
                'liquidaciones_borradas' => $liquidacionBorrada,
                'liquidaciones_mala_buena' => $liquidacionMalabuena,
                'relaciones_inactivadas' => $relacionesInactivadas,
                'relaciones_borradas' => $relacionesBorradas
            ];
        }catch (\Exception $exception){
            DB::rollBack();
            return $exception;
        }
    }

}