<?php

namespace App\Http\Controllers\RecCompensacion;

use App\Capresoca\RecCompensacion\ProcesoConciliacionPilaBanco;
use App\Compensacion\CpLiquidacione;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsAfiliadoPagador;
use App\Models\Enums\ESiNo;
use App\Models\RecCompensacion\LqContributivoEsperada;
use App\Models\RecCompensacion\RecConciliacionPlanillaAfiliado;
use App\Models\RecCompensacion\RecLogBancarioDetalleAportante;
use App\Models\RecCompensacion\RecLogBancarioDetalleAportanteSgp;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleI;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleILiquidacione;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleIPe;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleIPLiquidacione;
use App\Models\RecCompensacion\RecRecaudoPlanillaEncabezado;
use App\Models\TalentoHumano\TbValorCopago;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;

class RecConciliacionController extends Controller
{

    protected $LIMIT = 100;

    public function store()
    {
        try {
            $this->ejecucionConciliacionPilaBanco();

        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function ejecucionConciliacionPilaBanco()
    {
        try {
            $llavesLogBancariExitosos = ProcesoConciliacionPilaBanco::getConsecutivosLogBancarioExitosos();
            $llavesRecaudoPlanillasExitosoI = ProcesoConciliacionPilaBanco::getConsecutivosRecaudoPlanillaExitosos(false);
            $llavesRecaudoPlanillasExitosoIP = ProcesoConciliacionPilaBanco::getConsecutivosRecaudoPlanillaExitosos(true);

            if ($llavesLogBancariExitosos->isNotEmpty() && ($llavesRecaudoPlanillasExitosoI->isNotEmpty() || $llavesRecaudoPlanillasExitosoIP->isNotEmpty())) {
                $mapaSalarioMinimo = $this->getValoresSalarioMinimoXAnno();
                $mapaTotales = collect();
                $mapaTotalesP = collect();
                $listaLogBancario = collect();
                $listaRecaudoPlanillaI = collect();
                $listaRecaudoPlanillaIP = collect();

                foreach (ProcesoConciliacionPilaBanco::getSubLista($llavesRecaudoPlanillasExitosoI, $this->LIMIT) as $key => $sublist) {
                    if (count($sublist) > 0) {
                        $mapaTotales->push(ProcesoConciliacionPilaBanco::getRecRecaudoPlantillaDetalleITotalesConciliacion($sublist));
                    }
                }

                foreach (ProcesoConciliacionPilaBanco::getSubLista($llavesRecaudoPlanillasExitosoIP, $this->LIMIT) as $key => $sublist) {
                    if (count($sublist) > 0) {
                        $mapaTotalesP->push(ProcesoConciliacionPilaBanco::getRecRecaudoPlantillaDetalleIPTotalesConciliacion($sublist));
                    }
                }

                foreach (ProcesoConciliacionPilaBanco::getSubLista($llavesLogBancariExitosos, $this->LIMIT) as $key => $sublist) {
                    if (count($sublist) > 0) {
                        $listaLogBancario->push(ProcesoConciliacionPilaBanco::getLogBancarioDetalleAportanteSgpConciliacion($sublist));
                        $listaLogBancario->push(ProcesoConciliacionPilaBanco::getLogBancarioDetalleAportanteConciliacion($sublist));
                    }
                }

                foreach (ProcesoConciliacionPilaBanco::getSubLista($llavesRecaudoPlanillasExitosoI, $this->LIMIT) as $key => $sublist) {
                    if (count($sublist) > 0) {
                        $listaRecaudoPlanillaI->push(ProcesoConciliacionPilaBanco::getRecRecaudoPlantillaDetalleIConciliacion($sublist));
                    }
                }

                foreach (ProcesoConciliacionPilaBanco::getSubLista($llavesRecaudoPlanillasExitosoIP, $this->LIMIT) as $key => $sublist) {
                    if (count($sublist) > 0) {
                        $listaRecaudoPlanillaIP->push(ProcesoConciliacionPilaBanco::getRecRecaudoPlantillaDetalleIPConciliacion($sublist));
                    }
                }

                $mapaAportante = collect();
                $mapaPlanillatipoI = collect();
                $mapaPlanillatipoIP = collect();
                $chekMapTotales = $mapaTotales->mapWithKeys(function ($query) { return $query->toArray(); });
                $chekMapIPTotales = $mapaTotalesP->mapWithKeys(function ($query) { return $query->toArray(); });


                $listLogs = $listaLogBancario->toArray();
                foreach ($listLogs as $itemKey => $log) {
                    foreach ($listLogs[$itemKey] as $key => $aportante) {
                        //$keyCompuesta = "$aportante->identificacion_aportante-$aportante->numero_planilla_liquidacion-$aportante->periodo_pago-$aportante->codigo_operador_informacion-$aportante->valor_planilla";
                        $keyCompuesta = $aportante['identificacion_aportante'] . '-' . $aportante['numero_planilla_liquidacion'] . '-' . $aportante['periodo_pago'] . '-' . $aportante['codigo_operador_informacion'] . '-' . $aportante['valor_planilla'];
                        $mapaAportante->put($keyCompuesta, $aportante);

                        $keyCompuesta2 = substr($aportante['identificacion_aportante'], 0, (strlen($aportante['identificacion_aportante']) - 1));
                        $keyCompuesta2 = $keyCompuesta2 . '-' . $aportante['numero_planilla_liquidacion'] . '-' . $aportante['periodo_pago'] . '-' . $aportante['codigo_operador_informacion'] . '-' . $aportante['valor_planilla'];
                        $mapaAportante->put($keyCompuesta2, $aportante);
                    }
                }

                $listPlanillaI = $listaRecaudoPlanillaI->toArray();
                foreach ($listPlanillaI as $itemKey => $listPlanilla) {
                    foreach ($listPlanillaI[$itemKey] as $key => $planilla) {
                        $consecutivoRecaudoEncabezado = $chekMapTotales->offsetGet($planilla->consecutivo_recaudo_encabezado)['valor'];
                        $keyCompuesta = $planilla->numero_identificacion_aportante . '-' . $planilla->numero_radicacion_pila . '-' . $planilla->periodo_pago . '-' . $planilla->codigo_operador;
                        $keyCompuesta = $keyCompuesta . '-' . $consecutivoRecaudoEncabezado;
                        $mapaPlanillatipoI->put($keyCompuesta, $planilla);

                        $keyCompuesta2 = $planilla->numero_identificacion_aportante . $planilla->digito_verificacion_aportante . '-' . $planilla->numero_radicacion_pila . '-' . $planilla->periodo_pago . '-' . $planilla->codigo_operador;
                        $keyCompuesta2 = $keyCompuesta2 . '-' . $consecutivoRecaudoEncabezado;
                        $mapaPlanillatipoI->put($keyCompuesta2, $planilla);
                    }
                }

                $listPlanillaIP = $listaRecaudoPlanillaIP->toArray();
                foreach ($listPlanillaIP as $Key => $planillaIP) {
                    foreach ($listPlanillaIP[$Key] as $itemKey => $planilla) {
                        $consecutivoRecaudoEncabezado = $chekMapIPTotales->offsetGet($planilla->consecutivo_recaudo_encabezado)['valor'];
                        $planillaEncabezado = RecRecaudoPlanillaEncabezado::select('numero_planilla')->where('consecutivo_recaudo', $planilla->consecutivo_recaudo_encabezado)->first();

                        $keyCompuesta = $planilla->numero_identificacion_pagador . '-' . $planillaEncabezado['numero_planilla'] . '-' . "$planilla->periodo_pago-$planilla->codigo_operador-$consecutivoRecaudoEncabezado";
                        $mapaPlanillatipoIP->put($keyCompuesta, $planilla);

                        $keyCompuesta2 = "$planilla->numero_identificacion_pagador$planilla->digito_verificacion_pagador-" . $planillaEncabezado['numero_planilla'] . "-$planilla->periodo_pago-$planilla->codigo_operador-$consecutivoRecaudoEncabezado";
                        $mapaPlanillatipoIP->put($keyCompuesta2, $planilla);
                    }
                }

                $empalmadas = collect();
                $empalmadasP = collect();
                $planillasHuerfanas = collect();
                $planillasHuerfanasP = collect();
                $setEmpalmadas = collect();
                $setDescartadas = collect();

                $empalmada = null;
                foreach ($mapaPlanillatipoI->toArray() as $key => $planilla) {
                    $consecutivoRecaudoEncabezado = $chekMapTotales->offsetGet($planilla->consecutivo_recaudo_encabezado)['valor'];
                    $keyCompuesta = "$planilla->numero_identificacion_aportante-$planilla->numero_radicacion_pila-$planilla->periodo_pago-$planilla->codigo_operador-$consecutivoRecaudoEncabezado";

                    $keyCompuesta2 = "$planilla->numero_identificacion_aportante$planilla->digito_verificacion_aportante-$planilla->numero_radicacion_pila-$planilla->periodo_pago-$planilla->codigo_operador-$consecutivoRecaudoEncabezado";

                    if ((!$setEmpalmadas->offsetExists($keyCompuesta)) && (!$setEmpalmadas->offsetExists($keyCompuesta2)) && (!$setDescartadas->offsetExists($keyCompuesta2)) && (!$setDescartadas->offsetExists($keyCompuesta))) {
                        if ($mapaAportante->offsetExists($keyCompuesta)) {
                            $empalmada = collect($mapaAportante->offsetGet($keyCompuesta));
                            $empalmada->put('consecutivo_recaudo_planilla_detalle_i', $planilla->consecutivo_recaudo_planilla_detalle_i);
                            $empalmada->put('consecutivo_planilla_detalle', $planilla->consecutivo_recaudo_encabezado);
                            $empalmada->put('sw_conciliacion', ESiNo::getIndice(ESiNo::SI)->getId());
                            $empalmadas->push($empalmada);
                            $setEmpalmadas->push($keyCompuesta);
                            $setDescartadas->push($keyCompuesta2);
                        } elseif ($mapaAportante->offsetExists($keyCompuesta2)) {
                            $empalmada = collect($mapaAportante->offsetGet($keyCompuesta2));
                            $empalmada->put('consecutivo_recaudo_planilla_detalle_i', $planilla->consecutivo_recaudo_planilla_detalle_i);
                            $empalmada->put('consecutivo_planilla_detalle', $planilla->consecutivo_recaudo_encabezado);
                            $empalmada->put('sw_conciliacion', ESiNo::getIndice(ESiNo::SI)->getId());
                            $empalmadas->push($empalmada);
                            $setEmpalmadas->push($keyCompuesta2);
                            $setDescartadas->push($keyCompuesta);
                        } else {
                            $planillasHuerfanas->push($mapaPlanillatipoI->offsetGet($keyCompuesta));
                            $setDescartadas->push($keyCompuesta2);
                        }

                    }
                }

                foreach ($mapaPlanillatipoIP->toArray() as $keyI => $planilla) {
                    $consecutivoRecaudoEncabezado = $chekMapIPTotales->offsetGet($planilla->consecutivo_recaudo_encabezado)['valor'];
                    $planillaEncabezado = RecRecaudoPlanillaEncabezado::select('numero_planilla')->where('consecutivo_recaudo', $planilla->consecutivo_recaudo_encabezado)->first();

                    $keyCompuesta = $planilla->numero_identificacion_pagador . '-' . $planillaEncabezado['numero_planilla'] . '-' . "$planilla->periodo_pago-$planilla->codigo_operador-$consecutivoRecaudoEncabezado";
                    $keyCompuesta2 = "$planilla->numero_identificacion_pagador$planilla->digito_verificacion_pagador-" . $planillaEncabezado['numero_planilla'] . "-$planilla->periodo_pago-$planilla->codigo_operador-$consecutivoRecaudoEncabezado";

                    if ((!$setEmpalmadas->offsetExists($keyCompuesta)) && (!$setEmpalmadas->offsetExists($keyCompuesta2)) && (!$setDescartadas->offsetExists($keyCompuesta2)) && (!$setDescartadas->offsetExists($keyCompuesta))) {
                        if ($mapaAportante->offsetExists($keyCompuesta)) {
                            $empalmada = collect($mapaAportante->offsetGet($keyCompuesta));
                            $empalmada->put('consecutivo_recaudo_planilla_detalle_i_p', $planilla->consecutivo_recaudo_planilla_detalle_i_p);
                            $empalmada->put('consecutivo_planilla_detalle', $planilla->consecutivo_recaudo_encabezado);
                            $empalmada->put('sw_conciliacion', ESiNo::getIndice(ESiNo::SI)->getId());
                            $empalmadasP->push($empalmada);
                            $setEmpalmadas->push($keyCompuesta);
                            $setDescartadas->push($keyCompuesta2);
                        } elseif ($mapaAportante->offsetExists($keyCompuesta2)) {
                            $empalmada = collect($mapaAportante->offsetGet($keyCompuesta2));
                            $empalmada->put('consecutivo_recaudo_planilla_detalle_i_p', $planilla->consecutivo_recaudo_planilla_detalle_i_p);
                            $empalmada->put('consecutivo_planilla_detalle', $planilla->consecutivo_recaudo_encabezado);
                            $empalmada->put('sw_conciliacion', ESiNo::getIndice(ESiNo::SI)->getId());
                            $empalmadasP->push($empalmada);
                            $setEmpalmadas->push($keyCompuesta);
                            $setDescartadas->push($keyCompuesta2);
                        } else {
                            $planillasHuerfanasP->push($mapaPlanillatipoIP->offsetGet($keyCompuesta));
                            $setDescartadas->push($keyCompuesta2);
                        }
                    }
                    //dd($setEmpalmadas);
                }

                $llavesHuerfanas = collect();
                $llavesHuerfanasConciliadas = collect();
                $listaHuerfanasAux = collect();
                $listaHuerfanasAuxP = collect();

                foreach ($planillasHuerfanas->toArray() as $key => $planilla) {
                    $verificacionUno = $llavesHuerfanas->containsStrict('consecutivo_recaudo_planilla_detalle_i', $planilla->consecutivo_recaudo_planilla_detalle_i);
                    $verificacionDos = $llavesHuerfanas->containsStrict('consecutivo_recaudo_encabezado', $planilla->consecutivo_recaudo_encabezado);

                    if (!$verificacionUno && !$verificacionDos) {
                        $llavesHuerfanas->push([
                            'consecutivo_recaudo_planilla_detalle_i' => $planilla->consecutivo_recaudo_planilla_detalle_i,
                            'consecutivo_recaudo_encabezado'         => $planilla->consecutivo_recaudo_encabezado
                        ]);
                    }
                }

                foreach ($planillasHuerfanasP->toArray() as $key => $planilla) {
                    $verificacionUno = $planillasHuerfanasP->containsStrict('consecutivo_recaudo_planilla_detalle_i_p', $planilla->consecutivo_recaudo_planilla_detalle_i_p);
                    $verificacionDos = $planillasHuerfanasP->containsStrict('consecutivo_recaudo_encabezado', $planilla->consecutivo_recaudo_encabezado);

                    if (!$verificacionUno && !$verificacionDos) {
                        $llavesHuerfanas->push([
                            'consecutivo_recaudo_planilla_detalle_i_p' => $planilla->consecutivo_recaudo_planilla_detalle_i_p,
                            'consecutivo_recaudo_encabezado'           => $planilla->consecutivo_recaudo_encabezado
                        ]);
                    }
                }

               // dd($llavesHuerfanas);

                foreach (ProcesoConciliacionPilaBanco::getSubLista($llavesHuerfanas, $this->LIMIT) as $key => $sublist) {
                    //if (count($sublist) > 0) {
                    $llavesHuerfanasConciliadas->push(ProcesoConciliacionPilaBanco::conciliarPlanillasHuerfanas($sublist));
                    //}
                }
               //dd('asa', $llavesHuerfanasConciliadas->toArray());

                // se actualizan las planillas que conciliaron y las que no siguen el proceso para planillas huerfanas
                foreach ($planillasHuerfanas->toArray() as $key => $planilla) {
                    //dd($llavesRecaudoPlanillasExitosoI->toArray(), $planillasHuerfanas, $empalmadas);

                    //dd($llavesHuerfanasConciliadas->map(function ($query) use ($planilla) { return $query->containsStrict('consecutivo_recaudo_encabezado', 936); }), $llavesHuerfanasConciliadas);
                    if ($this->containStrict($llavesHuerfanasConciliadas, $planilla->consecutivo_recaudo_encabezado)) {

                        $plan = RecRecaudoPlanillaDetalleI::where('consecutivo_recaudo_encabezado', $planilla->consecutivo_recaudo_encabezado)
                            ->where('consecutivo_recaudo_planilla_detalle_i',$planilla->consecutivo_recaudo_planilla_detalle_i)->first();
                        $plan->sw_conciliacion = ESiNo::getIndice(ESiNo::SI)->getId();
                        $plan->usuario_conciliacion = Auth::user()->id;
                        $plan->fecha_conciliacion = Carbon::now()->toDateTimeString();
                        $plan->save();
                    } else {
                        $listaHuerfanasAux->push($planilla);
                    }
                }

                foreach ($planillasHuerfanasP as $key => $planilla) {
                    if ($this->containStrict($llavesHuerfanasConciliadas,$planilla->consecutivo_recaudo_encabezado)) {
                        $plan = RecRecaudoPlanillaDetalleIPe::where('consecutivo_recaudo_encabezado', $planilla->consecutivo_recaudo_encabezado)
                            ->where('consecutivo_recaudo_planilla_detalle_i_p',$planilla->consecutivo_recaudo_planilla_detalle_i_p)->first();
                        $plan->sw_conciliacion = ESiNo::getIndice(ESiNo::SI)->getId();
                        $plan->usuario_conciliacion = Auth::user()->id;
                        $plan->fecha_conciliacion = Carbon::now()->toDateTimeString();
                        $plan->save();
                    } else {
                        $listaHuerfanasAuxP->push($planilla);
                    }
                }

                $planillasHuerfanas->forget($planillasHuerfanas->map(function ($query, $index) { return $index; })->toArray());
                $planillasHuerfanas->push($listaHuerfanasAux);
                $planillasHuerfanasP->forget($planillasHuerfanasP->map(function ($query, $index) { return $index; })->toArray());
                $planillasHuerfanasP->push($listaHuerfanasAuxP);

                // Actualizamos la planillas empalmadas con sw_conciliacion en SI
                if (($empalmadas->isNotEmpty()) || ($empalmadasP->isNotEmpty())) {
                    $llavesEmp = collect();
                    $llavesEmpP = collect();

                    foreach ($empalmadas->toArray() as $key => $x) {
                        $llavesEmp->push([
                            'consecutivo_planilla_detalle'           => $x['consecutivo_planilla_detalle'],
                            'consecutivo_recaudo_planilla_detalle_i' => $x['consecutivo_recaudo_planilla_detalle_i']
                        ]);
                    }

                    foreach ($empalmadasP->toArray() as $key => $x) {
                        $llavesEmpP->push([
                            'consecutivo_planilla_detalle'             => $x['consecutivo_planilla_detalle'],
                            'consecutivo_recaudo_planilla_detalle_i_p' => $x['consecutivo_recaudo_planilla_detalle_i_p']
                        ]);
                    }

                    $this->ejecutarConciliacionPlanillasAfiliadoSiic($llavesEmp, $mapaSalarioMinimo);
                    $this->ejecutarConciliacionPlanillasPensionadosAfiliadoSiic($llavesEmpP, $mapaSalarioMinimo);
                    $this->actualizarLogBancarioAportantesEmpalmados($empalmadas, $empalmadasP);
                    // $this->ejecutarConciliacionPlanillasPensionadosAfiliadoSiic($llavesEmp, $mapaSalarioMinimo);
                }
                dd('t',$planillasHuerfanas);
                // Sacamos tambien aparte que aportantes no empalman con las planillas, asi tengo una lista de planilla y de aportante huerfanos
               //  $aportantesHuerfanos = array();


            }

        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * @return Collection
     */
    public function getValoresSalarioMinimoXAnno()
    {
        $resultado = collect();

        $lista = TbValorCopago::all()->toArray();

        if (count($lista) > 0) {
            foreach ($lista as $key => $item) {
                $resultado->put(((string)$item['anio']), $item['valor_salario']);
            }
        }

        return $resultado;
    }

    /**
     * @param Collection $llavesEmp
     * @param Collection $mapaSalarioMinimo
     * @return bool
     */
    private function ejecutarConciliacionPlanillasAfiliadoSiic(Collection $llavesEmp, Collection $mapaSalarioMinimo)
    {
        $mapaPlanillas = new Collection();
        $mapaAfiliados = new Collection();
        $mapaAportantes = new Collection();
        $mapaAfiliadosXContributivo = new Collection();
        $mapaAfiliadoXDiasPlanilla = new Collection();
        $mapaAfiliadoXIBCPlanilla = new Collection();
        $mapaAfiliadoXCotizacionPlanilla = new Collection();
        $mapaAfiliadoXSecuenciasPlanilla = new Collection();
        $mapaConciliados = new Collection();
        $lista = new  Collection();
        $listaNumerosId = new  Collection();
        $listaNumerosNit = new  Collection();
        $setNumerosId = new  Collection();
        $setNumerosNit = new  Collection();
        $setNumerosIdNovedades = new  Collection();
        $setNumerosIdVst = new  Collection();
        $listaAfiliados = new  Collection();
        $fechaGrabado = Carbon::now()->toDateTimeString();
        $usuarioGrabado = new  Collection();
        $sql = null;
        $datosAfiliado = null;
        $datosAportante = null;
        $datosAfiliadoXContributivo = null;
        $i = null;
        $l = null;
        $sdf = new Carbon();
        $fechaInicioPeriodo = null;
        $fechaFinPeriodo = null;
        $fechaIngreso = null;
        $fechaRetiro = null;
        $fechaInicioRL1 = null;
        $fechaFinRL1 = null;
        $fechaInicioRL2 = null;
        $fechaFinRL2 = null;
        $fechaFinal = new Carbon();
        $periodoValido = false;
        $vinculacionEncontrada = false;
        $vinculacionDuplicada = false;
        $diasCotizados = 0;
        $cotizacion = 0;
        $ibc = 0;
        $ibcLimSuperior = 0;
        $ibcLimInferior = 0;
        $annoPeriodo = "";
        $idCotizantePeriodo = "";
        $secuencias = "";
        $LLAVE = 0;
        $TIPO_ID = 1;
        $NUMERO_ID = 2;
        $ESTADO_AFILIADO = 3;
        $TIPO_REGIMEN = 4;
        $LLAVE_APORTANTE = 1;
        $FECHA_INGRESO = 2;
        $FECHA_RETIRO = 3;
        $IBC = 4;
        $TIPO_COTIZANTE = 5;
        $LLAVE_INGRESO = 6;

        $llavesing = $llavesEmp->map(function ($query) {
            return $query['consecutivo_planilla_detalle'];
        });
        //array_unique($llavesing,SORT_NUMERIC);

        foreach (ProcesoConciliacionPilaBanco::getSubLista($llavesing,$this->LIMIT) as $sublist) {
            if (count($sublist) > 0) {
                $lista->push(RecRecaudoPlanillaDetalleILiquidacione::whereIn('consecutivo_recaudo_encabezado', $sublist)->get());
            }
        }
        $listas = $lista->toArray();
        foreach ($listas as $key => $lis) {
            foreach ($listas[$key] as $obj) {
                $l = $obj;
                $i = $l['recaudo_planilla_detalle_is'];
                $idCotizantePeriodo = $l['tipo_identificacion_cotizante'] . '_' . trim($l['numero_identificacion_cotizante']) . '_' . $i['periodo_pago'];
                $mapaPlanillas->put($idCotizantePeriodo,$obj);
                $setNumerosId->push(trim($l['numero_identificacion_cotizante']));
                $setNumerosNit->push(trim($i['numero_identificacion_aportante']));

                // Sumatoria de días
                if (!$mapaAfiliadoXDiasPlanilla->containsStrict($idCotizantePeriodo)) {
                    $mapaAfiliadoXDiasPlanilla->put($idCotizantePeriodo,0);
                }

                // Sumatoria de IBC
                if (!$mapaAfiliadoXIBCPlanilla->containsStrict($idCotizantePeriodo)) {
                    $mapaAfiliadoXIBCPlanilla->put($idCotizantePeriodo,0);
                }

                // Sumatoria de cotización obligatoria
                if (!$mapaAfiliadoXCotizacionPlanilla->containsStrict($idCotizantePeriodo)) {
                    $mapaAfiliadoXCotizacionPlanilla->put($idCotizantePeriodo,0);
                }

                // Concatenar secuencias
                if (!$mapaAfiliadoXSecuenciasPlanilla->containsStrict($idCotizantePeriodo)) {
                    $mapaAfiliadoXSecuenciasPlanilla->put($idCotizantePeriodo,'');
                }

                $diasCotizados = $mapaAfiliadoXDiasPlanilla->offsetGet($idCotizantePeriodo);
                $ibc = $mapaAfiliadoXIBCPlanilla->offsetGet($idCotizantePeriodo);
                $cotizacion = $mapaAfiliadoXCotizacionPlanilla->offsetGet($idCotizantePeriodo);
                $secuencias = $mapaAfiliadoXSecuenciasPlanilla->offsetGet($idCotizantePeriodo);

                if (($i['tipo_planilla'] === 'N') && (!empty($i['numero_planilla']))) {
                    if (($l['sw_correcciones'] === 'C')) {
                        $diasCotizados += $l['dias_cotizados'];
                        $ibc += $l['ingreso_base_cotizacion'];
                        $cotizacion += $l['cotizacion_obligatoria'];
                        $secuencias = $secuencias . (empty($secuencias) ? $l['secuencia'] : ',' . $l['secuencia']);
                    }
                } else {
                    $diasCotizados += $l['dias_cotizados'];
                    $ibc += $l['ingreso_base_cotizacion'];
                    $cotizacion += $l['cotizacion_obligatoria'];
                    $secuencias = $secuencias . (empty($secuencias) ? $l['secuencia'] : ',' . $l['secuencia']);
                }

                $mapaAfiliadoXDiasPlanilla->put($idCotizantePeriodo, $diasCotizados);
                $mapaAfiliadoXIBCPlanilla->put($idCotizantePeriodo, $ibc);
                $mapaAfiliadoXCotizacionPlanilla->put($idCotizantePeriodo, $cotizacion);
                $mapaAfiliadoXSecuenciasPlanilla->put($idCotizantePeriodo, $secuencias);

                // Llevar Registro de cotizantes con novedades ING, RET, IGE, LMA
                if ((!empty($l['sw_ing'])) || (!empty($l['sw_ret'])) || (!empty($l['sw_ige'])) || ((!empty($l['sw_lma'])))) {
                    $setNumerosIdNovedades->push($idCotizantePeriodo);
                }

                // Llevar registro de cotizaciones con novedad VST
                if (!empty($l['sw_vst'])) {
                    $setNumerosIdVst->push($idCotizantePeriodo);
                }
            }
        }

        foreach ($setNumerosId->toArray() as $key => $id) {
            $listaNumerosId->push($id);
        }

        foreach ($setNumerosNit->toArray() as $key => $nit) {
            $listaNumerosNit->push($nit);
        }

        $sql = DB::table('as_afiliados')->select('id','tipo_identificacion','identificacion','estado','as_regimene_id');
        // Consultar afiliados por números de identificación
        foreach (ProcesoConciliacionPilaBanco::getSubLista($listaNumerosId, $this->LIMIT) as $Akey => $sublist) {
            if (count($sublist) > 0) {
                $listaTemp = $sql->whereIn('identificacion', $sublist)->get();
                foreach ($listaTemp->toArray() as $key => $o) {
                    $mapaAfiliados->put($o->tipo_identificacion . '-' . $o->identificacion, $o);
                    $listaAfiliados->put('id',$o->id);
                }
            }
        }

        $sql = DB::table('as_afiliado_pagador');
        // Consultar afiliados por números de identificación
        foreach (ProcesoConciliacionPilaBanco::getSubLista($listaAfiliados, $this->LIMIT) as $Ikey => $sublist) {
            if (count($sublist) > 0) {
                $listas = array_values($sublist);
                $listaTemp = $sql->whereIn('as_afiliado_id', $listas)->get();
                //dd($listaTemp->toArray());
                foreach ($listaTemp->toArray() as $key => $o) {

                    if (!$mapaAfiliadosXContributivo->containsStrict($o->as_afiliado_id)) {
                        $mapaAfiliadosXContributivo->put($o->as_afiliado_id, collect());
                    }
                    $mapaAfiliadosXContributivo->offsetGet($o->as_afiliado_id)->push($o);
                }
            }
        }

        $sql = DB::table('as_pagadores');
        // Consultar afiliados por números de identificación
        foreach (ProcesoConciliacionPilaBanco::getSubLista($listaNumerosNit, $this->LIMIT) as $Ikey => $sublist) {
            if (count($sublist) > 0) {
                $listaTemp = $sql->whereIn('identificacion', $sublist)->get();
                foreach ($listaTemp->toArray() as $key => $o) {
                    $mapaAportantes->put($o->identificacion, $o);
                }
            }
        }

        foreach ($mapaPlanillas->toArray() as $key => $o) {
            $l = $o;
            $i = $l['recaudo_planilla_detalle_is'];

            $idCotizantePeriodo = $l['tipo_identificacion_cotizante'] .'_'.$l['numero_identificacion_cotizante'].'_'.$i['periodo_pago'];
            $fechaInicioPeriodo = Carbon::parse($i['periodo_pago'].'-01');
            $fechaFinPeriodo = Carbon::parse($fechaInicioPeriodo)->endOfMonth();
            $annoPeriodo = substr($i['periodo_pago'],0,4);

            $obj = new RecConciliacionPlanillaAfiliado();
            $obj->consecutivo_recaudo_encabezado = $i['consecutivo_recaudo_encabezado'];
            $obj->secuencia = $l['secuencia'];
            $obj->fecha_grabado = $fechaGrabado;
            $obj->usuario_grabado = Auth::user()->id;
            $obj->secuencias_planilla = $mapaAfiliadoXSecuenciasPlanilla->offsetGet($idCotizantePeriodo);

            $datosAfiliado = $mapaAfiliados->offsetGet(($l['tipo_identificacion_cotizante'] . '-'. $l['numero_identificacion_cotizante']));

            if ($datosAfiliado === null) {
                $obj->inexactitudes = 'Afiliado NO EXISTE';
            } else {
                $datosAportante = $mapaAportantes->offsetGet($i['numero_identificacion_aportante']);

                $obj->afiliado = $datosAfiliado->id;
                $obj->estado_siic = $datosAfiliado->estado;
                $obj->regimen_siic = $datosAfiliado->as_regimene_id;
                $obj->tipo_cotizante_planilla = $l['tipo_cotizante'];
                $obj->dias_cotizados_planilla = $mapaAfiliadoXDiasPlanilla->offsetGet($idCotizantePeriodo);
                $obj->ibc_planilla = $mapaAfiliadoXIBCPlanilla->offsetGet($idCotizantePeriodo);
                $obj->cotizacion_obligatoria_planilla = $mapaAfiliadoXCotizacionPlanilla->offsetGet($idCotizantePeriodo);

                if ($datosAportante !== null && ($mapaAfiliadosXContributivo->offsetGet($datosAfiliado->id) !== null)) {
                    foreach ($mapaAfiliadosXContributivo->offsetGet($datosAfiliado->id) as $key => $ingresoCon) {
                        if ($datosAportante->id === $ingresoCon->as_pagador_id) {
                            $liquidacion = CpLiquidacione::with('aportes')->where('relacion_laboral_id', $datosAportante->id)->latest()->first()->toArray();
                            $fechaIngreso = Date::parse($ingresoCon->fecha_vinculacion);
                            $fechaRetiro = (($liquidacion['retiro'] !== 0) ? Date::parse($liquidacion['aportes'][0]['fecha_pago']) : null);

                            $periodoValido = ($fechaInicioPeriodo->greaterThanOrEqualTo($fechaIngreso) && $fechaFinPeriodo->isAfter($fechaIngreso) && ($fechaRetiro === null || $fechaInicioPeriodo->lessThanOrEqualTo($fechaRetiro)) || ($fechaFinPeriodo->isAfter($fechaIngreso) && ($fechaRetiro === null || ($fechaInicioPeriodo->isBefore($fechaRetiro)))));
                            if ($periodoValido) {
                                if ($vinculacionEncontrada && $datosAfiliadoXContributivo !== null) {
                                    $fechaInicioRL1 = Carbon::parse($datosAfiliadoXContributivo->fecha_viculacion);
                                    $fechaFinRL1    = Carbon::parse($liquidacion['aportes'][0]['fecha_pago']);
                                    $fechaInicioRL2 = Carbon::parse($ingresoCon->fecha_viculacion);
                                    $fechaFinRL2    = Carbon::parse($liquidacion['aportes'][0]['fecha_pago']);

                                    $fechaFinRL1 = ($fechaFinRL1 == null ? $fechaFinal : $fechaFinRL1);
                                    $fechaFinRL2 = ($fechaFinRL2 == null ? $fechaFinal : $fechaFinRL2);

                                    if (($fechaInicioRL1->greaterThanOrEqualTo($fechaInicioRL2) && $fechaFinRL1->isAfter($fechaInicioRL2) && ($fechaFinRL2 == null || $fechaInicioRL1->lessThanOrEqualTo($fechaFinRL2))) || ($fechaFinRL1->isAfter($fechaInicioRL2) && ($fechaFinRL2 == null || ($fechaInicioRL1->isBefore($fechaFinRL2))))) {
                                        $vinculacionDuplicada = true;
                                    }
                                }
                                $vinculacionEncontrada = true;
                                $datosAfiliadoXContributivo = $ingresoCon;
                            }
                        }
                    }
                }

                if ($datosAfiliadoXContributivo !== null) {
                    if ($vinculacionDuplicada) {
                        $obj->inexactitudes = 8; // VINCULACIÓN DUPLICADA
                    } else {
                        if ($fechaInicioPeriodo->greaterThanOrEqualTo($fechaIngreso)) {
                            if ($fechaRetiro === null || $fechaFinPeriodo->lessThanOrEqualTo($fechaRetiro)) {
                                $diasCotizados = 30;
                            } else {
                                $diasCotizados = ((int) $fechaInicioPeriodo->diff($fechaRetiro));
                            }
                        } else {
                            if ($fechaRetiro === null || ($fechaFinPeriodo->lessThanOrEqualTo($fechaRetiro))) {
                                $diasCotizados = (30 - ((int) $fechaInicioPeriodo->diff($fechaIngreso)));
                            } else {
                                $diasCotizados = ((int) $fechaIngreso->diff($fechaRetiro));
                            }
                        }

                        $obj->consecutivo_aportante            = $datosAfiliadoXContributivo->as_pagador_id;
                        $obj->consecutivo_ingreso_contributivo = $datosAfiliadoXContributivo->id;
                        $obj->tipo_cotizante_siic              = str_pad(((string) $datosAfiliadoXContributivo->tipo_cotizante),2,'0', STR_PAD_RIGHT);
                        $obj->fecha_ingreso_siic               = $fechaIngreso->toDateString();
                        $obj->fecha_retiro_siic                = $fechaRetiro->toDateString();
                        $obj->dias_cotizados_siic              = $diasCotizados;
                        $obj->ibc_siic                         = round(((double) $datosAfiliadoXContributivo->ibc / 30) * $diasCotizados);
                        $obj->valor_liquidacion_esperada       = $this->redondearNumeroAux(((double) $obj->ibc_siic * $l['tarifa']),2);
                        $obj->valor_cartera                    =  (double) ($obj->valor_liquidacion_esperada - $obj->cotizacion_obligatoria_planilla);

                        if ($obj->dias_cotizados_siic > $mapaAfiliadoXDiasPlanilla->offsetGet($idCotizantePeriodo) && !$setNumerosIdNovedades->containsStrict($idCotizantePeriodo)) {
                            $obj->inexactitudes = 'INEXACTITUD DIAS COTIZADOS';
                        } elseif ($obj->dias_cotizados_siic < $mapaAfiliadoXDiasPlanilla->offsetGet($idCotizantePeriodo) && !$setNumerosIdNovedades->containsStrict($idCotizantePeriodo)) {
                            $obj->inexactitudes = 'INCONSISTENCIA MAYOR DIAS';
                        }

                        $ibcLimSuperior = $this->redondearNumeroAux($obj->ibc_siic, 4);
                        $ibcLimInferior = $ibcLimSuperior - 10000;

                        if (($mapaAfiliadoXIBCPlanilla->offsetGet($idCotizantePeriodo) < $ibcLimInferior) && (!$setNumerosIdNovedades->containsStrict($idCotizantePeriodo)) && (!$setNumerosIdVst->containsStrict($idCotizantePeriodo))) {
                            $obj->inexactitudes = ((!empty($obj->inexactitudes)) ? $obj->inexactitudes . ', ' :  '') . 'INEXACTITUD IBC';
                        } elseif (($mapaAfiliadoXIBCPlanilla->offsetGet($idCotizantePeriodo) > $ibcLimSuperior) && (!$setNumerosIdNovedades->containsStrict($idCotizantePeriodo)) && (!$setNumerosIdVst->containsStrict($idCotizantePeriodo))) {
                            $obj->inexactitudes = ((!empty($obj->inexactitudes)) ? $obj->inexactitudes . ', ' :  '') . 'DIFERENCIA IBC MENOR';
                        }

                        if ($obj->tipo_cotizante_siic === $l['tipo_cotizante']) {
                            $obj->inexactitudes = ((!empty($obj->inexactitudes)) ? $obj->inexactitudes . ', ' :  '') . 'INEXACTITUD TIPO COTIZANTE';
                        }

                        if ($datosAfiliadoXContributivo->ibc === $mapaSalarioMinimo->offsetGet($annoPeriodo)) {
                            $obj->sw_salario_minimo = ESiNo::getIndice(ESiNo::SI)->getId();
                        } else {
                            $obj->sw_salario_minimo = ESiNo::getIndice(ESiNo::NO)->getId();
                        }
                        $mapaConciliados->put($obj->consecutivo_ingreso_contributivo."_". $i['periodo_pago'], $obj);
                    }
                } else {
                    $obj->inexactitudes = 'OMISION VINCULACION';
                }
            }
            $obj->save();
            $datosAfiliado = null;
            $datosAfiliadoXContributivo = null;
            $datosAportante = null;
            $vinculacionEncontrada = false;
            $vinculacionDuplicada = false;
        }

        $this->actualizarLqContributivoEsperada($mapaConciliados, null);
        return true;
    }

    /**
     * @param Collection $llavesRecaudo
     * @param Collection $mapaSalarioMinimo
     * @return bool
     */
    public function ejecutarConciliacionPlanillasPensionadosAfiliadoSiic(Collection $llavesRecaudo, Collection $mapaSalarioMinimo)
    {
        $mapaPlanillas = collect();
        $mapaAfiliados = collect();
        $mapaAportantes = collect();
        $mapaAfiliadosXContributivo = collect();
        $mapaConciliados = collect();
        $mapaAfiliadoXDiasPlanilla = collect();
        $mapaAfiliadoXIBCPlanilla = collect();
        $mapaAfiliadoXCotizacionPlanilla = collect();
        $mapaAfiliadoXSecuenciasPlanilla = collect();
        $lista = collect();
        $listaNumerosId = collect();
        $listaNumerosNit = collect();
        $setNumerosId = collect();
        $setNumerosNit = collect();
        $setNumerosIdNovedades = collect();
        $setNumerosIdVsp = collect();
        $listaAfiliados = collect();
        $fechaGrabado = Carbon::now()->toDateTimeString();
        $usuarioGrabado = Auth::user()->id;
        $idPensionadoPeriodo = "";
        $secuencias = "";
        $sql = null;
        $datosAfiliado = null;
        $datosAportante = null;
        $datosAfiliadoXContributivo = null;
        $i = new RecRecaudoPlanillaDetalleIPe();
        $l = new RecRecaudoPlanillaDetalleIPLiquidacione();
        $calendar = new Carbon();
        $sdf = new Date();
        $fechaInicioPeriodo = null;
        $fechaFinPeriodo = null;
        $fechaIngreso = null;
        $fechaRetiro = null;
        $fechaInicioRL1 = null;
        $fechaFinRL1 = null;
        $fechaInicioRL2 = null;
        $fechaFinRL2 = null;
        $fechaFinal = Carbon::parse("2999-12-31");
        $periodoValido = false;
        $vinculacionEncontrada = false;
        $vinculacionDuplicada = false;
        $diasCotizados = 0;
        $cotizacion = 0;
        $ibc = 0;
        $ibcSiicLimSuperior = 0;
        $ibcSiicLimInferior = 0;
        $LLAVE = 0;
        $TIPO_ID = 1;
        $NUMERO_ID = 2;
        $ESTADO_AFILIADO = 3;
        $TIPO_REGIMEN = 4;
        $LLAVE_APORTANTE = 1;
        $FECHA_INGRESO = 2;
        $FECHA_RETIRO = 3;
        $IBC = 4;
        $TIPO_COTIZANTE = 5;
        $LLAVE_INGRESO = 6;

        $llavesing = $llavesRecaudo->map(function ($query) {
            return $query['consecutivo_planilla_detalle'];
        });
        //array_unique($llavesing,SORT_NUMERIC);
        foreach (ProcesoConciliacionPilaBanco::getSubLista($llavesing,$this->LIMIT) as $sublist) {
            if (count($sublist) > 0) {
                $lista->push(RecRecaudoPlanillaDetalleIPLiquidacione::whereIn('consecutivo_recaudo_encabezado', $sublist)->get());
            }
        }
        $listas = $lista->toArray();
        //$items = RecRecaudoPlanillaDetalleIPLiquidacione::whereIn('consecutivo_recaudo_encabezado', $llavesing)->get();
        foreach ($listas as $keygen => $lis) {
            foreach ($listas[$keygen] as $obj) {
                $l = $obj;
                $i = $obj['recaudo_planilla_detalle_ip'];
                $idCotizantePeriodo = $l['tipo_identificacion_cotizante'] . '_' . trim($l['numero_identificacion_cotizante']) . '_' . $i['periodo_pago'];
                $mapaPlanillas->put($idCotizantePeriodo,$obj);
                $setNumerosId->push(trim($l['numero_identificacion_cotizante']));
                $setNumerosNit->push(trim($i['numero_identificacion_aportante']));

                // Sumatoria de días
                if (!$mapaAfiliadoXDiasPlanilla->containsStrict($idCotizantePeriodo)) {
                    $mapaAfiliadoXDiasPlanilla->put($idCotizantePeriodo,0);
                }
                // Sumatoria de IBC
                if (!$mapaAfiliadoXIBCPlanilla->containsStrict($idCotizantePeriodo)) {
                    $mapaAfiliadoXDiasPlanilla->put($idCotizantePeriodo, 0);
                }
                // Sumatoria de cotización obligatoria
                if (!$mapaAfiliadoXCotizacionPlanilla->containsStrict($idCotizantePeriodo)) {
                    $mapaAfiliadoXCotizacionPlanilla->put($idCotizantePeriodo,0);
                }
                // Contacter secuencias
                if (!$mapaAfiliadoXSecuenciasPlanilla->containsStrict($idCotizantePeriodo)) {
                    $mapaAfiliadoXSecuenciasPlanilla->put($idCotizantePeriodo,"");
                }

                $diasCotizados = $mapaAfiliadoXDiasPlanilla->offsetGet($idCotizantePeriodo);
                $ibc = $mapaAfiliadoXIBCPlanilla->offsetGet($idCotizantePeriodo);
                $cotizacion = $mapaAfiliadoXCotizacionPlanilla->offsetGet($idCotizantePeriodo);
                $secuencias = $mapaAfiliadoXSecuenciasPlanilla->offsetGet($idCotizantePeriodo);

                // Agregar validación para planillas tipo N
                if (($i['tipo_planilla'] === 'N') && (!empty($i['numero_planilla']))) {
                    if ($l['sw_correcciones'] === 'C') {
                        $diasCotizados += ($l['dias_cotizados']);
                        $ibc += ($l['ingreso_base_cotizacion']);
                        $cotizacion += $l['cotizacion_obligatoria'];
                        $secuencias += ((empty($secuencias)) ? $l['secuencia'] : (',' . $l['secuencia']));
                    }
                } else {
                    $diasCotizados += ($l['dias_cotizados']);
                    $ibc += ($l['ingreso_base_cotizacion']);
                    $cotizacion += $l['cotizacion_obligatoria'];
                    $secuencias += ((empty($secuencias)) ? $l['secuencia'] : (',' . $l['secuencia']));
                }

                $mapaAfiliadoXDiasPlanilla->put($idCotizantePeriodo, $diasCotizados);
                $mapaAfiliadoXIBCPlanilla->put($idCotizantePeriodo, $ibc);
                $mapaAfiliadoXCotizacionPlanilla->put($idCotizantePeriodo, $cotizacion);
                $mapaAfiliadoXSecuenciasPlanilla->put($idCotizantePeriodo, $secuencias);

                // llevar registro de cotizantes con novedades ING, RET, IGE, LMA
                if ((!empty($l['sw_ing'])) || (!empty($l['sw_ret'])) || (!empty($l['sw_ige'])) || (!empty($l['sw_lma']))) {
                    $setNumerosIdNovedades->push($idCotizantePeriodo);
                }

                // Llevar registro de cotizaciones con novedad VST
                if (!empty($l['sw_vst'])) {
                    $setNumerosIdVsp->push($idCotizantePeriodo);
                }
            }
        }

        foreach ($setNumerosId->toArray() as $key => $id) {
            $listaNumerosId->push($id);
        }

        foreach ($setNumerosNit->toArray() as $key => $nit) {
            $listaNumerosNit->push($nit);
        }

        $sql = DB::table('as_afiliados')->select('id','tipo_identificacion','identificacion','estado','as_regimene_id');
        // Consultar afiliados por números de identificación
        foreach (ProcesoConciliacionPilaBanco::getSubLista($listaNumerosId, $this->LIMIT) as $Akey => $sublist) {
            if (count($sublist) > 0) {
                $listaTemp = $sql->whereIn('identificacion', $sublist)->get();
                foreach ($listaTemp->toArray() as $key => $o) {
                    $mapaAfiliados->put($o->tipo_identificacion . '-' . $o->identificacion, $o);
                    $listaAfiliados->put('id',$o->id);
                }
            }
        }

        $sql = DB::table('as_afiliado_pagador');
        // Consultar afiliados por números de identificación
        foreach (ProcesoConciliacionPilaBanco::getSubLista($listaAfiliados, $this->LIMIT) as $Ikey => $sublist) {
            if (count($sublist) > 0) {
                $listas = array_values($sublist);
                $listaTemp = $sql->whereIn('as_afiliado_id', $listas)->get();
                //dd($listaTemp->toArray());
                foreach ($listaTemp->toArray() as $key => $o) {
                    if (!$mapaAfiliadosXContributivo->containsStrict($o->as_afiliado_id)) {
                        $mapaAfiliadosXContributivo->put($o->as_afiliado_id, collect());
                    }
                    $mapaAfiliadosXContributivo->offsetGet($o->as_afiliado_id)->push($o);
                }
            }
        }

        $sql = DB::table('as_pagadores');
        // Consultar afiliados por números de identificación
        foreach (ProcesoConciliacionPilaBanco::getSubLista($listaNumerosNit, $this->LIMIT) as $Ikey => $sublist) {
            if (count($sublist) > 0) {
                $listaTemp = $sql->whereIn('identificacion', $sublist)->get();
                foreach ($listaTemp->toArray() as $key => $o) {
                    $mapaAportantes->put($o->identificacion, $o);
                }
            }
        }

        foreach ($mapaPlanillas->toArray() as $key => $item) {
            $l = $o;
            $i = $l['recaudo_planilla_detalle_ip'];
            $fechaInicioPeriodo = $sdf->parse($i['periodo_pago'].'-01');
            $fechaFinPeriodo = $fechaInicioPeriodo->endOfMonth();
            $idPensionadoPeriodo = $l['tipo_identificacion_pensionado'].'_'.$l['numero_identificacion_pensionado'].'_'.$l['periodo_pago'];

            $obj = new RecConciliacionPlanillaAfiliado();
            $obj->consecutivo_recaudo_encabezado = $i['consecutivo_recaudo_encabezado'];
            $obj->secuencia = $l['secuencia'];
            $obj->fecha_grabado = $fechaGrabado;
            $obj->usuario_grabado = $usuarioGrabado;
            $obj->secuencias_planilla = $mapaAfiliadoXSecuenciasPlanilla->offsetGet($idPensionadoPeriodo);

            $datosAfiliado = $mapaAfiliados->offsetGet($l['tipo_identificacion_pensionado'].'-'.$l['numero_identificacion_pensionado']);

            if ($datosAfiliado == null) {
                $obj->inexactitudes = 'AFILIADO NO EXISTE';
            } else {
                $datosAportante = $mapaAportantes->offsetGet($i['numero_identificacion_pagador']);

                $obj->afiliado = $datosAfiliado->id;
                $obj->estado_siic = $datosAfiliado->estado;
                $obj->regimen_siic = $datosAfiliado->as_regimene_id;
                $obj->tipo_cotizante_planilla = $l['tipo_pensionado'];
                $obj->dias_cotizados_planilla = $mapaAfiliadoXDiasPlanilla->offsetGet($idPensionadoPeriodo);
                $obj->ibc_planilla = $mapaAfiliadoXIBCPlanilla->offsetGet($idPensionadoPeriodo);
                $obj->cotizacion_obligatoria_planilla = $mapaAfiliadoXCotizacionPlanilla->offsetGet($idPensionadoPeriodo);

                if ($datosAportante !== null && ($mapaAfiliadosXContributivo->offsetGet($datosAfiliado->id) !== null)) {
                    $obj->consecutivo_aportante = $datosAportante->id;
                    $mapaAfiliados = $mapaAfiliadosXContributivo->offsetGet($datosAfiliado->id);
                    foreach ($mapaAfiliados as $key => $ingresoCon) {
                        if ($datosAportante->id === $ingresoCon->as_pagador_id) {
                            $liquidacion = CpLiquidacione::with('aportes')->where('relacion_laboral_id', $datosAportante->id)->latest()->first()->toArray();
                            $fechaIngreso = Date::parse($ingresoCon->fecha_vinculacion);
                            $fechaRetiro = (($liquidacion['retiro'] !== 0) ? Date::parse($liquidacion['aportes'][0]['fecha_pago']) : null);

                            $periodoValido = ($fechaInicioPeriodo->greaterThanOrEqualTo($fechaIngreso) && $fechaFinPeriodo->isAfter($fechaIngreso) && ($fechaRetiro === null || $fechaInicioPeriodo->lessThanOrEqualTo($fechaRetiro)) || ($fechaFinPeriodo->isAfter($fechaIngreso) && ($fechaRetiro === null || ($fechaInicioPeriodo->isBefore($fechaRetiro)))));
                            if ($periodoValido) {
                                if ($vinculacionEncontrada && $datosAfiliadoXContributivo !== null) {
                                    $fechaInicioRL1 = Carbon::parse($datosAfiliadoXContributivo->fecha_viculacion);
                                    $fechaFinRL1    = Carbon::parse($liquidacion['aportes'][0]['fecha_pago']);
                                    $fechaInicioRL2 = Carbon::parse($ingresoCon->fecha_viculacion);
                                    $fechaFinRL2    = Carbon::parse($liquidacion['aportes'][0]['fecha_pago']);

                                    $fechaFinRL1 = ($fechaFinRL1 == null ? $fechaFinal : $fechaFinRL1);
                                    $fechaFinRL2 = ($fechaFinRL2 == null ? $fechaFinal : $fechaFinRL2);

                                    if (($fechaInicioRL1->greaterThanOrEqualTo($fechaInicioRL2) && $fechaFinRL1->isAfter($fechaInicioRL2) && ($fechaFinRL2 == null || $fechaInicioRL1->lessThanOrEqualTo($fechaFinRL2))) || ($fechaFinRL1->isAfter($fechaInicioRL2) && ($fechaFinRL2 == null || ($fechaInicioRL1->isBefore($fechaFinRL2))))) {
                                        $vinculacionDuplicada = true;
                                    }
                                }
                                $vinculacionEncontrada = true;
                                $datosAfiliadoXContributivo = $ingresoCon;
                            }
                        }
                    }
                }

                if ($datosAfiliadoXContributivo !== null) {
                    if ($vinculacionDuplicada) {
                        $obj->inexactitudes = 'VINCULACION DUPLICADA'; // VINCULACIÓN DUPLICADA
                    } else {
                        if ($fechaInicioPeriodo->greaterThanOrEqualTo($fechaIngreso) === true) {
                            if ($fechaRetiro === null || $fechaFinPeriodo->lessThanOrEqualTo($fechaRetiro)) {
                                $diasCotizados = 30;
                            } else {
                                $diasCotizados = ((int) $fechaInicioPeriodo->diff($fechaRetiro));
                            }
                        } else {
                            if ($fechaRetiro === null || ($fechaFinPeriodo->lessThanOrEqualTo($fechaRetiro))) {
                                $diasCotizados = (30 - ((int) $fechaInicioPeriodo->diff($fechaIngreso)));
                            } else {
                                $diasCotizados = ((int) $fechaIngreso->diff($fechaRetiro));
                            }
                        }

                        $obj->consecutivo_aportante            = $datosAfiliadoXContributivo->as_pagador_id; // LALLA
                        $obj->consecutivo_ingreso_contributivo = $datosAfiliadoXContributivo->id;
                        $obj->tipo_cotizante_siic              = str_pad(((string) $datosAfiliadoXContributivo->tipo_cotizante),2,'0', STR_PAD_RIGHT);
                        $obj->fecha_ingreso_siic               = $fechaIngreso->toDateString();
                        $obj->fecha_retiro_siic                = $fechaRetiro->toDateString();
                        $obj->dias_cotizados_siic              = $diasCotizados;
                        $obj->ibc_siic                         = round(((double) $datosAfiliadoXContributivo->ibc / 30) * $diasCotizados);
                        $obj->valor_liquidacion_esperada       = $this->redondearNumeroAux(((double) $obj->ibc_siic * $l['tarifa']),2);
                        $obj->valor_cartera                    =  (double) ($obj->valor_liquidacion_esperada - ($mapaAfiliadoXCotizacionPlanilla->offsetGet($idPensionadoPeriodo)));
                        // $obj->valor_cartera                    =  (double) ($obj->valor_liquidacion_esperada - $obj->cotizacion_obligatoria_planilla);

                        if (($obj->dias_cotizados_siic > $mapaAfiliadoXDiasPlanilla->offsetGet($idPensionadoPeriodo)) && (!$setNumerosIdNovedades->containsStrict($idPensionadoPeriodo))) {
                            $obj->inexactitudes = 'INEXACTITUD DIAS COTIZADOS';
                        } elseif (($obj->dias_cotizados_siic < $mapaAfiliadoXDiasPlanilla->offsetGet($idPensionadoPeriodo)) && (!$setNumerosIdNovedades->containsStrict($idPensionadoPeriodo))) {
                            $obj->inexactitudes = 'INCONSISTENCIA MAYOR DIAS';
                        }

                        // Validar por rango redondeado en miles
                        $ibcSiicLimSuperior = $this->redondearNumeroAux($obj->ibc_siic, 4);
                        $ibcSiicLimInferior = ($ibcSiicLimSuperior - 10000);

                        if (($mapaAfiliadoXIBCPlanilla->offsetGet($idPensionadoPeriodo) < $ibcSiicLimInferior) && (!$setNumerosIdNovedades->containsStrict($idPensionadoPeriodo))) {
                            $obj->inexactitudes = ((!empty($obj->inexactitudes)) ? $obj->inexactitudes . ', ' :  '') . 'INEXACTITUD IBC';
                        } elseif (($mapaAfiliadoXIBCPlanilla->offsetGet($idPensionadoPeriodo) > $ibcSiicLimInferior) && (!$setNumerosIdNovedades->containsStrict($idPensionadoPeriodo))) {
                            $obj->inexactitudes = ((!empty($obj->inexactitudes)) ? $obj->inexactitudes . ', ' :  '') . 'DIFERENCIA IBC MENOR';
                        }

                        $mapaConciliados->put($obj->consecutivo_ingreso_contributivo."_". $i['periodo_pago'], $obj);
                    }
                } else {
                    $obj->inexactitudes = 'OMISION VINCULACION';
                }
            }
            $obj->save();
            $datosAfiliado = null;
            $datosAfiliadoXContributivo = null;
            $datosAportante = null;
            $vinculacionEncontrada = false;
            $vinculacionDuplicada = false;
        }

        $this->actualizarLqContributivoEsperada($mapaConciliados, null);
        return true;
    }

    /**
     * @param Collection $listaEmpalmadas
     * @param Collection $listaEmpalmadasP
     */
    public function actualizarLogBancarioAportantesEmpalmados (Collection $listaEmpalmadas, Collection $listaEmpalmadasP) {
        foreach ($listaEmpalmadas as $key => $d) {
            $pla = RecRecaudoPlanillaDetalleI::where('consecutivo_recaudo_encabezado', $d->consecutivo_planilla_detalle)->first();

            $RECAUDO_APORTANTE = 1;
            if ($d->tipo_archivo === $RECAUDO_APORTANTE) {
                $ap = RecLogBancarioDetalleAportante::where('consecutivo_log_detalle_aportante', $d->consecutivo_log_detalle_aportante)->first();
                $ap->sw_conciliacion = $d->sw_conciliacion;
                $ap->consecutivo_recaudo_planilla_detalle_i = $pla['consecutivo_recaudo_planilla_detalle_i'];
                $ap->usuario_conciliacion =  Auth::user()->id;
                $ap->fecha_conciliacion = Carbon::now()->toDateTimeString();
                $ap->save();

                if (!is_null($pla)) {
                    $pla->consecutivo_log_bancario_aportante = $d->consecutivo_log_detalle_aportante;
                }
            } else {
                $ap = RecLogBancarioDetalleAportanteSgp::where('consecutivo_log_detalle_aportante_sgp', $d->consecutivo_recaudo_planilla_detalle_i_p)->first();
            }
        }
    }



    /**
     * @param Collection $mapaConciliados
     * @param Collection $mapaIncumplimiento
     */
    public function actualizarLqContributivoEsperada (Collection $mapaConciliados, Collection $mapaIncumplimiento) {
        $listaLqEsperada = collect();
        $sdfPeriodo = new Carbon();
        $sdfFecha = new Carbon();
        $setPeriodos = collect();
        $setIngresos = collect();

        $objConciliacion = null;
        $objIncumplimiento = null;
        $llaveIngresoPeriodo = "";
        $llaveMapa = null;
        $sql = null;
        $query = null;

        if ($mapaConciliados !== null && $mapaConciliados->isNotEmpty()) {
            foreach ($mapaConciliados->toArray() as $key => $e) {
                $llaveMapa = explode('_', $key);
                $setIngresos->push($llaveMapa[0]);
                $setPeriodos->push($sdfFecha->parse($llaveMapa[1].'-01')->format('Y-m-d'));
            }
        }

        if ($mapaIncumplimiento !== null && $mapaIncumplimiento->isNotEmpty()) {
            foreach ($mapaIncumplimiento->toArray() as $key => $e) {
                $llaveMapa = explode('_', $key);
                $setIngresos->push($llaveMapa[0]);
                $setPeriodos->push($sdfFecha->parse($llaveMapa[1].'-01')->format('Y-m-d'));
            }
        }

        if (!$setIngresos->isEmpty() && !$setPeriodos->isEmpty()) {
            foreach (ProcesoConciliacionPilaBanco::getSubLista($setIngresos, $this->LIMIT) as $key => $sublist) {
                if (count($sublist) > 0) {
                    $sql = LqContributivoEsperada::whereIn('periodo_servicio', $setPeriodos->toArray())
                        ->whereIn('consecutivo_ingreso_contributivo', $sublist)
                        ->where('sw_conciliado', ESiNo::getIndice(ESiNo::NO)->getId())->get();
                    $listaLqEsperada->push($sql);
                }
            }

            $lgEsperadas = $listaLqEsperada->toArray();
            foreach ($lgEsperadas as $key => $item) {
                foreach ($lgEsperadas[$key] as $itemKey => $lq) {
                    $llaveIngresoPeriodo = $lq['consecutivo_ingreso_contributivo'] . '_' . $sdfPeriodo->parse($lq['periodo_servicio'])->format('Y-m-d');

                    if ($mapaConciliados !== null && $mapaConciliados->containsStrict($llaveIngresoPeriodo)) {
                        $objConciliacion = $mapaConciliados->offsetGet($llaveIngresoPeriodo);
                        $lqUpdate = LqContributivoEsperada::where('periodo_servicio', $setPeriodos->toArray())
                            ->where('consecutivo_ingreso_contributivo', $sublist)
                            ->where('sw_conciliado', ESiNo::getIndice(ESiNo::NO)->getId())->first();
                        $lqUpdate->dias_externo = $objConciliacion->dias_cotizados_siic;
                        $lqUpdate->ibc_externo = ((double) $objConciliacion->ibc_siic);
                        $lqUpdate->valor_liquidacion_esperada_externa = ((double) $objConciliacion->valor_liquidacion_esperada);
                        $lqUpdate->cartera_externo = ((double) $objConciliacion->valor_cartera);
                        $lqUpdate->sw_conciliado = (ESiNo::getIndice(ESiNo::SI)->getId());
                        $lqUpdate->save();
                    }
                }

                if ($mapaIncumplimiento !== null && $mapaIncumplimiento->containsStrict($llaveIngresoPeriodo)) {
                    $objConciliacion = $mapaIncumplimiento->offsetGet($llaveIngresoPeriodo);
                    $lqUpdate = LqContributivoEsperada::where('periodo_servicio', $setPeriodos->toArray())
                        ->where('consecutivo_ingreso_contributivo', $sublist)
                        ->where('sw_conciliado', ESiNo::getIndice(ESiNo::NO)->getId())->first();
                    $lqUpdate->periodo_pago = $lq['periodo_servicio'];
                    $lqUpdate->dias_externo = $objConciliacion->dias_esperados;
                    $lqUpdate->ibc_externo = ((double) $objConciliacion->ibc_esperado);
                    $lqUpdate->valor_liquidacion_esperada_externa = ((double) $objConciliacion->valor_liquidacion_esperada);
                    $lqUpdate->cartera_externo = ((double) $objConciliacion->valor_cartera);
                    $lqUpdate->sw_conciliado = (ESiNo::getIndice(ESiNo::NO)->getId());
                    $lqUpdate->save();
                }
            }
        }

    }
    
    
    // Metodos posigbles de refactorizar

    /**
     * @param float $num
     * @param int $factor
     * @return float|int
     */
    public function redondearNumeroAux (float $num, int  $factor)
    {
        $redondeo = pow(10, $factor);

        $numAux = ((double) $num / $redondeo);
        $item = explode('.', $numAux);
        $cadena = (string) $item[0];
        $num = (double) $cadena;

        if (($numAux - $num) >= 0) {
            $num = ($num + 1) * $redondeo;
        } else {
            $num = ($num) * $redondeo;
        }

        return $num;
    }

    /**
     * @param Collection $llavesHuerfanasConciliadas
     * @param int $value
     * @return bool
     */
    private function containStrict(Collection $llavesHuerfanasConciliadas, int $value): bool
    {
        $containers = $llavesHuerfanasConciliadas->toArray();
        foreach ($containers as $key => $conciliada) {
            if (count($conciliada) > 0) {
                foreach ($containers[$key] as $itemKey => $item) {
                    foreach ($containers[$key][$itemKey] as $itemUno) {
                        return $itemUno['consecutivo_recaudo_encabezado'] === $value;
                    }
                }
            } else {
                return false;
            }
        }
    }


}
