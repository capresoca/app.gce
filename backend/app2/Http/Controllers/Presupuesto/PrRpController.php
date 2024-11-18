<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Requests\Presupuesto\PrRpRequest;
use App\Http\Resources\Presupuesto\PrRpResource;
use App\Models\ContratacionEstatal\CeProconminuta;
use App\Models\Niif\GnTercero;
use App\Models\Presupuesto\PrCdp;
use App\Models\Presupuesto\PrDetcdp;
use App\Models\Presupuesto\PrEntidadResolucione;
use App\Models\Presupuesto\PrRp;
use App\Models\Presupuesto\PrStrgasto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade as PDF;

class PrRpController extends Controller
{

    public function index()
    {

        if (Input::get('per_page')) {
            $rps = PrRp::with('minuta','cdp.dependencia', 'presupuestoInicialGasto.entidadResolucion.tercero', 'tercero')
                ->pimp()
                ->orderBy('consecutivo', 'desc')
                ->paginate(Input::get('per_page'));
            return PrRpResource::collection($rps);
        }
        return PrRpResource::collection(
            PrRp::with('minuta','cdp.dependencia', 'presupuestoInicialGasto.entidadResolucion.tercero', 'tercero')
                ->pimp()
                ->orderBy('consecutivo', 'desc')
                ->get()
        );
    }

    public function store(PrRpRequest $request)
    {
        try {
            DB::beginTransaction();
            $pr_rp = PrRp::create($request->except('detalles'));
            if ($request['estado'] === 'Confirmado') {
                $this->creActualizaDetalleCdp($request->detalles);
            }
            $pr_rp->detalles()->createMany($request->detalles);
            $pr_rp->load('minuta.cdp','cdp.dependencia','tercero','presupuestoInicialGasto','detalles.detalle_cdp','detalles.rubro', 'detalles.tipoGasto');
            DB::commit();
            return response()->json([
                'msg' => 'Registro creado éxitosamente.',
                'data' => new PrRpResource($pr_rp)
            ], 201);
        } catch (ValidationException $validationException) {
            DB::rollBack();
            return response()->json([
                'msg' => 'Error en la validación',
                'validacion' => $validationException
            ], 422);
        }catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'msg' => 'Se ha presentado un error al crear.',
                'error' => $exception
            ], 500);
        }
    }

    public function show(PrRp $pr_rp)
    {
        return new PrRpResource($pr_rp->load('minuta.cdp.dependencia','cdp.dependencia','tercero','entidadResolucion','detalles.detalle_cdp','detalles.rubro','detalles.tipoGasto'));
    }

    public function update(PrRpRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $pr_rp = PrRp::find($id);
            $pr_rp->fill($request->except('detalles','fecha_anulacion'));
//            $detallesAEliminar = $pr_rp->detalles;
//            $this->restarValCdpAValStr($detallesAEliminar);
            if ($request['estado'] === 'Confirmado') {
                $this->creActualizaDetalleCdp($request->detalles);
            }
            if ($request['estado'] === 'Anulado') {
                $pr_rp->fecha_anulacion = Carbon::now()->format('Y-m-d');
            }
            $pr_rp->save();
            $pr_rp->detalles()->delete();
            $pr_rp->detalles()->createMany($request->detalles);
            $pr_rp->load('minuta.cdp','cdp.dependencia','tercero','presupuestoInicialGasto','detalles.detalle_cdp','detalles.rubro');
            DB::commit();
//            $pr_rp->update($request->except('detalles'));
            return response()->json([
                'msg' => 'Registro actualizado éxitosamente.',
                'data' => new PrRpResource($pr_rp)
            ], 202);
        } catch (ValidationException $validationException) {
            return response()->json([
                'msg' => 'Error en la validación',
                'validacion' => $validationException
            ], 422);
        } catch (\Exception $exception) {
            return response()->json([
                'msg' => 'Error al actualizar el registro.',
                'error' => $exception
            ], 500);
        }
    }

    public function destroy($id) {}

    public function restarValCdpAValStr ($details)
    {
        try {
            foreach ($details as $detail) {
                $iddetcdp = (int) $detail['pr_detcdp_id'];
                $detcdp = PrDetcdp::where('id', $iddetcdp)->first();
                $valorEjecutado = (double) $detcdp->valor_ejecutado;
                $valorIngresado = (double) $detail['valor'];
                $detcdp->valor_ejecutado = ($valorEjecutado < $valorIngresado) ? ($valorIngresado - $valorEjecutado) : ($valorEjecutado - $valorIngresado);
                $detcdp->save();
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


    public function creActualizaDetalleCdp ($detalles) {
        try {
            foreach ($detalles as $detalle) {
                $detcdp = PrDetcdp::find($detalle['pr_detcdp_id']);
                $valorEjecutado = $detcdp->valor_ejecutado;
                $valorIngresado = (double) $detalle['valor'];
                $detcdp->valor_ejecutado = ($valorEjecutado !== 0) ? $valorEjecutado + $valorIngresado : $valorIngresado;
//                $detcdp->valor_ejecutado = $valorEjecutado + $valorIngresado;
                $detcdp->save();
                $this->cambiarEstadoValorCdp($detcdp->pr_cdp_id);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception,
                'msg' => 'Error al cambiar el valor en ejecución en el detalle del cdp.'
            ], 500);
        }
    }

    public function cambiarEstadoValorCdp ($id)
    {
        $prCdp = PrCdp::find($id);
        if ($prCdp->suma_valores_ejecutados === $prCdp->valor_cdp) {
            $prCdp->estado_valor = 'Comprometido Total';
        } elseif ($prCdp->suma_valores_ejecutados > 0 && $prCdp->suma_valores_ejecutados < $prCdp->valor_cdp) {
            $prCdp->estado_valor = 'Comprometido Parcialmente';
        }
        $prCdp->save();
    }

    public function getEntidadResolucionWithCdp () {
        try {
            $entidades = PrStrgasto::where('periodo', '>=', Carbon::now()->format('Y'))
                ->where('estado', 'Confirmada')->get();
            $periodos = [];
            foreach ($entidades as $item) {
                array_push($periodos, $item->periodo);
            }
            return $periodos ? $periodos : ['msg' => 'No existen periodos.'] ;
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function findByPeriodo ($periodo) {
        try {
            $pr_strgasto = PrStrgasto::where('periodo', $periodo)->first();
            $pr_strgasto->load('entidadResolucion.tercero');
//            $pr_cdp = PrCdp::where('periodo', $periodo)->first();
//            $pr_cdp->load('dependencia','presupuesto_inicial_gasto.entidadResolucion','detalles.rubro','detalles.tipoGasto');
            return response()->json([
                'data' => new Resource($pr_strgasto)
            ]);
        }catch (\Exception $exception) {
            return $exception;
        }
    }

    public function contratosSinRpAndConCdp ()
    {
        try {
            $ceproconminutas = CeProconminuta::ConCdp()->with('cdp')->get();
            $minutas = [];
            foreach ($ceproconminutas as $ceproconminuta) {
                if ($ceproconminuta->cdp->valor_cdp !== $ceproconminuta->cdp->suma_valores_ejecutados) {
                    array_push($minutas, $ceproconminuta);
                }
            }
            return response()->json([
                'data' => new Resource($minutas)
            ], 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function rubrosOfCdp ($id) {
        try {
            $pr_cdp = PrCdp::find($id);
            $pr_cdp->load('detalles.rubro', 'detalles.tipoGasto');
            $detalles_cdp = [];
            foreach ($pr_cdp->detalles as $detalle) {
                array_push($detalles_cdp, $detalle->rubro);
            }
            return response()->json([
                'rubros' => new Resource($detalles_cdp),
                'detalles' => new Resource($pr_cdp->detalles)
            ],200);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function findRubro()
    {
        try {
            $cdpId = (int) Input::get('cdpId');
            $rubroId = (int) Input::get('rubroId');

            $pr_detstrgasto = PrDetcdp::where('pr_cdp_id', $cdpId)->where('pr_rubro_id', $rubroId)->first();

            $pr_detstrgasto->load('tipoGasto', 'rubro');
            return new Resource($pr_detstrgasto);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function getRpsDeTercero ($id)
    {
        try {
            $tercero = GnTercero::find($id);
            $tercero->load('rps');
            $resgistros_presupuestales = [];
            foreach ($tercero->rps as $item) {
                array_push($resgistros_presupuestales, $item);
            }
            if ($resgistros_presupuestales === []) {
                return response()->json([
                    'exists' => false,
                    'msg' => 'El tercero no cuenta con registros presupuestales.'
                ]);
            }
            return response()->json([
                'exists' => true,
                'rps' => new Resource($resgistros_presupuestales)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function getPdf () {
        try {
            $id = Input::get('id');
            $pr_rp = PrRp::find($id);
            $pr_rp->load('minuta.cdp.dependencia','cdp.dependencia','tercero','entidadResolucion','detalles.detalle_cdp','detalles.rubro','detalles.tipoGasto');

            if (Input::get('html')) {
                return view('dompdf.RPReporte');
            }
            setlocale(LC_ALL, "es_ES");
            $pdf = PDF::loadView('dompdf.RPReporte', ['prRp' => $pr_rp]);
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream('REGISTRO PRESUPUESTAL', ['Attachment' => 0]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
