<?php

namespace App\Http\Controllers\Autorizaciones;

use App\Http\Requests\Autorizaciones\DetsolautorizacionRequest;
use App\Http\Resources\Autorizaciones\DetsolautorizacionResource;
use App\Models\Autorizaciones\AuDetsolautorizacione;
use App\Models\Autorizaciones\AuSolautorizacione;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DetsolautorizacionController extends Controller
{

    public function index() {}

    public function store(AuSolautorizacione $au_solautorizacione, DetsolautorizacionRequest $request)
    {
        $detalle = $au_solautorizacione->detalles()->create($request->all());
        return new DetsolautorizacionResource($detalle);
    }

    public function show(AuSolautorizacione $au_solautorizacione, AuDetsolautorizacione $detalle)
    {
        return new DetsolautorizacionResource($detalle);
    }

    public function update(AuSolautorizacione $au_solautorizacione, DetsolautorizacionRequest $request, AuDetsolautorizacione $detalle)
    {
        $detalle->update($request->all());
        return new DetsolautorizacionResource($detalle);
    }

    public function destroy(AuSolautorizacione $au_solautorizacione, AuDetsolautorizacione $detalle)
    {
        try {
            $detalle->delete();

            return response()->json([
                'message' => 'El detallle se ha removido de la solicitud.'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception,
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
