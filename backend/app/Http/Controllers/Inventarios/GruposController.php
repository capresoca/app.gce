<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Requests\Inventarios\GruposRequest;
use App\Http\Resources\Inventarios\GruposResource;
use App\Models\Inventarios\InGrupo;
use App\Models\Niif\GnTercero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class GruposController extends Controller
{

    public function index()
    {

        if(Input::get('per_page')){
            return GruposResource::collection(
                InGrupo::with('ingresos','gastos','venta','rtfencompras','rtfparacompras','rubro')
                    ->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return GruposResource::collection(InGrupo::with('ingresos','gastos','venta','rtfencompras','rtfparacompras','rubro')->pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(GruposRequest $request)
    {
        $grupo = InGrupo::create($request->all());
        return response()->json([
                'message' => 'El grupo fue creado con exito',
                'data' => InGrupo::where('id',$grupo->id)->with('ingresos','gastos','venta','rtfencompras','rtfparacompras','rubro')->first()
            ]);
    }


    public function show(InGrupo $grupo)
    {
        return new GruposResource($grupo);
    }


    public function update(GruposRequest $request, $id)
    {
        try {
        $grupo = InGrupo::find($id);
        $grupo->update($request->all());
        $grupo=$grupo->where('id',$id)->with('ingresos','gastos','venta','rtfencompras','rtfparacompras','rubro')->first();
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
        $grupo = InGrupo::where('codigo',$codigo)->with('ingresos','gastos','venta','rtfencompras','rtfparacompras','rubro')->first();
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
        $grupo = InGrupo::where('id',$id)->with('ingresos','gastos','venta','rtfencompras','rtfparacompras','rubro')->first();
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
