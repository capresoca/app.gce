<?php

namespace App\Http\Controllers\TalentoHumano;

use App\Http\Requests\TalentoHumano\RetirosRequest;
use App\Http\Resources\TalentoHumano\RetirosResource;
use App\Models\TalentoHumano\ThRetiro;
use App\Models\Niif\GnTercero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class RetirosController extends Controller
{

    public function index()
    {

        if(Input::get('per_page')){
            return RetirosResource::collection(
                ThRetiro::pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return RetirosResource::collection(ThRetiro::pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(RetirosRequest $request)
    {
        $retiro = ThRetiro::create($request->all());
        return response()->json([
                'message' => 'El motivo de retiro fue creado con exito',
                'data' => ThRetiro::where('id',$retiro->id)->first()
            ]);
    }


    public function show(ThRetiro $retiro)
    {
        return new RetirosResource($retiro);
    }


    public function update(RetirosRequest $request, $id)
    {
        try {
        $retiro = ThRetiro::find($id);
        $retiro->update($request->all());
        $retiro=$retiro->where('id',$id)->first();
        return response()->json([
                'message' => 'El motivo de retiro fue editado con exito',
                'data' => $retiro
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }
    public function findByCodigo($codigo)
    {
        $retiro = ThRetiro::where('codigo',$codigo)->first();
        if($retiro){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del motivo de retiro ya se encuentra registrado',
                'data' => $retiro
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El motivo de retiro no existe'
        ],204);
    }
    public function findById($id)
    {
        $retiro = ThRetiro::where('id',$id)->first();
        if($retiro){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del motivo de retiro ya se encuentra registrado',
                'data' => $retiro
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El motivo de retiro no existe'
        ],204);
    }
}
