<?php

namespace App\Http\Controllers\Autorizaciones;

use App\Http\Requests\Autorizaciones\SolautorizacionRequest;
use App\Http\Resources\Autorizaciones\SolautorizacionesResource;
use App\Http\Resources\Autorizaciones\SolautorizacionResource;
use App\Models\Autorizaciones\AuSolautorizacione;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class SolautorizacionController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return SolautorizacionesResource::collection(
                        AuSolautorizacione::with('afiliado','regimen','entidadOrigen',
                                            'cie10','entidadDestino','modservicio','servicio',
                                            'autorizacion','planCobertura','tutela')->pimp()
                                    ->orderBy('au_autsolicitudes.updated_at','desc')
                                    ->paginate(Input::get('per_page'))
            );
        }
        return SolautorizacionResource::collection(
                    AuSolautorizacione::with('afiliado','regimen','entidadOrigen',
                                                'cie10','entidadDestino','modservicio',
                                                'servicio','autorizacion','planCobertura.detalles', 'tutela')
                                        ->pimp()->orderBy('updated_at', 'desc')->get());

    }

    public function store(SolautorizacionRequest $request)
    {
        $au_solautorizacione = AuSolautorizacione::create($request->all());

        $au_solautorizacione->load('afiliado','regimen','entidadOrigen','cie10',
                                    'entidadDestino','modservicio','servicio',
                                    'autorizacion','planCobertura');

        return response()->json([
           'message' => 'Solicitud creada correctamente.',
           'solautorizacion' => new SolautorizacionesResource($au_solautorizacione),
        ], 201);
    }

    public function show(AuSolautorizacione $au_solautorizacione)
    {
        return new SolautorizacionResource(
                    $au_solautorizacione->load('afiliado','regimen','entidadOrigen',
                                        'cie10','entidadDestino','modservicio','servicio',
                                        'autorizacion','planCobertura'));
    }

    public function update(SolautorizacionRequest $request, AuSolautorizacione $au_solautorizacione)
    {
        $au_solautorizacione->update($request->all());
        $au_solautorizacione->load('afiliado','regimen','entidadOrigen','cie10',
                                    'entidadDestino','modservicio','servicio',
                                    'autorizacion','planCobertura');

        return response()->json([
            'message' => 'Solicitud actualizada correctamente.',
            'solautorizacion' => new SolautorizacionesResource($au_solautorizacione)
        ], 202);
    }

    public function destroy($id)
    {
        //
    }
}
