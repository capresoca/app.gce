<?php

namespace App\Http\Controllers\Tesoreria;

use App\Http\Requests\Tesoreria\ConceptoEgresoRequest;
use App\Http\Resources\Tesoreria\ConceptosEgresoResource;
use App\Models\Tesoreria\TsConceptoEgreso;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class ConceptoEgresosController extends Controller
{

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        if(Input::get('per_page')){
            return ConceptosEgresoResource::collection(
                TsConceptoEgreso::with('niif.clascuenta','cuenta_banco.banco')
                    ->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return ConceptosEgresoResource::collection(TsConceptoEgreso::with('niif.clascuenta','cuenta_banco.banco')->pimp()->orderBy('updated_at', 'desc')->get());
    }


    /**
     * @param ConceptoEgresoRequest $request
     * @return ConceptosEgresoResource
     */
    public function store(ConceptoEgresoRequest $request)
    {
        $concepto = TsConceptoEgreso::create($request->all());
        return response()->json([
                'message' => 'El concepto fue creado con exito',
                'data' => TsConceptoEgreso::where('id',$concepto->id)->with('niif.clascuenta')->first()
            ]);        
    }


    /**
     * @param TsConceptoEgreso $concepto
     * @return ConceptosEgresoResource
     */
    public function show(TsConceptoEgreso $ts_concepto)
    {
//        dd($ts_concepto);
        return new ConceptosEgresoResource($ts_concepto);
    }


    public function update(ConceptoEgresoRequest $request, $id)
    {
        $concepto = TsConceptoEgreso::find($id);
        $concepto->update($request->all());
        $concepto=TsConceptoEgreso::whereId($id)->with('niif.clascuenta')->first();
        return new ConceptosEgresoResource($concepto);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function findByCodigo($codigo)
    {
        try {
            $concepto=TsConceptoEgreso::where('codigo',$codigo)->with('niif.clascuenta','banco.clascuenta','presupuestal','ingreso')->first();
            if ($concepto) {
                return response()->json([
                        'exists' => true,
                        'message' => 'El concepto de recibo de caja existe',
                        'data' => $concepto
                    ]);
            }
            return response()->json([
                'exists' => false,
                'message' => 'El concepto de recibo de caja existe'
            ],204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se presento un error en el servidor',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
    public function findById($id)
    {
        try {
            $concepto=TsConceptoEgreso::where('id',$id)->with('niif.clascuenta','banco.clascuenta','presupuestal','ingreso')->first();
            if ($concepto) {
                return response()->json([
                        'exists' => true,
                        'message' => 'El concepto de recibo de caja existe',
                        'data' => $concepto
                    ]);
            }
            return response()->json([
                'exists' => false,
                'message' => 'El concepto de recibo de caja existe'
            ],204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se presento un error en el servidor',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
