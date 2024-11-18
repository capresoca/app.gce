<?php

namespace App\Http\Controllers\TalentoHumano;

use App\Http\Resources\TalentoHumano\ScContratoEmpleadoExtraResource;
use App\Models\TalentoHumano\ScContratoEmpleado;
use App\Models\TalentoHumano\ScContratoEmpleadoExtra;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

/**
 * @author Jorge Hernández 01/05/2020
 * Creando Soluciones Informaticas Ltda
 */

class ScContEmpleadoExtrasController extends Controller
{

    public function index()
    {
        $idContratoEmpleado = Input::get('contrato_empleado');
        if (Input::get('per_page')) {
            return ScContratoEmpleadoExtraResource::collection(ScContratoEmpleadoExtra::with('extralaboral')
                ->where('contrato_empleado',$idContratoEmpleado)->pimp()->orderBy('contrato_empleado_extra','desc')->paginate(Input::get('per_page')));
        }
        return ScContratoEmpleadoExtraResource::collection(ScContratoEmpleadoExtra::with('extralaboral')
            ->where('contrato_empleado',$idContratoEmpleado)->pimp()->orderBy('contrato_empleado_extra','desc')->pimp()->get());
    }

    public function store(Request $request)
    {
        try {
            $sccontEmpleadoExtra = ScContratoEmpleadoExtra::create($request->all());

            return response()->json([
                'data' => new ScContratoEmpleadoExtraResource($sccontEmpleadoExtra)
            ], 201);

        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
                'msg'   => $exception
            ],500);
        }
    }

    public function show($contratoEmpleadoExtra)
    {
        try {
            $contratoEmpleado = ScContratoEmpleadoExtra::with(
                'extralaboral',
                'contrato')
                ->where('contrato_empleado_extra', $contratoEmpleadoExtra)->first();
            return new Resource($contratoEmpleado);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
                'message' => 'Error en el servidor'
            ], 500);
        }
    }

    public function update(Request $request, $contratoEmpleadoExtra)
    {
        try {
            $sccontEmpleadoExtra = ScContratoEmpleadoExtra::with('extralaboral')
                ->where('contrato_empleado_extra', $contratoEmpleadoExtra)->first();
            $sccontEmpleadoExtra->fill($request->all());
            $sccontEmpleadoExtra->save();

            return response()->json([
                'data' => new ScContratoEmpleadoExtraResource($sccontEmpleadoExtra)
            ], 201);

        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
                'msg'   => $exception
            ],500);
        }
    }

    public function destroy($id)
    {
        //
    }
}
