<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/09/2018
 * Time: 9:02 AM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;

use App\Capresoca\Aseguramiento\RespuestasBDUA\NCTrait;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorNegados;
use App\Models\Aseguramiento\AsBduaregrespuesta;
use Illuminate\Support\Facades\Log;
use App\Models\Enums\ETipoBdua;
use Illuminate\Support\Facades\DB;
use App\Models\Aseguramiento\AsBduaproceso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Aseguramiento\AsBduaarchivo;
use App\Models\Aseguramiento\AsBduarespuesta;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsTramite;

class NSNEG extends ProcesadorNegados implements ProcesadorCSVInterface {
	use NCTrait;

	protected $vigencia;
	
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
	    $bduaArchivo->nombre                  = 'NS_NEG'.$bduaProceso->id;
	    $this->csvPath                        = 'NS_NEG'.$bduaProceso->id;
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
	
	protected function getTramiteS3(AsAfiliado $afiliado)
	{
	    //where('as_bduaarchivo_id', $this->respuesta->as_bduaarchivo_id)
	    $tramite =  AsTramite::whereHas('novedad', function ($query) use ($afiliado){
	        $query->where('as_afiliado_id', $afiliado->id)->whereHas('afiliado', function ($query2) {
	            $query2->where('estado_traslado','NS_NEG');
	        });
	    })->first();
	    Log::info('Tramite Consulta: ',array($afiliado));
	    if(!$tramite){
	        $afiliadoDatosRelacionados = $afiliado->with('tipo_id')->first();
	        throw new \Exception('No se encontro el tramite de traslado subsidiado del afiliado: '.$afiliadoDatosRelacionados->tipo_id->abreviatura.' '.$afiliadoDatosRelacionados->identificacion.' del proceso: '.$this->respuesta->as_bduaarchivo_id);
	        
	    }
	    
	    return $tramite;
	}
	
	public function procesar() {
	    $registros      = DB::select("select d.informacion_ns AS informacion,d.tipo_identificacion_afiliado,d.numero_identificacion_afiliado as identificacion,
                                        a.apellido1 as primer_apellido,a.apellido2 as segundo_apellido,a.nombre1 as primer_nombre,a.nombre2 as segundo_nombre,
                                        a.fecha_nacimiento,if(a.gn_sexo_id=1,'F','M') as genero, f.as_afiliado_id as consecutivo_afiliado, a.estado_traslado, f.id
                                        from log_ns_encabezado e
                                        inner join log_ns_detalle d on
                                        e.consecutivo_log= d.consecutivo_log_ns
                                        inner join as_novtramites f on
                                        f.consecutivo_log_ns = d.consecutivo_log_ns_detalle
                                        inner join as_afiliados a on
                                        a.id = f.as_afiliado_id AND a.estado_traslado = 'NS_NEG'
                                            where a.consecutivo_vigencia = {$this->vigencia} and e.tipo = ".ETipoBdua::getIndice(ETipoBdua::NS_NEG)->getId()." ");
	    
	    $this->numFilas   = count($registros);
	    
	    $this->respuesta = $this->crearRespuestaS3('Valido');
	    if(!$this->respuesta){
	        return;
	    }
	    $this->procesadas = 0;
	    $this->aplicadas = 0;
	    
		/*$this->respuesta = $this->crearRespuesta('Glosas');
		if (!$this->respuesta) {
			return;
		}*/
		
		
	    foreach ($registros as $r) {
			try {
			    $fila       = explode(',', $r->informacion);
			    
                $this->procesadas++;
				$afiliado = $this->getAfiliado($fila[2], $fila[3]);
				
				//$tramite = $this->getTramite($afiliado, $fila[12]);

				$tramite = $this->getTramiteS3($afiliado); //AsTramite::where('as_bduaarchivo_id', $this->respuesta->as_bduaarchivo_id)
				
				if(!$tramite) {
				    $log = 'No se encontro el tramite:'.$this->respuesta->as_bduaarchivo_id.' '.$fila[0];
				    Log::info($log);
				    $this->guardarPendiente($log);
				    $this->pushMonitor($log,$this->proceso,'error--text');
				    continue;
				}
				$tramite->estado = 'Validado';
				$tramite->save();
				
				/*$tramite->estado = 'Glosado';
				$tramite->save();*/

				$filaRespuesta = $r->informacion;

				$regRespuesta = AsBduaregrespuesta::create(
					[
						'as_bduarespuesta_id' => $this->respuesta->id,
						'as_tramite_id' => $tramite->id,
						'respuesta' => $filaRespuesta,
					]
				);
				$bduaGlosas = $this->crearGlosas($fila);

				$regRespuesta->glosas()->createMany($bduaGlosas);

				$this->procesarGlosas($afiliado, $regRespuesta);
				$this->pushMonitor('Proceso creado', $this->proceso, 'console--success');
				//$this->pushAvance($this->proceso, $this->respuesta);
				$this->aplicadas++;
			} catch (\Exception $e) {
                $this->procesadas++;
				$this->guardarPendiente($e->getMessage());
				$this->pushMonitor($e->getMessage(), $this->proceso, 'error--text');
				continue;
			}

		}

		//$this->pushAvance($this->proceso, $this->respuesta);

	}

}