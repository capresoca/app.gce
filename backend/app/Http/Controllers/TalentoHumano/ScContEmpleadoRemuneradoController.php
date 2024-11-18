<?php

namespace App\Http\Controllers\TalentoHumano;

use App\Http\Resources\TalentoHumano\ScContratoEmpleadoRemuneracionResource;
use App\Models\TalentoHumano\ScContratoEmpleadoRemuneracion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

/**
 * @author Jorge Hernández 01/05/2020
 * Creando Soluciones Informaticas Ltda
 */

class ScContEmpleadoRemuneradoController extends Controller
{
    public function index()
    {
        $idContratoEmpleado = Input::get('contrato_empleado');
        if (Input::get('per_page')) {
            return ScContratoEmpleadoRemuneracionResource::collection(
                ScContratoEmpleadoRemuneracion::with('contrato')->where('contrato_empleado',$idContratoEmpleado)
                    ->pimp()->orderBy('contrato_empleado_remuneracion','desc')->paginate(Input::get('per_page')));
        }
        return ScContratoEmpleadoRemuneracionResource::collection(
            ScContratoEmpleadoRemuneracion::with('contrato')->where('contrato_empleado',$idContratoEmpleado)
                ->pimp()->orderBy('contrato_empleado_remuneracion','desc')->pimp()->get());
    }

    public function store(Request $request)
    {
        try {

            $remuneracion = ScContratoEmpleadoRemuneracion::with('contrato')->create($request->all());

            return response()->json([
                'data' => new ScContratoEmpleadoRemuneracionResource($remuneracion)
            ],201);

        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
                'message' => 'Error en el servidor'
            ], 500);
        }
    }

    public function show(ScContratoEmpleadoRemuneracion $contratoEmpleadoRemuneracion)
    {
        return new Resource($contratoEmpleadoRemuneracion->load('contrato'));
    }

    public function update(Request $request, $contratoEmpleadoRemuneracion)
    {
        try {

            $remuneracion = ScContratoEmpleadoRemuneracion::with('contrato')
                ->where('contrato_empleado_remuneracion',$contratoEmpleadoRemuneracion)->first();
            $remuneracion->fill($request->all());
            $remuneracion->save();

            return response()->json([
                'data' => new ScContratoEmpleadoRemuneracionResource($remuneracion)
            ],201);

        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
                'message' => 'Error en el servidor'
            ], 500);
        }
    }

    public function destroy($id)
    {
        //
    }
}
