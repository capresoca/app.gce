<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Capresoca\Compensacion\EstadoRelacionLaboral;
use App\Compensacion\CpLiquidacione;
use App\Http\Resources\Aseguramiento\AfiliadoPagadorResource;
use App\Http\Resources\Aseguramiento\AfiliadosPagadorResource;
use App\Models\Aseguramiento\AsAfiliadoPagador;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Laracsv\Export;

class AfiliadoPagadorController extends Controller
{
    public function index(){
        $query = AsAfiliadoPagador::pimp()
            ->with('afiliado','pagador','liquidaciones.aportes.usuario');


        if(Input::get('per_page')){
            return AfiliadosPagadorResource::collection(
                $query->orderBy('fecha_vinculacion','desc')->simplePaginate(Input::get('per_page'))
            );
        }
        $relaciones_laborales = $query->limit(100)->get();


        if(Input::get('csv')){
            $csv = new Export();
            $csv->build($relaciones_laborales, [
                'pagador.identificacion',
                'pagador.razon_social',
                'afiliado.tipo_identificacion',
                'afiliado.identificacion',
                'afiliado.nombre_completo',
                'afiliado.celular',
                'pagador.tercero.correo_electronico',
                'pagador.tercero.direccion',
                'pagador.tercero.telefono_fijo',
                'ibc',
            ]);

            return $csv->download();
        }

        return AfiliadoPagadorResource::collection($relaciones_laborales);

    }

    public function show(AsAfiliadoPagador $afiliadoaportante)
    {

        $afiliadoaportante->load(['afiliado.tercero','pagador.tercero','liquidaciones.aportes.planilla','liquidaciones.aportes.usuario']);

        return new AfiliadoPagadorResource($afiliadoaportante);
    }

    public function estado(AsAfiliadoPagador $afiliadoaportante)
    {
        $estado = new EstadoRelacionLaboral($afiliadoaportante);

        $estadoPeriodo = [
            'periodo_actual_mora' => $estado->periodoActualMora(),
            'periodo_consultado_mora' => $estado->periodoEnMora(Input::get('periodo')),
            'promedio_ibc' => $estado->getPromedioIbcUltimoAnio(Input::get('periodo'))
        ];

        return response()->json($estadoPeriodo,200);
    }

    public function morosos(){
        $morosos = AsAfiliadoPagador::whereHas('liquidaciones', function ($query) {
            $query->doesntHave('aportes')
            ->orWhere(function ($query){
                $query->where('dias_pagados','<',30)
                    ->where('retiro',0);
            });
        })->with('afiliado','liquidaciones.aportes')->paginate(20);

        return AfiliadoPagadorResource::collection($morosos);
    }


    public function getPeriodosVencidos(AsAfiliadoPagador $afiliadoaportante)
    {

        return $afiliadoaportante->load('liquidaciones.aportes');
        
    }


    public function liquidacionesMorosos(){

        $query_liquidaciones = DB::table('cp_liquidaciones')
            ->select('as_pagadores.identificacion','as_pagadores.razon_social','as_afiliados.tipo_identificacion as tipo_identificacion_afiliado',
                'as_afiliados.identificacion as identificacion_afiliado','as_afiliados.nombre_completo','as_afiliados.celular','cp_liquidaciones.ibc','cp_liquidaciones.dias_pagados','cp_liquidaciones.periodo')
            ->join('as_afiliado_pagador','cp_liquidaciones.relacion_laboral_id','=','as_afiliado_pagador.id')
            ->join('as_pagadores','as_afiliado_pagador.as_pagador_id','=','as_pagadores.id')
            ->join('as_afiliados','as_afiliado_pagador.as_afiliado_id','as_afiliados.id')
            ->where('cp_liquidaciones.dias_pagados','<',30)
            ->where(['cp_liquidaciones.retiro' => 0, 'cp_liquidaciones.ingreso' => 0]);

        if(Input::get('periodo')){
            $query_liquidaciones = $query_liquidaciones->where('cp_liquidaciones.periodo',Input::get('periodo'));
        }

        $liquidaciones = $query_liquidaciones->get();

        if(Input::get('csv')){
            $csv = new Export();
            $csv->build($liquidaciones, [
                'identificacion',
                'razon_social',
                'tipo_identificacion_afiliado',
                'identificacion_afiliado',
                'nombre_completo',
                'celular',
                'ibc',
                'dias_pagados',
                'periodo'
            ]);

            $csv->download();
        }else{
            return $liquidaciones->paginate();
        }

    }

}
