<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Requests\Presupuesto\ModPresupuestalRequest;
use App\Http\Resources\Presupuesto\ModPresupuestalResource;
use App\Models\Presupuesto\PrDetstrgasto;
use App\Models\Presupuesto\PrDetstringreso;
use App\Models\Presupuesto\PrModPresupuestale;
use App\Models\Presupuesto\PrStrgasto;
use App\Models\Presupuesto\PrStringreso;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\ValidationException;

class ModPresupuestaleController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return ModPresupuestalResource::collection(
//                dd(PrModPresupuestale::with(
//                    'presupuesto_inicial_gasto.entidadResolucion','presupuesto_inicial_ingreso.entidadResolucion')
//                    ->pimp()
//                    ->orderBy('updated_at','desc')
//                    ->toSql())
                PrModPresupuestale::with(
                    'presupuesto_inicial_gasto.entidadResolucion','presupuesto_inicial_ingreso.entidadResolucion')
                    ->pimp()
                    ->orderBy('consecutivo_presupuestal','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return ModPresupuestalResource::collection(PrModPresupuestale::with(
            'presupuesto_inicial_gasto.entidadResolucion','presupuesto_inicial_ingreso.entidadResolucion')
            ->pimp()
            ->orderBy('consecutivo_presupuestal','desc')
            ->get());
    }

    public function store(ModPresupuestalRequest $request)
    {
        try {
            DB::beginTransaction();
            $pr_mod_presupuestale = PrModPresupuestale::create($request->except('fecha_anulacion','detalles'));
            if ($request['estado'] === 'Confirmada') {
                $this->actualizarDetalle($request->detalles, $request->tipo);
            }
            $pr_mod_presupuestale->detalles()->createMany($request->detalles);
            $pr_mod_presupuestale->load('presupuesto_inicial_gasto.entidadResolucion', 'detalles.rubro', 'detalles.tipoGasto', 'detalles.tipoIngreso', 'detalles.detalleGasto','detalles.detalleIngreso', 'presupuesto_inicial_ingreso.entidadResolucion');
            DB::commit();
            return response()->json(
                [
                    'msg' => 'Se ha registrado correctamente la modificación.',
                    'data' => new ModPresupuestalResource($pr_mod_presupuestale)
                ], 201);
        } catch (ValidationException $validationException) {
            DB::rollBack();
            return response()->json([
                'error' => $validationException,
                'msg' => 'Error de validación, algunos campos no son validos o se encuentran vacíos.'
            ],422);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'msg' => 'Error en el servidor al realizar el registro de la modificacón',
                'error' => $exception
            ],500);
        }
    }

    public function show(PrModPresupuestale $pr_mod_presupuestale)
    {
        return new Resource($pr_mod_presupuestale->load('presupuesto_inicial_gasto.entidadResolucion', 'detalles.rubro', 'detalles.tipoGasto', 'detalles.tipoIngreso', 'detalles.detalleGasto','detalles.detalleIngreso', 'presupuesto_inicial_ingreso.entidadResolucion'));
    }

    public function update(ModPresupuestalRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $pr_mod_presupuestale = PrModPresupuestale::find($id);
            $pr_mod_presupuestale->fill($request->except('detalles', 'fecha_anulacion'));
//            $pr_mod_presupuestale->update($request->except('detalles', 'fecha_anulacion'));
            if ($request['estado'] === 'Anulada') {
                $pr_mod_presupuestale->fecha_anulacion = Carbon::now()->format('Y-m-d');
            }
//            $detallesAEliminar = $pr_mod_presupuestale->detalles;
//            $this->restarValores($detallesAEliminar, $pr_mod_presupuestale->tipo);
//            if ($request['estado'] !== 'Anulada') {
            if ($request['estado'] === 'Confirmada') {
                $this->actualizarDetalle($request->detalles, $pr_mod_presupuestale->tipo);
            }
            $pr_mod_presupuestale->save();
            $pr_mod_presupuestale->detalles()->delete();
            $pr_mod_presupuestale->detalles()->createMany($request->detalles);
            $pr_mod_presupuestale->load('presupuesto_inicial_gasto.entidadResolucion', 'detalles.rubro', 'detalles.tipoGasto', 'detalles.tipoIngreso', 'detalles.detalleGasto','detalles.detalleIngreso', 'presupuesto_inicial_ingreso.entidadResolucion');
            DB::commit();
            return response()->json(
                [
                    'message' => 'Se ha actualizado la modificación ',
                    'data' => new ModPresupuestalResource($pr_mod_presupuestale)
                ]
            );
        } catch (ValidationException $validationException) {
            return response()->json([
                'msg' => 'Error en la validación, puede que algunos campos no se enviaran.',
                'error' => $validationException
            ],422);
        }catch (\Exception $e) {
            return response()->json([
                'error'=> $e,
                'msg' => 'Se ha presentado un error al actualizar.'
            ], 500);
        }
    }


    public function destroy($id) {}

    public function restarValores ($details, $tipo)
    {
        if ($details) {
            foreach ($details as $detail) {
                $idDetalle = (int) (($tipo === 'Ingreso') ? $detail['pr_detstringreso_id'] : $detail['pr_detstrgasto_id']);
                $pr_detstr = ($tipo === 'Ingreso') ? PrDetstringreso::find($idDetalle) : PrDetstrgasto::find($idDetalle);
                $valorIngresado = (double) $detail['valor_movimiento'];
                if ($detail['naturaleza'] === 'Crédito') {
                    $valorCredito = (double) $pr_detstr->valor_credito;
                    $pr_detstr->valor_credito = ($valorCredito < $valorIngresado) ? ($valorIngresado - $valorCredito) : ($valorCredito - $valorIngresado);
                } elseif ($detail['naturaleza'] === 'Débito') {
                    $valorDebito = (double) $pr_detstr->valor_debito;
                    $pr_detstr->valor_debito = ($valorDebito < $valorIngresado) ? ($valorIngresado - $valorDebito) : ($valorDebito - $valorIngresado);
                }
                $pr_detstr->save();
            }
        }
    }

    public function actualizarDetalle ($detalles, $tipo)
    {
        if ($detalles) {
            foreach ($detalles as $detalle) {
                $idDetalle = (int) (($tipo === 'Ingreso') ? $detalle['pr_detstringreso_id'] : $detalle['pr_detstrgasto_id']);
                $pr_detstr = ($tipo === 'Ingreso') ? PrDetstringreso::find($idDetalle) : PrDetstrgasto::find($idDetalle);
                $valorIngresado = (double) $detalle['valor_movimiento'];
                if ($detalle['naturaleza'] === 'Crédito') {
                    $valorAnteriorCredito = (double) $pr_detstr->valor_credito;
                    $pr_detstr->valor_credito = ($valorAnteriorCredito !== 0) ? $valorAnteriorCredito + $valorIngresado : $valorIngresado;
                } elseif ($detalle['naturaleza'] === 'Débito') {
                    $valorAnteriorDebito = (double) $pr_detstr->valor_debito;
                    $pr_detstr->valor_debito = ($valorAnteriorDebito !== 0) ? $valorAnteriorDebito + $valorIngresado : $valorIngresado;
                }
                $pr_detstr->save();
            }
        }
    }

    public function getPresupuestoConfirmado () {
        $presupuestos = PrStrgasto::where('periodo', '>=', Carbon::now()->format('Y'))
            ->where('estado', 'Confirmada')->get();
        $periodos = [];
        foreach ($presupuestos as $item) {
            array_push($periodos, $item->periodo);
        }
        return response()->json([
            'exists' => $periodos !== [] ? true : false,
            'data' => $periodos !== [] ? $periodos : 'Verifique el módulo de Presupuesto Inicial de Gastos.'
        ],200);
    }

    public function getPresupuestoIngresoConfirmado () {
        $presupuestos = PrStringreso::where('periodo', '>=', Carbon::now()->format('Y'))
            ->where('estado', 'Confirmada')->get();
        $periodos = [];
        foreach ($presupuestos as $item) {
            array_push($periodos, $item->periodo);
        }
        return response()->json([
            'exists' => $periodos !== [] ? true : false,
            'data' => $periodos !== [] ? $periodos : 'Verifique el módulo de Presupuesto Inicial de Ingresos.'
        ], 200);
    }

    public function findByPeriodo () {
        try {
            $tipo = Input::get('tipo');
            $periodo = Input::get('periodo');
            $pr_str = ($tipo === 'Ingreso') ? PrStringreso::with('detalles.tipoIngreso')->where('periodo', $periodo)->first() : PrStrgasto::with('detalles.tipoGasto')->where('periodo', $periodo)->first();
            $pr_str->load('entidadResolucion.tercero', 'detalles.rubro');
            $rubros = [];
            foreach ($pr_str->detalles as $detalle) {
                array_push($rubros, $detalle->rubro);
            }
//            $gasto = $pr_strgasto->toArray();
//            array_push($gasto, $rubros);
            return response()->json(
                [
                    'rubros' => new Resource($rubros),
                    'presupuesto' => new Resource($pr_str)
//                    'detalles' => new Resource($pr_str->detalles)
                ]
            );

        } catch (\Exception $exception) {
            return $exception;
        }
    }

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
