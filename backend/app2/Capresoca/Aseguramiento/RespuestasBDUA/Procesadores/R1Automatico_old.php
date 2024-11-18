<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 6/11/2018
 * Time: 9:27 AM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;


use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesador;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Models\Aseguramiento\AsBduaarchivo;
use App\Models\Aseguramiento\AsBduaregrespuesta;
use App\Models\Aseguramiento\AsTramite;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsClasecotizante;
use App\Models\Aseguramiento\AsPagadore;
use App\Models\General\GnTipdocidentidade;
use App\Models\Aseguramiento\AsTipafiliado;
use App\Models\Aseguramiento\AsParentesco;
use App\Models\Aseguramiento\AsCondicionDiscapacidade;
use App\Models\Aseguramiento\AsAfiliadoPagador;
use App\Models\Aseguramiento\AsBduarespuesta;
use App\Models\General\GnMunicipio;
use App\Models\Niif\NfCiiu;
use App\Models\General\GnZona;
use Illuminate\Support\Facades\Log;
use App\Traits\ToolsTrait;
use App\Models\General\GnSexo;

class R1Automatico extends Procesador implements ProcesadorCSVInterface
{
    
    protected $tipos_ident = [
        'CC' => 1,
        'TI' => 2,
        'RC' => 3,
        'CN' => 4,
        'PA' => 5,
        'CD' => 6,
        'SC' => 7,
        'CE' => 9,
        'AS' => 10,
        'MS' => 11,
        'NIT'=> 12,
        'PE' => 13,

    ];
    protected $clase_cotizante = [
        	"1" =>	1,
        	"2" =>	2,
        	"3" =>	3,
        	"4" =>	4,
        	"12" =>	5,
        	"16" =>	6,
        	"18" =>	7,
        	"19" =>	8,
        	"20" =>	9,
        	"22" =>	10,
        	"31" =>	11,
        	"32" =>	12,
        	"33" =>	13,
        	"35" =>	14,
        	"36" =>	15,
        	"42" =>	16,
        	"47" =>	17,
        	"52" =>	18,
        	"53" =>	19,
        	"55" =>	20,
        	"10" =>	21,
        	"11" =>	22,
        	"15" =>	23,
        	"21" =>	24,
        	"44" =>	25,
        	"45" =>	26,
        	"48" =>	27,
        	"56" =>	28,
    ];
    protected $tipo_afiliado = [
        "C" => 1,
        "F" => 2,
        "B" => 3,
        "A" => 4,
        "O" => 5,
    ];
    protected $parentesco = [
        "1" => 1,
        "2" => 2,
        "4" => 3,
        "9" => 4,
        "5" => 5,
        "3" => 6,
        "7" => 7,
        "8" => 8,
    ];

    protected $discapacidades = [
        "D1" => 1,
        "D2" => 2,
        "D3" => 3,
        "D4" => 4,
        "D5" => 5,
        "D6" => 6,  
    ];

    public function procesar()
    {
        $this->respuesta = $this->crearRespuestaR1Automatico('Valido');
        if(!$this->respuesta){
            $this->pushMonitor('No se puede crear respuesta archivo R1 no encontrado',$this->proceso,'error--text');
            return;
        }
        $this->procesadas = 0;
        $this->aplicadas = 0;

        $this->pushMonitor('Iniciando proceso',$this->proceso,'info--text');
        foreach ($this->data as $datum) {

            try{
                $this->procesadas ++;
                $afiliado = $this->getAfiliado($datum[1], $datum[2]);
                $tramite = AsTramite::where('as_bduaarchivo_id', $this->respuesta->as_bduaarchivo_id)
                   ->where('consecutivo_reporte', $datum[10])->first();

                if(!$tramite) {
                    $log = 'No se encontro el tramite:'.$this->respuesta->as_bduaarchivo_id.' '.$datum[10];
                    $this->guardarPendiente($log);
                    $this->pushMonitor($log,$this->proceso,'error--text');
                    continue;
                }
                $this->actualizardatosR1($afiliado,$datum);

                $regRespuesta = AsBduaregrespuesta::create(
                    [
                        'as_bduarespuesta_id' => $this->respuesta->id,
                        'as_tramite_id' => $tramite->id,
                        'respuesta' => implode(',', $datum)
                    ]
                );

                $tramite->estado = 'Validado';

                $tramite->save();

                $this->aplicadas ++;

                $this->pushMonitor('Se cambio el estado del tramite traslado contributivo a VALIDADO y se aplicaron los cambios correspondientes: '. $regRespuesta->respuesta, $this->proceso,'console-success');

                $this->pushAvance($this->proceso, $this->respuesta);

            } catch (\Exception $e){
                $this->guardarPendiente($e->getMessage());
                $this->pushMonitor($e->getMessage().' Line:'.$e->getLine(),$this->proceso,'error--text');
                continue;
            }

        }
        $this->pushMonitor('Proceso finalizado',$this->proceso,'info--text');
    }
    private function actualizardatosR1(AsAfiliado $afiliado, $row){
        $municipio=GnMunicipio::where('codigo', $row[26].$row[27])->first();
        if(!$municipio){
            $log='El codigo del municipio no es valido: '.$row[26].$row[27];
            
            throw new \Exception($log);            
        }

        
        $afiliado->gn_tipdocidentidad_id=$this->tipos_ident[$row[14]];
        $afiliado->identificacion=$row[15];
        $afiliado->apellido1 = $row[16];
        $afiliado->apellido2 = $row[17];
        $afiliado->nombre1 = $row[18];
        $afiliado->nombre2 = $row[19];
        $afiliado->fecha_nacimiento = ToolsTrait::homologarFecha($row[20]);
        $afiliado->gn_sexo_id = $row[21] == 'M' ? 1 : 2;
        $afiliado->as_clasecotizante_id = empty($row[22]) ? NULL: $this->clase_cotizante[$row[22]];
        $afiliado->as_tipafiliado_id = empty($row[23]) ? NULL: $this->tipo_afiliado[$row[23]];
        $afiliado->as_parentesco_id = empty($row[24]) ? NULL: $this->parentesco[$row[24]];
        $afiliado->as_condicion_discapacidades_id = empty($row[25]) ? NULL: $this->discapacidades[$row[25]];
        $afiliado->gn_municipio_id = $municipio->id;
        $afiliado->gn_zona_id = $row[28] == 'U' ? 1 : 2;
        $afiliado->fecha_afiliacion = ToolsTrait::homologarFecha($row[29]);
        $afiliado->as_regimene_id = 1;
        $afiliado->save();

        /*$tipo_documento_aportante = GnTipdocidentidade::where('abreviatura',$this->getTraslateTipoIdentificacionAportante($row[30]))->first();

        if(!$tipo_documento_aportante){
            $log='Tipo de documento del aportante no valida No se puede crear el la relacion aportante pagador'.$row[30];
            $this->guardarPendiente($log);
            $this->pushMonitor($log, $this->proceso,'white-success');
        }*/
        // Esto NUNCA VA A PASAR por que los pagadores NO tienen tercero asignado por consiguiente no se pueden encontrar en la base de datos /////
        
        /*
        $aportante = AsPagadore::whereHas('tercero', function ($query) use ($row, $tipo_documento_aportante){
            $query->where('identificacion', $row[31])
                    ->where('gn_tipdocidentidad_id', $tipo_documento_aportante->id);
        })->first();*/
        $aportante = AsPagadore::where('identificacion',$row[31])->first();
        if (!$aportante) {
            $log='No se puede crear relacion aportante-afiliado por que el aportante no existe: '.$row[30].' '.$row[31];
            $this->guardarPendiente($log);
            $this->pushMonitor($log, $this->proceso,'white-success');
        }else{
            $actividad = NfCiiu::where('codigo', $row[32])->first();
            if (!$actividad) {
                $log='No se puede crear relacion aportante-afiliado que la actividad economica no es valida CIIU: '.$row[32];
                $this->guardarPendiente($log);
                $this->pushMonitor($log, $this->proceso,'white-success');                           
            }
            AsAfiliadoPagador::create(
                [
                    'as_afiliado_id' => $afiliado->id,
                    'as_pagador_id' => $aportante->id,
                    'fecha_vinculacion' => ToolsTrait::homologarFecha($row[29]),
                    'nf_ciiu_id' => !$actividad ? null: $actividad->id,
                ]
            );
        }                
    }
    

	private function crearRespuestaR1Automatico($tipo) {
		$nombre_archivo_enviado = substr($this->fileName, 12, 16);

		$bduaArchivo = AsBduaarchivo::where('nombre', $nombre_archivo_enviado)->first();

		if (!$bduaArchivo) {
			return;
		}

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
}