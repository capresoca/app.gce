<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Requests\Inventarios\AlmacenRequest;
use App\Http\Resources\Inventarios\AlmacenResource;
use App\Models\Inventarios\InAlmacene;
use App\Models\Niif\GnTercero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class AlmacenController extends Controller
{

    public function index()
    {

        if(Input::get('per_page')){
            return AlmacenResource::collection(
                InAlmacene::pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return AlmacenResource::collection(InAlmacene::pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(AlmacenRequest $request)
    {
        $almacen = InAlmacene::create($request->all());
        return response()->json([
                'message' => 'El almacen fue creado con exito',
                'data' => InAlmacene::where('id',$almacen->id)->first()
            ]);
    }


    public function show(InAlmacene $almacen)
    {
        return new AlmacenResource($almacen);
    }


    public function update(AlmacenRequest $request, $id)
    {
        try {
        $almacen = InAlmacene::find($id);
        $almacen->update($request->all());
        $almacen=$almacen->where('id',$id)->first();
        return response()->json([
                'message' => 'El almacen fue editado con exito',
                'data' => $almacen
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }
    public function findByCodigo($codigo)
    {
        $almacen = InAlmacene::where('codigo',$codigo)->first();
        if($almacen){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del almacen ya se encuentra registrado',
                'data' => $almacen
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El almacen no existe'
        ],204);
    }
    public function findById($id)
    {
        $almacen = InAlmacene::where('id',$id)->first();
        if($almacen){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del almacen ya se encuentra registrado',
                'data' => $almacen
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El almacen no existe'
        ],204);
    }
}
