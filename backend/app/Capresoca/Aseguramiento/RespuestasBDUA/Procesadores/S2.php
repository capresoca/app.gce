<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31/08/2018
 * Time: 5:12 PM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;

use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesador;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Models\Aseguramiento\AsEps;
use App\Models\Aseguramiento\AsSoltraslado;
use App\Models\Aseguramiento\AsTramite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class S2 extends Procesador implements ProcesadorCSVInterface
{

    public function procesar()
    {

        $gnArchivo = $this->crearGnArchivo();
        try {
            $regimen = $this->getRegimenFromFileName();
        } catch (\Exception $e) {
            $this->pushMonitor($e->getMessage(),$this->proceso,'error--text');
        }


        foreach ($this->data as $filaSolicitud){
            try{

                $afiliado = $this->getAfiliado($filaSolicitud[2], $filaSolicitud[3]);

                $epsSolicitante = AsEps::where('cod_habilitacion', $filaSolicitud[1])->first();

                if(!$this->filaValida($filaSolicitud)){
                    continue;
                }

                $soltraslado = AsSoltraslado::create(
                    [
                        'as_bduaproceso_id' => $this->proceso->id,
                        'gn_archivo_id' => $gnArchivo->id,
                        'bduaserial' => $filaSolicitud[0],
                        'as_afiliado_id' => $afiliado->id,
                        'as_eps_id' => $epsSolicitante ? $epsSolicitante->id: null,
                        'fecha_solicita' => $filaSolicitud[10],
                        'fecha_apropiacion' => $filaSolicitud[11],
                        'cod_eps' => $filaSolicitud[1],
                        'nombre1S2' => $filaSolicitud[6],
                        'nombre2S2' => $filaSolicitud[7],
                        'apellido1S2' => $filaSolicitud[4],
                        'apellido2S2' => $filaSolicitud[5],
                        'cod_departamentoS2' => $filaSolicitud[8],
                        'cod_municipioS2' => $filaSolicitud[9],
                        'tip_docu_cabfamiliaS2' => $filaSolicitud[12],
                        'identificacion_cabfamiliaS2' => $filaSolicitud[13],
                        'parentescoS2' => $filaSolicitud[14],
                        'as_regimen_id' => $regimen,
                        'estado' => 'Registrado'
                    ]
                );

                $tramite = AsTramite::create([
                    'tipo_tramite' => $this->getTipoTramitePorFileName($this->fileName),
                    'clase_tramite' => 'Automatico',
                    'fecha_radicacion' => today()->toDateString(),
                    'estado' => 'Radicado'
                ]);
                if($soltraslado->dias_eps >= 365){
                    $soltraslado->respuesta = 'Aprobado';
                    $soltraslado->estado = 'Aprobado';
                    $soltraslado->as_cautraslado_id = 1;
                    $soltraslado->as_tramite_id = $tramite->id;
                    $soltraslado->save();
                }else{
                    $soltraslado->respuesta = 'Negado';
                    $soltraslado->estado = 'Negado';
                    $soltraslado->as_cautraslado_id = 14;
                    $soltraslado->as_tramite_id = $tramite->id;
                    $soltraslado->save();
                }

                $this->pushMonitor('Se Procesó Solicitud de Traslado Subsidiado.',$this->proceso,'info--text');
            } catch (\Exception $e){
                $this->pushMonitor($e->getMessage(),$this->proceso,'error--text');
                continue;
            }
        }
    }

    /**
     * @param $filaSolicitud
     * @return bool
     * @throws \Exception
     */
    private function filaValida($filaSolicitud)
    {
        $validator = Validator::make($filaSolicitud,[
            0 => 'required',
            1 => 'required|string|size:6',
            2 => 'required|string|size:2',
            3 => 'required|string|min:3|max:16',
            4 => 'required|string|max:20',
            5 => '',
            6 => 'required|string|max:20',
            7 => '',
            8 => 'required|string|size:2',
            9 => 'required|string|size:3',
            10 => 'required|string|size:10',
            11 => 'required|string|size:10',
        ]);

        if($validator->fails()){
            throw new \Exception('La estructura de la fila no es valida');
        }

        return true;
    }

    private function getTipoTramitePorFileName($fileName)
    {
        $fileName = substr($fileName,0,2);

        if($fileName === 'R2') return 'R4';

        if($fileName === 'S2') return 'S4';

        return $fileName;
    }

}


