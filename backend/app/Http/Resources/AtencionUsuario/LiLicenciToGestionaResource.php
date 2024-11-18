<?php

namespace App\Http\Resources\AtencionUsuario;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsPagadore;
use App\Http\Controllers\Aseguramiento\AuditoriaGestionEconomicaController;

class LiLicenciToGestionaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $objPlanilla = new AuditoriaGestionEconomicaController();
        $totalDias = 0;
        $totalIbc= 0;
        $relaciones = $this->getRelacionesLaborales($this->consecutivo_afiliado);
        $planillas = $objPlanilla->getPlanillasConciliadas($this->fecha_inicio, $this->numero_identificacion_afiliado, $this->numero_identificacion_aportante);
        // \Log::info('$this->fecha_inicio '.$this->fecha_inicio);
        // \Log::info('$this->numero_identificacion_afiliado '.$this->numero_identificacion_afiliado);
        // \Log::info('$this->numero_identificacion_aportante '.$this->numero_identificacion_aportante);
        // \Log::info('las planillas '.print_r($planillas, true));
        $listaLlavesEncabezados = '';
        $tipoIdCotizante = $this->tipo_identificacion_afiliado;
        $idCotizante = $this->numero_identificacion_afiliado;
        for ($i=0; $i < count($planillas); $i++) { 
            $listaLlavesEncabezados .= $planillas[$i]->consecutivo_recaudo_encabezado.',';
        }
        $compensaciones = $objPlanilla->getMapaIbcCompensacionXPlanilla(substr($listaLlavesEncabezados, 0, -1));

        $arrayPlanillas = [];

        for ($j=0; $j < count($planillas); $j++) { 
            $ibcCompensado = '';
            $compensado = '';
            for ($k=0; $k < count($compensaciones) ; $k++) { 
                if ($compensaciones[$k]->consecutivo_recaudo_encabezado == $planillas[$j]->consecutivo_recaudo_encabezado && $compensaciones[$k]->secuencia == $planillas[$j]->secuencia) {
                    $ibcCompensado = $compensaciones[$k]->ingreso_base_cotizacion;
                    $compensado = 'Si';
                } else {
                    $ibcCompensado = '';
                    $compensado = 'No';
                }
            }
            $arrayPlanillas[$j] = [
                'consecutivo_recaudo_encabezado' => $planillas[$j]->consecutivo_recaudo_encabezado,
                'dias_cotizados' => $planillas[$j]->dias_cotizados,
                'fecha_pago' => $planillas[$j]->fecha_pago,
                'ingreso_base_cotizacion' => $planillas[$j]->ingreso_base_cotizacion,
                'numeroPlanillaCorregida' => $planillas[$j]->numeroPlanillaCorregida,
                'numero_planilla' => $planillas[$j]->numero_planilla,
                'periodo_pago' => $planillas[$j]->periodo_pago,
                'secuencia' => $planillas[$j]->secuencia,
                'subtipo_cotizante' => $planillas[$j]->subtipo_cotizante,
                'sw_conciliacion' => $planillas[$j]->sw_conciliacion,
                'sw_ing' => $planillas[$j]->sw_ing != null ? "X" : "",
                'sw_ret' => $planillas[$j]->sw_ret != null ? "X" : "",
                'sw_sln' => $planillas[$j]->sw_sln != null ? "X" : "",
                'sw_vsp' => $planillas[$j]->sw_vsp != null ? "X" : "",
                'sw_vst' => $planillas[$j]->sw_vst != null ? "X" : "",
                'tipo_cotizante'=> $planillas[$j]->tipo_cotizante,
                'ibc_compensado' => $ibcCompensado,
                'compensado' => $compensado
            ];
            $totalDias = $totalDias + $planillas[$j]->dias_cotizados;
            $totalIbc = $totalIbc + $planillas[$j]->ingreso_base_cotizacion;
        }

        //Planillas
        $planillaDetalle = $objPlanilla->getDetallePanilla($tipoIdCotizante, $idCotizante, null, null, null);
        $compensacionPlanilla = $objPlanilla->getCompensacionPlanillaAcxAbx($tipoIdCotizante, $idCotizante, null, null, null);
        $planillaPensionado = $objPlanilla->getDetallePanillaPensionados($tipoIdCotizante, $idCotizante, null, null, null);
        $liquidacion = $objPlanilla->listClearancePeriodXInability($this->fecha_inicio, $this->fecha_fin);
        // $planillaPensionado = $objPlanilla->getDetallePanillaPensionados('x', 1, null, null, null);

        $arrayIncapacidad = [
            'tipo_cotizante' => $this->nombre, 
            'consecutivo_licencia' => $this->consecutivo_licencia,
            'prestador' => $this->consecutivo_ips,
            'registro_profesional' => $this->consecutivo_medico,
            'nombre_profesional' => $this->nombre_profesional,
            'diagnostico_principal' => $this->diagnostico_principal,
            'tipoLicencia' => $this->tipo_incapacidad,
            'fecha_incio_transcripcion' => $this->fecha_inicio ,
            'fecha_expedicion' => $this->fecha_expedicion,
            'dias' => $this->numero_dias_incapacidad,
            'fecha_fin' => $this->fecha_fin,
            'id_aportante' => $this->consecutivo_aportante,
            'id_afiliado' => $this->consecutivo_afiliado,
            'identificacion_cotizante' => $this->numero_identificacion_afiliado,
            'identificacion_aportante' => $this->numero_identificacion_aportante,
            'estado_licencia' => $this->estado_licencia
        ];
        $arraySoportes = [
            'urlsoporte' => $this->url
        ];
        
        $keyes = []; 
        for ($l=0; $l < count($compensacionPlanilla) ; $l++) { 
            $llave = $compensacionPlanilla[$l]->periodo_compensado."-".$compensacionPlanilla[$l]->tipo_documento_cotizante."-".$compensacionPlanilla[$l]->numero_documento_cotizante."-".$compensacionPlanilla[$l]->numero_planilla_liquidacion;
            $keyes[$l] = [
                'llave' => $llave,
                'consecutivo_compensacion_detalle' => $compensacionPlanilla[$l]->consecutivo_compensacion_detalle,
                'consecutivo_encabezado' => $compensacionPlanilla[$l]->consecutivo_encabezado
            ];
        }

        // \Log::info('keyes '.print_r($keyes, true));
        // \Log::info('compensacionPlanilla  '.print_r($compensacionPlanilla, true));
        // \Log::info('planillas '.print_r($planillaDetalle, true));
        // \Log::info('planillaPensionado '.print_r($planillaPensionado, true));

        $arrayPlanillaDetalles = [];
        for ($m=0; $m < count($planillaDetalle) ; $m++) { 
            $llaveDetalle = $planillaDetalle[$m]->periodo_pago."-".$planillaDetalle[$m]->tipo_identificacion_cotizante."-".$planillaDetalle[$m]->numero_identificacion_cotizante."-".$planillaDetalle[$m]->numero_planilla;
            $arrayPlanillaDetalles[$m] = [
                'planilla' => $planillaDetalle[$m]->numero_radicacion_pila,
                'identificacion_aportante' => $planillaDetalle[$m]->tipo_identificacion_aportante . '-' . $planillaDetalle[$m]->numero_identificacion_aportante,
                'razon_social_aportante' => $planillaDetalle[$m]->razon_social_aportante,
                'identificacion_cotizante' => $planillaDetalle[$m]->tipo_identificacion_cotizante . '-' . $planillaDetalle[$m]->numero_identificacion_cotizante,
                'nombre_cotizante' => $planillaDetalle[$m]->primer_nombre . ' ' . $planillaDetalle[$m]->segundo_nombre . ' ' . $planillaDetalle[$m]->primer_apellido . ' ' . $planillaDetalle[$m]->segundo_apellido,
                'tipo_planilla' => $planillaDetalle[$m]->tipo_planilla,
                'correcciones' => $planillaDetalle[$m]->sw_correcciones,
                'planilla_corregida' => $planillaDetalle[$m]->numero_planilla,
                'tipo_cotizante' => $planillaDetalle[$m]->tipo_cotizante,
                'subtipo_cotizante' => $planillaDetalle[$m]->subtipo_cotizante,
                'periodo ' => $planillaDetalle[$m]->periodo_pago,
                'fecha_pago' => $planillaDetalle[$m]->fecha_pago,
                'dias' => $planillaDetalle[$m]->dias_cotizados,
                'ing' => $planillaDetalle[$m]->sw_ing != null ? "X" : "",
                'ret' => $planillaDetalle[$m]->sw_ret != null ? "X" : "",
                'tde' => $planillaDetalle[$m]->sw_tde != null ? "X" : "",
                'tae' => $planillaDetalle[$m]->sw_tae != null ? "X" : "",
                'vsp' => $planillaDetalle[$m]->sw_vsp != null ? "X" : "",
                'vst' => $planillaDetalle[$m]->sw_vst != null ? "X" : "",
                'sln' => $planillaDetalle[$m]->sw_sln != null ? "X" : "",
                'ige' => $planillaDetalle[$m]->sw_ige != null ? "X" : "",
                'lma' => $planillaDetalle[$m]->sw_lma != null ? "X" : "",
                'vac' => $planillaDetalle[$m]->sw_vac_lr != null ? "X" : "",
                'ibc' => $planillaDetalle[$m]->ingreso_base_cotizacion,
                'tarifa' => $planillaDetalle[$m]->tarifa,
                'cotizacion_obligatoria' => $planillaDetalle[$m]->cotizacion_obligatoria,
                'estado_conciliacion' => 'example',
                'estado_compensacion' => 'registro_compensado',
                'fecha_proceso_giro_compensacion' => '',
                'total_dias_compensados' => '',
                'upc_reconocida' => '',
                'provision_incapacidades' => '',
                'valor_upc_promocion_prevencion' => '',
                'serial_bdua' => '',
                'serial_ha' => ''
            ];

            for ($n=0; $n < count($keyes); $n++) { 
               if ($keyes[$n]['llave'] === $llaveDetalle) {
                    for ($o=0; $o < count($compensacionPlanilla); $o++) { 
                        if ($keyes[$n]['consecutivo_compensacion_detalle'] === $compensacionPlanilla[$o]->consecutivo_compensacion_detalle && $keyes[$n]['consecutivo_encabezado'] === $compensacionPlanilla[$o]->consecutivo_encabezado) {
                            $arrayPlanillaDetalles[$m]['estado_compensacion'] = $compensacionPlanilla[$o]->registro_compensado;
                            $arrayPlanillaDetalles[$m]['fecha_proceso_giro_compensacion'] = $compensacionPlanilla[$o]->fecha_proceso_giro_compensacion;
                            $arrayPlanillaDetalles[$m]['total_dias_compensados'] = $compensacionPlanilla[$o]->total_dias_compensados;
                            $arrayPlanillaDetalles[$m]['upc_reconocida'] = $compensacionPlanilla[$o]->upc_reconocida;
                            $arrayPlanillaDetalles[$m]['provision_incapacidades'] = $compensacionPlanilla[$o]->provision_incapacidades;
                            $arrayPlanillaDetalles[$m]['valor_upc_promocion_prevencion'] = $compensacionPlanilla[$o]->valor_upc_promocion_prevencion;
                            $arrayPlanillaDetalles[$m]['serial_bdua'] = $compensacionPlanilla[$o]->serial_bdua;
                            $arrayPlanillaDetalles[$m]['serial_ha'] = $compensacionPlanilla[$o]->serial_ha;
                        }
                    }
               }
            }
        }

        // \Log::info('array Planilla  '.print_r($arrayPlanillaDetalles, true));

        $arrayPlanillaPensionados = [];
        for ($p=0; $p < count($planillaPensionado) ; $p++) { 
            $llaveDetalle = $planillaPensionado[$p]->periodo_pago."-".$planillaPensionado[$p]->tipo_identificacion_pensionado."-".$planillaPensionado[$p]->numero_identificacion_pensionado."-".$planillaPensionado[$p]->numero_planilla;
            // \Log::info('llaveDetalle  '.$llaveDetalle);
            $arrayPlanillaPensionados[$p] = [
                'planilla' => $planillaPensionado[$p]->numero_planilla,
                'identificacion_aportante' => $planillaPensionado[$p]->tipo_identificacion_pagador . '-' . $planillaPensionado[$p]->numero_identificacion_pagador,
                'razon_social_aportante' => $planillaPensionado[$p]->razon_social_pagador,
                'identificacion_cotizante' => $planillaPensionado[$p]->tipo_identificacion_pensionado . '-' . $planillaPensionado[$p]->numero_identificacion_pensionado,
                'nombre_cotizante' => $planillaPensionado[$p]->primer_nombre_pensionado . ' ' . $planillaPensionado[$p]->segundo_nombre_pensionado . ' ' . $planillaPensionado[$p]->primer_apellido_pensionado . ' ' . $planillaPensionado[$p]->segundo_apellido_pensionado,
                'tipo_planilla' => $planillaPensionado[$p]->tipo_planilla,
                'correcciones' => '',//$planillaPensionado[$p]->sw_correcciones,
                'planilla_corregida' => $planillaPensionado[$p]->numero_planilla,
                'tipo_cotizante' => $planillaPensionado[$p]->tipo_pensionado,
                'subtipo_cotizante' => '',//$planillaPensionado[$p]->subtipo_cotizante,
                'periodo' => $planillaPensionado[$p]->periodo_pago,
                'fecha_pago' => $planillaPensionado[$p]->fecha_pago,
                'dias' => $planillaPensionado[$p]->dias_cotizados,
                'ing' => $planillaPensionado[$p]->sw_ing != null ? "X" : "",
                'ret' => $planillaPensionado[$p]->sw_ret != null ? "X" : "",
                'tde' => $planillaPensionado[$p]->sw_tde != null ? "X" : "",
                'tae' => $planillaPensionado[$p]->sw_tae != null ? "X" : "",
                'vsp' => $planillaPensionado[$p]->sw_vsp != null ? "X" : "",
                'vst' => '',//sw_vst != null ? "X" : "",
                'sln' => '',//sw_sln != null ? "X" : "",
                'ige' => '',//sw_ige != null ? "X" : "",
                'lma' => '',//sw_lma != null ? "X" : "",
                'vac' => '',//sw_vac_lr != null ? "X" : "",
                'ibc' => $planillaPensionado[$p]->ingreso_base_cotizacion,
                'tarifa' => $planillaPensionado[$p]->tarifa,
                'cotizacion_obligatoria' => $planillaPensionado[$p]->cotizacion_obligatoria,
                'estado_conciliacion' => 'example',
                'estado_compensacion' => 'registro_compensado',
                'fecha_proceso_giro_compensacion' => '',
                'total_dias_compensados' => '',
                'upc_reconocida' => '',
                'provision_incapacidades' => '',
                'valor_upc_promocion_prevencion' => '',
                'serial_bdua' => '',
                'serial_ha' => ''
            ];

            for ($q=0; $q < count($keyes); $q++) { 
               if ($keyes[$q]['llave'] === $llaveDetalle) {
                    for ($r=0; $r < count($compensacionPlanilla); $r++) { 
                        if ($keyes[$q]['consecutivo_compensacion_detalle'] === $compensacionPlanilla[$r]->consecutivo_compensacion_detalle && $keyes[$q]['consecutivo_encabezado'] === $compensacionPlanilla[$r]->consecutivo_encabezado) {
                            $arrayPlanillaPensionados[$p]['estado_compensacion'] = $compensacionPlanilla[$r]->registro_compensado;
                            $arrayPlanillaPensionados[$p]['fecha_proceso_giro_compensacion'] = $compensacionPlanilla[$r]->fecha_proceso_giro_compensacion;
                            $arrayPlanillaPensionados[$p]['total_dias_compensados'] = $compensacionPlanilla[$r]->total_dias_compensados;
                            $arrayPlanillaPensionados[$p]['upc_reconocida'] = $compensacionPlanilla[$r]->upc_reconocida;
                            $arrayPlanillaPensionados[$p]['provision_incapacidades'] = $compensacionPlanilla[$r]->provision_incapacidades;
                            $arrayPlanillaPensionados[$p]['valor_upc_promocion_prevencion'] = $compensacionPlanilla[$r]->valor_upc_promocion_prevencion;
                            $arrayPlanillaPensionados[$p]['serial_bdua'] = $compensacionPlanilla[$r]->serial_bdua;
                            $arrayPlanillaPensionados[$p]['serial_ha'] = $compensacionPlanilla[$r]->serial_ha;
                        }
                    }
               }
            }
        }

        // \Log::info('Pensionados  '.print_r($arrayPlanillaPensionados, true));

        // $arrayDetalleFinal = array();
        $arrayDetalleFinal = [];
        $arrayDetalleFinal = array_merge($arrayPlanillaDetalles, $arrayPlanillaPensionados);

        //Cartera sin sanear
        $carteraSinSanear = $objPlanilla->getListaIncumplimiento($this->consecutivo_aportante, $this->consecutivo_afiliado, '2');
        //Incapacidades previas
        $incapacidadesPrevias = $objPlanilla->listLiLiceseXAffiliate($this->consecutivo_afiliado, $this->consecutivo_aportante);
        // \Log::info('Incapacidades previas  '.print_r($incapacidadesPrevias, true));

        return [
            'incapacidad' => $arrayIncapacidad,
            'planilla' => $arrayPlanillas,
            'detallesPlanilla' => $arrayDetalleFinal,
            'urlsoporte' => $this->url,
            'nombre_soporte' => $this->soporte,
            'razon_social' => $this->razon_social,
            'relaciones' => $relaciones,
            'cartera' => $carteraSinSanear,
            'incapacidadesPrevias' => $incapacidadesPrevias,
            'totalDias' => $totalDias,
            'totalIbc' => $totalIbc,
            'liquidacion' => $liquidacion
        ];
    }

    private function getRelacionesLaborales($idAfiliado)
    {
        // $relacion = AsAfiliado::join('as_afiliado_pagador', 'as_afiliado_pagador.as_afiliado_id', '=', 'as_afiliados.id')
        //                       ->join('as_pagadores', 'as_pagadores.id', '=', 'as_afiliado_pagador.as_pagador_id')
        //                       ->leftJoin('as_clasecotizantes', 'as_clasecotizantes.id', '=', 'as_afiliados.as_clasecotizante_id')
        //                       ->where('as_afiliado_pagador.estado','=','Activo')
        //                       ->where('as_afiliados.id', $idAfiliado)
        //                       ->select('fecha_vinculacion', 'fecha_fin_vinculacion', 'as_afiliados.ibc', 'as_clasecotizantes.codigo', 'as_clasecotizantes.descripcion')
        //                     //   ->distinct()
        //                       ->get();
        // \Log::info('el id afili' . $idAfiliado);
        // \Log::info('las relaciones' . print_r($relacion, true));
        // return $relacion;
        // El bloque anterior gerera error por el modelo AsAfiliado===>local.ERROR: Trying to get property 'codigo' of non-object {"userId":1,"email":"soporte@sistemasinteligentes.co","exception":"[object] (ErrorException(code: 0): Trying to get property 'codigo' of non-object at /home/system/CreandoSoft/Software/capresoca/capresoca-backend/app/Models/Aseguramiento/AsAfiliado.php:607)

        $relacion = AsPagadore::join('as_afiliado_pagador', 'as_afiliado_pagador.as_pagador_id', '=', 'as_pagadores.id')
                            ->join('as_afiliados', 'as_afiliados.id', '=', 'as_afiliado_pagador.as_afiliado_id')
                            ->leftJoin('as_clasecotizantes', 'as_clasecotizantes.id', '=', 'as_afiliados.as_clasecotizante_id')
                            ->where('as_afiliado_pagador.estado','=','Activo')
                            ->where('as_afiliados.id', $idAfiliado)
                            ->select('fecha_vinculacion', 'fecha_fin_vinculacion', 'as_afiliados.ibc', 'as_clasecotizantes.codigo', 'as_clasecotizantes.descripcion')
                            ->get();
                            return $relacion;
    }
}
