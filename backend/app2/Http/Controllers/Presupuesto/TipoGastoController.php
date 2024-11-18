<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Requests\Presupuesto\TipoGastoRequest;
use App\Http\Resources\Presupuesto\TipoGastoResource;
use App\Models\Presupuesto\PrTiposGasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Traits\EnumsTrait;

class TipoGastoController extends Controller
{

    public function index()
    {

        if(Input::get('per_page')){
            return TipoGastoResource::collection(
                PrTiposGasto::pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return TipoGastoResource::collection(PrTiposGasto::pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(TipoGastoRequest $request)
    {
        $tipoGasto = PrTiposGasto::create($request->all());
        return response()->json([
                'message' => 'El cargo de empleados fue creado con exito',
                'data' => PrTiposGasto::where('id',$tipoGasto->id)->first()
            ]);
    }


    public function show(PrTiposGasto $tipoGasto)
    {
        return new TipoGastoResource($tipoGasto);
    }


    public function update(TipoGastoRequest $request, $id)
    {
        try {
        $tipoGasto = PrTiposGasto::find($id);
        $tipoGasto->update($request->all());
        $tipoGasto=$tipoGasto->where('id',$id)->first();
        return response()->json([
                'message' => 'El cargo de empleados fue editado con exito',
                'data' => $tipoGasto
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }
    public function findByCodigo($codigo)
    {
        $tipoGasto = PrTiposGasto::where('codigo',$codigo)->first();
        if($tipoGasto){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del cargo de empleados ya se encuentra registrado',
                'data' => $tipoGasto
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El cargo de empleados no existe'
        ],204);
    }
    public function findById($id)
    {
        $tipoGasto = PrTiposGasto::where('id',$id)->first();
        if($tipoGasto){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del cargo de empleados ya se encuentra registrado',
                'data' => $tipoGasto
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
            'definicion' => EnumsTrait::getEnumValues('pr_tipos_gastos','definicion'),
            'clasificacion' => EnumsTrait::getEnumValues('pr_tipos_gastos','clasificacion')
        ];
        return response()->json([
            'data' => $complemen
        ]);
    }
}
