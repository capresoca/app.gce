<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 26/09/2018
 * Time: 6:21 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;

use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesador;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsBduaarchivo;
use App\Models\Aseguramiento\AsBduaproceso;
use App\Models\Aseguramiento\AsFormtrasmov;
use App\Models\Aseguramiento\AsParentesco;
use App\Models\Aseguramiento\AsTramite;
use App\Models\Aseguramiento\AsTraslatramite;
use App\Models\General\GnEmpresa;
use App\Models\General\GnMunicipio;
use App\Models\General\GnSexo;
use App\Models\General\GnTipdocidentidade;
use App\Traits\ToolsTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class S1 extends Procesador implements ProcesadorCSVInterface
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

    public function procesar()
    {

        $proceso = AsBduaproceso::firstOrCreate([
            'fecha' => $this->getFechaProcesoFromFileName(),
            'tipo'  => 'Traslado'
        ],[
            'gs_usuario_id' => 1
        ]);

        $archivo = AsBduaarchivo::firstOrCreate([
            'as_bduaproceso_id' => $proceso->id,
            'nombre' => $this->fileName,
            'as_tipbduaarchivo_id' => 14
        ]);
        $row=0;
        foreach ($this->data as $fila) {
            try{
                $this->pushMonitor('Procesando Traslados Regiemen Subsidiado (S1) Registro:'.$row,$this->proceso,'info--text');

                try{
                    $afiliado = $this->getAfiliado($fila[1],$fila[2]);

                }catch(\Exception $e){

                    $municipio = GnMunicipio::whereCodigo($fila[17].$fila[18])->first()->id;

                    $afiliado = AsAfiliado::updateOrCreate([
                        'gn_tipdocidentidad_id' => $this->tipos_ident[$fila[9]],
                        'identificacion'        => $fila[10],
                    ],[
                        'estado'                => 2,
                        'apellido1'             => $fila[11],
                        'apellido2'             => $fila[12],
                        'nombre1'               => $fila[13],
                        'nombre2'               => $fila[14],
                        'nombre_completo'       => '',
                        'fecha_nacimiento'      => ToolsTrait::homologarFecha($fila[15]),
                        'gn_sexo_id'            => $fila[16] == 'M' ? 1 : 2,
                        'gn_municipio_id'       => $municipio,
                        'gn_zona_id'            => $fila[19] == 'U' ? 1 : 2,
                        'fecha_afiliacion'      => ToolsTrait::homologarFecha($fila[20]),
                        'as_pobespeciale_id'    => $fila[21],
                        'nivel_sisben'          => $fila[22],
                        'as_regimene_id'        => 2,
                        'gn_muniafiliacion_id'  => $municipio
                    ]);
                }

                if(isset($fila[24],$fila[25], $fila[26])){
                    $cabeza = $this->getAfiliado($fila[24],$fila[25]);
                    $parentesco = AsParentesco::where('codigo',$fila[26])->first();
                }



                DB::beginTransaction();
                $tramite = AsTramite::create([
                    'tipo_tramite' => 'Traslado Subsidiado',
                    'clase_tramite' => 'Testing',
                    'fecha_radicacion' => ToolsTrait::homologarFecha($fila[20]),
                    'estado' => 'Radicado',
                    'as_bduaarchivo_id' => $archivo->id
                ]);

                $formulario = AsFormtrasmov::create(
                    [
                        'tipo' => 'Traslado',
                        'as_afiliado_id' => $afiliado->id,
                        'as_padre_id' => isset($cabeza) ? $cabeza->id : null,
                        'as_parentesco' => isset($parentesco) ? $parentesco->id : null,
                        'gn_tipdocidentidad_id' => GnTipdocidentidade::where('abreviatura',$fila[9])->first()->id,
                        'identificacion' => $fila[10],
                        'nombre1' => $fila[13],
                        'nombre2' => $fila[14],
                        'apellido1' => $fila[11],
                        'apellido2' => $fila[12],
                        'fecha_nacimiento' => ToolsTrait::homologarFecha($fila[15]),
                        'gn_sexo_id' => GnSexo::where('abreviatura', $fila[16])->first()->id,
                        'estado' => 'Radicado',
                        'fecha_traslado' => ToolsTrait::homologarFecha($fila[20]),
                        'tipo_traslado' => $fila[23],
                        'as_eps_id' => 307
                    ]
                );

                AsTraslatramite::create([
                    'as_afiliado_id' => $afiliado->id,
                    'as_tramite_id' => $tramite->id,
                    'as_formtrasmov_id' => $formulario->id,
                    'codigo_entidad' => GnEmpresa::first()->codhabilitacion_subsid
                ]);

                DB::commit();
                $this->pushMonitor('Proceso creado Traslados Regiemen Subsidiado (S1) Registro:'.$row,$this->proceso,'info--text');
                $row++;
            } catch (\Exception $e)
            {
                DB::rollback();
                Log::info('No se pudo s1', $e->getMessage());
                $row++;
                $this->pushMonitor($e->getMessage().' '. $e->getLine(),$this->proceso,'error--text');
                continue;
            }
        }
    }
}

