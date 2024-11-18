<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 28/08/2018
 * Time: 3:00 PM
 */

namespace App\Capresoca\Aseguramiento;


use App\Models\Aseguramiento\AsBduaarchivo;
use App\Models\Aseguramiento\AsBduaproceso;
use App\Models\Aseguramiento\AsTipbduaarchivo;
use App\Models\Aseguramiento\AsTramite;
use App\Models\General\GnEmpresa;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use App\Events\BduaProcesosEvent;

class GeneradorArchivosBDUA
{
    protected $proceso;

    protected $empresa;

    const MS = 1;
    const NS = 2;
    const NC = 3;
    const MA = 18;
    const S1 = 14;
    const R1 = 13;
    const S4 = 30;
    const R4 = 31;
    const MC = 37;

    public function __construct(AsBduaproceso $proceso)
    {
        $this->proceso = $proceso;

        $this->empresa = GnEmpresa::first();

        if ($proceso->tipo === 'General'){
            $this->generarArchivosGeneral();
        } else {
            $this->generarArchivosTraslados();
        }

    }

    private function generarArchivosGeneral()
    {
        $this->crearPorTipo(AsTipbduaarchivo::find(self::NS),$this->empresa->codhabilitacion_subsid);
        $this->crearPorTipo(AsTipbduaarchivo::find(self::MS),$this->empresa->codhabilitacion_subsid);
        $this->crearPorTipo(AsTipbduaarchivo::find(self::MC),$this->empresa->codhabilitacion_contrib);
        $this->crearPorTipo(AsTipbduaarchivo::find(self::NC),$this->empresa->codhabilitacion_contrib);
        $this->crearPorTipo(AsTipbduaarchivo::find(self::MA),$this->empresa->codhabilitacion_contrib);
        //$this->crearPorTipo(AsTipbduaarchivo::find(self::S4),$this->empresa->codhabilitacion_contrib);
        //$this->crearPorTipo(AsTipbduaarchivo::find(self::R4),$this->empresa->codhabilitacion_contrib);
        $this->crearArchivoS4(AsTipbduaarchivo::find(self::S4));
        $this->crearArchivoR4(AsTipbduaarchivo::find(self::R4));
//        $this->crearArchivoMA();

    }

    private function generarArchivosTraslados()
    {
        $this->crearTrasladosPorTipo(AsTipbduaarchivo::find(self::S1),$this->empresa->codhabilitacion_subsid);
        $this->crearTrasladosPorTipo(AsTipbduaarchivo::find(self::R1),$this->empresa->codhabilitacion_contrib);

    }

    private function crearPorTipo($tipoArchivo, $cod_empresa)
    {
        $tramitesPendientes = Astramite::PendientesPorTipo($tipoArchivo->tipo_tramite)->get();

        if($tramitesPendientes->count()){

            $this->pushMonitor('begin','white--text');

            $bduaarchivo = $this->crearBduaarchivo($tipoArchivo, $cod_empresa, $tramitesPendientes->count());
            $this->anexarTramites($tramitesPendientes, $bduaarchivo);

            $this->pushMonitor('end','white--text');
        }
    }

    private function crearTrasladosPorTipo($tipoArchivo, $cod_empresa)
    {
        $trasladosPendientes = AsTramite::TrasladoPorTipo($tipoArchivo->tipo_tramite)->get();
        if($trasladosPendientes->count()){
            
            $this->pushMonitor('begin','white--text');

            $bduaarchivo = $this->crearBduaarchivo($tipoArchivo, $cod_empresa, $trasladosPendientes->count());
            $this->anexarTramites($trasladosPendientes, $bduaarchivo);

            $this->pushMonitor('end','white--text');
        }

    }
    public function crearArchivoS4($tipoArchivo)
    {
        $tramitesPendientesSubsidado = Astramite::PendientesPorTipo($tipoArchivo->tipo_tramite,$tipoArchivo->tipo_tramite.'0')->get();
        
        if($tramitesPendientesSubsidado->count()){
            
            $this->pushMonitor('begin','white--text');
            
            $bduaarchivoSubsidiado = $this->crearBduaarchivo($tipoArchivo, $this->empresa->codhabilitacion_subsid, $tramitesPendientesSubsidado->count());
            $this->anexarTramites($tramitesPendientesSubsidado, $bduaarchivoSubsidiado);
            
            $this->pushMonitor('end','white--text');
        }
        $tramitesPendientesContributivo = Astramite::PendientesPorTipo($tipoArchivo->tipo_tramite,$tipoArchivo->tipo_tramite.'C')->get();
        
        if($tramitesPendientesContributivo->count()){
            
            $this->pushMonitor('begin','white--text');
            
            $bduaarchivoContributivo = $this->crearBduaarchivo($tipoArchivo, $this->empresa->codhabilitacion_contrib, $tramitesPendientesContributivo->count());
            $this->anexarTramites($tramitesPendientesContributivo, $bduaarchivoContributivo);
            
            $this->pushMonitor('end','white--text');
        }
    }
    public function crearArchivoR4($tipoArchivo)
    {
        $tramitesPendientesSubsidado = Astramite::PendientesPorTipo($tipoArchivo->tipo_tramite,$tipoArchivo->tipo_tramite.'0')->get();
        
        if($tramitesPendientesSubsidado->count()){
            
            $this->pushMonitor('begin','white--text');

            $bduaarchivoSubsidiado = $this->crearBduaarchivo($tipoArchivo, $this->empresa->codhabilitacion_subsid, $tramitesPendientesSubsidado->count());
            $this->anexarTramites($tramitesPendientesSubsidado, $bduaarchivoSubsidiado);
            
            $this->pushMonitor('end','white--text');
        }
        $tramitesPendientesContributivo = Astramite::PendientesPorTipo($tipoArchivo->tipo_tramite,$tipoArchivo->tipo_tramite.'C')->get();
        
        if($tramitesPendientesContributivo->count()){
            
            $this->pushMonitor('begin','white--text');

            $bduaarchivoContributivo = $this->crearBduaarchivo($tipoArchivo, $this->empresa->codhabilitacion_contrib, $tramitesPendientesContributivo->count());
            $this->anexarTramites($tramitesPendientesContributivo, $bduaarchivoContributivo);
            
            $this->pushMonitor('end','white--text');
        }
    }

    private function crearBduaarchivo(AsTipbduaarchivo $tipo_archivo,$codigo_empresa, $registros=NULL)
    {

        $nombreArchivo = $this->getNombreArchivo($tipo_archivo->iniciales,$codigo_empresa);

        $archivo = AsBduaarchivo::where('nombre', $nombreArchivo)->first();

        if($archivo)
            return $archivo;

        $archivo =  AsBduaarchivo::updateOrCreate(
            [
                'as_bduaproceso_id' => $this->proceso->id,
                'nombre' => $nombreArchivo,
                'as_tipbduaarchivo_id' => $tipo_archivo->id,
                'numero_registros' => $registros
            ]);

        return $archivo;
    }


    private function anexarTramites(Collection $tramites_pendientes, AsBduaarchivo $bduaarchivo)
    {
        foreach ($tramites_pendientes as $key => $tramite) {
            $tramite->as_bduaarchivo_id = $bduaarchivo->id;
            if(!$tramite->consecutivo_reporte){
                $tramite->consecutivo_reporte = $key + 1;
            }
            $tramite->save();
        }
    }

    private function getNombreArchivo($tipo,$codigo_empresa)
    {
        $fecha = new Carbon($this->proceso->fecha);
        return $tipo.$codigo_empresa.$fecha->format('dmY');
    }
    

    private function pushMonitor($message, $class){
        Log::info($message);
        broadcast(
            new BduaProcesosEvent(
                [
                    "type" => "generador",
                    "message"=> [
                        'text' => $message,
                        'class' => $class
                    ]
                ],
                $this->proceso
            )
        );
    }

}