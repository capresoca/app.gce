<?php

namespace App\Http\Controllers\Niif;

use App\Http\Requests\Niif\TipcomdiarioRequest;
use App\Http\Resources\Niif\TipcomdiariosResource;
use App\Models\Niif\NfTipcomdiario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TipcomdiarioController extends Controller
{

    public function index()
    {
        return TipcomdiariosResource::collection(NfTipcomdiario::all());
    }


    public function store(TipcomdiarioRequest $request)
    {
        $tipcomdiario = NfTipcomdiario::create($request->all());

        return new TipcomdiariosResource($tipcomdiario);
    }


    public function show(NfTipcomdiario $tipcomdiario)
    {
        return new TipcomdiariosResource($tipcomdiario);
    }


    public function update(TipcomdiarioRequest $request, NfTipcomdiario $tipcomdiario)
    {
        $tipcomdiario->update($request->all());

        return new TipcomdiariosResource($tipcomdiario);
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
