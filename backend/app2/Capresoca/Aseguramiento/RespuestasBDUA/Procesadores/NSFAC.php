<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 17/09/2018
 * Time: 4:55 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;

use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesador;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Models\Aseguramiento\AsNovtramite;
use App\Models\Aseguramiento\AsTipnovedade;
use App\Models\Aseguramiento\AsTramite;
use App\Traits\ToolsTrait;
use Illuminate\Support\Facades\DB;

class NSFAC extends Procesador implements ProcesadorCSVInterface
{

    public function procesar()
    {
        foreach ($this->data as $novedad) {
            try {
                $afiliado = $this->getAfiliado($novedad[2],$novedad[3]);
                if($afiliado){
                    $tramite = AsTramite::create([
                        'tipo_tramite' => 'Novedad Subsidiado',
                        'clase_tramite' => 'Testing',
                        'fecha_radicacion' => ToolsTrait::homologarFecha($novedad[12]),
                        'consecutivo_reporte' => $novedad[0],
                        'estado' => 'Radicado',
                    ]);
    
                    $tipNovedad = AsTipnovedade::where('codigo',$novedad[11])->first();
    
                    $novtramite = AsNovtramite::create(
                        [
                            'as_tramite_id' => $tramite->id,
                            'as_tipnovedade_id' => $tipNovedad->id,
                            'as_afiliado_id' => $afiliado->id,
                            'gn_municipio_id' => $afiliado->gn_municipio_id,
                            'gn_tipdocidentidad_id' => $afiliado->gn_tipdocidentidad_id,
                            'codigo_entidad' => $novedad[1],
                            'identificacion' => $afiliado->identificacion,
                            'apellido1' => $afiliado->apellido1,
                            'apellido2' => $afiliado->apellido2,
                            'nombre1' => $afiliado->nombre1,
                            'nombre2' => $afiliado->nombre2,
                            'fecha_nacimiento' => $afiliado->fecha_nacimiento,
                            'fecha_ini_novedad' => ToolsTrait::homologarFecha($novedad[12]),
                            'v1' => $novedad[13],
                            'v2' => $novedad[14],
                            'v3' => $novedad[15],
                            'v4' => $novedad[16],
                            'v5' => $novedad[17],
                            'v6' => $novedad[18],
                            'v7' => $novedad[19]
                        ]
                    );
                    $this->pushMonitor('Se proceso Novedad Subsidiado : '.$novedad[2].' - '.$novedad[3],$this->proceso,'console-success');
    
                }
            } catch (\Exception $e) {
                $this->pushMonitor($e->getMessage(),$this->proceso,'console-error');
            }

        }
    }
}