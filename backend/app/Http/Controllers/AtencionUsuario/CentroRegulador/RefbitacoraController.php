<?php

namespace App\Http\Controllers\AtencionUsuario\CentroRegulador;

use App\Http\Repositories\AtencionUsuario\RefbitacoraRepository;
//use App\Http\Resources\AtencionUsuario\CentroRegulador\RefpresentacioneResource;
use App\Models\CentroRegulador\AuRefbitacora;
use App\Models\CentroRegulador\AuReferencia;
use App\Models\CentroRegulador\AuReftipaccione;
use App\Traits\ArchivoTrait;
use Carbon\Carbon;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class RefbitacoraController extends Controller
{
    protected $repoBitacora;

    public function __construct(RefbitacoraRepository $repoBitacora)
    {
        $this->repoBitacora = $repoBitacora;
    }

    public function index()
    {
        return Resource::collection(AuRefbitacora::with('presentacion.entidad','referencia','traslado.entidadOrigen','traslado.entidadDestino','traslado.entidadTransportadora','accion','archivo')->orderBy('updated_at','desc')->get());
    }

    public function store(Request $request) {
        try {
            DB::beginTransaction();
            $bitacoraRequest = $request->toArray();
            $presentacion = null;
            $traslado = null;
            $bitacora = AuRefbitacora::create($bitacoraRequest);
            if (isset($bitacoraRequest['au_tipaccion_id']) && ($bitacoraRequest['au_tipaccion_id'] !== "7")) {
                $metodoDelTipo = AuReftipaccione::findOrFail($bitacoraRequest['au_tipaccion_id'])->metodo;
                $this->$metodoDelTipo($bitacoraRequest,$bitacora);
            }
            if ($bitacoraRequest['au_tipaccion_id'] === 3
                || $bitacoraRequest['au_tipaccion_id'] === 5
                || $bitacoraRequest['au_tipaccion_id'] === 6
                || $bitacoraRequest['au_tipaccion_id'] === 7
                || $bitacoraRequest['au_tipaccion_id'] === 10
                || $bitacoraRequest['au_tipaccion_id'] === 12
                || $bitacoraRequest['au_tipaccion_id'] === 13
                || $bitacoraRequest['au_tipaccion_id'] === 15
                || $bitacoraRequest['au_tipaccion_id'] === 16
                || $bitacoraRequest['au_tipaccion_id'] === 17
                || $bitacoraRequest['au_tipaccion_id'] === 18
                || $bitacoraRequest['au_tipaccion_id'] === 19
                || $bitacoraRequest['au_tipaccion_id'] === 20) {
                $bitacora->fecha = Carbon::now()->toDateTimeString();
            }
            if ($bitacoraRequest['au_tipaccion_id'] === 10
            || $bitacoraRequest['au_tipaccion_id'] === 6) {
                $this->actualizarBitacora($bitacoraRequest);
            }
            if ($bitacoraRequest['au_tipaccion_id'] === "7" && isset($bitacoraRequest['archivo'])) {
                $archive = ArchivoTrait::subirArchivo($bitacoraRequest['archivo'], 'AtencionUsuario/CentroRegulador/Bitacora');
                $bitacora->gn_archivo_id = $archive->id;
            }
//            dd($bitacora);
            $bitacora->save();
            $bitacora->load('presentacion.entidad','traslado.entidadOrigen','traslado.entidadDestino','traslado.entidadTransportadora','accion','archivo');
            DB::commit();
            return new Resource($bitacora);
        } catch (ValidationException $validationException){
            return $validationException;
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function show(AuRefbitacora $au_refbitacora) {

        return new Resource($au_refbitacora->load('presentacion.entidad','referencia','traslado.entidadOrigen','traslado.entidadDestino','traslado.entidadTransportadora','accion','archivo'));
    }

    public function findBitacorasId ($id_referencia) {
        try {
            $bitacoraRef = AuRefbitacora::with('presentacion.entidad','referencia','traslado.entidadOrigen','traslado.entidadDestino','traslado.entidadTransportadora','accion','archivo')->where('au_referencia_id', '=', $id_referencia)->orderBy('created_at','desc')->get();
            return new Resource($bitacoraRef);
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error en la carga de los registros.',
                'error' => $e
            ], 500);
        }
    }

    /**
     * @param $bitacoraRequest
     * @param $bitacora
     * @throws \Exception
     */
    public function actualizarEstadopresentacion($bitacoraRequest, $bitacora)
    {
        try{
            if (isset($bitacoraRequest['presentacion'])) {
                $this->repoBitacora->validar($bitacoraRequest['presentacion']);
                $presentacion = $this->repoBitacora->guardar($bitacoraRequest);
                $bitacora->au_refpresentacion_id = !$presentacion ? null : $presentacion->id;
//                if ($bitacoraRequest['au_tipaccion_id'] === 6) {
//                    $this->actualizarBitacora($bitacoraRequest);
//                }
            }
        } catch (ValidationException $validationException){
            throw $validationException;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param $bitacoraRequest
     * @param $bitacora
     * @throws \Exception
     */
    public function solicitudTraslado($bitacoraRequest, $bitacora) {
        try {
            if (isset($bitacoraRequest['traslado'])) {
                $this->repoBitacora->validarTraslado($bitacoraRequest['traslado']);
                $traslado = $this->repoBitacora->createTraslado($bitacoraRequest['traslado']);
                $bitacora->au_reftraslado_id = !$traslado ? null : $traslado->id;
                if (isset($bitacoraRequest['presentacion']['id'])) {
                    $bitacora->au_refpresentacion_id = $bitacoraRequest['presentacion']['id'];
                }
//                if ($bitacoraRequest['au_tipaccion_id'] === 10) {
//                    $this->actualizarBitacora($bitacoraRequest);
//                }
                if ($bitacoraRequest['au_tipaccion_id'] === 9) {
                    $bitacoraRequest['presentacion']['fecha_llegada'] = !$traslado ? null : $traslado->fecha_llegada;
                    $this->actualizarEstadopresentacion($bitacoraRequest, $bitacora);
                }
                if ($bitacoraRequest['au_tipaccion_id'] === 9
                    || $bitacoraRequest['au_tipaccion_id'] === 12) {
                    $this->actualEntidadEgresoReferencia($bitacoraRequest);
                }
                if ($bitacoraRequest['au_tipaccion_id'] === 8){
                    $bitacoraRequest['presentacion']['fecha_salida'] = !$traslado ? null : $traslado->fecha_traslado;
                    $this->actualizarEstadopresentacion($bitacoraRequest, $bitacora);
                }
            }
        } catch (ValidationException $validationException) {
            throw $validationException;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
    /**
     * @param $bitacoraRequest
     * @param $bitacora
     * @throws \Exception
     */
    public function cancelarProceso ($bitacoraRequest, $bitacora) {
        try {
            $idPre = $bitacoraRequest['presentacion']['id'];
            $idTras = $bitacoraRequest['traslado']['id'];
            if (is_null($idPre) && is_null($idTras)) {
                $this->repoBitacora->actualizarEstadoReferencia($bitacora);
            }
//            if (isset($bitacoraRequest['traslado'])) {
            if (isset($idTras)) {
                $bitacora->au_reftraslado_id = $bitacoraRequest['traslado']['id'];
                $this->solicitudTraslado($bitacoraRequest, $bitacora);
            }
//            if (isset($bitacoraRequest['presentacion'])) {
            if (isset($idPre)) {
                $bitacora->au_refpresentacion_id = $bitacoraRequest['presentacion']['id'];
                $this->actualizarEstadopresentacion($bitacoraRequest, $bitacora);
            }
        } catch (ValidationException $validationException) {
            throw $validationException;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function actualEntidadEgresoReferencia ($bitacoraRequest) {
        $id = $bitacoraRequest['au_referencia_id'];
        $referencia = AuReferencia::find($id);
        $referencia->entidad_egreso_id = $bitacoraRequest['traslado']['entidad_destino_id'];
        $referencia->save();
    }

    public function actualizarBitacora ($bitacoraRequest) {
        $actualizarRef = AuRefbitacora::where('au_tipaccion_id', '=' , 5)
            ->where('au_refpresentacion_id', $bitacoraRequest['presentacion']['id'])->first();
        if ($actualizarRef->au_refpresentacion_id === $bitacoraRequest['presentacion']['id']) {
            $id = $actualizarRef->id;
            $bitacora = AuRefbitacora::find($id);
            $bitacora->au_tipaccion_id = 11;
            $bitacora->save();
            $bitacora->load('presentacion.entidad','traslado.entidadOrigen','traslado.entidadDestino','traslado.entidadTransportadora','accion','archivo');
        }
    }

}
