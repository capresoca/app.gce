<?php

namespace App\Http\Controllers\TalentoHumano;

use App\Http\Requests\TalentoHumano\GruposEmpleadosRequest;
use App\Http\Resources\TalentoHumano\GruposEmpleadosResource;
use App\Models\TalentoHumano\ThGruposEmpleado;
use App\Models\Niif\GnTercero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class GruposEmpleadosController extends Controller
{

    public function index()
    {

        if(Input::get('per_page')){
            return GruposEmpleadosResource::collection(
                ThGruposEmpleado::pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return GruposEmpleadosResource::collection(ThGruposEmpleado::pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(GruposEmpleadosRequest $request)
    {
        $grupo = ThGruposEmpleado::create($request->all());
        return response()->json([
                'message' => 'El grupo fue creado con exito',
                'data' => ThGruposEmpleado::where('id',$grupo->id)->first()
            ]);
    }


    public function show(ThGruposEmpleado $grupo)
    {
        return new GruposEmpleadosResource($grupo);
    }


    public function update(GruposEmpleadosRequest $request, $id)
    {
        try {
        $grupo = ThGruposEmpleado::find($id);
        $grupo->update($request->all());
        $grupo=$grupo->where('id',$id)->first();
        return response()->json([
                'message' => 'El grupo fue editado con exito',
                'data' => $grupo
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }
    public function findByCodigo($codigo)
    {
        $grupo = ThGruposEmpleado::where('codigo',$codigo)->first();
        if($grupo){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del grupo ya se encuentra registrado',
                'data' => $grupo
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El grupo no existe'
        ],204);
    }
    public function findById($id)
    {
        $grupo = ThGruposEmpleado::where('id',$id)->first();
        if($grupo){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del grupo ya se encuentra registrado',
                'data' => $grupo
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El grupo no existe'
        ],204);
    }
}
