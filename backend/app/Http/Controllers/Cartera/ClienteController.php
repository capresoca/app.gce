<?php

namespace App\Http\Controllers\Cartera;

use App\Http\Requests\Cartera\ClienteRequest;
use App\Http\Resources\Cartera\ClienteResource;
use App\Models\Cartera\CrCliente;
use App\Models\Niif\GnTercero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class ClienteController extends Controller
{

    public function index()
    {

        if(Input::get('per_page')){
            return ClienteResource::collection(
                CrCliente::with('vendedor.tercero','tercero.tipo_id','tercero.municipio.departamento','niif')
                    ->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return ClienteResource::collection(CrCliente::with('vendedor.tercero','tercero.tipo_id','tercero.municipio.departamento','niif')->pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(ClienteRequest $request)
    {
        $cr_cliente = CrCliente::create($request->all());
        return response()->json([
                'message' => 'El cliente fue creado con exito',
                'data' => CrCliente::where('id',$cr_cliente->id)->with('vendedor.tercero','tercero.tipo_id','tercero.municipio.departamento','niif')->first()
            ]);
    }


    public function show(CrCliente $cr_cliente)
    {
        return new ClienteResource($cr_cliente->load('vendedor.tercero','tercero.tipo_id','tercero.municipio.departamento','niif'));
    }


    public function update(ClienteRequest $request, $id)
    {
        try {
        $cliente = CrCliente::find($id);
        $cliente->update($request->all());
        $cliente=$cliente->where('id',$id)->with('vendedor.tercero','tercero.municipio.departamento','niif')->first();
        return response()->json([
                'message' => 'El cliente fue editado con exito',
                'data' => $cliente
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }
    public function findByCodigo($codigo)
    {
        $cliente = CrCliente::where('codigo',$codigo)->with('vendedor.tercero','tercero.municipio.departamento','niif')->first();
        if($cliente){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del cliente ya se encuentra registrado',
                'cliente' => $cliente
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El cliente no existe'
        ],204);
    }
    public function findById($id)
    {
        $cliente = CrCliente::where('id',$id)->with('vendedor.tercero','tercero.municipio.departamento','niif')->first();
        if($cliente){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del cliente ya se encuentra registrado',
                'data' => $cliente
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El cliente no existe'
        ],204);
    }
    public function findTercero($id)
    {
        $tercero = GnTercero::where('id',$id)->with('municipio.departamento')->first();
        if($tercero){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del tercero ya se encuentra registrado',
                'tercero' => $tercero
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El tercero no existe'
        ],204);
    }
}
