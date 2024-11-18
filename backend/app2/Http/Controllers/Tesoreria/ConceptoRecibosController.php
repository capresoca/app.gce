<?php

namespace App\Http\Controllers\Tesoreria;

use App\Http\Requests\Tesoreria\ConceptoReciboRequest;
use App\Http\Resources\Tesoreria\ConceptosReciboResource;
use App\Models\Tesoreria\TsConceptoRecibo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class ConceptoRecibosController extends Controller
{

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        if(Input::get('per_page')){
            return ConceptosReciboResource::collection(
                TsConceptoRecibo::with('niif.clascuenta','concepto_retencion','resolucion','rubro.rubro')
                    ->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return ConceptosReciboResource::collection(TsConceptoRecibo::with('niif.clascuenta','concepto_retencion','resolucion','rubro.rubro')->pimp()->orderBy('updated_at', 'desc')->get());
    }


    /**
     * @param ConceptoReciboRequest $request
     * @return ConceptosReciboResource
     */
    public function store(ConceptoReciboRequest $request)
    {
        $concepto = TsConceptoRecibo::create($request->all());
        return response()->json([
                'message' => 'El concepto fue creado con exito',
                'data' => TsConceptoRecibo::where('id',$concepto->id)->with('niif.clascuenta')->first()
            ]);
    }


    /**
     * @param TsConceptoRecibo $concepto
     * @return ConceptosReciboResource
     */
    public function show(TsConceptoRecibo $ts_concepto)
    {
        return new ConceptosReciboResource($ts_concepto);
    }


    public function update(ConceptoReciboRequest $request, $id)
    {
        $concepto = TsConceptoRecibo::find($id);
        $concepto->update($request->all());
        $concepto=TsConceptoRecibo::where('id',$id)->with('niif.clascuenta')->first();
        return new ConceptosReciboResource($concepto);
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
            $concepto=TsConceptoRecibo::where('codigo',$codigo)->with('niif.clascuenta')->first();
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
            $concepto=TsConceptoRecibo::where('id',$id)->with('niif.clascuenta')->first();
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
