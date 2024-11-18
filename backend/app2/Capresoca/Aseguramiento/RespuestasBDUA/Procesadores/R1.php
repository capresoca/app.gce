<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31/08/2018
 * Time: 6:28 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;

use App\Models\Aseguramiento\AsAfiliado;
use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesador;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Models\Aseguramiento\AsBduaarchivo;
use App\Models\Aseguramiento\AsBduaproceso;
use App\Models\Aseguramiento\AsClasecotizante;
use App\Models\Aseguramiento\AsEps;
use App\Models\Aseguramiento\AsFormtrasmov;
use App\Models\Aseguramiento\AsPagadore;
use App\Models\Aseguramiento\AsParentesco;
use App\Models\Aseguramiento\AsTipafiliado;
use App\Models\Aseguramiento\AsTramite;
use App\Models\Aseguramiento\AsTraslatramite;
use App\Models\General\GnEmpresa;
use App\Models\General\GnSexo;
use App\Models\General\GnTipdocidentidade;
use App\Models\Niif\NfCiiu;
use App\Traits\ToolsTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class R1 extends Procesador implements ProcesadorCSVInterface
{

    public function procesar()
    {

        $row = 0;

        $proceso = AsBduaproceso::firstOrCreate([
            'fecha' => $this->getFechaProcesoFromFileName(),
            'tipo' => 'Traslado'
        ], [
            'gs_usuario_id' => 1
        ]);

        $this->pushMonitor('Se creo el proceso:' . $proceso->fecha, $this->proceso, 'info--text');

        $archivo = AsBduaarchivo::firstOrCreate([
            'as_bduaproceso_id' => $proceso->id,
            'nombre' => $this->fileName,
            'as_tipbduaarchivo_id' => 13
        ]);

        $this->pushMonitor('Se creo el bduaarchivo:' . $archivo->nombre, $this->proceso, 'info--text');

        foreach ($this->data as $fila) {
            try {
                $afiliado = $this->getAfiliado($fila[1], $fila[2]);
                $this->pushMonitor('Procesando Traslados Regiemen Contributivo (R1) Registro:' . $row, $this->proceso, 'info--text');

                $cabeza = AsAfiliado::AfiliadoPorIdentificacion([
                    'identificacion' => $fila[13],
                    'tipoId' => $fila[12]])->first();
                $parentesco = AsParentesco::where('codigo', $fila[24])->first();
                $tipo_id = GnTipdocidentidade::where('abreviatura', $fila[14])->first();
                $eps_solicitado = AsEps::where('cod_habilitacion', $fila[9])->first();
                $actividad = NfCiiu::where('codigo', $fila[32])->first();
                $tipafiliado = AsTipafiliado::where('codigo_planos', $fila[23])->first();
                $clasecotizante = AsClasecotizante::where('codigo', $fila[22])->first();

                $tipo_id_aportante = GnTipdocidentidade::where('abreviatura', $fila[30])->first();

                //if(!$tipo_id_aportante)
                //throw new \Exception('No se encontró este tipo de identificación del aportante var:30'.$fila[30].' Fila:'.$row);

                $aportante = DB::table('as_pagadores')
                    ->join('gn_terceros', 'gn_terceros.id', '=', 'as_pagadores.gn_tercero_id')
                    ->select('as_pagadores.id')
                    ->where('gn_terceros.gn_tipdocidentidad_id', !$tipo_id_aportante ? null : $tipo_id_aportante->id)
                    ->where('gn_terceros.identificacion', $fila[31])->first();

                //if(!$aportante)
                //  throw new \Exception('No se encontro el aportante R1 var30:'.$fila[30].' var31'.$fila[31].' Fila:'.$row);

                DB::beginTransaction();

                $tramite = AsTramite::create([
                    'tipo_tramite' => 'Traslado Contributivo',
                    'clase_tramite' => 'Testing',
                    'fecha_radicacion' => today()->toDateString(),
                    'consecutivo_reporte' => (int)$fila[10],
                    'estado' => 'Enviado',
                    'as_bduaarchivo_id' => $archivo->id
                ]);

                $formulario = AsFormtrasmov::create(
                    [
                        'tipo' => 'Movilidad',
                        'as_afiliado_id' => $afiliado->id,
                        'as_padre_id' => !$cabeza ? null : $cabeza->id,
                        'as_parentesco' => !$parentesco ? null : $parentesco->id,
                        'gn_tipdocidentidad_id' => $tipo_id->id,
                        'identificacion' => $fila[15],
                        'nombre1' => $fila[18],
                        'nombre2' => $fila[19],
                        'apellido1' => $fila[16],
                        'apellido2' => $fila[17],
                        'as_eps_id' => $eps_solicitado->id,
                        'fecha_nacimiento' => ToolsTrait::homologarFecha($fila[20]),
                        'gn_sexo_id' => GnSexo::where('abreviatura', $fila[21])->first()->id,
                        'nf_ciiu_id' => !$actividad ? null : $actividad->id,
                        'estado' => 'Radicado',
                        'fecha_traslado' => ToolsTrait::homologarFecha($fila[29]),
                        'as_clasecotizante_id' => !$clasecotizante ? null : $clasecotizante->id,
                        'as_pagadore_id' => !$aportante ? null : $aportante->id
                    ]
                );

                AsTraslatramite::create([
                    'as_afiliado_id' => $afiliado->id,
                    'as_tramite_id' => $tramite->id,
                    'as_formtrasmov_id' => $formulario->id,
                    'as_tipafiliado_id' => $tipafiliado->id,
                    'codigo_entidad' => $fila[9],
                ]);

                DB::commit();
                $this->pushMonitor('Proceso Creado Traslados Regimen Contributivo (R1) Registro:' . $row, $this->proceso, 'console-success');
                $row++;

            } catch (\Exception $e) {
                $row++;
                DB::rollback();
                $this->pushMonitor($e->getMessage(), $this->proceso, 'error--text');
                continue;
            }
        }
    }
}