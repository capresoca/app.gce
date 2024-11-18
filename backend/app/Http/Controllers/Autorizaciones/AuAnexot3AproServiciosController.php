<?php

namespace App\Http\Controllers\Autorizaciones;

use App\Models\Autorizaciones\AuAnexot3;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuAnexot3AproServiciosController extends Controller
{
    /**
     * Display a listing of the resource.
     * @author JORGE EDUARDO HERNÁNDEZ 06-04-2020
     */
    public function index()
    {
        //
    }


    public function store(AuAnexot3 $anexot3, Request $request) {
        try {
            $solicitud = $anexot3->solicitud_aprobado()->create([
                'fecha_aprobacion' => $request->fecha_aprobacion,
                'observacion'      => $request->observacion,
                'gs_usuario_aprueba_id' => Auth::user()->id
            ]);

            $anexot3->au_anexot3_serv_especiale_id = $solicitud->id;
            $anexot3->sw_espera = 0;
            $anexot3->save();

            return response()->json([
                'data' => collect($solicitud)
            ])->setStatusCode(201);

        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception->getMessage(),
                'message' => $exception
            ]);
        }
    }

    public function show($id) {}

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
