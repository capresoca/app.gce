<?php

namespace App\Http\Controllers\RecCompensacion;

use App\Capresoca\RecCompensacion\ClasificadorArchivosRec;
use App\Capresoca\RecCompensacion\ProcesoConciliacionPilaBanco;
use App\Http\Repositories\RecCompensacion\CompensacionRepository;
use App\Http\Resources\Resources\RecCompensacionesResource;
use App\Models\Aseguramiento\AsTipaportante;
use App\Models\Enums\EEstadoArchivoCompensacionRecaudo;
use App\Models\Enums\ESiNo;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleA;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleAPe;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleARe;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleI;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleILiquidacione;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleIPe;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleIPLiquidacione;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleIPTotale;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleIRe;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleIRLiquidacion;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleIRTotal;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleITotal;
use App\Models\RecCompensacion\RecRecaudoPlanillaEncabezado;
use App\Models\RedServicios\RsEntidade;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Jenssegers\Date\Date;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use function Matrix\add;

/**
 * @author Jorge Eduardo Hernández Oropeza 12/05/2020
 * Creando Soluciones Informaticas LTDA
 *
 *
 * Class RecCompensacionController
 * @package App\Http\Controllers\RecCompensacion
 */
class RecCompensacionController extends Controller
{

    protected $compensacionRepository;
    protected $directorio;
    protected $eEstadoArchivoCompensacionRecaudo;

    public function __construct(CompensacionRepository $compensacionRepository)
    {
        $this->compensacionRepository = $compensacionRepository;
        $this->directorio = Storage::disk('local')->path("descarga/liquidacion_recaudos");
        $this->eEstadoArchivoCompensacionRecaudo = [
            'CARGUE_EXITOSO' => (integer)1,
            'CARGUE_CON_INCONSISTENCIAS' => (integer)0,
            'CARGUE_CON_INCONSISTENCIAS_CONTENIDO' => (integer)2,
            'CARGUE_ACTIVO' => (integer)3
        ];
    }

    public function index()
    {
        $query = RecRecaudoPlanillaEncabezado::with('recaudo_transferencia_encabezado')->pimp();

        $per_page = Input::get('per_page');

        if ($per_page) {

            $coleccion = json_decode(Input::get('collection'), true);

            if (!is_null($coleccion['fechaInicio']) && is_null($coleccion['fechaFin'])) {
                $query->where('fecha_pago', '>=', $coleccion['fechaInicio']);
            }

            if (is_null($coleccion['fechaInicio']) && !is_null($coleccion['fechaFin'])) {
                $query->where('fecha_pago', '<=', $coleccion['fechaFin']);
            }

            if (!is_null($coleccion['fechaInicio']) && !is_null($coleccion['fechaFin'])) {
                $fechaIni = Carbon::parse($coleccion['fechaInicio']);
                $fechaFin = Carbon::parse($coleccion['fechaFin']);
                $query->whereBetween('fecha_pago', [$fechaIni, $fechaFin]);
            }

            if (!is_null($coleccion['numeroPlanilla'])) {
                $query->where('numero_planilla', $coleccion['numeroPlanilla']);
            }

            if (!is_null($coleccion['numeroDocumento'])) {
                $query->where('numero_documento', $coleccion['numeroDocumento']);
            }

            if (!is_null($coleccion['periodoPago'])) {
                $query->where('periodo_pago', $coleccion['periodoPago']);
            }

            if (!is_null($coleccion['tipoArchivo'])) {
                $query->where('tipo_archivo', $coleccion['tipoArchivo']);
            }

            if (!is_null($coleccion['estadoC'])) {
                $query->where('resultado', $coleccion['estadoC']);
            }

            if (!is_null($coleccion['otraEPS'])) {
                if ($coleccion['otraEPS'] === 1) {
                    $query->whereNotNull('consecutivo_encabezado_ac');
                } else {
                    $query->whereNull('consecutivo_encabezado_ac');
                }
            }

            return RecCompensacionesResource::collection($query->orderBy('fecha_grabado', 'desc')->paginate($per_page));
        }

        return RecCompensacionesResource::collection($query->orderBy('fecha_grabado', 'desc')->get());
    }

    public function store(Request $request)
    {
        try {

            $llaveEncavezado = $request->input('llave_encabezado') ?? null;

            list($resultado, $resultados) = ClasificadorArchivosRec::getPlanillaFTP(collect(), '');

            if ($resultado->count() <= 0) {
                throw new \Exception('No es posible realizar el proceso, no se encontraron datos.', 413);
            }

            $mapaConsolidado = $resultado->toArray();
            $diaSeleccionado = $resultados->toArray()['diaSeleccionado'];
            $terminado = $this->procesarPlanillasRecaudo($mapaConsolidado, $diaSeleccionado, null, $llaveEncavezado);

            return response()->json([
                'data' => $terminado,
                'message' => 'Proceso Exitoso.'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }

    public function getRecaudoPlanillaFTP()
    {
        $resultados = ClasificadorArchivosRec::getPlanillaFTP(collect(), 'cargue');

        return response()->json(['data' => $resultados])->setStatusCode(200);
    }

    /**
     * @param $listadoPendientes
     * @param $diaSeleccionado
     * @param null $revalidar
     * @param $llaveEncavezado
     * @return Collection
     * @throws \Exception
     */
    public function procesarPlanillasRecaudo($listadoPendientes, $diaSeleccionado, $revalidar = null, $llaveEncavezado)
    {
        $ruta = "$this->directorio/$diaSeleccionado";
        $mapaArchivos = collect();
        $mapaConsolidado = collect();
        $sdf = new Carbon();
        $fechaActual = $sdf->now()->toDateTimeString();
        $listaEncabezados = collect();
        $listaA = collect();
        $listaI = collect();
        $listaAR = collect();
        $listaIR = collect();
        $listaAP = collect();
        $listaIP = collect();
        $listaILiquidacion = collect();
        $listaIRLiquidacion = collect();
        $listaIPLiquidacion = collect();
        $listaITotales = collect();
        $listaIRTotales = collect();
        $listaIPTotales = collect();

        $mapaEncabezados = collect();

        $mapaRazonSocial = collect();
        $nombreArchivo = null;
        $nombreArchivoX = null;
        $archivoPlanilla = null;
        $encabezado = null;
        $contenido = null;
        $fecha_pago = 0;
        $numero_plailla = 2;
        $tipo_documento = 3;
        $numero_documento = 4;
        $operador = 6;
        $tipo_archivo = 7;
        $periodo_pago = 8;
        $in = null;
        $csvReader = null;
        $iEncabezadoProcesado = false;
        $iREncabezadoProcesado = false;
        $iPEncabezadoProcesado = false;
        $iLiquidacionErroneoAgregado = false;
        $resultado = null;
        $mensajeError = "";
        $errores = null;
        $archivosProcesados = collect();
        $archivosExistosos = collect();
        $archivosErroneos = collect();

        $listaTipoAportante = AsTipaportante::all()->toArray();

        $periodo = ClasificadorArchivosRec::getLinPeriodoLiquidacionXFecha($diaSeleccionado, 'Y-m-d');

        foreach ($listadoPendientes as $key => $file) {
            $archivoPlanilla = $ruta . "/" . $file['nombre_archivo'];
            if (is_file($archivoPlanilla)) {
                $nombreArchivo = File::basename($archivoPlanilla);
                $nombreArchivoX = str_replace('_A_', '_X_', $nombreArchivo);
                $nombreArchivoX = str_replace('_I_', '_X_', $nombreArchivoX);
                $nombreArchivoX = str_replace('_AR_', '_XR_', $nombreArchivoX);
                $nombreArchivoX = str_replace('_IR_', '_XR_', $nombreArchivoX);
                $nombreArchivoX = str_replace('_AP_', '_XP_', $nombreArchivoX);
                $nombreArchivoX = str_replace('_IP_', '_XP_', $nombreArchivoX);

                if (!$mapaArchivos->offsetExists($nombreArchivoX)) {
                    $mapaArchivos->put($nombreArchivoX, collect());
                }
                $mapaArchivos->offsetGet($nombreArchivoX)->push($archivoPlanilla);
            }
        }

        $listaRP = DB::table('rec_recaudo_planilla_encabezados')
            ->select('numero_planilla', 'tipo_archivo', 'tipo_documento', 'numero_documento', 'periodo_pago', 'operador')
            ->where('resultado', EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId())->get();

        $setPlanillasGuardadas = collect();

        foreach ($listaRP as $key => $item) {
            $keyItem = "$item->numero_planilla-$item->tipo_archivo-$item->tipo_documento-$item->numero_documento-$item->periodo_pago-$item->operador";
            $setPlanillasGuardadas->push($keyItem);
        }

        $setMunicipios = collect();
        $setDepartamentos = collect();
        $col = collect();

        DB::beginTransaction();
        $ultimateID = RecRecaudoPlanillaEncabezado::select('consecutivo_recaudo')->latest('consecutivo_recaudo')->first();
        if (empty($ultimateID['consecutivo_recaudo'])) {
            DB::statement("SET FOREIGN_KEY_CHECKS = 0");
            DB::statement("TRUNCATE TABLE rec_recaudo_planilla_encabezados");
            DB::statement("TRUNCATE TABLE rec_recaudo_planilla_detalle_as");
            DB::statement("TRUNCATE TABLE rec_recaudo_planilla_detalle_a_pes");
            DB::statement("TRUNCATE TABLE rec_recaudo_planilla_detalle_a_res");
            DB::statement("TRUNCATE TABLE rec_recaudo_planilla_detalle_is");
            DB::statement("TRUNCATE TABLE rec_recaudo_planilla_detalle_i_totals");
            DB::statement("TRUNCATE TABLE rec_recaudo_planilla_detalle_i_liquidaciones");
            DB::statement("TRUNCATE TABLE rec_recaudo_planilla_detalle_i_pes");
            DB::statement("TRUNCATE TABLE rec_recaudo_planilla_detalle_i_p_totales");
            DB::statement("TRUNCATE TABLE rec_recaudo_planilla_detalle_i_p_liquidaciones");
            DB::statement("TRUNCATE TABLE rec_recaudo_planilla_detalle_i_res");
            DB::statement("TRUNCATE TABLE rec_recaudo_planilla_detalle_i_r_totals");
            DB::statement("TRUNCATE TABLE rec_recaudo_planilla_detalle_i_r_liquidacions");
            DB::statement("SET FOREIGN_KEY_CHECKS = 1");
        }
        $consecutivo = (!empty($ultimateID['consecutivo_recaudo'])) ? ($ultimateID['consecutivo_recaudo']) : (0);
        foreach ($mapaArchivos->toArray() as $key => $e) {
            if (sizeof($e) !== 2) {
                if (is_null($revalidar)) {
                    throw new \Exception('Error en liquidación recaudo planillas, archivos incompletos.', 413);
                }
            }
            foreach ($e as $itemKey => $archive) {
                $name = File::name($archive);
                if (is_file($archive) && file_exists($archive)) {
                    $in = fopen($archive, 'r');
                    if (stripos($name, "_A_")) {
                        $encabezado = explode('_', $name);
                        $recEncabezado = new RecRecaudoPlanillaEncabezado();
                        if (!is_null($revalidar)) {
                            $recEncabezado = RecRecaudoPlanillaEncabezado::where('consecutivo_recaudo', $llaveEncavezado)->first();
                        } else {
                            $consecutivo = $consecutivo + 1;
                            $recEncabezado->consecutivo_recaudo = $consecutivo;
                        }
                        $recEncabezado->fecha_pago = $sdf->parse($encabezado[$fecha_pago])->format('Y-m-d'); //fecha_pago
                        $recEncabezado->numero_planilla = $encabezado[$numero_plailla]; //numero_planilla
                        $recEncabezado->tipo_documento = $encabezado[$tipo_documento]; //tipo_documento
                        $recEncabezado->numero_documento = $encabezado[$numero_documento]; //numero_documento
                        $recEncabezado->operador = $encabezado[$operador]; //operador
                        $recEncabezado->tipo_archivo = $encabezado[$tipo_archivo]; //tipo_archivo
                        $recEncabezado->periodo_pago = $encabezado[$periodo_pago]; //periodo_pago
                        $recEncabezado->fecha_descarga = $fechaActual;
                        $recEncabezado->fecha_cargue = $fechaActual;
                        $recEncabezado->usuario_grabado = Auth::user()->id;
                        $recEncabezado->fecha_grabado = $sdf->parse($fechaActual)->toDateTimeString();
                        $recEncabezado->consecutivo_periodo_liquidacion = $periodo->consecutivo_liquidacion ?? null;
                        $recEncabezado->consecutivo_encabezado_ac = $consecutivoEncabezadoAc ?? null;
                        $recEncabezado->codigo_eps_transfiere = $codigoEpsTransfiere ?? null;
                        //$recEncabezado->save();
                        Log::info('drecEncabezadoAEncabezado', [$recEncabezado]);

                        $listaEncabezados->push($recEncabezado);

                        $detalleA = new RecRecaudoPlanillaDetalleA();
                        $detalleA->consecutivo_recaudo_encabezado = $recEncabezado->consecutivo_recaudo;
                        $detalleA->nombre_archivo = $name;
                        // $detalleA->save();
                        // $csvReader = fgetcsv($gestor,567,";");
                        $csvReader = $in;
                        $resultado = $name;
                        $llave = "$recEncabezado->numero_planilla-A-$recEncabezado->tipo_documento-$recEncabezado->numero_documento-$recEncabezado->periodo_pago-$recEncabezado->operador";

                        if ($setPlanillasGuardadas->containsStrict($llave)) {
                            $mensajeError = "Error liquidación recaudo planilla: Planilla Guardada $llave";
                        }

                        while (!feof($csvReader)) {
                            $linea = fgets($csvReader);
                            if ($linea !== false) {
                                $contenido = str_replace("\r\n", '', $linea);
                                $detalleA->contenido = $contenido;
                                if ((strlen($contenido) == 567)) {
                                    $errores = $this->compensacionRepository->validarEstructuraRecaudoPlanillaA($contenido, $name, $setMunicipios, $setDepartamentos);
                                    if (($errores->isEmpty()) && empty($mensajeError)) {
                                        $detalleA->razon_social_aportante = trim(substr($contenido, 0, 200));
                                        $detalleA->tipo_identificacion_aportante = trim(substr($contenido, 200, 2));
                                        $detalleA->numero_identificacion_aportante = trim(substr($contenido, 202, 16));
                                        $detalleA->digito_verificacion_aportante = trim(substr($contenido, 218, 1));
                                        $detalleA->codigo_sucursal = trim(substr($contenido, 219, 10));
                                        $detalleA->nombre_sucursal = trim(substr($contenido, 229, 39));
                                        $detalleA->clase_aportante = trim(substr($contenido, 269, 1));
                                        $detalleA->naturaleza_juridica = trim(substr($contenido, 270, 1));
                                        $detalleA->tipo_persona = trim(substr($contenido, 271, 1));
                                        $detalleA->forma_presentacion = trim(substr($contenido, 272, 1));
                                        $detalleA->direccion_aportante = trim(substr($contenido, 273, 40));
                                        $detalleA->municipio = trim(substr($contenido, 313, 3));
                                        $detalleA->departamento = trim(substr($contenido, 316, 2));
                                        $detalleA->actividad_economica = trim(substr($contenido, 318, 4));
                                        $detalleA->telefono = trim(substr($contenido, 322, 10));
                                        $detalleA->fax = trim(substr($contenido, 332, 10));
                                        $detalleA->correo_electronico = trim(substr($contenido, 342, 60));
                                        $detalleA->numero_identificacion_representante = trim(substr($contenido, 402, 16));
                                        $detalleA->digito_verificacion_representante = trim(substr($contenido, 418, 1));
                                        $detalleA->tipo_identificacion_representante = trim(substr($contenido, 419, 2));
                                        $detalleA->primer_apellido_representante = trim(substr($contenido, 421, 20));
                                        $detalleA->segundo_apellido_representante = trim(substr($contenido, 441, 30));
                                        $detalleA->primer_nombre_representante = trim(substr($contenido, 471, 20));
                                        $detalleA->segundo_nombre_representante = trim(substr($contenido, 491, 30));
                                        $detalleA->fecha_inicio_accion = trim(substr($contenido, 521, 10));
                                        $detalleA->tipo_accion = trim(substr($contenido, 531, 1));
                                        $detalleA->fecha_terminacion_actividades = trim(substr($contenido, 532, 10));
                                        $detalleA->codigo_operador = trim(substr($contenido, 542, 2));
                                        $detalleA->periodo_pago = trim(substr($contenido, 544, 7));
                                        $detalleA->tipo_aportante = trim(substr($contenido, 551, 2));
                                        $detalleA->fecha_matricula_mercantil = trim(substr($contenido, 553, 10));
                                        $detalleA->departamento_aportante = trim(substr($contenido, 563, 2));
                                        $detalleA->sw_exonerado_pago = trim(substr($contenido, 565, 1));
                                        $detalleA->sw_caja_compensacion = trim(substr($contenido, 566, 1));
                                        Log::info('DETALLES', [$detalleA]);
                                        // $detalleA->save();
                                        $archivosExistosos->push($resultado);
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId();
                                        // $recEncabezado->save();
                                        $setPlanillasGuardadas->push($llave);
                                        Log::info('detalleAEncabezado', [$detalleA]);

                                        //Log::info('PASA SIN PROBLEMA', [$setPlanillasGuardadas, $detalleA]);
                                    } else {
                                        foreach ($errores->toArray() as $key => $r) {
                                            $mensajeError = $mensajeError . $r . ';';
                                        }
                                        $detalleA->errores = $mensajeError;
                                        // $detalleA->save();
                                        $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                    }
                                } else {
                                    $mensajeError = "Error liquidación recaudo planilla cantidad caracteres";
                                    $detalleA->errores = $mensajeError;
                                    // $detalleA->save();
                                    $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                    $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                    // $recEncabezado->save();
                                }
                                $listaA->push($detalleA);
                            }
                        }
                        $archivosProcesados->push($resultado);
                        //$csvReader = \file_get_contents($archive,false,null,0,567);
                    } elseif (stripos($name, "_I_")) {
                        $encabezado = explode('_', $name);
                        $recEncabezado = new RecRecaudoPlanillaEncabezado();
                        if (!is_null($revalidar)) {
                            $recEncabezado = RecRecaudoPlanillaEncabezado::where('consecutivo_recaudo', $llaveEncavezado)->first();
                        } else {
                            $consecutivo = $consecutivo + 1;
                            $recEncabezado->consecutivo_recaudo = $consecutivo;
                        }
                        $recEncabezado->fecha_pago = $sdf->parse($encabezado[$fecha_pago])->format('Y-m-d'); //fecha_pago
                        $recEncabezado->numero_planilla = $encabezado[$numero_plailla]; //numero_planilla
                        $recEncabezado->tipo_documento = $encabezado[$tipo_documento]; //tipo_documento
                        $recEncabezado->numero_documento = $encabezado[$numero_documento]; //numero_documento
                        $recEncabezado->operador = $encabezado[$operador]; //operador
                        $recEncabezado->tipo_archivo = $encabezado[$tipo_archivo]; //tipo_archivo
                        $recEncabezado->periodo_pago = $encabezado[$periodo_pago]; //periodo_pago
                        $recEncabezado->fecha_descarga = $fechaActual;
                        $recEncabezado->fecha_cargue = $fechaActual;
                        $recEncabezado->usuario_grabado = Auth::user()->id;
                        $recEncabezado->fecha_grabado = $sdf->parse($fechaActual)->toDateTimeString();
                        $recEncabezado->consecutivo_periodo_liquidacion = $periodo->consecutivo_liquidacion ?? null;
                        $recEncabezado->consecutivo_encabezado_ac = $consecutivoEncabezadoAc ?? null;
                        $recEncabezado->codigo_eps_transfiere = $codigoEpsTransfiere ?? null;
                        // $recEncabezado->save();
                        Log::info('encabezado_i', [$recEncabezado]);

                        $listaEncabezados->push($recEncabezado);

                        $detalleIEncabezado = new RecRecaudoPlanillaDetalleI();
                        $detalleIEncabezado->consecutivo_recaudo_encabezado = $recEncabezado->consecutivo_recaudo;
                        $detalleIEncabezado->nombre_archivo = $name;
                        $detalleIEncabezado->contenido = "";
                        $detalleIEncabezado->errores = "";
                        // $detalleIEncabezado->save();
                        $listaI->push($detalleIEncabezado);

                        $detalleITotales = new RecRecaudoPlanillaDetalleITotal();
                        $detalleITotales->consecutivo_recaudo_encabezado = $recEncabezado->consecutivo_recaudo;
                        $detalleITotales->contenido = "";
                        //$csvReader = \file_get_contents($archive,false,null,0,567);;
                        $csvReader = $in;
                        $resultado = $name;
                        $llave = "$recEncabezado->numero_planilla-I-$recEncabezado->tipo_documento-$recEncabezado->numero_documento-$recEncabezado->periodo_pago-$recEncabezado->operador";

                        if ($setPlanillasGuardadas->containsStrict($llave)) {
                            $mensajeError = "Error liquidación recaudo planilla: Planilla Guardada $llave";
                        }

                        while (!feof($csvReader)) {
                            $linea = fgets($csvReader);
                            if ($linea !== false) {
                                $contenido = str_replace("\r\n", '', $linea);
                                $detalleIEncabezado->contenido = $contenido;
                                if (!$iEncabezadoProcesado) {
                                    if (strlen($contenido) === 527) {
                                        $errores = $this->compensacionRepository->validarEstructuraRecaudoPlanillaIEncabezado($contenido, $name, $setPlanillasGuardadas, $listaTipoAportante);
                                        if ($errores->isEmpty() && empty($mensajeError)) {
                                            $detalleIEncabezado->numero_registro = trim(substr($contenido, 0, 5));
                                            $detalleIEncabezado->tipo_registro = trim(substr($contenido, 5, 1));
                                            $detalleIEncabezado->codigo_formato = trim(substr($contenido, 6, 2));
                                            $detalleIEncabezado->nit_administradora = trim(substr($contenido, 8, 16));
                                            $detalleIEncabezado->digito_verificacion_administradora = trim(substr($contenido, 24, 1));
                                            $detalleIEncabezado->razon_social_aportante = trim(substr($contenido, 25, 200));
                                            $detalleIEncabezado->tipo_identificacion_aportante = trim(substr($contenido, 225, 2));
                                            $detalleIEncabezado->numero_identificacion_aportante = trim(substr($contenido, 227, 16));
                                            $detalleIEncabezado->digito_verificacion_aportante = trim(substr($contenido, 243, 1));
                                            $detalleIEncabezado->tipo_aportante = trim(substr($contenido, 244, 2));
                                            $detalleIEncabezado->direccion_correspondencia = trim(substr($contenido, 246, 40));
                                            $detalleIEncabezado->municipio = trim(substr($contenido, 286, 3));
                                            $detalleIEncabezado->departamento = trim(substr($contenido, 289, 2));
                                            $detalleIEncabezado->telefono = trim(substr($contenido, 291, 10));
                                            $detalleIEncabezado->fax = trim(substr($contenido, 301, 10));
                                            $detalleIEncabezado->correo_electronico = trim(substr($contenido, 311, 60));
                                            $detalleIEncabezado->periodo_pago = trim(substr($contenido, 371, 7));
                                            $detalleIEncabezado->codigo_arl = trim(substr($contenido, 378, 6));
                                            $detalleIEncabezado->tipo_planilla = trim(substr($contenido, 384, 1));
                                            $detalleIEncabezado->fecha_pago_planilla = trim(substr($contenido, 385, 10));
                                            $detalleIEncabezado->fecha_pago = trim(substr($contenido, 395, 10));
                                            $detalleIEncabezado->numero_planilla = trim(substr($contenido, 405, 10));
                                            $detalleIEncabezado->numero_radicacion_pila = trim(substr($contenido, 415, 10));
                                            $detalleIEncabezado->forma_presentacion = trim(substr($contenido, 425, 1));
                                            $detalleIEncabezado->codigo_sucursal = trim(substr($contenido, 426, 10));
                                            $detalleIEncabezado->nombre_sucursal = trim(substr($contenido, 436, 40));
                                            $detalleIEncabezado->numero_empleados = trim(substr($contenido, 476, 5));
                                            $detalleIEncabezado->numero_afiliados = trim(substr($contenido, 481, 5));
                                            $detalleIEncabezado->codigo_operador = trim(substr($contenido, 486, 2));
                                            $detalleIEncabezado->modalidad_planilla = trim(substr($contenido, 488, 2));
                                            $detalleIEncabezado->dias_mora = trim(substr($contenido, 490, 3));
                                            $detalleIEncabezado->numero_registros_tipo_2 = trim(substr($contenido, 493, 8));
                                            $detalleIEncabezado->fecha_matricula_mercantil = trim(substr($contenido, 501, 10));
                                            $detalleIEncabezado->departamento_aportante = trim(substr($contenido, 511, 2));
                                            $detalleIEncabezado->sw_exonerado_pago_aportes = trim(substr($contenido, 513, 1));
                                            $detalleIEncabezado->clase_aportante = trim(substr($contenido, 514, 1));
                                            $detalleIEncabezado->naturaleza_juridica = trim(substr($contenido, 515, 1));
                                            $detalleIEncabezado->tipo_persona = trim(substr($contenido, 516, 1));
                                            $detalleIEncabezado->fecha_actualizacion = trim(substr($contenido, 517, 10));
                                            $detalleIEncabezado->nombre_archivo = $name;
                                            // $detalleIEncabezado->save();
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId();
                                            // $recEncabezado->save();
                                            $archivosExistosos->push($resultado);
                                            Log::info('detalleeIEncabezado', [$detalleIEncabezado]);
                                        } else {
                                            foreach ($errores as $item) {
                                                $mensajeError = "$mensajeError$item;";
                                            }
                                            $detalleIEncabezado->errores = $mensajeError;
                                            //$detalleIEncabezado->save();
                                            $archivosErroneos->push("$resultado, RE-> $mensajeError");
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                            // $recEncabezado->save();
                                            $iLiquidacionErroneoAgregado = true;
                                        }
                                    } else {
                                        $mensajeError = "Error liquidación planilla T1 no cumple estructura";
                                        $detalleIEncabezado->errores = $mensajeError;
                                        // $detalleIEncabezado->save();
                                        $archivosErroneos->push("$resultado, RE-> $mensajeError");
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                        $iLiquidacionErroneoAgregado = true;
                                    }
                                    $iEncabezadoProcesado = true;
                                } elseif ($this->iniciaCon($contenido, '00031') && empty($mensajeError) && (strlen($contenido) < 50)) {
                                    if (strlen($contenido) === 45) {
                                        $errores = $this->compensacionRepository->validarEstructuraTipo3Renglon31Aportantes($contenido); //CREAR MÉTODO
                                        if ($errores->isEmpty() && empty($mensajeError)) {
                                            //dd(EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId());
                                            $detalleITotales->registro_total_aportes = trim(substr($contenido, 0, 5));
                                            $detalleITotales->tipo_registro_31 = trim(substr($contenido, 5, 1));
                                            $detalleITotales->total_ibc = ((double)trim(substr($contenido, 6, 13)));
                                            $detalleITotales->total_cotizacion_obligatoria = ((double)trim(substr($contenido, 19, 13)));
                                            $detalleITotales->total_upc_adicional = ((double)trim(substr($contenido, 32, 13)));
                                            $detalleITotales->contenido = $detalleITotales->contenido . $contenido;
                                            // $detalleITotales->save();
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId();
                                            // $recEncabezado->save();
                                            $archivosExistosos->push($resultado);
                                        } else {
                                            foreach ($errores as $key => $r) {
                                                $mensajeError = $mensajeError . $r . ';';
                                            }
                                            $detalleIEncabezado->errores = $mensajeError;
                                            // $detalleIEncabezado->save();
                                            $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                            // $recEncabezado->save();
                                        }
                                    } elseif (empty($mensajeError)) {
                                        $mensajeError = "Error liquidación recaudo planilla: T3 Renglon 31, no cumple estructura";
                                        $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                        $detalleIEncabezado->errores = $mensajeError;
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                    }
                                } elseif ($this->iniciaCon($contenido, '00036') && empty($mensajeError) && (strlen($contenido) < 50)) {
                                    if (strlen($contenido) === 32) {
                                        $errores = $this->compensacionRepository->validarEstructuraTipo3Renglon36Mora($contenido); //CREAR MÉTODO
                                        if ($errores->isEmpty() && empty($mensajeError)) {
                                            $detalleITotales->registro_intereses_mora = trim(substr($contenido, 0, 5));
                                            $detalleITotales->tipo_registro_36 = trim(substr($contenido, 5, 1));
                                            $detalleITotales->dias_mora = ((integer)trim(substr($contenido, 6, 4)));
                                            $detalleITotales->valor_mora_cotizaciones = ((double)trim(substr($contenido, 10, 11)));
                                            $detalleITotales->valor_mora_upc_adicional = ((double)trim(substr($contenido, 21, 11)));
                                            $detalleITotales->contenido = $detalleITotales->contenido . $contenido;
                                            // $detalleITotales->save();
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId();
                                            // $recEncabezado->save();
                                            $archivosExistosos->push($resultado);
                                        } else {
                                            foreach ($errores as $key => $r) {
                                                $mensajeError = $mensajeError . $r . ';';
                                            }
                                            $detalleIEncabezado->errores = $mensajeError;
                                            // $detalleIEncabezado->save();
                                            $archivosErroneos->push($resultado . ", RE->" . $mensajeError);
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                            // $recEncabezado->save();
                                        }
                                    } elseif (empty($mensajeError)) {
                                        $mensajeError = "Error liquidación recaudo planilla: T3 Renglon 36, no cumple estructura";
                                        $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                        $detalleIEncabezado->errores = $mensajeError;
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        /// $recEncabezado->save();
                                    }
                                } elseif ($this->iniciaCon($contenido, '00039') && empty($mensajeError) && (strlen($contenido) < 50)) {
                                    if (strlen($contenido) === 45) {
                                        $errores = $this->compensacionRepository->validarEstructuraTipo3Renglon39Totales($contenido); //CREAR MÉTODO
                                        if (((double)($detalleITotales->total_upc_adicional + $detalleITotales->valor_mora_upc_adicional)) !== ((double)trim(substr($contenido, 32, 13)))) {
                                            $errores->push('Error liquidación recaudo planilla: T3 renglón 39 Total UPC Adicional');
                                        }
                                        if ($errores->isEmpty() && empty($mensajeError)) {
                                            $detalleITotales->registro_total_pagar = trim(substr($contenido, 0, 5));
                                            $detalleITotales->tipo_registro_39 = ((int)trim(substr($contenido, 5, 1)));
                                            $detalleITotales->total_cotizacion = ((double)trim(substr($contenido, 6, 13)));
                                            $detalleITotales->total_neto_aportes = ((double)trim(substr($contenido, 19, 13)));
                                            $detalleITotales->total_neto_upc_adicional = ((double)trim(substr($contenido, 32, 13)));
                                            $detalleITotales->contenido = $detalleITotales->contenido . $contenido;
                                            // $detalleITotales->save();
                                            $listaITotales->push($detalleITotales);
                                            $archivosExistosos->push($resultado);
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId();
                                            // $recEncabezado->save();
                                            $setPlanillasGuardadas->push($llave);
                                        } else {
                                            foreach ($errores as $key => $r) {
                                                $mensajeError = $mensajeError . $r . ';';
                                            }
                                            $detalleIEncabezado->errores = $mensajeError;
                                            // $detalleIEncabezado->save();
                                            $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                            // $recEncabezado->save();
                                        }
                                    } elseif (empty($mensajeError)) {
                                        $mensajeError = "Error liquidación recaudo planilla: T3 Renglon 39, no cumple estructura";
                                        $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                        $detalleIEncabezado->errores = $mensajeError;
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                    }
                                } elseif ($this->iniciaCon($contenido, '4') && empty($mensajeError) && (strlen($contenido) === 59) && ($detalleIEncabezado->tipo_planilla === "N")) {
                                    if (strlen($contenido) === 59) {
                                        $errores = $this->compensacionRepository->validarEstructuraTipo3Renglon4($contenido); //CREAR MÉTODO
                                        if ($errores->isEmpty() && empty($mensajeError)) {
                                            $detalleITotales->tipo_registro_4 = trim(substr($contenido, 0, 1));
                                            $detalleITotales->indicador_ugpp = trim(substr($contenido, 1, 1));
                                            $detalleITotales->acto_administrativo_ugpp = trim(substr($contenido, 2, 14));
                                            $detalleITotales->fecha_apertura_liquidacion = trim(substr($contenido, 16, 10));
                                            $detalleITotales->nombre_entidad_liquidacion = trim(substr($contenido, 26, 20));
                                            $detalleITotales->valor_pagado_sancion = ((double)trim(substr($contenido, 46, 13)));
                                            $detalleITotales->contenido = $detalleITotales->contenido . $contenido;
                                            // $detalleITotales->save();
                                            $archivosExistosos->push($resultado);
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId();
                                            // $recEncabezado->save();
                                            $setPlanillasGuardadas->push($llave);
                                        } else {
                                            foreach ($errores as $key => $r) {
                                                $mensajeError = $mensajeError . $r . ';';
                                            }
                                            $detalleIEncabezado->errores = $mensajeError;
                                            // $detalleIEncabezado->save();
                                            $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                            // $recEncabezado->save();
                                        }
                                    } elseif (empty($mensajeError)) {
                                        $mensajeError = "Error liquidación recaudo planilla: T3 Renglon 4, no cumple estructura";
                                        $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                        $detalleIEncabezado->errores = $mensajeError;
                                        // $detalleIEncabezado->save();
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                    }
                                } elseif (strlen($contenido) === 353) {
                                    if (strlen($contenido) === 353) {
                                        $errores = $this->compensacionRepository->validarEstructuraRecaudoPlanillaITipo2Liquidacion($contenido, $setDepartamentos, $setMunicipios);
                                        if ($errores->isEmpty() && empty($mensajeError)) {
                                            $detalleILiquidacion = new RecRecaudoPlanillaDetalleILiquidacione();
                                            $detalleILiquidacion->consecutivo_recaudo_encabezado = $recEncabezado->consecutivo_recaudo;
                                            $detalleILiquidacion->secuencia = trim(substr($contenido, 0, 5));
                                            $detalleILiquidacion->tipo_registro = trim(substr($contenido, 5, 1));
                                            $detalleILiquidacion->tipo_identificacion_cotizante = trim(substr($contenido, 6, 2));
                                            $detalleILiquidacion->numero_identificacion_cotizante = trim(substr($contenido, 8, 16));
                                            $detalleILiquidacion->tipo_cotizante = trim(substr($contenido, 24, 2));
                                            $detalleILiquidacion->subtipo_cotizante = trim(substr($contenido, 26, 2));
                                            $detalleILiquidacion->sw_extranjero = trim(substr($contenido, 28, 1));
                                            $detalleILiquidacion->sw_colombiano_exterior = trim(substr($contenido, 29, 1));
                                            $detalleILiquidacion->departamento = trim(substr($contenido, 30, 2));
                                            $detalleILiquidacion->municipio = trim(substr($contenido, 32, 3));
                                            $detalleILiquidacion->primer_apellido = trim(substr($contenido, 35, 20));
                                            $detalleILiquidacion->segundo_apellido = trim(substr($contenido, 55, 30));
                                            $detalleILiquidacion->primer_nombre = trim(substr($contenido, 85, 20));
                                            $detalleILiquidacion->segundo_nombre = trim(substr($contenido, 105, 30));
                                            $detalleILiquidacion->sw_ing = trim(substr($contenido, 135, 1));
                                            $detalleILiquidacion->sw_ret = trim(substr($contenido, 136, 1));
                                            $detalleILiquidacion->sw_tde = trim(substr($contenido, 137, 1));
                                            $detalleILiquidacion->sw_tae = trim(substr($contenido, 138, 1));
                                            $detalleILiquidacion->sw_vsp = trim(substr($contenido, 139, 1));
                                            $detalleILiquidacion->sw_vst = trim(substr($contenido, 140, 1));
                                            $detalleILiquidacion->sw_sln = trim(substr($contenido, 141, 1));
                                            $detalleILiquidacion->sw_ige = trim(substr($contenido, 142, 1));
                                            $detalleILiquidacion->sw_lma = trim(substr($contenido, 143, 1));
                                            $detalleILiquidacion->sw_vac_lr = trim(substr($contenido, 144, 1));
                                            $detalleILiquidacion->dias_cotizados = ((int)trim(substr($contenido, 145, 2)));
                                            $detalleILiquidacion->salario_basico = ((double)trim(substr($contenido, 147, 9)));
                                            $detalleILiquidacion->ingreso_base_cotizacion = ((double)trim(substr($contenido, 156, 9)));
                                            $detalleILiquidacion->tarifa = ((double)trim(substr($contenido, 165, 7)));
                                            $detalleILiquidacion->cotizacion_obligatoria = ((double)trim(substr($contenido, 172, 9)));
                                            $detalleILiquidacion->valor_upc_adicional = ((double)trim(substr($contenido, 181, 9)));
                                            $detalleILiquidacion->sw_correcciones = trim(substr($contenido, 190, 1));
                                            $detalleILiquidacion->sw_salario_integral = trim(substr($contenido, 191, 1));
                                            $detalleILiquidacion->sw_exonerado_aportes = trim(substr($contenido, 192, 1));
                                            $detalleILiquidacion->fecha_ingreso = trim(substr($contenido, 193, 10));
                                            $detalleILiquidacion->fecha_retiro = trim(substr($contenido, 203, 10));
                                            $detalleILiquidacion->fecha_inicio_vsp = trim(substr($contenido, 213, 10));
                                            $detalleILiquidacion->fecha_inicio_sln = trim(substr($contenido, 223, 10));
                                            $detalleILiquidacion->fecha_fin_sln = trim(substr($contenido, 233, 10));
                                            $detalleILiquidacion->fecha_inicio_ige = trim(substr($contenido, 243, 10));
                                            $detalleILiquidacion->fecha_fin_ige = trim(substr($contenido, 253, 10));
                                            $detalleILiquidacion->fecha_inicio_lma = trim(substr($contenido, 263, 10));
                                            $detalleILiquidacion->fecha_fin_lma = trim(substr($contenido, 273, 10));
                                            $detalleILiquidacion->fecha_inicio_vac_lr = trim(substr($contenido, 283, 10));
                                            $detalleILiquidacion->fecha_fin_vac_lr = trim(substr($contenido, 293, 10));
                                            $detalleILiquidacion->fecha_inicio_vct = trim(substr($contenido, 303, 10));
                                            $detalleILiquidacion->fecha_fin_vct = trim(substr($contenido, 313, 10));
                                            $detalleILiquidacion->fecha_inicio_irl = trim(substr($contenido, 323, 10));
                                            $detalleILiquidacion->fecha_fin_irl = trim(substr($contenido, 333, 10));
                                            $detalleILiquidacion->fecha_radicacion_exterior = trim(substr($contenido, 343, 10));
                                            $detalleILiquidacion->contenido = $contenido;
                                            // $detalleILiquidacion->save();
                                            $listaILiquidacion->push($detalleILiquidacion);
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId();
                                            // $recEncabezado->save();
                                        } else {
                                            foreach ($errores->toArray() as $key => $r) {
                                                $mensajeError = $mensajeError . $r . ";";
                                            }
                                            $detalleIEncabezado->errores = $mensajeError;
                                            // $detalleIEncabezado->save();
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                            // $recEncabezado->save();

                                            if (!$iLiquidacionErroneoAgregado) {
                                                $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                                $iLiquidacionErroneoAgregado = true;
                                            } else {
                                                $aux = collect();
                                                foreach ($archivosErroneos->toArray() as $key => $ae) {
                                                    if (!strpos($ae, $resultado)) {
                                                        $aux->push($ae);
                                                    }
                                                }
                                                $archivosErroneos = $aux;
                                                $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                            }
                                        }
                                    } else {
                                        //  dd('AS');
                                        $mensajeError = "Error liquidación recaudo planilla: T2 no cumple estructura";
                                        $detalleIEncabezado->errores = $mensajeError;
                                        // $detalleIEncabezado->save();
                                        $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                    }
                                } else {
                                    if (!empty($contenido) && empty($mensajeError)) {
                                        $mensajeError = "Error liquidación recaudo planilla: Cantidad Carácteres.";
                                        $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                        $detalleIEncabezado->errores = $mensajeError;
                                        // $detalleIEncabezado->save();
                                    }
                                }
                            }
                        }
                        // $contenido = fgetcsv($gestor,0,";")[0];
                        $archivosProcesados->push($resultado);
                        $iLiquidacionErroneoAgregado = false;
                    } elseif (stripos($name, "_AR_")) {
                        $encabezado = explode('_', $name);
                        $recEncabezado = new RecRecaudoPlanillaEncabezado();
                        if (!is_null($revalidar)) {
                            $recEncabezado = RecRecaudoPlanillaEncabezado::where('consecutivo_recaudo', $llaveEncavezado)->first();
                        } else {
                            $consecutivo = $consecutivo + 1;
                            $recEncabezado->consecutivo_recaudo = $consecutivo;
                        }
                        $recEncabezado->fecha_pago = $sdf->parse($encabezado[$fecha_pago])->format('Y-m-d'); //fecha_pago
                        $recEncabezado->numero_planilla = $encabezado[$numero_plailla]; //numero_planilla
                        $recEncabezado->tipo_documento = $encabezado[$tipo_documento]; //tipo_documento
                        $recEncabezado->numero_documento = $encabezado[$numero_documento]; //numero_documento
                        $recEncabezado->operador = $encabezado[$operador]; //operador
                        $recEncabezado->tipo_archivo = $encabezado[$tipo_archivo]; //tipo_archivo
                        $recEncabezado->periodo_pago = $encabezado[$periodo_pago]; //periodo_pago
                        $recEncabezado->fecha_descarga = $fechaActual;
                        $recEncabezado->fecha_cargue = $fechaActual;
                        $recEncabezado->usuario_grabado = Auth::user()->id;
                        $recEncabezado->fecha_grabado = $sdf->parse($fechaActual)->toDateTimeString();
                        $recEncabezado->consecutivo_periodo_liquidacion = $periodo->consecutivo_liquidacion ?? null;
                        // $recEncabezado->save();
                        $listaEncabezados->push($recEncabezado);

                        $detalleAR = new RecRecaudoPlanillaDetalleARe();
                        $detalleAR->consecutivo_recaudo_encabezado = $recEncabezado->consecutivo_recaudo;
                        $detalleAR->nombre_archivo = $name;
                        // $detalleAR->save();
                        $csvReader = $in;
                        $resultado = $name;
                        if (!$setPlanillasGuardadas->containsStrict($recEncabezado->numero_planilla . '-A')) {
                            $mensajeError = "Error liquidación recaudo planilla: Planilla Guardada";
                        }

                        while (!feof($csvReader)) {
                            $linea = fgets($csvReader);
                            if ($linea != false) {
                                $contenido = str_replace("\r\n", '', $linea);
                                // $contenido = fgetcsv($gestor,0,";")[0];
                                $detalleAR->contenido = $contenido;
                                if (strlen($contenido) === 567) {
                                    $errores = $this->compensacionRepository->validarEstructuraRecaudoPlanillaA($contenido, $name, $setMunicipios, $setDepartamentos);
                                    if ($errores->isEmpty() && empty($mensajeError)) {
                                        $detalleAR->razon_social_aportante = trim(substr($contenido, 0, 200));
                                        $detalleAR->tipo_identificacion_aportante = trim(substr($contenido, 200, 2));
                                        $detalleAR->numero_identificacion_aportante = trim(substr($contenido, 202, 16));
                                        $detalleAR->digito_verificacion_aportante = trim(substr($contenido, 218, 1));
                                        $detalleAR->codigo_sucursal = trim(substr($contenido, 219, 10));
                                        $detalleAR->nombre_sucursal = trim(substr($contenido, 229, 39));
                                        $detalleAR->clase_aportante = trim(substr($contenido, 269, 1));
                                        $detalleAR->naturaleza_juridica = trim(substr($contenido, 270, 1));
                                        $detalleAR->tipo_persona = trim(substr($contenido, 271, 1));
                                        $detalleAR->forma_presentacion = trim(substr($contenido, 272, 1));
                                        $detalleAR->direccion_aportante = trim(substr($contenido, 273, 40));
                                        $detalleAR->municipio = trim(substr($contenido, 313, 3));
                                        $detalleAR->departamento = trim(substr($contenido, 316, 2));
                                        $detalleAR->actividad_economica = trim(substr($contenido, 318, 4));
                                        $detalleAR->telefono = trim(substr($contenido, 322, 10));
                                        $detalleAR->fax = trim(substr($contenido, 332, 10));
                                        $detalleAR->correo_electronico = trim(substr($contenido, 342, 60));
                                        $detalleAR->numero_identificacion_representante = trim(substr($contenido, 402, 16));
                                        $detalleAR->digito_verificacion_representante = trim(substr($contenido, 418, 1));
                                        $detalleAR->tipo_identificacion_representante = trim(substr($contenido, 419, 2));
                                        $detalleAR->primer_apellido_representante = trim(substr($contenido, 421, 20));
                                        $detalleAR->segundo_apellido_representante = trim(substr($contenido, 441, 30));
                                        $detalleAR->primer_nombre_representante = trim(substr($contenido, 471, 20));
                                        $detalleAR->segundo_nombre_representante = trim(substr($contenido, 491, 30));
                                        $detalleAR->fecha_inicio_accion = trim(substr($contenido, 521, 10));
                                        $detalleAR->tipo_accion = trim(substr($contenido, 531, 1));
                                        $detalleAR->fecha_terminacion_actividades = trim(substr($contenido, 532, 10));
                                        $detalleAR->codigo_operador = trim(substr($contenido, 542, 2));
                                        $detalleAR->periodo_pago = trim(substr($contenido, 544, 7));
                                        $detalleAR->tipo_aportante = trim(substr($contenido, 551, 2));
                                        $detalleAR->fecha_matricula_mercantil = trim(substr($contenido, 553, 10));
                                        $detalleAR->departamento_aportante = trim(substr($contenido, 563, 2));
                                        $detalleAR->sw_exonerado_pago = trim(substr($contenido, 565, 1));
                                        $detalleAR->sw_caja_compensacion = trim(substr($contenido, 566, 1));
                                        // $detalleAR->save();
                                        $archivosExistosos->push($resultado);
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId();
                                        // $recEncabezado->save();
                                        //Log::info('PASA SIN PROBLEMA', [$setPlanillasGuardadas, $detalleA]);
                                    } else {
                                        foreach ($errores->toArray() as $key => $r) {
                                            $mensajeError = $mensajeError . $r . ';';
                                        }
                                        $detalleAR->errores = $mensajeError;
                                        // $detalleAR->save();
                                        $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                    }
                                } else {
                                    $mensajeError = "Error liquidación recaudo planilla cantidad caracteres";
                                    $detalleAR->errores = $mensajeError;
                                    // $detalleAR->save();
                                    $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                    $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                    // $recEncabezado->save();
                                }
                                $listaAR->push($detalleAR);
                            }
                        }
                        $archivosProcesados->push($resultado);
                    } elseif (stripos($name, '_IR_')) {
                        $encabezado = explode('_', $name);
                        $recEncabezado = new RecRecaudoPlanillaEncabezado();
                        if (!is_null($revalidar)) {
                            $recEncabezado = RecRecaudoPlanillaEncabezado::where('consecutivo_recaudo', $llaveEncavezado)->first();
                        } else {
                            $consecutivo = $consecutivo + 1;
                            $recEncabezado->consecutivo_recaudo = $consecutivo;
                        }
                        $recEncabezado->fecha_pago = $sdf->parse($encabezado[$fecha_pago])->format('Y-m-d'); //fecha_pago
                        $recEncabezado->numero_planilla = $encabezado[$numero_plailla]; //numero_planilla
                        $recEncabezado->tipo_documento = $encabezado[$tipo_documento]; //tipo_documento
                        $recEncabezado->numero_documento = $encabezado[$numero_documento]; //numero_documento
                        $recEncabezado->operador = $encabezado[$operador]; //operador
                        $recEncabezado->tipo_archivo = $encabezado[$tipo_archivo]; //tipo_archivo
                        $recEncabezado->periodo_pago = $encabezado[$periodo_pago]; //periodo_pago
                        $recEncabezado->fecha_descarga = $fechaActual;
                        $recEncabezado->fecha_cargue = $fechaActual;
                        $recEncabezado->usuario_grabado = Auth::user()->id;
                        $recEncabezado->fecha_grabado = $sdf->parse($fechaActual)->toDateTimeString();
                        $recEncabezado->consecutivo_periodo_liquidacion = $periodo->consecutivo_liquidacion ?? null;
                        // $recEncabezado->save();
                        $listaEncabezados->push($recEncabezado);

                        $detalleIREncabezado = new RecRecaudoPlanillaDetalleIRe();
                        $detalleIREncabezado->consecutivo_recaudo_encabezado = $recEncabezado->consecutivo_recaudo;
                        $detalleIREncabezado->nombre_archivo = $name;
                        $detalleIREncabezado->contenido = "";
                        $detalleIREncabezado->errores = "";
                        // $detalleIREncabezado->save();
                        $listaIR->push($detalleIREncabezado);

                        $detalleIRTotales = new RecRecaudoPlanillaDetalleIRTotal();
                        $detalleIRTotales->consecutivo_recaudo_encabezado = $recEncabezado->consecutivo_recaudo;
                        $detalleIRTotales->contenido = "";
                        // $detalleIRTotales->save();
                        $csvReader = $in;
                        $resultado = $name;
                        // $llave = "$recEncabezado->numero_planilla-I-$recEncabezado->tipo_documento-$recEncabezado->numero_documento-$recEncabezado->periodo_pago-$recEncabezado->operador";
                        if (!$setPlanillasGuardadas->contains("$recEncabezado->numero_planilla-I")) {
                            $mensajeError = "Error liquidación recaudo planilla: Planilla Guardada";
                        }

                        while (!feof($csvReader)) {
                            $linea = fgets($csvReader);

                            if ($linea != false) {
                                $contenido = str_replace("\r\n", '', $linea);
                                $detalleIRTotales->contenido = $contenido;
                                if (!$iREncabezadoProcesado) {
                                    if (strlen($contenido) === 527) {
                                        $errores = $this->compensacionRepository->validarEstructuraRecaudoPlanillaIEncabezado($contenido, $name, $setPlanillasGuardadas, $listaTipoAportante);
                                        if ($errores->isEmpty() && empty($mensajeError)) {
                                            $detalleIREncabezado->numero_registro = trim(substr($contenido, 0, 5));
                                            $detalleIREncabezado->tipo_registro = trim(substr($contenido, 5, 1));
                                            $detalleIREncabezado->codigo_formato = trim(substr($contenido, 6, 2));
                                            $detalleIREncabezado->nit_administradora = trim(substr($contenido, 8, 16));
                                            $detalleIREncabezado->digito_verificacion_administradora = trim(substr($contenido, 24, 1));
                                            $detalleIREncabezado->razon_social_aportante = trim(substr($contenido, 25, 200));
                                            $detalleIREncabezado->tipo_identificacion_aportante = trim(substr($contenido, 225, 2));
                                            $detalleIREncabezado->numero_identificacion_aportante = trim(substr($contenido, 227, 16));
                                            $detalleIREncabezado->digito_verificacion_aportante = trim(substr($contenido, 243, 1));
                                            $detalleIREncabezado->tipo_aportante = trim(substr($contenido, 244, 2));
                                            $detalleIREncabezado->direccion_correspondencia = trim(substr($contenido, 246, 40));
                                            $detalleIREncabezado->municipio = trim(substr($contenido, 286, 3));
                                            $detalleIREncabezado->departamento = trim(substr($contenido, 289, 2));
                                            $detalleIREncabezado->telefono = trim(substr($contenido, 291, 10));
                                            $detalleIREncabezado->fax = trim(substr($contenido, 301, 10));
                                            $detalleIREncabezado->correo_electronico = trim(substr($contenido, 311, 60));
                                            $detalleIREncabezado->periodo_pago = trim(substr($contenido, 371, 7));
                                            $detalleIREncabezado->codigo_arl = trim(substr($contenido, 378, 6));
                                            $detalleIREncabezado->tipo_planilla = trim(substr($contenido, 384, 1));
                                            $detalleIREncabezado->fecha_pago_planilla = trim(substr($contenido, 385, 10));
                                            $detalleIREncabezado->fecha_pago = trim(substr($contenido, 395, 10));
                                            $detalleIREncabezado->numero_planilla = trim(substr($contenido, 405, 10));
                                            $detalleIREncabezado->numero_radicacion_pila = trim(substr($contenido, 415, 10));
                                            $detalleIREncabezado->forma_presentacion = trim(substr($contenido, 425, 1));
                                            $detalleIREncabezado->codigo_sucursal = trim(substr($contenido, 426, 10));
                                            $detalleIREncabezado->nombre_sucursal = trim(substr($contenido, 436, 40));
                                            $detalleIREncabezado->numero_empleados = trim(substr($contenido, 476, 5));
                                            $detalleIREncabezado->numero_afiliados = trim(substr($contenido, 481, 5));
                                            $detalleIREncabezado->codigo_operador = trim(substr($contenido, 486, 2));
                                            $detalleIREncabezado->modalidad_planilla = trim(substr($contenido, 488, 2));
                                            $detalleIREncabezado->dias_mora = trim(substr($contenido, 490, 3));
                                            $detalleIREncabezado->numero_registros_tipo_2 = trim(substr($contenido, 493, 8));
                                            $detalleIREncabezado->fecha_matricula_mercantil = trim(substr($contenido, 501, 10));
                                            $detalleIREncabezado->departamento_aportante = trim(substr($contenido, 511, 2));
                                            $detalleIREncabezado->sw_exonerado_pago_aportes = trim(substr($contenido, 513, 1));
                                            $detalleIREncabezado->clase_aportante = trim(substr($contenido, 514, 1));
                                            $detalleIREncabezado->naturaleza_juridica = trim(substr($contenido, 515, 1));
                                            $detalleIREncabezado->tipo_persona = trim(substr($contenido, 516, 1));
                                            $detalleIREncabezado->fecha_actualizacion = trim(substr($contenido, 517, 10));
                                            $detalleIREncabezado->nombre_archivo = $name;
                                            // $detalleIREncabezado->save();
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId();
                                            // $recEncabezado->save();
                                            $archivosExistosos->push($resultado);
                                        } else {
                                            foreach ($errores as $item) {
                                                $mensajeError = "$mensajeError$item;";
                                            }
                                            $detalleIREncabezado->errores = $mensajeError;
                                            // $detalleIREncabezado->save();
                                            $archivosErroneos->push("$resultado, RE-> $mensajeError");
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                            // $recEncabezado->save();
                                            $iLiquidacionErroneoAgregado = true;
                                        }
                                    } else {
                                        $mensajeError = "Error liquidación planilla T1 no cumple estructura";
                                        $detalleIREncabezado->errores = $mensajeError;
                                        // $detalleIREncabezado->save();
                                        $archivosErroneos->push("$resultado, RE-> $mensajeError");
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                        $iLiquidacionErroneoAgregado = true;
                                    }
                                    $iREncabezadoProcesado = true;
                                } elseif ($this->iniciaCon($contenido, '00031') && empty($mensajeError) && (strlen($contenido) < 50)) {
                                    if (strlen($contenido) === 45) {
                                        $errores = $this->compensacionRepository->validarEstructuraTipo3Renglon31Aportantes($contenido); //CREAR MÉTODO
                                        if ($errores->isEmpty() && empty($mensajeError)) {
                                            $detalleIRTotales->registro_total_aportes = trim(substr($contenido, 0, 5));
                                            $detalleIRTotales->tipo_registro_31 = trim(substr($contenido, 5, 1));
                                            $detalleIRTotales->total_ibc = ((double)trim(substr($contenido, 6, 13)));
                                            $detalleIRTotales->total_cotizacion_obligatoria = ((double)trim(substr($contenido, 19, 13)));
                                            $detalleIRTotales->total_upc_adicional = ((double)trim(substr($contenido, 32, 13)));
                                            $detalleIRTotales->contenido = $detalleIRTotales->contenido + $contenido;
                                            // $detalleIRTotales->save();
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId();
                                            // $recEncabezado->save();
                                            $archivosExistosos->push($resultado);
                                        } else {
                                            foreach ($errores->toArray() as $r) {
                                                $mensajeError = $mensajeError . $r . ';';
                                            }
                                            $detalleIREncabezado->errores = $mensajeError;
                                            // $detalleIREncabezado->save();
                                            $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                            // $recEncabezado->save();
                                        }
                                    } elseif (empty($mensajeError)) {
                                        $mensajeError = "Error liquidación recaudo planilla: T3 Renglon 31, no cumple estructura";
                                        $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                        $detalleIREncabezado->errores = $mensajeError;
                                        // $detalleIREncabezado->save();
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                    }
                                } elseif ($this->iniciaCon($contenido, '00036') && empty($mensajeError) && (strlen($contenido) < 50)) {
                                    if (strlen($contenido) === 32) {
                                        $errores = $this->compensacionRepository->validarEstructuraTipo3Renglon36Mora($contenido); //CREAR MÉTODO
                                        if ($errores->isEmpty() && empty($mensajeError)) {
                                            $detalleIRTotales->registro_intereses_mora = trim(substr($contenido, 0, 5));
                                            $detalleIRTotales->tipo_registro_36 = trim(substr($contenido, 5, 1));
                                            $detalleIRTotales->dias_mora = ((int)trim(substr($contenido, 6, 4)));
                                            $detalleIRTotales->valor_mora_cotizaciones = ((double)trim(substr($contenido, 10, 11)));
                                            $detalleIRTotales->valor_mora_upc_adicional = ((double)trim(substr($contenido, 21, 11)));
                                            $detalleIRTotales->contenido = $detalleIRTotales->contenido + $contenido;
                                            // $detalleIRTotales->save();
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId();
                                            // $recEncabezado->save();
                                            $archivosExistosos->push($resultado);
                                        } else {
                                            foreach ($errores as $r) {
                                                $mensajeError = $mensajeError . $r . ';';
                                            }
                                            $detalleIREncabezado->errores = $mensajeError;
                                            // $detalleIREncabezado->save();
                                            $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                            // $recEncabezado->save();
                                        }
                                    } elseif (empty($mensajeError)) {
                                        $mensajeError = "Error liquidación recaudo planilla: T3 Renglon 36, no cumple estructura";
                                        $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                        $detalleIREncabezado->errores = $mensajeError;
                                        // $detalleIREncabezado->save();
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                    }
                                } elseif ($this->iniciaCon($contenido, '00039') && empty($mensajeError) && (strlen($contenido) < 50)) {
                                    if (strlen($contenido) === 45) {
                                        $errores = $this->compensacionRepository->validarEstructuraTipo3Renglon39Totales($contenido); //CREAR MÉTODO
                                        if (((double)($detalleIRTotales->total_upc_adicional + $detalleIRTotales->valor_mora_upc_adicional)) !== ((double)trim(substr($contenido, 32, 13)))) {
                                            $errores->push('Error liquidación recaudo planilla: T3 renglón 39 Total UPC Adicional');
                                        }
                                        if ($errores->isEmpty() && empty($mensajeError)) {
                                            $detalleIRTotales->registro_total_pagar = trim(substr($contenido, 0, 5));
                                            $detalleIRTotales->tipo_registro_39 = trim(substr($contenido, 5, 1));
                                            $detalleIRTotales->total_cotizacion = ((double)trim(substr($contenido, 6, 13)));
                                            $detalleIRTotales->total_neto_aportes = ((double)trim(substr($contenido, 19, 13)));
                                            $detalleIRTotales->total_neto_upc_adicional = ((double)trim(substr($contenido, 32, 13)));
                                            $detalleIRTotales->contenido = $detalleIRTotales->contenido + $contenido;
                                            // $detalleIRTotales->save();
                                            $listaIRTotales->push($detalleIRTotales);
                                            $archivosExistosos->push($resultado);
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId();
                                            // $recEncabezado->save();
                                        } else {
                                            foreach ($errores as $key => $r) {
                                                $mensajeError = $mensajeError . $r . ';';
                                            }
                                            $detalleIREncabezado->errores = $mensajeError;
                                            // $detalleIREncabezado->save();
                                            $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                            // $recEncabezado->save();
                                        }
                                    } elseif (empty($mensajeError)) {
                                        $mensajeError = "Error liquidación recaudo planilla: T3 Renglon 39, no cumple estructura";
                                        $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                        $detalleIREncabezado->errores = $mensajeError;
                                        // $detalleIREncabezado->save();
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                    }
                                } elseif (($valid = (strlen($contenido) === 353)) && empty($mensajeError)) {
                                    if ($valid) {
                                        $errores = $this->compensacionRepository->validarEstructuraRecaudoPlanillaITipo2Liquidacion($contenido, $setDepartamentos, $setMunicipios);
                                        if ($errores->isEmpty() && empty($mensajeError)) {
                                            $detalleIRLiquidacion = new RecRecaudoPlanillaDetalleIRLiquidacion();
                                            $detalleIRLiquidacion->consecutivo_recaudo_encabezado = $recEncabezado->consecutivo_recaudo;
                                            $detalleIRLiquidacion->secuencia = trim(substr($contenido, 0, 5));
                                            $detalleIRLiquidacion->tipo_registro = trim(substr($contenido, 5, 1));
                                            $detalleIRLiquidacion->tipo_identificacion_cotizante = trim(substr($contenido, 6, 2));
                                            $detalleIRLiquidacion->numero_identificacion_cotizante = trim(substr($contenido, 8, 16));
                                            $detalleIRLiquidacion->tipo_cotizante = trim(substr($contenido, 24, 2));
                                            $detalleIRLiquidacion->subtipo_cotizante = trim(substr($contenido, 26, 2));
                                            $detalleIRLiquidacion->sw_extranjero = trim(substr($contenido, 28, 1));
                                            $detalleIRLiquidacion->sw_colombiano_exterior = trim(substr($contenido, 29, 1));
                                            $detalleIRLiquidacion->departamento = trim(substr($contenido, 30, 2));
                                            $detalleIRLiquidacion->municipio = trim(substr($contenido, 32, 3));
                                            $detalleIRLiquidacion->primer_apellido = trim(substr($contenido, 35, 20));
                                            $detalleIRLiquidacion->segundo_apellido = trim(substr($contenido, 55, 30));
                                            $detalleIRLiquidacion->primer_nombre = trim(substr($contenido, 85, 20));
                                            $detalleIRLiquidacion->segundo_nombre = trim(substr($contenido, 105, 30));
                                            $detalleIRLiquidacion->sw_ing = trim(substr($contenido, 135, 1));
                                            $detalleIRLiquidacion->sw_ret = trim(substr($contenido, 136, 1));
                                            $detalleIRLiquidacion->sw_tde = trim(substr($contenido, 137, 1));
                                            $detalleIRLiquidacion->sw_tae = trim(substr($contenido, 138, 1));
                                            $detalleIRLiquidacion->sw_vsp = trim(substr($contenido, 139, 1));
                                            $detalleIRLiquidacion->sw_vst = trim(substr($contenido, 140, 1));
                                            $detalleIRLiquidacion->sw_sln = trim(substr($contenido, 141, 1));
                                            $detalleIRLiquidacion->sw_ige = trim(substr($contenido, 142, 1));
                                            $detalleIRLiquidacion->sw_lma = trim(substr($contenido, 143, 1));
                                            $detalleIRLiquidacion->sw_vac_lr = trim(substr($contenido, 144, 1));
                                            $detalleIRLiquidacion->dias_cotizados = ((int)trim(substr($contenido, 145, 2)));
                                            $detalleIRLiquidacion->salario_basico = ((double)trim(substr($contenido, 147, 9)));
                                            $detalleIRLiquidacion->ingreso_base_cotizacion = ((double)trim(substr($contenido, 156, 9)));
                                            $detalleIRLiquidacion->tarifa = ((double)trim(substr($contenido, 165, 7)));
                                            $detalleIRLiquidacion->cotizacion_obligatoria = ((double)trim(substr($contenido, 172, 9)));
                                            $detalleIRLiquidacion->valor_upc_adicional = ((double)trim(substr($contenido, 181, 9)));
                                            $detalleIRLiquidacion->sw_correcciones = trim(substr($contenido, 190, 1));
                                            $detalleIRLiquidacion->sw_salario_integral = trim(substr($contenido, 191, 1));
                                            $detalleIRLiquidacion->sw_exonerado_aportes = trim(substr($contenido, 192, 1));
                                            $detalleIRLiquidacion->fecha_ingreso = trim(substr($contenido, 193, 10));
                                            $detalleIRLiquidacion->fecha_retiro = trim(substr($contenido, 203, 10));
                                            $detalleIRLiquidacion->fecha_inicio_vsp = trim(substr($contenido, 213, 10));
                                            $detalleIRLiquidacion->fecha_inicio_sln = trim(substr($contenido, 223, 10));
                                            $detalleIRLiquidacion->fecha_fin_sln = trim(substr($contenido, 233, 10));
                                            $detalleIRLiquidacion->fecha_inicio_ige = trim(substr($contenido, 243, 10));
                                            $detalleIRLiquidacion->fecha_fin_ige = trim(substr($contenido, 253, 10));
                                            $detalleIRLiquidacion->fecha_inicio_lma = trim(substr($contenido, 263, 10));
                                            $detalleIRLiquidacion->fecha_fin_lma = trim(substr($contenido, 273, 10));
                                            $detalleIRLiquidacion->fecha_inicio_vac_lr = trim(substr($contenido, 283, 10));
                                            $detalleIRLiquidacion->fecha_fin_vac_lr = trim(substr($contenido, 293, 10));
                                            $detalleIRLiquidacion->fecha_inicio_vct = trim(substr($contenido, 303, 10));
                                            $detalleIRLiquidacion->fecha_fin_vct = trim(substr($contenido, 313, 10));
                                            $detalleIRLiquidacion->fecha_inicio_irl = trim(substr($contenido, 323, 10));
                                            $detalleIRLiquidacion->fecha_fin_irl = trim(substr($contenido, 333, 10));
                                            $detalleIRLiquidacion->fecha_radicacion_exterior = trim(substr($contenido, 343, 10));
                                            $detalleIRLiquidacion->contenido = $contenido;
                                            // $detalleIRLiquidacion->save();
                                            $listaIRLiquidacion->push($detalleIRLiquidacion);
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId();
                                            // $recEncabezado->save();
                                            $archivosExistosos->push($resultado);
                                        } else {
                                            foreach ($errores->toArray() as $key => $r) {
                                                $mensajeError = $mensajeError . $r . ";";
                                            }
                                            $detalleIREncabezado->errores = $mensajeError;
                                            // $detalleIREncabezado->save();
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                            // $recEncabezado->save();

                                            if (!$iLiquidacionErroneoAgregado) {
                                                $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                                $iLiquidacionErroneoAgregado = true;
                                            } else {
                                                $aux = collect();
                                                foreach ($archivosErroneos->toArray() as $key => $ae) {
                                                    if (!strpos($ae, $resultado)) {
                                                        $aux->push($ae);
                                                    }
                                                }
                                                $archivosErroneos = $aux;
                                                $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                            }
                                        }
                                    } else {
                                        $mensajeError = "Error liquidación recaudo planilla: T2 no cumple estructura";
                                        $detalleIREncabezado->errores = $mensajeError;
                                        // $detalleIREncabezado->save();
                                        $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                    }
                                } else {
                                    if (!empty($contenido) && empty($mensajeError)) {
                                        $mensajeError = "Error liquidación recaudo planilla: Cantidad Carácteres.";
                                        $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                        $detalleIREncabezado->errores = $mensajeError;
                                        /// $detalleIREncabezado->save();
                                    }
                                }
                            }
                        }
                        $archivosProcesados->push($resultado);
                        $iLiquidacionErroneoAgregado = false;
                    } elseif (stripos($name, '_AP_')) {
                        $encabezado = explode('_', $name);
                        $recEncabezado = new RecRecaudoPlanillaEncabezado();
                        if (!is_null($revalidar)) {
                            $recEncabezado = RecRecaudoPlanillaEncabezado::where('consecutivo_recaudo', $llaveEncavezado)->first();
                        } else {
                            $consecutivo = $consecutivo + 1;
                            $recEncabezado->consecutivo_recaudo = $consecutivo;
                        }
                        $recEncabezado->fecha_pago = $sdf->parse($encabezado[$fecha_pago])->format('Y-m-d'); //fecha_pago
                        $recEncabezado->numero_planilla = $encabezado[$numero_plailla]; //numero_planilla
                        $recEncabezado->tipo_documento = $encabezado[$tipo_documento]; //tipo_documento
                        $recEncabezado->numero_documento = $encabezado[$numero_documento]; //numero_documento
                        $recEncabezado->operador = $encabezado[$operador]; //operador
                        $recEncabezado->tipo_archivo = $encabezado[$tipo_archivo]; //tipo_archivo
                        $recEncabezado->periodo_pago = $encabezado[$periodo_pago]; //periodo_pago
                        $recEncabezado->fecha_descarga = $fechaActual;
                        $recEncabezado->fecha_cargue = $fechaActual;
                        $recEncabezado->usuario_grabado = Auth::user()->id;
                        $recEncabezado->fecha_grabado = $sdf->parse($fechaActual)->toDateTimeString();
                        $recEncabezado->consecutivo_periodo_liquidacion = $periodo->consecutivo_liquidacion ?? null;
                        $recEncabezado->consecutivo_encabezado_ac = $consecutivoEncabezadoAc ?? null;
                        $recEncabezado->codigo_eps_transfiere = $codigoEpsTransfiere ?? null;
                        // $recEncabezado->save();
                        $listaEncabezados->push($recEncabezado);

                        $detalleAP = new RecRecaudoPlanillaDetalleAPe();
                        $detalleAP->consecutivo_recaudo_encabezado = $recEncabezado->consecutivo_recaudo;
                        $detalleAP->nombre_archivo = $name;
                        //$detalleAP->save();
                        $csvReader = $in;
                        $resultado = $name;
                        $llave = "$recEncabezado->numero_planilla-AP-$recEncabezado->tipo_documento-$recEncabezado->numero_documento-$recEncabezado->periodo_pago-$recEncabezado->operador";

                        if ($setPlanillasGuardadas->containsStrict($llave)) {
                            $mensajeError = "Error liquidación recaudo planilla: Planilla Guardada $llave";
                        }
                        while (!feof($csvReader)) {
                            $linea = fgets($csvReader);
                            if ($linea != false) {
                                $contenido = str_replace("\r\n", '', $linea);
                                $detalleAP->contenido = $contenido;
                                if (strlen($contenido) === 482) {
                                    $errores = $this->compensacionRepository->validarEstructuraRecaudoPlanillaAP($contenido, $resultado);
                                    if ($errores->isEmpty() && empty($mensajeError)) {
                                        $detalleAP->razon_social_pagador = trim(substr($contenido, 0, 150));
                                        $detalleAP->tipo_identificacion_pagador = trim(substr($contenido, 152, 2));
                                        $detalleAP->numero_identificacion_pagador = trim(substr($contenido, 152, 16));
                                        $detalleAP->digito_verificacion_pagador = trim(substr($contenido, 168, 1));
                                        $detalleAP->codigo_sucursal = trim(substr($contenido, 169, 10));
                                        $detalleAP->nombre_sucursal = trim(substr($contenido, 179, 40));
                                        $detalleAP->clase_pagador = trim(substr($contenido, 219, 1));
                                        $detalleAP->naturaleza_juridica = trim(substr($contenido, 220, 1));
                                        $detalleAP->tipo_persona = trim(substr($contenido, 221, 1));
                                        $detalleAP->forma_presentacion = trim(substr($contenido, 222, 1));
                                        $detalleAP->direccion_pagador = trim(substr($contenido, 223, 40));
                                        $detalleAP->municipio = trim(substr($contenido, 263, 3));
                                        $detalleAP->departamento = trim(substr($contenido, 266, 2));
                                        $detalleAP->actividad_economica = trim(substr($contenido, 268, 4));
                                        $detalleAP->telefono = trim(substr($contenido, 272, 10));
                                        $detalleAP->fax = trim(substr($contenido, 282, 10));
                                        $detalleAP->correo_electronico = trim(substr($contenido, 292, 60));
                                        $detalleAP->numero_identificacion_representante = trim(substr($contenido, 352, 16));
                                        $detalleAP->digito_verificacion_representante = trim(substr($contenido, 368, 1));
                                        $detalleAP->tipo_identificacion_representante = trim(substr($contenido, 369, 2));
                                        $detalleAP->primer_apellido_representante = trim(substr($contenido, 371, 20));
                                        $detalleAP->segundo_apellido_representante = trim(substr($contenido, 391, 30));
                                        $detalleAP->primer_nombre_representante = trim(substr($contenido, 421, 20));
                                        $detalleAP->segundo_nombre_representante = trim(substr($contenido, 441, 30));
                                        $detalleAP->codigo_operador = trim(substr($contenido, 471, 2));
                                        $detalleAP->periodo_pago = trim(substr($contenido, 473, 7));
                                        $detalleAP->tipo_pagador = trim(substr($contenido, 480, 2));
                                        // $detalleAP->save();
                                        $archivosExistosos->push($resultado);
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId();
                                        // $recEncabezado->save();
                                    } else {
                                        foreach ($errores->toArray() as $key => $r) {
                                            $mensajeError = $mensajeError . $r . ';';
                                        }
                                        $detalleAP->errores = $mensajeError;
                                        // $detalleAP->save();
                                        $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                    }
                                } else {
                                    $mensajeError = "Error liquidación recaudo planilla cantidad caracteres";
                                    $detalleAP->errores = $mensajeError;
                                    // $detalleAP->save();
                                    $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                    $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();;
                                    // $recEncabezado->save();
                                }
                                $listaAP->push($detalleAP);
                            }
                        }
                        $archivosProcesados->push($resultado);
                    } elseif (stripos($name, '_IP_')) {
                        $encabezado = explode('_', $name);
                        $recEncabezado = new RecRecaudoPlanillaEncabezado();
                        if (!is_null($revalidar)) {
                            $recEncabezado = RecRecaudoPlanillaEncabezado::where('consecutivo_recaudo', $llaveEncavezado)->first();
                        } else {
                            $consecutivo = $consecutivo + 1;
                            $recEncabezado->consecutivo_recaudo = $consecutivo;
                        }
                        $recEncabezado->fecha_pago = $sdf->parse($encabezado[$fecha_pago])->format('Y-m-d'); //fecha_pago
                        $recEncabezado->numero_planilla = $encabezado[$numero_plailla]; //numero_planilla
                        $recEncabezado->tipo_documento = $encabezado[$tipo_documento]; //tipo_documento
                        $recEncabezado->numero_documento = $encabezado[$numero_documento]; //numero_documento
                        $recEncabezado->operador = $encabezado[$operador]; //operador
                        $recEncabezado->tipo_archivo = $encabezado[$tipo_archivo]; //tipo_archivo
                        $recEncabezado->periodo_pago = $encabezado[$periodo_pago]; //periodo_pago
                        $recEncabezado->fecha_descarga = $fechaActual;
                        $recEncabezado->fecha_cargue = $fechaActual;
                        $recEncabezado->usuario_grabado = Auth::user()->id;
                        $recEncabezado->fecha_grabado = $sdf->parse($fechaActual)->toDateTimeString();
                        $recEncabezado->consecutivo_periodo_liquidacion = $periodo->consecutivo_liquidacion ?? null;
                        $recEncabezado->consecutivo_encabezado_ac = $consecutivoEncabezadoAc ?? null;
                        $recEncabezado->codigo_eps_transfiere = $codigoEpsTransfiere ?? null;
                        // $recEncabezado->save();
                        $listaEncabezados->push($recEncabezado);

                        $detalleIPEncabezado = new RecRecaudoPlanillaDetalleIPe();
                        $detalleIPEncabezado->consecutivo_recaudo_encabezado = $recEncabezado->consecutivo_recaudo;
                        $detalleIPEncabezado->nombre_archivo = $name;
                        $detalleIPEncabezado->contenido = "";
                        $detalleIPEncabezado->errores = "";
                        // $detalleIPEncabezado->save();
                        $listaIP->push($detalleIPEncabezado);

                        $detalleIPTotales = new RecRecaudoPlanillaDetalleIPTotale();
                        $detalleIPTotales->consecutivo_recaudo_encabezado = $recEncabezado->consecutivo_recaudo;
                        $detalleIPTotales->contenido = "";
                        // $detalleIPTotales->save();
                        $csvReader = fopen($archive, 'r');
                        $resultado = $name;
                        $llave = "$recEncabezado->numero_planilla-IP-$recEncabezado->tipo_documento-$recEncabezado->numero_documento-$recEncabezado->periodo_pago-$recEncabezado->operador";

                        if ($setPlanillasGuardadas->containsStrict($llave)) {
                            $mensajeError = "Error liquidación recaudo planilla: Planilla Guardada";
                        }

                        while (!feof($csvReader)) {
                            $linea = fgets($csvReader);
                            if ($linea != false) {
                                $contenido = str_replace("\r\n", '', $linea);
                                $detalleIPEncabezado->contenido = ($detalleIPEncabezado->contenido . $contenido . "\r\n");

                                if (!$iPEncabezadoProcesado) {
                                    if (strlen($contenido) === 427) {
                                        $errores = $this->compensacionRepository->validarEstructuraRecaudoPlanillaIPEncabezado($contenido, $resultado, $setDepartamentos, $setMunicipios);

                                        if ($errores->isEmpty() && empty($mensajeError)) {
                                            $detalleIPEncabezado->numero_registro = trim(substr($contenido, 0, 5));
                                            $detalleIPEncabezado->tipo_registro = trim(substr($contenido, 5, 1));
                                            $detalleIPEncabezado->codigo_formato = trim(substr($contenido, 6, 2));
                                            $detalleIPEncabezado->nit_administradora = trim(substr($contenido, 8, 16));
                                            $detalleIPEncabezado->digito_verificacion_administradora = trim(substr($contenido, 24, 1));
                                            $detalleIPEncabezado->razon_social_pagador = trim(substr($contenido, 25, 150));
                                            $detalleIPEncabezado->tipo_identificacion_pagador = trim(substr($contenido, 175, 2));
                                            $detalleIPEncabezado->numero_identificacion_pagador = trim(substr($contenido, 177, 16));
                                            $detalleIPEncabezado->digito_verificacion_pagador = trim(substr($contenido, 193, 1));
                                            $detalleIPEncabezado->clase_pagador = trim(substr($contenido, 194, 1));
                                            $detalleIPEncabezado->direccion_correspondencia = trim(substr($contenido, 195, 40));
                                            $detalleIPEncabezado->municipio = trim(substr($contenido, 235, 3));
                                            $detalleIPEncabezado->departamento = trim(substr($contenido, 238, 2));
                                            $detalleIPEncabezado->telefono = trim(substr($contenido, 240, 10));
                                            $detalleIPEncabezado->fax = trim(substr($contenido, 250, 10));
                                            $detalleIPEncabezado->correo_electronico = trim(substr($contenido, 260, 60));
                                            $detalleIPEncabezado->periodo_pago = trim(substr($contenido, 320, 7));
                                            $detalleIPEncabezado->fecha_pago = trim(substr($contenido, 327, 10));
                                            $detalleIPEncabezado->forma_presentacion = trim(substr($contenido, 337, 1));
                                            $detalleIPEncabezado->codigo_sucursal = trim(substr($contenido, 338, 10));
                                            $detalleIPEncabezado->nombre_sucursal = trim(substr($contenido, 348, 40));
                                            $detalleIPEncabezado->numero_pensionados = trim(substr($contenido, 388, 7));
                                            $detalleIPEncabezado->numero_afiliados = trim(substr($contenido, 395, 7));
                                            $detalleIPEncabezado->codigo_operador = trim(substr($contenido, 402, 2));
                                            $detalleIPEncabezado->tipo_planilla = trim(substr($contenido, 404, 1));
                                            $detalleIPEncabezado->dias_mora = trim(substr($contenido, 405, 4));
                                            $detalleIPEncabezado->numero_registros_tipo_2 = trim(substr($contenido, 409, 8));
                                            $detalleIPEncabezado->fecha_actualizacion = trim(substr($contenido, 417, 10));
                                            $detalleIPEncabezado->nombre_archivo = $name;
                                            // $detalleIPEncabezado->save();
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId();
                                            // $recEncabezado->save();
                                            $archivosExistosos->push($resultado);

                                        } else {
                                            foreach ($errores as $item) {
                                                $mensajeError = "$mensajeError$item;";
                                            }
                                            $detalleIPEncabezado->errores = $mensajeError;
                                            // $detalleIPEncabezado->save();
                                            $archivosErroneos->push("$resultado, RE-> $mensajeError");
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                            // $recEncabezado->save();
                                            $iLiquidacionErroneoAgregado = true;
                                        }
                                    } else {
                                        $mensajeError = "Error liquidación planilla T1 no cumple estructura";
                                        $detalleIPEncabezado->errores = $mensajeError;
                                        // $detalleIPEncabezado->save();
                                        $archivosErroneos->push("$resultado, RE-> $mensajeError");
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                        $iLiquidacionErroneoAgregado = true;
                                    }
                                    $iPEncabezadoProcesado = true;
                                } elseif ($this->iniciaCon($contenido, '00031') && empty($mensajeError) && (strlen($contenido) < 50)) {
                                    if (strlen($contenido) === 45) {
                                        $errores = $this->compensacionRepository->validarEstructuraTipo3Renglon31AportesIP($contenido); //CREAR MÉTODO
                                        if (empty($errores) && empty($mensajeError)) {
                                            $detalleIPTotales->registro_total_aportes = trim(substr($contenido, 0, 5));
                                            $detalleIPTotales->tipo_registro_31 = trim(substr($contenido, 5, 1));
                                            $detalleIPTotales->valor_ibc = trim(substr($contenido, 6, 13));
                                            $detalleIPTotales->valor_cotizacion_obligatoria = trim(substr($contenido, 19, 13));
                                            $detalleIPTotales->valor_aporte_fosyga = trim(substr($contenido, 32, 13));
                                            $detalleIPTotales->contenido = $detalleIPTotales->contenido + $contenido;
                                            //$detalleIPTotales->save();
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId();
                                            // $recEncabezado->save();
                                            $archivosExistosos->push($resultado);
                                        } else {
                                            foreach ($errores as $key => $r) {
                                                $mensajeError = $mensajeError . $r . ';';
                                            }
                                            $detalleIPEncabezado->errores = $mensajeError;
                                            //$detalleIPEncabezado->save();
                                            $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                            // $recEncabezado->save();
                                        }
                                    } elseif (empty($mensajeError)) {
                                        $mensajeError = "Error liquidación recaudo planilla: T3 Renglon 31, no cumple estructura";
                                        $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                        $detalleIPEncabezado->errores = $mensajeError;
                                        // $detalleIPEncabezado->save();
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                    }
                                } elseif ($this->iniciaCon($contenido, '00036') && empty($mensajeError) && (strlen($contenido) < 50)) {
                                    if (strlen($contenido) === 32) {
                                        $errores = $this->compensacionRepository->validarEstructuraTipo3Renglon36MoraIP($contenido); //CREAR MÉTODO
                                        if ($errores->isEmpty() && empty($mensajeError)) {
                                            $detalleIPTotales->registro_intereses_mora = trim(substr($contenido, 0, 5));
                                            $detalleIPTotales->tipo_registro_36 = trim(substr($contenido, 5, 1));
                                            $detalleIPTotales->dias_mora = trim(substr($contenido, 6, 4));
                                            $detalleIPTotales->valor_mora_cotizaciones = trim(substr($contenido, 10, 11));
                                            $detalleIPTotales->valor_mora_upc_adicional = trim(substr($contenido, 21, 11));
                                            $detalleIPTotales->contenido = $detalleIPTotales->contenido + $contenido;
                                            // $detalleIPTotales->save();
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId();
                                            // $recEncabezado->save();
                                            $archivosExistosos->push($resultado);
                                        } else {
                                            foreach ($errores as $key => $r) {
                                                $mensajeError = $mensajeError . $r . ';';
                                            }
                                            $detalleIPEncabezado->errores = $mensajeError;
                                            // $detalleIPEncabezado->save();
                                            $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                            /// $recEncabezado->save();
                                        }
                                    } elseif (empty($mensajeError)) {
                                        $mensajeError = "Error liquidación recaudo planilla: T3 Renglon 36, no cumple estructura";
                                        $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                        $detalleIPEncabezado->errores = $mensajeError;
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                    }
                                } elseif ($this->iniciaCon($contenido, '00037') && empty($mensajeError) && (strlen($contenido) < 50)) {
                                    if (strlen($contenido) === 45) {
                                        $errores = $this->compensacionRepository->validarEstructuraTipo3Renglon37AportesInteresesIP($contenido); //CREAR MÉTODO
                                        if (((double)($detalleIPTotales->valor_cotizacion_obligatoria + $detalleIPTotales->valor_mora_cotizaciones)) !== ((double)trim(substr($contenido, 19, 13)))) {
                                            $errores->push('Error liquidación recaudo planilla: T3 renglón 39 Valor Cotización Obligatoria');
                                        }

                                        if (((double)($detalleIPTotales->valor_aporte_fosyga + $detalleIPTotales->valor_mora_upc_adicional)) !== ((double)trim(substr($contenido, 32, 13)))) {
                                            $errores->push('Error liquidación recaudo planilla: T3 renglón 39 Total UPC Adicional');
                                        }
                                        if ($errores->isEmpty() && empty($mensajeError)) {
                                            $detalleIPTotales->registro_aportes_intereses = trim(substr($contenido, 0, 5));
                                            $detalleIPTotales->tipo_registro_37 = trim(substr($contenido, 5, 1));
                                            $detalleIPTotales->valor_ibc_intereses = trim(substr($contenido, 6, 13));
                                            $detalleIPTotales->valor_cotizacion_obligatoria_intereses = trim(substr($contenido, 19, 13));
                                            $detalleIPTotales->valor_upc_adicional = trim(substr($contenido, 32, 13));
                                            $detalleIPTotales->contenido = $detalleIPTotales->contenido + $contenido;
                                            // $detalleIPTotales->save();
                                            // $listaIPTotales->push($detalleIPTotales);
                                            //$archivosExistosos->push($resultado);
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId();
                                            // $recEncabezado->save();
                                            $archivosExistosos->push($resultado);
                                        } else {
                                            foreach ($errores as $key => $r) {
                                                $mensajeError = $mensajeError . $r . ';';
                                            }
                                            $detalleIPEncabezado->errores = $mensajeError;
                                            // $detalleIPEncabezado->save();
                                            $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                            // $recEncabezado->save();
                                        }
                                    } elseif (empty($mensajeError)) {
                                        $mensajeError = "Error liquidación recaudo planilla: T3 Renglon 37, no cumple estructura";
                                        $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                        $detalleIPEncabezado->errores = $mensajeError;
                                        // $detalleIPEncabezado->save();
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                    }
                                } elseif ($this->iniciaCon($contenido, '00039') && empty($mensajeError) && (strlen($contenido) < 60)) {
                                    if (strlen($contenido) === 58) {
                                        $errores = $this->compensacionRepository->validarEstructuraTipo3Renglon39TotalesIP($contenido); //CREAR MÉTODO
                                        if (((double)($detalleIPTotales->valor_aporte_fosyga + $detalleIPTotales->valor_mora_upc_adicional)) !== ((double)trim(substr($contenido, 45, 13)))) {
                                            $errores->push('Error liquidación recaudo planilla: T3 renglón 39 Total UPC Adicional');
                                        }

                                        if (((double)($detalleIPTotales->valor_cotizacion_obligatoria + $detalleIPTotales->valor_mora_cotizaciones)) !== ((double)trim(substr($contenido, 19, 13)))) {
                                            $errores->push('Error liquidación recaudo planilla: T3 renglón 39 Valor Cotización Obligatoria');
                                        }
                                        if ($errores->isEmpty() && empty($mensajeError)) {
                                            $detalleIPTotales->registro_total_pagar = trim(substr($contenido, 0, 5));
                                            $detalleIPTotales->tipo_registro_39 = trim(substr($contenido, 5, 1));
                                            $detalleIPTotales->total_ibc = trim(substr($contenido, 6, 13));
                                            $detalleIPTotales->total_cotizacion_obligatoria = trim(substr($contenido, 19, 13));
                                            $detalleIPTotales->total_aporte_fosyga = trim(substr($contenido, 32, 13));
                                            $detalleIPTotales->total_upc_adicional = trim(substr($contenido, 45, 13));
                                            $detalleIPTotales->contenido = $detalleIPTotales->contenido + $contenido;
                                            //$detalleIPTotales->save();
                                            $listaIPTotales->push($detalleIPTotales);
                                            $archivosExistosos->push($resultado);
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId();
                                            // $recEncabezado->save();
                                        } else {
                                            foreach ($errores as $key => $r) {
                                                $mensajeError = $mensajeError . $r . ';';
                                            }
                                            $detalleIPEncabezado->errores = $mensajeError;
                                            //$detalleIPEncabezado->save();
                                            $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                            // $recEncabezado->save();
                                        }
                                    } elseif (empty($mensajeError)) {
                                        $mensajeError = "Error liquidación recaudo planilla: T3 Renglon 39, no cumple estructura";
                                        $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                        $detalleIPEncabezado->errores = $mensajeError;
                                        // $detalleIPEncabezado->save();
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                    }
                                } elseif (strlen($contenido) === 365 && empty($mensajeError)) {
                                    if (strlen($contenido) === 365) {
                                        $errores = $this->compensacionRepository->validarEstructuraRecaudoPlanillaIPTipo2Liquidacion($contenido, $setDepartamentos, $setMunicipios);
                                        if ($errores->isEmpty() && empty($mensajeError)) {#REF
                                            $detalleIPLiquidacion = new RecRecaudoPlanillaDetalleIPLiquidacione();
                                            $detalleIPLiquidacion->consecutivo_recaudo_encabezado = $recEncabezado->consecutivo_recaudo;
                                            $detalleIPLiquidacion->secuencia = trim(substr($contenido, 0, 7));
                                            $detalleIPLiquidacion->tipo_registro = trim(substr($contenido, 7, 1));
                                            $detalleIPLiquidacion->tipo_identificacion_pensionado = trim(substr($contenido, 8, 2));
                                            $detalleIPLiquidacion->numero_identificacion_pensionado = trim(substr($contenido, 10, 16));
                                            $detalleIPLiquidacion->primer_apellido_pensionado = trim(substr($contenido, 26, 20));
                                            $detalleIPLiquidacion->segundo_apellido_pensionado = trim(substr($contenido, 46, 30));
                                            $detalleIPLiquidacion->primer_nombre_pensionado = trim(substr($contenido, 76, 20));
                                            $detalleIPLiquidacion->segundo_nombre_pensionado = trim(substr($contenido, 96, 30));
                                            $detalleIPLiquidacion->primer_apellido_causante = trim(substr($contenido, 126, 20));
                                            $detalleIPLiquidacion->segundo_apellido_causante = trim(substr($contenido, 146, 30));
                                            $detalleIPLiquidacion->primer_nombre_causante = trim(substr($contenido, 176, 20));
                                            $detalleIPLiquidacion->segundo_nombre_causante = trim(substr($contenido, 196, 30));
                                            $detalleIPLiquidacion->tipo_identificacion_causante = trim(substr($contenido, 226, 2));
                                            $detalleIPLiquidacion->numero_identificacion_causante = trim(substr($contenido, 228, 20));
                                            $detalleIPLiquidacion->tipo_pension = trim(substr($contenido, 244, 2));
                                            $detalleIPLiquidacion->sw_pension_compartida = trim(substr($contenido, 246, 1));
                                            $detalleIPLiquidacion->tipo_pensionado = trim(substr($contenido, 247, 1));
                                            $detalleIPLiquidacion->sw_pensionado_exterior = trim(substr($contenido, 248, 1));
                                            $detalleIPLiquidacion->departamento_residencia = trim(substr($contenido, 249, 2));
                                            $detalleIPLiquidacion->municipio_residencia = trim(substr($contenido, 251, 3));
                                            $detalleIPLiquidacion->sw_ing = trim(substr($contenido, 254, 1));
                                            $detalleIPLiquidacion->sw_ret = trim(substr($contenido, 255, 1));
                                            $detalleIPLiquidacion->sw_tde = trim(substr($contenido, 256, 1));
                                            $detalleIPLiquidacion->sw_tae = trim(substr($contenido, 257, 1));
                                            $detalleIPLiquidacion->sw_vsp = trim(substr($contenido, 258, 1));
                                            $detalleIPLiquidacion->sw_sus = trim(substr($contenido, 259, 1));
                                            $detalleIPLiquidacion->dias_cotizados = ((int)trim(substr($contenido, 260, 2)));
                                            $detalleIPLiquidacion->ingreso_base_cotizacion = ((double)trim(substr($contenido, 262, 9)));
                                            $detalleIPLiquidacion->tarifa = ((double)trim(substr($contenido, 271, 7)));
                                            $detalleIPLiquidacion->cotizacion_obligatoria = ((double)trim(substr($contenido, 278, 9)));
                                            $detalleIPLiquidacion->valor_upc_adicional = ((double)trim(substr($contenido, 287, 9)));
                                            $detalleIPLiquidacion->fecha_ingreso = trim(substr($contenido, 296, 10));
                                            $detalleIPLiquidacion->fecha_retiro = trim(substr($contenido, 306, 10));
                                            $detalleIPLiquidacion->fecha_inicio_vsp = trim(substr($contenido, 316, 10));
                                            $detalleIPLiquidacion->valor_mesada_pensional = ((double)trim(substr($contenido, 326, 9)));
                                            $detalleIPLiquidacion->fecha_radicacion_exterior = trim(substr($contenido, 335, 10));
                                            $detalleIPLiquidacion->fecha_inicio_sus = trim(substr($contenido, 345, 10));
                                            $detalleIPLiquidacion->fecha_fin_sus = trim(substr($contenido, 355, 10));
                                            $detalleIPLiquidacion->contenido = $contenido;
                                            // $detalleIPLiquidacion->save();
                                            $listaIPLiquidacion->push($detalleIPLiquidacion);
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId();
                                            //$recEncabezado->save();
                                            //$archivosExistosos->push($resultado);
                                        } else {
                                            foreach ($errores->toArray() as $key => $r) {
                                                $mensajeError = $mensajeError . $r . ";";
                                            }
                                            $detalleIPEncabezado->errores = $mensajeError;
                                            //$detalleIPEncabezado->save();
                                            $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                            //$recEncabezado->save();

                                            if (!$iLiquidacionErroneoAgregado) {
                                                $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                                $iLiquidacionErroneoAgregado = true;
                                            } else {
                                                $aux = collect();
                                                foreach ($archivosErroneos->toArray() as $key => $ae) {
                                                    if (!strpos($ae, $resultado)) {
                                                        $aux->push($ae);
                                                    }
                                                }
                                                $archivosErroneos = $aux;
                                                $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                            }
                                        }
                                    } else {
                                        $mensajeError = "Error liquidación recaudo planilla: T2 no cumple estructura";
                                        $detalleIPEncabezado->errores = $mensajeError;
                                        //$detalleIPEncabezado->save();
                                        $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        //$recEncabezado->save();
                                    }
                                } else {
                                    if (!empty($contenido) && empty($mensajeError)) {
                                        $mensajeError = "Error liquidación recaudo planilla: Cantidad Carácteres.";
                                        $archivosErroneos->push($resultado . ", RE-> " . $mensajeError);
                                        $recEncabezado->resultado = EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId();
                                        // $recEncabezado->save();
                                        $detalleIPEncabezado->errores = $mensajeError;
                                        // $detalleIPEncabezado->save();
                                    }
                                }
                            }
                        }
                        $archivosProcesados->push($resultado);
                        $iLiquidacionErroneoAgregado = false;
                    }
                    fclose($in);
                }
                $iEncabezadoProcesado = false;
                $iREncabezadoProcesado = false;
                $iPEncabezadoProcesado = false;
                $mensajeError = "";
                $resultado = null;
                //File::deleteDirectory('descarga/liquidacion_recaudos/$diaSeleccionado',0);
            }
        }
        DB::commit();

        if (!is_null($revalidar)) {
            $this->eliminarRevalidadasPila($llaveEncavezado);

            $recEncabezado = $listaEncabezados->get(0);
            $enc = new Collection();
            $enc->put('consecutivo_recaudo', $recEncabezado->consecutivo_recaudo);
            $enc->put('fecha_cargue', $recEncabezado->fecha_cargue);
            $enc->put('fecha_descarga', $recEncabezado->fecha_descarga);
            $enc->put('fecha_pago', $recEncabezado->fecha_pago);
            $enc->put('operador', $recEncabezado->operador);
            $enc->put('tipo_documento', $recEncabezado->tipo_documento);
            $enc->put('numero_documento', $recEncabezado->numero_documento);
            $enc->put('periodo_pago', $recEncabezado->periodo_pago);
            $enc->put('numero_planilla', $recEncabezado->numero_planilla);
            $enc->put('resultado', $recEncabezado->resultado);
//            $enc->put('consecutivo_recaudo', $recEncabezado->consecutivo_recaudo);
//            $enc->put('fecha_cargue_d', $recEncabezado->fecha_cargue);
//            $enc->put('fecha_descarga_d', $recEncabezado->fecha_descarga);
//            $enc->put('fecha_pago_d', $recEncabezado->fecha_pago);
//            $enc->put('operador', $recEncabezado->operador);
//            $enc->put('tipo_documento', $recEncabezado->tipo_documento);
//            $enc->put('numero_documento', $recEncabezado->numero_documento);
//            $enc->put('periodo_pago', $recEncabezado->periodo_pago);
//            $enc->put('numero_planilla', $recEncabezado->numero_planilla);
            $mapaEncabezados->put($recEncabezado->consecutivo_recaudo, $enc);

            $refEncabezados = RecRecaudoPlanillaEncabezado::find($recEncabezado->consecutivo_recaudo);
            $refEncabezados->fill($enc->toArray());
            $refEncabezados->save();

        } else {
            foreach ($listaEncabezados as $key => $item) {
                $enc = new Collection();
                $enc->put('consecutivo_recaudo', $item->consecutivo_recaudo);
                $enc->put('fecha_cargue', $item->fecha_cargue);
                $enc->put('fecha_descarga', $item->fecha_descarga);
                $enc->put('fecha_pago', $item->fecha_pago);
                $enc->put('operador', $item->operador);
                $enc->put('tipo_documento', $item->tipo_documento);
                $enc->put('numero_documento', $item->numero_documento);
                $enc->put('periodo_pago', $item->periodo_pago);
                $enc->put('numero_planilla', $item->numero_planilla);
                $enc->put('tipo_archivo', $item->tipo_archivo);
                $enc->put('resultado', $item->resultado);
                //$enc->put('usuario_grabado', $item->usuario_grabado);
//                $enc->put('consecutivo_recaudo', $item->consecutivo_recaudo);
//                $enc->put('fecha_cargue_d', $item->fecha_cargue);
//                $enc->put('fecha_descarga_d', $item->fecha_descarga);
//                $enc->put('fecha_pago_d', $item->fecha_pago);
//                $enc->put('operador', $item->operador);
//                $enc->put('tipo_documento', $item->tipo_documento);
//                $enc->put('numero_documento', $item->numero_documento);
//                $enc->put('periodo_pago', $item->periodo_pago);
//                $enc->put('numero_planilla', $item->numero_planilla);
                $mapaEncabezados->put($item->consecutivo_recaudo, $enc);

                $refEncabezados = new RecRecaudoPlanillaEncabezado();
                $refEncabezados->fill($enc->toArray());
                $refEncabezados->save();
            }
        }

        if ($listaA->isNotEmpty()) {
            foreach ($listaA->toArray() as $itemKey => $recA) {
                if (isset($recA['tipo_identificacion_aportante'])) {
                    if ((($recA['tipo_identificacion_aportante'] !== '') || !is_null($recA['tipo_identificacion_aportante'])) && ($recA['numero_identificacion_aportante'] !== '') && ($recA['razon_social_aportante'] !== '')) {
                        $mapaRazonSocial->put(($recA['tipo_identificacion_aportante'] . $recA['numero_identificacion_aportante']), $recA['razon_social_aportante']);
                        RecRecaudoPlanillaDetalleA::create($recA);
                    }
                }
            }
        }

        if ($listaI->isNotEmpty()) {
            foreach ($listaI->toArray() as $itemKey => $recI) {
                RecRecaudoPlanillaDetalleI::create($recI);
            }
        }

        $listaILiquidacionTwo = collect();
        if ($listaILiquidacion->isNotEmpty()) {
            foreach ($listaILiquidacion->toArray() as $itemKey => $recIL) {
                $itemRec = RecRecaudoPlanillaDetalleILiquidacione::create($recIL);
                $listaILiquidacionTwo->push($itemRec);
            }
        }

        if ($listaITotales->isNotEmpty()) {
            foreach ($listaITotales->toArray() as $itemKey => $recIT) {
                RecRecaudoPlanillaDetalleITotal::create($recIT);
            }
        }

        if ($listaAR->isNotEmpty()) {
            foreach ($listaAR->toArray() as $itemKey => $recAR) {
                if (isset($recAR['tipo_identificacion_aportante'])) {
                    if ((($recAR['tipo_identificacion_aportante'] !== '') || !is_null($recAR['tipo_identificacion_aportante'])) && ($recAR['numero_identificacion_aportante'] !== '') && ($recAR['razon_social_aportante'] !== '')) {
                        $mapaRazonSocial->put(($recAR['tipo_identificacion_aportante'] . $recAR['numero_identificacion_aportante']), $recAR['razon_social_aportante']);
                        RecRecaudoPlanillaDetalleARe::create($recAR);
                    }
                }
            }
        }

        if ($listaIR->isNotEmpty()) {
            foreach ($listaIR->toArray() as $itemKey => $recIR) {
                RecRecaudoPlanillaDetalleIRe::create($recIR);
            }
        }

        $listaIRLiquidacionTwo = collect();
        if ($listaIRLiquidacion->isNotEmpty()) {
            foreach ($listaIRLiquidacion->toArray() as $itemKey => $recIRL) {
                $itemIRL = RecRecaudoPlanillaDetalleIRLiquidacion::create($recIRL);
                $listaIRLiquidacionTwo->push($itemIRL);
            }
        }

        if ($listaIRTotales->isNotEmpty()) {
            foreach ($listaIRTotales->toArray() as $itemKey => $recIRT) {
                RecRecaudoPlanillaDetalleIRTotal::create($recIRT);
            }
        }

        if ($listaAP->isNotEmpty()) {
            foreach ($listaAP->toArray() as $itemKey => $recAP) {
                if (isset($recAP['tipo_identificacion_pagador'])) {
                    if ((($recAP['tipo_identificacion_pagador'] !== '') || !is_null($recAP['tipo_identificacion_pagador'])) && ($recAP['numero_identificacion_pagador'] !== '') && ($recAP['razon_social_pagador'] !== '')) {
                        $mapaRazonSocial->put(($recAP['tipo_identificacion_pagador'] . $recAP['numero_identificacion_pagador']), $recAP['razon_social_pagador']);
                        RecRecaudoPlanillaDetalleAPe::create($recAP);
                    }
                }
            }
        }

        if ($listaIP->isNotEmpty()) {
            foreach ($listaIP->toArray() as $itemKey => $recIP) {
                RecRecaudoPlanillaDetalleIPe::create($recIP);
            }
        }

        $listaIPLiquidacionTwo = collect();
        if ($listaIPLiquidacion->isNotEmpty()) {
            foreach ($listaIPLiquidacion->toArray() as $itemKey => $recIPL) {
                $itemRec = RecRecaudoPlanillaDetalleIPLiquidacione::create($recIPL);
                $listaIPLiquidacionTwo->push($itemRec);
            }
        }

        if ($listaIPTotales->isNotEmpty()) {
            foreach ($listaIPTotales->toArray() as $itemKey => $recIPT) {
                RecRecaudoPlanillaDetalleIPTotale::create($recIPT);
            }
        }

        $mapaConsolidado->put('archivosProcesados', $archivosProcesados->count());
        $mapaConsolidado->put('archivosExitosos', $archivosExistosos->count());
        $mapaConsolidado->put('archivosErroneos', $archivosErroneos->count());

        $casosProcesosMasivos = new Collection();
        $casosProcesosMasivos->put('lista_informacion_proceso', collect());
        $info = false;
        if ($listaILiquidacionTwo->isNotEmpty()) {
            foreach ($listaILiquidacionTwo->toArray() as $key => $il) {
                if ((!empty($il['sw_ing'])) || (!empty($il['sw_ret']))) {
                    $info = true;
                    $enc = $mapaEncabezados->offsetGet($il['consecutivo_recaudo_encabezado']);
                    $llaveMapa = $enc['tipo_documento'] . $enc['numero_documento'];
                    $razonSocial = $mapaRazonSocial->offsetGet($llaveMapa);

                    $informacionProcesosMasivosAutomaticos = new Collection();
                    $informacionProcesosMasivosAutomaticos->put('tipo_documento_afiliado', $il['tipo_identificacion_cotizante']);
                    $informacionProcesosMasivosAutomaticos->put('numero_documento_afiliado', $il['numero_identificacion_cotizante']);
                    $informacionProcesosMasivosAutomaticos->put('tipo_documento_aportante', $enc['tipo_documento']);
                    $informacionProcesosMasivosAutomaticos->put('numero_documento_aportante', $enc['numero_documento']);
                    $informacionProcesosMasivosAutomaticos->put('primer_nombre_afiliado', $il['primer_nombre']);
                    $informacionProcesosMasivosAutomaticos->put('segundo_nombre_afiliado', $il['segundo_nombre']);
                    $informacionProcesosMasivosAutomaticos->put('primer_apellido_afiliado', $il['primer_apellido']);
                    $informacionProcesosMasivosAutomaticos->put('segundo_apellido_afiliado', $il['segundo_apellido']);
                    $informacionProcesosMasivosAutomaticos->put('tipo_cotizante', $il['tipo_cotizante']);
                    $informacionProcesosMasivosAutomaticos->put('razon_social', $razonSocial);
                    $informacionProcesosMasivosAutomaticos->put('periodo', $enc['periodo_pago']);
                    $informacionProcesosMasivosAutomaticos->put('dias', $il['dias_cotizados']);
                    $informacionProcesosMasivosAutomaticos->put('ibc', $il['ingreso_base_cotizacion']);
                    $informacionProcesosMasivosAutomaticos->put('codigo_eps', 'EPS025');
                    $informacionProcesosMasivosAutomaticos->put('codigo_eps_traslado', 'EPS025');
                    $informacionProcesosMasivosAutomaticos->put('contenido', $il['contenido']);
                    $informacionProcesosMasivosAutomaticos->put('planilla', $enc['numero_planilla']);
                    $informacionProcesosMasivosAutomaticos->put('fecha_pago', $enc['fecha_pago']);
                    $informacionProcesosMasivosAutomaticos->put('ing', $il['sw_ing']);
                    $informacionProcesosMasivosAutomaticos->put('ret', $il['sw_ret']);
                    $informacionProcesosMasivosAutomaticos->put('consecutivo_recaudo_encabezado', $il['consecutivo_recaudo_encabezado']);
                    $informacionProcesosMasivosAutomaticos->put('secuencia_pila', $il['secuencia']);
                    $casosProcesosMasivos->offsetGet('lista_informacion_proceso')->push($informacionProcesosMasivosAutomaticos);

                    $liquidacion = RecRecaudoPlanillaDetalleILiquidacione::where('consecutivo_planilla_detalle_iliquidacion', $il['consecutivo_planilla_detalle_iliquidacion'])->first();
                    $liquidacion->sw_procesado = ESiNo::getIndice(ESiNo::SI)->getId();
                    $liquidacion->save();
                }
            }
        }

        if ($listaIPLiquidacionTwo->isNotEmpty()) {
            $casosProcesosMasivos->put('lista_i_p_liquidacion', collect());
            foreach ($listaIPLiquidacionTwo->toArray() as $key => $il) {
                if ((!empty($il['sw_ing'])) || (!empty($il['sw_ret']))) {
                    $info = true;

                    $enc = $mapaEncabezados->offsetGet($il['consecutivo_recaudo_encabezado']);
                    $llaveMapa = $enc['tipo_documento'] . $enc['numero_documento'];
                    $razonSocial = $mapaRazonSocial->offsetGet($llaveMapa);

                    $informacionProcesosMasivosAutomaticos = new Collection();
                    $informacionProcesosMasivosAutomaticos->put('tipo_documento_afiliado', $il['tipo_identificacion_pensionado']);
                    $informacionProcesosMasivosAutomaticos->put('numero_documento_afiliado', $il['numero_identificacion_pensionado']);
                    $informacionProcesosMasivosAutomaticos->put('tipo_documento_aportante', $enc['tipo_documento']);
                    $informacionProcesosMasivosAutomaticos->put('numero_documento_aportante', $enc['numero_documento']);
                    $informacionProcesosMasivosAutomaticos->put('primer_nombre_afiliado', $il['primer_nombre_pensionado']);
                    $informacionProcesosMasivosAutomaticos->put('segundo_nombre_afiliado', $il['segundo_nombre_pensionado']);
                    $informacionProcesosMasivosAutomaticos->put('primer_apellido_afiliado', $il['primer_apellido_pensionado']);
                    $informacionProcesosMasivosAutomaticos->put('segundo_apellido_afiliado', $il['segundo_apellido_pensionado']);
                    $informacionProcesosMasivosAutomaticos->put('tipo_cotizante', $il['tipo_pensionado']);
                    $informacionProcesosMasivosAutomaticos->put('razon_social', $razonSocial);
                    $informacionProcesosMasivosAutomaticos->put('periodo', $enc['periodo_pago']);
                    $informacionProcesosMasivosAutomaticos->put('dias', $il['dias_cotizados']);
                    $informacionProcesosMasivosAutomaticos->put('ibc', $il['ingreso_base_cotizacion']);
                    $informacionProcesosMasivosAutomaticos->put('codigo_eps', 'EPS025');
                    $informacionProcesosMasivosAutomaticos->put('codigo_eps_traslado', 'EPS025');
                    $informacionProcesosMasivosAutomaticos->put('contenido', $il['contenido']);
                    $informacionProcesosMasivosAutomaticos->put('planilla', $enc['numero_planilla']);
                    $informacionProcesosMasivosAutomaticos->put('fecha_pago', $enc['fecha_pago']);
                    $informacionProcesosMasivosAutomaticos->put('ing', $il['sw_ing']);
                    $informacionProcesosMasivosAutomaticos->put('ret', $il['sw_ret']);
                    $informacionProcesosMasivosAutomaticos->put('consecutivo_recaudo_encabezado', $il['consecutivo_recaudo_encabezado']);
                    $informacionProcesosMasivosAutomaticos->put('secuencia_pila', $il['secuencia']);
                    $casosProcesosMasivos->offsetGet('lista_informacion_proceso')->push($informacionProcesosMasivosAutomaticos);

                    $liquidacion = RecRecaudoPlanillaDetalleIPLiquidacione::where('consecutivo_recaudo_planilla_i_p_liquidacion', $il['consecutivo_recaudo_planilla_i_p_liquidacion'])->first();
                    $liquidacion->sw_procesado = ESiNo::getIndice(ESiNo::SI)->getId();
                    $liquidacion->save();
                }
            }
        }

        if ($listaIRLiquidacionTwo->isNotEmpty()) {
            $casosProcesosMasivos->put('lista_i_r_liquidacion', collect());
            foreach ($listaIRLiquidacionTwo->toArray() as $key => $il) {
                $info = true;

                $enc = $mapaEncabezados->offsetGet($il['consecutivo_recaudo_encabezado']);
                $llaveMapa = $enc['tipo_documento'] . $enc['numero_documento'];
                $razonSocial = $mapaRazonSocial->offsetGet($llaveMapa);

                $informacionProcesosMasivosAutomaticos = new Collection();
                $informacionProcesosMasivosAutomaticos->put('tipo_documento_afiliado', $il['tipo_identificacion_cotizante']);
                $informacionProcesosMasivosAutomaticos->put('numero_documento_afiliado', $il['numero_identificacion_cotizante']);
                $informacionProcesosMasivosAutomaticos->put('tipo_documento_aportante', $enc['tipo_documento']);
                $informacionProcesosMasivosAutomaticos->put('numero_documento_aportante', $enc['numero_documento']);
                $informacionProcesosMasivosAutomaticos->put('primer_nombre_afiliado', $il['primer_nombre']);
                $informacionProcesosMasivosAutomaticos->put('segundo_nombre_afiliado', $il['segundo_nombre']);
                $informacionProcesosMasivosAutomaticos->put('primer_apellido_afiliado', $il['primer_apellido']);
                $informacionProcesosMasivosAutomaticos->put('segundo_apellido_afiliado', $il['segundo_apellido']);
                $informacionProcesosMasivosAutomaticos->put('tipo_cotizante', $il['tipo_cotizante']);
                $informacionProcesosMasivosAutomaticos->put('razon_social', $razonSocial);
                $informacionProcesosMasivosAutomaticos->put('periodo', $enc['periodo_pago']);
                $informacionProcesosMasivosAutomaticos->put('dias', $il['dias_cotizados']);
                $informacionProcesosMasivosAutomaticos->put('ibc', $il['ingreso_base_cotizacion']);
                $informacionProcesosMasivosAutomaticos->put('codigo_eps', 'EPS025');
                $informacionProcesosMasivosAutomaticos->put('codigo_eps_traslado', 'EPS025');
                $informacionProcesosMasivosAutomaticos->put('contenido', $il['contenido']);
                $informacionProcesosMasivosAutomaticos->put('planilla', $enc['numero_planilla']);
                $informacionProcesosMasivosAutomaticos->put('fecha_pago', $enc['fecha_pago']);
                $informacionProcesosMasivosAutomaticos->put('ing', $il['sw_ing']);
                $informacionProcesosMasivosAutomaticos->put('ret', $il['sw_ret']);
                $informacionProcesosMasivosAutomaticos->put('consecutivo_recaudo_encabezado', $il['consecutivo_recaudo_encabezado']);
                $informacionProcesosMasivosAutomaticos->put('secuencia_pila', $il['secuencia']);
                $casosProcesosMasivos->offsetGet('lista_informacion_proceso')->push($informacionProcesosMasivosAutomaticos);

                $liquidacion = RecRecaudoPlanillaDetalleIRLiquidacion::where('consecutivo_recaudo_planilla_detalle_i_r_liquidacion', $il['consecutivo_recaudo_planilla_detalle_i_r_liquidacion'])->first();
                $liquidacion->sw_procesado = ESiNo::getIndice(ESiNo::SI)->getId();
                $liquidacion->save();
            }
        }

        if ($info) {
            // dd('as',$info, $casosProcesosMasivos, $mapaEncabezados);
            $casosProcesosMasivos->put('mapa_encabezados_pila', $mapaEncabezados);
            $casosProcesosMasivos->put('ing', true);
            $casosProcesosMasivos->put('ret', true);
            $casosProcesosMasivos->put('guardar', true);
            // $this->getInformacionNovedadesPilaProcesoMasivo($casosProcesosMasivos);
        }

        return $mapaConsolidado;
    }

    /**
     * @param array $lists
     * @param array $listDias
     * @param Carbon $fecha
     * @param Collection $resultados
     * @return array[]
     */
    public function getDayFile(array $lists, array $listDias, Carbon $fecha, Collection $resultados): array
    {
        if ($lists !== null) {
            foreach ($lists as $key => $list) {
                $nuevaRuta = "$this->directorio/$list";
                if (is_dir($nuevaRuta) && $list !== '.' && $list !== '..') {
                    array_push($listDias, $fecha->parse($list)->format('Y-m-d'));
                }
            }

            sort($listDias);
            if (!empty($listDias)) {
                $diaSeleccionado = $listDias[0];

                if ($resultados !== null) {
                    $resultados->put('diaSeleccionado', $diaSeleccionado);
                    $resultados->put('diasRestantes', (strval(count($listDias) - 1)));
                }
                $carpetaDia = "$this->directorio/$diaSeleccionado/";
                $archivos = scandir($carpetaDia);

                if ($archivos !== null && count($archivos) > 0) {
                    $data = array();
                    foreach ($archivos as $key => $archivo) {
                        if ($archivo !== '.' && $archivo !== '..') {
                            array_push($data, [
                                'nombre_archivo' => $archivo,
                                'fecha_descarga' => $fecha->now()->format('Y/m/d')
                            ]);
                        }
                    }
                    $resultados->put('archivos', $data);
                }
            }
        }
        return $resultados->toArray();
    }

    /**
     * @param $llaveEncavezado
     */
    private function eliminarRevalidadasPila($llaveEncavezado): void
    {
        $rec_recaudo_planilla_detalle_a = RecRecaudoPlanillaDetalleA::where('consecutivo_recaudo_encabezado', $llaveEncavezado)->first();
        $rec_recaudo_planilla_detalle_a->delete();

        $rec_recaudo_planilla_detalle_i = RecRecaudoPlanillaDetalleI::where('consecutivo_recaudo_encabezado', $llaveEncavezado)->first();
        $rec_recaudo_planilla_detalle_i->delete();

        $rec_recaudo_planilla_detalle_i_liquidacion = RecRecaudoPlanillaDetalleILiquidacione::where('consecutivo_recaudo_encabezado', $llaveEncavezado)->first();
        $rec_recaudo_planilla_detalle_i_liquidacion->delete();

        $rec_recaudo_planilla_detalle_i_totales = RecRecaudoPlanillaDetalleITotal::where('consecutivo_recaudo_encabezado', $llaveEncavezado)->first();
        $rec_recaudo_planilla_detalle_i_totales->delete();

        $rec_recaudo_planilla_detalle_a_r = RecRecaudoPlanillaDetalleARe::where('consecutivo_recaudo_encabezado', $llaveEncavezado)->first();
        $rec_recaudo_planilla_detalle_a_r->delete();

        $rec_recaudo_planilla_detalle_i_r = RecRecaudoPlanillaDetalleIRe::where('consecutivo_recaudo_encabezado', $llaveEncavezado)->first();
        $rec_recaudo_planilla_detalle_i_r->delete();

        $rec_recaudo_planilla_detalle_i_r_liquidacion = RecRecaudoPlanillaDetalleIRLiquidacion::where('consecutivo_recaudo_encabezado', $llaveEncavezado)->first();
        $rec_recaudo_planilla_detalle_i_r_liquidacion->delete();

        $rec_recaudo_planilla_detalle_i_r_total = RecRecaudoPlanillaDetalleIRTotal::where('consecutivo_recaudo_encabezado', $llaveEncavezado)->first();
        $rec_recaudo_planilla_detalle_i_r_total->delete();

        $rec_recaudo_planilla_detalle_a_p = RecRecaudoPlanillaDetalleAPe::where('consecutivo_recaudo_encabezado', $llaveEncavezado)->first();
        $rec_recaudo_planilla_detalle_a_p->delete();

        $rec_recaudo_planilla_detalle_i_p = RecRecaudoPlanillaDetalleIPe::where('consecutivo_recaudo_encabezado', $llaveEncavezado)->first();
        $rec_recaudo_planilla_detalle_i_p->delete();

        $rec_recaudo_planilla_detalle_i_p_liquidacion = RecRecaudoPlanillaDetalleIPLiquidacione::where('consecutivo_recaudo_encabezado', $llaveEncavezado)->first();
        $rec_recaudo_planilla_detalle_i_p_liquidacion->delete();

        $rec_recaudo_planilla_detalle_i_p_totales = RecRecaudoPlanillaDetalleIPTotale::where('consecutivo_recaudo_encabezado', $llaveEncavezado)->first();
        $rec_recaudo_planilla_detalle_i_p_totales->delete();
    }

    public function iniciaCon($string, $startString)
    {
        $len = strlen($startString);
        if (substr($string, 0, $len) === $startString) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Collection $casosProcesosMasivos
     */
    public function getInformacionNovedadesPilaProcesoMasivo(Collection $casosProcesosMasivos)
    {

        $listaInformacion = new Collection();
        $mapaProcesoIng = new Collection();
        $mapaProcesoRet = new Collection();
        $listaLLavesEncabezados = new Collection();
        $listaPlanillasActualizar = new Collection();

        $mapaDiasHabilesXEncabezado = new Collection();

        $listaInformacion->push($casosProcesosMasivos->offsetGet('lista_informacion_proceso'));

        $mapaEncabezadosPila = $casosProcesosMasivos->offsetGet('mapa_encabezados_pila');
        foreach ($mapaEncabezadosPila->toArray() as $key => $enc) {
            $fechaHabil = $this->compensacionRepository->getDiaHabilSiguienteSemana($enc['fecha_cargue']);
            $calendar = \IntlCalendar::createInstance();
            $calendar->setTime((float)$fechaHabil);
            $calendar->add(\IntlCalendar::FIELD_MONTH, (-2));
            $fechaHabil = $calendar->getTime();

            $mapaDiasHabilesXEncabezado->put($enc['consecutivo_recaudo'], $fechaHabil);
        }

        $listaAuxiliar = new Collection();

        foreach ($listaInformacion->toArray() as $key => $listaInforme) {
            $fechaHabil = $mapaDiasHabilesXEncabezado->offsetGet($listaInforme['consecutivo_recaudo']);
            $fechaPago = $listaInforme['fecha_pago'];
            //$fechaHabil < $fechaPago
            //($fechaHabil,$fechaPago);
            $val = intlcal_before($fechaHabil, $fechaPago);
            if ($val) {
                $listaAuxiliar->push($listaInforme);
            } else {
                $listaLLavesEncabezados->push($listaInforme['consecutivo_recaudo']);
                $listaPlanillasActualizar->push($listaInforme);
            }
        }

        if ($listaPlanillasActualizar->isNotEmpty()) {
            $model = new Collection();
            $model->put('usuario', Auth::user()->id);
            $model->put('empresa', 7);

            $mapaTipoArchivoXEncabezado = new Collection();

            foreach (ProcesoConciliacionPilaBanco::getSubLista($listaLLavesEncabezados, 400) as $key => $sublist) {
                $mapaTipoArchivoXEncabezado->push($this->compensacionRepository->getMapaTipoArchivoXEncabezadoPila($sublist));
            }

            foreach ($listaPlanillasActualizar as $key => $o) {
                // dd($o);
                // die;
                // if ($mapaTipoArchivoXEncabezado->containsStrict($o->consecutivo_recaudo)) {
                // }
            }
        }
    }

    public function exportar()
    {
        try {
            $coleccion = json_decode(Input::get('collection'), true);

            if (empty($coleccion['fechaInicio']) || empty($coleccion['fechaFin'])) {
                throw new \Exception('Error: Fecha Inicio y Fecha Fin son obligatorios.');
            } elseif (empty($coleccion['estadoC'])) {
                throw new \Exception('Error: Debe seleccionar un resultado de cargue.');
            } elseif (($coleccion['tipoArchivo'] !== 'I') && ($coleccion['tipoArchivo'] !== 'IP')) {
                throw new \Exception('Error: El tipo de archivo está vacío o no es válido para exportar masivo.');
            } else {
                // Los cargues con inconsistencias no guardan liquidaciones por cotizante,
                // por lo tanto el parametro no se usa por el momento ya que no se puede generar
                // el reporte para esos casos, solamente cargues exitosos

                if (($coleccion['estadoC'] === EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId())) {
                    if ($coleccion['tipoArchivo'] === 'I') {
                        return response()->download(ClasificadorArchivosRec::getRecaudoDetalladoCotizantesInconsistentes($coleccion['fechaInicio'], $coleccion['fechaFin'], $coleccion['numeroPlanilla'], $coleccion['numeroDocumento'], $coleccion['periodoPago']))->setStatusCode(200);
                    }
                    if ($coleccion['tipoArchivo'] === 'IP') {
                        return response()->download(ClasificadorArchivosRec::getRecaudoDetalladoCotizantesPensionadosInconsistentes($coleccion['fechaInicio'], $coleccion['fechaFin'], $coleccion['numeroPlanilla'], $coleccion['numeroDocumento'], $coleccion['periodoPago']))->setStatusCode(200);
                    }
                } else {
                    if ($coleccion['tipoArchivo'] === 'I') {
                        $file = ClasificadorArchivosRec::getRecaudoDetalladoCotizantes($coleccion['fechaInicio'], $coleccion['fechaFin'], $coleccion['numeroPlanilla'], $coleccion['numeroDocumento'], $coleccion['periodoPago']);
                        return response()->download($file)->setStatusCode(200);
                    }
                    if ($coleccion['tipoArchivo'] === 'IP') {
                        return response()->download(ClasificadorArchivosRec::getRecaudoDetalladoPensionados($coleccion['fechaInicio'], $coleccion['fechaFin'], $coleccion['numeroPlanilla'], $coleccion['numeroDocumento'], $coleccion['periodoPago']))->setStatusCode(200);
                    }
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'message' => $e
            ], 500);
        }
    }

    /**
     * @return BinaryFileResponse
     */
    public function getContenidoRecaudoPlanillaOriginal()
    {
        $encabezado = Input::get('ìd');
        $errores = Input::get('error');
        $resultado = "";
        $sql = null;
        $sql = 'SELECT';
        $column = (($errores !== "false") ? ' o.errores' : ' o.contenido').' AS item';
        $sql .= "$column, o.nombre_archivo FROM ";
        $rec_encabezado = RecRecaudoPlanillaEncabezado::where('consecutivo_recaudo',$encabezado)->first();

        switch (trim($rec_encabezado['tipo_archivo'])) {
            case 'A': $sql .= 'rec_recaudo_planilla_detalle_as '; break;
            case 'I': $sql .= 'rec_recaudo_planilla_detalle_is '; break;
            case 'AR': $sql .= 'rec_recaudo_planilla_detalle_a_res '; break;
            case 'IR': $sql .= 'rec_recaudo_planilla_detalle_i_res '; break;
            case 'AP': $sql .= 'rec_recaudo_planilla_detalle_a_pes '; break;
            case 'IP': $sql .= 'rec_recaudo_planilla_detalle_i_pes '; break;
        }

        $lists = DB::select($sql.'AS o WHERE o.consecutivo_recaudo_encabezado = '.$encabezado);

        $query = collect();

        $fileName = null;
        foreach ($lists as $key => $list) {
            $query->push($key);
            $resultado .= (($errores !== "false") ? 'ERROR: ' : '') . $list->item .($query->count() === count($lists) ? '' : PHP_EOL);
            $fileName = $list->nombre_archivo.'.txt';
        }
        $contenido = $resultado;
        file_put_contents($fileName,$contenido);

        return response()->download($fileName)->setStatusCode(200);
    }

    public function esPlanillaConciliada($consecutivoRecaudo)
    {
        try {
            $criteria = null;
            $encabezado = RecRecaudoPlanillaEncabezado::with('recaudo_transferencia_encabezado')
                ->where('consecutivo_recaudo',$consecutivoRecaudo)->first()->toArray();

            $encabezadoPar = null;

            $criteria = RecRecaudoPlanillaEncabezado::where('fecha_grabado', '=', $encabezado['fecha_grabado'])
                ->where('numero_planilla', '=', $encabezado['numero_planilla']);

            if ($encabezado['tipo_archivo'] === 'A') {
                $encabezadoPar = $criteria->where('tipo_archivo', 'I')->first();
                if (!isset($encabezadoPar)) {
                    return $this->getSwConciliacion(null, false);
                }
                $i = RecRecaudoPlanillaDetalleI::where('consecutivo_recaudo_encabezado', $encabezadoPar['consecutivo_recaudo'])->first();
                return $this->getSwConciliacion($i,true);
            } else if ($encabezado['tipo_archivo'] === 'AP') {
                $encabezadoPar = $criteria->where('tipo_archivo', 'IP')->first();
                if (!isset($encabezadoPar)) {
                    return $this->getSwConciliacion(null, false);
                }
                $i = RecRecaudoPlanillaDetalleIPe::where('consecutivo_recaudo_encabezado', $encabezadoPar['consecutivo_recaudo'])->first();
                return $this->getSwConciliacion($i,true);
            } else if ($encabezado['tipo_archivo'] === 'I') {
                $i = RecRecaudoPlanillaDetalleI::where('consecutivo_recaudo_encabezado', $encabezadoPar['consecutivo_recaudo'])->first();
                if (!isset($i)) {
                    return $this->getSwConciliacion(null, false);
                }
                return $this->getSwConciliacion($i,true);
            } else if ($encabezado['tipo_archivo'] === 'IP') {
                $i = RecRecaudoPlanillaDetalleIPe::where('consecutivo_recaudo_encabezado', $encabezadoPar['consecutivo_recaudo'])->first();
                if (!isset($i)) {
                    return $this->getSwConciliacion(null, false);
                }
                //return (($i['sw_conciliacion'] !== null) && ($i['sw_conciliacion'] === ESiNo::getIndice(ESiNo::SI)->getId()));
                return $this->getSwConciliacion($i,true);
            }

            return $this->getSwConciliacion(null, false);
        } catch (\Exception $exception) {
            return response()->json([
                'errors'    => $exception->getMessage(),
                'message'   => $exception
            ],500);
        }
    }

    /**
     * @param $i
     * @return JsonResponse
     */
    protected function getSwConciliacion($i, $vboolean): JsonResponse
    {
        if (is_null($i) && (!$vboolean)) {
            $data = ['data' => false];
        } else {
            $data = ['sw_conciliacion' => ($i['sw_conciliacion'] !== null && ($i['sw_conciliacion'] === ESiNo::getIndice(ESiNo::SI)->getId()))];
        }
        return response()->json($data, 200);
    }

    public function eliminarRecaudoPlanilla($consecutivoRecaudo)
    {
        $criteria = null;
        $sql = null;
        $borrador1 = null;
        $borrador2 = null;

        DB::statement("SET FOREIGN_KEY_CHECKS = 0");
        $encabezado = RecRecaudoPlanillaEncabezado::where('consecutivo_recaudo', $consecutivoRecaudo)->first();
        $encabezadoPar = null;

        $criteria = RecRecaudoPlanillaEncabezado::with('recaudo_transferencia_encabezado')->where('fecha_grabado',$encabezado['fecha_grabado'])->where('numero_planilla',$encabezado['numero_planilla']);

        switch ($encabezado['tipo_archivo']) {
            case 'A':
                $encabezadoPar = $criteria->where('tipo_archivo','=','I')->first();
                break;
            case 'AP':
                $encabezadoPar = $criteria->where('tipo_archivo','=','IP')->first();
                break;
            case 'AR':
                $encabezadoPar = $criteria->where('tipo_archivo','=','IR')->first();
                break;
            case 'I':
                $encabezadoPar = $criteria->where('tipo_archivo','=','A')->first();
                break;
            case 'IP':
                $encabezadoPar = $criteria->where('tipo_archivo','=','AP')->first();
                break;
            case 'IR':
                $encabezadoPar = $criteria->where('tipo_archivo','=','AR')->first();
                break;
        }

        try {

            while (!$borrador1 || !$borrador2) {

                if (($encabezado['tipo_archivo'] === 'A') || ($encabezado['tipo_archivo'] === 'AP') || ($encabezado['tipo_archivo'] === 'AR')) {
                    $sql = "DELETE o.* FROM ";

                    if ($encabezado['tipo_archivo'] === 'A') {
                        $sql .= "rec_recaudo_planilla_detalle_as ";
                    } elseif ($encabezado['tipo_archivo'] === 'AP') {
                        $sql .= "rec_recaudo_planilla_detalle_a_pes ";
                    } elseif ($encabezado['tipo_archivo'] === 'AR') {
                        $sql .= "rec_recaudo_planilla_detalle_a_res ";
                    }

                    $idRecaudo = ((integer) $encabezado['consecutivo_recaudo_encabezado']);
                    $sql .= "AS o WHERE o.consecutivo_recaudo_encabezado = $idRecaudo";
                    DB::statement($sql);
                    Log::info('ERROR ELIMINACION', [$sql]);
                } elseif (($encabezado['tipo_archivo'] === 'I') || ($encabezado['tipo_archivo'] === 'IP') || ($encabezado['tipo_archivo'] === 'IR')) {
                    // Borrar detalle I totals
                    $sql = "DELETE o.* FROM ";

                    if ($encabezado['tipo_archivo'] === 'I') {
                        $sql .= "rec_recaudo_planilla_detalle_i_totals ";
                    } elseif ($encabezado['tipo_archivo'] === 'IP') {
                        $sql .= "rec_recaudo_planilla_detalle_i_p_totales ";
                    } elseif ($encabezado['tipo_archivo'] === 'IR') {
                        $sql .= "rec_recaudo_planilla_detalle_i_r_totals ";
                    }

                    $idRecaudo = ((integer) $encabezado['consecutivo_recaudo_encabezado']);
                    $sql .= "AS o WHERE o.consecutivo_recaudo_encabezado = $idRecaudo";

                    // Borrar detalle I liquidaciones
                    $sql1 = "DELETE o.* FROM ";

                    if ($encabezado['tipo_archivo'] === 'I') {
                        $sql1 .= "rec_recaudo_planilla_detalle_i_liquidaciones ";
                    } elseif ($encabezado['tipo_archivo'] === 'IP') {
                        $sql1 .= "rec_recaudo_planilla_detalle_i_p_liquidaciones ";
                    } elseif ($encabezado['tipo_archivo'] === 'IR') {
                        $sql1 .= "rec_recaudo_planilla_detalle_i_r_liquidacions ";
                    }

                    $idRecaudo = ((integer) $encabezado['consecutivo_recaudo_encabezado']);
                    $sql1 .= "AS o WHERE o.consecutivo_recaudo_encabezado = $idRecaudo";

                    // Borrar detalle I
                    $sql2 = "DELETE o.* FROM ";

                    if ($encabezado['tipo_archivo'] === 'I') {
                        $sql2 .= "rec_recaudo_planilla_detalle_is ";
                    } elseif ($encabezado['tipo_archivo'] === 'IP') {
                        $sql2 .= "rec_recaudo_planilla_detalle_i_pes ";
                    } elseif ($encabezado['tipo_archivo'] === 'IR') {
                        $sql2 .= "rec_recaudo_planilla_detalle_i_res ";
                    }

                    $idRecaudo = ((integer) $encabezado['consecutivo_recaudo_encabezado']);
                    $sql2 .= "AS o WHERE o.consecutivo_recaudo_encabezado = $idRecaudo";

                    DB::statement($sql);
                    DB::statement($sql1);
                    DB::statement($sql2);
                    Log::info('ERROR ELIMINACION 2', ['sql' => $sql, 'sql1' => $sql1, 'sql2' => $sql2]);
                }
                $encabezado->delete();

                if (!$borrador1) {
                    $borrador1 = true;
                    $encabezado = $encabezadoPar;
                    $borrador2 = ($encabezado == null);
                } elseif (!$borrador2) {
                    $borrador2 = true;
                }

            }
            DB::statement("SET FOREIGN_KEY_CHECKS = 1");

            return response()->json([
                'data' => true
            ],200);

        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception->getMessage()
            ],500);
        }



    }

}