<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Aseguramiento\AfiliadoRepository;
use App\Http\Requests\Aseguramiento\AfiliadoRequest;
use App\Http\Resources\Aseguramiento\AsAfiliadoResource;
use App\Http\Resources\Aseguramiento\AsAfiliadosResource;
use App\Jobs\CargarAfiliadoHM;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsConsecutivoCerelectronicoAfiliado;
use App\Models\RedServicios\RsAfiliadoServicio;
use App\Models\RedServicios\RsAfiservicambio;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Models\Aseguramiento\BDUA\AfAfiliadoMs;
use App\Models\TbVigenciaTraslado;
use App\Models\Enums\ESiNo;
use App\Models\Enums\ETipoNovedad;
use Illuminate\Support\Facades\Log;
use App\Models\General\TbNomenclatura;
use App\Models\Aseguramiento\BDUA\LogMsDetalle;
use App\Http\Resources\Aseguramiento\LogMsDetalleResource;

class AfiliadoController extends Controller
{
    protected $repoAfiliado;

    public function __construct(AfiliadoRepository $repoAfiliado)
    {
        $this->repoAfiliado = $repoAfiliado;
        //PermiteTrait::checkPermisos($this,11);
    }

    public function index()
    {

        if (Input::get('per_page')) {
            // \Log::debug(print_r(AsAfiliadosResource::collection(AsAfiliado::with('regimen')->pimp()->orderBy('id', 'desc')->paginate(Input::get('per_page'))), true));
            return AsAfiliadosResource::collection(AsAfiliado::with('regimen')->pimp()->orderBy('id', 'desc')->paginate(Input::get('per_page')));
        }

        return AsAfiliadoResource::collection(AsAfiliado::with('cabeza', 'aportantes.tercero', 'ips.tercero', 'zona', 'regimen')->pimp()->limit(100)->orderBy('updated_at', 'desc')->get());
    }

    public function store(AfiliadoRequest $request)
    {
        //Log::info('Entra hasta aqui');
        $afiliado       = NULL;
        DB::beginTransaction();
        try {
            
            $date = date('Y-m-d');
            $vigencia = TbVigenciaTraslado::where('fecha_inicio', '<=', $date)->where('fecha_fin', '>=', $date)
                ->where('sw_abierto', '=', ESiNo::getIndice(ESiNo::SI)->getId())
                ->where('tipo', '=', ETipoNovedad::getIndice(ETipoNovedad::TRASLADO)->getId())->first();

            if (is_null($vigencia) || empty($vigencia)) {
                return response()->json([
                    'message' => 'No existe vigencia para crear el afiliado',
                    'errors' => 'No existe vigencia para crear el afiliado',
                ], 500);
            }
            //Log::info('Fecha afiliado controller afiliacion: ', array($request->fecha_afiliacion));
            
            
            $nm = TbNomenclatura::find($request->nomenclatura);
            
            //Log::info('array completo: ', array($request));
            
            $requestCopy = $request->toArray();
            $requestCopy['direccion']           = $nm->abreviatura.' '.$request->direccion;
            //Log::info('Afiliacion 1: ', array($requestCopy['fecha_afiliacion']));
            $requestCopy['fecha_afiliacion']    = $this->formDate($requestCopy['fecha_afiliacion']);
            $requestCopy['fecha_nacimiento']    = $this->formDate($requestCopy['fecha_nacimiento']);
            $requestCopy['fecha_expedicion']    = $this->formDate($requestCopy['fecha_expedicion']);
            //Log::info('Afiliacion 2: ', array($requestCopy['fecha_afiliacion']));
            $afiliado                   = AsAfiliado::create($requestCopy);
            //Log::info('Datos Afiliados: ', array($afiliado));
            $afiliado->nivel_sisben     = $request->nivel_sisben;
            $afiliado->save();

            $temp = AsAfiliado::find($afiliado->id);
            $temp->estado_traslado = 'S1';
            $temp->save();

            $afAfiliado = new AfAfiliadoMs();

            //$afAfiliado->codigo_entidad           = ;
            //$afAfiliado->tipo_identificacion_padre           = ;
            //$afAfiliado->numero_identificacion_padre           = ;
            $afAfiliado->tipo_identificacion = $afiliado->tipo_identificacion;
            $afAfiliado->numero_identificacion = $afiliado->identificacion;
            $afAfiliado->primer_apellido = $afiliado->apellido1;
            $afAfiliado->segundo_apellido = $afiliado->apellido2;
            $afAfiliado->primer_nombre = $afiliado->nombre1;
            $afAfiliado->segundo_nombre = $afiliado->nombre2;
            $afAfiliado->fecha_nacimiento = $afiliado->fecha_nacimiento;
            $afAfiliado->genero = $afiliado->gn_sexo_id;
            $afAfiliado->consecutivo_vigencia = $vigencia['consecutivo_vigencia'];
            $afAfiliado->consecutivo_afiliado = $afiliado->id;
            //$afAfiliado->departamento           = ;
            $afAfiliado->municipio = $afiliado->gn_municipio_id;
            $afAfiliado->zona_afiliacion = $afiliado->gn_zona_id;
            $afAfiliado->fecha_afiliacion = $afiliado->fecha_afiliacion;
            $afAfiliado->tipo_poblacion = $afiliado->as_pobespeciale_id;
            $afAfiliado->tipo_traslado = "S";
            $afAfiliado->estado_procesado = 0;
            
            $afAfiliado->save();

            DB::commit();

            return response(new AsAfiliadosResource($afiliado));
        } catch (\Exception $e) {
            //Log::info('Errores: ', array($e));
            DB::rollBack();
            if($afiliado!=null) {
                $afiliado->delete();
            }
            return response()->json([
                'message' => 'Se produjo un error no manejado: ' . $e->getMessage(),
                'errors' => 'Se produjo un error no manejado',
            ], 500);
        }
    }

    public function show(AsAfiliado $afiliado)
    {
        //$afiliado       = AsAfiliado::find($afiliado->id);
        
        //Log::info('NIvel sisben: ', array($afiliado->id));
        $afiliado->load([
            'tipo_afiliado',
            'vereda',
            'aportantes.tercero',
            'barrio',
            'beneficiarios_activos',
            'arl',
            'afp',
            'ips',
            'ips.tercero',
            'ips.tipo',
            'ips.municipio',
            'cabeza',
            'beneficiarios.parentesco',
            'aportantes',
            'regimen',
            'parentesco',
            'condicion_discapacidad',
            'poblacion_especial',
            'zona',
            'zona_dnp',
            'ciiu',
            'clase_cotizante',
            'municipio',
            'relaciones_laborales.pagador',
            'servicios_contratados.servicio',
            'servicios_portabilidad.servicio',
            'servicios_portabilidad.contrato.contrato.entidad',

        ])->append('servicios_habilitados');

        if (Input::get('copago_anual')) {
            $afiliado->append('copago_anual');
        }
        //Log::info('IPS: ', array($afiliado->ips));
        //Log::info('Afiliado retorna: ',$afiliado);
        return new AsAfiliadoResource($afiliado);
    }

    public function update(AfiliadoRequest $request, AsAfiliado $afiliado)
    {
        Log::info('Entrando al update afiliado');
        $requestCopy = $request->toArray();
        Log::info('fecha viene: '.$requestCopy['fecha_nacimiento']);
        $requestCopy['fecha_nacimiento'] = $this->formDate($requestCopy['fecha_nacimiento']);
        $requestCopy['fecha_afiliacion'] = $this->formDate($requestCopy['fecha_afiliacion']);
        $requestCopy['fecha_expedicion'] = $this->formDate($requestCopy['fecha_expedicion']);

        $temp = $afiliado->as_regimene_id;
        if ($afiliado->as_tipafiliado_id == 1 || $afiliado->as_tipafiliado_id == 2) {
            if ($requestCopy['as_regimene_id'] == 1) {
                $requestCopy['as_tipafiliado_id'] = 1;
            }
            if ($requestCopy['as_regimene_id'] == 2) {
                $requestCopy['as_tipafiliado_id'] = 2;
            }
        }

        Log::info('artributos afiliados: ',array($requestCopy));
        
        $afiliado->update($requestCopy);
        if ($requestCopy['as_regimene_id'] !== $temp) {
            $this->actualizarRegimenBeneficiarios($afiliado);
        }

        return response(new AsAfiliadosResource($afiliado))->setStatusCode(202);
    }

    public function formDate(string $date)
    {
        if (!$date) return null;

        $dateSplit = explode('/', $date);
        return $dateSplit[2] . '-' . $dateSplit[1] . '-' . $dateSplit[0];
    }

    public function incidencias()
    {
        try {
            return response()->json([
                'data' => [
                    'fallecidos' => $this->fallecidos(),
                    'inexistentesReportadosPorConcurrencias' => $this->inexistentesReportadosPorConcurrencias(),
                ],
            ]);
        } catch (\Exception $ex) {
            if ($ex->getCode() == 0) {
                abort(500, 'Error desconocido, contacte a soporte');
            }

            abort($ex->getCode(), $ex->getMessage());
        }
    }

    private function inexistentesReportadosPorConcurrencias()
    {
        $consulta = "
                SELECT  a.identificacion,
                        a.nombre_paciente,
                        d.nombre AS ips_hospitalizado,
                        c.fecha AS fecha_hospitalizacion,
                        a.cama_servicio,
                        format(((UNIX_TIMESTAMP( (CURDATE()) )-UNIX_TIMESTAMP(c.fecha))/(60*60*24)),0) AS 'dias_reportado'
                FROM cm_pacientes_hospitalarios AS a
                LEFT JOIN cm_censos AS c ON c.id = a.cm_censo_id
                LEFT JOIN rs_entidades AS d ON d.id = c.ips_id
                LEFT JOIN as_afiliados AS b ON b.id = a.as_afiliado_id
                WHERE b.id IS NULL
                GROUP BY a.identificacion
            ";

        $resultado = collect(DB::select($consulta))->sortByDesc('dias_reportado')->values()->all();

        return $resultado;
    }

    private function fallecidos()
    {
        $consulta = "
                SELECT c.tipo_identificacion,
                         c.identificacion,
                         CONCAT_WS(' ', c.nombre1, ifnull(c.nombre2,''), c.apellido1, ifnull(c.apellido2,'')) AS nombre_completo,
                         c.fecha_nacimiento,
                         a.fecha_egreso AS fecha_reporte,
                         'Concurrencia' AS Fuente,
                         format(((UNIX_TIMESTAMP( (CURDATE()) )-UNIX_TIMESTAMP(b.fecha))/(60*60*24)),0) AS 'dias_reportado'
                FROM  cm_conegresos AS a
                LEFT JOIN cm_concurrencias AS b ON b.id = a.cm_concurrencia_id
                LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
                WHERE a.estado_salida = 'Muerto' AND c.estado != 8
                GROUP BY c.id
                UNION
                SELECT c.tipo_identificacion,
                         c.identificacion,
                         CONCAT_WS(' ', c.nombre1, ifnull(c.nombre2,''), c.apellido1, ifnull(c.apellido2,'')) AS nombre_completo,
                         c.fecha_nacimiento,
                         a.fecha_egreso AS fecha_reporte,
                         'Centro Regulador' AS Fuente,
                         format(((UNIX_TIMESTAMP( (CURDATE()) )-UNIX_TIMESTAMP(a.fecha_solicitud))/(60*60*24)),0) AS 'dias_reportado'
                FROM au_referencias AS a
                LEFT JOIN as_afiliados AS c ON  c.id = a.as_afiliado_id
                WHERE a.estado = 'Fallecida'  AND c.estado != 8
                GROUP BY c.id        
            ";

        $resultado = collect(DB::select($consulta))->sortByDesc('dias_reportado')->values()->all();

        return $resultado;
    }

    public function cargarHM(Request $request)
    {
        $path_storage = $request->file('archivo_afiliados')->storeAs('afiliados_hm', $request->file('archivo_afiliados')->getClientOriginalName(), 'local');

        $app_path = str_replace('\\', '/', storage_path());
        $path = $app_path . '/app/' . $path_storage;
        CargarAfiliadoHM::dispatch($path)->onQueue('archivos');

        return response()->json()->setStatusCode(201);
    }

    public function syncServicios(Request $request, AsAfiliado $afiliado)
    {
//         Para la red de servicios se reemplaza por el siguiente metodo (asignarServicios)
//        $afiliado->servicios_contratados()->sync($request->servicios_contratados);
//        $servicios_afiliado = $afiliado->servicios_contratados->load('contrato.contrato.entidad');
        $afiliado->servicios_habilitados()->sync($request->servicios_habilitados);
        $servicios_afiliado = $afiliado->servicios_habilitados->load('entidad');

        return response(new Resource($servicios_afiliado))->setStatusCode(202);
    }

    public function asignarServicios(Request $request, AsAfiliado $afiliado)
    {
        foreach ($request->servicios_habilitados as $key => $servicio) {
            $idSerPrimario = (integer)$servicio['idServicio'];
            $idSerhabilitado = isset($servicio['idEntidad']) ? ((integer)$servicio['idEntidad']) : null;
            if ($afiliado->servicios_habilitados()->count() >= 1) {
                $servEntidad = RsAfiliadoServicio::where('as_afiliado_id', $afiliado->id);
                if (isset($afiliado->portabilidad_activa)) {
                    $afiServicio = $servEntidad->whereHas('servicio_portabilidad', function ($query) use ($idSerPrimario
                    ) {
                        $query->where('rs_servicio_id', $idSerPrimario);
                    })->first();
                } else {
                    $afiServicio = $servEntidad->whereHas('servicio_habilitado', function ($query) use ($idSerPrimario
                    ) {
                        $query->where('rs_servicio_id', $idSerPrimario);
                    })->first();
                }
                if (isset($afiServicio)) {
                    $this->registrarServicioAnterior($afiliado, $afiServicio);
                }
                $this->conDeRegistro($afiliado, $afiServicio, $idSerhabilitado, isset($afiliado->portabilidad_activa) ? 2 : 1);
            } else {
                $this->crearAfiliadoServicio($afiliado, $idSerhabilitado, 1);
            }
        }
        $servicios_afiliado = $afiliado->servicios_habilitados;

        return response(new Resource($servicios_afiliado))->setStatusCode(202);
    }

    public function getCertificadoAfiliacion(Request $request)
    {
        try {
            $id = Input::get('id');
            $user = User::find(Input::get('user'));
            $view = '';
            $afiliado = '';
            if (Input::get('tipo')) {
                if (Input::get('tipo') == '1') {
                    $view = 'dompdf.certificado_afiliacion_solo';
                    $afiliado = AsAfiliado::whereId($id)->with([
                        'estado_afiliado',
                        'regimen',
                    ])->first();
                } else {
                    $view = 'dompdf.certificado_afiliacion';
                    $afiliado = AsAfiliado::whereId($id)->with([
                        'cabeza.parentesco',
                        'beneficiarios',
                        'relaciones_laborales.pagador',
                        'estado_afiliado',
                        'regimen',
                    ])->first();
                }
            }

            if (Input::get('html')) {
                return view($view);
            }
            setlocale(LC_ALL, "es_ES");
            //Auth::login($user);
            //dd($user->name);
            //return view('dompdf.certificado_afiliacion', ['afiliado' => $afiliado,'fecha' => strftime('%d de %B del %Y', strtotime(date('Y-m-d') )), 'usuario' => Auth::user()->name]);
            $pdf = PDF::loadView($view, [
                'afiliado' => $afiliado,
                'fecha' => strftime('%d de %B del %Y', strtotime(date('Y-m-d'))),
                'usuario' => $user->name,
            ]);
            $pdf->setPaper('letter', 'portrait');

            return $pdf->stream('Certificado Afiliacion ' . $afiliado->identificacion . '.pdf', ['Attachment' => 0]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getCertificadoRed()
    {
        try {
            $id = Input::get('id');
            $user = User::find(Input::get('user'));
            $afiliado = AsAfiliado::find($id);
            $view = 'dompdf.aseguramiento.certificado_red_servicios';
            if (Input::get('html')) {
                return view($view, [
                    'afiliado' => $afiliado,
                    'fecha' => strftime('%d de %B del %Y', strtotime(date('Y-m-d'))),
                    'usuario' => $user->name,
                ]);
            }
            setlocale(LC_ALL, "es_ES");

            $pdf = PDF::loadView($view, [
                'afiliado' => $afiliado,
                'fecha' => strftime('%d de %B del %Y', strtotime(date('Y-m-d'))),
                'usuario' => $user->name,
            ]);
            $pdf->setPaper('letter', 'portrait');

            return $pdf->stream('Certificado Red Servicios ' . $afiliado->identificacion . '.pdf', ['Attachment' => 0]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getCertificadoElectronico()
    {
        try {
            $id = Input::get('id');
            $user = User::find(Input::get('user'));
            $view = 'dompdf.certificacio_electronica_afiliado';
            $afiliado = AsAfiliado::whereId($id)->with([
                'cabeza.parentesco',
                'beneficiarios',
                'relaciones_laborales.pagador',
                'estado_afiliado',
                'regimen',
                'tipo_afiliado'
            ])->first();

            $fechaHoy = Carbon::now()->toDateString();
            $existe = AsConsecutivoCerelectronicoAfiliado::where('as_afiliado_id', $id)
                ->latest('id')->first();
            $fecha = Carbon::parse($existe['created_at'])->format('Y-m-d');
            if ($fechaHoy !== $fecha) {
                $consecutivo_electronico = new AsConsecutivoCerelectronicoAfiliado();
                $consecutivo_electronico->as_afiliado_id = $id;
                $consecutivo_electronico->save();
                $consecutivo_electronico->toArray();
            } else {
                $consecutivo_electronico = $existe;
            }

            if (Input::get('html')) {
                return view($view);
            }
            setlocale(LC_ALL, "es_ES");

            $pdf = PDF::loadView($view, [
                'afiliado' => $afiliado,
                'fecha' => strftime('%d de %B del %Y', strtotime(date('Y-m-d'))),
                'usuario' => $user->name,
                'consecutivo_electronico' => $consecutivo_electronico['id']
            ]);
            $pdf->setPaper('letter', 'portrait');

            return $pdf->stream('Certificado Electrónico No. Documento ' . $afiliado->identificacion . '.pdf', ['Attachment' => 0]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function registrarBeneficiario(Request $request, $id)
    {
        try {
            $afiliado = AsAfiliado::find($id);
            $afiliado->cabfamilia_id = $request->cabfamilia_id;
            $afiliado->cabfamilianterior_id = $request->cabfamilianterior_id;
            $afiliado->as_parentesco_id = $request->as_parentesco_id;
            $afiliado->save();

            $afiliado->load('cabeza.parentesco', 'beneficiarios', 'tipo_afiliado', 'parentesco');

            $asAfiliado = AsAfiliado::where('id', $request->cabfamilia_id)->get();

            $afAfiliado = AfAfiliadoMs::where('consecutivo_afiliado', $id)->get();
            if (!empty($afAfiliado)) {
                $afAfiliado[0]->tipo_identificacion_padre = $asAfiliado[0]->tipo_identificacion;
                $afAfiliado[0]->numero_identificacion_padre = $asAfiliado[0]->identificacion;
                $afAfiliado[0]->save();
            }

            return response()->json([
                'data' => new Resource($afiliado),
                'msg' => 'Registro actualizado correctamente. PROBANDO QUE ACTUALICE',
            ], 202);
        } catch (\Exception $e) {
            return response()->json([
                'data' => new Resource($e->getMessage()),
                'msg' => 'errores',
            ], 500);
            //return $e->getMessage();
        }
    }

    function actualizarRegimenBeneficiarios(AsAfiliado $afiliado)
    {
        $beneficiarios = $afiliado->beneficiarios;
        if ($beneficiarios) {
            foreach ($beneficiarios as $beneficiario) {
                $afiliado_ = AsAfiliado::find($beneficiario->id);
                $afiliado_->as_regimene_id = $afiliado->as_regimene_id;
                $afiliado_->save();
            }
        }
    }

    public function getAtencionesYServicios($id)
    {
        $data = DB::select("
            SELECT c.codigo,
             c.descrip AS Servicio,
             c.cAut,
             b.fecha,
            'Autorizacion' AS tipoServicio
            FROM as_afiliados AS a
            LEFT JOIN au_anexot3s AS b ON b.as_afiliado_id = a.id
            LEFT JOIN au_anexot31s AS c ON c.au_anexot3s_id = b.id
            LEFT JOIN rs_cie10s AS d ON d.codigo = b.dPrin
            WHERE a.id = {$id} and b.id IS NOT NULL and c.ind IN (2,3)
            GROUP BY c.id
            UNION
            SELECT b.codigo,
                   b.nombre AS Servicio,
                   b.cantidad,
                   b.fecha_servicio,
                   'Facturas' AS tipoServicio
            FROM as_afiliados AS a
            LEFT JOIN cm_facservicios AS b ON b.as_afiliado_id = a.id
            WHERE a.id = {$id}
            GROUP BY b.id
        ");

        return new Resource($data);
    }

    /**
     * @param \App\Models\Aseguramiento\AsAfiliado $afiliado
     * @param $afiservicio
     * @param $idSerEntidad
     * @param int $param
     */
    private function conDeRegistro(AsAfiliado $afiliado, $afiservicio, $idSerEntidad, int $param): void
    {
        if (isset($afiservicio) && (is_null($idSerEntidad))) {
            if (($afiservicio['rs_portabilidad_id'] === $afiliado->portabilidad_activa['id']) || (is_null($afiservicio['rs_portabilidad_id']) && isset($afiservicio['rs_servhabilitado_id']))) {
                $afiservicio->delete();
            }
        } else {
            if (isset($afiservicio) && (!is_null($idSerEntidad))) {
                if ($param === 2) {
                    if ($afiservicio['rs_portabilidad_id'] === $afiliado->portabilidad_activa['id']) {
                        $afiservicio->rs_servportabilidad_id = $idSerEntidad;
                    }
                } else {
                    $afiservicio->rs_servhabilitado_id = $idSerEntidad;
                }
                $afiservicio->save();
            } else {
                $this->crearAfiliadoServicio($afiliado, $idSerEntidad, $param);
            }
        }
    }

    /**
     * @param \App\Models\Aseguramiento\AsAfiliado $afiliado
     * @param $idSerHabilitado
     * @param int $param
     * @return mixed
     */
    private function crearAfiliadoServicio(AsAfiliado $afiliado, $idSerHabilitado, int $param)
    {
        return RsAfiliadoServicio::create([
            'rs_portabilidad_id' => ($param === 2) ? $afiliado->portabilidad_activa['id'] : null,
            'rs_servportabilidad_id' => ($param === 2) ? $idSerHabilitado : null,
            'rs_servhabilitado_id' => ($param !== 2) ? $idSerHabilitado : null,
            'as_afiliado_id' => $afiliado->id,
        ]);
    }

    /**
     * @param \App\Models\Aseguramiento\AsAfiliado $afiliado
     * @param $afiServicio
     */
    private function registrarServicioAnterior(AsAfiliado $afiliado, $afiServicio): void
    {
        RsAfiservicambio::create([
            'as_afiliado_id' => $afiliado->id,
            'id_serv_anterior' => isset($afiliado->portabilidad_activa) ? $afiServicio['rs_servportabilidad_id'] : $afiServicio['rs_servhabilitado_id'],
            'id_portabilidad' => isset($afiliado->portabilidad_activa) ? $afiServicio['rs_portabilidad_id'] : null,
            'gs_usuario_id' => Auth::user()->id,
        ]);
    }
    
    public function getLogTrasladoMs(Request $request)
    {
        if (Input::get('per_page'))
            $data = LogMsDetalle::select('le.descripcion','tv.descripcion AS vigencia','le.fecha','log_ms_detalle.informacion','ams.estado_procesado')
            ->leftJoin('log_ms_encabezado AS le','le.consecutivo_log_ms','=','log_ms_detalle.consecutivo_log_ms')
            ->leftJoin('tb_vigencia_traslado AS tv', 'tv.consecutivo_vigencia','=','le.consecutivo_vigencia')
            ->leftJoin('af_afiliado_ms AS ams','ams.consecutivo_ns','=','log_ms_detalle.consecutivo_ms')
            ->leftJoin('as_afiliados AS af','af.id','=', 'ams.consecutivo_afiliado')
            ->where('af.id','=',$request->as_afiliado_id)->paginate(Input::get('per_page'));
            
        return LogMsDetalleResource::collection($data);
        
    }

}
