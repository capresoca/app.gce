<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Requests\Inventarios\SubgruposRequest;
use App\Http\Resources\Inventarios\SubgruposResource;
use App\Models\Inventarios\InSubgrupo;
use App\Models\Niif\GnTercero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class SubgruposController extends Controller
{

    public function index()
    {

        if(Input::get('per_page')){
            return SubgruposResource::collection(
                InSubgrupo::pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return SubgruposResource::collection(InSubgrupo::pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(SubgruposRequest $request)
    {
        $subgrupo = InSubgrupo::create($request->all());
        return response()->json([
                'message' => 'El grupo fue creado con exito',
                'data' => InSubgrupo::where('id',$subgrupo->id)->first()
            ]);
    }


    public function show(InSubgrupo $subgrupo)
    {
        return new SubgruposResource($subgrupo);
    }


    public function update(SubgruposRequest $request, $id)
    {
        try {
        $subgrupo = InSubgrupo::find($id);
        $subgrupo->update($request->all());
        $subgrupo=$subgrupo->where('id',$id)->first();
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
        $subgrupo = InSubgrupo::where('codigo',$codigo)->first();
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
        $subgrupo = InSubgrupo::where('id',$id)->first();
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
