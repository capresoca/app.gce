<?php


namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;


use App\Capresoca\Aseguramiento\RespuestasBDUA\MSTrait;
use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesador;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Models\Aseguramiento\AsBduaaccione;
use App\Models\Aseguramiento\AsBduaregrespuesta;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Aseguramiento\AsBduaproceso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Aseguramiento\AsBduaarchivo;
use App\Models\Aseguramiento\AsBduarespuesta;
use App\Models\Enums\ETipoBdua;

class MCVAL extends Procesador implements ProcesadorCSVInterface
{
    use MSTrait;
    
    protected $vigencia;

    public function procesar()
    {
        $this->procesadas = 0;
        $this->aplicadas = 0;
        $this->respuesta = $this->crearRespuestaMCVAL('Valido');
        $this->proceso = $this->respuesta->archivo->proceso;

        if(!$this->respuesta) {
            $log = 'No se encuentra MC VAL';
            $this->pushMonitor($log,$this->proceso,'error--text');
            return;
        }

        $log = 'Se inicio el procesamiento de la respuesta ' . $this->respuesta->tipo_respuesta .': '. $this->respuesta->archivo->nombre;
        Log::info($log);
        $this->pushMonitor($log, $this->proceso,'info');
        
        $registros      = DB::select("select d.informacion,d.tipo_identificacion_afiliado,d.numero_identificacion_afiliado as identificacion,
                                            a.primer_apellido,a.segundo_apellido,a.primer_nombre,a.segundo_nombre,
                                            a.fecha_nacimiento,if(a.genero=1,'F','M') as genero, a.consecutivo_afiliado, f.id
                                                 from log_ms_encabezado e
                                                    inner join log_ms_detalle d on
                                                    e.consecutivo_log_ms = d.consecutivo_log_ms
                                                    inner join log_ms_encabezado enc on
                                                    enc.consecutivo_log_ms = d.consecutivo_log_ms AND enc.tipo = ".ETipoBdua::getIndice(ETipoBdua::MC_VAL)->getId()."
                                                    inner join af_afiliado_ms a on
                                                    a.consecutivo_ns = d.consecutivo_ms
                                                    inner join as_formafiliaciones f on
                                                    f.as_afiliado_id = a.consecutivo_afiliado and a.tipo_traslado = 'MC'
                                                    where a.consecutivo_vigencia = {$this->vigencia}  and a.tipo_traslado = 'MC' ");


        $this->procesadas = 0;
        $this->aplicadas = 0;
        $this->numFilas   = count($registros);
        Log::info('Cantidad Registros: '.$this->numFilas);
        
        foreach ($registros as $r)
        {
            try {
                $afiliacion         = explode(',', $r->informacion);
                $this->procesadas++;
                $afiliado = $this->getAfiliado($afiliacion[3], $afiliacion[4]);

                $estado_activo = 3;
                $afiliado->estado = $estado_activo;
                $afiliado->save();
                $tramite = $this->getTramite($afiliado, $afiliacion[18]);

                $tramite->estado = 'Validado';
                $tramite->save();

                $filaRespuesta = $r->informacion;

                $regRespuesta = AsBduaregrespuesta::create(
                    [
                        'as_bduarespuesta_id' => $this->respuesta->id,
                        'as_tramite_id' => $tramite->id,
                        'respuesta' => $filaRespuesta
                    ]
                );

                $accion = AsBduaaccione::create(
                    [
                        'as_bduaregrespuesta_id' => $regRespuesta->id,
                        'as_afiliado_id' => $afiliado->id,
                        'accion' => 'Se activo el afiliado'
                    ]
                );

                if ($accion) {
                    $this->aplicadas++;
                    $this->pushMonitor('Se activo el afiliado: ' . $afiliado->tipo_identificacion . ' ' .
                        $afiliado->identificacion, $this->proceso, 'console-success');
                }

                //$this->pushAvance($this->proceso, $this->respuesta);
            } catch (\Exception $e){
                Log::error($e);
                $this->guardarPendiente($e->getMessage());
                $this->pushMonitor($e->getMessage(),$this->proceso,'error--text');
                continue;
            }


        }
    }
    
    private function crearRespuestaMCVAL($tipo) {
        //$nombre_archivo_enviado = 'S1' . substr($this->fileName, 2, 14);
        //Log::info('Archivo: ',array($nombre_archivo_enviado));
        $maxproceso       = DB::select("SELECT MAX(consecutivo) as consecutivo FROM as_bduaprocesos");
        
        $bduaProceso                  = new AsBduaproceso();
        $bduaProceso->consecutivo     = $maxproceso[0]->consecutivo+1;
        $bduaProceso->fecha           = date('Y-m-d');
        $bduaProceso->tipo            = 'Afiliacion';
        $bduaProceso->gs_usuario_id   = Auth::user()->id;
        $bduaProceso->uuid            = Str::uuid();
        $bduaProceso->save();
        
        $bduaArchivo      = new AsBduaarchivo();
        $bduaArchivo->as_bduaproceso_id       = $bduaProceso->id;
        $bduaArchivo->nombre                  = $this->fileName.'_'.$bduaProceso->id;
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