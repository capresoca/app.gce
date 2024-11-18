<?php

namespace App\Http\Controllers\TalentoHumano;

use App\Http\Requests\TalentoHumano\FondosRequest;
use App\Http\Resources\TalentoHumano\FondosResource;
use App\Models\TalentoHumano\ThFondo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class FondosController extends Controller
{

    public function index()
    {

        if(Input::get('per_page')){
            return FondosResource::collection(
                ThFondo::with('tercero')
                    ->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return FondosResource::collection(ThFondo::with('tercero')->pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(FondosRequest $request)
    {
        $fondos = ThFondo::create($request->all());
        return response()->json([
                'message' => 'El fondo fue creado con exito',
                'data' => ThFondo::with('tercero')->where('id',$fondos->id)->first()
            ]);
    }


    public function show(ThFondo $fondos)
    {
        return new FondosResource($fondos);
    }


    public function update(FondosRequest $request, $id)
    {
        try {
        $fondos = ThFondo::find($id);
        $fondos->update($request->all());
        $fondos=$fondos->with('tercero')->where('id',$id)->first();
        return response()->json([
                'message' => 'El fondo fue editado con exito',
                'data' => $fondos
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }
    public function findByCodigo($codigo)
    {
        $fondos = ThFondo::with('tercero')->where('codigo',$codigo)->first();
        if($fondos){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del fondo ya se encuentra registrado',
                'data' => $fondos
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El fondo no existe'
        ],204);
    }
    public function findById($id)
    {
        $fondos = ThFondo::with('tercero')->where('id',$id)->first();
        if($fondos){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del fondo ya se encuentra registrado',
                'data' => $fondos
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El fondo no existe'
        ],204);
    }
}
