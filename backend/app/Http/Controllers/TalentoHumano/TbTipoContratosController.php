<?php

namespace App\Http\Controllers\TalentoHumano;

use App\Models\TalentoHumano\TbTipoContrato;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

/**
 * @author Jorge Hernández 27/04/2020
 * Creando Soluciones Informaticas Ltda
 */

class TbTipoContratosController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            return Resource::collection(TbTipoContrato::pimp()->paginate(Input::get('per_page')));
        }
        return Resource::collection(TbTipoContrato::pimp()->get());
    }

    public function store(Request $request)
    {
        $tipo = new TbTipoContrato();
        $tipo->fill($request->all());
        $tipo->save();

        return response()->json([
            'data' => collect($tipo),
            'msg' => 'Se guardo el registro  éxitosamente.'
        ], 201);
    }

    public function show(TbTipoContrato $tipoContrato)
    {
        return collect($tipoContrato);
    }

    public function update(Request $request, $tipoContrato)
    {
        $tipo = TbTipoContrato::find($tipoContrato);
        $tipo->fill($request->all());
        $tipo->save();

        return response()->json([
            'data' => collect($tipo),
            'msg' => 'Se actualizó el registro éxitosamente.'
        ], 201);
    }

    public function destroy(TbTipoContrato $tipoContrato)
    {
        $tipoContrato->delete();

        return response()->json([
            'data' => '',
            'msg' => 'Se elimio éxitosamente'
        ], 200);
    }
}
