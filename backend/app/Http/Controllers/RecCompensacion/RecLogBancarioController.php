<?php

namespace App\Http\Controllers\RecCompensacion;

use App\Http\Resources\Resources\RecLogBancarioResource;
use App\Models\Enums\EEstadoArchivoCompensacionRecaudo;
use App\Models\RecCompensacion\RecLogBancarioDetalleAportante;
use App\Models\RecCompensacion\RecLogBancarioDetalleAportanteSgp;
use App\Models\RecCompensacion\RecLogBancarioEncabezado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class RecLogBancarioController extends Controller
{
    protected $ruta;
    protected $directorio;

    public function __construct()
    {
        $this->ruta = "descarga/liquidacion_recaudos/zip";
        $this->directorio = Storage::disk('local');
        $this->tipoArchivoLogBancario = [
            ['tipo'=> 'ESSC33', 'value' => 1],
            ['tipo'=> 'SPSC33', 'value' => 2],
        ];
    }

    public function index ()
    {
        $query = RecLogBancarioEncabezado::pimp();

        $per_page = Input::get('per_page');

        if ($per_page) {
            return RecLogBancarioResource::collection($query->orderBy('fecha_grabado', 'desc')->paginate($per_page));
        }

        return RecLogBancarioResource::collection($query->orderBy('fecha_grabado', 'desc')->get());
    }

    public function store (Request $request) {
        try {
            $file = $request->file('archivo');
            $resultado = collect();

            $datos = $this->extraerArchivos($request);

            if (!$datos->isEmpty()) {
                $resultado = $this->getGuardarLogBancario($datos, $file);
            }

            return response()->json([
                'data' => $resultado->toArray()
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception->getMessage(),
                'message' => $exception
            ],500);
        }
    }

    public function validacionesEstructuraLogBancario($value)
    {
        $NRO_CARACTERES_MAX_TIPO_6 = 120;
        $NRO_CARACTERES_MIN_TIPO_6 = 100;
        $TIPO_REGISTRO_6 = "6";

        $errores = collect();

        foreach ($value['lista_registros'] as $key => $reg)  {
            $archivo_open = fopen($value['path_info'],'r');
            $registro = fgetcsv($archivo_open,0,";")[0];
            //dd(strlen($registro), $registro, ($TIPO_REGISTRO_6 === trim(substr($registro,0,1))));
            // die;

            if (!($TIPO_REGISTRO_6 === trim(substr($registro,0,1)))) continue;

            if (!((int) strlen($registro) >= $NRO_CARACTERES_MIN_TIPO_6 && (int) strlen($registro) <= $NRO_CARACTERES_MAX_TIPO_6)) {
                $errores->push("Error archivo log bancario: Nro. Carácteres invalido. Item de archivo $key");
            }
            else {
                if (empty(trim(substr($registro,1,16)))) {
                    $errores->push("Error archivo log bancario: Nro. id vacío.  Item de archivo $key");
                }

                if (empty(trim(substr($registro,17,16)))) {
                    $errores->push("Error archivo log bancario: Nombre aportante vacío.  Item de archivo $key");
                }

                if (empty(trim(substr($registro,33,8)))) {
                    $errores->push("Error archivo log bancario: Código banco autorizador vacío.  Item de archivo $key");
                } else {
                    if (!is_numeric(trim(substr($registro,33,8)))) {
                        $errores->push("Error archivo log bancario: El código banco autorizador no es númerico.  Item de archivo $key");
                    }
                }

                if (empty(trim(substr($registro,41,15)))) {
                    $errores->push("Error archivo log bancario: Número de planilla vacío.  Item de archivo $key");
                }

                if (empty(trim(substr($registro,56,6)))) {
                    $errores->push("Error archivo log bancario: Periodo de pago vacío.  Item de archivo $key");
                }

                if (empty(trim(substr($registro,62,2)))) {
                    $errores->push("Error archivo log bancario: Canal de pago vacío.  Item de archivo $key");
                }

                if (empty(trim(substr($registro,64,6)))) {
                    $errores->push("Error archivo log bancario: Número de registro vacío.  Item de archivo $key");
                } else {
                    if (!is_numeric(trim(substr($registro,64,6)))) {
                        $errores->push("Error archivo log bancario: El número de registro no es númerico.  Item de archivo $key");
                    }
                }

                if (empty(trim(substr($registro,72,18)))) {
                    $errores->push("Error archivo log bancario: Valor plantilla vacío.  Item de archivo $key");
                } else {
                    $valorPlanilla = trim(substr($registro,72,16)) . "." . trim(substr($registro,88,2));

                    if (!is_numeric($valorPlanilla)) {
                        $errores->push("Error archivo log bancario: Valor plantilla no númerico.  Item de archivo $key");
                    }
                }

                if (empty(trim(substr($registro,90,4)))) {
                    $errores->push("Error archivo log bancario: Hora minuto vacío.  Item de archivo $key");
                } else {
                    if (!is_numeric(trim(substr($registro,90,4)))) {
                        $errores->push("Error archivo log bancario: Hora minuto no es númerico.  Item de archivo $key");
                    }
                }

                if (empty(trim(substr($registro,94,6)))) {
                    $errores->push("Error archivo log bancario: Número de secuencia vacío.  Item de archivo $key");
                } else {
                    if (!is_numeric(trim(substr($registro,94,6)))) {
                        $errores->push("Error archivo log bancario: Número de secuencia no númerico.  Item de archivo $key");
                    }
                }
            }

        }

        return $errores;
    }

    // GUARDAR

    public function getGuardarLogBancario (Collection $datos, $file)
    {
        $resultado = collect();
        $numeroCuenta = "";
        $FECHA_PAGO = 0;
        $TIPO_ARCHIVO = 1;
        $TIPO_REGISTRO_6 = 6;

        $llaveValidacion = "";
        $errorValidacion = "";
        $sdf = new Carbon();

        $resultado->put('archivosProcesados', 0);
        $resultado->put('cargadosConExito', 0);
        $resultado->put('cargadosConInconsistencia', 0);
        $resultado->put('llavesArchivosExitosos', collect());
        $resultado->put('llavesArchivosInconsistentes', collect());


        $listaLogs = RecLogBancarioEncabezado::select('fecha_pago', 'tipo_archivo')->where('estado',EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId())->get();
        $setLogsBancariosGuardados = collect();

        foreach ($listaLogs as $key => $log) {
            $setLogsBancariosGuardados->put(($sdf->parse($log['fecha_pago'])->format('Ymd') . '_' . (string) $log['tipo_archivo']),0);
        }

        foreach ($datos->toArray() as $key => $value) {

            $errores = $this->validacionesEstructuraLogBancario($value);

            $valore = $resultado->offsetGet('archivosProcesados');
            $resultado->put('archivosProcesados',$valore + 1);

            $datosArchivo = explode('_',$value['nombre_archivo']);
            $cantidad = file($value['path_info']);

            $this->directorio->delete($this->ruta.'/'.$value['real_name'].'/'.File::name($value['path_info']));

            $encabezado = new RecLogBancarioEncabezado();
            $encabezado->fecha_pago = $sdf->parse($datosArchivo[$FECHA_PAGO])->format('Ymd');
            //$encabezado->tipo_archivo = ($datosArchivo[$TIPO_ARCHIVO] === 'ESSC33' ? 1 : ($datosArchivo[$TIPO_ARCHIVO] === 'SPSC33' ? 2 : NULL));
            $encabezado->tipo_archivo = ($datosArchivo[$TIPO_ARCHIVO] === 'EPSC25' ? 1 : ($datosArchivo[$TIPO_ARCHIVO] === 'EPS025' ? 2 : NULL));
            $encabezado->cantidad_archivos = count($cantidad);
            $encabezado->fecha_descarga = $sdf->now()->toDateString();
            $encabezado->fecha_cargue = $sdf->now()->toDateString();
            $encabezado->nombre_archivo = $value['nombre_archivo'];
            $encabezado->save();

            $llaveValidacion = $datosArchivo[$FECHA_PAGO].'_'.$encabezado->tipo_archivo;

            if ($errores->isEmpty()) {
                $encabezado->estado = 1;
                $encabezado->save();
                $valor = $resultado->offsetGet('cargadosConExito');
                $resultado->put('cargadosConExito',$valor + 1);
                $resultado->offsetGet('llavesArchivosExitosos')->push($encabezado->consecutivo_log_bancario_encabezado);
            } else {
                $encabezado->estado = 2;
                $encabezado->save();
                $valor = $resultado->offsetGet('cargadosConInconsistencia');
                $resultado->put('cargadosConInconsistencia',$valor + 1);
                $resultado->offsetGet('llavesArchivosInconsistentes')->push($encabezado->consecutivo_log_bancario_encabezado);
            }

            foreach ($value['lista_registros'] as $itemKey => $reg) {
                $registro = str_getcsv($reg['registro'])[0];
                if ($encabezado->tipo_archivo === 1) {

                    $detAportante = new RecLogBancarioDetalleAportante();
                    $detAportante->consecutivo_encabezado = $encabezado->consecutivo_log_bancario_encabezado;
                    $detAportante->tipo_registro = trim(substr($registro,0,1));
                    $detAportante->text_registro = $reg['registro'];

                    if (trim(substr($registro,0,1)) === 5) {
                        $numeroCuenta = trim(substr($registro,1,17));
                        $detAportante->numero_cuenta = $numeroCuenta;
                    } else if ($TIPO_REGISTRO_6 == trim(substr($registro,0,1))) {
                        if ($errores->isEmpty()) {
                            $detAportante->identificacion_aportante = trim(substr($registro,1,16));
                            $detAportante->nombre_aportante = trim(substr($registro,17,16));
                            $detAportante->codigo_banco_autorizador = trim(substr($registro,33,8));
                            $detAportante->numero_planilla_liquidacion = trim(substr($registro,41,15));
                            $detAportante->periodo_pago = trim(substr($registro,56,4)) . '-' . trim(substr($registro,60,2));
                            $detAportante->canal_pago = trim(substr($registro,62,2));
                            $detAportante->numero_registros = trim(substr($registro,64,6));
                            $detAportante->codigo_operador_informacion = trim(substr($registro,70,2));
                            $detAportante->valor_planilla = trim(substr($registro,72,16)) . "." . trim(substr($registro,88,2));
                            $detAportante->hora_minuto = trim(substr($registro,90,4));
                            $detAportante->numero_secuencia = trim(substr($registro,94,6));

                            if (strlen($registro) === 120) {
                                $detAportante->reservado = (trim(substr($registro, 100, 20)) === "" ? (NULL) : trim(substr($registro, 100, 20)));
                            }
                            $detAportante->numero_cuenta = $numeroCuenta;
                        } else {
                            $detAportante->text_error =  $reg['registro'];
                        }
                    }
                    $detAportante->save();
                }
                else {
                    $detAportanteSgp = new RecLogBancarioDetalleAportanteSgp();
                    $detAportanteSgp->consecutivo_encabezado = $encabezado->consecutivo_log_bancario_encabezado;
                    $detAportanteSgp->tipo_registro = trim(substr($registro,0,1));
                    $detAportanteSgp->text_registro = $reg['registro'];

                    if (trim(substr($registro,0,1)) === 5) {
                        $numeroCuenta = trim(substr($registro,1,17));
                        $detAportanteSgp->numero_cuenta = $numeroCuenta;
                    } else if ($TIPO_REGISTRO_6 == trim(substr($registro,0,1))) {
                        if ($errores->isEmpty()) {
                            $detAportanteSgp->identificacion_aportante = trim(substr($registro,1,16));
                            $detAportanteSgp->nombre_aportante = trim(substr($registro,17,16));
                            $detAportanteSgp->codigo_banco_autorizador = trim(substr($registro,33,8));
                            $detAportanteSgp->numero_planilla_liquidacion = trim(substr($registro,41,15));
                            $detAportanteSgp->periodo_pago = trim(substr($registro,56,4)) . '-' . trim(substr($registro,60,2));
                            $detAportanteSgp->canal_pago = trim(substr($registro,62,2));
                            $detAportanteSgp->numero_registros = trim(substr($registro,64,6));
                            $detAportanteSgp->codigo_operador_informacion = trim(substr($registro,70,2));
                            $detAportanteSgp->valor_planilla = trim(substr($registro,72,16)) . "." . trim(substr($registro,88,2));
                            $detAportanteSgp->hora_minuto = trim(substr($registro,90,4));
                            $detAportanteSgp->numero_secuencia = trim(substr($registro,94,6));

                            if (strlen($registro) === 120) {
                                $detAportanteSgp->reservado = (trim(substr($registro, 100, 20)) === "" ? (NULL) : trim(substr($registro, 100, 20)));
                            }
                            $detAportanteSgp->numero_cuenta = $numeroCuenta;
                        } else {
                            $detAportanteSgp->text_error =  $reg['registro'];
                        }
                    }
                    $detAportanteSgp->save();
                }
            }
        }

        $this->directorio->deleteDirectory($this->ruta);
        return $resultado;
    }
    
    // PRIVATE FUNCTION
    
    private function extraerArchivos(Request $request)
    {
        $archivo = $request->file('archivo');
        $nombre = $archivo->getClientOriginalName();
        $realName = explode('.', $nombre)[0];
        $ruta = $this->directorio->put($this->ruta, $archivo);
        $path = $this->directorio->path($ruta);
        //Storage::disk('local')->path('recaudos/' . $realName);
        if (File::isDirectory($this->directorio->path("$this->ruta/$realName"))) {
            File::deleteDirectory($this->directorio->path("$this->ruta/$realName"));
        }

        $zip = new \ZipArchive();
        $zip->open($path);
        $zip->extractTo($this->directorio->path("$this->ruta/$realName"));
        $zip->close();

        $files = File::allFiles($this->directorio->path("$this->ruta/$realName"));

        $datos = collect();
        if (count($files)>0 && !is_null($files)) {
            foreach ($files as $key => $file) {
                $fichero = $file;
                if (file_exists($fichero)) {
                    $dto = new Collection();
                    $dto->put('nombre_archivo', str_replace(".TXT",'',$fichero->getFilename()));
                    $dto->put('path_info', $fichero);
                    $dto->put('real_name', $realName);
                    $dto->put('lista_registros', collect());
                    $csvReader = file($fichero);
                    foreach ($csvReader as $csv) {
                        $dto->offsetGet('lista_registros')->push(['registro' => $csv]);
                    }
                    $datos->push($dto);
                }
            }
        }
        return $datos;
    }
}
