<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Http\Resources\Aseguramiento\DepuracionBDUAResource;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\BDUA\AfAfiliadoMs;
use App\Models\Aseguramiento\BDUA\LogMsDetalle;
use App\Models\Aseguramiento\BDUA\TbVigenciaTraslado;
use App\Models\Niif\GnTercero;
use App\Models\RedServicios\RsEntidade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Jenssegers\Date\Date;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Log;

/**
 * @author Jorge Hernández 16/004/2020
 * Creando Soluciones Informaticas Ltda
 */


class DepuracionesBDUAController extends Controller
{

    public function index()
    {
       if (Input::get('per_page')) {
            return DepuracionBDUAResource::collection(
                AfAfiliadoMs::with('afiliado')->pimp()
                    ->where('estado_procesado', 2)
                    ->paginate(Input::get('per_page'))
            );
        }
        return DepuracionBDUAResource::collection(
            AfAfiliadoMs::with('afiliado')->pimp()
                ->where('estado_procesado', 2)
                ->orderBy('updated_at', 'desc')->get());
        
       /* if (Input::get('per_page')) {
            $vigencia = TbVigenciaTraslado::pimp()->with('periodoLiquidacion')->orderBy('fecha_inicio', 'desc')->paginate(Input::get('per_page'));
            return Resource::collection($vigencia);
        }
        return Resource::collection(TbVigenciaTraslado::pimp()->orderBy('fecha_inicio', 'desc')->get());*/
        
    }

    public function store(Request $request)
    {
        try {
            $id_noconsecutivo = Input::get('no_afafiliado_id');

            $logDetelle = LogMsDetalle::with('cabecera')->where('consecutivo_ns', $id_noconsecutivo)->first();

            $today = Date::now()->format('Y-m-d');
            $logVigente = TbVigenciaTraslado::where('consecutivo_vigencia',$logDetelle->cabecera['consecutivo_vigencia'])->first();

            $fecha_inicio = Carbon::parse($logVigente['fecha_inicio'])->format('Y-m-d');
            $fecha_fin = Carbon::parse($logVigente['fecha_fin'])->format('Y-m-d');
            $fecha_afiliacion = Carbon::parse($logVigente['fecha_minima_afiliacion'])->format('Y-m-d');

            if (!$this->check_in_range($fecha_inicio, $fecha_fin, $today)) {
                throw new \Exception("No es posible realizar la actualización la fecha de hoy $today se encuentra fuera del rango de vigencia ($fecha_inicio a $fecha_fin)",422);
            }

            if ($today > $fecha_afiliacion) {
                throw new \Exception("No es posible realizar la actualización ya que la fecha minima de novedad $fecha_afiliacion, ya es vencida.",422);
            }

            //Actualización
            $findBDUA = AfAfiliadoMs::where('consecutivo_ns',$id_noconsecutivo)->first();
            $findBDUA->estado_procesado = 4;
            $findBDUA->save();

            $afiliado = AsAfiliado::with('relaciones_laborales','aportantes','cabeza.tipo_id','tipo_id','sexo','municipio','zona','poblacion_especial')->find($request->id);
            $afiliado->nombre1 = $request->nombre1;
            $afiliado->nombre2 = $request->nombre2;
            $afiliado->apellido1 = $request->apellido1;
            $afiliado->apellido2 = $request->apellido2;
            $afiliado->gn_municipio_id = $request->gn_municipio_id;
            $afiliado->gn_zona_id = $request->gn_zona_id;
            $afiliado->fecha_afiliacion = $request->fecha_afiliacion;
            $afiliado->gn_sexo_id = $request->gn_sexo_id;
            $afiliado->rs_entidade_id = $request->rs_entidade_id;
            $afiliado->save();

            $this->createLog($afiliado, $findBDUA, $logDetelle);

//            $novedadAnterior = AsNovtramite::find($id_novedadAnterior);
//            $novedadAnterior->estado_proceso = 4;
//            $novedadAnterior->save();
            return response()->json([
                'data' => collect($logDetelle)
            ], 201);

        } catch (\Exception $exception) {
            return response()->json([
                'errors' =>  $exception->getMessage()
            ],500);
        }
    }

    public function show($id)
    {
        try {
            Log::info('id lelga: '.$id);
            $log = LogMsDetalle::where('consecutivo_ms',$id)->first();

            $afiliado = AfAfiliadoMs::with('afiliado')->where('consecutivo_ns', $id)->first();

            if (! $log) {
                throw new \Exception('No se encuentra ningún registro',400);
            }

            return response()->json([
                'data' => [
                    'log' => collect($log),
                    'afiliado' => isset($afiliado) ? collect($afiliado) : null
                ]
            ],200);

        } catch (\Exception $exception) {
            return response()->json([
                'errors' =>  $exception->getMessage()
            ],500);
        }
    }

    public function update(Request $request, $id){}

    public function destroy($id){}

    public function eliminarBDUA (Request $request, $no_consecutivoaf) {
        try {

            $afafiliado = AfAfiliadoMs::where('consecutivo_ns',$no_consecutivoaf)->first();
            $afafiliado->estado_procesado = 3;
            $afafiliado->save();


        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception->getMessage()
            ],500);
        }
    }

    public function createLog ($data, $datams, $vigencia) {
        try {
            $entidad = RsEntidade::find($data->rs_entidade_id);
            $tipoIdenPadre = $data->cabeza['tipo_id']['abreviatura'];
            $identPadre = $data->cabeza['identificacion'];

            $relaciones = $data->relaciones_laborales;

            $aportante = null;
            foreach ($relaciones as $key => $relacione) {
                if ($relacione->estado === 'Activo') {
                    $aportante = $relacione;
                }
            }

            $tipoIdentificacion = isset($aportante) ? GnTercero::with('tipo_id')->find($aportante['pagador']['gn_tercero_id'])->tipo_id['abreviatura'] : null;

            $resource = [
                'codigo_entidad'                    => $entidad->cod_habilitacion,
                'tipo_identificacion_padre'         => $tipoIdenPadre,
                'numero_identificacion_padre'       => $identPadre,
                'tipo_identificacion'               => $data->tipo_id['abreviatura'],
                'numero_identificacion'             => $data->identificacion,
                'primer_apellido'                   => $data->apellido1,
                'segundo_apellido'                  => $data->apellido2,
                'primer_nombre'                     => $data->nombre1,
                'segundo_nombre'                    => $data->nombre2,
                'fecha_nacimiento'                  => $data->fecha_nacimiento,
                'genero'                            => $data->sexo['abreviatura'],
                'departamento'                      => $data->municipio['departamento']['id'],
                'municipio'                         => $data->municipio['id'],
                'zona_afiliacion'                   => $data->zona['nombre'],
                'fecha_afiliacion'                  => $data->fecha_afiliacion,
                'tipo_poblacion'                    => $data->poblacion_especial['id'] ?? null,
                'nivel_sisben'                      => $data->nivel_sisben,
                'modalidad_subsidio'                => null,
                'consecutivo_afiliado'              => $data->id,
                'usuario_grabado'                   => Auth::user()->name,
                'fecha_grabado'                     => Carbon::now()->toDateTimeString(),
                'municipio_actual'                  => $data->municipio['codigo'],
                'sw_nacimiento'                     => $data->es_nacimiento,
                'fecha_ms'                          => $datams->fecha_ms,
                'consecutivo_vigencia'              => $vigencia->cabecera['consecutivo_vigencia'],
                'consecutivo_log_ms'                => $vigencia->consecutivo_log_ms,
                'consecutivo_ms_procesada'          => $datams->consecutivo_ns,
                'estado_procesado'                  => 1,
                'condicion_discapacidad'            => $data->as_condicion_discapacidades_id ?? null,
                'parentesco'                        => $data->as_parentesco_id,
                'etnia'                             => $data->as_etnia_id,
                'consecutivo_ips'                   => $data->rs_entidade_id ?? null,
                'tipo_documento_cotizante'          => $data->as_tipafiliado_id === 3 ? $tipoIdenPadre : null,
                'numero_documento_cotizante'        => $data->as_tipafiliado_id === 3 ? $identPadre : null,
                'consecutivo_afiliado_cotizante'    => $data->as_tipafiliado_id === 3 ? $data->cabeza['id'] : null,
                'tipo_cotizante'                    => $data->as_clasecotizante_id,
                'tipo_afiliado'                     => $data->as_tipafiliado_id,
                'tipo_identificacion_aportante'     => isset($aportante) ? ($tipoIdentificacion ?? null) : null,
                'numero_identificacion_aportante'   => isset($aportante) ? $aportante['pagador']['identificacion'] : null,
                'consecutivo_aportante'             => isset($aportante) ? $aportante['as_pagador_id'] : null,
                'ibc'                               => isset($aportante) ? $aportante['ibc'] : null
            ];

            return AfAfiliadoMs::create((array) $resource);

        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ]);
        }
    }

    private function check_in_range ($fecha_inicio, $fecha_fin, $fecha) {
        if (($fecha >= $fecha_inicio) && ($fecha <= $fecha_fin)) {
            return true;
        } else {
            return false;
        }
    }
}
