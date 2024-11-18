<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Requests\Presupuesto\TipoRecursoRequest;
use App\Http\Resources\Presupuesto\TipoRecursoResource;
use App\Models\Presupuesto\PrTipoRecurso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Traits\EnumsTrait;

class TipoRecursoController extends Controller
{

    public function index()
    {

        if(Input::get('per_page')){
            return TipoRecursoResource::collection(
                PrTipoRecurso::pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return TipoRecursoResource::collection(PrTipoRecurso::pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(TipoRecursoRequest $request)
    {
        $tipoRecurso = PrTipoRecurso::create($request->all());
        return response()->json([
                'message' => 'El cargo de empleados fue creado con exito',
                'data' => PrTipoRecurso::where('id',$tipoRecurso->id)->first()
            ]);
    }


    public function show(PrTipoRecurso $tipoRecurso)
    {
        return new TipoRecursoResource($tipoRecurso);
    }


    public function update(TipoRecursoRequest $request, $id)
    {
        try {
        $tipoRecurso = PrTipoRecurso::find($id);
        $tipoRecurso->update($request->all());
        $tipoRecurso=$tipoRecurso->where('id',$id)->first();
        return response()->json([
                'message' => 'El cargo de empleados fue editado con exito',
                'data' => $tipoRecurso
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }
    public function findByCodigo($codigo)
    {
        $tipoRecurso = PrTipoRecurso::where('codigo',$codigo)->first();
        if($tipoRecurso){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del cargo de empleados ya se encuentra registrado',
                'data' => $tipoRecurso
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El cargo de empleados no existe'
        ],204);
    }
    public function findById($id)
    {
        $tipoRecurso = PrTipoRecurso::where('id',$id)->first();
        if($tipoRecurso){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del cargo de empleados ya se encuentra registrado',
                'data' => $tipoRecurso
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
            'situacionRecurso' => EnumsTrait::getEnumValues('pr_tipo_recursos','situacion_recurso')
        ];
        return response()->json([
            'data' => $complemen
        ]);
    }
}
