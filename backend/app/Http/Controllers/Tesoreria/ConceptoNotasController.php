<?php

namespace App\Http\Controllers\Tesoreria;

use App\Http\Requests\Tesoreria\ConceptoNotaRequest;
use App\Http\Resources\Tesoreria\ConceptosNotasResource;
use App\Models\Tesoreria\TsConceptoNota;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class ConceptoNotasController extends Controller
{

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        if(Input::get('per_page')){
            return ConceptosNotasResource::collection(
                TsConceptoNota::with('niif.clascuenta')
                    ->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return ConceptosNotasResource::collection(TsConceptoNota::with('niif.clascuenta')->pimp()->orderBy('updated_at', 'desc')->get());
    }


    /**
     * @param ConceptoNotaRequest $request
     * @return ConceptosNotasResource
     */
    public function store(ConceptoNotaRequest $request)
    {
        $concepto = TsConceptoNota::create($request->all());
        return response()->json([
                'message' => 'El concepto fue creado con exito',
                'data' => TsConceptoNota::where('id',$concepto->id)->with('niif.clascuenta')->first()
            ]);
    }


    /**
     * @param TsConceptoNota $concepto
     * @return ConceptosNotasResource
     */
    public function show(TsConceptoNota $ts_concepto)
    {
        return new ConceptosNotasResource($ts_concepto);
    }


    public function update(ConceptoNotaRequest $request, $id)
    {
        $concepto = TsConceptoNota::find($id);
        $concepto->update($request->all());
        $concepto=TsConceptoNota::where('id',$id)->with('niif.clascuenta','rubro','entidad.tercero')->first();
        return new ConceptosNotasResource($concepto);
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
            $concepto=TsConceptoNota::where('codigo',$codigo)->with('niif.clascuenta','rubro','entidad.tercero')->first();
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
            $concepto=TsConceptoNota::where('id',$id)->with('niif.clascuenta','rubro','entidad.tercero')->first();
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
