<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Requests\Presupuesto\StringresoRequest;
use App\Http\Resources\Presupuesto\StringresoResource;
use App\Models\Presupuesto\PrConcepto;
use App\Models\Presupuesto\PrDetstringreso;
use App\Models\Presupuesto\PrEntidadResolucione;
use App\Models\Presupuesto\PrStringreso;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class StringresoController extends Controller
{

    public function index(){
        if(Input::get('per_page')){
            return StringresoResource::collection(
                PrStringreso::with(
                    'entidadResolucion.tercero',
                    'usuarioConfirma',
                    'usuario',
                    'detalles.rubro',
                    'detalles.tipoIngreso')
                    ->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return StringresoResource::collection(PrStringreso::with(
            'entidadResolucion.tercero',
            'usuarioConfirma',
            'usuario',
            'detalles.rubro',
            'detalles.tipoIngreso')
            ->pimp()
            ->orderBy('updated_at','desc')
            ->get());
    }


    public function store(StringresoRequest $request) {
        try {
            DB::beginTransaction();
            $pr_stringreso = PrStringreso::create($request->except('fecha', 'detalles'));
            $pr_stringreso->detalles()->createMany($request->detalles);
            if ($request['estado'] === 'Registrada') {
                $pr_stringreso->fecha = Carbon::now()->toDateTimeString();
            }
            $pr_stringreso->load('entidadResolucion.tercero', 'usuarioConfirma', 'usuario','detalles.rubro','detalles.tipoIngreso');
            DB::commit();
            return response()->json(
                [
                    'message' => 'Se ha registrado correctamente.',
                    'data' => new StringresoResource($pr_stringreso)
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

    public function show(PrStringreso $pr_stringreso)
    {
        return new Resource($pr_stringreso->load('entidadResolucion.tercero', 'usuarioConfirma', 'usuario','detalles.rubro','detalles.tipoIngreso'));
    }

    public function update(StringresoRequest $request, PrStringreso $pr_stringreso)
    {
        try {
            DB::beginTransaction();
            $pr_stringreso->update($request->except('fecha_confirmacion', 'gs_usuario_conf_id', 'detalles'));
            $pr_stringreso->detalles()->delete();
            $pr_stringreso->detalles()->createMany($request->detalles);
            if ($request['estado'] === 'Confirmada') {
                $pr_stringreso->fecha_confirmacion = Carbon::now()->toDateTimeString();
                $pr_stringreso->gs_usuario_conf_id = Auth::user()->id;
            }
            $pr_stringreso->load('entidadResolucion.tercero', 'usuarioConfirma', 'usuario','detalles.rubro','detalles.tipoIngreso');
            DB::commit();
            return response()->json(
                [
                    'message' => 'Se ha registrado correctamente.',
                    'data' => new StringresoResource($pr_stringreso)
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

    public function destroy($id) {}

    public function getPeriodos () {
        $pr_entidades = PrEntidadResolucione::whereDoesntHave('presupuestoIngreso', function ($query) {
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
            $pr_entidades->load('tercero', 'presupuestoIngreso.detalles.rubro', 'presupuestoIngreso.detalles.tipoIngreso');
            if ($pr_entidades) {
                return new StringresoResource($pr_entidades);
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
            $pr_stringreso = PrStringreso::find($id);
            $pr_stringreso->load(['entidadResolucion']);
            $threes = $this->arbolDeIngresos(1, 'Ingresos', $id);
            if (Input::get('html')) {
                return view('dompdf.reporte_presupuesto_ingresos');
            }
            $pdf = PDF::loadView('dompdf.reporte_presupuesto_ingresos', ['stringreso' => $pr_stringreso, 'detalles' => $threes]);
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream('Presupuesto de Ingresos. '. $pr_stringreso->periodo ,['Attachment' => 0]);

        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception,
                'msg' => 'Error al Imprimir el archivo (Revise la configuración del navegador.)'
            ], 500);
        }
    }

    public function arbolDeIngresos ($estructuraId, $tipoRubro, $strIngresoId) {
        $arbolito = PrConcepto::with('presupuestoIngresos.tipoIngreso')->where('pr_estructura_id', $estructuraId)->where('tipo_rubro', $tipoRubro)->get();
        foreach ( $arbolito as $value){
            $idsRubros = PrConcepto::where('codigo', 'LIKE', $value->codigo.'%')->where('pr_estructura_id', $estructuraId)->pluck('id');
            $ingresos = PrDetstringreso::with('tipoIngreso','rubro')->where('pr_stringreso_id', $strIngresoId)->whereIn('pr_rubro_id', $idsRubros)->sum('valor_inicial');
            $value['total'] = $ingresos;
        }
        return $arbolito;
//        return response()->json($arbolito);
    }
}
