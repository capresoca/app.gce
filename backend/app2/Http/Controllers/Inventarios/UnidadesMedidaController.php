<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Requests\Inventarios\UnidadesMedidaRequest;
use App\Http\Resources\Inventarios\UnidadesMedidaResource;
use App\Models\Inventarios\InUnidadesMedida;
use App\Models\Niif\GnTercero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class UnidadesMedidaController extends Controller
{

    public function index()
    {

        if(Input::get('per_page')){
            return UnidadesMedidaResource::collection(
                InUnidadesMedida::pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return UnidadesMedidaResource::collection(InUnidadesMedida::pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(UnidadesMedidaRequest $request)
    {
        $unidadMedida = InUnidadesMedida::create($request->all());
        return response()->json([
                'message' => 'El unidad de medida fue creado con exito',
                'data' => InUnidadesMedida::where('id',$unidadMedida->id)->first()
            ]);
    }


    public function show(InUnidadesMedida $unidadMedida)
    {
        return new UnidadesMedidaResource($unidadMedida);
    }


    public function update(UnidadesMedidaRequest $request, $id)
    {
        try {
        $unidadMedida = InUnidadesMedida::find($id);
        $unidadMedida->update($request->all());
        $unidadMedida=$unidadMedida->where('id',$id)->first();
        return response()->json([
                'message' => 'El unidad de medida fue editado con exito',
                'data' => $unidadMedida
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }
    public function findByCodigo($codigo)
    {
        $unidadMedida = InUnidadesMedida::where('codigo',$codigo)->first();
        if($unidadMedida){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del unidad de medida ya se encuentra registrado',
                'data' => $unidadMedida
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El unidad de medida no existe'
        ],204);
    }
    public function findById($id)
    {
        $unidadMedida = InUnidadesMedida::where('id',$id)->first();
        if($unidadMedida){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del unidad de medida ya se encuentra registrado',
                'data' => $unidadMedida
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El unidad de medida no existe'
        ],204);
    }
}
