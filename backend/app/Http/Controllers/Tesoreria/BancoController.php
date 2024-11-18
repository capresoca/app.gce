<?php

namespace App\Http\Controllers\Tesoreria;

use App\Http\Requests\Tesoreria\BancoRequest;
use App\Http\Resources\Tesoreria\BancosResource;
use App\Models\Tesoreria\TsBanco;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BancoController extends Controller
{

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return BancosResource::collection(TsBanco::with('tercero')->get());
    }


    /**
     * @param BancoRequest $request
     * @return BancosResource
     */
    public function store(BancoRequest $request)
    {
        $banco = TsBanco::create($request->all());
        $banco->load('tercero');
        return new BancosResource($banco);
    }


    /**
     * @param TsBanco $banco
     * @return BancosResource
     */
    public function show(TsBanco $banco)
    {
        return new BancosResource($banco);
    }


    /**
     * @param BancoRequest $request
     * @param TsBanco $banco
     * @return BancosResource
     */
    public function update(BancoRequest $request, TsBanco $banco)
    {
        $banco->update($request->all());
        $banco->load('tercero');
        return new BancosResource($banco);
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
