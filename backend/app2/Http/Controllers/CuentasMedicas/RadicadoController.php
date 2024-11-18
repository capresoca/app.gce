<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Capresoca\CuentasMedicas\CargarFacturasFromRips;
use App\Http\Controllers\Controller;
use App\Http\Repositories\CuentasMedicas\RadicadosRepository;
use App\Http\Repositories\Niif\ComdiarioRespository;
use App\Http\Requests\AuditoriaCuentas\RadicadosAsignadoRequest;
use App\Http\Requests\CuentasMedicas\RadicadoRequest;
use App\Http\Resources\CuentasMedicas\RadicadoResource;
use App\Jobs\ProcesarRips;
use App\Models\CuentasMedicas\CmFactsdevuelta;
use App\Models\CuentasMedicas\CmRadicado;
use App\Models\RedServicios\RsEntidade;
use App\Models\RedServicios\RsPlanescobertura;
use App\Models\Riips\RsRipsRadicado;
use App\Traits\CuentasMedicasTrazaTrait;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\ValidationException;

use App\Capresoca\CuentasMedicas\CargarConsultasFromRips;
use App\Capresoca\CuentasMedicas\CargarHospitalizacionesFromRips;
use App\Capresoca\CuentasMedicas\CargarMedicamentosFromRips;
use App\Capresoca\CuentasMedicas\CargarNacimientosFromRips;
use App\Capresoca\CuentasMedicas\CargarOtrosFromRips;
use App\Capresoca\CuentasMedicas\CargarProcedimientosFromRips;
use App\Capresoca\CuentasMedicas\CargarUrgenciasFromRips;
use App\Capresoca\CuentasMedicas\CargarUsuariosFromRips;
use Illuminate\Support\Facades\Log;

class RadicadoController extends Controller
{
    /**
     * @param $radicado
     * @return mixed
     */
    public static function getLoad($radicado)
    {
        return $radicado->load(CmRadicado::allRelations());
    }

    public function index()
    {
        if (Input::get('per_page')) {
            return RadicadoResource::collection(CmRadicado::select('cm_radicados.*',
                'rs_rips_radicados.cod_radicacion_ct', 'rs_planescoberturas.nombre')
                ->leftJoin('rs_rips_radicados', 'rs_rips_radicados.id', '=', 'cm_radicados.rs_rip_radicado_id')
                ->leftJoin('rs_planescoberturas','rs_planescoberturas.id','=','cm_radicados.rs_contrato_id')
                ->pimp()->orderBy('consecutivo', 'desc')->paginate(Input::get('per_page')));
        }

        return RadicadoResource::collection(CmRadicado::select('cm_radicados.*',
            'rs_rips_radicados.cod_radicacion_ct', 'rs_planescoberturas.nombre')
            ->leftJoin('rs_rips_radicados', 'rs_rips_radicados.id', '=', 'cm_radicados.rs_rip_radicado_id')
            ->leftJoin('rs_planescoberturas','rs_planescoberturas.id','=','cm_radicados.rs_contrato_id')
            ->pimp()->orderBy('consecutivo', 'desc')->get());
    }

    public function store(RadicadoRequest $request)
    {
        try {
            
            Log::info('Registro X : ',array($this));
            
            
            $this->validateCapita($request);
            DB::beginTransaction();
            $radicado = CmRadicado::create($request->all());
            self::getLoad($radicado);
            $rips = RsRipsRadicado::find($request->rips_id);
            if ($rips->tipo_facturacion !== 'Capita') {
                $comdiarioRepository = new ComdiarioRespository();
                if ($radicado->nombre_estado === 'Radicado') {
                    $radicado['valorTotalNeto'] = $request->valorNetoTotal;
                    $comdiarioRepository->createComcuentaradicada($radicado);
                }
            }
            DB::commit();
            $estadoRad = new RadicadosRepository();
            $estadoRad->guardar([
                'cm_radicado_id' => $radicado->id,
                'cm_estadosrad_id' => 1,
                'gs_usuario_id' => Auth::user()->id,
                'aceptado' => '1',
            ]);
            $rips->estado_radicacion = 'Confirmado';
            //ProcesarRips::dispatch($rips, $radicado)->onQueue('archivos');
            
            Log::info('Registro Antes 000 : '.$radicado->id,array($radicado));
            
            try {
                Log::info('Registro Antes: '.$radicado->id,array($radicado));
                self::registrarFacturas($rips, $radicado);
                Log::info('Registro Luego: '.$radicado->id,array($radicado));
            } catch (Exception $exception) {
                Log::info('Error GRAVE: '.$radicado->id,array($radicado));
            }

            Log::info('Registro Antes 001 : '.$radicado->id,array($radicado));
            
            $rips->save();

            return response()->json([
                'data' => new RadicadoResource($radicado),
            ], 201);
        } catch (QueryException $ex) {
            Log::info('Registro Antes 0013 : '.$ex->getTraceAsString(),array($this));
            
            
            DB::rollBack();
            return response()->json([
                'errors' => [
                    [
                        'code' => $ex->getCode(),
                        'message' => $ex->getMessage()
                    ]
                ],
                'message' => 'Ocurrió un error al intentar crear el formulario'
            ], 400);
        } catch (ValidationException $ex) {
            
            Log::info('Registro Antes 0013 : '.$ex->getTraceAsString(),array($this));
            
            DB::rollBack();
            return response()->json([
                'errors' => $ex->getMessage(),
                'message' => 'Hay errores en el formulario que debe corregir antes de poder crearse'
            ], 422);
        } catch (\Exception $ex) {
            
            Log::info('Registro Antes 0013 : '.$ex->getTraceAsString(),array($this));
            
            DB::rollBack();
            if ($ex->getCode() == 0)
                abort(500, 'Ocurrió un error desconocido, póngase en contacto con el desarrollador. (Excepción: ' . $ex->getMessage() . ')');
            abort($ex->getCode(), $ex->getMessage());
        }
    }
    
    
    public static function registrarFacturas($rips, $radicado)
    {
        try {
            
            $cargadorAF = new CargarFacturasFromRips($rips);
            
            $cargadorAF->store($radicado);
            
            try {
                $cargadorAC = new CargarConsultasFromRips($rips, $radicado);
                $cargadorAC->store();
            } catch (FileNotFoundException $exception) {
                Log::info('No tiene archivo AC');
            }
            
            try {
                $cargadorAP = new CargarProcedimientosFromRips($rips, $radicado);
                $cargadorAP->store();
            } catch (FileNotFoundException $exception) {
                Log::info('No tiene archivo AP');
            }
            
            try {
                $cargadorAM = new CargarMedicamentosFromRips($rips, $radicado);
                $cargadorAM->store();
            } catch (FileNotFoundException $exception) {
                Log::info('No tiene archivo AM');
            }
            
            try {
                $cargadorAT = new CargarOtrosFromRips($rips, $radicado);
                $cargadorAT->store();
            } catch (FileNotFoundException $exception) {
                Log::info('No tiene archivo AT');
            }
            
            try {
                $cargadorAU = new CargarUrgenciasFromRips($rips, $radicado);
                $cargadorAU->store();
            } catch (FileNotFoundException $exception) {
                Log::info('No tiene archivo AU');
            }
            
            try {
                $cargadorAH = new CargarHospitalizacionesFromRips($rips, $radicado);
                $cargadorAH->store();
            } catch (FileNotFoundException $exception) {
                Log::info('No tiene archivo AH');
            }
            
            try {
                $cargadorAN = new CargarNacimientosFromRips($rips, $radicado);
                $cargadorAN->store();
            } catch (FileNotFoundException $exception) {
                Log::info('No tiene archivo AN');
            }
            
            try {
                $cargadorUS = new CargarUsuariosFromRips($rips, $radicado);
                $cargadorUS->store();
            } catch (FileNotFoundException $exception) {
                Log::info('No tiene archivo US');
            }
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }

    public function show($radicado)
    {
        $cuenta = CmRadicado::with('entidad.tercero',
            'ripRadicado',
            'contrato.contrato.entidad',
            'contrato.contrato.tipo_contrato',
            'facturas.servicios')->find($radicado);

        return $cuenta;
    }

    public function update(RadicadoRequest $request, $id)
    {
        try {
            $radicado = CmRadicado::find($id);
            $radicado->enviar_glosas = $request->enviar_glosas;
            $radicado->save();

            return new RadicadoResource($radicado);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'errors' => $exception->getMessage()
            ]);
        }
    }

    public function destroy($id) {}

    public function nuevoEstadoRadicado(Request $request)
    {
        try {
            //self::getLoad($radicado);
            $radicados = $request->ids;
            $radicado = null;
            foreach ($radicados as $key => $rad) {
                $id = (int) $rad;
                $radicado = CmRadicado::with('facturas.servicios', 'facturas.nacimientos', 'facturas.internaciones', 'estadolote')->find($id);
                $estadoRad = new RadicadosRepository();
                $data = [
                    'cm_radicado_id' => $radicado['id'],
                    'cm_estadosrad_id' => $request['cm_estadosrad_id'],
                    'gs_usuario_id' => Auth::user()['id'],
                    'descripcion' => $request['descripcion'],
                    'aceptado' => $request['aceptado'],
                ];
                $estadoRad->validar($data);

                if ($request['cm_estadosrad_id'] === 7 && ($radicado->estadolote['estado'] === 'Radicado')) {
                    $facturasCopy = $radicado->facturas;
                    foreach ($facturasCopy as $key => $factura) {
                        $facturaDevuelta = $this->getDataFacDevuelta($factura);
                        CmFactsdevuelta::create($facturaDevuelta);
                        if (count($factura['internaciones']) > 0) {
                            foreach ($factura->internaciones as $internacione) {
                                $internacione->delete();
                            }
                        }
                        if (count($factura->nacimientos) > 0) {
                            foreach ($factura->nacimientos as $nacimiento) {
                                $nacimiento->delete();
                            }
                        }
                        if (count($factura->servicios) > 0) {
                            foreach ($factura->servicios as $servicio) {
                                $servicio->delete();
                            }
                        }
                        $factura->delete();
                    }
                    $rip_id = $radicado->rs_rip_radicado_id;
                    $radicado->rs_rip_radicado_id = null;
                    $radicado->save();
                    $rips = RsRipsRadicado::find((int) $rip_id);
                    $rips->delete();
                }

                $estadoRad->guardar($data);
            }

            //if ($request->)
            //$radicado->fill($request->all());
            //$radicado->save();
            return new RadicadoResource($radicado);
        } catch (ValidationException $ex) {
            DB::rollBack();
            return response()->json([
                'errors' => $ex,
                'message' => 'Hay errores en el formulario que debe corregir antes de poder crearse'
            ], 422);
        } catch (\Exception $ex) {
            DB::rollBack();
            if ($ex->getCode() == 0)
                abort(500, 'Ocurrió un error desconocido, póngase en contacto con el desarrollador. (Excepción: ' . $ex->getMessage() . ')');
            abort($ex->getCode(), $ex->getMessage());
        }
    }

    public function scopeRipsradicados($id)
    {
        try {
            $ripsRadicados = RsRipsRadicado::where('rs_entidad_id', $id)->where('estado_radicacion', 'Validado')->get();

            return new Resource($ripsRadicados);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception,
                'msg' => 'Se ha encontrado un error',
            ]);
        }
    }

    public function scopeContratosActivos($id)
    {
        try {
            if (! $id) {
                throw new \Exception('No existen contratos activos.');
            }

            $tercero_id = RsEntidade::select('id','gn_tercero_id')->where('id',$id)->first()->gn_tercero_id;
            $contratos = RsPlanescobertura::with('contrato.entidad')
                ->whereHas('contrato', function ($query) use ($tercero_id) {
                    $query->whereHas('entidad', function ($query) use ($tercero_id) {
                        $query->whereHas('tercero', function ($query) use ($tercero_id) {
                            $query->where('id', $tercero_id);
                        });
                    });
                })->get();

            return new Resource($contratos);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception,
                'msg' => 'Se ha encontrado un error',
            ]);
        }
    }

    public function getRipsFacturas(RsRipsRadicado $rip)
    {
        try {
            $cargadorFacturas = new CargarFacturasFromRips($rip);

            $msg = 'Existe entre una a varias facturas que ya se encuentran registradas.';

            if ($cargadorFacturas->getData() === $msg) {
                throw new \Exception("$msg Por favor, revisar los rips ya cargados de la entidad.");
            }

            return new Resource($cargadorFacturas->getData());
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPdfRadicado()
    {
        try {
            $id = Input::get('id');
            //$radicado = CmRadicado::find($id);
            $radicado = DB::selectOne("SELECT a.id AS id_radicado,
                         LPAD(a.consecutivo,6,'0') AS conseradicado,
                         a.fecha,
                         d.nombre AS nombre_entidad,
                         CONCAT_WS(' ', g.abreviatura, f.identificacion) AS identidad,
                         b.cod_radicacion_ct AS radicado_entidadrad,
                         a.fecha_radicado,
                         a.periodo_inicio,
                         a.periodo_fin,
                         IF(a.rs_contrato_id IS NOT NULL,
                            CONCAT(h.numero_contrato,' (',i.nombre,')'),
                             'Sin Contrato') AS contratorad,
                         IF(a.rs_contrato_id IS NOT NULL,
                            IF(c.regimen_atendido = 2,
                                'Subsidiado','Contributivo'),
                             'N/A') AS plan_beneficiorad,
                         e.name AS usuario
                FROM cm_radicados AS a
                LEFT JOIN rs_rips_radicados AS b ON b.id = a.rs_rip_radicado_id
                LEFT JOIN rs_planescoberturas AS c ON c.id = a.rs_contrato_id
                LEFT JOIN ce_proconminutas AS h ON h.id = c.ce_proconminuta_id
                LEFT JOIN ce_tipocontratos AS i ON i.id = h.ce_tipocontrato_id
                LEFT JOIN rs_entidades AS d ON d.id = a.rs_entidad_id
                LEFT JOIN gn_terceros AS f ON f.id = d.gn_tercero_id
                LEFT JOIN gn_tipdocidentidades AS g ON g.id = f.gn_tipdocidentidad_id
                LEFT JOIN gs_usuarios AS e ON e.id = a.gs_usuario_id
                WHERE a.id = '{$id}'");
            //$radicado->load('entidad.tercero', 'ripRadicado', 'contrato.contrato.entidad', 'contrato.contrato.tipo_contrato', 'usuario');
            $facturas = DB::select("SELECT  fac.id,
                                                   fac.consecutivo as no_factura,
                                                   fac.fecha,
                                                   serv.cm_factura_id,
                                                   IF (serv.as_afiliado_id IS NOT NULL,
                                                       CONCAT_WS(' ', ma.tipo_identificacion, ma.identificacion, ma.nombre_completo),
                                                       CONCAT_WS(' ', serv.tipo_documento,serv.documento,'NO REGISTRA EN BD')
                                                   ) AS afiliado,
                                                   fac.valor_neto,
                                                   fac.valor_descuentos AS valor_descuento,
                                                   fac.valor_comision,
                                                   fac.valor_compartido
                                        FROM cm_facturas AS fac
                                        LEFT JOIN cm_facservicios AS serv ON serv.cm_factura_id = fac.id
                                        LEFT JOIN as_afiliados AS ma ON ma.id = serv.as_afiliado_id
                                        WHERE fac.cm_radicado_id = '{$id}'
                                        GROUP BY serv.cm_factura_id");
            $estados = DB::select("SELECT IF(b.cm_estadosrad_id = 1,
                                                        a.fecha_radicado, b.created_at) AS fecha_estado,
                                                IF(b.cm_estadosrad_id = 1,
                                                e.name, d.name) AS usuario,
                                                 c.estado,
                                                 b.aceptado
                                        FROM cm_radicados AS a
                                        LEFT JOIN cm_radcambios AS b ON b.cm_radicado_id = a.id
                                        LEFT JOIN cm_estadosrads AS c ON c.id = b.cm_estadosrad_id
                                        LEFT JOIN gs_usuarios AS d ON d.id = b.gs_usuario_id
                                        LEFT JOIN gs_usuarios AS e ON e.id = a.gs_usuario_id
                                        WHERE a.id = '{$id}' AND b.aceptado = '1'
                                        GROUP BY b.cm_estadosrad_id");

            $item = CmRadicado::with('facturas')->find($id);


            if (Input::get('html')) {
                return view('dompdf.radicacion');
            }

            $pdf = PDF::loadView('dompdf.radicacion',
                [
                    'radicado' => $radicado,
                    'facturas' => $facturas,
                    'estados' => $estados,
                    'item' => $item
                ]);
            $pdf->setPaper('letter', 'portrait');

            return $pdf->stream('Radicado Cuentas Medicas No. '.$item->consecutivo, ['Attachment' => 0]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function asignarAuditor(RadicadosAsignadoRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $radicado = CmRadicado::find($id);
            $radicado->fill($request->except('auditoresAsignados'));
            $radicado->auditoresAsignados()->delete();
            $radicado->auditoresAsignados()->createMany($request->auditores_asignados);
            $radicado->save();
            self::getLoad($radicado);
            DB::commit();

            return response()->json([
                'msg' => $radicado->auditoresAsignados->count() === 1
                    ? 'Se asignó un auditor al radicado.'
                    : 'Se asignaron '.$radicado->auditoresAsignados->count().' auditores al radicado.',
                'data' => new Resource($radicado),
            ], 202);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => $e,
                'msg' => $e->getMessage(),
            ], 500);
        }
    }

    public function asignar($id, Request $request)
    {
        $radicado = CmRadicado::find($id);
        $radicado->estado = $request->estado;
        $radicado->save();
        self::getLoad($radicado);

        return new Resource($radicado);
//        $workflow = Workflow::get($radicado);
//        $workflow->apply($radicado, 'asignar');
    }

    public function getContratosRadicados()
    {
        try {
            return json_decode(json_encode(DB::select("SELECT b.id, b.nombre
                                FROM cm_radicados AS a
                                LEFT JOIN rs_planescoberturas AS b ON b.id = a.rs_contrato_id
                                WHERE a.rs_contrato_id IS NOT NULL
                                GROUP BY b.id")), true);
        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * @param $factura
     * @return array
     */
    private function getDataFacDevuelta($factura): array
    {
        return [
            'id' => $factura['id'],
            'cm_radicado_id' => $factura['cm_radicado_id'],
            'consecutivo' => $factura['consecutivo'],
            'fecha' => $factura['fecha'],
            'no_contrato' => $factura['no_contrato'],
            'plan_beneficio' => $factura['plan_beneficio'],
            'valor_compartido' => $factura['valor_compartido'],
            'valor_comision' => $factura['valor_comision'],
            'valor_descuentos' => $factura['valor_descuentos'],
            'valor_neto' => $factura['valor_neto'],
            'estado' => $factura['estado'],
            'instancia' => $factura['instancia'],
            'revision_finalizada' => $factura['revision_finalizada'],
            'gs_usuario_avala_id' => $factura['gs_usuario_avala_id'],
        ];
    }

    /**
     * @param \App\Http\Requests\CuentasMedicas\RadicadoRequest $request
     * @throws \Exception
     */
    private function validateCapita(RadicadoRequest $request): void
    {
        $modalidad = RsRipsRadicado::select('id', 'tipo_facturacion')->where('id', $request['rs_rip_radicado_id'])->first()->tipo_facturacion;
        if (! is_null($request['rs_contrato_id']) && ($modalidad === 'Capita')) {
            $existeServicios = CuentasMedicasTrazaTrait::validarSiExistenServicios($request['rs_contrato_id']);

            if ($existeServicios === false) {
                throw new \Exception('El contrato seleccionado no tiene registrados los porcentajes en los servicios.', 422);
            }
        }
    }
}
