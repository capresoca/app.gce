<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Requests\Presupuesto\PrCdpRequest;
use App\Http\Resources\Presupuesto\PrCdpResource;
use App\Models\Presupuesto\PrCdp;
use App\Models\Presupuesto\PrDetstrgasto;
use App\Models\Presupuesto\PrStrgasto;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class PrCdpController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return PrCdpResource::collection(
                PrCdp::with('dependencia.tercero', 'presupuesto_inicial_gasto.entidadResolucion.tercero')->pimp()
                    ->orderBy('consecutivo','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return PrCdpResource::collection(PrCdp::with('dependencia.tercero', 'presupuesto_inicial_gasto.entidadResolucion.tercero')->pimp()
            ->orderBy('consecutivo','desc')
            ->get());
    }

    public function store(PrCdpRequest $request)
    {
        try {
            DB::beginTransaction();
            $prCdp = PrCdp::create($request->except('detalles','fecha_confirmacion','estado_valor'));
            if ($request['estado'] === 'Confirmada') {
                $prCdp->fecha_confirmacion = Carbon::now()->toDateTimeString();
                $prCdp->estado_valor = 'Disponible';
                $this->creActualizaDetalleStrgasto($request->detalles, $prCdp->id);
            }
            $prCdp->detalles()->createMany($request->detalles);
            $prCdp->load('dependencia.tercero', 'presupuesto_inicial_gasto.entidadResolucion.tercero', 'detalles.tipoGasto', 'detalles.rubro');
            DB::commit();
            return response()->json([
                'msg' => 'Registro creado éxitosamente.',
                'data' => new PrCdpResource($prCdp)
            ], 201);
        }catch (ValidationException $validationException) {
            return response()->json([
                'msg' => 'Error en la validación',
                'error' => $validationException
            ], 422);
        } catch (\Exception $exception) {
            return response()->json([
                'msg' => 'Se ha presentado un error al crear',
                'error' => $exception
            ], 500);
        }
    }

    public function show(PrCdp $prCdp)
    {
        return new Resource($prCdp->load('dependencia.tercero', 'presupuesto_inicial_gasto.entidadResolucion.tercero','detalles.detStrgasto','detalles.tipoGasto', 'detalles.rubro'));
    }

    public function update(PrCdpRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $prCdp = PrCdp::find($id);
            $prCdp->fill($request->except('detalles', 'fecha_confirmacion','fecha_anulacion','estado_valor'));
//            $detallesAEliminar = $prCdp->detalles;
//            $this->restValRpAValCdp($detallesAEliminar);
//            dd($prCdp);
            if ($request['estado'] === 'Anulada') {
                $prCdp->fecha_anulacion = Carbon::now()->format('Y-m-d');
            }
            if ($request['estado'] === 'Confirmada') {
                $this->creActualizaDetalleStrgasto($request->detalles);
                $prCdp->fecha_confirmacion = Carbon::now()->toDateTimeString();
                $prCdp->estado_valor = 'Disponible';
            }
            $prCdp->save();
            $prCdp->detalles()->delete();
            $prCdp->detalles()->createMany($request->detalles);
            DB::commit();
            $prCdp->load('dependencia.tercero', 'presupuesto_inicial_gasto.entidadResolucion.tercero', 'detalles.rubro');
//            $prCdp->update($request->except('detalles', 'fecha_confirmacion'));
//            $detallesAEliminar->delete();
            return response()->json([
                'msg' => 'Registro actualizado éxitosamente.',
                'data' => new PrCdpResource($prCdp)
            ], 202);
        } catch (ValidationException $validationException) {
            DB::rollBack();
            return response()->json([
                'msg' => 'Error en la validación',
                'validacion' => $validationException
            ], 422);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'msg' => 'Error al actualizar el registro.',
                'error' => $exception
            ], 500);
        }
    }

    public function destroy($id) {}

    public function restValRpAValCdp ($details)
    {
        foreach ($details as $detail) {
            $idDetStrgasto = $detail['pr_detstrgasto_id'];
            $detstrgasto = PrDetstrgasto::find($idDetStrgasto);
            $valorEjecutado = $detstrgasto->valor_ejecutado;
            $valorIngresado = (double) $detail['valor'];
            $detstrgasto->valor_ejecutado = ($valorEjecutado < $valorIngresado) ? ($valorIngresado - $valorEjecutado) : ($valorEjecutado - $valorIngresado);
            $detstrgasto->save();
//            $detstrgasto->valor_ejecutado = ($valorEjecutado !== 0 || $valorEjecutado !== $valorIngresado) ? $valorEjecutado - $valorIngresado : 0;
//                && $detail['pr_cdp_id'] === $id
        }
    }
    
    public function creActualizaDetalleStrgasto ($detalles) {
        try {
            foreach ($detalles as $detalle) {
                $idDetStrgasto = (int) $detalle['pr_detstrgasto_id'];
                $detstrgasto = PrDetstrgasto::where('id', $idDetStrgasto)->first();
                $valorEjecutado = (double) $detstrgasto->valor_ejecutado;
                $valorIngresado = (double) $detalle['valor'];
                $detstrgasto->valor_ejecutado = ($valorEjecutado !== 0) ? $valorEjecutado + $valorIngresado : $valorIngresado;
                $detstrgasto->save();
//                $detstrgasto->valor_ejecutado = ($valorEjecutado === $valorIngresado && $detalle['pr_cdp_id'] === $id && isset($detalle['id'])) ? $valorIngresado : $valorEjecutado + $valorIngresado;
            }
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception,
                'msg' => 'Error al cambiar de valor en el detalle de la estructura incial de presupuesto de gastos.'
            ], 500);
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
            return response()->json([
                'rubros' => new Resource($rubros),
                'strgasto' => new Resource($pr_strgasto)
            ]);

        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function getCdpsSinMinuta() {
        try {
            $prCdps = PrCdp::sinMinuta()->where('estado','Confirmada')->get();
            $cdps = [];
            foreach ($prCdps as $prCdp) {
                if ($prCdp->valor_cdp !== $prCdp->suma_valores_ejecutados) {
                    array_push($cdps, $prCdp);
                }
            }
            return new Resource($cdps);
        } catch (\Exception $e) {
            return response()->json([
                'msg' => $e->getMessage(),
                'error' => $e
            ], 500);
        }
    }
    public function findRubroForDetcdp ()
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

    public function getPdf () {
        try {
            $id = Input::get('id');
            $prCdp = PrCdp::find($id);

            $prCdp->load([
                'dependencia.tercero',
                'presupuesto_inicial_gasto.entidadResolucion.tercero',
                'detalles.detStrgasto','detalles.tipoGasto', 'detalles.rubro'
            ]);

            if (Input::get('html')) {
                return view('dompdf.CDPReporte');
            }

            setlocale(LC_ALL, "es_ES");
            $pdf = PDF::loadView('dompdf.CDPReporte', ['prCdp' => $prCdp]);
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream('CERTIFICADO DE DISPONIBILIDAD PRESUPUESTAL', ['Attachment' => 0]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
