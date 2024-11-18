<?php

namespace App\Http\Controllers\TalentoHumano;

use App\Http\Requests\TalentoHumano\CargosEmpleadosRequest;
use App\Http\Resources\TalentoHumano\CargosEmpleadosResource;
use App\Models\TalentoHumano\ThCargosEmpleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class CargosEmpleadosController extends Controller
{

    public function index()
    {

        if(Input::get('per_page')){
            return CargosEmpleadosResource::collection(
                ThCargosEmpleado::with('nivel')
                    ->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return CargosEmpleadosResource::collection(ThCargosEmpleado::with('nivel')->pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(CargosEmpleadosRequest $request)
    {
        $cargo = ThCargosEmpleado::create($request->all());
        return response()->json([
                'message' => 'El cargo de empleados fue creado con exito',
                'data' => ThCargosEmpleado::with('nivel')->where('id',$cargo->id)->first()
            ]);
    }


    public function show(ThCargosEmpleado $cargo)
    {
        return new CargosEmpleadosResource($cargo);
    }


    public function update(CargosEmpleadosRequest $request, $id)
    {
        try {
        $cargo = ThCargosEmpleado::find($id);
        $cargo->update($request->all());
        $cargo=$cargo->with('nivel')->where('id',$id)->first();
        return response()->json([
                'message' => 'El cargo de empleados fue editado con exito',
                'data' => $cargo
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }
    public function findByCodigo($codigo)
    {
        $cargo = ThCargosEmpleado::with('nivel')->where('codigo',$codigo)->first();
        if($cargo){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del cargo de empleados ya se encuentra registrado',
                'data' => $cargo
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El cargo de empleados no existe'
        ],204);
    }
    public function findById($id)
    {
        $cargo = ThCargosEmpleado::with('nivel')->where('id',$id)->first();
        if($cargo){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del cargo de empleados ya se encuentra registrado',
                'data' => $cargo
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El cargo de empleados no existe'
        ],204);
    }
}
