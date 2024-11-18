<?php

namespace App\Http\Controllers\TalentoHumano;

use App\Http\Requests\TalentoHumano\NivelesCargoRequest;
use App\Http\Resources\TalentoHumano\NivelesCargoResource;
use App\Models\TalentoHumano\ThNivelesCargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class NivelesCargoController extends Controller
{

    public function index()
    {

        if(Input::get('per_page')){
            return NivelesCargoResource::collection(
                ThNivelesCargo::pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return NivelesCargoResource::collection(ThNivelesCargo::pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(NivelesCargoRequest $request)
    {
        $nivel = ThNivelesCargo::create($request->all());
        return response()->json([
                'message' => 'El nivel cargo fue creado con exito',
                'data' => ThNivelesCargo::where('id',$nivel->id)->first()
            ]);
    }


    public function show(ThNivelesCargo $nivel)
    {
        return new NivelesCargoResource($nivel);
    }


    public function update(NivelesCargoRequest $request, $id)
    {
        try {
        $nivel = ThNivelesCargo::find($id);
        $nivel->update($request->all());
        $nivel=$nivel->where('id',$id)->first();
        return response()->json([
                'message' => 'El nivel cargo fue editado con exito',
                'data' => $nivel
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }
    public function findByCodigo($codigo)
    {
        $nivel = ThNivelesCargo::where('codigo',$codigo)->first();
        if($nivel){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del nivel cargo ya se encuentra registrado',
                'data' => $nivel
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El nivel cargo no existe'
        ],204);
    }
    public function findById($id)
    {
        $nivel = ThNivelesCargo::where('id',$id)->first();
        if($nivel){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del nivel cargo ya se encuentra registrado',
                'data' => $nivel
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El nivel cargo no existe'
        ],204);
    }
}
