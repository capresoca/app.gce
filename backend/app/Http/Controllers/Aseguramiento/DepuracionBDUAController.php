<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Http\Repositories\Aseguramiento\NovtramiteRepository;
use App\Http\Requests\Aseguramiento\DepuracionRequest;
use App\Http\Resources\Aserguramiento\DepuracionResource;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsNovtramite;
use App\Models\Aseguramiento\AsTipnovedade;
use App\Models\Aseguramiento\BDUA\LogNsDetalle;
use App\Models\Aseguramiento\BDUA\TbPeriodoLiquidacion;
use App\Models\Aseguramiento\BDUA\TbVigenciaTraslado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Jenssegers\Date\Date;
use function Matrix\trace;

/**
 * @author Jorge Hernández 16/004/2020
 * Creando Soluciones Informaticas Ltda
 */


class DepuracionBDUAController extends Controller
{

    protected $novedadTramite;

    public function __construct(NovtramiteRepository $repository)
    {
        $this->novedadTramite = $repository;
    }

    public function index()
    {
        if (Input::get('per_page')) {
            return DepuracionResource::collection(
                AsNovtramite::pimp()
                    ->where('estado_proceso', 2)
                    ->paginate(Input::get('per_page'))
            );
        }
        return DepuracionResource::collection(AsNovtramite::pimp()
            ->where('estado_proceso', 2)
            ->orderBy('updated_at', 'desc')->get());

    }


    public function store(DepuracionRequest $request)
    {
        try {
            $id_novedadAnterior = Input::get('id_novedadAnterior');

            $logDetelle = LogNsDetalle::with('cabecera')->where('consecutivo_ns', $id_novedadAnterior)->first();

            $today = Date::now()->format('Y-m-d');
            $logVigente = TbVigenciaTraslado::where('consecutivo_vigencia',$logDetelle->cabecera['consecutivo_vigencia'])->first();

            $fecha_inicio = Carbon::parse($logVigente['fecha_inicio'])->format('Y-m-d');
            $fecha_fin = Carbon::parse($logVigente['fecha_fin'])->format('Y-m-d');
            $fecha_novedad = Carbon::parse($logVigente['fecha_minima_novedad'])->format('Y-m-d');

            if (!$this->check_in_range($fecha_inicio, $fecha_fin, $today)) {
                throw new \Exception("No es posible realizar la actualización la fecha de hoy $today se encuentra fuera del rango de vigencia ($fecha_inicio a $fecha_fin)",422);
            }

            if ($today > $fecha_novedad) {
                throw new \Exception("No es posible realizar la actualización ya que la fecha minima de novedad $fecha_novedad, ya es vencida.",422);
            }

            $novedad = $this->novedadTramite->guardar($request, $id_novedadAnterior);

            //Actualización
            $findNovedad = AsNovtramite::find($novedad['id']);
            $findNovedad->estado_proceso = 1;
            $findNovedad->consecutivo_ns_procesada = $id_novedadAnterior;
            $findNovedad->save();

            $novedadAnterior = AsNovtramite::find($id_novedadAnterior);
            $novedadAnterior->estado_proceso = 4;
            $novedadAnterior->save();

            return response()->json([
                'data' => collect($novedad)
            ], 201);

        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
                'message' => $exception->getMessage()
            ],500);
        }
    }


    public function show($id)
    {
        try {

            $log = LogNsDetalle::whereConsecutivoNs($id)->first();

            if (! $log) {
                throw new \Exception('No se encuentra ningún registro',400);
            }

            return response()->json([
                'data' => [
                    'log' => collect($log)
                ]
            ],200);

        } catch (\Exception $exception) {
            return response()->json([
                'errors' =>  $exception->getMessage()
            ],500);
        }
    }

    public function update(Request $request, $id){}

    public function destroy($id){
        try {
            $novtramite = AsNovtramite::find($id);
            $novtramite->estado_proceso = 3;
            $novtramite->save();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function validarSiNegAbierto (AsNovtramite $novtramite) {
        try {
            if (! is_null($novtramite->consecutivo_vigencia)) {
                $vigencia = TbVigenciaTraslado::where('consecutivo_vigencia',$novtramite->consecutivo_vigencia)->first();

                $liquidacion = TbPeriodoLiquidacion::where('consecutivo_liquidacion', $vigencia['consecutivo_liquidacion'])->first();

                if (isset($liquidacion) && $liquidacion->estado === 1) {
                    throw new \Exception('La información del afiliado debe ser asegurada durante 
                    el mismo periodo liquidación, el actual no le corresponde, no es posible realizar su operación.',422);
                }
            }

            $novedad = $novtramite->load('novedad', 'municipio', 'afiliado', 'tipo_id');

            return response()->json([
                'data' => collect($novedad)
            ],200);

        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception->getMessage()
            ],500);
        }
    }

    public function camposNovedad ($codigo) {

        $tipoNovedad = AsTipnovedade::with('campos')->where('codigo', $codigo)->first();

        return response()->json([
            'data' => collect($tipoNovedad)
        ],200);
    }

    private function check_in_range ($fecha_inicio, $fecha_fin, $fecha) {
        if (($fecha >= $fecha_inicio) && ($fecha <= $fecha_fin)) {
            return true;
        } else {
            return false;
        }
    }
}
