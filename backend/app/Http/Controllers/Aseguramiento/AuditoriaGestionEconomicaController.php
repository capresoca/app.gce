<?php

namespace App\Http\Controllers\Aseguramiento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Storage;
use App\Http\Resources\AtencionUsuario\LiLicenciaResource;
use App\Http\Resources\AtencionUsuario\SoportesLicenciaResource;
use App\Http\Resources\AtencionUsuario\LiLicenciToGestionaResource;
// use App\Http\Resources\AtencionUsuario\GetPlanillasConciliadasResource;
use App\Models\LiGestiones\LiLicencia;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleILiquidacione;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleIPLiquidacione;
use App\Models\LiGestiones\ReRecaudoIncumplimientoDetalle;
use Carbon\Carbon;
use App\Models\LiGestiones\LiAuditoria;
use App\Models\LiGestiones\LiAuditoriaPrevia;
use App\Models\LiGestiones\LiAuditoriaRechazo;

class AuditoriaGestionEconomicaController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            // \Log::debug(print_r(LiLicenciaResource::collection(LiLicencia::with('rsentidades')->pimp()->orderBy('consecutivo_licencia', 'desc')->paginate(Input::get('per_page'))), true));

            return LiLicenciaResource::collection(LiLicencia::with('rsentidades', 'aportante','medicos')->pimp()->orderBy('consecutivo_licencia', 'desc')->paginate(Input::get('per_page'))); 
        }
        return LiLicenciaResource::collection(LiLicencia::with('rsentidades')->pimp()->limit(100)->orderBy('consecutivo_licencia', 'desc')->get());
    }

    public function show()
    {
        return LiLicenciToGestionaResource::collection(LiLicencia::with('municipio')
                                           ->join('li_licencia_soportes','li_licencia_soportes.consecutivo_licencia','=','li_licencias.consecutivo_licencia')
                                           ->join('as_afiliados','as_afiliados.id','=','li_licencias.consecutivo_afiliado')
                                           ->join('as_tipafiliados','as_tipafiliados.id','=','as_afiliados.as_tipafiliado_id')
                                           ->where('li_licencias.consecutivo_licencia',Input::get('id'))
                                           ->get());
    }

    public function getSoportes()
    {
        // return LiLicencia::with('municipio')
        //                 ->join('li_licencia_soportes','li_licencia_soportes.consecutivo_licencia','=','li_licencias.consecutivo_licencia')
        //                 ->join('as_afiliados','as_afiliados.id','=','li_licencias.consecutivo_afiliado')
        //                 ->join('as_tipafiliados','as_tipafiliados.id','=','as_afiliados.as_tipafiliado_id')
        //                 ->join('tb_proceso_administrativo_encabezados', 'tb_proceso_administrativo_encabezados.proceso', '=', 'li_licencias.tipo_incapacidad')
        //                 ->join('tb_proceso_administrativo_detalles', 'tb_proceso_administrativo_encabezados.consecutivo_proceso', '=', 'tb_proceso_administrativo_detalles.consecutivo_proceso')
        //                 ->join('tb_tipo_soportes', 'tb_tipo_soportes.consecutivo_soporte', '=', 'tb_proceso_administrativo_detalles.consecutivo_soporte')
        //                 ->where('li_licencias.consecutivo_licencia',Input::get('id'))
        //                 ->get();
        return SoportesLicenciaResource::collection(LiLicencia::join('tb_proceso_administrativo_encabezados', 'tb_proceso_administrativo_encabezados.proceso', '=', 'li_licencias.tipo_incapacidad')
                                                            ->join('tb_proceso_administrativo_detalles', 'tb_proceso_administrativo_encabezados.consecutivo_proceso', '=', 'tb_proceso_administrativo_detalles.consecutivo_proceso')
                                                            ->join('tb_tipo_soportes', 'tb_tipo_soportes.consecutivo_soporte', '=', 'tb_proceso_administrativo_detalles.consecutivo_soporte')
                                                            ->where('li_licencias.consecutivo_licencia',Input::get('id'))
                                                            ->get());
    }

    public function getPlanillasConciliadas($fechaInicio, $numeroIdentificacionCotizante, $numeroIdentificacionAportante)
    {
        $correcciones = 'A';
        $nuevafecha = date("Y-m",strtotime($fechaInicio."- 1 year"));
        $listaPeriodos[0] = $nuevafecha;    
        for ($i = 1; $i <= 12; $i++) {
            $nuevafecha = date("Y-m",strtotime($nuevafecha."+ 1 month"));
            // \Log::info('Lista periodos '. $nuevafecha);
            $listaPeriodos[$i] = $nuevafecha;
        }

        $consulta =
        RecRecaudoPlanillaDetalleILiquidacione::leftJoin('rec_recaudo_planilla_detalle_is', 'rec_recaudo_planilla_detalle_is.consecutivo_recaudo_planilla_detalle_i', '=', 'rec_recaudo_planilla_detalle_i_liquidaciones.consecutivo_planilla_detalle_iliquidacion')
                                                ->leftJoin('rec_recaudo_planilla_encabezados', 'rec_recaudo_planilla_encabezados.consecutivo_recaudo', '=', 'rec_recaudo_planilla_detalle_i_liquidaciones.consecutivo_planilla_detalle_iliquidacion')
                                                ->where('rec_recaudo_planilla_detalle_i_liquidaciones.numero_identificacion_cotizante',$numeroIdentificacionCotizante)
                                                ->where('rec_recaudo_planilla_detalle_is.numero_identificacion_aportante',$numeroIdentificacionAportante)
                                                ->whereIn('rec_recaudo_planilla_encabezados.periodo_pago', $listaPeriodos)
                                                ->where('rec_recaudo_planilla_detalle_i_liquidaciones.sw_correcciones', '!=', $correcciones)
                                                ->select('rec_recaudo_planilla_encabezados.numero_planilla', 'tipo_cotizante', 'subtipo_cotizante', 'rec_recaudo_planilla_encabezados.fecha_pago', 'rec_recaudo_planilla_encabezados.periodo_pago', 'dias_cotizados', 'ingreso_base_cotizacion', 'sw_conciliacion', 'rec_recaudo_planilla_detalle_i_liquidaciones.sw_vst', 'rec_recaudo_planilla_detalle_is.numero_planilla as numeroPlanillaCorregida', 'rec_recaudo_planilla_detalle_i_liquidaciones.consecutivo_recaudo_encabezado', 'secuencia', 'sw_ing', 'sw_ret', 'sw_sln', 'sw_vsp', 'tipo_identificacion_cotizante', 'numero_identificacion_cotizante')
                                                ->orderBy('rec_recaudo_planilla_encabezados.periodo_pago', 'desc')
                                                ->get();
        return $consulta;
    }

    public function getMapaIbcCompensacionXPlanilla($listaLlavesEncabezados)
    {
        $compensacion = '';
        if (!empty($listaLlavesEncabezados)) {
            // \Log::info('la listaLlavesEncabezados  '.$listaLlavesEncabezados);
            $compensacion = DB::select( DB::raw("
                SELECT o.consecutivo_recaudo_encabezado, o.secuencia, d.ingreso_base_cotizacion FROM
                rec_conciliacion_planilla_afiliados o,
                rec_compensacion_planilla_detalles d
                WHERE o.consecutivo_recaudo_encabezado IN( ". $listaLlavesEncabezados .")
                AND o.consecutivo_compensacion = d.consecutivo_encabezado AND o.secuencia_compensacion = d.secuencia
            "));
        }
        return $compensacion;
    }

    public function getDetallePanilla($tipoIdCotizante, $idCotizante, $tipoIdAportante, $idAportante, $numeroPlanilla)
    {
        $consultaDetallePlanilla = RecRecaudoPlanillaDetalleILiquidacione::join('rec_recaudo_planilla_encabezados', 'rec_recaudo_planilla_encabezados.consecutivo_recaudo', '=', 'rec_recaudo_planilla_detalle_i_liquidaciones.consecutivo_recaudo_encabezado')
                                                          ->join('rec_recaudo_planilla_detalle_is', 'rec_recaudo_planilla_detalle_is.consecutivo_recaudo_planilla_detalle_i', '=', 'rec_recaudo_planilla_encabezados.consecutivo_recaudo')
                                                          ->where('rec_recaudo_planilla_detalle_i_liquidaciones.tipo_identificacion_cotizante', $tipoIdCotizante)
                                                          ->where('rec_recaudo_planilla_detalle_i_liquidaciones.numero_identificacion_cotizante', $idCotizante)
                                                          ->select('numero_radicacion_pila', 'tipo_identificacion_aportante', 'numero_identificacion_aportante', 'razon_social_aportante', 'tipo_identificacion_cotizante', 'numero_identificacion_cotizante', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido',
                                                                   'tipo_planilla', 'sw_correcciones', 'rec_recaudo_planilla_encabezados.numero_planilla', 'tipo_cotizante', 'subtipo_cotizante', 'rec_recaudo_planilla_encabezados.periodo_pago', 'rec_recaudo_planilla_encabezados.fecha_pago', 'dias_cotizados', 'sw_ing', 'sw_ret', 'sw_tde', 'sw_tae', 'sw_vsp', 'sw_vst', 'sw_sln', 'sw_ige', 'sw_lma', 'sw_vac_lr','ingreso_base_cotizacion', 'tarifa', 'cotizacion_obligatoria')
                                                          ->get();
        return $consultaDetallePlanilla;
    }

    public function getCompensacionPlanillaAcxAbx($tipoIdCotizante, $idCotizante, $tipoIdAportante, $idAportante, $numeroPlanilla)
    {
        if (!empty($tipoIdCotizante) && !empty($idCotizante)) {
            $consultaCompensacion= DB::select( DB::raw("
            SELECT * FROM
            desarrollo.rec_compensacion_planilla_detalles o
            INNER JOIN desarrollo.rec_compensacion_planilla_encabezados p
            ON o.consecutivo_encabezado = p.consecutivo_encabezado
            WHERE o.tipo_documento_cotizante = '". $tipoIdCotizante ."'
            AND o.numero_documento_cotizante = ". $idCotizante
            ));
            return $consultaCompensacion;         
        } else {
            $consultaCompensacion = [];
            return $consultaCompensacion;  
        }
    }

    public function getDetallePanillaPensionados($tipoIdCotizante, $idCotizante, $tipoIdAportante, $idAportante, $numeroPlanilla)
    {
        $consultaDetallePlanilla = RecRecaudoPlanillaDetalleIPLiquidacione::join('rec_recaudo_planilla_encabezados', 'rec_recaudo_planilla_encabezados.consecutivo_recaudo', '=', 'rec_recaudo_planilla_detalle_i_p_liquidaciones.consecutivo_recaudo_encabezado')
                                                                          ->join('rec_recaudo_planilla_detalle_i_pes', 'rec_recaudo_planilla_detalle_i_pes.consecutivo_recaudo_encabezado', '=', 'rec_recaudo_planilla_encabezados.consecutivo_recaudo')
                                                                          ->where('rec_recaudo_planilla_detalle_i_p_liquidaciones.tipo_identificacion_pensionado', $tipoIdCotizante)
                                                                          ->where('rec_recaudo_planilla_detalle_i_p_liquidaciones.numero_identificacion_pensionado', $idCotizante)
                                                                          ->select('numero_planilla', 'tipo_identificacion_pagador', 'numero_identificacion_pagador', 'razon_social_pagador', 'tipo_identificacion_pensionado', 'numero_identificacion_pensionado', 'primer_nombre_pensionado', 'segundo_nombre_pensionado', 'primer_apellido_pensionado', 'segundo_apellido_pensionado',
                                                                                    'tipo_planilla', 'numero_planilla', 'tipo_pensionado', 'rec_recaudo_planilla_encabezados.periodo_pago', 'rec_recaudo_planilla_encabezados.fecha_pago', 'dias_cotizados', 'sw_ing', 'sw_ret', 'sw_tde', 'sw_tae', 'sw_vsp', 'ingreso_base_cotizacion', 'tarifa', 'cotizacion_obligatoria')
                                                                          ->get();
        return $consultaDetallePlanilla;
    }

    public function descargar()
    {
        // $public_path = public_path();
        $url = Input::get('url');
        // \Log::info('public_path '.$public_path);
        \Log::info('url '.$url);
        // $headers = [
        //     'Content-Type' => 'application/pdf',
        // ];
        // return response()->download($file, '36-45-sample.pdf', $headers);
        // return response()->download(storage_path("app/soportes_incapacidades/{$filename}"));
        return response()->download(storage_path($url));
    }

    public function getListaIncumplimiento($consecutivoAportante, $consecutivoAfiliado, $carteraSaneada)
    {
        $consulta = ReRecaudoIncumplimientoDetalle::leftJoin('re_recaudo_incumplimiento', 're_recaudo_incumplimiento.consecutivo_recaudo_incumplimiento', '=', 're_recaudo_incumplimiento_detalle.consecutivo_recaudo_incumplimiento')
                                                  ->leftJoin('as_pagadores', 'as_pagadores.id', '=', 're_recaudo_incumplimiento.consecutivo_aportante')
                                                  ->leftJoin('as_afiliado_pagador', 'as_afiliado_pagador.as_pagador_id', '=', 'as_pagadores.id')
                                                  ->leftJoin('as_afiliados', 'as_afiliados.id', '=', 'as_afiliado_pagador.as_afiliado_id')
                                                  ->where('re_recaudo_incumplimiento_detalle.sw_cartera_saneada',$carteraSaneada)
                                                  ->where('re_recaudo_incumplimiento.consecutivo_aportante',$consecutivoAportante)
                                                  ->where('as_afiliado_pagador.as_afiliado_id',$consecutivoAfiliado)
                                                  ->select('re_recaudo_incumplimiento.fecha_plazo', 're_recaudo_incumplimiento_detalle.tipo_cotizante_esperado', 're_recaudo_incumplimiento.periodo_pago', 're_recaudo_incumplimiento_detalle.dias_esperados', 're_recaudo_incumplimiento_detalle.ibc_esperado')
                                                  ->get();
        return $consulta;
    }

    public function listLiLiceseXAffiliate($consecutivoAfiliado, $consecutivoAportante)
    {
        $consulta = LiLicencia::join('rs_cie10s', 'rs_cie10s.id', '=', 'li_licencias.diagnostico_principal')
                              ->where('consecutivo_afiliado', $consecutivoAfiliado)
                              ->where('consecutivo_aportante', $consecutivoAportante)
                              ->select('consecutivo_licencia', 'tipo_incapacidad', 'numero_dias_incapacidad', 'fecha_inicio', 'fecha_fin', 'estado_licencia', 'dias_acumulados', 'rs_cie10s.descripcion as dx')
                              ->orderBy('fecha_inicio', 'desc')
                              ->get();
        return $consulta;
    }

    public function listClearancePeriodXInability($fechaInicio, $fechaFin)
    {
        $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        $listaPeriodo = [];
        $fechaInicioComoEntero = strtotime($fechaInicio);
        $fechaFinComoEntero = strtotime($fechaFin);
        $fecha = strtotime($fechaInicio);
        $mesIncicio= date("m", $fechaInicioComoEntero);
        $mesFin= date("m", $fechaFinComoEntero);
        $totalDiasLiquidados = 0;

        for ($i=0; $i < ($mesFin-$mesIncicio)+1 ; $i++) {
            $dia = date("d", $fecha);
            $mes= date("m", $fecha);
            $ano = date("Y", $fecha);
            $diasMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
            // if ($i === 0) {
            //     $diasLiquidados = ($diasMes - $dia)+1;
            // } else if ($i === ($mesFin-$mesIncicio)) {
            //     $diasLiquidados = date("d", $fechaFinComoEntero) + 1;
            // } else {
                $diasLiquidados = $diasMes;
            // }
            $totalDiasLiquidados += $diasLiquidados;
            $totalDiasReconocer = 0;
            $listaPeriodo[$i] = [
                'mes' => $meses[$mes-1].' '.$ano,
                'diasLiquidados' => $diasLiquidados,
                'diasReconocer' => 0,
                'valorReconocer' => 0,
                'totalDiasLiquidados' => $totalDiasLiquidados,
                'totalDiasReconocer' => $totalDiasReconocer,
            ];
            $nuevaFecha = date('d-m-Y', $fecha);
            $fecha=strtotime($nuevaFecha."+ 1 month");
        }

        return $listaPeriodo;
    }

    public function calcularIbc(Request $request) 
    {
        $planillasConciliadas = $this->getPlanillasConciliadas($request->fecha_incio_transcripcion, $request->identificacion_cotizante ,$request->identificacion_aportante);
        if ($request->valorSelectIbc == '1') {
            $ibc = ($request->totalIbc / $request->totalDias) * 30;
            return $ibc;
        } else if ($request->valorSelectIbc == '2') {
            $ibcTotalLiquida = 0;
            $dias = 0;
            if (count($planillasConciliadas) != 0) {     
              $periodo = $planillasConciliadas[0]->periodo_pago;
      
              for ($i = 0; $i < count($planillasConciliadas); $i++) {
      
                if ($periodo === $planillasConciliadas[$i]->periodo_pago) {
                  $val = $planillasConciliadas[$i]->ingreso_base_cotizacion;
                  $ibcTotalLiquida = $ibcTotalLiquida + $val;
                  $dias = $dias + $planillasConciliadas[$i]->dias_cotizados;
                }
      
              }
      
              $ibc = ($ibcTotalLiquida / $dias) * 30;
      
            }
            return $ibc;
        }
    }

    public function sendApproveOrReject(Request $request)
    {
        \Log::info($request->accionAuditoria);
        \Log::info('info del request '.$request);
        if ($this->validationsAudit($request->accionAuditoria, $request->incapacidadesPrevias, $request) != 'correcto') {
            $devuelto = $this->validationsAudit($request->accionAuditoria, $request->incapacidadesPrevias, $request);
            // valor total Liquidacion
            // super.getObject().setValorLiquidacion(f.getDisabilityValue());
            // super.getObject().setAfAfiliado(f.getQueryObjectAffiliate());
            // $correcto = costImplService.updateApproveOrRejectDisabilityLicense(super.getObject());
            $correcto = 'asigno valor a correcto';
            \Log::info($correcto);
            \Log::info($devuelto);
            return $devuelto;
        } else {
            $this->updateApproveOrRejectDisabilityLicense($request);
            $devuelto = $this->validationsAudit($request->accionAuditoria, $request->incapacidadesPrevias, $request);
            return $devuelto;
        }
    }

    public function validationsAudit($accionAuditoria, $incapacidadesPrevias, $requestIncapacidad)
    {
        $seleccionados = count($incapacidadesPrevias);
        $validacion = 'correcto';
        $date1 = '';
        $date2 = '';
        $count = 0;
        $countvalidateValorIncapacidad = 0;
        if ($accionAuditoria === 'aprobar') {
            for ($i=0; $i < $seleccionados; $i++) { 
                if ($seleccionados > 0) {
                    $date1 = $incapacidadesPrevias[$i]['fecha_fin'];
                    $date2 = strtotime($date1);
                    $date2 = strtotime($date2."+ 1 day");
                    if ($incapacidadesPrevias[$i]['estado_licencia'] != '2') { // si es diferente a 'aprobado'
                        $validacion = 'No se permite seleccionar incapacidades Previas que no sean Aprobadas.';
                    }
                    $count ++;
                    // agregar for para getListClearancePeriod

                }
            }

            if ($count == 1) {
                # Sólo se considera incapacidad previa si cumple las condiciones de días anteriores a la fecha inicio de la incapacidad que se está auditando. Verificar.
                // agregar Util.getDiferenciaDias30
            }

            if ($count > 1) {
                $validacion = 'Solo Puede seleccionar una incapacidad Previa Cuando va a Aprobar la incapacidad Y es una prorroga.';
            }

            if ($countvalidateValorIncapacidad != 0) {
                $validacion = 'Al ser prórroga el valor de la incapacidad debe ser mayor a cero.';
            }

            if (!empty($requestIncapacidad->concepto_medico)) {
                $validacion = 'La información de Concepto Médico debe estar VACÍA.';
            }

            if (!empty($requestIncapacidad->rechazos)) {
                $validacion = 'La información de Causal de Rechazo debe estar VACÍA.';
            }

            //agregar validacion movimiento

            if ($requestIncapacidad->total_liquidacion == 0) { // !$requestIncapacidad->validarLiquidar
                $validacion = 'Se debe liquidar antes de aprobar la prestación económica';
            }       
        }
        else if ($accionAuditoria === 'rechazar') {

            if (empty($requestIncapacidad->concepto_medico)) {
                $validacion = 'La información de Concepto está vacío.';
            }

            if (empty($requestIncapacidad->rechazos)) {
                $validacion = 'La información de Causal de Rechazo está vacía.';
            }
            
            if ($seleccionados != 0) {
                $validacion = 'No se permite seleccionar incapacidades previas cuando va a rechazar la incapacidad.';
            }
        
            // agregar validacion movimiento
        }
    
        // agregar for para getListClearancePeriod

        return $validacion;
    }

    public function updateApproveOrRejectDisabilityLicense ($requestIncapacidad)
    {
        \Log::info('ingreso a grabar');
        $seleccionados = count($requestIncapacidad->incapacidadesPrevias);
        // int diasIncapacidadAcumulados = 0;
        $diasIncapacidadAcumulados = 0;
        // short secuencia = 0;
        $secuencia = 0;
        $date = Carbon::now();
        $date = $date->format('Y-m-d');
        // Se inserta en li_auditoria

        $auditoria = new LiAuditoria();
        $auditoria->consecutivo_licencia = $requestIncapacidad->consecutivo_licencia;
        $auditoria->concepto = $requestIncapacidad->concepto_medico;
        $auditoria->estado_auditoria = $requestIncapacidad->accionAuditoria == 'aprobar' ? 2 : 3; // 2 aprobado, 3 negado
        $auditoria->usuario_grabado = \Auth::user()->name;
        $auditoria->fecha_grabado = $date;
        $auditoria->valor_liquidacion = $requestIncapacidad->total_liquidacion;

        $auditoria->save();
        $IDauditoria = $auditoria->consecutivo_auditoria; // obtenemos el id de la auditoria recien insertada
    
        // Se Inserta en li_auditoria_previa siempre y cuando la accion sea
        // APROBAR y se haya seleccionado una inacapacidad previa para la
        // prorroga.
        // Nota = si se rechaza la incapacidad NO se guarda en
        // li_auditoria_previa
    
        // Long consecutivoLicenciaPrevia = null;

        if ($requestIncapacidad->accionAuditoria == 'aprobar' && !empty($requestIncapacidad->incapacidadesPrevias)) {
            for ($i=0; $i < $seleccionados; $i++) { 
                if ($seleccionados > 0) {
                    $diasIncapacidadAcumulados = $diasIncapacidadAcumulados + $requestIncapacidad->incapacidadesPrevias[$i]['dias_acumulados'];
                    $auditoriaPrevia = new LiAuditoriaPrevia();
                    $auditoriaPrevia->consecutivo_auditoria = $IDauditoria;
                    $auditoriaPrevia->consecutivo_licencia = $requestIncapacidad->incapacidadesPrevias[$i]['consecutivo_licencia'];
                    $auditoriaPrevia->save();
                }
            }
        }

    // ----
        // Date fechaMenor = dto.getFechaInicio();    
        // // Se Inserta en li_auditoria_periodo  -- agregar
    
        // Se Inserta en li_auditoria_rechazo siempre y cuando la accion sea
        // RECHAZADA y el iterador de Causal Rechazo tenga elementos en la
        // lista
        // Nota = si se Aprueba la incapacidad NO se guarda en
        // li_auditoria_rechazo
        // secuencia = 0;

        $countRechazos = count($requestIncapacidad->rechazos);
        if ($requestIncapacidad->accionAuditoria == 'rechazar' && !empty($requestIncapacidad->rechazos)) {
            for ($i=0; $i < $countRechazos; $i++) { 
                $auditoriaRechazo = new LiAuditoriaRechazo();
                $auditoriaRechazo->consecutivo_auditoria = $IDauditoria;
                $auditoriaRechazo->consecutivo_rechazo = $requestIncapacidad->rechazos[$i]['causal_rechazo'];
                $auditoriaRechazo->save();
            }
        }
    
        // post li_rechazo
    }

}
