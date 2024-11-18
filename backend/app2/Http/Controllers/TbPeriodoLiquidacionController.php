<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use App\Models\TbPeriodoLiquidacion;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class TbPeriodoLiquidacionController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            $periodoL = TbPeriodoLiquidacion::pimp()->orderBy('fecha_inicio', 'desc')->paginate(Input::get('per_page'));
            return Resource::collection($periodoL);
        }
        return Resource::collection(TbPeriodoLiquidacion::pimp()->orderBy('fecha_inicio', 'desc')->get());
    }

    public function store(Request $request)
    {
        $periodoL = new TbPeriodoLiquidacion();
        $periodoL->descripcion = $request->descripcion;
        $periodoL->fecha_inicio = $request->fecha_inicio;
        $periodoL->fecha_fin = $request->fecha_fin;
        $periodoL->estado = '1'; // 1: abierto, 2: cerrado
        $periodoL->save();
    }

    public function actualizar (Request $request, $id)
    {
        $periodoL = TbPeriodoLiquidacion::find($id);
        $periodoL->descripcion = $request->descripcion;
        $periodoL->fecha_inicio = $request->fecha_inicio;
        $periodoL->fecha_fin = $request->fecha_fin;
        $periodoL->save();
    }

    public function destroy($id)
    {
        try {
            $periodoL = TbPeriodoLiquidacion::find($id);
            $periodoL->delete();
            return response('',202);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
