<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Requests\Presupuesto\TipoIngresoRequest;
use App\Http\Resources\Presupuesto\TipoIngresoResource;
use App\Models\Presupuesto\PrTipoIngreso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Traits\EnumsTrait;

class TipoIngresoController extends Controller
{

    public function index()
    {

        if(Input::get('per_page')){
            return TipoIngresoResource::collection(
                PrTipoIngreso::pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return TipoIngresoResource::collection(PrTipoIngreso::pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(TipoIngresoRequest $request)
    {
        $tipoIngresos = PrTipoIngreso::create($request->all());
        return response()->json([
                'message' => 'El cargo de empleados fue creado con exito',
                'data' => PrTipoIngreso::where('id',$tipoIngresos->id)->first()
            ]);
    }


    public function show(PrTipoIngreso $tipoIngresos)
    {
        return new TipoIngresoResource($tipoIngresos);
    }


    public function update(TipoIngresoRequest $request, $id)
    {
        try {
        $tipoIngresos = PrTipoIngreso::find($id);
        $tipoIngresos->update($request->all());
        $tipoIngresos=$tipoIngresos->where('id',$id)->first();
        return response()->json([
                'message' => 'El cargo de empleados fue editado con exito',
                'data' => $tipoIngresos
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }
    public function findByCodigo($codigo)
    {
        $tipoIngresos = PrTipoIngreso::where('codigo',$codigo)->first();
        if($tipoIngresos){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del cargo de empleados ya se encuentra registrado',
                'data' => $tipoIngresos
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El cargo de empleados no existe'
        ],204);
    }
    public function findById($id)
    {
        $tipoIngresos = PrTipoIngreso::where('id',$id)->first();
        if($tipoIngresos){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del cargo de empleados ya se encuentra registrado',
                'data' => $tipoIngresos
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El cargo de empleados no existe'
        ],204);
    }
    function complementos()
    {
        $complemen = [
            'definicion' => EnumsTrait::getEnumValues('pr_tipo_ingresos','definicion')
        ];
        return response()->json([
            'data' => $complemen
        ]);
    }
}
