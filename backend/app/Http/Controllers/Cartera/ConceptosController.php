<?php

namespace App\Http\Controllers\Cartera;

use App\Http\Requests\Cartera\ConceptosRequest;
use App\Http\Resources\Cartera\ConceptoResource;
use App\Models\Cartera\CrConcepto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class ConceptosController extends Controller
{

    public function index()
    {
        if(Input::get('per_page')){
            return ConceptoResource::collection(
                CrConcepto::with('niif.clascuenta')
                    ->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return ConceptoResource::collection(CrConcepto::with('niif.clascuenta')->pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(ConceptosRequest $request)
    {
        $cr_concepto = CrConcepto::create($request->all());
        return response()->json([
                'message' => 'El concepto fue creado con exito',
                'data' => CrConcepto::where('id',$cr_concepto->id)->with('niif.clascuenta')->first()
            ]);
    }


    public function show(CrConcepto $cr_concepto)
    {
        return new ConceptoResource($cr_concepto->load('niif.clascuenta'));
    }


    public function update(ConceptosRequest $request, $id)
    {
        try {
        $concepto = CrConcepto::find($id);
        $concepto->update($request->all());
        $concepto=$concepto->where('id',$id)->with('niif.clascuenta')->first();
        return response()->json([
                'message' => 'El concepto fue editado con exito',
                'data' => $concepto
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }
    public function findByCodigo($codigo,$tipoConcepto)
    {
        $concepto = CrConcepto::where('codigo',$codigo)->where('tipo_concepto',$tipoConcepto)->with('niif.clascuenta')->first();
        if($concepto){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del concepto ya se encuentra registrado',
                'concepto' => $concepto
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El concepto no existe'
        ],204);
    }
    public function findById($id)
    {
        $concepto = CrConcepto::where('id',$id)->with('niif.clascuenta')->first();
        if($concepto){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del concepto ya se encuentra registrado',
                'data' => $concepto
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El concepto no existe'
        ],204);
    }
}
