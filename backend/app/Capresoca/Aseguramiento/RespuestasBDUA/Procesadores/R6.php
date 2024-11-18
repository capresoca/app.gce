<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/09/2018
 * Time: 9:02 AM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;

use App\Capresoca\Aseguramiento\RespuestasBDUA\TrasladosTrait;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorNegados;
use App\Models\Aseguramiento\AsBduaregrespuesta;
use Illuminate\Support\Facades\Log;
use App\Models\Aseguramiento\AsBduaproceso;
use App\Models\Aseguramiento\AsBduarespuesta;
use App\Models\Aseguramiento\AsBduaarchivo;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Enums\ETipoBdua;

class R6 extends ProcesadorNegados implements ProcesadorCSVInterface {

use TrasladosTrait;

    protected $vigencia;

    private function crearRespuestaS3($tipo) {
        //$nombre_archivo_enviado = 'S1' . substr($this->fileName, 2, 14);
        //Log::info('Archivo: ',array($nombre_archivo_enviado));
        $maxproceso       = DB::select("SELECT MAX(consecutivo) as consecutivo FROM as_bduaprocesos");
        
        $bduaProceso                  = new AsBduaproceso();
        $bduaProceso->consecutivo     = $maxproceso[0]->consecutivo+1;
        $bduaProceso->fecha           = date('Y-m-d');
        $bduaProceso->tipo            = 'Movilidad';
        $bduaProceso->gs_usuario_id   = Auth::user()->id;
        $bduaProceso->uuid            = Str::uuid();
        $bduaProceso->save();
        
        $bduaArchivo      = new AsBduaarchivo();
        $bduaArchivo->as_bduaproceso_id       = $bduaProceso->id;
        $bduaArchivo->nombre                  = $this->fileName;
        $this->csvPath                        = $this->fileName;
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


	public function procesar() {

		$this->respuesta = $this->crearRespuesta('Glosas');
		if (!$this->respuesta) {
			$this->pushMonitor('No se pudo crear la respuesta. El archivo no existe: '.$this->fileName, $this->proceso, 'error--text');
			return;
		}
		
		
		//$this->pushMonitor('Iniciando proceso de glosas del archivos: '.$this->fileName, $this->proceso, 'info--text');
		
		$registros      = DB::select("select d.informacion,d.tipo_identificacion_afiliado,d.numero_identificacion_afiliado as identificacion,
                                            a.primer_apellido,a.segundo_apellido,a.primer_nombre,a.segundo_nombre,
                                            a.fecha_nacimiento,if(a.genero=1,'F','M') as genero, a.consecutivo_afiliado, f.tipo_traslado, f.id
                                                 from log_ms_encabezado e
                                                    inner join log_ms_detalle d on
                                                    e.consecutivo_log_ms = d.consecutivo_log_ms
                                                    inner join log_ms_encabezado enc on
                                                    enc.consecutivo_log_ms = d.consecutivo_log_ms AND enc.tipo = ".ETipoBdua::getIndice(ETipoBdua::R6)->getId()."
                                                    inner join af_afiliado_ms a on
                                                    a.consecutivo_ns = d.consecutivo_ms
                                                    inner join as_formtrasmovs f on
                                                    f.as_afiliado_id = a.consecutivo_afiliado and a.tipo_traslado = 'R'
                                                    where a.consecutivo_vigencia = {$this->vigencia}  and a.tipo_traslado = 'R'
                                                    AND f.fecha_efectiva_traslado IS NULL ");
		
		$this->procesadas = 0;
        $this->aplicadas = 0;
        try {
            foreach ($registros as $r) {
                $fila       = explode(',', $r->informacion);
                $this->procesadas++;
				$afiliado = $this->getAfiliado($fila[1], $fila[2]);
				
				/*$traslado = $this->getSolicitudTraslado($afiliado, $this->proceso->id);

				$traslado->estado = 'Glosado';
				$traslado->save();

				$filaRespuesta = implode(',', $fila);

				$regRespuesta = AsBduaregrespuesta::create(
					[
						'as_bduarespuesta_id' => $this->respuesta->id,
						'as_tramite_id' => $traslado->as_tramite_id,
						'respuesta' => $filaRespuesta,
					]
				);
				*/
				
				$tramite = $this->getTramite($afiliado);
				
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
				
				$bduaGlosas = $this->crearGlosas($fila);

				$regRespuesta->glosas()->createMany($bduaGlosas);

				$this->procesarGlosas($afiliado, $regRespuesta);
				$this->aplicadas++;
				//$this->pushMonitor('Proceso creado', $this->proceso, 'console--success');
				$this->pushAvance($this->proceso, $this->respuesta);
    		}
        } catch (\Exception $e) {
            $this->guardarPendiente($e->getMessage());
            $this->pushMonitor('R6: '.$e->getMessage(), $this->proceso, 'error--text');
            //continue;
        }
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
}