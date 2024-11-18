<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Http\Requests\Presupuesto\StrgastoRequest;
use App\Http\Resources\Presupuesto\StrgastoResource;
use App\Models\Presupuesto\PrConcepto;
use App\Models\Presupuesto\PrDetstrgasto;
use App\Models\Presupuesto\PrEntidadResolucione;
use App\Models\Presupuesto\PrStrgasto;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class StrgastoController extends Controller
{

    public function index(){
        if(Input::get('per_page')){
            return StrgastoResource::collection(
                PrStrgasto::with(
                    'entidadResolucion.tercero',
                    'usuarioConfirma',
                    'usuario',
                    'detalles.rubro',
                    'detalles.tipoGasto')
                    ->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return StrgastoResource::collection(PrStrgasto::with(
            'entidadResolucion.tercero',
            'usuarioConfirma',
            'usuario',
            'detalles.rubro',
            'detalles.tipoGasto')
            ->pimp()
            ->orderBy('updated_at','desc')
            ->get());
    }

    public function store(StrgastoRequest $request) {
        try {
            DB::beginTransaction();
            $pr_strgasto = new PrStrgasto();
            $pr_strgasto->fill($request->except('fecha', 'detalles'));
            if ($request['estado'] === 'Registrada') {
                $pr_strgasto->fecha = Carbon::now()->toDateTimeString();
            }
            $pr_strgasto->save();
            $pr_strgasto->detalles()->createMany($request->detalles);
            $pr_strgasto->load('entidadResolucion.tercero', 'usuarioConfirma', 'usuario','detalles.rubro','detalles.tipoGasto');
            DB::commit();
            return response()->json(
                [
                    'message' => 'Se ha registrado correctamente.',
                    'data' => new StrgastoResource($pr_strgasto)
                ], 201
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'msg' => 'Se ha presentado un error al crear.',
                    'error' => $e
                ], 500
            );
        }
    }


    public function show(PrStrgasto $pr_strgasto)
    {
        return new Resource($pr_strgasto->load('entidadResolucion.tercero', 'usuario','detalles.rubro','detalles.tipoGasto'));
    }


    public function update(StrgastoRequest $request, PrStrgasto $pr_strgasto)
    {
        try {
            DB::beginTransaction();
            $pr_strgasto->update($request->except('fecha_confirmacion', 'gs_usuario_conf_id', 'detalles'));
            if ($request->estado === 'Confirmada') {
                $pr_strgasto->fecha_confirmacion = Carbon::now()->toDateTimeString();
                $pr_strgasto->gs_usuario_conf_id = Auth::user()->id;
            }
            $pr_strgasto->detalles()->delete();
            $pr_strgasto->detalles()->createMany($request->detalles);
            $pr_strgasto->load('entidadResolucion.tercero', 'usuario', 'usuarioConfirma','detalles.rubro','detalles.tipoGasto');
            DB::commit();
            return response()->json(
                [
                    'message' => 'Se ha registrado correctamente.',
                    'data' => new StrgastoResource($pr_strgasto)
                ], 202
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'msg' => 'Se ha presentado un error al actualizar.',
                    'error' => $e
                ], 500
            );
        }
    }

    public function getPeriodos () {
        $pr_entidades = PrEntidadResolucione::whereDoesntHave('presupuestoGasto', function ($query) {
            $query->where('estado', 'Confirmada')->orWhere('estado', 'Registrada');
        })->get();
        $periodos = [];
        foreach ($pr_entidades as $item) {
            array_push($periodos, $item->periodo);
        }
        return $periodos;
    }

    public function findByPeriodo ($periodo)
    {
        try {
            $pr_entidades = PrEntidadResolucione::where('periodo', $periodo)->first();
            $pr_entidades->load('tercero', 'presupuestoGasto.detalles.rubro', 'presupuestoGasto.detalles.tipoGasto');
            if ($pr_entidades) {
                return new Resource($pr_entidades);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception,
                'msg' => $exception->getMessage()
            ], 500);
        }
    }

    public function getInformePdf ()
    {
        try {
            $id = Input::get('id');
            $pr_strgasto = PrStrgasto::find($id);
            $pr_strgasto->load(['entidadResolucion']);
            $threes = $this->arbolDeGastos(1, 'Gastos', $id);
            if (Input::get('html')) {
                return view('dompdf.reporte_presupuesto_gastos');
            }
            $pdf = PDF::loadView('dompdf.reporte_presupuesto_gastos', ['strgasto' => $pr_strgasto, 'detalles' => $threes]);
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream('Presupuesto de Gastos. '. $pr_strgasto->periodo ,['Attachment' => 0]);

        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception,
                'msg' => 'Error al Imprimir el archivo (Revise la configuración del navegador.)'
            ], 500);
        }
    }

    public function arbolDeGastos ($estructuraId, $tipoRubro, $strGastoId) {
        $arbolito = PrConcepto::with('presupuesto.tipoGasto')->where('pr_estructura_id', $estructuraId)->where('tipo_rubro', $tipoRubro)->get();
        foreach ($arbolito as $value){
            $idsRubros = PrConcepto::where('codigo', 'LIKE', $value->codigo.'%')->where('pr_estructura_id', $estructuraId)->pluck('id');
            $gastos = PrDetstrgasto::with('tipoGasto','rubro')->where('pr_strgasto_id', $strGastoId)->whereIn('pr_rubro_id', $idsRubros)->sum('valor_inicial');
            $value['total'] = $gastos;
        }
        return $arbolito;
//        return response()->json($arbolito);
    }
}
