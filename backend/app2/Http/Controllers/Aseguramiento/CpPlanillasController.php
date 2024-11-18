<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Compensacion\CpAporte;
use App\Compensacion\CpLiquidacione;
use App\Compensacion\CpPlanilla;
use App\Http\Requests\Aseguramiento\CpPlanillasRequest;
use App\Models\Aseguramiento\AsAfiliadoPagador;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CpPlanillasController extends Controller
{

    public function index() {}

    public function store(CpPlanillasRequest $request){
        try {
            $cp_aporte = new CpAporte();
            $cp_aporte->fill($request->except('gs_usuario_id','planilla_id'));
            $cp_aporte->gs_usuario_id = Auth::user()->id;
//                $cp_aporte->planilla_id = $request->planilla_id;
            $planilla = $this->crearPlanilla($request);
            $cp_aporte->planilla_id = $planilla->id;
            $cp_aporte->save();
            $cp_aporte->load('planilla', 'liquidacion','usuario');
            $this->actualizarLiquidacion($request);
            return response()->json([
                'data' => new Resource($cp_aporte),
                'message' => 'El aporte se ha registrado correctamente.'
            ],201);
        } catch (ValidationException $validationException) {
            return $validationException->getMessage();
        } catch (\Exception $e) {
            return response()->json([
                'e' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id){}

    public function update(Request $request, $id) {}

    public function destroy($id) {}

    public function findByNumeroPlanilla ($numero) {
        try {
            $cp_planilla = CpPlanilla::where('numero', $numero)->first();
            if ($cp_planilla) {
                return response()->json([
                    'exists' => true,
                    'msg' => 'El número de planilla ya esta registrado ¿Desea Utilizarlo?',
                    'data' => new Resource($cp_planilla)
                ], 200);
            }
            return response()->json([
                'exists' => false,
                'msg' => 'El número de planilla no existe ¿Desea crearlo?'
            ], 200);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function crearPlanilla ($request)
    {
        if (!isset($request->planilla_id)) {
            $cp_planilla = new CpPlanilla();
//            dd($request);
//            dd($request['planilla']['aportante_id']);
            $cp_planilla->aportante_id = $request['planilla']['aportante_id'];
            $cp_planilla->numero = $request['planilla']['numero'];
            $cp_planilla->periodo = $request['planilla']['periodo'];
            $cp_planilla->registros = 1;
            $cp_planilla->gs_usuario_id = Auth::user()->id;
        } else {
            $cp_planilla = CpPlanilla::find($request->planilla_id);
            $registros = $cp_planilla->registros;
            $cp_planilla->registros = $registros + 1;
        }
        $cp_planilla->save();
        return $cp_planilla;
    }

    public function actualizarLiquidacion ($request)
    {
        $id = (int) $request->liquidacion_id;
        $liquidacion = CpLiquidacione::find($id);
        $diasRegistrados = $liquidacion->dias_pagados;
        $liquidacion->dias_pagados = $diasRegistrados + $request->dias;
        if ($request->retiro === 1) {
            $liquidacion->retiro = $request->retiro;
        }
        if ($request->ingreso === 1) {
            $liquidacion->ingreso = $request->ingreso;
        }
        $liquidacion->save();
        if ($request->retiro === 1) {
            $this->depurarRetiros($liquidacion->relacion_laboral_id);
        }
    }

    public function depurarRetiros ($id) {
        $afectados = 0;
        $relaboral = AsAfiliadoPagador::find($id);

//        if(!$relaboral) continue;

        $liquidacionesRetiro = $relaboral->liquidaciones_unorder()->whereRetiro(1)->orderBy('periodo')->get();

        foreach ( $liquidacionesRetiro as  $liquidacionRetiro) {

            $periodoRetiroCarbon = Carbon::parse($liquidacionRetiro->periodo);
            $aporteRetiro = $liquidacionRetiro->aportes()->whereRetiro(1)->orderBy('fecha_pago','desc')->first();

            if(!$aporteRetiro) continue;

            $fechaPagoLiquidacionRetiro = $aporteRetiro->forzar_retiro === 0 ? Carbon::parse($aporteRetiro->fecha_pago) : Carbon::parse($aporteRetiro->liquidacion->periodo);

            $periodoCorte = $liquidacionRetiro->periodo;

            $periodoRetiroCarbonMasUnMes = clone $periodoRetiroCarbon;
            $periodoRetiroCarbonMasUnMes->addMonth()->lastOfMonth();


            if($fechaPagoLiquidacionRetiro->greaterThan($periodoRetiroCarbonMasUnMes) ) {
                $periodoCorte = $fechaPagoLiquidacionRetiro->format('Y-m');
            }

            $liquidacionesPostRetiro = $relaboral->liquidaciones_unorder()->where('periodo', '>', $periodoCorte)->orderBy('periodo')->get();


            $estadoRelaboral = 'Inactivo';

            foreach ($liquidacionesPostRetiro as $liquidacionPostRetiro){
                if($liquidacionPostRetiro->ingreso || $liquidacionPostRetiro->dias_pagados > 0){
                    $estadoRelaboral = 'Activo';
                    break;
                };

                $liquidacionPostRetiro->delete();
                $afectados ++;
            }

            $relaboral->estado = $estadoRelaboral;
            $relaboral->save();
        }
    }
}
