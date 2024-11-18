<?php

namespace App\Http\Controllers\Tesoreria;

use App\Http\Requests\Tesoreria\CajaRequest;
use App\Http\Resources\Tesoreria\CajasResource;
use App\Models\Tesoreria\TsCaja;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CajaController extends Controller
{

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return CajasResource::collection(TsCaja::pimp()->with(['niif','cencosto'])->get());
    }


    /**
     * @param CajaRequest $request
     * @return CajasResource
     */
    public function store(CajaRequest $request)
    {
        $caja = TsCaja::create($request->all());
        $caja->load('niif','cencosto');
        return new CajasResource($caja);
    }


    /**
     * @param TsCaja $caja
     * @return CajasResource
     */
    public function show(TsCaja $caja)
    {
        return new CajasResource($caja);
    }


    /**
     * @param CajaRequest $request
     * @param TsCaja $caja
     * @return CajasResource
     */
    public function update(CajaRequest $request, TsCaja $caja)
    {
        $caja->update($request->all());
        $caja->load('cencosto','niif');
        return new CajasResource($caja);
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
