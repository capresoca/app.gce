<?php

namespace App\Http\Controllers\Niif;

use App\Http\Requests\Niif\NivcuentaRequest;
use App\Http\Resources\Niif\NivcuentasResource;
use App\Models\Niif\NfNivcuenta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NivcuentaController extends Controller
{

    public function index()
    {
        return NivcuentasResource::collection(NfNivcuenta::all());
    }


    public function store(NivcuentaRequest $request)
    {
        $nivcuenta = new NfNivcuenta();
        $nivcuenta->fill($request->except('codigo'));
        $nivcuenta->codigo = NfNivcuenta::all()->pluck('codigo')->last() + 1;
        $nivcuenta->save();

        return new NivcuentasResource($nivcuenta);
    }


    public function show(NfNivcuenta $nivcuenta)
    {
        return new NivcuentasResource($nivcuenta);
    }


    public function update(NivcuentaRequest $request, NfNivcuenta $nivcuenta)
    {
        $nivcuenta->update($request->all());
        return new NivcuentasResource($nivcuenta);
    }


}
