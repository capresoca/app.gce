<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Requests\Presupuesto\ModStrgastoRequest;
use App\Http\Resources\Presupuesto\ModStrgastoResource;
use App\Models\Presupuesto\PrDetstrgasto;
use App\Models\Presupuesto\PrModStrgasto;
use App\Models\Presupuesto\PrStrgasto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ModStrgastoController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return ModStrgastoResource::collection(
                PrModStrgasto::with(
                    'presupuesto_inicial_gasto.entidadResolucion')
                    ->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return ModStrgastoResource::collection(PrModStrgasto::with(
            'presupuesto_inicial_gasto.entidadResolucion')
            ->pimp()
            ->orderBy('updated_at','desc')
            ->get());
    }

    public function store(ModStrgastoRequest $request)
    {
        DB::beginTransaction();
        $pr_mod_strgasto = PrModStrgasto::create($request->except('fecha_anulacion', 'detalles'));
        $this->actualizarDetalle($request->detalles);
        $pr_mod_strgasto->detalles()->createMany($request->detalles);
        $pr_mod_strgasto->load('presupuesto_inicial_gasto.entidadResolucion', 'detalles.rubro', 'detalles.tipoGasto');
        DB::commit();
        return response()->json(
            [
                'message' => 'Se ha registrado correctamente.',
                'data' => new ModStrgastoResource($pr_mod_strgasto)
            ]
        );
//        try {
//            DB::beginTransaction();
//            $pr_mod_strgasto = PrModStrgasto::create($request->except('fecha_anulacion', 'detalles'));
//            $pr_mod_strgasto->detalles()->createMany($request['detalles']);
//            $this->actualizarDetalle($request['detalles']);
//            $pr_mod_strgasto->load('presupuesto_inicial_gasto.entidadResolucion', 'detalles.rubro');
//            DB::commit();
//            return response()->json(
//                [
//                    'message' => 'Se ha registrado correctamente.',
//                    'data' => new PrModStrgasto($pr_mod_strgasto)
//                ], 201
//            );
//        } catch (\Exception $e) {
//            return response()->json(
//                [
//                    'msg' => 'Se ha presentado un error al crear.',
//                    'error' => $e
//                ], 500
//            );
//        }
    }

    public function show(PrModStrgasto $pr_mod_strgasto)
    {
        return new Resource($pr_mod_strgasto->load('presupuesto_inicial_gasto.entidadResolucion','detalles.rubro', 'detalles.tipoGasto'));
    }

    public function update(ModStrgastoRequest $request, PrModStrgasto $pr_mod_strgasto)
    {
        try {
            DB::beginTransaction();
            $pr_mod_strgasto->update($request->except('detalles', 'fecha_anulacion'));
            if ($request['estado'] === 'Anulada') {
                $pr_mod_strgasto->fecha_anulacion = Carbon::now()->format('Y-m-d');
            }
            $pr_mod_strgasto->detalles()->delete();
            $this->actualizarDetalle($request->detalles);
            $pr_mod_strgasto->detalles()->createMany($request->detalles);
            $pr_mod_strgasto->load('presupuesto_inicial_gasto.entidadResolucion','detalles.rubro','detalles.tipoGasto');
            DB::commit();
            return response()->json(
                [
                    'message' => 'Se ha registrado correctamente.',
                    'data' => new ModStrgastoResource($pr_mod_strgasto)
                ]
            );
        } catch (\Exception $e) {
            return response()->json([
                'error'=> $e,
                'msg' => 'Se ha presentado un error al actualizar.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        //
    }

    public function actualizarDetalle ($detalles) {
        if ($detalles) {
            foreach ($detalles as $detalle) {
                $idDetalle = (integer) $detalle['pr_detstrgasto_id'];
                $pr_detstrgasto = PrDetstrgasto::find($idDetalle);
                if ($detalle['naturaleza'] === 'Crédito') {
                    $pr_detstrgasto->valor_credito = $detalle['valor_movimiento'];
                } elseif ($detalle['naturaleza'] === 'Débito') {
                    $pr_detstrgasto->valor_debito = $detalle['valor_movimiento'];
                }
                $pr_detstrgasto->save();
            }
        }
    }

    public function getPresupuestoConfirmado () {
        try {
            $presupuestos = PrStrgasto::where('periodo', '>=', Carbon::now()->format('Y'))
                ->where('estado', 'Confirmada')->get();
            $periodos = [];
            foreach ($presupuestos as $item) {
                array_push($periodos, $item->periodo);
            }
            return $periodos;
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function findByPeriodo ($periodo) {
        try {
            $pr_strgasto = PrStrgasto::where('periodo', $periodo)->first();
            $pr_strgasto->load('entidadResolucion.tercero', 'detalles.rubro', 'detalles.tipoGasto');
            $rubros = [];
            foreach ($pr_strgasto->detalles as $detalle) {
                array_push($rubros, $detalle->rubro);
            }
//            $gasto = $pr_strgasto->toArray();
//            array_push($gasto, $rubros);
            return response()->json([
                'rubros' => new Resource($rubros),
                'strgasto' => new Resource($pr_strgasto)
            ]);

        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function findRubro()
    {
        try {
            $strgastoId = (integer) Input::get('strgastoId');
            $rubroId = (integer) Input::get('rubroId');

            $pr_detstrgasto = PrDetstrgasto::where('pr_strgasto_id', $strgastoId)->where('pr_rubro_id', $rubroId)->first();
            $pr_detstrgasto->load('tipoGasto', 'rubro');
            return new Resource($pr_detstrgasto);
        } catch (\Exception $exception) {
            return $exception;
        }
    }
}
