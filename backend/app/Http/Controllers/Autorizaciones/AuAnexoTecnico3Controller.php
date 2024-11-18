<?php

namespace App\Http\Controllers\Autorizaciones;

use App\Autorizaciones\AuAnexot3regs;
use App\Autorizaciones\Reforigenautorizacion;
use App\Exports\AutorizacioneesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Autorizaciones\Anexo3Request;
use App\Http\Resources\Autorizaciones\AnexoTecnico3Resource;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Autorizaciones\AuAnexot3;
use App\Models\Autorizaciones\AuAnexot31;
use App\Models\Autorizaciones\AuAnexot3Entrega;
use App\Models\Autorizaciones\AuAnexot3neg;
use App\Models\Autorizaciones\AuCopagoAnexot3;
use App\Models\Autorizaciones\AuEspecialidad;
use App\Models\Autorizaciones\AuOrigenAtencion;
use App\Models\Autorizaciones\Refcobertura;
use App\Models\Autorizaciones\Refcup;
use App\Models\Autorizaciones\Refcupsdet;
use App\Models\Autorizaciones\Refmodalidadservicio;
use App\Models\Autorizaciones\Refmotivoneg;
use App\Models\Autorizaciones\Refnivelatencion;
use App\Models\Autorizaciones\Refqx;
use App\Models\Autorizaciones\Refrecobro;
use App\Models\Autorizaciones\Refsersol;
use App\Models\Autorizaciones\RefUbic;
use App\Models\Autorizaciones\Refviasol;
use App\Models\General\GnArchivo;
use App\Models\RedServicios\Refntcie10gestante;
use App\Models\RedServicios\RsCie10;
use App\Models\RedServicios\RsCumcontratados;
use App\Models\RedServicios\RsCuotacopago;
use App\Models\RedServicios\RsCups;
use App\Models\RedServicios\RsCupscontratados;
use App\Models\RedServicios\RsEntidade;
use App\Models\RedServicios\RsOtroscontratado;
use App\Models\RedServicios\RsSalminimo;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AuAnexoTecnico3Controller extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            return AnexoTecnico3Resource::collection(AuAnexot3::funcionario()->with('afiliado', 'usuarioProceso')
                ->where('sw_espera',0)
                ->pimp()->orderBy('updated_at', 'desc')->paginate(Input::get('per_page')));
        }

        return AnexoTecnico3Resource::collection(AuAnexot3::funcionario()->with('afiliado', 'usuarioProceso')
            ->pimp()->orderBy('updated_at', 'desc')->get());
    }

    public function indexPrestadotes()
    {
        if (Input::get('per_page')) {
            return AnexoTecnico3Resource::collection(AuAnexot3::isEntidad()->with('afiliado', 'usuarioProceso')
                ->where('sw_espera',0)
                ->pimp()->orderBy('updated_at', 'desc')->paginate(Input::get('per_page')));
        }

        return AnexoTecnico3Resource::collection(AuAnexot3::isEntidad()->with('afiliado', 'usuarioProceso')
            ->pimp()->orderBy('updated_at', 'desc')->get());
    }

    public function indexSolicitudesPorAprobar()
    {
        if (Input::get('per_page')) {
            return AnexoTecnico3Resource::collection(AuAnexot3::funcionario()
                ->with('afiliado', 'usuarioProceso')
                ->where('sw_espera',1)
                ->pimp()->orderBy('updated_at', 'desc')->paginate(Input::get('per_page')));
        }
    }

    public function indexSolicitudesPorAprobarPrestadores()
    {
        if (Input::get('per_page')) {
            return AnexoTecnico3Resource::collection(AuAnexot3::isEntidad()
                ->with('afiliado', 'usuarioProceso')
                ->where('sw_espera',1)
                ->pimp()->orderBy('updated_at', 'desc')->paginate(Input::get('per_page')));
        }
    }

    public function store(Anexo3Request $request)
    {
        try {
            DB::beginTransaction();
            $anexo = new AuAnexot3();
            $anexo->fill($request->except('gn_archivo_id','sw_espera'));

            if ($request->historia_clinica !== null) {
                $anexo->gn_archivo_id = $this->subirArchivoB64($request->historia_clinica);
            }

            if ($this->validaNoigual($request)) {
                throw new \Exception("⚠ Existe uno o varios registros repetidos entre los servicios solicitados.", 400);
            }

            if ($this->validaFrecuenciaDeUso($request) !== []) {
                $vals = $this->validaFrecuenciaDeUso($request);
                foreach ($vals as $val) {
                    throw new \Exception("La frecuencia de uso del servicio #".$val['servicio'].", no ha cumplido el " . $val['frecuencia'] . " de Uso.");
                }
            }

            $datos = array();
            foreach ($request->detalles as $key => $detalle) {
                $cup = RsCups::where('codigo',$detalle['codigo'])->first();
                if ($cup['sw_requiere_permiso_especial'] === 1) {
                    array_push($datos, $detalle['codigo']);
                    //$datos->push($detalle['codigo']);
                }
            }
            //$this->validaServicioRequiereAprobacion($request, $datos);

            $anexo->sw_espera = (count($datos) > 0) ? 1 : 0;

            $anexo->save();
            $anexo->detalles()->createMany($request->detalles);
            $anexo->load(AuAnexot3::allRelations());
            DB::commit();
            $this->updateAfiliado($request, $anexo);
            $this->saveRegimenAfiliado($anexo);

            return response()->json([new Resource($anexo)])->setStatusCode(201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'errors' => $e->getMessage(),
                'msg' => $e,
                'code' => $e->getCode(),
            ], 500);
        }
    }

    public function show(AuAnexot3 $anexot3)
    {
        return new Resource($anexot3->load(AuAnexot3::allRelations()));
    }

    public function update(Anexo3Request $request, $anexot3)
    {
        try {
            $anexot = AuAnexot3::with('detalles')->find($anexot3);
            $anexot->fill($request->except('historia_clinica'));

            if ($request->ind === '2') {
                $this->anularAt3($request, $anexot);
            } else {
                $detalles = $anexot->detalles;
                if ($this->validaNoigual($request)) {
                    throw new \Exception("⚠ Existe uno o varios registros repetidos entre los servicios solicitados.", 400);
                }
                $this->procesoDetallesAnexot3($request, $detalles);
            }
            $anexot->save();

            $anexot->load(AuAnexot3::allRelations());

            return response()->json(['data' => new Resource($anexot)])->setStatusCode(202);
        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception->getMessage(),
                'msg' => $exception,
                'code' => $exception->getCode(),
            ], 500);
        }
    }

    /**
     * @param $request
     * @param $detalles
     */
    public function procesoDetallesAnexot3($request, $detalles): void
    {
        foreach ($request->detalles as $key => $detail) {
            $copago = $detail['copago'];
            $anexot3 = AuAnexot3::whereId($detail['au_anexot3s_id'])->first();
            $cobertura = Refcobertura::whereCodigo($detail['cober'])->first();

            $rangoActivo = null;
            $valorUsuario = 0;
            $isCopaOrCuo = null; // 1 = Copago, 2 = Cuota

            if ($request->ind === '3') {
                if ($detail['ind'] === 1 && ! isset($detail['negacion'])) {
                    $valorTotal = ($detail['valor'] * $detail['cAut']);
                    if ((! $this->validateCubrioCopagoAnio($anexot3->afiliado, 1)) || (! $this->noCopaOCuota($anexot3)) || (! $this->isNivelOorN($anexot3->afiliado) && $anexot3->afiliado['as_regimene_id'] === 2)) {
                        if ($this->siCopago($detail, $cobertura, $anexot3)) {
                            if ($anexot3->afiliado['as_regimene_id'] === 2 && $anexot3->afiliado['nivel_sisben'] >= '2') {
                                $isCopaOrCuo = 1;
                                $rangoActivo = RsCuotacopago::where('grupo', '=', 'Sisben Nivel 2')->whereHas('salminimo', function (
                                    $query
                                ) {
                                    $query->where('anio', Carbon::now()->year);
                                })->first();
                            }
                        } else {
                            if ($anexot3->afiliado['as_regimene_id'] === 1) {
                                $isCopaOrCuo = 2;
                                $cuotas = RsCuotacopago::where('grupo', '<>', 'Sisben Nivel 2')->whereHas('salminimo', function (
                                    $query
                                ) {
                                    $query->where('anio', Carbon::now()->year);
                                })->get();
                                foreach ($cuotas as $cuota) {
                                    $salMinimoCotizante = null;
                                    if ($this->isBeneficiarioCotizante($anexot3->afiliado)) {
                                        $salMinimoCotizante = ($anexot3->afiliado['cabeza']['ingreso_total'] / $cuota['salminimo']['valor']);
                                    } else {
                                        $salMinimoCotizante = ($anexot3->afiliado['ingreso_total'] / $cuota['salminimo']['valor']);
                                    }
                                    if (($cuota->limite_inferior_salario === 'Incluido' ? ($salMinimoCotizante >= $cuota['salminimos_inicio']) : ($salMinimoCotizante > $cuota['salminimos_inicio'])) && ($cuota->limite_superior_salario === 'Incluido' ? ($salMinimoCotizante <= $cuota['salminimos_fin']) : ($salMinimoCotizante < $cuota['salminimos_fin']))) {
                                        $rangoActivo = $cuota;
                                    }
                                }
                            }
                        }
                        if ($rangoActivo !== null) {
                            if ($anexot3->afiliado['as_regimene_id'] === 1 || $this->isBeneficiarioCotizante($anexot3->afiliado)) {
                                $valorUsuario = $rangoActivo['cuota_moderadora'];
                            } else {
                                $valorUsuario1 = $valorTotal * ($rangoActivo['copago'] / 100);
                                $valorUsuario = $this->getValorUsuarioTopes($valorUsuario1, $rangoActivo, $anexot3);
                            }
                        }
                    }
                    $detail['fS'] = today()->toDateString();
                }
            }

//            $detail['copago'] = ($valorUsuario === 0) ? ($valorUsuario = 0) : ((double)$valorUsuario);

            if ($detail['id'] !== null) {
                foreach ($detalles as $detalle) {
                    if ($detalle->id === $detail['id']) {
                        $ref = AuAnexot31::with('usuarioValida', 'negacion')->where('id', $detalle->id)->first();

                        $detail['copago'] = $copago;

                        $ref->update((array) $detail);

                        $ref = AuAnexot31::find($detail['id']);
                        $ref->copago = $copago;

                        $ref->save();

                        $this->registroDetAutONeg($detail, $anexot3, $ref, $valorUsuario, $isCopaOrCuo);
                    }
                }
            } else {
                $ref = AuAnexot31::with('negacion')->create((array) $detail);
                $this->registroDetAutONeg($detail, $anexot3, $ref, $valorUsuario, $isCopaOrCuo);
            }
        }
    }

    public function validaNoigual($request)
    {
        $data = [];
        foreach ($request->detalles as $detail) {
            $val = array_count_values(array_column($request->detalles, 'codigo'))[$detail['codigo']];
            if ($val > 1) {
                array_push($data, $detail['codigo']);
            }
        }

        return (count($data) > 0) ? (true) : (false);
    }

    /**
     * @author JORGE HERNANDEZ 06/04/2020
     * @param $request
     * @return array
     */
    public function validaFrecuenciaDeUso($request):array {
        $detalles = $request->detalles;

        $data = [];

        foreach ($detalles as $detalle) {
            $cup = RsCups::find($detalle['id']);

            $serviciosAutorizados = AuAnexot3::with('anulada')->select(
                'au_anexot3s.id as id_cabecera',
                'au_anexot3s.as_afiliado_id',
                'b.id as id_detalle',
                'b.codigo',
                'b.descrip',
                'au_anexot3s.fS as fecha_auto',
                'b.fS as fecha_detauto')
                ->leftJoin('au_anexot31s as b','b.au_anexot3s_id','=','au_anexot3s.id')
                ->where('au_anexot3s.as_afiliado_id',$request->as_afiliado_id)
                ->where('b.codigo',$detalle['codigo'])
                ->where('b.ind',1)
                ->whereNotNull('b.cAut')
                ->latest('b.id')->first();

            if ($cup['codigo'] === $serviciosAutorizados['codigo']) {
                $date1 = Carbon::parse($serviciosAutorizados['fecha_detauto']);
                $date2 = Carbon::now();
                $diff = $date1->diff($date2);

                if ($cup['frecuencia_uso'] === 'Anual') {
                    if ($diff->days <= 365) {
                        array_push($data, [
                            'servicio' => $serviciosAutorizados['codigo'],
                            'frecuencia' => 'Año'
                        ]);
                    }
                } else if ($cup['frecuencia_uso'] === 'Mensual'){
                    if ($diff->days <= 30) {
                        array_push($data, [
                            'servicio' => $serviciosAutorizados['codigo'],
                            'frecuencia' => 'Mes'
                        ]);
                    }
                } else if ($cup['frecuencia_uso'] === 'Diario') {
                    if ($diff->h < 24) {
                        array_push($data, [
                            'servicio' => $serviciosAutorizados['codigo'],
                            'frecuencia' => 'Día'
                        ]);
                    }
                } else if ($cup['frecuencia_uso'] === 'Vida') {
                    if ($diff->days > 365) {
                        array_push($data, [
                            'servicio' => $serviciosAutorizados['codigo'],
                            'frecuencia' => 'Vida'
                        ]);
                    }
                }
            }
        }

        return $data;
    }

    public function registroDetAutONeg($detail, $anexot3, $ref, $valorUsuario, $isCopaOrCuo)
    {
        if (! is_null($isCopaOrCuo) && $detail['ind'] === 1 && ! isset($detail['negacion'])) {
            $this->guardarRefValorAutorizado($anexot3, $valorUsuario, $ref, $isCopaOrCuo);
        }
        if (isset($detail['negacion']) && ! isset($ref['negacion'])) {
            $this->negacionServicio($detail, $ref);
        }
    }

    // Pendiente Revisar el calculo cuando es cuota moderadora para el afiliado contributivo
    public function getValorUsuarioTopes($valor, $rango, AuAnexot3 $anexot3)
    {
        $valorTemp = $valor;
        $totalAutorizado = $this->validateCubrioCopagoAnio($anexot3->afiliado, 2);
        // $tope por evento Y por año
        if ($totalAutorizado < $rango->limite_anio) {
            $valorTemp = ($valorTemp > $rango->limite_evento) ? $rango->limite_evento : $valorTemp;
        } else {
            $diferencia = ($valorTemp + $totalAutorizado) - $rango->limite_anio;
            $valorTemp = ($diferencia > 0) ? ($valorTemp - $diferencia) : $valorTemp;
        }

        return $valorTemp;
    }

    public function destroy($id)
    {
    }

    public function anularAt3(Request $request, AuAnexot3 $anexot)
    {
        $anexot->anulada()->create(['observacion' => $request->observacion]);
        foreach ($anexot->detalles as $detalle) {
            $detalle->update(['ind' => $request->ind]);
        }

        return $anexot;
    }

    private function subirArchivoB64($archivo)
    {
        if (! $archivo) {
            return null;
        }

        if (! isset($archivo['string'])) {
            return null;
        }
        $array_base64 = explode(';', $archivo['string']);
        $file = explode(',', $array_base64[1]);
        $file = base64_decode($file[1]);

        $anioMes = today()->format('Ym');
        $ruta = 'Autorizaciones/'.$anioMes.'/'.$archivo['name'];
        Storage::put($ruta, $file);

        $archivo = GnArchivo::create([
            'nombre' => $archivo['name'],
            'size' => $archivo['size'],
            'extension' => $archivo['type'],
            'ruta' => $ruta,
        ]);

        return $archivo->id;
    }

    public function getEspecialidadOfRefCup(Refcup $refcup)
    {
        try {
            $especialidades = null;
            $nivelatencion = null;

            if ($refcup->esp === '' && $refcup->nivel === '') {
                $nivelatencion = Refnivelatencion::all();
            } else {
                $nivelatencion = Refnivelatencion::where('codigo', '=', $refcup->nivel)->get();
            }

            $modalidades = ($refcup->ambito === '' || $refcup === null) ? Refmodalidadservicio::all() : Refmodalidadservicio::whereCodigo($refcup->ambito)->get();

            $quirurgicos = ($refcup->Qx === '') ? Refqx::all() : Refqx::whereCodigo($refcup->Qx)->get();

            $refcoberturas = ($refcup->cober === '') ? Refcobertura::all() : Refcobertura::whereCodigo($refcup->cober)->get();

            $all_especialidades = AuEspecialidad::all();

            return response()->json([
                'especialidades' => new Resource($all_especialidades),
                'nivelatencion' => new Resource($nivelatencion),
                'quirurgicos' => new Resource($quirurgicos),
                'modalidades' => new Resource($modalidades),
                'coberturas' => new Resource($refcoberturas),
            ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function validacionEdadesAndGenero(AsAfiliado $afiliado)
    {
        $codCie10 = Input::get('cie10');
        $codCup = Input::get('refcup');

        $cie10 = RsCie10::whereCodigo($codCie10)->first() ?? null;
        $refcup = Refcup::whereCodigo($codCup)->first() ?? null;
        $refcupdet = Refcupsdet::whereCodigo($codCup)->first() ?? null;

        $edad_full = explode(',', $afiliado->edad_full, 3);
        $años = (int) $edad_full[0];
        $meses = (int) $edad_full[1];
        $dias = (int) $edad_full[2];
        $edad = null;
        if (isset($refcupdet) && isset($refcup)) {
            if ($refcupdet->eMed === 'A') {
                $edad = (int) $años;
            } elseif ($refcupdet->eMed === 'M') {
                $edad = (int) (($años * 12) + $meses);
            } elseif ($refcupdet->eMed === 'D') {
                $edad = (int) (($años * 365) + ($meses * 30) + $dias);
            }
        } else {
            if (isset($cie10)) {
                $edad = $afiliado->edad;
            }
        }

        $genero = $cie10['genero'] ?? $refcup['genero'] ?? null;

        $eMin = (integer) ($refcupdet['eMin'] ?? substr($cie10['edad_min'], 0, 3));
        $eMax = (integer) ($refcupdet['eMax'] ?? substr($cie10['edad_max'], 0, 3));

        $mensaje = (isset($refcup)) ? "servicio # $refcup->codigo" : "diagnóstico # $cie10->codigo";
        $respuesta = null;

        if ($genero !== null && $this->isNotGenero($afiliado, $genero)) {
            $respuesta = "El $mensaje no cumple con el genero del afiliado.";
        }

        if (($edad !== null) && ! $this->isRangoDeEdad($edad, $eMin, $eMax)) {
            $respuesta = "La edad del afiliado esta fuera del rango de edades del $mensaje .";
        }

        return response()->json(['respuesta' => $respuesta])->setStatusCode(200);
    }

    public function getValServicioODxInAnio()
    {
        $codCie10 = Input::get('cie10');
        $codCup = Input::get('refcup');
        $idAfiliado = (int) Input::get('idAfiliado');
        $afiliado = AsAfiliado::find($idAfiliado);

        $valServInAnio = DB::select("SELECT COUNT(*) AS codigo FROM au_anexot31s AS a
                                    LEFT JOIN au_anexot3s AS b ON b.id = a.au_anexot3s_id
                                    WHERE b.as_afiliado_id = '{$idAfiliado}' AND a.ind ='1'
                                    AND a.codigo = '{$codCup}' AND YEAR(a.fS) = YEAR(CURDATE())")[0]->codigo;

        $valNoConxDx = DB::select("SELECT COUNT(*) AS n FROM au_anexot31s AS a 
                                        LEFT JOIN au_anexot3s AS b ON b.id = a.au_anexot3s_id
                                        LEFT JOIN refcupsconprives AS c ON c.codigo = a.codigo
                                        WHERE c.codigo is not null and b.as_afiliado_id = '{$idAfiliado}'
                                        and b.dPrin = '{$codCie10}' and c.codigo = '{$codCup}'
                                        and cAut > 0 and a.ind ='1'
                                        GROUP BY  b.as_afiliado_id, a.codigo, b.dPrin, year(b.fecha)");
        $msg = "El servicio #$codCup ya fue autorizado";
        if ($valServInAnio !== 0) {
            $advertencia = "$msg para $afiliado->nombre_completo";
        }
        if ($valNoConxDx !== []) {
            $respuesta = "$msg por el mismo Dx #$codCie10 para $afiliado->nombre_completo";
        }

        return response()->json([
            'advertencia' => isset($advertencia) ? $advertencia : false,
            'respuesta' => isset($respuesta) ? $respuesta : false,
        ]);
    }

    public function getPlanesCobertura($codigo)
    {
        try {
            $cup = Refcup::whereCodigo($codigo)->first();
            $code = null;
            $item = null;
            $regimen = (integer) Input::get('regimen');
            if ($cup->AT === 'P' || $cup->AT === 'C' || $cup->AT === '3' || $cup->AT === '2' || $cup->AT === '4') {
                $datos = $this->consultaServiciosContratados('rs_cupscontratados', $codigo, 'a.rs_cups_id',$regimen);
                $item = '1';
            } else {
                if ($cup->AT === 'M') {
                    $datos = $this->consultaServiciosContratados('rs_cumcontratados', $codigo, 'a.rs_cum_id',$regimen);
                    $item = '2';
                } else {
                    if ($cup->AT === '1') {
                        $datos = $this->consultaServiciosContratados('rs_otroscontratados', $codigo, 'a.rs_otrosentidad_id',$regimen);
                        $item = '3';
                    } else {
                        $code = 404;
                        throw new \Exception('No existe el atributo AT para buscar entre los contratos', $code);
                    }
                }
            }

            $data = $this->obtenerServicios($datos, $item);

            if (count($data) <= 0) {
                $code = 404;
                throw new \Exception('El servicio no se encuentra contratado.', $code);
            }

            return $data;
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()])->setStatusCode(($code === null) ? 500 : $code);
        }
    }

    /**
     * @param $nombre
     * @param $codigo
     * @param $campo
     * @return array
     */
    public function consultaServiciosContratados(string $nombre, string $codigo, $campo, $regimen): array
    {
        $verificar = DB::select("SHOW COLUMNS FROM {$nombre} WHERE Field = 'rs_contrato_id'");

        $rs_contrato_id = "a.".($verificar !== [] ? "rs_contrato_id" : "rs_contratos_id");

        $data = DB::select("SELECT a.id AS id_servcontratado,
                                 a.codigo,
                                 {$rs_contrato_id},
                                 {$campo},
                                 a.tarifa,
                                 b.id AS id_plancobertura,
                                 b.nombre AS nombre_plan,
                                 b.regimen_atendido,
                                 c.id AS id_contrato,
                                 c.numero_contrato,
                                 c.rs_entidad_id,
                                 d.id AS id_entidad,
                                 d.nombre AS nombre_entidad
                        FROM {$nombre} AS a
                        LEFT JOIN rs_planescoberturas AS b ON b.id = {$rs_contrato_id}
                        LEFT JOIN ce_proconminutas AS c ON c.id = b.ce_proconminuta_id
                        LEFT JOIN rs_entidades AS d ON d.id = c.rs_entidad_id
                        WHERE {$rs_contrato_id} IS NOT NULL AND a.codigo = '{$codigo}' AND b.regimen_atendido = '{$regimen}' AND c.estado = 'Legalizado'");

        return $data;
    }

    public function obtenerServicios($datos, $code)
    {
        $data = [];
        $newDatos = json_decode(json_encode($datos));
        $column = '';

        switch ($code) {
            case '1':
                $column = 'rs_cups_id';
                break;
            case '2':
                $column = 'rs_cum_id';
                break;
            case '3':
                $column = 'rs_otrosentidad_id';
                break;
        }

        foreach ($newDatos as $key => $dato) {
            $column_contrato = isset($dato->rs_contrato_id) ? 'rs_contrato_id' : 'rs_contratos_id';
            array_push($data, [
                'id' => $dato->id_servcontratado,
                'codigo' => $dato->codigo,
                'tarifa' => $dato->tarifa,
                $column => $code === '1' ? $dato->rs_cups_id : ($code === '2' ? $dato->rs_cum_id : $dato->rs_otrosentidad_id),
                $column_contrato => $dato->rs_contrato_id ?? $dato->rs_contratos_id,
                'plan_cobertura' => [
                    'id' => $dato->id_plancobertura,
                    'nombre' => $dato->nombre_plan,
                    'regimen_atendido' => $dato->regimen_atendido,
                    'contrato' => [
                        'id' => $dato->id_contrato,
                        'numero_contrato' => $dato->numero_contrato,
                        'rs_entidad_id' => $dato->rs_entidad_id,
                        'entidad' => [
                            'id' => $dato->id_entidad,
                            'nombre' => $dato->nombre_entidad,
                        ],
                    ],
                ],
            ]);
        }

        return $data;
    }

    public function getInformeEstadisticaUsuario(Excel $excel)
    {
        $fecha_inicio = Input::get('fecha_inicio');
        $fecha_fin = Input::get('fecha_fin');
        $user = Input::get('user');
        $informe = DB::select("SELECT c.name AS autorizador, 
                                        COUNT(*) AS cantidad
                                FROM au_anexot31s AS a
                                LEFT JOIN au_anexot3s AS b ON b.id = a.au_anexot3s_id
                                LEFT JOIN gs_usuarios AS c ON c.id = b.gs_usuario_id
                                WHERE a.gs_usuario_id = '{$user}' AND a.ind!=2 AND a.cAut > 0 AND '{$fecha_inicio}' <= b.fecha AND b.fecha <= '{$fecha_fin}'");
        $data = json_decode(json_encode($informe), true);

        if ($data[0]['cantidad'] === 0) {
            $usuario = strtoupper(User::whereId((int) $user)->first()->name);
            $data[0]['cantidad'] = "Usuario: $usuario, no presenta autorizaciones entre las fechas $fecha_inicio - $fecha_fin";
            //throw new \Exception("Usuario: $usuario, no presenta autorizaciones entre las fechas $fecha_inicio - $fecha_fin", 422);
        }

        return $excel::download(new AutorizacioneesExport($data), 'informe_autorizacion_usuario.xlsx');
    }

    /**
     * @param $anexotCabeza
     * @return bool
     */
    public function noCopaOCuota($anexotCabeza): bool
    {
        return ($this->isDxGestante($anexotCabeza)) || ($this->esAltoCosto($anexotCabeza) || ($this->isPoblacionEspecial($anexotCabeza['afiliado'])));
    }

    public function getEntidadesPdf(AuAnexot3 $anexo)
    {
        $entidades = [];
        foreach ($anexo->detalles as $key => $detalle) {
            array_push($entidades, $detalle['entidad']);
        }

        return response()->json(['data' => (array) $entidades])->setStatusCode(200);
    }

    public function getPdf()
    {
        try {
            $anexo = AuAnexot3::find(Input::get('id'));
            $entidad = RsEntidade::with('tercero')->find(Input::get('entidad_id'));
            $anexo->load(AuAnexot3::allRelations());
            $recibido = Input::get('recibido') === "null" ? null : Input::get('recibido');
            $idDetalles = json_decode(Input::get('idDetalles')) ?? null;
            if (! is_null($recibido) || trim($recibido) !== '') {
                $this->guardarQuienRecibe($recibido, $anexo);
            }

            $dataPdf = [
                'autaprobada' => $anexo,
                'imprime' => User::find(Input::get('user')),
                'entidad_autorizada' => isset($entidad) ? $entidad : null,
                'imp' => $this->esONoCopiaImpresion($anexo, $entidad),
                'valor_x_entidad' => isset($entidad) ? $this->getValorTotalIps($anexo, $entidad) : null,
                'recibido' => (! is_null($recibido) || trim($recibido) !== '') ? $recibido : null,
                'servicios' => ! is_null($idDetalles) ? $this->getEntidades($anexo, $idDetalles) : null,
            ];

            if (Input::get('html')) {
                if (is_null($idDetalles)) {
                    return view('dompdf.autorizacion', $dataPdf);
                } else {
                    return view('dompdf.autorizacion_por_detalles', $dataPdf);
                }
            }
            //return view('dompdf.autorizacion',$dataPdf);

            if (is_null($idDetalles)) {
                $pdf = PDF::loadView('dompdf.autorizacion', $dataPdf);
            } else {
                $pdf = PDF::loadView('dompdf.autorizacion_por_detalles', $dataPdf);
            }

            //$pdf = PDF::loadView('dompdf.autorizacion', $dataPdf);

            $pdf->setPaper('letter', 'portrait');

            return $pdf->stream('Autorización de Servicios Médicos Asistenciales', ['Attachment' => 0]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getValorTotalIps($anexo, $value)
    {
        $anexot = collect($anexo->detalles);

        $suma = $anexot->where('pAut', '=', $value->id)->where('ind', '=', 1)->whereNotIn('cAut', [
            null,
            0,
        ])->sum(function ($query) {
            return ($query->valor * $query->cAut);
        });

        $copago = $anexot->where('pAut', '=', $value->id)->where('ind', '=', 1)->whereNotIn('cAut', [
            null,
            0,
        ])->sum(function ($query) {
            return ($query->copago);
        });

        return ['valor' => $suma, 'copago' => $copago];
    }

    public function getEntidades($anexo, $items)
    {
        //$valor = collect($anexo->detalles);
        $valores = [];
        foreach ($items as $item) {
            array_push($valores, AuAnexot31::with('entidad')->find($item));
        }

        $details = [];
        //$entitys = $valor->groupBy('pAut');
        $entitys = collect($valores)->groupBy('pAut');

        foreach ($entitys as $key => $entity) {
            $suma = 0;
            $copago = 0;
            $entidad_id = null;
            $entidad = null;
            $idDetalles = [];
            foreach ($entitys[$key] as $iKey => $end) {
                //$valorMultiplicado = 0;
                foreach ($items as $item) {
                    if ($end->id === $item && ($end->ind === 1 && ($end->cAut !== null || $end->cAut !== 0))) {
                        $valorMultiplicado = $end->valor * $end->cAut;
                        $suma += $valorMultiplicado;
                        $copago += $end->copago;
                        array_push($idDetalles, $item);
                    }
                }
                $entidad_id = $end->pAut;
                $entidad = $end->entidad;
            }
            array_push($details, [
                'entidad_id' => $entidad_id,
                'entidad' => $entidad,
                'valor_x_entidad' => (double) $suma,
                'valor_copago' => (double) $copago,
                'detallesPermitidos' => $idDetalles,
            ]);
        }

        return $details;
    }

    public function recobros(AuAnexot31 $anexot31)
    {
        $anexotCabeza = AuAnexot3::whereId($anexot31->au_anexot3s_id)->first();
        $ref = collect(Refrecobro::all());
        $recobro = null;
        if ($anexotCabeza->oriAut === '1') {
            switch ($anexot31->nivel) {
                case '1':
                    $recobro = $ref->whereCodigo(5);
                    if ($anexot31->cober === '8') {
                        $recobro = $ref->where('codigo', 5)->where('codigo', 8);
                    }
                    break;
                case ('2' || '3'):
                    $recobro = $ref->where('codigo', 6)->where('codigo', 7)->where('codigo', 9);
                    break;
                default:
                    $recobro = $ref->where('codigo', 4)->where('codigo', 9);
            }
        } else {
            if ($anexotCabeza->oriAut === '2') {
                $recobro = $ref->where('codigo', 3);
            }
            if ($anexotCabeza->oriAut === '3') {
                $recobro = $ref->where('codigo', 1)->where('codigo', 3);
            }
        }

        return response()->json(['data' => new Resource($recobro->get())])->setStatusCode(200);
    }

    // Complementos

    public function getComplementosAnexo()
    {
        $complementos = collect();
        $complementos->put('origenatencion', AuOrigenAtencion::all());
        $complementos->put('refubics', RefUbic::all());
        $complementos->put('origenesautorizacion', Reforigenautorizacion::whereCodigo('1')->get());
        $complementos->put('tiposServiciosSolicitados', Refsersol::all());
        $complementos->put('viasSolicitud', Refviasol::all());

        return response()->json([
            'data' => $complementos,
        ], 200);
    }

    public function getComplementosModal()
    {
        $complementos = collect();
        $complementos->put('motivosnegacion', Refmotivoneg::all());

        return response()->json(['data' => $complementos])->setStatusCode(200);
    }

    /**
     * @param $afiliado
     * @param $param
     * @return bool|float
     */
    public function validateCubrioCopagoAnio($afiliado, $param)
    {
        $anio_actual = Carbon::now()->year;
        if ($afiliado['as_regimene_id'] === 1 || $this->isBeneficiarioCotizante($afiliado)) {
            $valor_anio = $this->sumaCopaOrCutoa($afiliado, $anio_actual, 'cuota_moderadora');
        } else {
            $valor_anio = $this->sumaCopaOrCutoa($afiliado, $anio_actual, 'copago');
        }
        $salario_minimo = RsSalminimo::whereAnio($anio_actual)->first()['valor'];

        return ($param === 1) ? (($valor_anio >= $salario_minimo) ? (true) : (false)) : ((double) $valor_anio);
    }

    /**methods private**/

    /**
     * @param Anexo3Request $request
     * @param AuAnexot3 $anexo
     * @return mixed
     */
    private function updateAfiliado(Anexo3Request $request, AuAnexot3 $anexo)
    {
        $afiliado = AsAfiliado::find($anexo->as_afiliado_id);
        $afiliado->gn_vereda_id = is_null($request->datos_afiliado['gn_vereda_id']) ? null : $request->datos_afiliado['gn_vereda_id'];
        $afiliado->gn_barrio_id = is_null($request->datos_afiliado['gn_barrio_id']) ? null : $request->datos_afiliado['gn_barrio_id'];
        $afiliado->direccion = $request->datos_afiliado['direccion'];
        $afiliado->correo_electronico = $request->datos_afiliado['correo_electronico'];
        $afiliado->celular = $request->datos_afiliado['celular'];
        $afiliado->save();

        return $afiliado;
    }

    /**
     * @param AuAnexot3 $anexo
     * @return mixed
     */
    private function saveRegimenAfiliado(AuAnexot3 $anexo)
    {
        return AuAnexot3regs::create([
            'as_regimene_id' => $anexo->afiliado['as_regimene_id'],
            'au_anexot3_id' => $anexo->id,
        ]);
    }

    /**
     * @param $afiliado
     * @return bool
     */
    private function isNivelOorN($afiliado): bool
    {
        return $afiliado['nivel_sisben'] === '' || $afiliado['nivel_sisben'] === 'N';
    }

    /**
     * @param $afiliado
     * @return bool
     */
    private function isPoblacionEspecial($afiliado)
    {
        $poblacion = null;
        foreach ([2, 1, 17, 16, 11, 14] as $item) {
            if ($item === $afiliado['as_pobespeciale_id']) {
                $poblacion = $item;
            }
        }

        return boolval($poblacion);
    }

    /**
     * @param $anexot3
     * @return bool
     */
    private function isBeneficiarioCotizante($afiliado): bool
    {
        return $afiliado['as_tipafiliado_id'] === 3 && ($afiliado['cabeza']['as_tipafiliado_id'] === 1);
    }

    /**
     * @param $anexot3
     * @param $valorUsuario
     * @param $detail
     * @param $param
     * @return mixed
     */
    private function guardarRefValorAutorizado($anexot3, $valorUsuario, $detail, $param)
    {
        AuCopagoAnexot3::create([
            'as_afiliado_id' => $anexot3->afiliado['id'],
            'copago' => ($param === 1 ? $valorUsuario : 0),
            'cuota_moderadora' => ($param === 2 ? $valorUsuario : 0),
            'au_anext31_id' => $detail['id'],
            'fecha_autorizacion' => $detail['fS'],
        ]);
    }

    /**
     * @param $anexotCabeza
     * @return mixed
     */
    private function isDxGestante($anexotCabeza)
    {
        $gestante = Refntcie10gestante::whereCodigo($anexotCabeza['dPrin'])->first();

        return boolval($gestante);
    }

    /**
     * @param $anexot31
     * @param $cobertura
     * @param $anexotCabeza
     * @return bool
     */
    private function siCopago($anexot31, $cobertura, $anexotCabeza): bool
    {
        return $cobertura['copa'] === '1' && $anexotCabeza['afiliado']['nivel_sisben'] >= '2' && $anexotCabeza['afiliado']['edad'] >= 1 && ($anexot31['modSer'] === '3' || $anexot31['servicio']['copago'] === '1');
    }

    /**
     * @param $anexotCabeza
     * @return bool
     */
    private function esAltoCosto($anexotCabeza): bool
    {
        return $anexotCabeza['diagnostico_principal']['as_tipaltocosto_id'] !== '' || $anexotCabeza['diagnostico_principal']['as_tipaltocosto_id'] !== null;
    }

    /**
     * @param int $edad
     * @param int $eMin
     * @param int $eMax
     * @return bool
     */
    private function isRangoDeEdad(int $edad, int $eMin, int $eMax): bool
    {
        return ($edad >= $eMin) && ($edad <= $eMax);
    }

    /**
     * @param AsAfiliado $afiliado
     * @param $genero
     * @return bool
     */
    private function isNotGenero(AsAfiliado $afiliado, $genero): bool
    {
        return ($afiliado->sexo['abreviatura'] === 'M' && $genero === 'F') || ($afiliado->sexo['abreviatura'] === 'F' && $genero === 'M');
    }

    /**
     * @param $afiliado
     * @param int $anio_actual
     * @param string $colum
     * @return mixed
     */
    private function sumaCopaOrCutoa($afiliado, int $anio_actual, string $colum)
    {
        return AuCopagoAnexot3::where('as_afiliado_id', '=', $afiliado['id'])->whereYear('fecha_autorizacion', $anio_actual)->sum($colum);
    }

    /**
     * @param $anexo
     * @param $entidad
     * @return int
     */
    private function esONoCopiaImpresion($anexo, $entidad): int
    {
        $imp = 2;
        if (isset($entidad)) {
            foreach ($anexo->detalles as $detail) {
                $anext31 = AuAnexot31::find($detail['id']);
                if ($anext31->pAut === $entidad->id) {
                    if ($anext31->imp === 0) {
                        $imp = 0;
                        if ($anext31->ind === 1 && ($anext31->cAut !== 0 || $anext31->cAut !== null)) {
                            $anext31->imp = 1;
                        }
                    } else {
                        $imp = 1;
                    }
                }
                $anext31->save();
            }
        }

        return $imp;
    }

    /**
     * @param string $data
     * @param $anexo
     * @return string
     */
    private function guardarQuienRecibe(string $data, $anexo): string
    {
        $entregado = new AuAnexot3Entrega();
        $entregado->au_anexot3s_id = $anexo['id'];
        $entregado->recibido = $data;
        $entregado->save();

        return $entregado['recibido'];
    }

    /**
     * @param $detail
     * @param $ref
     * @return mixed
     */
    private function negacionServicio($detail, $ref)
    {
        $negar = new AuAnexot3neg();
        $negar->fill([
            'codigo' => $detail['negacion']['codigo'],
            'observacion' => $detail['negacion']['observacion'],
            'au_anexot31_id' => $ref['id'],
        ]);
        $negar->save();
    }

    /**
     * @author JORGE HERNÁNDEZ 06-04-2020
     * @param \App\Http\Requests\Autorizaciones\Anexo3Request $request
     * @param \Illuminate\Support\Collection $datos
     */
    private function validaServicioRequiereAprobacion(
        Anexo3Request $request,
        \Illuminate\Support\Collection $datos
    ): void {
        foreach ($request->detalles as $key => $detalle) {
            $cup = RsCups::find($detalle['codigo']);
            if ($cup['sw_requiere_permiso_especial'] === 1) {
                $datos->push($detalle['codigo']);
            }
        }
    }
}
