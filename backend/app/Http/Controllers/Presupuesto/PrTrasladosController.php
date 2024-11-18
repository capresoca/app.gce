<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Requests\Presupuesto\PrTrasladoRequest;
use App\Http\Resources\Presupuesto\PrTrasladoResource;
use App\Models\Presupuesto\PrDetstrgasto;
use App\Models\Presupuesto\PrDetstringreso;
use App\Models\Presupuesto\PrTraslado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\ValidationException;

class PrTrasladosController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return PrTrasladoResource::collection(
                PrTraslado::with('presupuesto_inicial_gasto.entidadResolucion', 'presupuesto_inicial_ingreso.entidadResolucion')
                    ->pimp()
                    ->orderBy('consecutivo_presupuestal','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return PrTrasladoResource::collection(PrTraslado::with(
            'presupuesto_inicial_gasto.entidadResolucion', 'presupuesto_inicial_ingreso.entidadResolucion')
            ->pimp()
            ->orderBy('consecutivo_presupuestal','desc')
            ->get());
    }

    public function store(PrTrasladoRequest $request)
    {
        try {
            DB::beginTransaction();
            $pr_traslado = PrTraslado::create($request->except('fecha_anulacion','detalles'));
            $pr_traslado->detalles()->createMany($request->detalles);
            $pr_traslado->load('presupuesto_inicial_gasto.entidadResolucion', 'detalles.rubroTraslado', 'detalles.rubroTrasladado', 'detalles.tipoGasto', 'detalles.tipoIngreso', 'detalles.detalleGasto','detalles.detalleIngreso', 'presupuesto_inicial_ingreso.entidadResolucion');
            DB::commit();
            return response()->json(
                [
                    'msg' => 'Se ha registrado correctamente el traslado.',
                    'data' => new PrTrasladoResource($pr_traslado)
                ],201);
        } catch (ValidationException $validationException) {
            return response()->json([
                'error' => $validationException,
                'msg' => 'Error de validación, algunos campos no son validos o se encuentran vacíos.'
            ],422);
        } catch (\Exception $exception) {
            return response()->json([
                'msg' => 'Error en el servidor al realizar el registro del traslado.',
                'error' => $exception
            ],500);
        }
    }

    public function show(PrTraslado $pr_traslado)
    {
        return new Resource($pr_traslado->load('presupuesto_inicial_gasto.entidadResolucion.tercero', 'detalles.rubroTraslado', 'detalles.rubroTrasladado', 'detalles.tipoGasto', 'detalles.tipoIngreso', 'detalles.detalleGasto','detalles.detalleIngreso', 'presupuesto_inicial_ingreso.entidadResolucion.tercero'));
    }

    public function update(PrTrasladoRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $pr_traslado = PrTraslado::find($id);
            $pr_traslado->fill($request->except('detalles', 'fecha_anulacion'));
            if ($request['estado'] === 'Anulado') {
                $pr_traslado->fecha_anulacion = Carbon::now()->format('Y-m-d');
            }
            $pr_traslado->save();
            $pr_traslado->detalles()->delete();
            $pr_traslado->detalles()->createMany($request->detalles);
            $pr_traslado->load('presupuesto_inicial_gasto.entidadResolucion.tercero', 'detalles.rubroTraslado', 'detalles.rubroTrasladado', 'detalles.tipoGasto', 'detalles.tipoIngreso', 'detalles.detalleGasto','detalles.detalleIngreso', 'presupuesto_inicial_ingreso.entidadResolucion.tercero');
            DB::commit();
        } catch (ValidationException $validationException) {
            DB::rollBack();
            return response()->json([
                'msg' => 'Error en la validación, puede que algunos campos no se enviaran.',
                'error' => $validationException
            ],422);
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error'=> $e,
                'msg' => 'Se ha presentado un error al actualizar.'
            ], 500);
        }
    }

    public function destroy($id) {}

    public function findRubro()
    {
        try {
            $tipo = Input::get('tipo');
            $presupuestoId = Input::get('presupuestoId');
            $rubroId = (int) Input::get('rubroId');
            if ($tipo === 'Gasto') {
                $pr_detstrgasto = PrDetstrgasto::with('tipoGasto', 'rubro')->where('pr_strgasto_id', $presupuestoId)->where('pr_rubro_id', $rubroId)->first();
                return new Resource($pr_detstrgasto);
            } elseif ($tipo === 'Ingreso') {
                $pr_detstringreso = PrDetstringreso::with('tipoIngreso', 'rubro')->where('pr_stringreso_id', $presupuestoId)->where('pr_rubro_id', $rubroId)->first();
                return new Resource($pr_detstringreso);
            }
        } catch (\Exception $exception) {
            return $exception;
        }
    }
}
