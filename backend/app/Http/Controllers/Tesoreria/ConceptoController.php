<?php

namespace App\Http\Controllers\Tesoreria;

use App\Http\Requests\Tesoreria\ConceptoRequest;
use App\Http\Resources\Tesoreria\ConceptosResource;
use App\Models\Tesoreria\TsConcepto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConceptoController extends Controller
{

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return ConceptosResource::collection(TsConcepto::with(['niif']));
    }


    /**
     * @param ConceptoRequest $request
     * @return ConceptosResource
     */
    public function store(ConceptoRequest $request)
    {
        return new ConceptosResource(TsConcepto::create($request->all()));
    }


    /**
     * @param TsConcepto $concepto
     * @return ConceptosResource
     */
    public function show(TsConcepto $ts_concepto)
    {
        return new ConceptosResource($ts_concepto);
    }


    public function update(ConceptoRequest $request, TsConcepto $ts_concepto)
    {
        $ts_concepto->update($request->all());
        return new ConceptosResource($ts_concepto);
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
}
