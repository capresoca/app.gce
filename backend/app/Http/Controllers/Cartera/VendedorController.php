<?php

namespace App\Http\Controllers\Cartera;

use App\Http\Requests\Cartera\VendedorRequest;
use App\Http\Resources\Cartera\VendedorResource;
use App\Models\Cartera\CrVendedore;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VendedorController extends Controller
{

    public function index()
    {
        return VendedorResource::collection(CrVendedore::with('tercero.tipo_id')->orderBy('updated_at', 'desc')->get());
    }


    public function store(VendedorRequest $request)
    {
        $cr_vendedore = CrVendedore::create($request->all());
        return response()->json([
                'message' => 'El vendedor fue creado con exito',
                'data' => CrVendedore::where('id',$cr_vendedore->id)->with('tercero.tipo_id')->first()
            ]);
    }


    public function show(CrVendedore $cr_vendedore)
    {
        return new VendedorResource($cr_vendedore);
    }


    public function update(VendedorRequest $request, $id)
    {
        try {
        $vendedor = CrVendedore::find($id);
        $vendedor->update($request->all());
        $vendedor=CrVendedore::where('id',$id)->with('tercero.tipo_id')->first();
        return response()->json([
                'message' => 'El vendedor fue editado con exito',
                'data' => $vendedor
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }
    public function findByCodigo($codigo)
    {
        $vendedor = CrVendedore::where('codigo',$codigo)->with('tercero')->first();
        if($vendedor){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del vendedor ya se encuentra registrado',
                'vendedor' => $vendedor
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El vendedor no existe'
        ],204);
    }
}
