<?php

namespace App\Capresoca\RecCompensacion;

use App\Exports\RecPilaExport;
use App\Models\Enums\EEstadoArchivoCompensacionRecaudo;
use App\Models\Enums\ESiNo;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleI;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleILiquidacione;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleIPe;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleIPLiquidacione;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * @author Jorge Eduardo Hernández Oropeza 11/06/2020
 * @copyright Creando Soluciones Informaáticas LTDA
 *
 * Class ClasificadorArchivosRec
 * @package App\Capresoca\RecCompensacion
 */
class ClasificadorArchivosRec
{

    /**
     * @param Collection $resultados
     * @param string $dato
     * @return Collection|array
     */
    public static function getPlanillaFTP(Collection $resultados, string $dato)
    {
        $resultado = collect();
        $directorie = Storage::disk('local');
        $ruta = "descarga/liquidacion_recaudos";
        $sdf = new Carbon();
        $carpeta = null;
        $lisDias = array();

        $carpeta = $directorie->path($ruta);
        $l = scandir($carpeta);

        if ($l !== null || (count($l) > 0)) {
            foreach ($l as $key => $item) {
                $nuevaRuta = "$carpeta/$item";
                if (is_dir($nuevaRuta) && $item !== '.' && $item !== '..') {
                    array_push($lisDias, $sdf->parse($item)->format('Y-m-d'));
                }
            }

            sort($lisDias);
            if (!empty($lisDias)) {
                $diaSeleccionado = $lisDias[0];
                if ($resultados !== null || ($dato === 'cargue')) {
                    $resultados->put('diaSeleccionado', $diaSeleccionado);
                    $resultados->put('diasRestantes', (strval(count($lisDias) - 1)));
                }
                $carpetaDia = "$carpeta/$diaSeleccionado";
                $archivos = scandir($carpetaDia);
                if ($archivos !== null && count($archivos) > 0) {
                    $arr = array();
                    foreach ($archivos as $key => $archivo) {
                        if ($archivo !== '.' && $archivo !== '..') {
                            $dto = new Collection();
                            $dto->put('nombre_archivo', $archivo);
                            $dto->put('fecha_descarga', $sdf->now()->format('Y/m/d'));
                            if ($dato === 'cargue') {
                                array_push($arr, $dto->toArray());
                            }
                            $resultado->push($dto);
                        }
                    }
                    if ($dato === 'cargue' && (count($arr) > 0)) {
                        $resultados->put('archivos', $arr);
                    }
                }
            }
        }

        if ($dato === 'cargue') {
            return $resultados;
        } else {
            return [
                $resultado,
                $resultados
            ];
        }
    }

    /**
     * @param string $fecha
     * @param string $formato
     * @return array|null|Collection
     */
    public static function getLinPeriodoLiquidacionXFecha(string $fecha, string $formato)
    {
        $sdf = new Carbon();
        $date = $sdf->parse($fecha)->format($formato);
        $query = DB::table('tb_periodo_liquidacion')
            ->where('fecha_inicio', '<=', $date)
            ->where('fecha_fin', '>=', $date)
            ->orderBy('consecutivo_liquidacion', 'ASC')->first();

        if (!empty($query)) {
            return $query;
        } else {
            return null;
        }
    }

    /**
     * @param string $fechaInicio
     * @param string $fechaFin
     * @param string $numeroPlanilla
     * @param string $idAportante
     * @param string $periodoPago
     * @return string
     */
    public static function getRecaudoDetalladoCotizantesInconsistentes($fechaInicio, $fechaFin, $numeroPlanilla, $idAportante, $periodoPago)
    {
        $resultado = null;
        $lista = null;
        $texto = null;
        $separador = '|';
        $mapa = collect();
        $sql = null;
        $query = null;

        $sql = RecRecaudoPlanillaDetalleI::with('recRecaudoPlanillaEncabezado')
            ->leftJoin('rec_recaudo_planilla_encabezados','rec_recaudo_planilla_encabezados.consecutivo_recaudo','rec_recaudo_planilla_detalle_is.consecutivo_recaudo_encabezado')
            ->where('rec_recaudo_planilla_encabezados.resultado', EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId());

        self::consultaWhere($fechaInicio, $fechaFin, $sql, $numeroPlanilla, $idAportante, $periodoPago);

        $lista = $sql->orderBy('rec_recaudo_planilla_encabezados.fecha_grabado', 'desc')->get();


        $texto = '';
        $texto = 'Contenido Archivo|Nombre Archivo|Fecha Cargue|Estado Cargue|Tipo Archivo|Número Planilla|Fecha Planilla|Estado Conciliación'.PHP_EOL;
        $dato = collect();
        foreach ($lista->toArray() as $keyP => $item) {
            $e = $item['recRecaudoPlanillaEncabezado'];
            $i = $item;

            $dato->push($keyP);
            $texto .= ($i['contenido'].'|');
            $texto .= substr($i['nombre_archivo'],0, (strlen($i['nombre_archivo']) - 4)).'|';
            $texto .= Carbon::parse($e['fecha_cargue'])->format('Y/m/d').'|';
            $texto .= EEstadoArchivoCompensacionRecaudo::getIndice($e['resultado'])->getDescripcion().'|';
            $texto .= $e['tipo_archivo'].'|'.$e['numero_planilla'].'|';
            $texto .= Carbon::parse($e['fecha_pago'])->format('Y/m/d').'|';
            $texto .= (($i['sw_conciliacion'] !== null) ? ($i['sw_conciliacion'] === ESiNo::getIndice(ESiNo::SI)->getId() ? 'Conciliada' : 'No Conciliada') : 'No Conciliada');
            $texto .= ($dato->count() === $lista->count()) ? PHP_EOL : null;
        }
        $contenido = $texto;
        $fileName = 'pila_incosnsistente.txt';
        file_put_contents($fileName, $contenido);

        return $fileName;
    }

    /**
     * @param string $fechaInicio
     * @param string $fechaFin
     * @param string $numeroPlanilla
     * @param string $idAportante
     * @param string $periodoPago
     * @return string
     */
    public static function getRecaudoDetalladoCotizantesPensionadosInconsistentes($fechaInicio, $fechaFin, $numeroPlanilla, $idAportante, $periodoPago)
    {
        $resultado = null;
        $lista = null;
        $texto = null;
        $separador = '|';
        $mapa = collect();
        $sql = null;
        $query = null;

        $sql = RecRecaudoPlanillaDetalleIPe::with('recRecaudoPlanillaEncabezado')
            ->leftJoin('rec_recaudo_planilla_encabezados','rec_recaudo_planilla_encabezados.consecutivo_recaudo','rec_recaudo_planilla_detalle_is.consecutivo_recaudo_encabezado')
            ->where('rec_recaudo_planilla_encabezados.resultado', EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_CON_INCONSISTENCIAS)->getId());

        self::consultaWhere($fechaInicio, $fechaFin, $sql, $numeroPlanilla, $idAportante, $periodoPago);

        $listaP = $sql->orderBy('rec_recaudo_planilla_encabezados.fecha_grabado', 'desc')->get();

        $textoP = '';
        $textoP = 'Contenido Archivo|Nombre Archivo|Fecha Cargue|Estado Cargue|Tipo Archivo|Número Planilla|Fecha Planilla|Estado Conciliación'.PHP_EOL;
        $dato = collect();
        foreach ($listaP->toArray() as $keyP => $item) {
            $e = $item['recRecaudoPlanillaEncabezado'];
            $i = $item;

            $dato->push($keyP);
            $textoP .= ($i['contenido'].'|');
            $textoP .= substr($i['nombre_archivo'],0, (strlen($i['nombre_archivo']) - 4)).'|';
            $textoP .= Carbon::parse($e['fecha_cargue'])->format('Y/m/d').'|';
            $textoP .= EEstadoArchivoCompensacionRecaudo::getIndice($e['resultado'])->getDescripcion().'|';
            $textoP .= $e['tipo_archivo'].'|'.$e['numero_planilla'].'|';
            $textoP .= Carbon::parse($e['fecha_pago'])->format('Y/m/d').'|';
            $textoP .= (($i['sw_conciliacion'] !== null) ? ($i['sw_conciliacion'] === ESiNo::getIndice(ESiNo::SI)->getId() ? 'Conciliada' : 'No Conciliada') : 'No Conciliada');
            $textoP .= ($dato->count() === $listaP->count()) ? PHP_EOL : null;
        }
        $contenido = $textoP;
        $fileName = 'pila_pensionados_incosnsistente.txt';
        file_put_contents($fileName, $contenido);

        return $fileName;
    }

    /**
     * @param string $fechaInicio
     * @param string $fechaFin
     * @param string $numeroPlanilla
     * @param string $idAportante
     * @param string $periodoPago
     * @return string
     */
    public static function getRecaudoDetalladoCotizantes($fechaInicio = '', $fechaFin = '', $numeroPlanilla='', $idAportante='', $periodoPago='')
    {
        $resultado = null;
        $lista = null;
        $texto = null;
        $separador = '|';
        $mapa = collect();
        $sql = null;
        $query = null;

        $sql = RecRecaudoPlanillaDetalleILiquidacione::with('recaudo_planilla_encabezado')
            ->leftJoin('rec_recaudo_planilla_encabezados','rec_recaudo_planilla_encabezados.consecutivo_recaudo','rec_recaudo_planilla_detalle_i_liquidaciones.consecutivo_recaudo_encabezado');

        self::consultaWhere($fechaInicio, $fechaFin, $sql, $numeroPlanilla, $idAportante, $periodoPago);

        $lista = $sql->orderBy('rec_recaudo_planilla_encabezados.fecha_grabado', 'desc')->get();

        $textoConsiliado = '';
        $dato = collect();

        $textoConsiliado .= 'NIT Administradora|Dígito Verificación|Razón Social Aportante|Tipo Documento Aportante|N° Identificación Aportante|';
        $textoConsiliado .= 'Dígito Verificación|Tipo Aportante|Dirección de Correspondencia|Municipio|Departamento|Teléfono|Fax|Correo Electrónico|';
        $textoConsiliado .= 'Perido Pago|Código ARL|Tipo Planilla|Fecha Pago Planilla|Fecha Pago|N° Planilla Asociada|N° Radicación o PILA|Forma Presentación|Código Sucursal|';
        $textoConsiliado .= 'Nombre Sucursal|N° Total Empleados|N° Total Afiliados|Código Operador|Modalidad Planilla|Días de Mora|N° Registros Tipo 2|Fecha Matrícula Mercantil|';
        $textoConsiliado .= 'Aportante Exonerado pago aporte Salud SENA ICBF|Clase Aportante|Naturaleza Jurídica|Tipo Persona|Secuencia|Tipo Registro|Tipo Documento Cotizante|';
        $textoConsiliado .= 'N° Identificación Cotizante|Tipo Cotizante|Subtipo Cotizante|Extranjero no obligado a cotizar a pensiones|Colombiano en el exterior|Departamento Ubicación Laboral|';
        $textoConsiliado .= 'Municipio Ubicación Laboral|Primer Apellido|Segundo Apellido|Primer Nombre|Segundo Nombre|ING|RET|TDE|TAE|VSP|VST|SLN|IGE|LMA|VAC-LR|Días Cotizados|';
        $textoConsiliado .= 'Salario Básico|Ingreso Base Cotización|Tarifa|Cotización Obligatoria|Valor UPC Adicional|Correcciones|Salario Integral|Cotizante Exonerado pago aporte Salud SENA ICBF|';
        $textoConsiliado .= 'Fecha Ingreso|Fecha Retiro|Fecha Inicio VSP|Fecha Inicio SLN|Fecha Fin SLN|Fecha Inicio IGE|Fecha Fin IGE|Fecha Inicio LMA|Fecha Fin LMA|Fecha Inicio VAC-LR|Fecha Fin VAC-LR|';
        $textoConsiliado .= 'Fecha Inicio VCT|Fecha Fin VCT|Fecha Inicio IRL|Fecha Fin IRL|Nombre Archivo|Fecha Cargue|Estado Cargue|Tipo Archivo|Número Planilla|Fecha Planilla|Tipo Planilla|Estado Conciliación'.PHP_EOL;

        foreach ($lista->toArray() as $key => $item) {
            $e  = $item['recaudo_planilla_encabezado'];
            $i  = $item['recaudo_planilla_detalle_is'];
            $il = $item;

            $dato->push($key);

            $textoConsiliado .= $i['nit_administradora'].'|';
            $textoConsiliado .= $i['digito_verificacion_administradora'].'|';
            $textoConsiliado .= $i['razon_social_aportante'].'|';
            $textoConsiliado .= $i['tipo_identificacion_aportante'].'|';
            $textoConsiliado .= $i['numero_identificacion_aportante'].'|';
            $textoConsiliado .= $i['digito_verificacion_aportante'].'|';
            $textoConsiliado .= $i['tipo_aportante'].'|';
            $textoConsiliado .= $i['direccion_correspondencia'].'|';
            $textoConsiliado .= $i['departamento'].$i['municipio'].'|';
            $textoConsiliado .= $i['departamento'].'|';
            $textoConsiliado .= $i['telefono'].'|';
            $textoConsiliado .= $i['fax'].'|';
            $textoConsiliado .= $i['correo_electronico'].'|';
            $textoConsiliado .= $i['periodo_pago'].'|';
            $textoConsiliado .= $i['codigo_arl'].'|';
            $textoConsiliado .= $i['tipo_planilla'].'|';
            $textoConsiliado .= $i['fecha_pago_planilla'].'|';
            $textoConsiliado .= $i['fecha_pago'].'|';
            $textoConsiliado .= $i['numero_planilla'].'|';
            $textoConsiliado .= $i['numero_radicacion_pila'].'|';
            $textoConsiliado .= $i['forma_presentacion'].'|';
            $textoConsiliado .= $i['codigo_sucursal'].'|';
            $textoConsiliado .= $i['nombre_sucursal'].'|';
            $textoConsiliado .= $i['numero_empleados'].'|';
            $textoConsiliado .= $i['numero_afiliados'].'|';
            $textoConsiliado .= $i['codigo_operador'].'|';
            $textoConsiliado .= $i['modalidad_planilla'].'|';
            $textoConsiliado .= $i['dias_mora'].'|';
            $textoConsiliado .= $i['numero_registros_tipo_2'].'|';
            $textoConsiliado .= $i['fecha_matricula_mercantil'].'|';
            $textoConsiliado .= $i['departamento_aportante'].'|';
            $textoConsiliado .= $i['sw_exonerado_pago_aportes'].'|';
            $textoConsiliado .= $i['clase_aportante'].'|';
            $textoConsiliado .= $i['naturaleza_juridica'].'|';
            $textoConsiliado .= $i['tipo_persona'].'|';
            $textoConsiliado .= $il['secuencia'].'|';
            $textoConsiliado .= $il['tipo_registro'].'|';
            $textoConsiliado .= $il['tipo_identificacion_cotizante'].'|';
            $textoConsiliado .= $il['numero_identificacion_cotizante'].'|';
            $textoConsiliado .= $il['tipo_cotizante'].'|';
            $textoConsiliado .= $il['subtipo_cotizante'].'|';
            $textoConsiliado .= $il['sw_extranjero'].'|';
            $textoConsiliado .= $il['sw_colombiano_exterior'].'|';
            $textoConsiliado .= $il['departamento'].'|';
            $textoConsiliado .= $il['municipio'].'|';
            $textoConsiliado .= $il['primer_apellido'].'|';
            $textoConsiliado .= $il['segundo_apellido'].'|';
            $textoConsiliado .= $il['primer_nombre'].'|';
            $textoConsiliado .= $il['segundo_nombre'].'|';
            $textoConsiliado .= $il['sw_ing'].'|';
            $textoConsiliado .= $il['sw_ret'].'|';
            $textoConsiliado .= $il['sw_tde'].'|';
            $textoConsiliado .= $il['sw_tae'].'|';
            $textoConsiliado .= $il['sw_vsp'].'|';
            $textoConsiliado .= $il['sw_vst'].'|';
            $textoConsiliado .= $il['sw_sln'].'|';
            $textoConsiliado .= $il['sw_ige'].'|';
            $textoConsiliado .= $il['sw_lma'].'|';
            $textoConsiliado .= $il['sw_vac_lr'].'|';
            $textoConsiliado .= $il['dias_cotizados'].'|';
            $textoConsiliado .= $il['salario_basico'].'|';
            $textoConsiliado .= $il['ingreso_base_cotizacion'].'|';
            $textoConsiliado .= $il['tarifa'].'|';
            $textoConsiliado .= $il['cotizacion_obligatoria'].'|';
            $textoConsiliado .= $il['valor_upc_adicional'].'|';
            $textoConsiliado .= $il['sw_correcciones'].'|';
            $textoConsiliado .= $il['sw_salario_integral'].'|';
            $textoConsiliado .= $il['sw_exonerado_aportes'].'|';
            $textoConsiliado .= $il['fecha_ingreso'].'|';
            $textoConsiliado .= $il['fecha_retiro'].'|';
            $textoConsiliado .= $il['fecha_inicio_vsp'].'|';
            $textoConsiliado .= $il['fecha_inicio_sln'].'|';
            $textoConsiliado .= $il['fecha_fin_sln'].'|';
            $textoConsiliado .= $il['fecha_inicio_ige'].'|';
            $textoConsiliado .= $il['fecha_fin_ige'].'|';
            $textoConsiliado .= $il['fecha_inicio_lma'].'|';
            $textoConsiliado .= $il['fecha_fin_lma'].'|';
            $textoConsiliado .= $il['fecha_inicio_vac_lr'].'|';
            $textoConsiliado .= $il['fecha_fin_vac_lr'].'|';
            $textoConsiliado .= $il['fecha_inicio_vct'].'|';
            $textoConsiliado .= $il['fecha_fin_vct'].'|';
            $textoConsiliado .= $il['fecha_inicio_irl'].'|';
            $textoConsiliado .= $il['fecha_fin_irl'].'|';
            $textoConsiliado .= substr($i['nombre_archivo'],0,(strlen($i['nombre_archivo']) - 4)).'|';
            $textoConsiliado .= (Carbon::parse($e['fecha_cargue'])->format('Y/m/d')).'|';
            $resultado = ((array) EEstadoArchivoCompensacionRecaudo::getResultado($e['resultado']));
            $textoConsiliado .= $resultado["\x00*\x00descripcion"].'|';
            $textoConsiliado .= $e['tipo_archivo'].'|';
            $textoConsiliado .= $e['numero_planilla'].'|';
            $textoConsiliado .= (Carbon::parse($e['fecha_pago'])->format('Y/m/d')).'|';
            $textoConsiliado .= ($i['tipo_planilla']).'|';
            $textoConsiliado .= (($i['sw_conciliacion'] !== null) ? ($i['sw_conciliacion'] === ESiNo::getIndice(ESiNo::SI)->getId() ? 'Conciliada' : 'No Conciliada') : 'No Conciliada');
            $textoConsiliado .= ($dato->count() === $lista->count() ? null : PHP_EOL);

        }

        $contenido = $textoConsiliado;
        $archName = 'pila_conciliados.txt';
        file_put_contents($archName, $contenido);

        return $archName;
    }

    /**
     * @param string $fechaInicio
     * @param string $fechaFin
     * @param string $numeroPlanilla
     * @param string $idAportante
     * @param string $periodoPago
     * @return string
     */
    public static function getRecaudoDetalladoPensionados($fechaInicio, $fechaFin, $numeroPlanilla, $idAportante, $periodoPago)
    {
        $resultado = null;
        $lista = null;
        $texto = null;
        $separador = '|';
        $mapa = collect();
        $sql = null;
        $query = null;

        $sql = RecRecaudoPlanillaDetalleIPLiquidacione::with('recaudo_planilla_encabezado')
            ->leftJoin('rec_recaudo_planilla_encabezados','rec_recaudo_planilla_encabezados.consecutivo_recaudo','rec_recaudo_planilla_detalle_i_liquidaciones.consecutivo_recaudo_encabezado');

        self::consultaWhere($fechaInicio, $fechaFin, $sql, $numeroPlanilla, $idAportante, $periodoPago);

        $lista = $sql->orderBy('rec_recaudo_planilla_encabezados.fecha_grabado', 'desc')->get();

        $textoPensionado = '';
        $dato = collect();

        $textoPensionado .= 'NIT Administradora|Dígito Verificación|Razón Social Pensionado|Tipo Documento Pensionado|N° Identificación Pensionado|';
        $textoPensionado .= 'Dígito Verificación|Tipo Pensionado|Dirección de Correspondencia|Municipio|Departamento|Teléfono|Fax|Correo Electrónico|';
        $textoPensionado .= 'Perido Pago|Código ARL|Tipo Planilla|Fecha Pago Planilla|Fecha Pago|N° Planilla Asociada|N° Radicación o PILA|Forma Presentación|Código Sucursal|';
        $textoPensionado .= 'Nombre Sucursal|N° Total Empleados|N° Total Afiliados|Código Operador|Modalidad Planilla|Días de Mora|N° Registros Tipo 2|Fecha Matrícula Mercantil|';
        $textoPensionado .= 'Pensionado Exonerado pago aporte Salud SENA ICBF|Clase Pensionado|Naturaleza Jurídica|Tipo Persona|Secuencia|Tipo Registro|Tipo Documento Cotizante|';
        $textoPensionado .= 'N° Identificación Cotizante|Tipo Cotizante|Subtipo Cotizante|Extranjero no obligado a cotizar a pensiones|Colombiano en el exterior|Departamento Ubicación Laboral|';
        $textoPensionado .= 'Municipio Ubicación Laboral|Primer Apellido|Segundo Apellido|Primer Nombre|Segundo Nombre|ING|RET|TDE|TAE|VSP|VST|SLN|IGE|LMA|VAC-LR|Días Cotizados|';
        $textoPensionado .= 'Salario Básico|Ingreso Base Cotización|Tarifa|Cotización Obligatoria|Valor UPC Adicional|Correcciones|Salario Integral|Cotizante Exonerado pago aporte Salud SENA ICBF|';
        $textoPensionado .= 'Fecha Ingreso|Fecha Retiro|Fecha Inicio VSP|Fecha Inicio SLN|Fecha Fin SLN|Fecha Inicio IGE|Fecha Fin IGE|Fecha Inicio LMA|Fecha Fin LMA|Fecha Inicio VAC-LR|Fecha Fin VAC-LR|';
        $textoPensionado .= 'Fecha Inicio VCT|Fecha Fin VCT|Fecha Inicio IRL|Fecha Fin IRL|Nombre Archivo|Fecha Cargue|Estado Cargue|Tipo Archivo|Número Planilla|Fecha Planilla|Tipo Planilla|Estado Conciliación'.PHP_EOL;


        foreach ($lista->toArray() as $key => $item) {
            $e  = $item['recaudo_planilla_encabezado'];
            $i  = $item['recaudo_planilla_detalle_ip'];
            $il = $item;

            $dato->push($key);

            $textoPensionado .= $i['nit_administradora'].'|';
            $textoPensionado .= $i['digito_verificacion_administradora'].'|';
            $textoPensionado .= $i['razon_social_pagador'].'|';
            $textoPensionado .= $i['tipo_identificacion_pagador'].'|';
            $textoPensionado .= $i['numero_identificacion_pagador'].'|';
            $textoPensionado .= $i['digito_verificacion_pagador'].'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= $i['direccion_correspondencia'].'|';
            $textoPensionado .= $i['departamento'].$i['municipio'].'|';
            $textoPensionado .= $i['departamento'].'|';
            $textoPensionado .= $i['telefono'].'|';
            $textoPensionado .= $i['fax'].'|';
            $textoPensionado .= $i['correo_electronico'].'|';
            $textoPensionado .= $i['periodo_pago'].'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= $i['tipo_planilla'].'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= $i['fecha_pago'].'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= $i['forma_presentacion'].'|';
            $textoPensionado .= $i['codigo_sucursal'].'|';
            $textoPensionado .= $i['nombre_sucursal'].'|';
            $textoPensionado .= $i['numero_pensionados'].'|';
            $textoPensionado .= $i['numero_afiliados'].'|';
            $textoPensionado .= $i['codigo_operador'].'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= $i['dias_mora'].'|';
            $textoPensionado .= $i['numero_registros_tipo_2'].'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= $il['secuencia'].'|';
            $textoPensionado .= $il['tipo_registro'].'|';
            $textoPensionado .= $il['tipo_identificacion_cotizante'].'|';
            $textoPensionado .= $il['numero_identificacion_cotizante'].'|';
            $textoPensionado .= $il['tipo_pensionado'].'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= $il['sw_pensionado_exterior'].'|';
            $textoPensionado .= $il['departamento_residencia'].'|';
            $textoPensionado .= $il['municipio_residencia'].'|';
            $textoPensionado .= $il['primer_apellido_pensionado'].'|';
            $textoPensionado .= $il['segundo_apellido_pensionado'].'|';
            $textoPensionado .= $il['segundo_apellido_pensionado'].'|';
            $textoPensionado .= $il['segundo_nombre_pensionado'].'|';
            $textoPensionado .= $il['sw_ing'].'|';
            $textoPensionado .= $il['sw_ret'].'|';
            $textoPensionado .= $il['sw_tde'].'|';
            $textoPensionado .= $il['sw_tae'].'|';
            $textoPensionado .= $il['sw_vsp'].'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= $il['dias_cotizados'].'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= $il['ingreso_base_cotizacion'].'|';
            $textoPensionado .= $il['tarifa'].'|';
            $textoPensionado .= $il['cotizacion_obligatoria'].'|';
            $textoPensionado .= $il['valor_upc_adicional'].'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= $il['fecha_ingreso'].'|';
            $textoPensionado .= $il['fecha_retiro'].'|';
            $textoPensionado .= $il['fecha_inicio_vsp'].'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= ''.'|';
            $textoPensionado .= substr($i['nombre_archivo'],0,(strlen($i['nombre_archivo']) - 4)).'|';
            $textoPensionado .= (Carbon::parse($e['fecha_cargue'])->format('Y/m/d')).'|';
            $resultado = ((array) EEstadoArchivoCompensacionRecaudo::getResultado($e['resultado']));
            $textoPensionado .= $resultado["\x00*\x00descripcion"].'|';
            //$textoPensionado .= (EEstadoArchivoCompensacionRecaudo::getIndice($e['resultado'])->getDescripcion()).'|';
            $textoPensionado .= $e['tipo_archivo'].'|';
            $textoPensionado .= $e['numero_planilla'].'|';
            $textoPensionado .= (Carbon::parse($e['fecha_pago'])->format('Y/m/d')).'|';
            $textoPensionado .= ($i['tipo_planilla']).'|';
            $textoPensionado .= (($i['sw_conciliacion'] !== null) ? ($i['sw_conciliacion'] === ESiNo::getIndice(ESiNo::SI)->getId() ? 'Conciliada' : 'No Conciliada') : 'No Conciliada');
            $textoPensionado .= ($dato->count() === $lista->count() ? null : PHP_EOL);
        }

        $contenido = $textoPensionado;
        $archName = 'pila_conciliados.txt';
        file_put_contents($archName, $contenido);

        return $archName;
    }

    /**
     * @param string $fechaInicio
     * @param string $fechaFin
     * @param $sql
     * @param string $numeroPlanilla
     * @param string $idAportante
     * @param string $periodoPago
     */
    protected static function consultaWhere($fechaInicio, $fechaFin, $sql, $numeroPlanilla, $idAportante, $periodoPago): void
    {
        if ((!empty($fechaInicio)) && (empty($fechaFin))) {
            $sql->where('rec_recaudo_planilla_encabezados.fecha_pago', '>=', Carbon::parse($fechaInicio)->format('Y-m-d'));
        }

        if ((empty($fechaInicio)) && (!empty($fechaFin))) {
            $sql->where('rec_recaudo_planilla_encabezados.fecha_pago', '>=', Carbon::parse($fechaFin)->format('Y-m-d'));
        }

        if ((!empty($fechaInicio)) && (!empty($fechaFin))) {
            $sql->whereBetween('rec_recaudo_planilla_encabezados.fecha_pago', [Carbon::parse($fechaInicio)->format('Y-m-d'), Carbon::parse($fechaFin)->format('Y-m-d')]);
        }

        if (!empty($numeroPlanilla)) {
            $sql->where('rec_recaudo_planilla_encabezados.numero_planilla', $numeroPlanilla);
        }

        if (!empty($idAportante)) {
            $sql->where('rec_recaudo_planilla_encabezados.numero_documento', $idAportante);
        }

        if (!empty($periodoPago)) {
            $sql->where('rec_recaudo_planilla_encabezados.periodo_pago', $periodoPago);
        }
    }

}