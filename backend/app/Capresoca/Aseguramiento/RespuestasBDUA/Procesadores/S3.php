<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 6/11/2018
 * Time: 5:02 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;

use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorNegados;
use App\Capresoca\Aseguramiento\RespuestasBDUA\S1Trait;
use App\Models\Aseguramiento\AsBduaarchivo;
use App\Models\Aseguramiento\AsBduaregrespuesta;
use App\Models\Aseguramiento\AsBduarespuesta;
use Illuminate\Support\Facades\Log;
use App\Models\Aseguramiento\AsBduaproceso;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Enums\ETipoBdua;
use App\Models\Aseguramiento\AsFormtrasmov;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\BDUA\AfAfiliadoMs;

class S3 extends ProcesadorNegados implements ProcesadorCSVInterface {
	use S1Trait;

	protected $vigencia;
	protected $vigenciaNueva;
	
	public function procesar() {
	    
	    $registros      = DB::select("select d.informacion,d.tipo_identificacion_afiliado,d.numero_identificacion_afiliado as identificacion,
                                            a.primer_apellido,a.segundo_apellido,a.primer_nombre,a.segundo_nombre,
                                            a.fecha_nacimiento,if(a.genero=1,'F','M') as genero, a.consecutivo_afiliado, f.tipo_traslado, f.id,a.consecutivo_ns
                                                 from log_ms_encabezado e
                                                    inner join log_ms_detalle d on
                                                    e.consecutivo_log_ms = d.consecutivo_log_ms
                                                    inner join log_ms_encabezado enc on
                                                    enc.consecutivo_log_ms = d.consecutivo_log_ms AND enc.tipo = ".ETipoBdua::getIndice(ETipoBdua::S3)->getId()."
                                                    inner join af_afiliado_ms a on
                                                    a.consecutivo_ns = d.consecutivo_ms
                                                    inner join as_formtrasmovs f on
                                                    f.as_afiliado_id = a.consecutivo_afiliado and a.tipo_traslado = 'S'
                                                    where a.consecutivo_vigencia = {$this->vigencia}  and a.tipo_traslado = 'S'
                                                    AND f.fecha_efectiva_traslado IS NULL ");
	    /*Log::info('Sql: '."select d.informacion,d.tipo_identificacion_afiliado,d.numero_identificacion_afiliado as identificacion,
                                            a.primer_apellido,a.segundo_apellido,a.primer_nombre,a.segundo_nombre,
                                            a.fecha_nacimiento,if(a.genero=1,'F','M') as genero, a.consecutivo_afiliado, f.tipo_traslado, f.id,a.consecutivo_ns
                                                 from log_ms_encabezado e
                                                    inner join log_ms_detalle d on
                                                    e.consecutivo_log_ms = d.consecutivo_log_ms
                                                    inner join log_ms_encabezado enc on
                                                    enc.consecutivo_log_ms = d.consecutivo_log_ms AND enc.tipo = ".ETipoBdua::getIndice(ETipoBdua::S3)->getId()."
                                                    inner join af_afiliado_ms a on
                                                    a.consecutivo_ns = d.consecutivo_ms
                                                    inner join as_formtrasmovs f on
                                                    f.as_afiliado_id = a.consecutivo_afiliado and a.tipo_traslado = 'S'
                                                    where a.consecutivo_vigencia = {$this->vigencia}  and a.tipo_traslado = 'S'
                                                    AND f.fecha_efectiva_traslado IS NULL ");*/
	    $this->numFilas   = count($registros);
	    
	    $this->respuesta = $this->crearRespuestaS3('Glosas');
	    if (!$this->respuesta) {
	        $log = 'No se encuentra archivo S1 en este proceso';
	        $this->pushMonitor($log, $this->proceso, 'error--text');
	        return;
	    }
	    
	    $this->procesadas = 0;
	    $this->aplicadas = 0;
	    
	    
	        //Log::info('Registros: ',array($registros));
        foreach ($registros as $r) {
            try {
	            
	            $datum       = explode(',', $r->informacion);
	            $this->procesadas++;
	            $afiliado = $this->getAfiliado($datum[1], $datum[2]);
	            
	            $tramite = $this->getTramiteS3($afiliado);
	            
	            $tramite->estado = 'Glosado';
	            $tramite->save();
	            
	            $filaRespuesta = $r->informacion;
	            
	            $regRespuesta = AsBduaregrespuesta::create(
	                [
	                    'as_bduarespuesta_id' => $this->respuesta->id,
	                    'as_tramite_id' => $tramite->id,
	                    'respuesta' => $filaRespuesta,
	                ]
	                );
	            
	            $bduaGlosas = $this->crearGlosas($datum);
	            
	            $regRespuesta->glosas()->createMany($bduaGlosas);
	            
	            $conErrores        = $this->procesarGlosas($afiliado, $regRespuesta);
	            
	            $form                       = AsFormtrasmov::find($r->id);
	            $formNuevo                  = new AsFormtrasmov();
	            $afiliadoActualizado        = AsAfiliado::find($form->as_afiliado_id);
	            
	            $afAfiliadoMs               = AfAfiliadoMs::find($r->consecutivo_ns);
	            if(!$conErrores) {
	               $afAfiliadoMs->estado_procesado        = 1;
	            } else {
	               $afAfiliadoMs->estado_procesado        = 2;
	            }
	            $afAfiliadoMs->save();
	            
	            //$afAfiliado->codigo_entidad           = ;
	            //$afAfiliado->tipo_identificacion_padre           = ;
	            //$afAfiliado->numero_identificacion_padre           = ;
	            if(!$conErrores) {
	                foreach ($formNuevo->getFillable() as $campo) {
	                    $formNuevo->{$campo}        = $form->{$campo};
	                }
	                
	                $formNuevo->nombre1      = $afiliadoActualizado->nombre1;
	                $formNuevo->nombre2      = $afiliadoActualizado->nombre2;
	                $formNuevo->apellido1    = $afiliadoActualizado->apellido1;
	                $formNuevo->apellido2    = $afiliadoActualizado->apellido2;
	                
	                $formNuevo->save();
	                
	                
	                
	                $afAfiliado = new AfAfiliadoMs();
	                
    	            $afAfiliado->tipo_identificacion            = $afiliadoActualizado->tipo_identificacion;
    	            $afAfiliado->numero_identificacion          = $afiliadoActualizado->identificacion;
    	            $afAfiliado->primer_apellido                = $afiliadoActualizado->apellido1;
    	            $afAfiliado->segundo_apellido               = $afiliadoActualizado->apellido2;
    	            $afAfiliado->primer_nombre                  = $afiliadoActualizado->nombre1;
    	            $afAfiliado->segundo_nombre                 = $afiliadoActualizado->nombre2;
    	            $afAfiliado->fecha_nacimiento               = $afiliadoActualizado->fecha_nacimiento;
    	            $afAfiliado->genero                         = $afiliadoActualizado->gn_sexo_id;
    	            $afAfiliado->consecutivo_vigencia           = $this->vigenciaNueva->consecutivo_vigencia;
    	            $afAfiliado->consecutivo_afiliado           = $afiliadoActualizado->id;
    	            //$afAfiliado->departamento           = ;
    	            $afAfiliado->municipio                      = $afiliadoActualizado->gn_municipio_id;
    	            $afAfiliado->zona_afiliacion                = $afiliadoActualizado->gn_zona_id;
    	            $afAfiliado->fecha_afiliacion               = $afiliadoActualizado->fecha_afiliacion;
    	            $afAfiliado->tipo_poblacion                 = $afiliadoActualizado->as_pobespeciale_id;
    	            $afAfiliado->tipo_traslado                  = "S";
    	            $afAfiliado->estado_procesado               = 0;
    	            $afAfiliado->save();
	            }
	            
	            $this->pushAvance($this->proceso, $this->respuesta);
	            Log::info('Procesa: '.$this->aplicadas);
	            $this->aplicadas++;
            } catch (\Exception $e) {
                $this->procesadas++;
                Log::info('Error Log: ',array($e->getMessage()));
                $this->guardarPendiente($e->getMessage());
                $this->pushMonitor($e->getMessage(), $this->proceso, 'error--text');
                //continue;
            }
        }
	    
	}
	
	private function crearRespuestaS3($tipo) {
	    //$nombre_archivo_enviado = 'S1' . substr($this->fileName, 2, 14);
	    //Log::info('Archivo: ',array($nombre_archivo_enviado));
	    $maxproceso       = DB::select("SELECT MAX(consecutivo) as consecutivo FROM as_bduaprocesos");
	    
	    $bduaProceso                  = new AsBduaproceso();
	    $bduaProceso->consecutivo     = $maxproceso[0]->consecutivo+1;
	    $bduaProceso->fecha           = date('Y-m-d');
	    $bduaProceso->tipo            = 'Traslado';
	    $bduaProceso->gs_usuario_id   = Auth::user()->id;
	    $bduaProceso->uuid            = Str::uuid();
	    $bduaProceso->save();
	    
	    $bduaArchivo      = new AsBduaarchivo();
	    $bduaArchivo->as_bduaproceso_id       = $bduaProceso->id;
	    $bduaArchivo->nombre                  = 'S3'.$bduaProceso->id;
	    $this->csvPath                        = 'S3'.$bduaProceso->id;
	    $bduaArchivo->as_tipbduaarchivo_id    = 10;
	    $bduaArchivo->numero_registros        = $this->numFilas;
	    $bduaArchivo->save();
	    //$bduaArchivo      = AsBduaarchivo::where('nombre', $nombre_archivo_enviado)->first();
	    
	    if (!$bduaArchivo) {
	        return;
	    }
	    //Log::info('Final Archivo Crear: ',array($nombre_archivo_enviado));
	    $gnArchivo = $this->crearGnArchivo();
	    
	    return AsBduarespuesta::create([
	        'as_bduaarchivo_id' => $bduaArchivo->id,
	        'tipo_respuesta' => $tipo,
	        'gn_archivo_id' => $gnArchivo->id,
	        'total_registros' => $this->numFilas,
	        'avance' => $this->procesadas,
	        'aplicadas' => $this->aplicadas,
	    ]);
	}
	
	/**
	 * @return mixed
	 */
	public function getVigencia()
	{
	    return $this->vigencia;
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