<?php

namespace App\Http\Controllers\Tesoreria;

use App\Http\Requests\Tesoreria\CuentasRequest;
use App\Http\Resources\Tesoreria\CuentaResource;
use App\Models\Tesoreria\TsCuenta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CuentaController extends Controller
{
    public function index()
    {
        return CuentaResource::collection(TsCuenta::orderBy('updated_at', 'desc')->with('detalles','banco','niif.clascuenta','cencosto','municipio')->get());

    }

    public function store(CuentasRequest $request)
    {
        $cuenta = TsCuenta::create($request->all());

        $cuenta->detalles()->createMany(
            $request->detalles
        );

        $cuenta->load('banco','niif','cencosto','municipio', 'detalles');

        return new CuentaResource($cuenta);
    }

    public function update(CuentasRequest $request, TsCuenta $cuenta)
    {
        $cuenta->update($request->all());

        $cuenta->detalles()->delete();

        $cuenta->detalles()->createMany($request->detalles);

        $cuenta->load('banco','niif','cencosto','municipio', 'detalles');

        return new CuentaResource($cuenta);
    }

    public function show(TsCuenta $cuenta)
    {
        $cuenta->load('banco','niif','cencosto','municipio', 'detalles');
        return new CuentaResource($cuenta);
    }
}
