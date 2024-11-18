<?php

namespace App\Http\Controllers\TalentoHumano;

use App\Http\Requests\TalentoHumano\ScContratosEmpleadoRequest;
use App\Http\Resources\TalentoHumano\ScContratosEmpleadoResource;
use App\Models\TalentoHumano\ScContratoEmpleado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

/**
 * @author Jorge Hernández 01/05/2020
 * Creando Soluciones Informaticas Ltda
 */

class ScContratosEmpleadoController extends Controller
{
    public function index()
    {
        $idempleado = Input::get('empleado');
        if (Input::get('per_page') && $idempleado) {
            return ScContratosEmpleadoResource::collection(ScContratoEmpleado::with(
                'tbcargo',
                'tb_tipocontrato',
                'scempleado'
            )->where('empleado', (int) $idempleado)->pimp()->orderBy('contrato_empleado','desc')->paginate(Input::get('per_page')));
        }
        return ScContratosEmpleadoResource::collection(ScContratoEmpleado::with(
            'tbcargo',
            'tb_tipocontrato',
            'scempleado'
        )->where('empleado', (int) $idempleado)->pimp()->get());
    }

    public function store(ScContratosEmpleadoRequest $request)
    {
        try {

            $contrato = ScContratoEmpleado::with(
                'tbcargo',
                'tb_tipocontrato'
            )->create($request->all());

            return response()->json([
                'data' => new ScContratosEmpleadoResource($contrato)
            ],201);

        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
                'message' => 'Error en el servidor'
            ], 500);
        }
    }

    public function show($conEmpleado)
    {
        try {
            $contratoEmpleado = ScContratoEmpleado::with('tbarea',
                'tbcargo',
                'tbcentrocosto',
                'tbdependencia',
                'configuracion_salarial',
                'sc_empleado',
                'tb_tipocontrato',
                'scempleado')
                ->where('contrato_empleado', $conEmpleado)->first();
            return new Resource($contratoEmpleado);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
                'message' => 'Error en el servidor'
            ], 500);
        }
    }

    public function update(Request $request, $conEmpleado)
    {
        try {

            $contrato = ScContratoEmpleado::with('tbcargo', 'tb_tipocontrato')
                ->where('contrato_empleado',$conEmpleado)
                ->first();
            $contrato->fill($request->all());
            if (!is_null($request->fecha_liquidacion)) {
                $contrato->fecha_final = Carbon::now()->format('Y-m-d');
            }
            $contrato->save();

            return response()->json([
                'data' => new ScContratosEmpleadoResource($contrato)
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
