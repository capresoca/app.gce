<?php

namespace App\Http\Controllers\TalentoHumano;

use App\Http\Requests\TalentoHumano\ProfesionalismoRequest;
use App\Http\Resources\TalentoHumano\ProfesionalismoResource;
use App\Models\TalentoHumano\ThProfesionalismo;
use App\Models\Niif\GnTercero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class ProfesionalismoController extends Controller
{

    public function index()
    {

        if(Input::get('per_page')){
            return ProfesionalismoResource::collection(
                ThProfesionalismo::pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return ProfesionalismoResource::collection(ThProfesionalismo::pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(ProfesionalismoRequest $request)
    {
        $profesionalismo = ThProfesionalismo::create($request->all());
        return response()->json([
                'message' => 'El grado de prifesionalismo fue creado con exito',
                'data' => ThProfesionalismo::where('id',$profesionalismo->id)->first()
            ]);
    }


    public function show(ThProfesionalismo $profesionalismo)
    {
        return new ProfesionalismoResource($profesionalismo);
    }


    public function update(ProfesionalismoRequest $request, $id)
    {
        try {
        $profesionalismo = ThProfesionalismo::find($id);
        $profesionalismo->update($request->all());
        $profesionalismo=$profesionalismo->where('id',$id)->first();
        return response()->json([
                'message' => 'El grado de prifesionalismo fue editado con exito',
                'data' => $profesionalismo
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }
    public function findByCodigo($codigo)
    {
        $profesionalismo = ThProfesionalismo::where('codigo',$codigo)->first();
        if($profesionalismo){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del grado de prifesionalismo ya se encuentra registrado',
                'data' => $profesionalismo
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El grado de prifesionalismo no existe'
        ],204);
    }
    public function findById($id)
    {
        $profesionalismo = ThProfesionalismo::where('id',$id)->first();
        if($profesionalismo){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del grado de prifesionalismo ya se encuentra registrado',
                'data' => $profesionalismo
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El grado de prifesionalismo no existe'
        ],204);
    }
}
