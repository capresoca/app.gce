<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Repositories\Presupuesto\ObligacioneRepository;
use App\Http\Requests\Presupuesto\PrObligacioneRequest;
use App\Http\Resources\Presupuesto\PrObligacioneResource;
use App\Models\Niif\GnTercero;
use App\Models\Presupuesto\PrCdp;
use App\Models\Presupuesto\PrDetrp;
use App\Models\Presupuesto\PrObligacione;
use App\Models\Presupuesto\PrStrgasto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade as PDF;

class PrObligacioneController extends Controller
{

    public function __construct(ObligacioneRepository $obligacioneRepository)
    {
        $this->obligacioneRepository = $obligacioneRepository;
    }

    public function index()
    {
        if (Input::get('per_page')) {
            $obligaciones = PrObligacione::with('entidadResolucion.tercero','tercero')->pimp()->orderBy('consecutivo', 'desc')->paginate(Input::get('per_page'));
            return PrObligacioneResource::collection($obligaciones);
        }
        return PrObligacioneResource::collection(PrObligacione::with('entidadResolucion.tercero', 'tercero')->pimp()->orderBy('consecutivo', 'desc')->get());
    }


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $obligacionRequest = $request->toArray();
            $this->obligacioneRepository->validar($obligacionRequest);
            $pr_obligacione = $this->obligacioneRepository->guardar($obligacionRequest);
            if ($request['estado'] === 'Confirmada') {
                $this->creActualizaDetalleRp($obligacionRequest['detalles']);
            }
            DB::commit();
            return response()->json([
                'msg' => 'Se ha registrado correctamente la obligación.',
                'data' => new PrObligacioneResource($pr_obligacione)
            ], 201);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'msg' => 'Los datos son invalidos.',
                'errors' => $e->validator
            ], 422);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'msg' => 'Error al registrar la obligación.',
                'error' => $exception
            ], 500);
        }
    }

    public function show(PrObligacione $pr_obligacione)
    {
        return new Resource($pr_obligacione->load('entidadResolucion.tercero', 'tercero', 'detalles.tipoGasto', 'detalles.rubro', 'detalles.cdp', 'detalles.detalle_rp', 'detalles.rp'));
    }


    public function update(PrObligacioneRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $pr_obligacione = PrObligacione::find($id);
            $pr_obligacione->fill($request->except('detalles','fecha_anulacion'));
//            $detallesAEliminar = $pr_obligacione->detalles;
//            $this->restarValObligacionAValRp($detallesAEliminar);
//            if ($request['estado'] !== 'Anulada') {
            if ($request['estado'] === 'Confirmada') {
                $this->creActualizaDetalleRp($request->detalles);
            }
            if ($request['estado'] === 'Anulada') {
                $pr_obligacione->fecha_anulacion = Carbon::now()->format('Y-m-d');
            }
            $pr_obligacione->save();
            $pr_obligacione->detalles()->delete();
            $pr_obligacione->detalles()->createMany($request->detalles);
            DB::commit();
//            $pr_obligacione->update($request->except('detalles'));
            return response()->json([
                'msg' => 'Se ha actualizado correctamente la obligación.',
                'data' => new PrObligacioneResource($pr_obligacione)
            ], 202);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'msg' => 'Los datos son invalidos.',
                'errors' => $e->validator
            ], 422);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'msg' => 'Error al actualizar la obligación.',
                'error' => $exception
            ], 500);
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
            return $periodos ? $periodos : ['msg' => 'No existen periodos.'] ;
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

    public function restarValObligacionAValRp ($details)
    {
        try {
            foreach ($details as $detail) {
                $idDetrp = (int) $detail['pr_detrp_id'];
                $detrp = PrDetrp::where('id', $idDetrp)->first();
                $valorEjecutado = (double) $detrp->valor_ejecutado;
                $valorIngresado = (double) $detail['valor'];
                $detrp->valor_ejecutado = ($valorEjecutado < $valorIngresado) ? ($valorIngresado - $valorEjecutado) : ($valorEjecutado - $valorIngresado);
                $detrp->save();
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

    public function creActualizaDetalleRp ($detalles) {
        try {
//            dd($detalles);
            foreach ($detalles as $detalle) {
                $id = (integer) $detalle['pr_detrp_id'];
                $detrp = PrDetrp::find($id);
                $valorEjecutado = $detrp->valor_ejecutado;
                $valorIngresado = (double) $detalle['valor'];
                $detrp->valor_ejecutado = ($valorEjecutado !== 0) ? $valorEjecutado + $valorIngresado : $valorIngresado;
                $detrp->save();
            }
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception,
                'msg' => 'Error al cambiar el valor en ejecución en el detalle del registro presupuestal.'
            ], 500);
        }
    }


    public function getRubrosDelRp ($id)
    {
        try {
            $detrps = PrDetrp::with('rubro')->where('pr_rp_id',$id)->get();
            $rubros = [];
            foreach ($detrps as $detrp) {
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

    public function getCdpDelRp ($id) {
        try {
            $detrp = PrDetrp::with('detalle_cdp')->where('pr_rp_id', $id)->first();
            $idCdp = (int) $detrp->detalle_cdp->pr_cdp_id;
            $cdp = PrCdp::find($idCdp);

            return response()->json([
                'cdp' => new Resource($cdp)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e,
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function findRubro()
    {
        try {
            $rpId = (integer) Input::get('rpId');
            $rubroId = (integer) Input::get('rubroId');

            $pr_detrp = PrDetrp::where('pr_rp_id', $rpId)->where('pr_rubro_id', $rubroId)->first();
            $pr_detrp->load('tipoGasto', 'rubro');
            return new Resource($pr_detrp);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function getObligacionesDeTercero ($id)
    {
        try {
            $tercero = GnTercero::find($id);
            $tercero->load('obligaciones');
            $obligaciones = [];
            foreach ($tercero->obligaciones as $item) {
                if ($item->estado === 'Confirmada') {
                    array_push($obligaciones, $item);
                }
            }
            return response()->json([
                'obligaciones' => new Resource($obligaciones)
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
            $prObligacion = PrObligacione::find($id);

            $prObligacion->load([
                'entidadResolucion.tercero', 'tercero', 'detalles.tipoGasto', 'detalles.rubro', 'detalles.cdp.dependencia', 'detalles.detalle_rp', 'detalles.rp'
            ]);

            if (Input::get('html')) {
                return view('dompdf.ObligacionReporte');
            }
            setlocale(LC_ALL, "es_ES");
            $pdf = PDF::loadView('dompdf.ObligacionReporte', ['prObligacion' => $prObligacion]);
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream('OBLIGACIÓN PRESUPUESTAL', ['Attachment' => 0]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}