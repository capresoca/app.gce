<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 4/09/2018
 * Time: 9:27 AM
 */

namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;

use App\Capresoca\Aseguramiento\RespuestasBDUA\Procesador;
use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorCSVInterface;
use App\Models\Aseguramiento\AsEps;
use App\Models\Aseguramiento\AsSoltraslado;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class R4 extends Procesador implements ProcesadorCSVInterface
{

    public function procesar()
    {

        $gnArchivo = $this->crearGnArchivo();
        foreach ($this->data as $filaSolicitud){
            try{
                $afiliado = $this->getAfiliado($filaSolicitud[2], $filaSolicitud[3]);

                $epsSolicitante = AsEps::where('cod_habilitacion', $filaSolicitud[1])->first();

                $datosAfiliado=$afiliado->with(['municipio.departamento','cabeza.tipo_id','parentesco'])->first();
                $municipio='';
                $departamento='';
                if ($datosAfiliado->municipio) {
                    $municipio=substr($datosAfiliado->municipio->codigo,2);
                    $departamento=$datosAfiliado->municipio->departamento->codigo;
                }
                $cabezaIdentificacion='';
                $cabezaTipoIdentificacion='';
                if($datosAfiliado->cabeza){
                    $cabezaIdentificacion=$datosAfiliado->cabeza->identificacion;
                    $cabezaTipoIdentificacion=$datosAfiliado->cabeza->tipo_id->abreviatura;
                }
                $parentescoCodigo='';
                if($datosAfiliado->parentesco){
                    $parentescoCodigo=$datosAfiliado->parentesco->codigo;
                }
                $fechaHoy=Carbon::now();
                //$this->filaValida($filaSolicitud);
                AsSoltraslado::create(
                    [
                        'as_bduaproceso_id' => $this->proceso->id,
                        'gn_archivo_id' => $gnArchivo->id,
                        'bduaserial' => $filaSolicitud[0],
                        'as_afiliado_id' => $afiliado->id,
                        'as_eps_id' => $epsSolicitante ? $epsSolicitante->id: null,
                        'fecha_solicita' => $fechaHoy->format('Y-m-d'),
                        'fecha_apropiacion' => $fechaHoy->format('Y-m-d'),
                        'cod_eps' => $epsSolicitante ? $epsSolicitante->cod_habilitacion: null,
                        'nombre1S2' => $afiliado->nombre1,
                        'nombre2S2' => $afiliado->nombre2,
                        'apellido1S2' => $afiliado->apellido1,
                        'apellido2S2' => $afiliado->apellido2,
                        'cod_departamentoS2' => $departamento,
                        'cod_municipioS2' => $municipio,
                        'tip_docu_cabfamiliaS2' => $cabezaTipoIdentificacion,
                        'identificacion_cabfamiliaS2' => $cabezaIdentificacion,
                        'parentescoS2' => $parentescoCodigo,
                        'estado' => 'Registrado'
                    ]
                );
                $this->pushMonitor('Solicitud de traslado creado con exito. ', $this->proceso, 'console-success');
            }catch (\Exception $e){
                Log::error($e->getMessage());
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
           'required',
           'required|string|size:6',
           'required|string|size:2',
           'required|string|min:3|max:16',
           'required|string|max:20',
           'max:30',
           'required|string|max:20',
           'max:30',
           'required|string|size:10',
           'required|string|size:10',
            '',
            '',
            '',
            'required|string|size:2',
            'required|string|size:3',
        ]);

        if($validator->fails()) {
            throw new \Exception('La estructura de la fila no es valida');
        }

        return true;
    }
}