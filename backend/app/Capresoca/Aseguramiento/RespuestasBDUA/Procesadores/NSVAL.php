<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 18/09/2018
 * Time: 8:49 AM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;

use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesador;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Models\Aseguramiento\{
    AsBduaaccione,
    AsBduaregrespuesta,
    AsTramite
};
use Illuminate\Support\Facades\Log;
use App\Capresoca\Aseguramiento\RespuestasBDUA\NovedadesValTrait;
use Illuminate\Support\Facades\DB;
use App\Models\Enums\ETipoBdua;
use App\Models\Aseguramiento\AsBduaproceso;
use App\Models\Aseguramiento\AsBduaarchivo;
use App\Models\Aseguramiento\AsBduarespuesta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Aseguramiento\AsAfiliado;

class NSVAL extends Procesador implements ProcesadorCSVInterface
{
    use NovedadesValTrait;

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
        $bduaArchivo->nombre                  = 'NS_VAL'.$bduaProceso->id;
        $this->csvPath                        = 'NS_VAL'.$bduaProceso->id;
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
                $query2->where('estado_traslado','NS_VAL');
            });
        })->first();
        Log::info('Tramite Consulta: ',array($afiliado));
        if(!$tramite){
            $afiliadoDatosRelacionados = $afiliado->with('tipo_id')->first();
            throw new \Exception('No se encontro el tramite de traslado subsidiado del afiliado: '.$afiliadoDatosRelacionados->tipo_id->abreviatura.' '.$afiliadoDatosRelacionados->identificacion.' del proceso: '.$this->respuesta->as_bduaarchivo_id);
            
        }
        
        return $tramite;
    }
    
    public function procesar()
    {
        $registros      = DB::select("select d.informacion_ns AS informacion,d.tipo_identificacion_afiliado,d.numero_identificacion_afiliado as identificacion,
                                        a.apellido1 as primer_apellido,a.apellido2 as segundo_apellido,a.nombre1 as primer_nombre,a.nombre2 as segundo_nombre,
                                        a.fecha_nacimiento,if(a.gn_sexo_id=1,'F','M') as genero, f.as_afiliado_id as consecutivo_afiliado, a.estado_traslado, f.id
                                        from log_ns_encabezado e
                                        inner join log_ns_detalle d on
                                        e.consecutivo_log= d.consecutivo_log_ns
                                        inner join as_novtramites f on
                                        f.consecutivo_log_ns = d.consecutivo_log_ns_detalle
                                        inner join as_afiliados a on 
                                        a.id = f.as_afiliado_id AND a.estado_traslado = 'NS_VAL'
                                            where a.consecutivo_vigencia = {$this->vigencia} and e.tipo = ".ETipoBdua::getIndice(ETipoBdua::NS_VAL)->getId()." ");

        /*Log::info('consulta: ',array("select d.informacion_ns AS informacion,d.tipo_identificacion_afiliado,d.numero_identificacion_afiliado as identificacion,
                                        a.apellido1 as primer_apellido,a.apellido2 as segundo_apellido,a.nombre1 as primer_nombre,a.nombre2 as segundo_nombre,
                                        a.fecha_nacimiento,if(a.gn_sexo_id=1,'F','M') as genero, f.as_afiliado_id as consecutivo_afiliado, a.estado_traslado, f.id
                                        from log_ns_encabezado e
                                        inner join log_ns_detalle d on
                                        e.consecutivo_log= d.consecutivo_log_ns
                                        inner join as_novtramites f on
                                        f.consecutivo_log_ns = d.consecutivo_log_ns_detalle
                                        inner join as_afiliados a on
                                        a.id = f.as_afiliado_id AND a.estado_traslado = 'NS_VAL'
                                            where a.consecutivo_vigencia = {$this->vigencia} and e.tipo = ".ETipoBdua::getIndice(ETipoBdua::NS_VAL)->getId()." "));*/
        
        $this->numFilas   = count($registros);
        
        $this->respuesta = $this->crearRespuestaS3('Valido');
        if(!$this->respuesta){
            return;
        }
        $this->procesadas = 0;
        $this->aplicadas = 0;
        
        $log = 'Se inicio el procesamiento de la respuesta ' . $this->respuesta->tipo_respuesta .': '. $this->respuesta->archivo->nombre;

        //$this->pushMonitor($log, $this->proceso,'info');

        //$arrayAfiliados         = array();
        //Log::info('Registros NS: ',array($registros));
        foreach ($registros as $r) {
            try{
                
                $novedad       = explode(',', $r->informacion);
                
                $this->procesadas ++;
                $log = 'Procesando novedad Subsidiado: '.$novedad[0];
                //$this->pushAvance($this->proceso, $this->respuesta);
                $this->pushMonitor($log,$this->proceso,'white--text');
                
                

                $afiliado = $this->getAfiliado($novedad[2],$novedad[3]);


                $tramite = $this->getTramiteS3($afiliado);/*AsTramite::where('as_bduaarchivo_id', $this->respuesta->as_bduaarchivo_id)
                    ->where('consecutivo_reporte', $novedad[0])->first();*/
                
                if(!$tramite) {
                    $log = 'No se encontro el tramite:'.$this->respuesta->as_bduaarchivo_id.' '.$novedad[0];
                    Log::info($log);
                    $this->guardarPendiente($log);
                    $this->pushMonitor($log,$this->proceso,'error--text');
                    continue;
                }
                $tramite->estado = 'Validado';
                $tramite->save();

                $regRespuesta = AsBduaregrespuesta::create(
                    [
                        'as_bduarespuesta_id' => $this->respuesta->id,
                        'as_tramite_id' => $tramite->id,
                        'respuesta' => implode(',', $novedad)
                    ]
                );

                $tipNove = $novedad[11];
                
                Log::info('Tipo Novedad: '.$tipNove);

                if(!method_exists($this, $tipNove)) continue;

                $this->$tipNove($afiliado, $novedad, $regRespuesta);

                Log::info('Llega fin: __________________________________________________________________________________________________');
                $this->pushMonitor('Se proceso la novedad Subsidiada: '.$novedad[0],$this->proceso,'console-success');

            }catch (\Exception $e)
            {
                Log::info($e->getMessage());
                $this->pushMonitor($e->getMessage(),$this->proceso,'error--text');
                continue;
            }
        }


    }

    private function guardarAccion($afiliado, $regRespuesta, $oldValues = array(), $newValues = array(), $accion = 'Se actualizaron los datos del afiliado')
    {
        $accion = AsBduaaccione::create(
            [
                'as_bduaregrespuesta_id' => $regRespuesta->id,
                'as_afiliado_id' => $afiliado->id,
                'accion' => $accion,
                'new_values' => json_encode($newValues, JSON_UNESCAPED_UNICODE),
                'old_values' => json_encode($oldValues, JSON_UNESCAPED_UNICODE)
            ]
        );

        if($accion){
            $this->aplicadas ++;
            //$this->pushMonitor('Se actualizaron los datos del afiliado: '.$afiliado->tipo_identificacion.' '.$afiliado->identificacion ,$this->proceso,'console-success');
        }

        //$this->pushAvance($this->proceso, $this->respuesta);

    }


    private function novedadSinAlgoritmo($novedad)
    {
        $log = 'No se ha implementado la función para procesar esta novedad: ' .$novedad[11];
        $this->guardarPendiente($log);
        //$this->pushMonitor($log,$this->proceso,'error--text');
    }


}