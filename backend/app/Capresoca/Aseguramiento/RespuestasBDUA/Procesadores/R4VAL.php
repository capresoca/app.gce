<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 6/11/2018
 * Time: 9:27 AM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;


use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesador;
use App\Capresoca\Aseguramiento\RespuestasBDUA\TrasladosTrait;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Models\Aseguramiento\AsBduaaccione;
use App\Models\Aseguramiento\AsBduaregrespuesta;
use App\Models\Enums\ETipoBdua;
use Illuminate\Support\Facades\DB;
use App\Models\Aseguramiento\AsBduaproceso;
use App\Models\Aseguramiento\AsBduaarchivo;
use App\Models\Aseguramiento\AsBduarespuesta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class R4VAL extends Procesador implements ProcesadorCSVInterface
{
    use TrasladosTrait;
    
    protected $vigencia;
    
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
    
    public function procesar()
    {
        
        $registros      = DB::select("select d.informacion
                                                 from log_ms_encabezado e
                                                    inner join log_ms_detalle d on
                                                    e.consecutivo_log_ms = d.consecutivo_log_ms
                                                    where e.consecutivo_vigencia = {$this->vigencia} AND e.tipo = ".ETipoBdua::getIndice(ETipoBdua::R4)->getId()." ");
        $this->numFilas   = count($registros);
        
        $this->respuesta = $this->crearRespuestaS3('Valido');
        if(!$this->respuesta){
			$this->pushMonitor('No se pudo crear la respuesta. El archivo no existe: '.$this->fileName, $this->proceso, 'error--text');
            return;
        }
        $this->procesadas = 0;
        $this->aplicadas = 0;

        try{
            foreach ($registros as $r) {
                $datum       = explode(',', $r->informacion);
                $this->procesadas ++;
                
                $afiliado = $this->getAfiliado($datum[2],$datum[3]);

                $regimen = substr($this->fileName,0,8) == 'R4EPS025' ? 2 : 1;

                $solTraslado =$this->getSolTrasladoTramiteContributivo($afiliado,ltrim($datum[4],'0'),$this->proceso->id,$regimen);

                $regRespuesta = AsBduaregrespuesta::create(
                    [
                        'as_bduarespuesta_id' => $this->respuesta->id,
                        'as_tramite_id' => $solTraslado->as_tramite_id,
                        'respuesta' => implode(',', $datum)
                    ]
                );

                $solTraslado->estado = 'Validado';

                $solTraslado->save();


                $this->aplicadas ++;

                $this->pushMonitor('Se cambio el estado del tramite traslado contributivo a validado: '. $regRespuesta->respuesta, $this->proceso,'console-success');

                $this->pushAvance($this->proceso, $this->respuesta);
    
            }
        } catch (\Exception $e){
            $this->pushMonitor($e->getMessage(),$this->proceso,'error--text');
        }
		$this->pushMonitor('Archivo procesado por completo: '.$this->fileName, $this->proceso, 'white--text');
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