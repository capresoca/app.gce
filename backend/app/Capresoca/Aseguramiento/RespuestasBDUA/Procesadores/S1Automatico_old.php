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
use App\Models\Aseguramiento\AsPobespeciale;
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
use App\Capresoca\Aseguramiento\RespuestasBDUA\S1Trait;

class S1Automatico extends Procesador implements ProcesadorCSVInterface
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
    
    use S1Trait;
    public function procesar()
    {
        $this->respuesta = $this->crearRespuestaS1Automatico('Valido');
        if(!$this->respuesta){
            $this->pushMonitor('No se puede crear respuesta archivo no encontrado',$this->proceso,'error--text');
            return;
        }
        $this->procesadas = 0;
        $this->aplicadas = 0;

        $this->pushMonitor('Iniciando proceso',$this->proceso,'info--text');
        foreach ($this->data as $datum) {

            try{
                $this->procesadas ++;

                try{
                    $afiliado = $this->getAfiliado($datum[1],$datum[2]);

                }catch(\Exception $e){

                    $municipio = GnMunicipio::whereCodigo($datum[17].$datum[18])->first()->id;

                    $afiliado = AsAfiliado::updateOrCreate([
                        'gn_tipdocidentidad_id' => $this->tipos_ident[$datum[9]],
                        'identificacion'        => $datum[10],
                    ],[
                        'estado'                => 2,
                        'apellido1'             => $datum[11],
                        'apellido2'             => $datum[12],
                        'nombre1'               => $datum[13],
                        'nombre2'               => $datum[14],
                        'nombre_completo'       => '',
                        'fecha_nacimiento'      => ToolsTrait::homologarFecha($datum[15]),
                        'gn_sexo_id'            => $datum[16] == 'M' ? 1 : 2,
                        'gn_municipio_id'       => $municipio,
                        'gn_zona_id'            => $datum[19] == 'U' ? 1 : 2,
                        'fecha_afiliacion'      => ToolsTrait::homologarFecha($datum[20]),
                        'as_pobespeciale_id'    => $datum[21],
                        'nivel_sisben'          => $datum[22],
                        'as_regimene_id'        => 2,
                        'gn_muniafiliacion_id'  => $municipio
                    ]);
                }

                $tramite = $this->getTramite($afiliado);
                $this->actualizardatosS1($afiliado,$datum);

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

                $this->pushMonitor('Se cambio el estado del tramite traslado Subsidiado a VALIDADO y se aplicaron los cambios correspondientes: '. $regRespuesta->respuesta, $this->proceso,'info--success');

                $this->pushAvance($this->proceso, $this->respuesta);

            } catch (\Exception $e){
                $this->guardarPendiente($e->getMessage());
                $this->pushMonitor($e->getMessage(),$this->proceso,'error--text');
                continue;
            }

        }
        $this->pushMonitor('Proceso finalizado',$this->proceso,'info--text');
    }
    private function actualizardatosS1(AsAfiliado $afiliado, $row){
        $municipio=GnMunicipio::where('codigo', $row[17].$row[18])->first();
        if(!$municipio){
            $log='El codigo del municipio no es valido: '.$row[17].$row[18];            
            throw new \Exception($log);            
        }
        $afiliado->gn_tipdocidentidad_id=$this->tipos_ident[$row[9]];
        $afiliado->identificacion=$row[10];
        $afiliado->apellido1 = $row[11];
        $afiliado->apellido2 = $row[12];
        $afiliado->nombre1 = $row[13];
        $afiliado->nombre2 = $row[14];
        $afiliado->fecha_nacimiento = ToolsTrait::homologarFecha($row[15]);
        $afiliado->gn_sexo_id = $row[16] == 'M' ? 1 : 2;
        $afiliado->gn_municipio_id = $municipio->id;
        $afiliado->gn_zona_id = $row[19] == 'U' ? 1 : 2;
        $afiliado->fecha_afiliacion = ToolsTrait::homologarFecha($row[20]);
        $afiliado->as_pobespeciale_id = $row[21];
        $afiliado->nivel_sisben = $row[22];
        $afiliado->as_regimene_id = 2;
        if ((!empty($row[24])) && (!empty($row[25]))) {
            try {
                $afiliado = $this->getAfiliado($row[24],$row[25]);
                $afiliado->cabfamilia_id = $afiliado->id;
            } catch (\Exception $e) {
                $this->guardarPendiente($e->getMessage().' No se puede asociar la cabeza de familia con el afiliado '.$row[9].' '.$row[10]);
            }
        }
        $afiliado->save();          
    }
    

	private function crearRespuestaS1Automatico($tipo) {
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