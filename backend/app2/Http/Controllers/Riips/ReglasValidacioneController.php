<?php

namespace App\Http\Controllers\Riips;

use App\Http\Controllers\Controller;
use App\Models\Riips\ReglasValidacion;
use App\Models\Riips\RsReglasValidacione;
use Illuminate\Http\Request;

class ReglasValidacioneController extends Controller {

	public function index() {
		try {
			$reglasReglasValidacion = new ReglasValidacion(RsReglasValidacione::first());
			return response()->json([
				'reglas' => $reglasReglasValidacion->getReglasArray(),
				'ayudas' => $reglasReglasValidacion->descripcionReglasValidacion(),
			]);
		} catch (\Exception $e) {
			return response()->json([
				"message" => "Error en el servidor",
				"error" => $e->getMessage(),
				"trace" => $e->getTrace(),
			]);
		}
	}

	public function store(Request $request) {
		try {
			$reglasValidacion = RsReglasValidacione::first();
			if (is_null($request->ct['codigo']) || $request->ct['codigo'] == false) {$reglasValidacion->ct_codigo = 0;} else { $reglasValidacion->ct_codigo = 1;}
			if (is_null($request->ct['fecha']) || $request->ct['fecha'] == false) {$reglasValidacion->ct_fecha = 0;} else { $reglasValidacion->ct_fecha = 1;}
			if (is_null($request->ct['nombreArchivo']) || $request->ct['nombreArchivo'] == false) {$reglasValidacion->ct_nombre_archivo = 0;} else { $reglasValidacion->ct_nombre_archivo = 1;}
			if (is_null($request->ct['esEntero']) || $request->ct['esEntero'] == false) {$reglasValidacion->ct_entero = 0;} else { $reglasValidacion->ct_entero = 1;}
			if (is_null($request->af['codigoPrestadorCt']) || $request->af['codigoPrestadorCt'] == false) {$reglasValidacion->af_codigo_prestador_ct = 0;} else { $reglasValidacion->af_codigo_prestador_ct = 1;}
			if (is_null($request->af['tipoIdentificacionPrestador']) || $request->af['tipoIdentificacionPrestador'] == false) {$reglasValidacion->af_tipo_identificacion_prestador = 0;} else { $reglasValidacion->af_tipo_identificacion_prestador = 1;}
			if (is_null($request->af['existePrestadorServicioSalud']) || $request->af['existePrestadorServicioSalud'] == false) {$reglasValidacion->af_existe_prestador = 0;} else { $reglasValidacion->af_existe_prestador = 1;}
			if (is_null($request->af['identificacionPrestador']) || $request->af['identificacionPrestador'] == false) {$reglasValidacion->af_identificacion_prestador = 0;} else { $reglasValidacion->af_identificacion_prestador = 1;}
			if (is_null($request->af['mismaFacturaCaptiacion']) || $request->af['mismaFacturaCaptiacion'] == false) {$reglasValidacion->af_misma_factura_capitacion = 0;} else { $reglasValidacion->af_misma_factura_capitacion = 1;}
			if (is_null($request->af['fechaExpedicion']) || $request->af['fechaExpedicion'] == false) {$reglasValidacion->af_fecha_expedicion = 0;} else { $reglasValidacion->af_fecha_expedicion = 1;}
			if (is_null($request->af['fechaInicio']) || $request->af['fechaInicio'] == false) {$reglasValidacion->af_fecha_inicio = 0;} else { $reglasValidacion->af_fecha_inicio = 1;}
			if (is_null($request->af['FechaInicioMenorQueFechafinal']) || $request->af['FechaInicioMenorQueFechafinal'] == false) {$reglasValidacion->af_fecha_inicio_menor_fecha_final = 0;} else { $reglasValidacion->af_fecha_inicio_menor_fecha_final = 1;}
			if (is_null($request->af['fechaFinal']) || $request->af['fechaFinal'] == false) {$reglasValidacion->af_fecha_final = 0;} else { $reglasValidacion->af_fecha_final = 1;}
			if (is_null($request->af['fechaFinalMayorQueFechaInicial']) || $request->af['fechaFinalMayorQueFechaInicial'] == false) {$reglasValidacion->af_fecha_final_mayor_fecha_inicial = 0;} else { $reglasValidacion->af_fecha_final_mayor_fecha_inicial = 1;}
			if (is_null($request->af['codigoEntidadadministradora']) || $request->af['codigoEntidadadministradora'] == false) {$reglasValidacion->af_codigo_entidad = 0;} else { $reglasValidacion->af_codigo_entidad = 1;}
			if (is_null($request->af['numeroPositivo']) || $request->af['numeroPositivo'] == false) {$reglasValidacion->af_numero_positivo = 0;} else { $reglasValidacion->af_numero_positivo = 1;}
			if (is_null($request->af['cruceFacturas']) || $request->af['cruceFacturas'] == false) {$reglasValidacion->af_cruce_factura = 0;} else { $reglasValidacion->af_cruce_factura = 1;}
			if (is_null($request->af['sumaValoresFacturas']) || $request->af['sumaValoresFacturas'] == false) {$reglasValidacion->af_suma_valores_facturas = 0;} else { $reglasValidacion->af_suma_valores_facturas = 1;}
			if (is_null($request->us['rangosTipoEdad']) || $request->us['rangosTipoEdad'] == false) {$reglasValidacion->us_rangos_tipo_edad = 0;} else { $reglasValidacion->us_rangos_tipo_edad = 1;}
			if (is_null($request->us['edad']) || $request->us['edad'] == false) {$reglasValidacion->us_edad = 0;} else { $reglasValidacion->us_edad = 1;}
			if (is_null($request->us['tipoIdentificacionUsuario']) || $request->us['tipoIdentificacionUsuario'] == false) {$reglasValidacion->us_tipo_identificacion_usuario = 0;} else { $reglasValidacion->us_tipo_identificacion_usuario = 1;}
			if (is_null($request->us['longitudIdentificacion']) || $request->us['longitudIdentificacion'] == false) {$reglasValidacion->us_longitud_identificacion = 0;} else { $reglasValidacion->us_longitud_identificacion = 1;}
			if (is_null($request->us['tipoUsuario']) || $request->us['tipoUsuario'] == false) {$reglasValidacion->us_tipo_usuario = 0;} else { $reglasValidacion->us_tipo_usuario = 1;}
			if (is_null($request->us['rangoGenero']) || $request->us['rangoGenero'] == false) {$reglasValidacion->us_rango_genero = 0;} else { $reglasValidacion->us_rango_genero = 1;}
			if (is_null($request->us['codigoDepartamento']) || $request->us['codigoDepartamento'] == false) {$reglasValidacion->us_codigo_departamento = 0;} else { $reglasValidacion->us_codigo_departamento = 1;}
			if (is_null($request->us['codigoMunicipio']) || $request->us['codigoMunicipio'] == false) {$reglasValidacion->us_codigo_municipio = 0;} else { $reglasValidacion->us_codigo_municipio = 1;}
			if (is_null($request->us['zona']) || $request->us['zona'] == false) {$reglasValidacion->us_zona = 0;} else { $reglasValidacion->us_zona = 1;}
			if (is_null($request->ac['numeroFacturaEnAf']) || $request->ac['numeroFacturaEnAf'] == false) {$reglasValidacion->ac_numero_factura_en_af = 0;} else { $reglasValidacion->ac_numero_factura_en_af = 1;}
			if (is_null($request->ac['codigoPrestadorCt']) || $request->ac['codigoPrestadorCt'] == false) {$reglasValidacion->ac_codigo_prestador_ct = 0;} else { $reglasValidacion->ac_codigo_prestador_ct = 1;}
			if (is_null($request->ac['rangosTipoIdentificacion']) || $request->ac['rangosTipoIdentificacion'] == false) {$reglasValidacion->ac_rangos_tipo_identificacion = 0;} else { $reglasValidacion->ac_rangos_tipo_identificacion = 1;}
			if (is_null($request->ac['longitudIdentificacion']) || $request->ac['longitudIdentificacion'] == false) {$reglasValidacion->ac_longitud_identificacion = 0;} else { $reglasValidacion->ac_longitud_identificacion = 1;}
			if (is_null($request->ac['fechaConsulta']) || $request->ac['fechaConsulta'] == false) {$reglasValidacion->ac_fecha_consulta = 0;} else { $reglasValidacion->ac_fecha_consulta = 1;}
			if (is_null($request->ac['numeroAutorizacion']) || $request->ac['numeroAutorizacion'] == false) {$reglasValidacion->ac_numero_autorizacion = 0;} else { $reglasValidacion->ac_numero_autorizacion = 1;}
			if (is_null($request->ac['existeCup']) || $request->ac['existeCup'] == false) {$reglasValidacion->ac_existe_cup = 0;} else { $reglasValidacion->ac_existe_cup = 1;}
			if (is_null($request->ac['cupValidoConsulta']) || $request->ac['cupValidoConsulta'] == false) {$reglasValidacion->ac_cup_valido_consulta = 0;} else { $reglasValidacion->ac_cup_valido_consulta = 1;}
			if (is_null($request->ac['rangoFinalidad']) || $request->ac['rangoFinalidad'] == false) {$reglasValidacion->ac_rango_finalidad = 0;} else { $reglasValidacion->ac_rango_finalidad = 1;}
			if (is_null($request->ac['finalidadUltimos']) || $request->ac['finalidadUltimos'] == false) {$reglasValidacion->ac_finalidad_ultimos = 0;} else { $reglasValidacion->ac_finalidad_ultimos = 1;}
			if (is_null($request->ac['referenciaCruzadaFinalidad']) || $request->ac['referenciaCruzadaFinalidad'] == false) {$reglasValidacion->ac_referencia_cruzada_finalidad = 0;} else { $reglasValidacion->ac_referencia_cruzada_finalidad = 1;}
			if (is_null($request->ac['causaExterna']) || $request->ac['causaExterna'] == false) {$reglasValidacion->ac_causa_externa = 0;} else { $reglasValidacion->ac_causa_externa = 1;}
			if (is_null($request->ac['diagnosticoPrincipal']) || $request->ac['diagnosticoPrincipal'] == false) {$reglasValidacion->ac_diagnostico_principal = 0;} else { $reglasValidacion->ac_diagnostico_principal = 1;}
			if (is_null($request->ac['referenciaCruzadaCie10']) || $request->ac['referenciaCruzadaCie10'] == false) {$reglasValidacion->ac_referencia_cruzada_cie10 = 0;} else { $reglasValidacion->ac_referencia_cruzada_cie10 = 1;}
			if (is_null($request->ac['referenciaCruzadaCie10Cups']) || $request->ac['referenciaCruzadaCie10Cups'] == false) {$reglasValidacion->ac_referencia_cruzada_cie10_cups = 0;} else { $reglasValidacion->ac_referencia_cruzada_cie10_cups = 1;}
			if (is_null($request->ac['diagnosticoSecundario1']) || $request->ac['diagnosticoSecundario1'] == false) {$reglasValidacion->ac_diagnostico_secundario1 = 0;} else { $reglasValidacion->ac_diagnostico_secundario1 = 1;}
			if (is_null($request->ac['referenciaCruzadaCie101']) || $request->ac['referenciaCruzadaCie101'] == false) {$reglasValidacion->ac_referencia_cruzada_cie101 = 0;} else { $reglasValidacion->ac_referencia_cruzada_cie101 = 1;}
			if (is_null($request->ac['referenciaCruzadaCie10Cups1']) || $request->ac['referenciaCruzadaCie10Cups1'] == false) {$reglasValidacion->ac_referencia_cruzada_cie10_cups1 = 0;} else { $reglasValidacion->ac_referencia_cruzada_cie10_cups1 = 1;}
			if (is_null($request->ac['diagnosticoSecundario2']) || $request->ac['diagnosticoSecundario2'] == false) {$reglasValidacion->ac_diagnostico_secundario2 = 0;} else { $reglasValidacion->ac_diagnostico_secundario2 = 1;}
			if (is_null($request->ac['referenciaCruzadaCie102']) || $request->ac['referenciaCruzadaCie102'] == false) {$reglasValidacion->ac_referencia_cruzada_cie102 = 0;} else { $reglasValidacion->ac_referencia_cruzada_cie102 = 1;}
			if (is_null($request->ac['referenciaCruzadaCie10Cups2']) || $request->ac['referenciaCruzadaCie10Cups2'] == false) {$reglasValidacion->ac_referencia_cruzada_cie10_cups2 = 0;} else { $reglasValidacion->ac_referencia_cruzada_cie10_cups2 = 1;}
			if (is_null($request->ac['diagnosticoSecundario3']) || $request->ac['diagnosticoSecundario3'] == false) {$reglasValidacion->ac_diagnostico_secundario3 = 0;} else { $reglasValidacion->ac_diagnostico_secundario3 = 1;}
			if (is_null($request->ac['referenciaCruzadaCie103']) || $request->ac['referenciaCruzadaCie103'] == false) {$reglasValidacion->ac_referencia_cruzada_cie103 = 0;} else { $reglasValidacion->ac_referencia_cruzada_cie103 = 1;}
			if (is_null($request->ac['referenciaCruzadaCie10Cups3']) || $request->ac['referenciaCruzadaCie10Cups3'] == false) {$reglasValidacion->ac_referencia_cruzada_cie10_cups3 = 0;} else { $reglasValidacion->ac_referencia_cruzada_cie10_cups3 = 1;}
			if (is_null($request->ac['tipoDiagnosticoPrincipal']) || $request->ac['tipoDiagnosticoPrincipal'] == false) {$reglasValidacion->ac_tipo_diagnostico_principal = 0;} else { $reglasValidacion->ac_tipo_diagnostico_principal = 1;}
			if (is_null($request->ac['valorConsultaEntero']) || $request->ac['valorConsultaEntero'] == false) {$reglasValidacion->ac_valor_consulta_entero = 0;} else { $reglasValidacion->ac_valor_consulta_entero = 1;}
			if (is_null($request->ac['valorConsultaCero']) || $request->ac['valorConsultaCero'] == false) {$reglasValidacion->ac_valor_consulta_cero = 0;} else { $reglasValidacion->ac_valor_consulta_cero = 1;}
			if (is_null($request->ac['valorConsultaSiempreCeroCapitacion']) || $request->ac['valorConsultaSiempreCeroCapitacion'] == false) {$reglasValidacion->ac_valor_consulta_siempre_cero_capitacion = 0;} else { $reglasValidacion->ac_valor_consulta_siempre_cero_capitacion = 1;}
			if (is_null($request->ac['valorCuotaModeradoraEntero']) || $request->ac['valorCuotaModeradoraEntero'] == false) {$reglasValidacion->ac_valor_cuota_moderadora_entero = 0;} else { $reglasValidacion->ac_valor_cuota_moderadora_entero = 1;}
			if (is_null($request->ac['valorNetoEntero']) || $request->ac['valorNetoEntero'] == false) {$reglasValidacion->ac_valor_neto_entero = 0;} else { $reglasValidacion->ac_valor_neto_entero = 1;}
			if (is_null($request->ac['valorNetoCero']) || $request->ac['valorNetoCero'] == false) {$reglasValidacion->ac_valor_neto_cero = 0;} else { $reglasValidacion->ac_valor_neto_cero = 1;}
			if (is_null($request->ap['numeroFacturaEnAf']) || $request->ap['numeroFacturaEnAf'] == false) {$reglasValidacion->ap_numero_factura_en_af = 0;} else { $reglasValidacion->ap_numero_factura_en_af = 1;}
			if (is_null($request->ap['codigoPrestadorCt']) || $request->ap['codigoPrestadorCt'] == false) {$reglasValidacion->ap_codigo_prestador_ct = 0;} else { $reglasValidacion->ap_codigo_prestador_ct = 1;}
			if (is_null($request->ap['rangosTipoIdentificacion']) || $request->ap['rangosTipoIdentificacion'] == false) {$reglasValidacion->ap_rangos_tipo_identificacion = 0;} else { $reglasValidacion->ap_rangos_tipo_identificacion = 1;}
			if (is_null($request->ap['longitudIdentificacion']) || $request->ap['longitudIdentificacion'] == false) {$reglasValidacion->ap_longitud_identificacion = 0;} else { $reglasValidacion->ap_longitud_identificacion = 1;}
			if (is_null($request->ap['fechaProcedimiento']) || $request->ap['fechaProcedimiento'] == false) {$reglasValidacion->ap_fecha_procedimiento = 0;} else { $reglasValidacion->ap_fecha_procedimiento = 1;}
			if (is_null($request->ap['numeroAutorizacion']) || $request->ap['numeroAutorizacion'] == false) {$reglasValidacion->ap_numero_autorizacion = 0;} else { $reglasValidacion->ap_numero_autorizacion = 1;}
			if (is_null($request->ap['existeCup']) || $request->ap['existeCup'] == false) {$reglasValidacion->ap_existe_cup = 0;} else { $reglasValidacion->ap_existe_cup = 1;}
			if (is_null($request->ap['realizacionProcedimiento']) || $request->ap['realizacionProcedimiento'] == false) {$reglasValidacion->ap_realizacion_procedimiento = 0;} else { $reglasValidacion->ap_realizacion_procedimiento = 1;}
			if (is_null($request->ap['finalidadProcedimiento']) || $request->ap['finalidadProcedimiento'] == false) {$reglasValidacion->ap_finalidad_procedimiento = 0;} else { $reglasValidacion->ap_finalidad_procedimiento = 1;}
			if (is_null($request->ap['personalQueAtiende']) || $request->ap['personalQueAtiende'] == false) {$reglasValidacion->ap_personal_que_atiende = 0;} else { $reglasValidacion->ap_personal_que_atiende = 1;}
			if (is_null($request->ap['diagnosticoQuirurgico']) || $request->ap['diagnosticoQuirurgico'] == false) {$reglasValidacion->ap_diagnostico_quirurgico = 0;} else { $reglasValidacion->ap_diagnostico_quirurgico = 1;}
			if (is_null($request->ap['diagnosticoPrincipal']) || $request->ap['diagnosticoPrincipal'] == false) {$reglasValidacion->ap_diagnostico_principal = 0;} else { $reglasValidacion->ap_diagnostico_principal = 1;}
			if (is_null($request->ap['referenciaCruzadaCie10']) || $request->ap['referenciaCruzadaCie10'] == false) {$reglasValidacion->ap_referencia_cruzada_cie10 = 0;} else { $reglasValidacion->ap_referencia_cruzada_cie10 = 1;}
			if (is_null($request->ap['referenciaCruzadaCie10Cups']) || $request->ap['referenciaCruzadaCie10Cups'] == false) {$reglasValidacion->ap_referencia_cruzada_cie10_cups = 0;} else { $reglasValidacion->ap_referencia_cruzada_cie10_cups = 1;}
			if (is_null($request->ap['diagnosticoSecundario1']) || $request->ap['diagnosticoSecundario1'] == false) {$reglasValidacion->ap_diagnostico_secundario1 = 0;} else { $reglasValidacion->ap_diagnostico_secundario1 = 1;}
			if (is_null($request->ap['referenciaCruzadaCie101']) || $request->ap['referenciaCruzadaCie101'] == false) {$reglasValidacion->ap_referencia_cruzada_cie101 = 0;} else { $reglasValidacion->ap_referencia_cruzada_cie101 = 1;}
			if (is_null($request->ap['referenciaCruzadaCie10Cups1']) || $request->ap['referenciaCruzadaCie10Cups1'] == false) {$reglasValidacion->ap_referencia_cruzada_cie10_cups1 = 0;} else { $reglasValidacion->ap_referencia_cruzada_cie10_cups1 = 1;}
			if (is_null($request->ap['diagnosticoSecundario2']) || $request->ap['diagnosticoSecundario2'] == false) {$reglasValidacion->ap_diagnostico_secundario2 = 0;} else { $reglasValidacion->ap_diagnostico_secundario2 = 1;}
			if (is_null($request->ap['referenciaCruzadaCie102']) || $request->ap['referenciaCruzadaCie102'] == false) {$reglasValidacion->ap_referencia_cruzada_cie102 = 0;} else { $reglasValidacion->ap_referencia_cruzada_cie102 = 1;}
			if (is_null($request->ap['referenciaCruzadaCie10Cups2']) || $request->ap['referenciaCruzadaCie10Cups2'] == false) {$reglasValidacion->ap_referencia_cruzada_cie10_cups2 = 0;} else { $reglasValidacion->ap_referencia_cruzada_cie10_cups2 = 1;}
			if (is_null($request->ap['formaRealizacion']) || $request->ap['formaRealizacion'] == false) {$reglasValidacion->ap_forma_realizacion = 0;} else { $reglasValidacion->ap_forma_realizacion = 1;}
			if (is_null($request->ap['valorProcedimientoEntero']) || $request->ap['valorProcedimientoEntero'] == false) {$reglasValidacion->ap_valor_procedimiento_entero = 0;} else { $reglasValidacion->ap_valor_procedimiento_entero = 1;}
			if (is_null($request->ap['valorConsultaCero']) || $request->ap['valorConsultaCero'] == false) {$reglasValidacion->ap_valor_consulta_cero = 0;} else { $reglasValidacion->ap_valor_consulta_cero = 1;}
			if (is_null($request->ap['valorConsultaSiempreCero']) || $request->ap['valorConsultaSiempreCero'] == false) {$reglasValidacion->ap_valor_consulta_siempre_cero = 0;} else { $reglasValidacion->ap_valor_consulta_siempre_cero = 1;}
			if (is_null($request->au['numeroFacturaEnAf']) || $request->au['numeroFacturaEnAf'] == false) {$reglasValidacion->au_numero_factura_en_af = 0;} else { $reglasValidacion->au_numero_factura_en_af = 1;}
			if (is_null($request->au['codigoPrestadorCt']) || $request->au['codigoPrestadorCt'] == false) {$reglasValidacion->au_codigo_prestador_ct = 0;} else { $reglasValidacion->au_codigo_prestador_ct = 1;}
			if (is_null($request->au['rangosTipoIdentificacion']) || $request->au['rangosTipoIdentificacion'] == false) {$reglasValidacion->au_rangos_tipo_identificacion = 0;} else { $reglasValidacion->au_rangos_tipo_identificacion = 1;}
			if (is_null($request->au['longitudIdentificacion']) || $request->au['longitudIdentificacion'] == false) {$reglasValidacion->au_longitud_identificacion = 0;} else { $reglasValidacion->au_longitud_identificacion = 1;}
			if (is_null($request->au['fechaingreso']) || $request->au['fechaingreso'] == false) {$reglasValidacion->au_fecha_ingreso = 0;} else { $reglasValidacion->au_fecha_ingreso = 1;}
			if (is_null($request->au['fechaIngresoMenorFechaEgreso']) || $request->au['fechaIngresoMenorFechaEgreso'] == false) {$reglasValidacion->au_fecha_ingreso_menor_fecha_egreso = 0;} else { $reglasValidacion->au_fecha_ingreso_menor_fecha_egreso = 1;}
			if (is_null($request->au['numeroAutorizacion']) || $request->au['numeroAutorizacion'] == false) {$reglasValidacion->au_numero_autorizacion = 0;} else { $reglasValidacion->au_numero_autorizacion = 1;}
			if (is_null($request->au['causaExterna']) || $request->au['causaExterna'] == false) {$reglasValidacion->au_causa_externa = 0;} else { $reglasValidacion->au_causa_externa = 1;}
			if (is_null($request->au['diagnosticoPrincipal']) || $request->au['diagnosticoPrincipal'] == false) {$reglasValidacion->au_diagnostico_principal = 0;} else { $reglasValidacion->au_diagnostico_principal = 1;}
			if (is_null($request->au['referenciaCruzadaCie10']) || $request->au['referenciaCruzadaCie10'] == false) {$reglasValidacion->au_referencia_cruzada_cie10 = 0;} else { $reglasValidacion->au_referencia_cruzada_cie10 = 1;}
			if (is_null($request->au['diagnosticoSecundario1']) || $request->au['diagnosticoSecundario1'] == false) {$reglasValidacion->au_diagnostico_secundario1 = 0;} else { $reglasValidacion->au_diagnostico_secundario1 = 1;}
			if (is_null($request->au['referenciaCruzadaCie101']) || $request->au['referenciaCruzadaCie101'] == false) {$reglasValidacion->au_referencia_cruzada_cie101 = 0;} else { $reglasValidacion->au_referencia_cruzada_cie101 = 1;}
			if (is_null($request->au['diagnosticoSecundario2']) || $request->au['diagnosticoSecundario2'] == false) {$reglasValidacion->au_diagnostico_secundario2 = 0;} else { $reglasValidacion->au_diagnostico_secundario2 = 1;}
			if (is_null($request->au['referenciaCruzadaCie102']) || $request->au['referenciaCruzadaCie102'] == false) {$reglasValidacion->au_referencia_cruzada_cie102 = 0;} else { $reglasValidacion->au_referencia_cruzada_cie102 = 1;}
			if (is_null($request->au['diagnosticoSecundario3']) || $request->au['diagnosticoSecundario3'] == false) {$reglasValidacion->au_diagnostico_secundario3 = 0;} else { $reglasValidacion->au_diagnostico_secundario3 = 1;}
			if (is_null($request->au['referenciaCruzadaCie103']) || $request->au['referenciaCruzadaCie103'] == false) {$reglasValidacion->au_referencia_cruzada_cie103 = 0;} else { $reglasValidacion->au_referencia_cruzada_cie103 = 1;}
			if (is_null($request->au['destinoUsuario']) || $request->au['destinoUsuario'] == false) {$reglasValidacion->au_destino_usuario = 0;} else { $reglasValidacion->au_destino_usuario = 1;}
			if (is_null($request->au['estadoSalida']) || $request->au['estadoSalida'] == false) {$reglasValidacion->au_estado_salida = 0;} else { $reglasValidacion->au_estado_salida = 1;}
			if (is_null($request->au['causaBasicaMuerte']) || $request->au['causaBasicaMuerte'] == false) {$reglasValidacion->au_causa_basica_muerte = 0;} else { $reglasValidacion->au_causa_basica_muerte = 1;}
			if (is_null($request->ah['numeroFacturaEnAf']) || $request->ah['numeroFacturaEnAf'] == false) {$reglasValidacion->ah_numero_factura_en_af = 0;} else { $reglasValidacion->ah_numero_factura_en_af = 1;}
			if (is_null($request->ah['codigoPrestadorCt']) || $request->ah['codigoPrestadorCt'] == false) {$reglasValidacion->ah_codigo_prestador_ct = 0;} else { $reglasValidacion->ah_codigo_prestador_ct = 1;}
			if (is_null($request->ah['rangosTipoIdentificacion']) || $request->ah['rangosTipoIdentificacion'] == false) {$reglasValidacion->ah_rangos_tipo_identificacion = 0;} else { $reglasValidacion->ah_rangos_tipo_identificacion = 1;}
			if (is_null($request->ah['longitudIdentificacion']) || $request->ah['longitudIdentificacion'] == false) {$reglasValidacion->ah_longitud_identificacion = 0;} else { $reglasValidacion->ah_longitud_identificacion = 1;}
			if (is_null($request->ah['viaIngreso']) || $request->ah['viaIngreso'] == false) {$reglasValidacion->ah_viaIngreso = 0;} else { $reglasValidacion->ah_viaIngreso = 1;}
			if (is_null($request->ah['fechaingreso']) || $request->ah['fechaingreso'] == false) {$reglasValidacion->ah_fecha_ingreso = 0;} else { $reglasValidacion->ah_fecha_ingreso = 1;}
			if (is_null($request->ah['fechaIngresoMenorFechaEgreso']) || $request->ah['fechaIngresoMenorFechaEgreso'] == false) {$reglasValidacion->ah_fecha_ingreso_menor_fecha_egreso = 0;} else { $reglasValidacion->ah_fecha_ingreso_menor_fecha_egreso = 1;}
			if (is_null($request->ah['numeroAutorizacion']) || $request->ah['numeroAutorizacion'] == false) {$reglasValidacion->ah_numero_autorizacion = 0;} else { $reglasValidacion->ah_numero_autorizacion = 1;}
			if (is_null($request->ah['causaExterna']) || $request->ah['causaExterna'] == false) {$reglasValidacion->ah_causa_externa = 0;} else { $reglasValidacion->ah_causa_externa = 1;}
			if (is_null($request->ah['diangosticoIngreso']) || $request->ah['diangosticoIngreso'] == false) {$reglasValidacion->ah_diangostico_ingreso = 0;} else { $reglasValidacion->ah_diangostico_ingreso = 1;}
			if (is_null($request->ah['diagnosticoPrincipalIngreso']) || $request->ah['diagnosticoPrincipalIngreso'] == false) {$reglasValidacion->ah_diagnostico_principal_ingreso = 0;} else { $reglasValidacion->ah_diagnostico_principal_ingreso = 1;}
			if (is_null($request->ah['referenciaCruzadaCie10Ingreso']) || $request->ah['referenciaCruzadaCie10Ingreso'] == false) {$reglasValidacion->ah_referencia_cruzada_cie10_ingreso = 0;} else { $reglasValidacion->ah_referencia_cruzada_cie10_ingreso = 1;}
			if (is_null($request->ah['diagnosticoPrincipal']) || $request->ah['diagnosticoPrincipal'] == false) {$reglasValidacion->ah_diagnostico_principal = 0;} else { $reglasValidacion->ah_diagnostico_principal = 1;}
			if (is_null($request->ah['referenciaCruzadaCie10']) || $request->ah['referenciaCruzadaCie10'] == false) {$reglasValidacion->ah_referencia_cruzada_cie10 = 0;} else { $reglasValidacion->ah_referencia_cruzada_cie10 = 1;}
			if (is_null($request->ah['diagnosticoSecundario1']) || $request->ah['diagnosticoSecundario1'] == false) {$reglasValidacion->ah_diagnostico_secundario1 = 0;} else { $reglasValidacion->ah_diagnostico_secundario1 = 1;}
			if (is_null($request->ah['referenciaCruzadaCie101']) || $request->ah['referenciaCruzadaCie101'] == false) {$reglasValidacion->ah_referencia_cruzada_cie101 = 0;} else { $reglasValidacion->ah_referencia_cruzada_cie101 = 1;}
			if (is_null($request->ah['diagnosticoSecundario2']) || $request->ah['diagnosticoSecundario2'] == false) {$reglasValidacion->ah_diagnostico_secundario2 = 0;} else { $reglasValidacion->ah_diagnostico_secundario2 = 1;}
			if (is_null($request->ah['referenciaCruzadaCie102']) || $request->ah['referenciaCruzadaCie102'] == false) {$reglasValidacion->ah_referencia_cruzada_cie102 = 0;} else { $reglasValidacion->ah_referencia_cruzada_cie102 = 1;}
			if (is_null($request->ah['diagnosticoSecundario3']) || $request->ah['diagnosticoSecundario3'] == false) {$reglasValidacion->ah_diagnostico_secundario3 = 0;} else { $reglasValidacion->ah_diagnostico_secundario3 = 1;}
			if (is_null($request->ah['referenciaCruzadaCie103']) || $request->ah['referenciaCruzadaCie103'] == false) {$reglasValidacion->ah_referencia_cruzada_cie103 = 0;} else { $reglasValidacion->ah_referencia_cruzada_cie103 = 1;}
			if (is_null($request->ah['diagnosticoSecundario4']) || $request->ah['diagnosticoSecundario4'] == false) {$reglasValidacion->ah_diagnostico_secundario4 = 0;} else { $reglasValidacion->ah_diagnostico_secundario4 = 1;}
			if (is_null($request->ah['referenciaCruzadaCie104']) || $request->ah['referenciaCruzadaCie104'] == false) {$reglasValidacion->ah_referencia_cruzada_cie104 = 0;} else { $reglasValidacion->ah_referencia_cruzada_cie104 = 1;}
			if (is_null($request->ah['estadoSalida']) || $request->ah['estadoSalida'] == false) {$reglasValidacion->ah_estado_salida = 0;} else { $reglasValidacion->ah_estado_salida = 1;}
			if (is_null($request->ah['causaBasicaMuerte']) || $request->ah['causaBasicaMuerte'] == false) {$reglasValidacion->ah_causa_basica_muerte = 0;} else { $reglasValidacion->ah_causa_basica_muerte = 1;}
			if (is_null($request->ah['diagnosticoSecundario5']) || $request->ah['diagnosticoSecundario5'] == false) {$reglasValidacion->ah_diagnostico_secundario5 = 0;} else { $reglasValidacion->ah_diagnostico_secundario5 = 1;}
			if (is_null($request->ah['referenciaCruzadaCie105']) || $request->ah['referenciaCruzadaCie105'] == false) {$reglasValidacion->ah_referencia_cruzada_cie105 = 0;} else { $reglasValidacion->ah_referencia_cruzada_cie105 = 1;}
			if (is_null($request->an['numeroFacturaEnAf']) || $request->an['numeroFacturaEnAf'] == false) {$reglasValidacion->an_numero_factura_en_af = 0;} else { $reglasValidacion->an_numero_factura_en_af = 1;}
			if (is_null($request->an['codigoPrestadorCt']) || $request->an['codigoPrestadorCt'] == false) {$reglasValidacion->an_codigo_prestador_ct = 0;} else { $reglasValidacion->an_codigo_prestador_ct = 1;}
			if (is_null($request->an['rangosTipoIdentificacion']) || $request->an['rangosTipoIdentificacion'] == false) {$reglasValidacion->an_rangos_tipo_identificacion = 0;} else { $reglasValidacion->an_rangos_tipo_identificacion = 1;}
			if (is_null($request->an['longitudIdentificacion']) || $request->an['longitudIdentificacion'] == false) {$reglasValidacion->an_longitud_identificacion = 0;} else { $reglasValidacion->an_longitud_identificacion = 1;}
			if (is_null($request->an['fechaingreso']) || $request->an['fechaingreso'] == false) {$reglasValidacion->an_fecha_ingreso = 0;} else { $reglasValidacion->an_fecha_ingreso = 1;}
			if (is_null($request->an['fechaNacimientoMenorFechaSalida']) || $request->an['fechaNacimientoMenorFechaSalida'] == false) {$reglasValidacion->an_fecha_nacimiento_menor_fecha_salida = 0;} else { $reglasValidacion->an_fecha_nacimiento_menor_fecha_salida = 1;}
			if (is_null($request->an['controlPrenatal']) || $request->an['controlPrenatal'] == false) {$reglasValidacion->an_control_prenatal = 0;} else { $reglasValidacion->an_control_prenatal = 1;}
			if (is_null($request->an['generoAn']) || $request->an['generoAn'] == false) {$reglasValidacion->an_genero_an = 0;} else { $reglasValidacion->an_genero_an = 1;}
			if (is_null($request->an['pesoEntero']) || $request->an['pesoEntero'] == false) {$reglasValidacion->an_peso_entero = 0;} else { $reglasValidacion->an_peso_entero = 1;}
			if (is_null($request->an['diagnosticoSecundario1']) || $request->an['diagnosticoSecundario1'] == false) {$reglasValidacion->an_diagnostico_secundario1 = 0;} else { $reglasValidacion->an_diagnostico_secundario1 = 1;}
			if (is_null($request->an['diagnosticoSecundario2']) || $request->an['diagnosticoSecundario2'] == false) {$reglasValidacion->an_diagnostico_secundario2 = 0;} else { $reglasValidacion->an_diagnostico_secundario2 = 1;}
			if (is_null($request->am['numeroFacturaEnAf']) || $request->am['numeroFacturaEnAf'] == false) {$reglasValidacion->am_numero_factura_en_af = 0;} else { $reglasValidacion->am_numero_factura_en_af = 1;}
			if (is_null($request->am['codigoPrestadorCt']) || $request->am['codigoPrestadorCt'] == false) {$reglasValidacion->am_codigo_prestador_ct = 0;} else { $reglasValidacion->am_codigo_prestador_ct = 1;}
			if (is_null($request->am['rangosTipoIdentificacion']) || $request->am['rangosTipoIdentificacion'] == false) {$reglasValidacion->am_rangos_tipo_identificacion = 0;} else { $reglasValidacion->am_rangos_tipo_identificacion = 1;}
			if (is_null($request->am['longitudIdentificacion']) || $request->am['longitudIdentificacion'] == false) {$reglasValidacion->am_longitud_identificacion = 0;} else { $reglasValidacion->am_longitud_identificacion = 1;}
			if (is_null($request->am['numeroAutorizacion']) || $request->am['numeroAutorizacion'] == false) {$reglasValidacion->am_numero_autorizacion = 0;} else { $reglasValidacion->am_numero_autorizacion = 1;}
			if (is_null($request->am['codigoMedicamento']) || $request->am['codigoMedicamento'] == false) {$reglasValidacion->am_codigo_medicamento = 0;} else { $reglasValidacion->am_codigo_medicamento = 1;}
			if (is_null($request->am['tipoMedicamento']) || $request->am['tipoMedicamento'] == false) {$reglasValidacion->am_tipo_medicamento = 0;} else { $reglasValidacion->am_tipo_medicamento = 1;}
			if (is_null($request->am['valorUnitarioProducto']) || $request->am['valorUnitarioProducto'] == false) {$reglasValidacion->am_valor_unitario_producto = 0;} else { $reglasValidacion->am_valor_unitario_producto = 1;}
			if (is_null($request->am['valorTotalMedicamento']) || $request->am['valorTotalMedicamento'] == false) {$reglasValidacion->am_valor_total_medicamento = 0;} else { $reglasValidacion->am_valor_total_medicamento = 1;}
			if (is_null($request->am['valorMedicamentoCero']) || $request->am['valorMedicamentoCero'] == false) {$reglasValidacion->am_valor_cero = 0;} else { $reglasValidacion->am_valor_cero = 1;}
			if (is_null($request->at['numeroFacturaEnAf']) || $request->at['numeroFacturaEnAf'] == false) {$reglasValidacion->at_numero_factura_en_af = 0;} else { $reglasValidacion->at_numero_factura_en_af = 1;}
			if (is_null($request->at['codigoPrestadorCt']) || $request->at['codigoPrestadorCt'] == false) {$reglasValidacion->at_codigo_prestador_ct = 0;} else { $reglasValidacion->at_codigo_prestador_ct = 1;}
			if (is_null($request->at['rangosTipoIdentificacion']) || $request->at['rangosTipoIdentificacion'] == false) {$reglasValidacion->at_rangos_tipo_identificacion = 0;} else { $reglasValidacion->at_rangos_tipo_identificacion = 1;}
			if (is_null($request->at['longitudIdentificacion']) || $request->at['longitudIdentificacion'] == false) {$reglasValidacion->at_longitud_identificacion = 0;} else { $reglasValidacion->at_longitud_identificacion = 1;}
			if (is_null($request->at['numeroAutorizacion']) || $request->at['numeroAutorizacion'] == false) {$reglasValidacion->at_numero_autorizacion = 0;} else { $reglasValidacion->at_numero_autorizacion = 1;}
			if (is_null($request->at['tipoServicio']) || $request->at['tipoServicio'] == false) {$reglasValidacion->at_tipo_servicio = 0;} else { $reglasValidacion->at_tipo_servicio = 1;}
			if (is_null($request->at['valorTotalMaterialInsumo']) || $request->at['valorTotalMaterialInsumo'] == false) {$reglasValidacion->at_valor_total_material_insumo = 0;} else { $reglasValidacion->at_valor_total_material_insumo = 1;}
			if (is_null($request->at['valorTotalInsumoCero']) || $request->at['valorTotalInsumoCero'] == false) {$reglasValidacion->at_valor_insumo_cero = 0;} else { $reglasValidacion->at_valor_insumo_cero = 1;}
			if (is_null($request->ad['codigoPrestadorCt']) || $request->ad['codigoPrestadorCt'] == false) {$reglasValidacion->ad_codigo_prestador_ct = 0;} else { $reglasValidacion->ad_codigo_prestador_ct = 1;}
			if (is_null($request->ad['codigoConcepto']) || $request->ad['codigoConcepto'] == false) {$reglasValidacion->ad_codigo_concepto = 0;} else { $reglasValidacion->ad_codigo_concepto = 1;}
			$reglasValidacion->save();
			$reglasValidacionArray = new ReglasValidacion($reglasValidacion);
			return response()->json([
				"state" => "save",
				"reglas" => $reglasValidacionArray->getReglasArray(),
			]);
		} catch (\Exception $e) {
			return response()->json([
				"message" => "Error en el servidor",
				"error" => $e->getMessage(),
				"trace" => $e->getTrace(),
			]);
		}
	}

	public function show($id) {}

	public function update(Request $request, $id) {}

	public function destroy($id) {}
}
