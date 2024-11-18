<?php 

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;

use App\Models\Aseguramiento\BDUA\LogNsEncabezado;
use App\Models\Aseguramiento\BDUA\LogMsEncabezado;
use Illuminate\Support\Facades\Auth;
use App\Models\Aseguramiento\BDUA\LogNsDetalle;
use App\Models\Enums\ETipoBdua;
use Illuminate\Support\Facades\DB;
use App\Models\Aseguramiento\BDUA\LogMsDetalle;
use App\Models\Enums\ESiNo;
use Illuminate\Support\Facades\Log;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadoresV2\S1Automatico1;
use App\Models\Aseguramiento\AsFormtrasmov;
use App\Models\Aseguramiento\BDUA\AfAfiliadoMs;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadoresV2\R1Automatico;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadoresV2\S2;
use App\Models\Aseguramiento\AsNovtramite;

class ProcesaBDUA {
    
    protected $tipo;
    protected $archivo;
    protected $finaliza;
    protected $descripcion;
    protected $fecha;
    protected $vigencia;
    protected $data;
    protected $urlArchivo;
    protected $request;
    protected $cantidadFilas;
    protected $vigenciaNueva;
    
    public function validar() {
        $hay = NULL;
        Log::info('Tipo Archivo: ',array($this->tipo));
        if($this->tipo==ETipoBdua::getIndice(ETipoBdua::NS_VAL)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::NS_NEG)->getId()
            || $this->tipo==ETipoBdua::getIndice(ETipoBdua::NC_VAL)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::NC_NEG)->getId()) {
                $hay = LogNsEncabezado::where('tipo', '=', $this->tipo)->where('sw_finaliza', '=', ESiNo::getIndice(ESiNo::SI)->getId())->where('consecutivo_vigencia', '=', $this->vigencia)->first();
                Log::info('valor consult log: ',array($hay));
            } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::MS_VAL)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::MS_NEG)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::MC_VAL)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::MC_NEG)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S1_AUTOMATICO)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S1_VAL)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R1_AUTOMATICO)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S3)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R3)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S2)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R2)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S6)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R6)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S5)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S4)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R4)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R5)->getId()) {
                    $hay = LogMsEncabezado::where('tipo', '=', $this->tipo)->where('sw_finaliza', '=', ESiNo::getIndice(ESiNo::SI)->getId())->where('consecutivo_vigencia', '=', $this->vigencia)->first();
            }
            return $hay;
    }
    
    public function actualizar() {
        
        DB::beginTransaction();
        
        $user           = Auth::user();
        
        if($this->tipo==ETipoBdua::getIndice(ETipoBdua::NS_VAL)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::NS_NEG)->getId()
            || $this->tipo==ETipoBdua::getIndice(ETipoBdua::NC_VAL)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::NC_NEG)->getId()) {
                
                LogNsEncabezado::where('tipo', '=', $this->tipo)->where('consecutivo_vigencia', '=', $this->vigencia)->update(array('sw_finaliza' => ESiNo::getIndice(ESiNo::SI)->getId()));
                
                //return 'entr NS';
                
            } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::MS_VAL)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::MS_NEG)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::MC_VAL)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::MC_NEG)->getId()) {
                    LogMsEncabezado::where('tipo', '=', $this->tipo)->where('consecutivo_vigencia', '=', $this->vigencia)->update(array('sw_finaliza' => ESiNo::getIndice(ESiNo::SI)->getId()));
                    //  return 'entr MS';
                    
            }
            
            DB::commit();
            return TRUE;
    }
    
    public function procesar() {
        DB::beginTransaction();
        $logNs          = new LogNsEncabezado();
        $logMs          = new LogMsEncabezado();
        
        $user           = Auth::user();
        
        if($this->tipo==ETipoBdua::getIndice(ETipoBdua::NS_VAL)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::NS_NEG)->getId()
            || $this->tipo==ETipoBdua::getIndice(ETipoBdua::NC_VAL)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::NC_NEG)->getId()) {
                $logNs->descripcion             = $this->descripcion;
                $logNs->fecha_grabado           = date('Y-m-d H:i;s');
                $logNs->usuario_grabado         = auth()->user()->id;
                $logNs->sw_finaliza             = $this->finaliza == 'Si' ? 1 : 0;
                $logNs->fecha                   = $this->fecha;
                $logNs->consecutivo_vigencia    = $this->vigencia;
                $logNs->tipo                    = $this->tipo;
                $logNs->save();
                
                $TIPO_IDENTIFICACION       = 2;
                $NUMERO_IDENTIFICACION      = 3;
            } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::MS_VAL)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::MS_NEG)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::MC_VAL)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::MC_NEG)->getId()) {
                    $logMs->descripcion             = $this->descripcion;
                    $logMs->fecha_grabado           = date('Y-m-d H:i;s');
                    $logMs->usuario_grabado         = auth()->user()->id;
                    $logMs->sw_finaliza             = $this->finaliza == 'Si' ? 1 : 0;
                    $logMs->fecha                   = $this->fecha;
                    $logMs->consecutivo_vigencia    = $this->vigencia;
                    $logMs->tipo                    = $this->tipo;
                    
                    $logMs->save();
                    if($this->tipo==ETipoBdua::getIndice(ETipoBdua::MC_VAL)->getId()) {
                        $TIPO_IDENTIFICACION       = 3;
                        $NUMERO_IDENTIFICACION      = 4;
                    } else {
                        $TIPO_IDENTIFICACION       = 1;
                        $NUMERO_IDENTIFICACION      = 2;
                    }
            } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::S1_AUTOMATICO)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S1_VAL)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R1_AUTOMATICO)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S3)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R3)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R6)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S6)->getId()) {
                    $logMs->descripcion             = $this->descripcion;
                    $logMs->fecha_grabado           = date('Y-m-d H:i;s');
                    $logMs->usuario_grabado         = auth()->user()->id;
                    $logMs->sw_finaliza             = $this->finaliza == 'Si' ? 1 : 0;
                    $logMs->fecha                   = $this->fecha;
                    $logMs->consecutivo_vigencia    = $this->vigencia;
                    $logMs->tipo                    = $this->tipo;
                    
                    $logMs->save();
                    
                    $TIPO_IDENTIFICACION       = 1;
                    $NUMERO_IDENTIFICACION      = 2;
            } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::S5)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S4)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R4)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R5)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S2)->getId()
                || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R2)->getId()) {
                    $logMs->descripcion             = $this->descripcion;
                    $logMs->fecha_grabado           = date('Y-m-d H:i;s');
                    $logMs->usuario_grabado         = auth()->user()->id;
                    $logMs->sw_finaliza             = $this->finaliza == 'Si' ? 1 : 0;
                    $logMs->fecha                   = $this->fecha;
                    $logMs->consecutivo_vigencia    = $this->vigencia;
                    $logMs->tipo                    = $this->tipo;
                    
                    $logMs->save();
                    
                    $TIPO_IDENTIFICACION       = 2;
                    $NUMERO_IDENTIFICACION      = 3;
            }
            
            // return $logNs->consecutivo_log;
            //return $this->numFilas;
            if($this->data!=null) {
                
                $arrayCedulas               = array();
                foreach ($this->data as $novedad) {
                    $arrayCedulas[]     = $novedad[$NUMERO_IDENTIFICACION];
                }
                
                $partesC        = array_chunk($arrayCedulas, 300);
                $cedulasN       = array();
                $idAfiliado     = array();
                
                if($this->tipo==ETipoBdua::getIndice(ETipoBdua::NS_VAL)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::NS_NEG)->getId()
                    || $this->tipo==ETipoBdua::getIndice(ETipoBdua::NC_VAL)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::NC_NEG)->getId()) {
                        foreach ($partesC as $p) {
                            $tra            = DB::table('as_novtramites')->whereIn('identificacion',$p)->where('consecutivo_vigencia',$this->vigencia)->get();
                            foreach ($tra as $t) {
                                $cedulasN['C'.$t->identificacion]   = $t->id;
                            }
                        }
                    } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::MS_VAL)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::MS_NEG)->getId()
                        || $this->tipo==ETipoBdua::getIndice(ETipoBdua::MC_VAL)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::MC_NEG)->getId()
                        || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S1_AUTOMATICO)->getId()
                        || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S1_VAL)->getId()
                        || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R1_AUTOMATICO)->getId()
                        || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S3)->getId()
                        || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R3)->getId()
                        || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S6)->getId()
                        || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R6)->getId()
                        || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R5)->getId()
                        || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S5)->getId()) {
                            foreach ($partesC as $p) {
                                $tra            = DB::table('af_afiliado_ms')->whereIn('numero_identificacion',$p)->where('consecutivo_vigencia',$this->vigencia);
                                
                                if($this->tipo==ETipoBdua::getIndice(ETipoBdua::S2)->getId()) {
                                    $tra        = $tra->where('tipo_traslado','S')->get();
                                } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::S5)->getId()) {
                                    $tra        = $tra->where('tipo_traslado','S')->get();
                                } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::S1_AUTOMATICO)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S3)->getId()
                                    || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S1_VAL)->getId()) {
                                    $tra        = $tra->where('tipo_traslado','S')->get();
                                    Log::info('ENTRA AQUI ___________________________ ');
                                } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::R1_AUTOMATICO)->getId()) {
                                    $tra        = $tra->where('tipo_traslado','R')->get();
                                } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::R2)->getId()) {
                                    $tra        = $tra->where('tipo_traslado','R')->get();
                                } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::R5)->getId()) {
                                    $tra        = $tra->where('tipo_traslado','R')->get();
                                } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::R3)->getId()) {
                                    $tra        = $tra->where('tipo_traslado','R')->get();
                                } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::R6)->getId()) {
                                    $tra        = $tra->where('tipo_traslado','R')->get();
                                } 
                                elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::MS_VAL)->getId()) {
                                    $tra        = $tra->where('tipo_traslado','MS')->get();
                                }
                                elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::MC_VAL)->getId()) {
                                    $tra        = $tra->where('tipo_traslado','MC')->get();
                                } else {
                                    $tra        = $tra->whereNull('tipo_traslado')->get();
                                }
                                Log::info('Cantidad cedulas: ',array(count($tra)));
                                foreach ($tra as $t) {
                                    $cedulasN['C'.$t->numero_identificacion]    = $t->consecutivo_ns;
                                    //$idAfiliado['C'.$t->numero_identificacion]   = $t->as_afiliado_id;
                                }
                            }
                    }
                    
                    $arrayLlaves        = array();
                    
                    foreach ($this->data as $novedad) {
                        if($this->tipo==ETipoBdua::getIndice(ETipoBdua::MS_VAL)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::MC_VAL)->getId()) {
                            $partes                                 = $novedad;
                            $l                                      = new LogMsDetalle();
                            $l->consecutivo_log_ms                  = $logMs->consecutivo_log_ms;
                            //$l->codigo_novedad                      = "";
                            $l->tipo_identificacion_afiliado        = utf8_encode($partes[$TIPO_IDENTIFICACION]);
                            $l->numero_identificacion_afiliado      = utf8_encode($partes[$NUMERO_IDENTIFICACION]);
                            $l->fecha_inicio_novedad                = $this->fecha;
                            $l->consecutivo_ms                      = empty($cedulasN['C'.$partes[$NUMERO_IDENTIFICACION]]) ? NULL : $cedulasN['C'.$partes[$NUMERO_IDENTIFICACION]];
                            $l->informacion                         = utf8_encode(implode(',', $novedad));
                            if(!empty($cedulasN['C'.$partes[$NUMERO_IDENTIFICACION]])) {
                                $actualiza      = AfAfiliadoMs::find($l->consecutivo_ms);
                                $actualiza->consecutivo_log_ms  = $logMs->consecutivo_log_ms;
                                $actualiza->save();
                                $arrayLlaves[]          = $actualiza->consecutivo_afiliado;
                                
                                $l->save();
                            }
                        } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::NS_VAL)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::NC_VAL)->getId()) {
                            $partes                                 = $novedad;
                            $l                                      = new LogNsDetalle();
                            $l->consecutivo_log_ns                  = $logNs->consecutivo_log;
                            //$l->codigo_novedad                      = "";
                            $l->tipo_identificacion_afiliado        = utf8_encode($partes[$TIPO_IDENTIFICACION]);
                            $l->numero_identificacion_afiliado      = utf8_encode($partes[$NUMERO_IDENTIFICACION]);
                            $l->fecha_inicio_novedad                = $this->fecha;
                            $l->consecutivo_ns                      = $cedulasN['C'.$partes[$NUMERO_IDENTIFICACION]];
                            $l->informacion_ns                      = utf8_encode(implode(',', $novedad));
                            $l->save();
                            
                            if(!empty($cedulasN['C'.$partes[$NUMERO_IDENTIFICACION]])) {
                                $actualiza                      = AsNovtramite::find($l->consecutivo_ns);
                                $actualiza->consecutivo_log_ns  = $l->consecutivo_log_ns_detalle;
                                $actualiza->save();
                                $arrayLlaves[]          = $actualiza->as_afiliado_id;
                            }
                        } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::NS_NEG)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::MS_NEG)->getId()
                            || $this->tipo==ETipoBdua::getIndice(ETipoBdua::NC_NEG)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::MC_NEG)->getId()) {
                                $glosas         = $novedad[count($novedad)-1];
                                $codigoNovedad  = 11;
                                $partes                                 = $novedad;
                                $pGlosas        = explode(';', $glosas);
                                foreach ($pGlosas as $g) {
                                    $gg         = substr($g, 0,strpos($g,'('));
                                    if($this->tipo==ETipoBdua::getIndice(ETipoBdua::NS_NEG)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::NC_NEG)->getId()) {
                                        $l                                      = new LogNsDetalle();
                                        $l->consecutivo_log_ns                  = $logNs->consecutivo_log;
                                        $l->codigo_novedad                      = $novedad[$codigoNovedad];
                                        $l->tipo_identificacion_afiliado        = utf8_encode($partes[$TIPO_IDENTIFICACION]);
                                        $l->numero_identificacion_afiliado      = utf8_encode($partes[$NUMERO_IDENTIFICACION]);
                                        $l->fecha_inicio_novedad                = $this->fecha;
                                        $l->consecutivo_ns                      = $cedulasN['C'.$partes[$NUMERO_IDENTIFICACION]];
                                        $l->informacion_ns                      = utf8_encode(implode(',', $novedad));
                                        $novePer                                = AsNovtramite::find($l->consecutivo_ns);
                                        $novePer->estado_proceso                = 2;
                                        $novePer->save();
                                        if(!empty($gg)) {
                                            $l->save();
                                        }
                                    } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::MS_NEG)->getId() || $this->tipo==ETipoBdua::getIndice(ETipoBdua::MC_NEG)->getId()) {
                                        $partes                                 = $novedad;
                                        $l                                      = new LogMsDetalle();
                                        $l->consecutivo_log_ms                  = $logMs->consecutivo_log_ms;
                                        $l->codigo_novedad                      = $novedad[$codigoNovedad];
                                        $l->tipo_identificacion_afiliado        = utf8_encode($partes[$TIPO_IDENTIFICACION]);
                                        $l->numero_identificacion_afiliado      = utf8_encode($partes[$NUMERO_IDENTIFICACION]);
                                        $l->fecha_inicio_novedad                = $this->fecha;
                                        $l->consecutivo_ms                      = empty($cedulasN['C'.$partes[$NUMERO_IDENTIFICACION]]) ? NULL : $cedulasN['C'.$partes[$NUMERO_IDENTIFICACION]];
                                        $l->informacion                         = utf8_encode(implode(',', $novedad));
                                        if(!empty($cedulasN['C'.$partes[$NUMERO_IDENTIFICACION]]) && !empty($gg)) {
                                            $l->save();
                                        }
                                    }
                                }
                        } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::S1_AUTOMATICO)->getId()
                            || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S1_VAL)->getId()
                            || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R1_AUTOMATICO)->getId()
                            || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S3)->getId()
                            || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R3)->getId()
                            || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S6)->getId()
                            || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R6)->getId()
                            || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S2)->getId()
                            || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R2)->getId()
                            || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R5)->getId()
                            || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S5)->getId()) {
                                // DB::flush();
                                $partes                                 = $novedad;
                                $l                                      = new LogMsDetalle();
                                //Log::info('Llave: ',array($logMs->consecutivo_log_ms));
                                // Log::info('Valor cedula: ',array(empty($cedulasN['C'.$partes[$NUMERO_IDENTIFICACION]]) ? NULL : $cedulasN['C'.$partes[$NUMERO_IDENTIFICACION]]));
                                $l->consecutivo_log_ms                  = $logMs->consecutivo_log_ms;
                                //$l->codigo_novedad                      = "";
                                $l->tipo_identificacion_afiliado        = utf8_encode($partes[$TIPO_IDENTIFICACION]);
                                $l->numero_identificacion_afiliado      = utf8_encode($partes[$NUMERO_IDENTIFICACION]);
                                $l->fecha_inicio_novedad                = $this->fecha;
                                $l->consecutivo_ms                      = empty($cedulasN['C'.$partes[$NUMERO_IDENTIFICACION]]) ? NULL : $cedulasN['C'.$partes[$NUMERO_IDENTIFICACION]];
                                $l->informacion                         = utf8_encode(implode(',', $novedad));
                                Log::info('Llaves Cedulas: ',array($cedulasN));
                                Log::info('Antes Entra a guardar: ',array(count($cedulasN)));
                                if(!empty($cedulasN['C'.$partes[$NUMERO_IDENTIFICACION]])) {
                                    Log::info('Entra a guardar: ',array('entra'));
                                    $actualiza      = AfAfiliadoMs::find($l->consecutivo_ms);
                                    $actualiza->consecutivo_log_ms  = $logMs->consecutivo_log_ms;
                                    $actualiza->save();
                                    $arrayLlaves[]          = $actualiza->consecutivo_afiliado;
                                    //$idAfiliado['C'.$t->numero_identificacion]
                                    //Log::info('Objeto Guardar: ',array($l));
                                    $l->save();
                                } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::S2)->getId()
                                    || $this->tipo==ETipoBdua::getIndice(ETipoBdua::S4)->getId()
                                    || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R4)->getId()
                                    || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R2)->getId()) {
                                        $l->save();
                                }
                        }
                    }
                    
                    /* Log::info('Finaliza: ',array($this->finaliza));
                     */
            }
            if($this->tipo==ETipoBdua::getIndice(ETipoBdua::MC_VAL)->getId()) {
                $arrayPartes        = array_chunk($arrayLlaves, 300);
                foreach ($arrayPartes as $ap) {
                    DB::update("update as_afiliados set estado_traslado = 'MC_VAL' WHERE id in (".implode(',', $ap).") ");
                }
            } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::MS_VAL)->getId()) {
                $arrayPartes        = array_chunk($arrayLlaves, 300);
                foreach ($arrayPartes as $ap) {
                    DB::update("update as_afiliados set estado_traslado = 'MS_VAL' WHERE id in (".implode(',', $ap).") ");
                }
            } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::NS_NEG)->getId()) {
                $arrayPartes        = array_chunk($arrayLlaves, 300);
                foreach ($arrayPartes as $ap) {
                    DB::update("update as_afiliados set estado_traslado = 'NS_NEG' WHERE id in (".implode(',', $ap).") ");
                }
            } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::NS_VAL)->getId()) {
                $arrayPartes        = array_chunk($arrayLlaves, 300);
                foreach ($arrayPartes as $ap) {
                    DB::update("update as_afiliados set estado_traslado = 'NS_VAL' WHERE id in (".implode(',', $ap).") ");
                }
            } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::R3)->getId()) {
                $arrayPartes        = array_chunk($arrayLlaves, 300);
                foreach ($arrayPartes as $ap) {
                    DB::update("update as_afiliados set estado_traslado = 'R3' WHERE id in (".implode(',', $ap).") ");
                }
            } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::S2)->getId()) {
                $arrayPartes        = array_chunk($arrayLlaves, 300);
                foreach ($arrayPartes as $ap) {
                    DB::update("update as_afiliados set estado_traslado = 'S2' WHERE id in (".implode(',', $ap).") ");
                }
            } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::S6)->getId()) {
                
                $arrayPartes        = array_chunk($arrayLlaves, 300);
                foreach ($arrayPartes as $ap) {
                    DB::update("update as_afiliados set estado_traslado = 'S6' WHERE id in (".implode(',', $ap).") ");
                }
                
            } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::S1_VAL)->getId()) {
                
                $arrayPartes        = array_chunk($arrayLlaves, 300);
                foreach ($arrayPartes as $ap) {
                    DB::update("update as_afiliados set estado_traslado = 'S1_VAL' WHERE id in (".implode(',', $ap).") ");
                }
            } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::S3)->getId()) {
                
                $arrayPartes        = array_chunk($arrayLlaves, 300);
                foreach ($arrayPartes as $ap) {
                    DB::update("update as_afiliados set estado_traslado = 'S3' WHERE id in (".implode(',', $ap).") ");
                }
            } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::S5)->getId()) {
                $arrayPartes        = array_chunk($arrayLlaves, 300);
                foreach ($arrayPartes as $ap) {
                    DB::update("update as_afiliados set estado_traslado = 'S5' WHERE id in (".implode(',', $ap).") ");
                }
            } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::R5)->getId()) {
                $arrayPartes        = array_chunk($arrayLlaves, 300);
                foreach ($arrayPartes as $ap) {
                    DB::update("update as_afiliados set estado_traslado = 'R5' WHERE id in (".implode(',', $ap).") ");
                }
            } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::R6)->getId()) {
                $arrayPartes        = array_chunk($arrayLlaves, 300);
                foreach ($arrayPartes as $ap) {
                    DB::update("update as_afiliados set estado_traslado = 'R6' WHERE id in (".implode(',', $ap).") ");
                }
            }
            
            if($this->finaliza=='Si') {
                Log::info('tipo: ',array($this->tipo));
                if($this->tipo==ETipoBdua::getIndice(ETipoBdua::NS_NEG)->getId()) {
                    /*Log::info('Archivo: ',array($this->archivo));
                    $s1Auto         = new NSNEG(NULL);
                    $s1Auto->setData($this->data);
                    $s1Auto->setFileName('NSFINALIZA'.$this->vigencia);
                    $s1Auto->setVigencia($this->vigencia);
                    //$s1Auto->setNumFilas($this->cantidadFilas);
                    $s1Auto->procesar();*/
                    Log::info('Traslado Llaves: ',array($cedulasN));
                } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::NS_VAL)->getId()) {
                    Log::info('Archivo: ',array($this->archivo));
                    $s1Auto         = new NSVAL(NULL);
                    $s1Auto->setData($this->data);
                    $s1Auto->setFileName('NSFINALIZA'.$this->vigencia);
                    $s1Auto->setVigencia($this->vigencia);
                    //$s1Auto->setNumFilas($this->cantidadFilas);
                    $s1Auto->procesar();
                    Log::info('Traslado Llaves: ',array($cedulasN));
                } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::R6)->getId()) {
                    Log::info('Archivo: ',array($this->archivo));
                    $s1Auto         = new R6(NULL);
                    $s1Auto->setData($this->data);
                    $s1Auto->setFileName('R6FINALIZA'.$this->vigencia);
                    $s1Auto->setVigencia($this->vigencia);
                    //$s1Auto->setNumFilas($this->cantidadFilas);
                    $s1Auto->procesar();
                    Log::info('Traslado Llaves: ',array($cedulasN));
                } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::S6)->getId()) {
                    Log::info('Archivo: ',array($this->archivo));
                    $s1Auto         = new S6(NULL);
                    $s1Auto->setData($this->data);
                    $s1Auto->setFileName('S6FINALIZA'.$this->vigencia);
                    $s1Auto->setVigencia($this->vigencia);
                    //$s1Auto->setNumFilas($this->cantidadFilas);
                    $s1Auto->procesar();
                    Log::info('Traslado Llaves: ',array($cedulasN));
                } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::S2)->getId()) {
                    $s1Auto         = new \App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadoresV2\S2();
                    Log::info('Objeto: ',array($s1Auto));
                    //$s1Auto->setData($this->data);
                    $s1Auto->setFileName('S2ARCHIVOFINAL'.$this->vigencia);
                    $s1Auto->setVigencia($this->vigencia);
                    //$s1Auto->setNumFilas($this->cantidadFilas);
                    $s1Auto->procesar();
                    Log::info('Traslado Llaves: ',array($cedulasN));
                } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::R2)->getId()) {
                    $s1Auto         = new \App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadoresV2\R2();
                    Log::info('Objeto: ',array($s1Auto));
                    //$s1Auto->setData($this->data);
                    $s1Auto->setFileName('R2ARCHIVOFINAL'.$this->vigencia);
                    $s1Auto->setVigencia($this->vigencia);
                    //$s1Auto->setNumFilas($this->cantidadFilas);
                    $s1Auto->procesar();
                    Log::info('Traslado Llaves: ',array($cedulasN));
                } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::S3)->getId()) {
                    Log::info('Archivo: ',array($this->archivo));
                    $s1Auto         = new S3(NULL);
                    $s1Auto->setData($this->data);
                    $s1Auto->setFileName('S3ARCHIVOFINAL'.$this->vigencia);
                    $s1Auto->setVigencia($this->vigencia);
                    $s1Auto->setVigenciaNueva($this->vigenciaNueva);
                    $s1Auto->setNumFilas($this->cantidadFilas);
                    $s1Auto->procesar();
                    Log::info('Traslado Llaves: ',array($cedulasN));
                } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::R3)->getId()) {
                    Log::info('Archivo: ',array($this->archivo));
                    $s1Auto         = new R3(NULL);
                    $s1Auto->setData($this->data);
                    $s1Auto->setFileName('R3ARCHIVOFINAL'.$this->vigencia);
                    $s1Auto->setVigencia($this->vigencia);
                    $s1Auto->setVigenciaNueva($this->vigenciaNueva);
                    $s1Auto->setNumFilas($this->cantidadFilas);
                    $s1Auto->procesar();
                    Log::info('Traslado Llaves: ',array($cedulasN));
                } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::S4)->getId()) {
                    Log::info('Importante: ',array('Inicia S4'));
                    $s1Auto         = new S4VAL(NULL);
                    $s1Auto->setFileName('S4ARCHIVOFINAL'.$this->vigencia);
                    $s1Auto->setVigencia($this->vigencia);
                    $s1Auto->procesar();
                    Log::info('Importante: ',array('FINALIZA S4'));
                } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::R4)->getId()) {
                    Log::info('Importante: ',array('Inicia R4'));
                    $s1Auto         = new R4VAL(NULL);
                    $s1Auto->setFileName('R4ARCHIVOFINAL'.$this->vigencia);
                    $s1Auto->setVigencia($this->vigencia);
                    $s1Auto->procesar();
                    Log::info('Importante: ',array('FINALIZA R4'));
                } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::S5)->getId()) {
                    Log::info('Importante: ',array('Inicia S5'));
                    $s1Auto         = new \App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadoresV2\S5(NULL);
                    $s1Auto->setVigencia($this->vigencia);
                    $s1Auto->procesar();
                    Log::info('Importante: ',array('FINALIZA S5'));
                } elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::S1_VAL)->getId()) {
                    Log::info('Importante: ',array('Inicia S1'));
                    $s1Auto         = new \App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadoresV2\S1VAL(NULL);
                    $s1Auto->setVigencia($this->vigencia);
                    $s1Auto->procesar();
                    Log::info('Importante: ',array('FINALIZA S1'));
                } 
                elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::MC_VAL)->getId()) {
                    Log::info('Importante: ',array('Inicia MC_VAL'));
                    $msval         = new \App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\MCVAL(NULL);
                    $msval->setVigencia($this->vigencia);
                    $msval->setFileName('MCVALFIN'.$this->vigencia);
                    $msval->procesar();
                    Log::info('Importante: ',array('FINALIZA MC_VAL'));
                }
                elseif($this->tipo==ETipoBdua::getIndice(ETipoBdua::MS_VAL)->getId()) {
                    Log::info('Importante: ',array('Inicia MS_VAL'));
                    $msval         = new \App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores\MSVAL(NULL);
                    $msval->setVigencia($this->vigencia);
                    $msval->setFileName('MSVALFIN'.$this->vigencia);
                    $msval->procesar();
                    Log::info('Importante: ',array('FINALIZA MS_VAL'));
                } 
                elseif(($this->tipo==ETipoBdua::getIndice(ETipoBdua::S1_AUTOMATICO)->getId()
                    || $this->tipo==ETipoBdua::getIndice(ETipoBdua::R1_AUTOMATICO)->getId())) {
                        
                        $registros      = DB::select("select f.* from log_ms_encabezado e
                                                        inner join log_ms_detalle d on
                                                        e.consecutivo_log_ms = d.consecutivo_log_ms
                                                        inner join af_afiliado_ms a on
                                                        a.consecutivo_ns = d.consecutivo_ms
                                                        inner join as_formtrasmovs f on
                                                        f.as_afiliado_id = a.consecutivo_afiliado
                                                        where a.consecutivo_vigencia = {$this->vigencia} ".($this->tipo==ETipoBdua::getIndice(ETipoBdua::S1_AUTOMATICO)->getId() ? " and a.tipo_traslado = 'S' " : " and a.tipo_traslado = 'R' ")."
                                                        AND f.fecha_efectiva_traslado IS NULL ");
                        Log:info('Cantidad: ',array($registros));
                        foreach ($registros as $r) {
                            $fechaEfectiva      = NULL;
                            switch ($r->tipo_traslado) {
                                case 0:
                                case 3:
                                case 5:{
                                    $fechaEfectiva  = date('Y-m-d');
                                }break;
                                case 1:
                                case 4:{
                                    //date("d-m-Y",strtotime($fecha_actual."+ 1 month"));
                                    $fechaEfectiva  = date("Y-m-01",strtotime(date('Y-m-d')."+ 1 month"));
                                }break;
                                case 2:{
                                    //date("d-m-Y",strtotime($fecha_actual."+ 1 month"));
                                    $fechaEfectiva  = date("Y-m-01",strtotime(date('Y-m-d')."+ 2 month"));
                                }break;
                            }
                            // DB::update("update as_formtrasmovs SET fecha_efectiva_traslado = '".$fechaEfectiva."' where id = ".$formtrasmov->id);
                            Log::info('Mostrar: ',array("update as_formtrasmovs set fecha_efectiva_traslado = '{$fechaEfectiva}' WHERE id = ".$r->id));
                            DB::update("update as_formtrasmovs set fecha_efectiva_traslado = '{$fechaEfectiva}' WHERE id = ".$r->id);
                            if($this->tipo==ETipoBdua::getIndice(ETipoBdua::S1_AUTOMATICO)->getId()) {
                                DB::update("update as_afiliados set estado_traslado = 'S1_AUTOMATICO' WHERE id = ".$r->as_afiliado_id);
                            } else {
                                DB::update("update as_afiliados set estado_traslado = 'R1_AUTOMATICO' WHERE id = ".$r->as_afiliado_id);
                            }
                        }
                        
                        if($this->tipo==ETipoBdua::getIndice(ETipoBdua::S1_AUTOMATICO)->getId()) {
                            Log::info('Importante: ',array('Inicia S1 AUTOMATICO'));
                            $s1Auto         = new S1Automatico1(NULL);
                            $s1Auto->procesaActivacion();
                            Log::info('Importante: ',array('FINALIZA S1 AUTOMATICO'));
                        } else {
                            $r1Auto         = new R1Automatico(NULL);
                            $r1Auto->procesaActivacion();
                        }
                }
            }
            
            DB::commit();
            return TRUE;
    }
    
    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
    
    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }
    
    /**
     * @return mixed
     */
    public function getArchivo()
    {
        return $this->archivo;
    }
    
    /**
     * @return mixed
     */
    public function getFinaliza()
    {
        return $this->finaliza;
    }
    
    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    
    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }
    
    /**
     * @return mixed
     */
    public function getVigencia()
    {
        return $this->vigencia;
    }
    
    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }
    
    /**
     * @param mixed $archivo
     */
    public function setArchivo($archivo)
    {
        $this->archivo = $archivo;
    }
    
    /**
     * @param mixed $finaliza
     */
    public function setFinaliza($finaliza)
    {
        $this->finaliza = $finaliza;
    }
    
    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
    
    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
    
    /**
     * @param mixed $vigencia
     */
    public function setVigencia($vigencia)
    {
        $this->vigencia = $vigencia;
    }
    
    /**
     * @return mixed
     */
    public function getUrlArchivo()
    {
        return $this->urlArchivo;
    }
    
    /**
     * @param mixed $urlArchivo
     */
    public function setUrlArchivo($urlArchivo)
    {
        $this->urlArchivo = $urlArchivo;
    }
    
    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }
    
    /**
     * @param mixed $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }
    
    /**
     * @return mixed
     */
    public function getCantidadFilas()
    {
        return $this->cantidadFilas;
    }
    
    /**
     * @param mixed $cantidadFilas
     */
    public function setCantidadFilas($cantidadFilas)
    {
        $this->cantidadFilas = $cantidadFilas;
    }
    
    /**
     * @return mixed
     */
    public function getVigenciaNueva()
    {
        return $this->vigenciaNueva;
    }

    /**
     * @param mixed $vigenciaNueva
     */
    public function setVigenciaNueva($vigenciaNueva)
    {
        $this->vigenciaNueva = $vigenciaNueva;
    }
}
?>