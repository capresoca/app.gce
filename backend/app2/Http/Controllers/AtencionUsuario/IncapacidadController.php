<?php

namespace App\Http\Controllers\AtencionUsuario;

use App\Capresoca\Compensacion\EstadoRelacionLaboral;
use App\Capresoca\Incapacidades\Liquidador;
use App\Http\Requests\AtencionUsuario\IncapacidadRequest;
use App\Http\Resources\AtencionUsuario\IncapacidadesResource;
use App\Models\AtencionUsuario\AuIncapacidade;
use App\Models\AtencionUsuario\AuTipincapacidade;
use App\Models\Presupuesto\PrDetstrgasto;
use App\Models\Presupuesto\PrSolicitudCp;
use App\Models\Presupuesto\PrStrgasto;
use App\Models\RedServicios\RsPortabilidade;
use App\Models\RedServicios\RsSalminimo;
use App\Traits\ArchivoTrait;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Barryvdh\DomPDF\Facade as PDF;

class IncapacidadController extends Controller
{

    public function index()
    {
        if(Input::get('per_page')){
            return  IncapacidadesResource::collection(
                AuIncapacidade::with('afiliado')->pimp()
                    ->orderBy('consecutivo','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return IncapacidadesResource::collection(
            AuIncapacidade::with('afiliado')->pimp()->orderBy('fecha_inicio','desc')->get()
        );
    }

    public function store(IncapacidadRequest $request)
    {
        $except = [
            'histclinica_id',
            'certbanco_id',
            'archivo_identificacion_id',
            'pr_solicitud_cp_id'
        ];

        $incapacidad = new AuIncapacidade();

        if($request->incapacidad_id=='null' || !$request->incapacidad_id){
            array_push($except,'incapacidad_id');
        }else{
            $incapacidad_anterior = AuIncapacidade::findOrFail($request->incapacidad_id);
            $incapacidad->dias_incapacidad_total += $incapacidad_anterior->dias_incapacidad_total;
        }

        $incapacidad->fill($request->except($except));

        $incapacidad->fecha_fin = Carbon::parse($request->fecha_inicio)->addDays($request->dias_incapacidad - 1)
                                    ->toDateString();

        $incapacidad->dias_incapacidad_total = $request->dias_incapacidad;

        $incapacidad->fecha = today()->toDateString();
        $incapacidad->estado = 'Registrada';

        if($request->dias_prematuro){
            $incapacidad->dias_incapacidad_total += $request->dias_prematuro;
        }

        $incapacidad->save();
        $this->subirAnexos($incapacidad,$request);
        $incapacidad->relaciones();
        return response([
            'incapacidad' => $incapacidad,
            'incapacidad_o' => new IncapacidadesResource($incapacidad)
        ],201);
    }

    public function update(IncapacidadRequest $request, AuIncapacidade $incapacidade)
    {
        $except = [
            'histclinica_id',
            'certbanco_id',
            'archivo_identificacion_id',
            'pr_solicitud_cp_id'
        ];
        if($request->incapacidad_id=='null' || !$request->incapacidad_id){
            array_push($except,'incapacidad_id');
        }else{
            $incapacidad_anterior = AuIncapacidade::findOrFail($request->incapacidad_id);
            $incapacidade->dias_incapacidad_total += $incapacidad_anterior->dias_incapacidad_total;
        }

        $incapacidade->update(
            $request->except(
                $except
            )
        );

        $incapacidade->fecha_fin = Carbon::parse($request->fecha_inicio)->addDays($request->dias_incapacidad - 1)
            ->toDateString();

        $incapacidade->dias_incapacidad_total = $request->dias_incapacidad;

        if ($request->estado === 'Aprobada') {
            $solicitud_cdp = $this->solicitudCdp($incapacidade);
            $incapacidade->pr_solicitud_cp_id = $solicitud_cdp->id;
        }

        $incapacidade->save();
        $this->subirAnexos($incapacidade,$request);
        $incapacidade->relaciones();

        return response([
            'incapacidad' => $incapacidade,
            'incapacidad_o' => new IncapacidadesResource($incapacidade)
        ],202);
    }

    public function show(AuIncapacidade $incapacidade)
    {
        $incapacidade->relaciones();

        $estado = new EstadoRelacionLaboral($incapacidade);

        $estadoPeriodo = $estado->liquidacion($incapacidade);

        return response()->json([
            'estado_relacion_laboral' => $estadoPeriodo,
            'incapacidad' => $incapacidade
        ],200);

    }

    public function subirAnexos(AuIncapacidade $incapacidad, Request $request)
    {
        $mes = Carbon::parse($incapacidad->fecha)->format('Ym');
        $path_storage = 'AtencionUsuario/Incapacidades/'.$mes;

        if($request->hasFile('histclinica')){
            $archivo_histclinica = ArchivoTrait::subirArchivo($request->histclinica, $path_storage);
            $incapacidad->histclinica_id = $archivo_histclinica->id;
        }

        if($request->hasFile('incapacidad')){
            $archivo_incapacidad = ArchivoTrait::subirArchivo($request->incapacidad, $path_storage);
            $incapacidad->archivo_incapacidad_id = $archivo_incapacidad->id;
        }

        if($request->hasFile('certbanco')){

            $certbanco = ArchivoTrait::subirArchivo($request->certbanco, $path_storage);
            $incapacidad->certbanco_id = $certbanco->id;
        }

        if($request->hasFile('archivo_identificacion')){
            $archivo_identificacion = ArchivoTrait::subirArchivo($request->archivo_identificacion, $path_storage);
            $incapacidad->archivo_identificacion_id = $archivo_identificacion->id;
        }

        $incapacidad->save();
    }

    public function solicitudCdp ($incapacidad)
    {

            $objeto_acontratar = $this->getDescripcion($incapacidad);

            if (!isset($incapacidad->pr_rubro_id )) {
                throw new \Exception('La incapacidad no tiene un rubro asociado');
            }

            $pr_solicitud =  new PrSolicitudCp();
            $pr_solicitud->pr_detstrgasto_id = $incapacidad->rubro->detstrgasto ? $incapacidad->rubro->detstrgasto->id : null;
            $pr_solicitud->objeto_acontratar = $objeto_acontratar;
            $pr_solicitud->valor = $incapacidad->total_a_pagar;
            $pr_solicitud->fecha = Carbon::now()->format('Y-m-d');
            $pr_solicitud->save();
            $pr_solicitud->rubros()->create([
                'pr_detstrgasto_id' => $pr_solicitud->pr_detstrgasto_id,
                'valor' => $pr_solicitud->valor
            ]);

            return $pr_solicitud;

    }

    public function getDetstrgastos ()
    {
        try {
            $detstrgastos = PrDetstrgasto::whereDoesntHave('strGasto', function ($query) {
                $query->where('periodo', '<>',Carbon::now()->format('Y'))->where('estado', 'Registrada');
            })->get();
            $detstrgastos->load('rubro','tipoGasto');
            $rubros = [];
            foreach ($detstrgastos as $detstrgasto) {
                array_push($rubros, $detstrgasto->rubro);
            }
            return new Resource($rubros);

        }catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception,
                'msg' => $exception->getMessage()
            ], 500);
        }
    }

    public function getPdf () {
        try {
            $id = Input::get('id');
            $solicitud = PrSolicitudCp::find($id);
//            $incapacidade = AuIncapacidade::find($id);
            if (Input::get('id') && $solicitud) {
                $solicitud->load([
                    'detstrgasto.rubro',
                    'detstrgasto.tipoGasto',
                    'detstrgasto.strGasto.entidadResolucion.encargos.usuario',
                    'rubros.detstrgasto.tipoGasto',
                    'rubros.detstrgasto.rubro'
                ]);

                if (Input::get('html')) {
                    return view('dompdf.solicitudCDP');
                }
                setlocale(LC_ALL, "es_ES");

                $pdf = PDF::loadView('dompdf.solicitudCDP', ['solicitud' => $solicitud]);
                $pdf->setPaper('letter', 'portrait');
                return $pdf->stream('SOLICITUD CERTIFICADO DE DISPONIBILIDAD PRESUPUESTAL', ['Attachment' => 0]);
            } else {
                return view('errors.enlace_roto');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function getPedfSolicitud(){
        $id = Input::get('id');
        $user = User::find(Input::get('user'));
        $solicitud = AuIncapacidade::find($id);
        $solicitud->load([
            'afiliado',
            'relacion_laboral.pagador.tercero.municipio.departamento',
            'tipo_incapacidad',
            'usuario',
            'cie10'
        ]);
        $estado = new EstadoRelacionLaboral($solicitud);
        $liquidacion = $estado->liquidacion();

        $datosLiquidacion = $this->liquidarPagoPDF($liquidacion, $solicitud);

        $programacion = $this->programarPagosLiquidacionPDF($datosLiquidacion);

        $view = 'dompdf.pre_liquidacion_incapacidad';
        if (Input::get('html')) {
            return view($view);
        }
        setlocale(LC_ALL, "es_ES");

        $pdf = PDF::loadView($view, ['solicitud' => $solicitud, 'liquidacion' => $datosLiquidacion, 'programacion' => $programacion, 'fecha' => strftime('%d de %B del %Y', strtotime($solicitud->fecha)),'usuario' => $user->name]);
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('Solicitud. '.$solicitud->consecutivo,['Attachment' => 0]);
    }

    public function liquidarPagoPDF($liquidacion, $solicitud){
        $datos = collect();
        $datos->put('ibc', $liquidacion['promedio_ibc']);
        $sdlv = $liquidacion['smlv']['valor'] / 30;
        $salarioDia = $liquidacion['promedio_ibc'] / 30;
        if($solicitud->estado == 'Aprobada'){
            $datos['ibc'] = $solicitud->ibc_pagado;
            $salarioDia = $solicitud->ibc_pagado / 30;
        }
        $salarioReconocer = $salarioDia * 0.6666666;
        $datos->put('salario_dia', $salarioDia);
        $datos->put('salario_reconocer', $salarioReconocer);
        $datos->put('salario_pagar', $salarioReconocer > $sdlv ? $salarioReconocer : $sdlv);
        if($solicitud->tipo_incapacidad->au_tipprestacion_id == '1'){
            $datos->put('total_pagar', $datos['salario_pagar'] * $solicitud->dias_incapacidad);
        }else{
            if($solicitud->au_tipincapacidades_id == 16 || $solicitud->au_tipincapacidades_id == 6 || $solicitud->au_tipincapacidades_id == 7 ){
                $datos->put('total_pagar', $datos['salario_pagar'] * ($solicitud->dias_incapacidad));
            }else{
                $datos->put('total_pagar', $datos['salario_pagar'] * ($solicitud->dias_incapacidad - 2));
            }
        }

        return $datos;
    }


    public function programarPagosLiquidacionPDF($datos){
        $pagos = (int)round(($datos['total_pagar'] / ($datos['salario_pagar'] * 30)), 0,PHP_ROUND_HALF_EVEN);
        $programacion = collect();
        $dia = 30;
        if($datos['salario_pagar'] * 30 > $datos['total_pagar']){
            $programacion->put($dia, $datos['total_pagar']);
        }else{
            for($i = 0 ; $i < $pagos; $i++){
                $programacion->put($dia, ($datos['salario_pagar'] * 30));
                $dia+=30;
            }
            if($datos['total_pagar'] - (($datos['salario_pagar'] * 30 * $pagos)) > 0){

                $programacion->put($dia, $datos['total_pagar'] - (($datos['salario_pagar'] * 30 * $pagos)));
            }
        }

        return $programacion;

    }

    /**
     * @param $incapacidad
     * @return string
     */
    private function getDescripcion($incapacidad): string
    {
        return !isset($incapacidad->pr_rubro_id)
            ? '' : 'Pago a la Empresa ' . $incapacidad->relacion_laboral->pagador->razon_social .
            ' de ' . $incapacidad->tipo_incapacidad->prestacion->tipo . ' de la trabajadora '
            . '<b>' . $incapacidad->afiliado->nombre_completo . '</b>' . ' identifiacada con ' .
            $incapacidad->afiliado->identificacion_completa . ' comprendida del ' .
            Carbon::parse($incapacidad->fecha_inicio)->format('d/m/Y') . ' AL ' .
            Carbon::parse($incapacidad->fecha_fin)->format('d/m/Y') . '.';
    }

}


