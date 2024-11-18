<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Requests\Presupuesto\ConceptoPresupuestoRequest;
use App\Http\Resources\Presupuesto\ConceptoPresupuestoResource;
use App\Models\Presupuesto\PrConceptoPresupuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Traits\EnumsTrait;

class ConceptoPresupuestoController extends Controller
{

    public function index()
    {

        if(Input::get('per_page')){
            return ConceptoPresupuestoResource::collection(
                PrConceptoPresupuesto::pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return ConceptoPresupuestoResource::collection(PrConceptoPresupuesto::pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(ConceptoPresupuestoRequest $request)
    {
        $conceptoPresupuesto = PrConceptoPresupuesto::create($request->all());
        return response()->json([
                'message' => 'El cargo de empleados fue creado con exito',
                'data' => PrConceptoPresupuesto::where('id',$conceptoPresupuesto->id)->first()
            ]);
    }


    public function show(PrConceptoPresupuesto $conceptoPresupuesto)
    {
        return new ConceptoPresupuestoResource($conceptoPresupuesto);
    }


    public function update(ConceptoPresupuestoRequest $request, $id)
    {
        try {
        $conceptoPresupuesto = PrConceptoPresupuesto::find($id);
        $conceptoPresupuesto->update($request->all());
        $conceptoPresupuesto=$conceptoPresupuesto->where('id',$id)->first();
        return response()->json([
                'message' => 'El cargo de empleados fue editado con exito',
                'data' => $conceptoPresupuesto
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }
    public function findByCodigo($codigo)
    {
        $conceptoPresupuesto = PrConceptoPresupuesto::where('codigo',$codigo)->first();
        if($conceptoPresupuesto){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del cargo de empleados ya se encuentra registrado',
                'data' => $conceptoPresupuesto
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El cargo de empleados no existe'
        ],204);
    }
    public function findById($id)
    {
        $conceptoPresupuesto = PrConceptoPresupuesto::where('id',$id)->first();
        if($conceptoPresupuesto){
             return response()->json([
                'exists' => true,
                'message'=> 'El codigo del cargo de empleados ya se encuentra registrado',
                'data' => $conceptoPresupuesto
            ],200);
        }
        return response()->json([
            'exists' => false,
            'message' => 'El cargo de empleados no existe'
        ],204);
    }
}
