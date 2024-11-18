<?php

namespace App\Http\Controllers\Riips;

use App\Http\Controllers\Controller;
use App\Http\Resources\Riips\RiipsResource;
use App\Models\Riips\RsRipsRadicado;
use App\Models\RsCum;
use App\Exports\RipAFInformeExport;
use App\Exports\RipACInformeExport;
use App\Exports\RipAPInformeExport;
use App\Exports\RipAHInformeExport;
use App\Exports\RipAMInformeExport;
use App\Exports\RipANInformeExport;
use App\Exports\RipATInformeExport;
use App\Exports\RipAUInformeExport;
use App\Exports\RipUSInformeExport;
use Chumper\Zipper\Zipper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Validator;

class RiipsRadicadoController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            return RiipsResource::collection(RsRipsRadicado::with('RsEntidad.tercero', 'RsEntidad.tipo')->where('estado_radicacion', '=', 'Validado')->orWhere('estado_radicacion', '=', 'Confirmado')->pimp()->orderBy('created_at', 'asc')->paginate(Input::get('per_page')));
        }

        return RiipsResource::collection(RsRipsRadicado::with('RsEntidad.tercero', 'RsEntidad.tipo')->where('estado_radicacion', '=', 'Validado')->orWhere('estado_radicacion', '=', 'Confirmado')->pimp()->orderBy('created_at', 'asc')->get());
        //try {
        //	return response()->json([
        //		'estado' => 'ok',
        //		'rips' => RsRipsRadicado::where('estado_radicacion', 'Validado')
        //            ->orWhere('estado_radicacion', 'Confirmado')
        //            ->with(['RsEntidad.tercero', 'RsEntidad.tipo'])->orderBy('created_at', 'ASC')->get(),
        //	]);
        //} catch (\Exception $e) {
        //	return response()->json([
        //		'message' => 'Error en el servidor',
        //		'errors' => $e->getMessage(),
        //	]);
        //}
    }

    public function store(Request $request)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validatedData->fails()) {
                return response()->json([
                    'estado' => 'validador',
                    'errors' => $validatedData->errors(),
                ]);
            }
            $ripsRadicado = RsRipsRadicado::where('id', $request->id)->first();
            $ripsRadicado->estado_radicacion = 'Confirmado';
            $ripsRadicado->save();

            return response()->json([
                'estado' => 'ok',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Error en el servidor",
                "error" => $e->getMessage(),
                "trace" => $e->getTrace(),
            ]);
        }
    }

    public function show($id)
    {
    }

    public function update(Request $request, $id)
    {
        try {
            $ripp = RsRipsRadicado::find($id);

            if ($ripp->estado_radicacion === 'Confirmado') {
                abort('422', 'No es posible cambiar el tipo de facturación');
            }

            $ripp->tipo_facturacion = $request->tipo_facturacion;
            $ripp->save();

            return response(new RiipsResource($ripp))->setStatusCode(201);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function destroy($id)
    {
    }

    public function getRipsValidadosZip($idRip)
    {
        try {

            $ripsRadicado = RsRipsRadicado::where('id', $idRip)->first();
            $existeArchivo = Storage::disk('s3')->exists('RIPS/'.$ripsRadicado->cod_radicacion.'/archivos/');

            if (! $existeArchivo) {
                return response()->json([
                    'state' => 'error',
                    'error' => 'No se encontró la ruta del RIPS con código '.$ripsRadicado->cod_radicacion,
                ], 404);
            }

            $ziper = new Zipper();
            $nombresDeArchivosEnS3 = Storage::disk('s3')->allFiles('RIPS/'.$ripsRadicado->cod_radicacion.'/archivos');

            if (empty($nombresDeArchivosEnS3)) {
                return response()->json([
                    'state' => 'error',
                    'error' => 'No existen archivos para descargar del RIPS con código '.$ripsRadicado->cod_radicacion,
                ], 404);
            }

            foreach ($nombresDeArchivosEnS3 as $rutaArchivo) {
                $temp = explode('/', $rutaArchivo);
                $nombreArchivoTXT = end($temp);

                $archivoTXT = Storage::disk('s3')->get($rutaArchivo);

                if (! $archivoTXT) {
                    return response()->json([
                        'state' => 'error',
                        'error' => 'Uno o varios de los archivos TXT del RIPS no se pudieron recuperar.',
                    ], 404);
                }

                $ziper->make(storage_path('app/public/tmp/ripstemp.zip'))->addString($nombreArchivoTXT, $archivoTXT);
            }

            $rutaArchivoZip = $ziper->getFilePath();

            $response = response()->download($rutaArchivoZip)->deleteFileAfterSend(true);

            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error en el servidor',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function getEntidadesRips()
    {
        $entidads = $this->getEntidadesFilter($this->consultaEntidadesValidadas(['Validado']));

        return response()->json(['data' => $entidads])->setStatusCode(200);
    }

    public function getEntysValidadasYConfirmadas()
    {
        $entidads = $this->getEntidadesFilter($this->consultaEntidadesValidadas(['Validado', 'Confirmado']));

        return response()->json(['data' => $entidads])->setStatusCode(200);
    }

    public function ejecutarSeed() 
    {
        // \Log::info('entro a ejecutar seed');
        $class = 'RipsFromS3ToDatabaseSeeder';
        DB::statement( 'TRUNCATE TABLE ripsac' );
        DB::statement( 'TRUNCATE TABLE ripsaf' );
        DB::statement( 'TRUNCATE TABLE ripsah' );
        DB::statement( 'TRUNCATE TABLE ripsam' );
        DB::statement( 'TRUNCATE TABLE ripsan' );
        DB::statement( 'TRUNCATE TABLE ripsap' );
        DB::statement( 'TRUNCATE TABLE ripsat' );
        DB::statement( 'TRUNCATE TABLE ripsau' );
        DB::statement( 'TRUNCATE TABLE ripsct' );
        DB::statement( 'TRUNCATE TABLE ripsus' );
        \Artisan::call("db:seed", ['--class' => $class]);
        // $this->reporteRip();
        return response()->json([
            'message' => '',
            'pro' => 'Tuncate ok, se ejecuto el seed'
        ]);
    }

    public function reporteRip () {
        // \Log::info('entro a ejecutar reporte');
        $tipoRip = Input::get('id');
        $consulta = DB::select(DB::raw(
            $this->consultaRip($tipoRip)
        ));
        $nombre = "";
        $numero_fila = 1;
        $longitud = count($consulta);
        for ($i=0; $i < $longitud; $i++) { 
            if ($nombre !== $consulta[$i]->nombre_archivo) {
                $numero_fila = 1;
                $nombre = $consulta[$i]->nombre_archivo;
            } else {
                $numero_fila += 1;
            }
            $consulta[$i]->numero_fila = $numero_fila;
        }
        return $this->getExcel($consulta, $tipoRip);
    }

    public function getExcel ($data, $tipoRip)
    {
        if ($tipoRip === '0') {
            return (new RipAFInformeExport($data))->download('af.xlsx');
        } else if ($tipoRip === '1') {
            return (new RipACInformeExport($data))->download('ac.xlsx');
        } else if ($tipoRip === '2') {
            return (new RipAPInformeExport($data))->download('ap.xlsx');
        } else if ($tipoRip === '3') {
            return (new RipAHInformeExport($data))->download('ah.xlsx');
        } else if ($tipoRip === '4') {
            return (new RipAMInformeExport($data))->download('am.xlsx');
        } else if ($tipoRip === '5') {
            return (new RipANInformeExport($data))->download('an.xlsx');
        } else if ($tipoRip === '6') {
            return (new RipATInformeExport($data))->download('at.xlsx');
        } else if ($tipoRip === '7') {
            return (new RipAUInformeExport($data))->download('au.xlsx');
        } else if ($tipoRip === '8') {
            return (new RipUSInformeExport($data))->download('us.xlsx');
        }
        
    }


    public function firmarReporte () {
        return URL::temporarySignedRoute('reporteRip', now()->addMinute(1), ['id' => Input::get('id')]);
    }

    /**
     * @param $estados
     * @return array
     */
    private function consultaEntidadesValidadas($estados): array
    {
        $status = is_array($estados) ? ("'".implode("','", $estados)."'") : $estados;

        return DB::select("SELECT a.rs_entidad_id,
                                             b.id AS id_enditad,
                                             b.nombre,
                                             b.cod_habilitacion,
                                             c.id AS id_tercero,
                                             c.identificacion,
                                             c.razon_social,
                                             c.nombre_completo
                                    FROM rs_rips_radicados AS a
                                    LEFT JOIN rs_entidades AS b ON b.id = a.rs_entidad_id
                                    LEFT JOIN gn_terceros AS c ON c.id = b.gn_tercero_id
                                    WHERE a.estado_radicacion IN ({$status}) AND b.id IS NOT NULL
                                    GROUP BY a.rs_entidad_id");
    }

    /**
     * @param array $entidads
     * @return array
     */
    private function getEntidadesFilter(array $entidads): array
    {
        $data = array();
        foreach ($entidads as $key => $entidad) {
            if ($entidad->id_enditad !== null) {
                array_push($data, [
                    'id' => $entidad->id_enditad,
                    'nombre' => $entidad->nombre,
                    'cod_habilitacion' => $entidad->cod_habilitacion,
                    'tercero' => [
                        'id' => $entidad->id_tercero,
                        'identificacion' => $entidad->identificacion,
                        'razon_social' => $entidad->razon_social,
                        'nombre_completo' => $entidad->nombre_completo,
                    ],
                ]);
            }
        }

        return $data;
    }

    private function consultaRip($id)
    {
        $array = array(
            "            
            SELECT
            numero_radicado,nombre_archivo,numero_fila,consecutivo_cargue,codigo_prestador,razon_social,tipo_identificacion,numero_identificacion,numero_factura,fecha_expedicion_factura,fecha_inicio,fecha_final,codigo_entidad,nombre_entidad,numero_contrato,plan_beneficios,numero_poliza,valor_copago,valor_comision,valor_descuentos,valor_neto_pagar,numero_cuenta,numero_contrato,periodo_facturado
            FROM (
            SELECT 
            r.cod_radicacion AS numero_radicado,
            CONCAT('AF',r.cod_radicacion_ct) AS nombre_archivo,
            0 as numero_fila,
            r.id AS consecutivo_cargue,
            e.cod_habilitacion  AS codigo_prestador,
            ax.nombreips AS razon_social,
            ax.tipoidentificacion AS tipo_identificacion,
            ax.identificacion AS numero_identificacion,
            ax.numerofactura AS numero_factura,
            ax.fechafactura AS fecha_expedicion_factura,
            ax.fechainicio AS fecha_inicio,
            ax.fechafinal AS fecha_final,
            ax.codigoeapb AS codigo_entidad,
            ax.nombreeapb AS nombre_entidad,
            ax.numerocontrato AS numero_contrato,
            ax.planbeneficios AS plan_beneficios,
            ax.numeropoliza AS numero_poliza,
            ax.copago AS valor_copago,
            ax.valorcomision AS valor_comision,
            ax.valordescuentos AS valor_descuentos,
            ax.valorfactura AS valor_neto_pagar,
            NULL AS numero_cuenta, 
            ax.numerocontrato AS numero_contrato_2,
            DATE_FORMAT(ax.fechainicio,'%Y%m') AS periodo_facturado,
            @na:=CONCAT('AF',r.cod_radicacion_ct) AS a0
            FROM 
            desarrollo.rs_rips_radicados r,
            desarrollo.ripsaf ax, 
            desarrollo.rs_entidades e
            WHERE
            r.estado_radicacion = 'Confirmado'
            AND ax.id = r.id
            AND e.id = r.rs_entidad_id
            
            ORDER BY r.cod_radicacion, ax.numerofactura
            ) t
            ORDER BY t.periodo_facturado, t.numero_radicado, t.nombre_archivo, t.numero_fila;
            ", 
            
            "
            SELECT 
            numero_radicado,nombre_archivo,numero_fila,consecutivo_cargue,numero_factura,codigo_prestador,tipo_identificacion,numero_identificacion,fecha_consulta,numero_autorizacion,codigo_consulta,finalidad,causa_externa,diagnostico_principal,diagnostico_relacionado_1,diagnostico_relacionado_2,diagnostico_relacionado_3,tipo_diagnostico_principal,valor_consulta,valor_cuota_moderadora,valor_neto_pagar,numero_contrato,periodo_facturado,numero_cuenta
            FROM (
            SELECT 
            r.cod_radicacion AS numero_radicado,
            CONCAT('AC',r.cod_radicacion_ct) AS nombre_archivo,
            0 AS numero_fila,
            r.id AS consecutivo_cargue,
            ax.numerofactura AS numero_factura,
            e.cod_habilitacion  AS codigo_prestador,
            axx.tipoidentificacion AS tipo_identificacion,
            axx.identificacion AS numero_identificacion,
            axx.fechaConsulta AS fecha_consulta,
            axx.numeroAutorizacion AS numero_autorizacion,
            axx.codigoConsulta AS codigo_consulta,
            axx.finalidadConsulta AS finalidad,
            axx.codigoCausaExterna AS causa_externa,
            axx.diagnosticoPrincipal AS diagnostico_principal,
            axx.diagnostico1 AS diagnostico_relacionado_1,
            axx.diagnostico2 AS diagnostico_relacionado_2,
            axx.diagnostico3 AS diagnostico_relacionado_3,
            axx.tipoDiagnosticoPrincipal AS tipo_diagnostico_principal,
            axx.valorConsulta AS valor_consulta,
            axx.copago AS valor_cuota_moderadora,
            axx.valorNeto AS valor_neto_pagar,
            ax.numerocontrato AS numero_contrato,
            DATE_FORMAT(ax.fechainicio,'%Y%m') AS periodo_facturado,
            NULL AS numero_cuenta,
            @na:=CONCAT('AC',r.cod_radicacion_ct) AS a0
            FROM 
            rs_rips_radicados r,
            ripsaf ax, 
            ripsac axx, 
            rs_entidades e
            WHERE
            r.estado_radicacion = 'Confirmado'
            AND ax.id = r.id
            AND axx.id = r.id
            AND axx.numeroFactura = ax.numeroFactura
            AND e.id = r.rs_entidad_id
            -- AND r.id IN (9739,10146)

            ORDER BY r.cod_radicacion, ax.numerofactura
            ) t
            ORDER BY t.periodo_facturado, t.numero_radicado, t.nombre_archivo, t.numero_fila;
            ", 
            
            "
            SELECT 
            numero_radicado,nombre_archivo,numero_fila,consecutivo_cargue,numero_factura,codigo_prestador,tipo_identificacion,numero_identificacion,fecha_procedimiento,numero_autorizacion,codigo_procedimiento,ambito,finalidad,personal,diagnostico_principal,diagnostico_relacionado,complicacion,forma_realizacion_acto_qx,valor_procedimiento,numero_cuenta,numero_contrato,periodo_facturado
            FROM (
            SELECT 
            r.cod_radicacion AS numero_radicado,
            CONCAT('AP',r.cod_radicacion_ct) AS nombre_archivo,
            0 AS numero_fila,
            r.id AS consecutivo_cargue,
            ax.numerofactura AS numero_factura,
            e.cod_habilitacion  AS codigo_prestador,
            axx.tipoidentificacion AS tipo_identificacion,
            axx.identificacion AS numero_identificacion,
            axx.fechaProcedimiento AS fecha_procedimiento,
            axx.numeroAutorizacion AS numero_autorizacion,
            axx.codigoProcedimiento AS codigo_procedimiento,
            axx.ambitoProcedimiento AS ambito,
            axx.finalidadProcedimiento AS finalidad,
            axx.personalAtiende AS personal,
            axx.diagnostico AS diagnostico_principal,
            axx.diagnostico1 AS diagnostico_relacionado,
            axx.diagnosticoComplicacion AS complicacion,
            axx.actoQuirurgico AS forma_realizacion_acto_qx,
            axx.valorProcedimiento AS valor_procedimiento,
            NULL AS numero_cuenta,
            ax.numerocontrato AS numero_contrato,
            DATE_FORMAT(ax.fechainicio,'%Y%m') AS periodo_facturado,
            @na:=CONCAT('AP',r.cod_radicacion_ct) AS a0
            FROM 
            rs_rips_radicados r,
            ripsaf ax, 
            ripsap axx, 
            rs_entidades e
            WHERE
            r.estado_radicacion = 'Confirmado'
            AND ax.id = r.id
            AND axx.id = r.id
            AND axx.numeroFactura = ax.numeroFactura
            AND e.id = r.rs_entidad_id
            -- AND r.id IN (9739,10146)

            ORDER BY r.cod_radicacion, ax.numerofactura
            ) t
            ORDER BY t.periodo_facturado, t.numero_radicado, t.nombre_archivo, t.numero_fila;
            ",

            "
            SELECT 
            numero_radicado,nombre_archivo,numero_fila,consecutivo_cargue,numero_factura,codigo_prestador,tipo_identificacion,numero_identificacion,via_ingreso,fecha_ingreso,hora_ingreso,numero_autorizacion,causa_externa,diagnostico_principal_ingreso,diagnostico_principal_egreso,diagnostico_relacionado_1,diagnostico_relacionado_2,diagnostico_relacionado_3,diagnostico_complicacion,estado_salida,diagnostico_causa_muerte,fecha_egreso,hora_egreso,numero_cuenta,numero_contrato,periodo_facturado
            FROM (
            SELECT 
            r.cod_radicacion AS numero_radicado,
            CONCAT('AH',r.cod_radicacion_ct) AS nombre_archivo,
            0 AS numero_fila,
            r.id AS consecutivo_cargue,
            ax.numerofactura AS numero_factura,
            e.cod_habilitacion  AS codigo_prestador,
            axx.tipoidentificacion AS tipo_identificacion,
            axx.identificacion AS numero_identificacion,
            codigoViaIngreso AS via_ingreso,
            fechaIngreso AS fecha_ingreso,
            horaIngreso AS hora_ingreso,
            numeroAutorizacion AS numero_autorizacion,
            codigoCausaExterna AS causa_externa,
            diagnosticoIngreso AS diagnostico_principal_ingreso,
            diagnosticoEgreso AS diagnostico_principal_egreso,
            diagnostico1 AS diagnostico_relacionado_1,
            diagnostico2 AS diagnostico_relacionado_2,
            diagnostico3 AS diagnostico_relacionado_3,
            codigoComplicacion AS diagnostico_complicacion,
            estadoSalida AS estado_salida,
            causaMuerte AS diagnostico_causa_muerte,
            fechaEgreso AS fecha_egreso,
            horaEgreso AS hora_egreso,
            NULL AS numero_cuenta,
            ax.numerocontrato AS numero_contrato,
            DATE_FORMAT(ax.fechainicio,'%Y%m') AS periodo_facturado,
            @na:=CONCAT('AH',r.cod_radicacion_ct) AS a0
            FROM 
            rs_rips_radicados r,
            ripsaf ax, 
            ripsah axx, 
            rs_entidades e
            WHERE
            r.estado_radicacion = 'Confirmado'
            AND ax.id = r.id
            AND axx.id = r.id
            AND axx.numeroFactura = ax.numeroFactura
            AND e.id = r.rs_entidad_id
            -- AND r.id IN (9739,10146)

            ORDER BY r.cod_radicacion, ax.numerofactura
            ) t
            ORDER BY t.periodo_facturado, t.numero_radicado, t.nombre_archivo, t.numero_fila;
            ",

            "
            SELECT 
            numero_radicado,nombre_archivo,numero_fila,consecutivo_cargue,numero_factura,codigo_prestador,tipo_identificacion,numero_identificacion,numero_autorizacion,codigo_medicamento,tipo_medicamento,nombre_generico,forma_farmaceutica,concentracion_medicamento,unidad_medida_medicamento,numero_unidades,valor_unitario,valor_total_medicamento,numero_cuenta,numero_contrato,periodo_facturado
            FROM (
            SELECT 
            r.cod_radicacion AS numero_radicado,
            CONCAT('AM',r.cod_radicacion_ct) AS nombre_archivo,
            0 AS numero_fila,
            r.id AS consecutivo_cargue,
            ax.numerofactura AS numero_factura,
            e.cod_habilitacion  AS codigo_prestador,
            axx.tipoidentificacion AS tipo_identificacion,
            axx.identificacion AS numero_identificacion,
            numeroAutorizacion AS numero_autorizacion,
            codigoMedicamento AS codigo_medicamento,
            tipoMedicamento AS tipo_medicamento,
            nombreGenerico AS nombre_generico,
            formaFarmaceutica AS forma_farmaceutica,
            concentracionMedicamento AS concentracion_medicamento,
            unidadMedida AS unidad_medida_medicamento,
            numeroUnidad AS numero_unidades,
            valorUnitario AS valor_unitario,
            valorTotal AS valor_total_medicamento,
            NULL AS numero_cuenta,
            ax.numerocontrato AS numero_contrato,
            DATE_FORMAT(ax.fechainicio,'%Y%m') AS periodo_facturado,
            @na:=CONCAT('AM',r.cod_radicacion_ct) AS a0
            FROM 
            rs_rips_radicados r,
            ripsaf ax, 
            ripsam axx, 
            rs_entidades e
            WHERE
            r.estado_radicacion = 'Confirmado'
            AND ax.id = r.id
            AND axx.id = r.id
            AND axx.numeroFactura = ax.numeroFactura
            AND e.id = r.rs_entidad_id
            -- AND r.id IN (9739,10146)
            
            ORDER BY r.cod_radicacion, ax.numerofactura
            ) t
            ORDER BY t.periodo_facturado, t.numero_radicado, t.nombre_archivo, t.numero_fila;
            ",

            "
            SELECT 
            numero_radicado,nombre_archivo,numero_fila,consecutivo_cargue,numero_factura,codigo_prestador,tipo_identificacion,numero_identificacion,fecha_nacimiento,hora_nacimiento,edad_gestacional,control_prenatal,sexo,peso,diagnostico_recien_nacido,diagnostico_causa_muerte,fecha_muerte_recien_nacido,hora_muerte_recien_nacido,numero_cuenta,numero_contrato,periodo_facturado
            FROM (
            SELECT 
            r.cod_radicacion AS numero_radicado,
            CONCAT('AN',r.cod_radicacion_ct) AS nombre_archivo,
            0 AS numero_fila,
            r.id AS consecutivo_cargue,
            ax.numerofactura AS numero_factura,
            e.cod_habilitacion  AS codigo_prestador,
            axx.tipoidentificacion AS tipo_identificacion,
            axx.identificacion AS numero_identificacion,
            fechaNacimiento AS fecha_nacimiento,
            horaNacimiento AS hora_nacimiento,
            edadGestacion AS edad_gestacional,
            controlPrenatal AS control_prenatal,
            genero AS sexo,
            peso AS peso,
            diagnostico AS diagnostico_recien_nacido,
            diagnosticoMuerte AS diagnostico_causa_muerte,
            fechaMuerte AS fecha_muerte_recien_nacido,
            fechaMuerte AS hora_muerte_recien_nacido,
            NULL AS numero_cuenta,
            ax.numerocontrato AS numero_contrato,
            DATE_FORMAT(ax.fechainicio,'%Y%m') AS periodo_facturado,
            @na:=CONCAT('AN',r.cod_radicacion_ct) AS a0
            FROM 
            rs_rips_radicados r,
            ripsaf ax, 
            ripsan axx, 
            rs_entidades e
            WHERE
            r.estado_radicacion = 'Confirmado'
            AND ax.id = r.id
            AND axx.id = r.id
            AND axx.numeroFactura = ax.numeroFactura
            AND e.id = r.rs_entidad_id
            -- AND r.id IN (9739,10146)
            
            ORDER BY r.cod_radicacion, ax.numerofactura
            ) t
            ORDER BY t.periodo_facturado, t.numero_radicado, t.nombre_archivo, t.numero_fila;
            ",

            "
            SELECT 
            numero_radicado,nombre_archivo,numero_fila,consecutivo_cargue,numero_factura,codigo_prestador,tipo_identificacion,numero_identificacion,numero_autorizacion,tipo_servicio,codigo_servicio,nombre_servicio,cantidad,valor_unitario,valor_total,numero_cuenta,numero_contrato,periodo_facturado
            FROM (
            SELECT 
            r.cod_radicacion AS numero_radicado,
            CONCAT('AT',r.cod_radicacion_ct) AS nombre_archivo,
            0 AS numero_fila,
            r.id AS consecutivo_cargue,
            ax.numerofactura AS numero_factura,
            e.cod_habilitacion  AS codigo_prestador,
            axx.tipoidentificacion AS tipo_identificacion,
            axx.identificacion AS numero_identificacion,
            numeroAutorizacion AS numero_autorizacion,
            tipoServicio AS tipo_servicio,
            codigoServicio AS codigo_servicio,
            nombreServicio AS nombre_servicio,
            cantidad AS cantidad,
            valorUnitario AS valor_unitario,
            valorTotal AS valor_total,
            NULL AS numero_cuenta,
            ax.numerocontrato AS numero_contrato,
            DATE_FORMAT(ax.fechainicio,'%Y%m') AS periodo_facturado,
            @na:=CONCAT('AT',r.cod_radicacion_ct) AS a0
            FROM 
            rs_rips_radicados r,
            ripsaf ax, 
            ripsat axx, 
            rs_entidades e
            WHERE
            r.estado_radicacion = 'Confirmado'
            AND ax.id = r.id
            AND axx.id = r.id
            AND axx.numeroFactura = ax.numeroFactura
            AND e.id = r.rs_entidad_id
            -- AND r.id IN (9739,10146)
            
            ORDER BY r.cod_radicacion, ax.numerofactura
            ) t
            ORDER BY t.periodo_facturado, t.numero_radicado, t.nombre_archivo, t.numero_fila
            ",

            "
            SELECT 
            numero_radicado,nombre_archivo,numero_fila,consecutivo_cargue,numero_factura,codigo_prestador,tipo_identificacion,numero_identificacion,fecha_ingreso,hora_ingreso,numero_autorizacion,causa_externa,diagnostico_salida,diagnostico_relacionado_1,diagnostico_relacionado_2,diagnostico_relacionado_3,destino_usuario,estado_salida,causa_basica_muerte,fecha_salida,hora_salida,numero_cuenta,numero_contrato,periodo_facturado
            FROM (
            SELECT 
            r.cod_radicacion AS numero_radicado,
            CONCAT('AU',r.cod_radicacion_ct) AS nombre_archivo,
            0 AS numero_fila,
            r.id AS consecutivo_cargue,
            ax.numerofactura AS numero_factura,
            e.cod_habilitacion  AS codigo_prestador,
            axx.tipoidentificacion AS tipo_identificacion,
            axx.identificacion AS numero_identificacion,
            fechaIngreso AS fecha_ingreso,
            horaIngreso AS hora_ingreso,
            numeroAutorizacion AS numero_autorizacion,
            causaExterna AS causa_externa,
            diagnostico AS diagnostico_salida,
            diagnostico1 AS diagnostico_relacionado_1,
            diagnostico2 AS diagnostico_relacionado_2,
            diagnostico3 AS diagnostico_relacionado_3,
            referencia AS destino_usuario,
            estadoSalida AS estado_salida,
            causaMuerte AS causa_basica_muerte,
            fechaSalida AS fecha_salida,
            horaSalida AS hora_salida,
            NULL AS numero_cuenta,
            ax.numerocontrato AS numero_contrato,
            DATE_FORMAT(ax.fechainicio,'%Y%m') AS periodo_facturado,
            @na:=CONCAT('AU',r.cod_radicacion_ct) AS a0
            FROM 
            rs_rips_radicados r,
            ripsaf ax, 
            ripsau axx, 
            rs_entidades e
            WHERE
            r.estado_radicacion = 'Confirmado'
            AND ax.id = r.id
            AND axx.id = r.id
            AND axx.numeroFactura = ax.numeroFactura
            AND e.id = r.rs_entidad_id
            -- AND r.id IN (9739,10146)
            
            ORDER BY r.cod_radicacion, ax.numerofactura
            ) t
            ORDER BY t.periodo_facturado, t.numero_radicado, t.nombre_archivo, t.numero_fila;
            ",

            "
            SELECT 
            numero_radicado,nombre_archivo,
            numero_fila,
            consecutivo_cargue,tipo_identificacion,numero_identificacion,codigo_prestador,tipo_usuario,primer_apellido,segundo_apellido,primer_nombre,segundo_nombre,edad,unidad_medida_edad,sexo,codigo_departamento,codigo_municipio,zona,numero_cuenta,numero_contrato,periodo_facturado
            FROM (
            SELECT DISTINCT
            numero_radicado,nombre_archivo,
            0 AS numero_fila,
            consecutivo_cargue,tipo_identificacion,numero_identificacion,codigo_prestador,tipo_usuario,primer_apellido,segundo_apellido,primer_nombre,segundo_nombre,edad,unidad_medida_edad,sexo,codigo_departamento,codigo_municipio,zona,numero_cuenta,numero_contrato,periodo_facturado,
            @na:=CONCAT('US', a0) AS a1
            FROM (
            SELECT 
            r.cod_radicacion AS numero_radicado,
            CONCAT('US',r.cod_radicacion_ct) AS nombre_archivo,
            0 AS numero_fila,
            r.id AS consecutivo_cargue,
            axx.tipoidentificacion AS tipo_identificacion,
            axx.identificacion AS numero_identificacion,
            e.cod_habilitacion  AS codigo_prestador,
            tipoUsuario AS tipo_usuario,
            primerApellido AS primer_apellido,
            segundoApellido AS segundo_apellido,
            primerNombre AS primer_nombre,
            segundoNombre AS segundo_nombre,
            edad AS edad,
            medidaEdad AS unidad_medida_edad,
            genero AS sexo,
            codigoDepartamento AS codigo_departamento,
            codigoMunicipio AS codigo_municipio,
            zona AS zona,
            NULL AS numero_cuenta,
            ax.numerocontrato AS numero_contrato,
            DATE_FORMAT(ax.fechainicio,'%Y%m') AS periodo_facturado,
            cod_radicacion_ct AS a0
            FROM 
            rs_rips_radicados r,
            ripsaf ax, 
            ripsac axx, 
            ripsus axxx, 
            rs_entidades e
            WHERE
            r.estado_radicacion = 'Confirmado'
            AND ax.id = r.id
            AND axx.id = r.id
            AND axx.numeroFactura = ax.numeroFactura
            AND e.id = r.rs_entidad_id
            AND axxx.id = axx.id
            AND axxx.tipoidentificacion = axx.tipoidentificacion
            AND axxx.identificacion = axx.identificacion
            -- AND r.id IN (9739,10146)
            UNION
            SELECT 
            r.cod_radicacion AS numero_radicado,
            CONCAT('US',r.cod_radicacion_ct) AS nombre_archivo,
            0 AS numero_fila,
            r.id AS consecutivo_cargue,
            axx.tipoidentificacion AS tipo_identificacion,
            axx.identificacion AS numero_identificacion,
            e.cod_habilitacion  AS codigo_prestador,
            tipoUsuario AS tipo_usuario,
            primerApellido AS primer_apellido,
            segundoApellido AS segundo_apellido,
            primerNombre AS primer_nombre,
            segundoNombre AS segundo_nombre,
            edad AS edad,
            medidaEdad AS unidad_medida_edad,
            genero AS sexo,
            codigoDepartamento AS codigo_departamento,
            codigoMunicipio AS codigo_municipio,
            zona AS zona,
            NULL AS numero_cuenta,
            ax.numerocontrato AS numero_contrato,
            DATE_FORMAT(ax.fechainicio,'%Y%m') AS periodo_facturado,
            cod_radicacion_ct AS a0
            FROM 
            rs_rips_radicados r,
            ripsaf ax, 
            ripsap axx, 
            ripsus axxx, 
            rs_entidades e
            WHERE
            r.estado_radicacion = 'Confirmado'
            AND ax.id = r.id
            AND axx.id = r.id
            AND axx.numeroFactura = ax.numeroFactura
            AND e.id = r.rs_entidad_id
            AND axxx.id = axx.id
            AND axxx.tipoidentificacion = axx.tipoidentificacion
            AND axxx.identificacion = axx.identificacion
            -- AND r.id IN (9739,10146)
            UNION
            SELECT 
            r.cod_radicacion AS numero_radicado,
            CONCAT('US',r.cod_radicacion_ct) AS nombre_archivo,
            0 AS numero_fila,
            r.id AS consecutivo_cargue,
            axx.tipoidentificacion AS tipo_identificacion,
            axx.identificacion AS numero_identificacion,
            e.cod_habilitacion  AS codigo_prestador,
            tipoUsuario AS tipo_usuario,
            primerApellido AS primer_apellido,
            segundoApellido AS segundo_apellido,
            primerNombre AS primer_nombre,
            segundoNombre AS segundo_nombre,
            edad AS edad,
            medidaEdad AS unidad_medida_edad,
            genero AS sexo,
            codigoDepartamento AS codigo_departamento,
            codigoMunicipio AS codigo_municipio,
            zona AS zona,
            NULL AS numero_cuenta,
            ax.numerocontrato AS numero_contrato,
            DATE_FORMAT(ax.fechainicio,'%Y%m') AS periodo_facturado,
            cod_radicacion_ct AS a0
            FROM 
            rs_rips_radicados r,
            ripsaf ax, 
            ripsam axx, 
            ripsus axxx, 
            rs_entidades e
            WHERE
            r.estado_radicacion = 'Confirmado'
            AND ax.id = r.id
            AND axx.id = r.id
            AND axx.numeroFactura = ax.numeroFactura
            AND e.id = r.rs_entidad_id
            AND axxx.id = axx.id
            AND axxx.tipoidentificacion = axx.tipoidentificacion
            AND axxx.identificacion = axx.identificacion
            -- AND r.id IN (9739,10146)
            UNION
            SELECT 
            r.cod_radicacion AS numero_radicado,
            CONCAT('US',r.cod_radicacion_ct) AS nombre_archivo,
            0 AS numero_fila,
            r.id AS consecutivo_cargue,
            axx.tipoidentificacion AS tipo_identificacion,
            axx.identificacion AS numero_identificacion,
            e.cod_habilitacion  AS codigo_prestador,
            tipoUsuario AS tipo_usuario,
            primerApellido AS primer_apellido,
            segundoApellido AS segundo_apellido,
            primerNombre AS primer_nombre,
            segundoNombre AS segundo_nombre,
            edad AS edad,
            medidaEdad AS unidad_medida_edad,
            axxx.genero AS sexo,
            codigoDepartamento AS codigo_departamento,
            codigoMunicipio AS codigo_municipio,
            zona AS zona,
            NULL AS numero_cuenta,
            ax.numerocontrato AS numero_contrato,
            DATE_FORMAT(ax.fechainicio,'%Y%m') AS periodo_facturado,
            cod_radicacion_ct AS a0
            FROM 
            rs_rips_radicados r,
            ripsaf ax, 
            ripsan axx, 
            ripsus axxx, 
            rs_entidades e
            WHERE
            r.estado_radicacion = 'Confirmado'
            AND ax.id = r.id
            AND axx.id = r.id
            AND axx.numeroFactura = ax.numeroFactura
            AND e.id = r.rs_entidad_id
            AND axxx.id = axx.id
            AND axxx.tipoidentificacion = axx.tipoidentificacion
            AND axxx.identificacion = axx.identificacion
            -- AND r.id IN (9739,10146)
            UNION
            SELECT 
            r.cod_radicacion AS numero_radicado,
            CONCAT('US',r.cod_radicacion_ct) AS nombre_archivo,
            0 AS numero_fila,
            r.id AS consecutivo_cargue,
            axx.tipoidentificacion AS tipo_identificacion,
            axx.identificacion AS numero_identificacion,
            e.cod_habilitacion  AS codigo_prestador,
            tipoUsuario AS tipo_usuario,
            primerApellido AS primer_apellido,
            segundoApellido AS segundo_apellido,
            primerNombre AS primer_nombre,
            segundoNombre AS segundo_nombre,
            edad AS edad,
            medidaEdad AS unidad_medida_edad,
            axxx.genero AS sexo,
            codigoDepartamento AS codigo_departamento,
            codigoMunicipio AS codigo_municipio,
            zona AS zona,
            NULL AS numero_cuenta,
            ax.numerocontrato AS numero_contrato,
            DATE_FORMAT(ax.fechainicio,'%Y%m') AS periodo_facturado,
            cod_radicacion_ct AS a0
            FROM 
            rs_rips_radicados r,
            ripsaf ax, 
            ripsau axx, 
            ripsus axxx, 
            rs_entidades e
            WHERE
            r.estado_radicacion = 'Confirmado'
            AND ax.id = r.id
            AND axx.id = r.id
            AND axx.numeroFactura = ax.numeroFactura
            AND e.id = r.rs_entidad_id
            AND axxx.id = axx.id
            AND axxx.tipoidentificacion = axx.tipoidentificacion
            AND axxx.identificacion = axx.identificacion
            -- AND r.id IN (9739,10146)
            UNION
            SELECT 
            r.cod_radicacion AS numero_radicado,
            CONCAT('US',r.cod_radicacion_ct) AS nombre_archivo,
            0 AS numero_fila,
            r.id AS consecutivo_cargue,
            axx.tipoidentificacion AS tipo_identificacion,
            axx.identificacion AS numero_identificacion,
            e.cod_habilitacion  AS codigo_prestador,
            tipoUsuario AS tipo_usuario,
            primerApellido AS primer_apellido,
            segundoApellido AS segundo_apellido,
            primerNombre AS primer_nombre,
            segundoNombre AS segundo_nombre,
            edad AS edad,
            medidaEdad AS unidad_medida_edad,
            axxx.genero AS sexo,
            codigoDepartamento AS codigo_departamento,
            codigoMunicipio AS codigo_municipio,
            zona AS zona,
            NULL AS numero_cuenta,
            ax.numerocontrato AS numero_contrato,
            DATE_FORMAT(ax.fechainicio,'%Y%m') AS periodo_facturado,
            cod_radicacion_ct AS a0
            FROM 
            rs_rips_radicados r,
            ripsaf ax, 
            ripsat axx, 
            ripsus axxx, 
            rs_entidades e
            WHERE
            r.estado_radicacion = 'Confirmado'
            AND ax.id = r.id
            AND axx.id = r.id
            AND axx.numeroFactura = ax.numeroFactura
            AND e.id = r.rs_entidad_id
            AND axxx.id = axx.id
            AND axxx.tipoidentificacion = axx.tipoidentificacion
            AND axxx.identificacion = axx.identificacion
            -- AND r.id IN (9739,10146)
            UNION
            SELECT 
            r.cod_radicacion AS numero_radicado,
            CONCAT('US',r.cod_radicacion_ct) AS nombre_archivo,
            0 AS numero_fila,
            r.id AS consecutivo_cargue,
            axx.tipoidentificacion AS tipo_identificacion,
            axx.identificacion AS numero_identificacion,
            e.cod_habilitacion  AS codigo_prestador,
            tipoUsuario AS tipo_usuario,
            primerApellido AS primer_apellido,
            segundoApellido AS segundo_apellido,
            primerNombre AS primer_nombre,
            segundoNombre AS segundo_nombre,
            edad AS edad,
            medidaEdad AS unidad_medida_edad,
            axxx.genero AS sexo,
            codigoDepartamento AS codigo_departamento,
            codigoMunicipio AS codigo_municipio,
            zona AS zona,
            NULL AS numero_cuenta,
            ax.numerocontrato AS numero_contrato,
            DATE_FORMAT(ax.fechainicio,'%Y%m') AS periodo_facturado,
            cod_radicacion_ct AS a0
            FROM 
            rs_rips_radicados r,
            ripsaf ax, 
            ripsah axx, 
            ripsus axxx, 
            rs_entidades e
            WHERE
            r.estado_radicacion = 'Confirmado'
            AND ax.id = r.id
            AND axx.id = r.id
            AND axx.numeroFactura = ax.numeroFactura
            AND e.id = r.rs_entidad_id
            AND axxx.id = axx.id
            AND axxx.tipoidentificacion = axx.tipoidentificacion
            AND axxx.identificacion = axx.identificacion
            -- AND r.id IN (9739,10146)
            
            ORDER BY 1
            ) tt ) t
            ORDER BY t.periodo_facturado, t.numero_radicado, t.nombre_archivo, t.numero_fila;
            "
        );
        // return count($array);
        return $array[$id];
    }

}
