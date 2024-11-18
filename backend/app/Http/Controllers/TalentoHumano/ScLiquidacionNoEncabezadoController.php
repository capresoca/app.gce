<?php

namespace App\Http\Controllers\TalentoHumano;

use App\Http\Requests\TalentoHumano\ScLiquidacionNoEncabezadoRequest;
use App\Http\Resources\TalentoHumano\ScLiquidacionNoEncabezadoResource;
use App\Models\TalentoHumano\ScLiquidacionNominaEncabezado;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class ScLiquidacionNoEncabezadoController extends Controller
{

    public function index()
    {
        if (Input::get('per_page')) {
            $empleado = ScLiquidacionNominaEncabezado::with('tbarea','cencosto','tbdependencia')->pimp()
                ->orderBy('liquidacion_nomina', 'desc')->paginate(Input::get('per_page'));
            return ScLiquidacionNoEncabezadoResource::collection($empleado);
        }
        return ScLiquidacionNoEncabezadoResource::collection(ScLiquidacionNominaEncabezado::with('tbarea','cencosto','tbdependencia')
            ->pimp()->orderBy('liquidacion_nomina', 'desc')->get());
    }

    public function store(ScLiquidacionNoEncabezadoRequest $request)
    {
        try {
            $liquidacion = ScLiquidacionNominaEncabezado::create($request->except('usuario'));

            return response()->json([
                'data' => new ScLiquidacionNoEncabezadoResource($liquidacion)
            ],201);

        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception->getMessage()
            ],500);
        }
    }

    public function show($liquidacionNominaEncabezado)
    {
        $liquidacionNomina = ScLiquidacionNominaEncabezado::where('liquidacion_nomina',$liquidacionNominaEncabezado)->first();
        return new ScLiquidacionNoEncabezadoResource($liquidacionNomina);
    }

    public function update(Request $request, $liquidacionNominaEncabezado)
    {
        try {
            $liquidacion = ScLiquidacionNominaEncabezado::where('liquidacion_nomina', $liquidacionNominaEncabezado)->first();
            $liquidacion->fill($request->all());
            $liquidacion->save();

            return response()->json([
                'data' => new ScLiquidacionNoEncabezadoResource($liquidacion)
            ],202);

        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception->getMessage()
            ],500);
        }
    }

    public function destroy($id)
    {
        //
    }
}
