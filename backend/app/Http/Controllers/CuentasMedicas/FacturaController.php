<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Exports\CmRadicadosExport;
use App\Http\Controllers\Controller;
use App\Http\Repositories\CuentasMedicas\RadicadosRepository;
use App\Http\Resources\CuentasMedicas\FacturaServiciosResource;
use App\Http\Resources\CuentasMedicas\FacturasResource;
use App\Models\AuditorCuentas\AcAuditore;
use App\Models\CuentasMedicas\CmAsignacionFactura;
use App\Models\CuentasMedicas\CmFactura;
use App\Models\CuentasMedicas\CmFacturaMonto;
use App\Models\CuentasMedicas\CmRadicado;
use App\Models\RedServicios\RsEntidade;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class FacturaController extends Controller
{
    public function index()
    {
        $roles = Auth::user()->roles;
        $nameRoles = [];
        foreach ($roles as $key => $role) {
            array_push($nameRoles, $roles[$key]['id']);
        }
        if (in_array(43, $nameRoles)) {
            //$auditor = AcAuditore::where('gs_usuario_id',Auth::user()->id)->first()->gs_usuario_id;
            $auditor = Auth::user()->id;
            if (Input::get('per_page')) {
                $total = (Input::get('per_page') === 'all') ? 2000 : Input::get('per_page');

                $facturas = CmFactura::with('radicado.entidad.tipo','auditores.usuario')
                    ->where('estado', '<>', 'Radicada')
                    ->whereHas('auditores', function ($query) use ($auditor) {
                        $query->where('ac_auditores.gs_usuario_id', '=', $auditor);
                    })->pimp()->paginate($total);
            }

            return FacturasResource::collection($facturas);
        } else {
            if (Input::get('per_page')) {
                $total = (Input::get('per_page') === 'all') ? 2000 : Input::get('per_page');

                return FacturasResource::collection(
                    CmFactura::pimp()->with('radicado.entidad.tipo','auditores.usuario')
                        ->orderBy('updated_at')->paginate($total));
            }
        }

        return $this->getAllRegistros();
    }

    public function show(CmFactura $factura)
    {
        return $factura;
    }

    public function getFactura($id)
    {
        $factura = CmFactura::with(CmFactura::allRelations())->where('id', '=', $id)->first();
        $roles = Auth::user()->roles;
        $nameRoles = [];

        foreach ($roles as $key => $role) {
            array_push($nameRoles, $roles[$key]['id']);
        }

        if (in_array(43, $nameRoles) && ($factura->estado === 'Asignada')) {
            $factura->estado = 'Auditando';
            $factura->save();
        }

        //CuentasMedicasTrazaTrait::registrarServices($factura,$factura->radicado()->first()->rs_contrato_id);

        return new FacturaServiciosResource($factura);
    }

    public function actualizarEstadoFactura(CmFactura $factura, Request $request)
    {
        try {
            $workflow = \Workflow::get($factura);
            $workflow->apply($factura, $request->estado);
            $factura->gs_usuario_avala_id = (integer) Auth::user()->id;
            $factura->save();

            return (new FacturasResource($factura->load('radicado.entidad','auditores.usuario')))->response()->setStatusCode(202);
        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getAllFacturas($radicado)
    {
        $query = CmFactura::where('cm_radicado_id', $radicado)->pimp()->with('usuarioAvala', 'glosas');
        if ($per_page = Input::get('per_page')) {
            return Resource::collection($query->paginate($per_page));
        }

        return Resource::collection($query->get());
    }

    public function asignacionFactura(Request $request)
    {
        try {
            list($facturasConglosas, $itemFacturas) = $this->validaReasignacion($request);

            if (count($itemFacturas) > 0) {
                throw new \Exception($facturasConglosas);
            }

            $data = collect();
            $uniqueAuditores = $request->auditores;
            $auditores = array_unique($uniqueAuditores);

            foreach ($request->facturas as $key => $factura) {
                $fact = CmFactura::with('auditores')->find($factura);
                if ($fact->auditores()->get()->count() > 0) {
                    $fact->auditores()->detach();
                }
                foreach ($auditores as $auditore) {
                    $exists = CmAsignacionFactura::where('ac_auditor_id', $auditore)->where('cm_factura_id', $fact->id)->get();
                    if (isset($exists)) {
                        foreach ($exists as $key => $item) {
                            $item->delete();
                        }
                    }
                    $this->attachAuditorFactura($fact, $auditore, null);
                }
                if ($fact->estado === 'Radicada') {
                    $workflow = \Workflow::get($fact);
                    $workflow->apply($fact, 'assign');
                    $fact->save();

                }
                $data->push($fact);
            }
            $radicados = CmRadicado::get();

            foreach ($radicados as $radicado) {
                if ($radicado->nombre_estado === 'Radicado') {
                    $numFacturas = count($radicado->facturas);
                    $factAsignadas = collect($radicado->facturas)->whereIn('estado', ['Asignada']);
                    if ($numFacturas === count($factAsignadas)) {
                        $cambio = new RadicadosRepository();
                        $cambio->guardar([
                            'cm_radicado_id' => $radicado->id,
                            'cm_estadosrad_id' => 2,
                            'gs_usuario_id' => Auth::user()->id,
                            'aceptado' => '1',
                        ]);
                    }
                }
            }

            return response()->json(['data' => new Resource($data), 'status' => 'ok'])->setStatusCode(200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => $e
            ],500);
        }
    }

    public function getPdfInforme()
    {
        try {
            $idRadicado = Input::get('id');
            $nombre_tabla = "zz_glosa".Str::random(16);
            DB::statement("SET GLOBAL local_infile = `ON`");
            DB::statement("CREATE TABLE {$nombre_tabla}
                                     SELECT  a.id,
                                            SUM(g.valor_glosa) AS glosa, 
                                            c.as_afiliado_id,
                                            a.rs_entidad_id
                                    FROM cm_radicados           AS a
                                    LEFT JOIN  cm_facturas      AS b ON b.cm_radicado_id = a.id
                                    LEFT JOIN cm_facservicios   AS c ON c.cm_factura_id = b.id
                                    LEFT JOIN cm_glosas         AS g ON g.cm_facservicio_id = c.id
                                    WHERE b.cm_radicado_id = '{$idRadicado}' AND g.of_facservicio IS NULL 
                                    GROUP BY a.id
                                    UNION
                                    SELECT  	a.id,
                                              SUM(g.valor_glosa) AS glosa, 
                                              c.as_afiliado_id,
                                              a.rs_entidad_id
                                    FROM cm_radicados           AS a
                                    LEFT JOIN  cm_facturas      AS b ON b.cm_radicado_id = a.id
                                    LEFT JOIN cm_facservicios   AS c ON c.cm_factura_id = b.id
                                    LEFT JOIN cm_glosas         AS g ON g.cm_facservicio_id = c.id
                                    WHERE b.cm_radicado_id = '{$idRadicado}' AND g.of_facservicio=1
                                    GROUP BY a.id");
            $cuentas = DB::selectOne("SELECT  h.consecutivo AS consecutivo,
                                                     h.fecha_radicado,
                                                     h.periodo_inicio,
                                                     h.periodo_fin,
                                                     w.cantidad AS numero_facturas,
                                                     IF(h.rs_contrato_id IS NOT NULL, k.numero_contrato, 'Sin Contrato') AS numero_contrato,
                                                     w.valor_cuenta AS valor_cuenta,
                                                     w.valor_descuentos AS descuentos,
                                                     w.valor_compartido AS copagos,
                                                     z.glosa AS glosas,
                                                     if(z.glosa IS NULL,w.valor_cuenta -  w.valor_compartido - w.valor_descuentos,w.valor_cuenta - w.valor_compartido - w.valor_descuentos - z.glosa) AS valor_avalado,
                                                     IF(h.rs_contrato_id IS NOT NULL,m.nombre,'Evento') AS tipo_facturacion,
                                                     CONCAT_WS(' ', '<b>',e.cod_habilitacion,'</b>', e.nombre) AS entidad,
                                                     IF(i.id IS NOT NULL,o.nombre,p.nombre) AS plan_beneficio,
                                                     a1.cod_radicacion_ct AS radicado_rip,
                                                     a1.created_at AS fecha_rip
                                            FROM  	(SELECT 	id, 
                                                    as_afiliado_id, 
                                                    rs_entidad_id, 
                                                    SUM(glosa) AS glosa 
                                                 FROM {$nombre_tabla}
                                                )AS z
                                            LEFT JOIN   (SELECT  u.cm_radicado_id AS id, 
                                                         COUNT(*) AS cantidad, 
                                                         SUM(u.valor_compartido + u.valor_comision + u.valor_descuentos + u.valor_neto) AS valor_cuenta,
                                                         SUM(u.valor_compartido) AS valor_compartido,
                                                         SUM(u.valor_comision) AS valor_comision,
                                                         SUM(u.valor_descuentos) AS valor_descuentos,
                                                         SUM(u.valor_neto) AS valor_neto
                                                     FROM cm_facturas AS u 
                                                     WHERE u.cm_radicado_id = '{$idRadicado}'
                                                     GROUP BY u.cm_radicado_id
                                                    ) AS w ON w.id = z.id
                                            LEFT JOIN cm_radicados              AS h ON h.id = w.id
                                            LEFT JOIN cm_facturas           AS j ON j.cm_radicado_id = h.id
                                            LEFT JOIN rs_planescoberturas  AS i ON i.id = h.rs_contrato_id
                                            LEFT JOIN ce_proconminutas        AS k ON k.id = i.ce_proconminuta_id
                                            LEFT JOIN ce_tipocontratos        AS m ON m.id = k.ce_tipocontrato_id
                                            LEFT JOIN rs_entidades         AS e ON e.id = z.rs_entidad_id
                                            LEFT JOIN as_afiliados         AS f ON f.id = z.as_afiliado_id
                                            LEFT JOIN as_regimenes              AS o ON o.id = i.regimen_atendido
                                            LEFT JOIN as_regimenes              AS p ON p.id = f.as_regimene_id
                                            LEFT JOIN rs_rips_radicados         AS a1 ON a1.id = h.rs_rip_radicado_id
                                            WHERE z.id ='{$idRadicado}'
                                            GROUP BY j.cm_radicado_id");

            $facturas = DB::select("SELECT  j.id as id_factura,
                                                    j.consecutivo AS numero_factura,
                                                    CONCAT_WS(' ',f.tipo_identificacion,f.identificacion) AS identificacion,
                                                    CONCAT_WS(' ',f.apellido1,f.apellido2) AS apellidos,
                                                    CONCAT_WS(' ',f.nombre1,f.nombre2) AS nombres,
                                                    j.valor_neto AS valor_factura,
                                                    j.valor_compartido AS valor_copago,
                                                    ifnull(z.glosa,0) AS valor_glosa,
                                                    ifnull(valor_factura-z.glosa,j.valor_neto) AS valor_pagar
                                            FROM cm_radicados AS h
                                            LEFT JOIN cm_facturas 			AS j ON j.cm_radicado_id = h.id
                                            LEFT JOIN cm_facservicios 		AS k ON k.cm_factura_id = j.id
                                            LEFT JOIN (SELECT  	a.id,
                                                                      SUM(g.valor_glosa) AS glosa,  
                                                                      g.of_facservicio,
                                                                      g.cm_facservicio_id,
                                                                      b.id AS id_factura,
                                                                      b.consecutivo AS numeroFactura,
                                                                      b.valor_comision,
                                                                      b.valor_neto,
                                                                      b.valor_compartido,
                                                                      b.valor_descuentos,
                                                                      c.as_afiliado_id,
                                                                      a.rs_entidad_id,
                                                                      b.valor_neto AS valor_factura 
                                                            FROM cm_radicados            AS a
                                                            LEFT JOIN cm_facturas        AS b ON b.cm_radicado_id = a.id
                                                            LEFT JOIN cm_facservicios    AS c ON c.cm_factura_id = b.id
                                                            LEFT JOIN cm_glosas          AS g ON g.cm_facservicio_id = c.id
                                                            WHERE b.cm_radicado_id = '{$idRadicado}' AND g.valor_glosa>0 AND g.of_facservicio IS NULL          
                                                            GROUP BY b.id
                                                            union
                                                            SELECT  	a.id,
                                                                      SUM(g.valor_glosa) AS glosa,  
                                                                      g.of_facservicio,
                                                                      g.cm_facservicio_id,
                                                                      b.id AS id_factura,
                                                                      b.consecutivo AS numeroFactura,
                                                                      b.valor_comision,
                                                                      b.valor_neto,
                                                                      b.valor_compartido,
                                                                      b.valor_descuentos,
                                                                      c.as_afiliado_id,
                                                                      a.rs_entidad_id,
                                                                      b.valor_neto AS valor_factura 
                                                            FROM cm_radicados            AS a
                                                            LEFT JOIN cm_facturas        AS b ON b.cm_radicado_id = a.id
                                                            LEFT JOIN cm_facservicios    AS c ON c.cm_factura_id = b.id
                                                            LEFT JOIN cm_glosas          AS g ON g.cm_facservicio_id = c.id
                                                            WHERE b.cm_radicado_id = '{$idRadicado}' AND g.valor_glosa>0 AND g.of_facservicio = 1         
                                                            GROUP BY b.id)		AS z ON z.id_factura = j.id	
                                            LEFT JOIN rs_entidades        AS e ON e.id = h.rs_entidad_id
                                            LEFT JOIN as_afiliados        AS f ON f.id = k.as_afiliado_id
                                            WHERE h.id = '{$idRadicado}'
                                            GROUP BY j.id");
            DB::statement("SET GLOBAL local_infile = `OFF`");

            $data1 = json_decode(json_encode($cuentas), true);
            $data2 = json_decode(json_encode($facturas), true);

            setlocale(LC_ALL, "es_ES");
            if (Input::get('html')) {
                return view('dompdf.cuentas_medicas.informe_cm');
            }

            $pdf = PDF::loadView('dompdf.cuentas_medicas.informe_cm', [
                    'cuentas' => $data1,
                    'facturas' => $data2,
                    'user' => User::find((integer) Input::get('user'))->name,
                ]);
            $pdf->setPaper('letter', 'portrait');
            $link = $pdf->stream('INFORME AUDITORÍA CUENTAS MÉDICAS', ['Attachment' => 0]);
            DB::statement("DROP TABLE {$nombre_tabla}");

            return $link;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getReporteGlosas(Excel $excel)
    {
        try {
            $id = Input::get('id');
            $cmglosas = DB::select("SELECT    c.id AS idServicio,
                                                     a1.cod_radicacion_ct AS radicado_rip,
                                                     b.id AS id_factura,
                                                  LPAD(a.consecutivo,6,0) AS numero_radicado,
                                                  b.consecutivo AS numero_factura,
                                                  IF(c.as_afiliado_id IS NOT NULL,
                                                         CONCAT_WS(' ', f.tipo_identificacion, f.identificacion),
                                                         CONCAT_WS(' ', c.tipo_documento, c.documento)) AS identificacion,
                                                  IF(c.as_afiliado_id IS NOT NULL,
                                                         f.nombre_completo,
                                                         c.nombre) AS afiliado,
                                                  c.codigo AS codigo_servico,
                                                  c.nombre AS nombre_servicio,
                                                  c.cantidad AS cantidad,
                                                  c.valor_unitario AS valor_unitario,
                                                  (c.valor_unitario * c.cantidad) AS valor_total,
                                                  z.glosa AS valor_glosa,
                                                  w.codGlosa AS detalle_glosa,
                                                  w.observacion,
                                                  CONCAT_WS(' ','CC',i.identification) AS usuario,
                                                  e.nombre AS entidad
                                        FROM  (SELECT  a.id,
                                                            g.cm_facservicio_id,  
                                                          SUM(g.valor_glosa) AS glosa, 
                                                          c.as_afiliado_id,
                                                          a.rs_entidad_id
                                                FROM cm_radicados           AS a
                                                LEFT JOIN  cm_facturas      AS b ON b.cm_radicado_id = a.id
                                                LEFT JOIN cm_facservicios   AS c ON c.cm_factura_id = b.id
                                                LEFT JOIN cm_glosas         AS g ON g.cm_facservicio_id = c.id
                                                WHERE b.cm_radicado_id = '{$id}' AND g.valor_glosa>0 AND g.of_facservicio IS NULL 
                                                GROUP BY g.cm_facservicio_id
                                                UNION
                                                SELECT  a.id,
                                                            g.cm_facservicio_id,  
                                                          SUM(g.valor_glosa) AS glosa, 
                                                          c.as_afiliado_id,
                                                          a.rs_entidad_id
                                                FROM cm_radicados           AS a
                                                LEFT JOIN  cm_facturas      AS b ON b.cm_radicado_id = a.id
                                                LEFT JOIN cm_facservicios   AS c ON c.cm_factura_id = b.id
                                                LEFT JOIN cm_glosas         AS g ON g.cm_facservicio_id = c.id
                                                WHERE b.cm_radicado_id = '{$id}' AND g.valor_glosa>0 AND g.of_facservicio=1
                                                GROUP BY g.cm_facservicio_id
                                                ) AS z
                                         LEFT JOIN (SELECT  
                                                            g.cm_facservicio_id,
                                                            ifnull(group_concat(DISTINCT d.glosa separator ','),'') as codGlosa,
                                                                  ifnull(group_concat(DISTINCT g.observacion SEPARATOR '|'),'') AS observacion                  
                                                        FROM cm_radicados             AS a
                                                        LEFT JOIN cm_facturas        AS b ON b.cm_radicado_id = a.id
                                                        LEFT JOIN cm_facservicios     AS c ON c.cm_factura_id = b.id
                                                        LEFT JOIN cm_glosas           AS g ON g.cm_facservicio_id = c.id
                                                        LEFT JOIN cm_manglosas          AS d ON d.id = g.cm_manglosa_id
                                                        WHERE b.cm_radicado_id = '{$id}' AND g.valor_glosa>0
                                                        GROUP BY g.cm_facservicio_id
                                                        ) AS w ON w.cm_facservicio_id = z.cm_facservicio_id
                                         LEFT JOIN cm_facservicios     AS c ON c.id = z.cm_facservicio_id
                                         LEFT JOIN cm_facturas          AS b ON b.id = c.cm_factura_id
                                         LEFT JOIN cm_radicados        AS a ON a.id = b.cm_radicado_id
                                         LEFT JOIN rs_entidades        AS e ON e.id = a.rs_entidad_id
                                         LEFT JOIN as_afiliados        AS f ON f.id = c.as_afiliado_id
                                         LEFT JOIN cm_glosas           AS g ON g.cm_facservicio_id = c.id
                                         LEFT JOIN cm_manglosas         AS h ON h.id = g.cm_manglosa_id
                                         LEFT JOIN gs_usuarios          AS i ON i.id = g.gs_usuario_id
                                         LEFT JOIN rs_rips_radicados         AS a1 ON a1.id = a.rs_rip_radicado_id
                                         WHERE a.id = '{$id}' AND g.id IS NOT NULL
                                         GROUP BY z.cm_facservicio_id");

            if (count($cmglosas) <= 0) {
                $data = "El reporte de glosas no se puede descargar, es posible que no existan glosas registradas en las facturas de la cuenta radicada # $id.";
                throw new \Exception($data, 422);
            }

            $data = json_decode(json_encode($cmglosas), true);

            $entidad = preg_replace("/([^A-Za-z0-9])/", "",$data[0]['entidad']);

            return $excel::download(new CmRadicadosExport($data), "REPORTE_GLOSAS_".$entidad.".xlsx");
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @param CmFactura $fact
     * @param $auditore
     * @param $param
     * @return mixed
     */
    private function attachAuditorFactura(CmFactura $fact, $auditore, $param)
    {
        CmAsignacionFactura::create(
            [
                'ac_auditor_id' => $auditore,
                'cm_factura_id' => $fact->id,
                'estado'        => '1',
                'fecha_reasignacion' => ($param === 1) ? Carbon::now()->toDateString() : null,
                'gs_usuario_id'     => Auth::user()->id
            ]
        );
    }

    /**
     * @param $audit
     * @param $fact
     * @param $param
     */
    private function siReasigna ($audit, $fact, $param): void
    {
        $asignPivot = CmAsignacionFactura::where('ac_auditor_id', $audit->id)->where('cm_factura_id', $fact->id)->first();
        $asignPivot->estado = ($param === 1) ? '2' : '1';
        $asignPivot->fecha_reasignacion = ($param === 2) ? Carbon::now()->toDateString() : ($asignPivot->fecha_reasignacion !== null) ? $asignPivot->fecha_reasignacion : null;

        $asignPivot->save();
    }

    /**
     * @return AnonymousResourceCollection
     */
    private function getAllRegistros(): AnonymousResourceCollection
    {
        return FacturasResource::collection(CmFactura::pimp()->with(CmFactura::allRelations())->orderBy('updated_at')->get());
    }

    // Complementos

    public function getComplementos()
    {
        $complementos = collect();
        $complementos->put('montos', CmFacturaMonto::all());
        $complementos->put('entidades', $this->entidades());
        $complementos->put('auditores', $this->getAuditores());
        $complementos->put('radicados', $this->getRadicadosXEntidad());

        return response()->json(['data' => $complementos], 200);
    }

    public function entidades()
    {
        $roles = Auth::user()->roles;
        $nameRoles = [];
        foreach ($roles as $key => $role) {
            array_push($nameRoles, $roles[$key]['id']);
        }
        if (in_array(43, $nameRoles)) {
            $idAuditor = (integer) AcAuditore::where('gs_usuario_id', Auth::user()->id)->first()->id;

            $entitys = DB::select("SELECT rad.rs_entidad_id as idEntidad FROM cm_asignacion_facturas as asignar
                                    LEFT JOIN cm_facturas AS fac ON fac.id = asignar.cm_factura_id
                                    LEFT JOIN cm_radicados AS rad ON rad.id = fac.cm_radicado_id
                                    WHERE rad.rs_entidad_id IS NOT NULL AND asignar.ac_auditor_id = '{$idAuditor}'
                                    GROUP BY rad.rs_entidad_id");
        } else {
            $entitys = DB::select("SELECT rad.rs_entidad_id as idEntidad FROM cm_facturas as fac
                                LEFT JOIN cm_radicados as rad ON rad.id = fac.cm_radicado_id
                                WHERE rad.rs_entidad_id IS NOT NULL
                                GROUP BY rad.rs_entidad_id");
        }
        $entes = collect();
        foreach ($entitys as $key => $entity) {
            $prestador = RsEntidade::with('tercero', 'municipio')->find($entity->idEntidad);
            $entes->push([
                'id' => $prestador['id'],
                'nombre' => $prestador['nombre'],
                'cod_habilitacion' => $prestador['cod_habilitacion'],
                'tercero' => [
                    'id' => $prestador['tercero']['id'],
                    'identificacion' => $prestador['tercero']['identificacion'],
                    'razon_social' => $prestador['tercero']['razon_social'],
                ],
                'municipio' => $prestador['municipio'],
            ]);
        }

        return $entes;
    }

    public function getAuditores()
    {
        $auditores = DB::select("SELECT  gs.id, 
                                                gs.identification,
                                                CONCAT_WS(' ','CC',gs.identification) AS identificacion_completa,
                                                gs.name,
                                                gs.email,
                                                gs.state,
                                                aud.tipo
                                FROM cm_asignacion_facturas  AS fac
                                LEFT JOIN ac_auditores AS aud ON  fac.ac_auditor_id = aud.id
                                LEFT JOIN gs_usuarios AS gs ON aud.gs_usuario_id = gs.id
                                WHERE fac.id IS NOT NULL
                                GROUP BY gs.id");

        return $auditores;
    }

    public function getRadicadosXEntidad()
    {
        $roles = Auth::user()->roles;
        $nameRoles = [];
        foreach ($roles as $key => $role) {
            array_push($nameRoles, $roles[$key]['id']);
        }
        if (in_array(43, $nameRoles)) {
            $idAuditor = (integer) AcAuditore::where('gs_usuario_id', Auth::user()->id)->first()->id;
            $radicados = DB::select("SELECT  rad.id,
                                                 LPAD(rad.consecutivo,6,0) AS numero_radicado,
                                                 rad.fecha_radicado,
                                                 rad.rs_entidad_id
                                         FROM cm_radicados AS rad
                                         LEFT JOIN cm_facturas AS fac ON fac.cm_radicado_id = rad.id
                                         LEFT JOIN cm_asignacion_facturas AS b ON b.cm_factura_id = fac.id 
                                         WHERE fac.cm_radicado_id IS NOT NULL AND b.ac_auditor_id = '{$idAuditor}'
                                         GROUP BY fac.cm_radicado_id");
        } else {
            $radicados = DB::select("SELECT  rad.id,
                                                LPAD(rad.consecutivo,6,0) AS numero_radicado,
                                                rad.fecha_radicado,
                                                rad.rs_entidad_id
                                        FROM cm_radicados AS rad
                                        LEFT JOIN cm_facturas AS fac ON fac.cm_radicado_id = rad.id
                                        WHERE fac.cm_radicado_id IS NOT NULL
                                        GROUP BY fac.cm_radicado_id");
        }

        $data = json_decode(json_encode($radicados), true);

        return $data;
    }

    /**
     * @param Request $request
     * @return array
     */
    private function validaReasignacion(Request $request): array
    {
        $facturasConglosas = "Los siguientes # Facturas, no es posible asignarlas porque presentan glosas registradas: ";
        $itemFacturas = array();
        $facturas = $request->facturas;
        foreach ($facturas as $key => $fact) {
            $factura = CmFactura::find($fact);
            if ($factura->existen_glosas) {
                array_push($itemFacturas, $factura->id);
                $facturasConglosas .= ($key + 1) . '-' . ($factura->consecutivo) . ((end($facturas) === $fact) ? '' : PHP_EOL);
            }
        }
        return array($facturasConglosas, $itemFacturas);
    }
}
