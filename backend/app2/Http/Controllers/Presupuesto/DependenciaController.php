<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Requests\Presupuesto\DependenciaRequest;
use App\Http\Resources\Presupuesto\DependenciaResource;
use App\Models\Presupuesto\PrDependencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class DependenciaController extends Controller
{

    public function index()
    {

        if(Input::get('per_page')){
            return DependenciaResource::collection(
                PrDependencia::with('tercero.municipio','cencosto')
                    ->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return DependenciaResource::collection(PrDependencia::with('tercero.municipio','cencosto')->pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(DependenciaRequest $request)
    {
        $dependencia = PrDependencia::create($request->all());
        return response()->json([
                'message' => 'El cargo de empleados fue creado con exito',
                'data' => PrDependencia::with('tercero.municipio','cencosto')->where('id',$dependencia->id)->first()
            ]);
    }


    public function show(PrDependencia $dependencia)
    {
        return new DependenciaResource($dependencia);
    }


    public function update(DependenciaRequest $request, $id)
    {
        try {
        $dependencia = PrDependencia::find($id);
        $dependencia->update($request->all());
        $dependencia=$dependencia->with('tercero.municipio','cencosto')->where('id',$id)->first();
        return response()->json([
                'message' => 'El cargo de empleados fue editado con exito',
                'data' => $dependencia
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }
    public function findByCodigo($codigo)
    {
        $dependencia = PrDependencia::with('tercero.municipio','cencosto')->where('codigo',$codigo)->first();
        if($dependencia){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del cargo de empleados ya se encuentra registrado',
                'data' => $dependencia
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El cargo de empleados no existe'
        ],204);
    }
    public function findById($id)
    {
        $dependencia = PrDependencia::with('tercero.municipio','cencosto')->where('id',$id)->first();
        if($dependencia){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del cargo de empleados ya se encuentra registrado',
                'data' => $dependencia
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El cargo de empleados no existe'
        ],204);
    }
}
