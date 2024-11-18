<?php

namespace App\Http\Controllers\TalentoHumano;

use App\Http\Requests\TalentoHumano\SubgruposEmpleadosRequest;
use App\Http\Resources\TalentoHumano\SubgruposEmpleadosResource;
use App\Models\TalentoHumano\ThSubgruposEmpleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class SubgruposEmpleadosController extends Controller
{

    public function index()
    {

        if(Input::get('per_page')){
            return SubgruposEmpleadosResource::collection(
                ThSubgruposEmpleado::with('cencosto')
                	->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return SubgruposEmpleadosResource::collection(ThSubgruposEmpleado::with('cencosto')->pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(SubgruposEmpleadosRequest $request)
    {
        $subgrupo = ThSubgruposEmpleado::create($request->all());
        return response()->json([
                'message' => 'El subgrupo fue creado con exito',
                'data' => ThSubgruposEmpleado::with('cencosto')->where('id',$subgrupo->id)->first()
            ]);
    }


    public function show(ThSubgruposEmpleado $subgrupo)
    {
        return new SubgruposEmpleadosResource($subgrupo);
    }


    public function update(SubgruposEmpleadosRequest $request, $id)
    {
        try {
        $subgrupo = ThSubgruposEmpleado::find($id);
        $subgrupo->update($request->all());
        $subgrupo=$subgrupo->with('cencosto')->where('id',$id)->first();
        return response()->json([
                'message' => 'El subgrupo fue editado con exito',
                'data' => $subgrupo
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }
    public function findByCodigo($codigo)
    {
        $subgrupo = ThSubgruposEmpleado::with('cencosto')->where('codigo',$codigo)->first();
        if($subgrupo){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del subgrupo ya se encuentra registrado',
                'data' => $subgrupo
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El subgrupo no existe'
        ],204);
    }
    public function findById($id)
    {
        $subgrupo = ThSubgruposEmpleado::with('cencosto')->where('id',$id)->first();
        if($subgrupo){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del subgrupo ya se encuentra registrado',
                'data' => $subgrupo
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El subgrupo no existe'
        ],204);
    }
}
