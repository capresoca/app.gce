<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Requests\Presupuesto\OrdenesDePagoRequest;
use App\Http\Resources\Presupuesto\OrdenesDePagoResource;
use App\Models\Presupuesto\PrCdp;
use App\Models\Presupuesto\PrDetobligacione;
use App\Models\Presupuesto\PrDetrp;
use App\Models\Presupuesto\PrOrdenesDePago;
use App\Models\Presupuesto\PrRp;
use App\Models\Presupuesto\PrStrgasto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\ValidationException;

class OrdenesDePagoController extends Controller
{

    public function index()
    {
        if (Input::get('per_page')) {
            $prOrdenDePagos = PrOrdenesDePago::with('entidadResolucion.tercero','tercero')->pimp()->orderBy('consecutivo', 'desc')->paginate(Input::get('per_page'));
            return OrdenesDePagoResource::collection($prOrdenDePagos);
        }
        return OrdenesDePagoResource::collection(PrOrdenesDePago::with('entidadResolucion.tercero', 'tercero')->pimp()->orderBy('consecutivo', 'desc')->get());

    }

    public function store(OrdenesDePagoRequest $request)
    {
        try {
            DB::beginTransaction();
            $pr_orden_de_pago = PrOrdenesDePago::create($request->except('detalles'));
            if ($request['estado'] === 'Confirmada') {
                $this->creActualizaDetalleDeObligacion($request->detalles);
            }
            $pr_orden_de_pago->detalles()->createMany($request->detalles);
            DB::commit();
            $pr_orden_de_pago->load('entidadResolucion.tercero','tercero');
            return response()->json([
                'msg' => 'Se ha registrado éxitosamente la orden de pago.',
                'data' => new OrdenesDePagoResource($pr_orden_de_pago)
            ],201);
        } catch (ValidationException $validationException) {
            return response()->json([
                'msg' => 'Error en la validación',
                'error' => $validationException
            ], 422);
        } catch (\Exception $exception) {
            return response()->json([
                'msg' => 'Se ha presentado un error en el servidor',
                'error' => $exception
            ],500);
        }
    }

    public function show(PrOrdenesDePago $pr_orden_de_pago)
    {
        return new OrdenesDePagoResource($pr_orden_de_pago->load('tercero', 'entidadResolucion.tercero', 'detalles.tipoGasto', 'detalles.rp', 'detalles.cdp', 'detalles.obligacion', 'detalles.detalle_obligacion', 'detalles.rubro'));
    }

    public function update(OrdenesDePagoRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $pr_orden_de_pago = PrOrdenesDePago::find($id);
            $pr_orden_de_pago->fill($request->except('detalles','fecha_anulacion'));
//            $detallesAEliminar = $pr_orden_de_pago->detalles;
//            $this->restarValObligacioneAValOrdenpago($detallesAEliminar);
//            if ($request['estado'] !== 'Anulada') {
            if ($request['estado'] === 'Confirmada') {
                $this->creActualizaDetalleDeObligacion($request->detalles);
            }
            if ($request['estado'] === 'Anulada') {
                $pr_orden_de_pago->fecha_anulacion = Carbon::now()->format('Y-m-d');
            }
            $pr_orden_de_pago->save();
            $pr_orden_de_pago->detalles()->delete();
            $pr_orden_de_pago->detalles()->createMany($request->detalles);
            DB::commit();
            $pr_orden_de_pago->load('entidadResolucion.tercero','tercero');
            //            $pr_orden_de_pago->update($request->except('detalles'));
            return response()->json([
                'msg' => 'Se ha actualizado éxitosamente la orden de pago.',
                'data' => new OrdenesDePagoResource($pr_orden_de_pago)
            ],202);
        } catch (ValidationException $validationException) {
            return response()->json([
                'msg' => 'Error en la validación',
                'error' => $validationException
            ], 422);
        } catch (\Exception $exception) {
            return response()->json([
                'msg' => 'Se ha presentado un error en el servidor',
                'error' => $exception
            ],500);
        }
    }

    public function destroy($id) {}

    public function getPeriodos () {
        try {
            $entidades = PrStrgasto::where('periodo', '>=', Carbon::now()->format('Y'))
                ->where('estado', 'Confirmada')->get();
            $periodos = [];
            foreach ($entidades as $item) {
                array_push($periodos, $item->periodo);
            }
            return response()->json([
                'periodos' => $periodos ? $periodos : ['msg' => 'No existen periodos.']
            ], 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function findByPeriodo ($periodo) {
        try {
            $pr_strgasto = PrStrgasto::where('periodo', $periodo)->first();
            $pr_strgasto->load('entidadResolucion.tercero');
            return response()->json([
                'strgasto' => new Resource($pr_strgasto)
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception
            ], 500);
        }
    }

    public function restarValObligacioneAValOrdenpago ($details)
    {
        try {
            foreach ($details as $detail) {
                $idDetobligacione = (int) $detail['pr_detobligacion_id'];
                $detobligacione = PrDetobligacione::where('id', $idDetobligacione)->first();
                $valorEjecutado = (double) $detobligacione->valor_ejecutado;
                $valorIngresado = (double) $detail['valor'];
                $detobligacione->valor_ejecutado = ($valorEjecutado < $valorIngresado) ? ($valorIngresado - $valorEjecutado) : ($valorEjecutado - $valorIngresado);
                $detobligacione->save();
//            $detstrgasto->valor_ejecutado = ($valorEjecutado !== 0 || $valorEjecutado !== $valorIngresado) ? $valorEjecutado - $valorIngresado : 0;
//                && $detail['pr_cdp_id'] === $id
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e,
                'msg' => 'Error en el server, ' . $e->getMessage()
            ],500);
        }
    }

    public function creActualizaDetalleDeObligacion ($detalles) {
        try {
//            dd($detalles);
            foreach ($detalles as $detalle) {
                $idDetObligacion = (int) $detalle['pr_detobligacion_id'];
                $detcdp = PrDetobligacione::find($idDetObligacion);
                $valorEjecutado = $detcdp->valor_ejecutado;
                $valorIngresado = (double) $detalle['valor'];
                $detcdp->valor_ejecutado = ($valorEjecutado !== 0) ? $valorEjecutado + $valorIngresado : $valorIngresado;
//                $detcdp->valor_ejecutado = $valorEjecutado + $valorIngresado;
                $detcdp->save();
            }
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception,
                'msg' => 'Error al cambiar el valor en ejecución en el detalle de la obligación.'
            ], 500);
        }
    }

    public function getRubrosDeObligacion ($id)
    {
        try {
            $detobligaciones = PrDetobligacione::with('rubro')->where('pr_obligacion_id',$id)->get();
            $rubros = [];
            foreach ($detobligaciones as $detrp) {
                array_push($rubros, $detrp->rubro);
            }

            return response()->json([
                'rubros' => new Resource($rubros)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function getRpAndCdpDeObligacion ($id)
    {
        try {
            $detobligacione = PrDetobligacione::with('detalle_rp')->where('pr_obligacion_id',$id)->first();
            $idRp = (int) $detobligacione->detalle_rp->pr_rp_id;
            $rp = PrRp::find($idRp);
            $idDetalleCdp = (int) $detobligacione->detalle_rp->pr_detcdp_id;
            $detrp = PrDetrp::with('detalle_cdp')->where('pr_detcdp_id',$idDetalleCdp)->first();
            $idCdp = (int) $detrp->detalle_cdp->pr_cdp_id;
            $cdp = PrCdp::find($idCdp);
            return response()->json([
                'cdp' => new Resource($cdp),
                'rp' => new Resource($rp)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function findRubroForDetobligacione ()
    {
        try {
            $obligacionId = (integer) Input::get('obligacionId');
            $rubroId = (integer) Input::get('rubroId');

            $pr_detstrgasto = PrDetobligacione::where('pr_obligacion_id', $obligacionId)->where('pr_rubro_id', $rubroId)->first();
            $pr_detstrgasto->load('tipoGasto', 'rubro');
            return new Resource($pr_detstrgasto);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

}
