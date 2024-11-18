<?php

namespace App\Http\Controllers\Autorizaciones;

use App\Models\Autorizaciones\AuAnexot31;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;

class AuAnexoT31Controller extends Controller
{

    public function index(){}

    public function store(AuAnexot31 $anexot31, Request $request)
    {
        $detalle = $anexot31->anexo_negado()->create($request->all());
        $detalle->load('motivo_neg','usuario_neg');
        return response()->json([new Resource($detalle)])->setStatusCode(201);
    }

    public function show($id){}

    public function update(Request $request, $id){}

    public function destroy($id){}
}
