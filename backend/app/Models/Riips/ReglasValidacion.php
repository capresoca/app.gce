<?php

namespace App\Models\Riips;

use App\Models\Riips\RsReglasValidacione;

class ReglasValidacion {
	private $reglas = null;

	/**
	 * @return mixed
	 */
	public function getReglas() {
		return $this->reglas;
	}

	/**
	 * @param mixed $reglas
	 *
	 * @return self
	 */
	public function setReglas($reglas) {
		$this->reglas = $reglas;

		return $this;
	}

	function __construct(RsReglasValidacione $reglas = null) {
		$this->reglas = $reglas;
	}

	public function getReglasArray() {
		//dd($this->reglas);
		$reglasValidacion = array(
			'ct' => array('codigo' => true, 'fecha' => true, 'nombreArchivo' => true, 'esEntero' => true),
			'af' => array('codigoPrestadorCt' => true, 'tipoIdentificacionPrestador' => true, 'existePrestadorServicioSalud' => true, 'identificacionPrestador' => true, 'mismaFacturaCaptiacion' => true, 'fechaExpedicion' => true, 'fechaInicio' => true, 'FechaInicioMenorQueFechafinal' => true, 'fechaFinal' => true, 'fechaFinalMayorQueFechaInicial' => true, 'codigoEntidadadministradora' => true, 'numeroPositivo' => true, 'cruceFacturas' => true, 'sumaValoresFacturas' => true),
			'us' => array('rangosTipoEdad' => true, 'edad' => true, 'tipoIdentificacionUsuario' => true, 'longitudIdentificacion' => true, 'tipoUsuario' => true, 'rangoGenero' => true, 'codigoDepartamento' => true, 'codigoMunicipio' => true, 'zona' => true),
			'ac' => array('numeroFacturaEnAf' => true, 'codigoPrestadorCt' => true, 'rangosTipoIdentificacion' => true, 'longitudIdentificacion' => true, 'fechaConsulta' => true, 'numeroAutorizacion' => true, 'existeCup' => true, 'cupValidoConsulta' => true, 'rangoFinalidad' => true, 'finalidadUltimos' => true, 'referenciaCruzadaFinalidad' => true, 'causaExterna' => true, 'diagnosticoPrincipal' => true, 'referenciaCruzadaCie10' => true, 'referenciaCruzadaCie10Cups' => true, 'diagnosticoSecundario1' => true, 'referenciaCruzadaCie101' => true, 'referenciaCruzadaCie10Cups1' => true, 'diagnosticoSecundario2' => true, 'referenciaCruzadaCie102' => true, 'referenciaCruzadaCie10Cups2' => true, 'diagnosticoSecundario3' => true, 'referenciaCruzadaCie103' => true, 'referenciaCruzadaCie10Cups3' => true, 'tipoDiagnosticoPrincipal' => true, 'valorConsultaEntero' => true, 'valorConsultaCero' => true, 'valorConsultaSiempreCeroCapitacion' => true, 'valorCuotaModeradoraEntero' => true, 'valorNetoEntero' => true, 'valorNetoCero' => true),
			'ap' => array('numeroFacturaEnAf' => true, 'codigoPrestadorCt' => true, 'rangosTipoIdentificacion' => true, 'longitudIdentificacion' => true, 'fechaProcedimiento' => true, 'numeroAutorizacion' => true, 'existeCup' => true, 'realizacionProcedimiento' => true, 'finalidadProcedimiento' => true, 'personalQueAtiende' => true, 'diagnosticoQuirurgico' => true, 'diagnosticoPrincipal' => true, 'referenciaCruzadaCie10' => true, 'referenciaCruzadaCie10Cups' => true, 'diagnosticoSecundario1' => true, 'referenciaCruzadaCie101' => true, 'referenciaCruzadaCie10Cups1' => true, 'diagnosticoSecundario2' => true, 'referenciaCruzadaCie102' => true, 'referenciaCruzadaCie10Cups2' => true, 'formaRealizacion' => true, 'valorProcedimientoEntero' => true, 'valorConsultaCero' => true, 'valorConsultaSiempreCero' => true),
			'au' => array('numeroFacturaEnAf' => true, 'codigoPrestadorCt' => true, 'rangosTipoIdentificacion' => true, 'longitudIdentificacion' => true, 'fechaingreso' => true, 'fechaIngresoMenorFechaEgreso' => true, 'numeroAutorizacion' => true, 'causaExterna' => true, 'diagnosticoPrincipal' => true, 'referenciaCruzadaCie10' => true, 'diagnosticoSecundario1' => true, 'referenciaCruzadaCie101' => true, 'diagnosticoSecundario2' => true, 'referenciaCruzadaCie102' => true, 'diagnosticoSecundario3' => true, 'referenciaCruzadaCie103' => true, 'destinoUsuario' => true, 'estadoSalida' => true, 'causaBasicaMuerte' => true),
			'ah' => array('numeroFacturaEnAf' => true, 'codigoPrestadorCt' => true, 'rangosTipoIdentificacion' => true, 'longitudIdentificacion' => true, 'viaIngreso' => true, 'fechaingreso' => true, 'fechaIngresoMenorFechaEgreso' => true, 'numeroAutorizacion' => true, 'causaExterna' => true, 'diangosticoIngreso' => true, 'diagnosticoPrincipalIngreso' => true, 'referenciaCruzadaCie10Ingreso' => true, 'diagnosticoPrincipal' => true, 'referenciaCruzadaCie10' => true, 'diagnosticoSecundario1' => true, 'referenciaCruzadaCie101' => true, 'diagnosticoSecundario2' => true, 'referenciaCruzadaCie102' => true, 'diagnosticoSecundario3' => true, 'referenciaCruzadaCie103' => true, 'diagnosticoSecundario4' => true, 'referenciaCruzadaCie104' => true, 'estadoSalida' => true, 'causaBasicaMuerte' => true, 'diagnosticoSecundario5' => true, 'referenciaCruzadaCie105' => true),
			'an' => array('numeroFacturaEnAf' => true, 'codigoPrestadorCt' => true, 'rangosTipoIdentificacion' => true, 'longitudIdentificacion' => true, 'fechaingreso' => true, 'fechaNacimientoMenorFechaSalida' => true, 'controlPrenatal' => true, 'generoAn' => true, 'pesoEntero' => true, 'diagnosticoSecundario1' => true, 'diagnosticoSecundario2' => true),
			'am' => array('numeroFacturaEnAf' => true, 'codigoPrestadorCt' => true, 'rangosTipoIdentificacion' => true, 'longitudIdentificacion' => true, 'numeroAutorizacion' => true, 'codigoMedicamento' => true, 'tipoMedicamento' => true, 'valorUnitarioProducto' => true, 'valorTotalMedicamento' => true, 'valorMedicamentoCero' => true),
			'at' => array('numeroFacturaEnAf' => true, 'codigoPrestadorCt' => true, 'rangosTipoIdentificacion' => true, 'longitudIdentificacion' => true, 'numeroAutorizacion' => true, 'tipoServicio' => true, 'valorTotalMaterialInsumo' => true, 'valorTotalInsumoCero' => true),
			'ad' => array('codigoPrestadorCt' => true, 'codigoConcepto' => true));
		if (!is_null($this->reglas)) {
			if ($this->reglas->ct_codigo == 1) {$reglasValidacion['ct']['codigo'] = true;} else { $reglasValidacion['ct']['codigo'] = false;}
			if ($this->reglas->ct_fecha == 1) {$reglasValidacion['ct']['fecha'] = true;} else { $reglasValidacion['ct']['fecha'] = false;}
			if ($this->reglas->ct_nombre_archivo == 1) {$reglasValidacion['ct']['nombreArchivo'] = true;} else { $reglasValidacion['ct']['nombreArchivo'] = false;}
			if ($this->reglas->ct_entero == 1) {$reglasValidacion['ct']['esEntero'] = true;} else { $reglasValidacion['ct']['esEntero'] = false;}
			if ($this->reglas->af_codigo_prestador_ct == 1) {$reglasValidacion['af']['codigoPrestadorCt'] = true;} else { $reglasValidacion['af']['codigoPrestadorCt'] = false;}
			if ($this->reglas->af_tipo_identificacion_prestador == 1) {$reglasValidacion['af']['tipoIdentificacionPrestador'] = true;} else { $reglasValidacion['af']['tipoIdentificacionPrestador'] = false;}
			if ($this->reglas->af_existe_prestador == 1) {$reglasValidacion['af']['existePrestadorServicioSalud'] = true;} else { $reglasValidacion['af']['existePrestadorServicioSalud'] = false;}
			if ($this->reglas->af_identificacion_prestador == 1) {$reglasValidacion['af']['identificacionPrestador'] = true;} else { $reglasValidacion['af']['identificacionPrestador'] = false;}
			if ($this->reglas->af_misma_factura_capitacion == 1) {$reglasValidacion['af']['mismaFacturaCaptiacion'] = true;} else { $reglasValidacion['af']['mismaFacturaCaptiacion'] = false;}
			if ($this->reglas->af_fecha_expedicion == 1) {$reglasValidacion['af']['fechaExpedicion'] = true;} else { $reglasValidacion['af']['fechaExpedicion'] = false;}
			if ($this->reglas->af_fecha_inicio == 1) {$reglasValidacion['af']['fechaInicio'] = true;} else { $reglasValidacion['af']['fechaInicio'] = false;}
			if ($this->reglas->af_fecha_inicio_menor_fecha_final == 1) {$reglasValidacion['af']['FechaInicioMenorQueFechafinal'] = true;} else { $reglasValidacion['af']['FechaInicioMenorQueFechafinal'] = false;}
			if ($this->reglas->af_fecha_final == 1) {$reglasValidacion['af']['fechaFinal'] = true;} else { $reglasValidacion['af']['fechaFinal'] = false;}
			if ($this->reglas->af_fecha_final_mayor_fecha_inicial == 1) {$reglasValidacion['af']['fechaFinalMayorQueFechaInicial'] = true;} else { $reglasValidacion['af']['fechaFinalMayorQueFechaInicial'] = false;}
			if ($this->reglas->af_codigo_entidad == 1) {$reglasValidacion['af']['codigoEntidadadministradora'] = true;} else { $reglasValidacion['af']['codigoEntidadadministradora'] = false;}
			if ($this->reglas->af_numero_positivo == 1) {$reglasValidacion['af']['numeroPositivo'] = true;} else { $reglasValidacion['af']['numeroPositivo'] = false;}
			if ($this->reglas->af_cruce_factura == 1) {$reglasValidacion['af']['cruceFacturas'] = true;} else { $reglasValidacion['af']['cruceFacturas'] = false;}
			if ($this->reglas->af_suma_valores_facturas == 1) {$reglasValidacion['af']['sumaValoresFacturas'] = true;} else { $reglasValidacion['af']['sumaValoresFacturas'] = false;}
			if ($this->reglas->us_rangos_tipo_edad == 1) {$reglasValidacion['us']['rangosTipoEdad'] = true;} else { $reglasValidacion['us']['rangosTipoEdad'] = false;}
			if ($this->reglas->us_edad == 1) {$reglasValidacion['us']['edad'] = true;} else { $reglasValidacion['us']['edad'] = false;}
			if ($this->reglas->us_tipo_identificacion_usuario == 1) {$reglasValidacion['us']['tipoIdentificacionUsuario'] = true;} else { $reglasValidacion['us']['tipoIdentificacionUsuario'] = false;}
			if ($this->reglas->us_longitud_identificacion == 1) {$reglasValidacion['us']['longitudIdentificacion'] = true;} else { $reglasValidacion['us']['longitudIdentificacion'] = false;}
			if ($this->reglas->us_tipo_usuario == 1) {$reglasValidacion['us']['tipoUsuario'] = true;} else { $reglasValidacion['us']['tipoUsuario'] = false;}
			if ($this->reglas->us_rango_genero == 1) {$reglasValidacion['us']['rangoGenero'] = true;} else { $reglasValidacion['us']['rangoGenero'] = false;}
			if ($this->reglas->us_codigo_departamento == 1) {$reglasValidacion['us']['codigoDepartamento'] = true;} else { $reglasValidacion['us']['codigoDepartamento'] = false;}
			if ($this->reglas->us_codigo_municipio == 1) {$reglasValidacion['us']['codigoMunicipio'] = true;} else { $reglasValidacion['us']['codigoMunicipio'] = false;}
			if ($this->reglas->us_zona == 1) {$reglasValidacion['us']['zona'] = true;} else { $reglasValidacion['us']['zona'] = false;}
			if ($this->reglas->ac_numero_factura_en_af == 1) {$reglasValidacion['ac']['numeroFacturaEnAf'] = true;} else { $reglasValidacion['ac']['numeroFacturaEnAf'] = false;}
			if ($this->reglas->ac_codigo_prestador_ct == 1) {$reglasValidacion['ac']['codigoPrestadorCt'] = true;} else { $reglasValidacion['ac']['codigoPrestadorCt'] = false;}
			if ($this->reglas->ac_rangos_tipo_identificacion == 1) {$reglasValidacion['ac']['rangosTipoIdentificacion'] = true;} else { $reglasValidacion['ac']['rangosTipoIdentificacion'] = false;}
			if ($this->reglas->ac_longitud_identificacion == 1) {$reglasValidacion['ac']['longitudIdentificacion'] = true;} else { $reglasValidacion['ac']['longitudIdentificacion'] = false;}
			if ($this->reglas->ac_fecha_consulta == 1) {$reglasValidacion['ac']['fechaConsulta'] = true;} else { $reglasValidacion['ac']['fechaConsulta'] = false;}
			if ($this->reglas->ac_numero_autorizacion == 1) {$reglasValidacion['ac']['numeroAutorizacion'] = true;} else { $reglasValidacion['ac']['numeroAutorizacion'] = false;}
			if ($this->reglas->ac_existe_cup == 1) {$reglasValidacion['ac']['existeCup'] = true;} else { $reglasValidacion['ac']['existeCup'] = false;}
			if ($this->reglas->ac_cup_valido_consulta == 1) {$reglasValidacion['ac']['cupValidoConsulta'] = true;} else { $reglasValidacion['ac']['cupValidoConsulta'] = false;}
			if ($this->reglas->ac_rango_finalidad == 1) {$reglasValidacion['ac']['rangoFinalidad'] = true;} else { $reglasValidacion['ac']['rangoFinalidad'] = false;}
			if ($this->reglas->ac_finalidad_ultimos == 1) {$reglasValidacion['ac']['finalidadUltimos'] = true;} else { $reglasValidacion['ac']['finalidadUltimos'] = false;}
			if ($this->reglas->ac_referencia_cruzada_finalidad == 1) {$reglasValidacion['ac']['referenciaCruzadaFinalidad'] = true;} else { $reglasValidacion['ac']['referenciaCruzadaFinalidad'] = false;}
			if ($this->reglas->ac_causa_externa == 1) {$reglasValidacion['ac']['causaExterna'] = true;} else { $reglasValidacion['ac']['causaExterna'] = false;}
			if ($this->reglas->ac_diagnostico_principal == 1) {$reglasValidacion['ac']['diagnosticoPrincipal'] = true;} else { $reglasValidacion['ac']['diagnosticoPrincipal'] = false;}
			if ($this->reglas->ac_referencia_cruzada_cie10 == 1) {$reglasValidacion['ac']['referenciaCruzadaCie10'] = true;} else { $reglasValidacion['ac']['referenciaCruzadaCie10'] = false;}
			if ($this->reglas->ac_referencia_cruzada_cie10_cups == 1) {$reglasValidacion['ac']['referenciaCruzadaCie10Cups'] = true;} else { $reglasValidacion['ac']['referenciaCruzadaCie10Cups'] = false;}
			if ($this->reglas->ac_diagnostico_secundario1 == 1) {$reglasValidacion['ac']['diagnosticoSecundario1'] = true;} else { $reglasValidacion['ac']['diagnosticoSecundario1'] = false;}
			if ($this->reglas->ac_referencia_cruzada_cie101 == 1) {$reglasValidacion['ac']['referenciaCruzadaCie101'] = true;} else { $reglasValidacion['ac']['referenciaCruzadaCie101'] = false;}
			if ($this->reglas->ac_referencia_cruzada_cie10_cups1 == 1) {$reglasValidacion['ac']['referenciaCruzadaCie10Cups1'] = true;} else { $reglasValidacion['ac']['referenciaCruzadaCie10Cups1'] = false;}
			if ($this->reglas->ac_diagnostico_secundario2 == 1) {$reglasValidacion['ac']['diagnosticoSecundario2'] = true;} else { $reglasValidacion['ac']['diagnosticoSecundario2'] = false;}
			if ($this->reglas->ac_referencia_cruzada_cie102 == 1) {$reglasValidacion['ac']['referenciaCruzadaCie102'] = true;} else { $reglasValidacion['ac']['referenciaCruzadaCie102'] = false;}
			if ($this->reglas->ac_referencia_cruzada_cie10_cups2 == 1) {$reglasValidacion['ac']['referenciaCruzadaCie10Cups2'] = true;} else { $reglasValidacion['ac']['referenciaCruzadaCie10Cups2'] = false;}
			if ($this->reglas->ac_diagnostico_secundario3 == 1) {$reglasValidacion['ac']['diagnosticoSecundario3'] = true;} else { $reglasValidacion['ac']['diagnosticoSecundario3'] = false;}
			if ($this->reglas->ac_referencia_cruzada_cie103 == 1) {$reglasValidacion['ac']['referenciaCruzadaCie103'] = true;} else { $reglasValidacion['ac']['referenciaCruzadaCie103'] = false;}
			if ($this->reglas->ac_referencia_cruzada_cie10_cups3 == 1) {$reglasValidacion['ac']['referenciaCruzadaCie10Cups3'] = true;} else { $reglasValidacion['ac']['referenciaCruzadaCie10Cups3'] = false;}
			if ($this->reglas->ac_tipo_diagnostico_principal == 1) {$reglasValidacion['ac']['tipoDiagnosticoPrincipal'] = true;} else { $reglasValidacion['ac']['tipoDiagnosticoPrincipal'] = false;}
			if ($this->reglas->ac_valor_consulta_entero == 1) {$reglasValidacion['ac']['valorConsultaEntero'] = true;} else { $reglasValidacion['ac']['valorConsultaEntero'] = false;}
			if ($this->reglas->ac_valor_consulta_cero == 1) {$reglasValidacion['ac']['valorConsultaCero'] = true;} else { $reglasValidacion['ac']['valorConsultaCero'] = false;}
			if ($this->reglas->ac_valor_consulta_siempre_cero_capitacion == 1) {$reglasValidacion['ac']['valorConsultaSiempreCeroCapitacion'] = true;} else { $reglasValidacion['ac']['valorConsultaSiempreCeroCapitacion'] = false;}
			if ($this->reglas->ac_valor_cuota_moderadora_entero == 1) {$reglasValidacion['ac']['valorCuotaModeradoraEntero'] = true;} else { $reglasValidacion['ac']['valorCuotaModeradoraEntero'] = false;}
			if ($this->reglas->ac_valor_neto_entero == 1) {$reglasValidacion['ac']['valorNetoEntero'] = true;} else { $reglasValidacion['ac']['valorNetoEntero'] = false;}
			if ($this->reglas->ac_valor_neto_cero == 1) {$reglasValidacion['ac']['valorNetoCero'] = true;} else { $reglasValidacion['ac']['valorNetoCero'] = false;}
			if ($this->reglas->ap_numero_factura_en_af == 1) {$reglasValidacion['ap']['numeroFacturaEnAf'] = true;} else { $reglasValidacion['ap']['numeroFacturaEnAf'] = false;}
			if ($this->reglas->ap_codigo_prestador_ct == 1) {$reglasValidacion['ap']['codigoPrestadorCt'] = true;} else { $reglasValidacion['ap']['codigoPrestadorCt'] = false;}
			if ($this->reglas->ap_rangos_tipo_identificacion == 1) {$reglasValidacion['ap']['rangosTipoIdentificacion'] = true;} else { $reglasValidacion['ap']['rangosTipoIdentificacion'] = false;}
			if ($this->reglas->ap_longitud_identificacion == 1) {$reglasValidacion['ap']['longitudIdentificacion'] = true;} else { $reglasValidacion['ap']['longitudIdentificacion'] = false;}
			if ($this->reglas->ap_fecha_procedimiento == 1) {$reglasValidacion['ap']['fechaProcedimiento'] = true;} else { $reglasValidacion['ap']['fechaProcedimiento'] = false;}
			if ($this->reglas->ap_numero_autorizacion == 1) {$reglasValidacion['ap']['numeroAutorizacion'] = true;} else { $reglasValidacion['ap']['numeroAutorizacion'] = false;}
			if ($this->reglas->ap_existe_cup == 1) {$reglasValidacion['ap']['existeCup'] = true;} else { $reglasValidacion['ap']['existeCup'] = false;}
			if ($this->reglas->ap_realizacion_procedimiento == 1) {$reglasValidacion['ap']['realizacionProcedimiento'] = true;} else { $reglasValidacion['ap']['realizacionProcedimiento'] = false;}
			if ($this->reglas->ap_finalidad_procedimiento == 1) {$reglasValidacion['ap']['finalidadProcedimiento'] = true;} else { $reglasValidacion['ap']['finalidadProcedimiento'] = false;}
			if ($this->reglas->ap_personal_que_atiende == 1) {$reglasValidacion['ap']['personalQueAtiende'] = true;} else { $reglasValidacion['ap']['personalQueAtiende'] = false;}
			if ($this->reglas->ap_diagnostico_quirurgico == 1) {$reglasValidacion['ap']['diagnosticoQuirurgico'] = true;} else { $reglasValidacion['ap']['diagnosticoQuirurgico'] = false;}
			if ($this->reglas->ap_diagnostico_principal == 1) {$reglasValidacion['ap']['diagnosticoPrincipal'] = true;} else { $reglasValidacion['ap']['diagnosticoPrincipal'] = false;}
			if ($this->reglas->ap_referencia_cruzada_cie10 == 1) {$reglasValidacion['ap']['referenciaCruzadaCie10'] = true;} else { $reglasValidacion['ap']['referenciaCruzadaCie10'] = false;}
			if ($this->reglas->ap_referencia_cruzada_cie10_cups == 1) {$reglasValidacion['ap']['referenciaCruzadaCie10Cups'] = true;} else { $reglasValidacion['ap']['referenciaCruzadaCie10Cups'] = false;}
			if ($this->reglas->ap_diagnostico_secundario1 == 1) {$reglasValidacion['ap']['diagnosticoSecundario1'] = true;} else { $reglasValidacion['ap']['diagnosticoSecundario1'] = false;}
			if ($this->reglas->ap_referencia_cruzada_cie101 == 1) {$reglasValidacion['ap']['referenciaCruzadaCie101'] = true;} else { $reglasValidacion['ap']['referenciaCruzadaCie101'] = false;}
			if ($this->reglas->ap_referencia_cruzada_cie10_cups1 == 1) {$reglasValidacion['ap']['referenciaCruzadaCie10Cups1'] = true;} else { $reglasValidacion['ap']['referenciaCruzadaCie10Cups1'] = false;}
			if ($this->reglas->ap_diagnostico_secundario2 == 1) {$reglasValidacion['ap']['diagnosticoSecundario2'] = true;} else { $reglasValidacion['ap']['diagnosticoSecundario2'] = false;}
			if ($this->reglas->ap_referencia_cruzada_cie102 == 1) {$reglasValidacion['ap']['referenciaCruzadaCie102'] = true;} else { $reglasValidacion['ap']['referenciaCruzadaCie102'] = false;}
			if ($this->reglas->ap_referencia_cruzada_cie10_cups2 == 1) {$reglasValidacion['ap']['referenciaCruzadaCie10Cups2'] = true;} else { $reglasValidacion['ap']['referenciaCruzadaCie10Cups2'] = false;}
			if ($this->reglas->ap_forma_realizacion == 1) {$reglasValidacion['ap']['formaRealizacion'] = true;} else { $reglasValidacion['ap']['formaRealizacion'] = false;}
			if ($this->reglas->ap_valor_procedimiento_entero == 1) {$reglasValidacion['ap']['valorProcedimientoEntero'] = true;} else { $reglasValidacion['ap']['valorProcedimientoEntero'] = false;}
			if ($this->reglas->ap_valor_consulta_cero == 1) {$reglasValidacion['ap']['valorConsultaCero'] = true;} else { $reglasValidacion['ap']['valorConsultaCero'] = false;}
			if ($this->reglas->ap_valor_consulta_siempre_cero == 1) {$reglasValidacion['ap']['valorConsultaSiempreCero'] = true;} else { $reglasValidacion['ap']['valorConsultaSiempreCero'] = false;}
			if ($this->reglas->au_numero_factura_en_af == 1) {$reglasValidacion['au']['numeroFacturaEnAf'] = true;} else { $reglasValidacion['au']['numeroFacturaEnAf'] = false;}
			if ($this->reglas->au_codigo_prestador_ct == 1) {$reglasValidacion['au']['codigoPrestadorCt'] = true;} else { $reglasValidacion['au']['codigoPrestadorCt'] = false;}
			if ($this->reglas->au_rangos_tipo_identificacion == 1) {$reglasValidacion['au']['rangosTipoIdentificacion'] = true;} else { $reglasValidacion['au']['rangosTipoIdentificacion'] = false;}
			if ($this->reglas->au_longitud_identificacion == 1) {$reglasValidacion['au']['longitudIdentificacion'] = true;} else { $reglasValidacion['au']['longitudIdentificacion'] = false;}
			if ($this->reglas->au_fecha_ingreso == 1) {$reglasValidacion['au']['fechaingreso'] = true;} else { $reglasValidacion['au']['fechaingreso'] = false;}
			if ($this->reglas->au_fecha_ingreso_menor_fecha_egreso == 1) {$reglasValidacion['au']['fechaIngresoMenorFechaEgreso'] = true;} else { $reglasValidacion['au']['fechaIngresoMenorFechaEgreso'] = false;}
			if ($this->reglas->au_numero_autorizacion == 1) {$reglasValidacion['au']['numeroAutorizacion'] = true;} else { $reglasValidacion['au']['numeroAutorizacion'] = false;}
			if ($this->reglas->au_causa_externa == 1) {$reglasValidacion['au']['causaExterna'] = true;} else { $reglasValidacion['au']['causaExterna'] = false;}
			if ($this->reglas->au_diagnostico_principal == 1) {$reglasValidacion['au']['diagnosticoPrincipal'] = true;} else { $reglasValidacion['au']['diagnosticoPrincipal'] = false;}
			if ($this->reglas->au_referencia_cruzada_cie10 == 1) {$reglasValidacion['au']['referenciaCruzadaCie10'] = true;} else { $reglasValidacion['au']['referenciaCruzadaCie10'] = false;}
			if ($this->reglas->au_diagnostico_secundario1 == 1) {$reglasValidacion['au']['diagnosticoSecundario1'] = true;} else { $reglasValidacion['au']['diagnosticoSecundario1'] = false;}
			if ($this->reglas->au_referencia_cruzada_cie101 == 1) {$reglasValidacion['au']['referenciaCruzadaCie101'] = true;} else { $reglasValidacion['au']['referenciaCruzadaCie101'] = false;}
			if ($this->reglas->au_diagnostico_secundario2 == 1) {$reglasValidacion['au']['diagnosticoSecundario2'] = true;} else { $reglasValidacion['au']['diagnosticoSecundario2'] = false;}
			if ($this->reglas->au_referencia_cruzada_cie102 == 1) {$reglasValidacion['au']['referenciaCruzadaCie102'] = true;} else { $reglasValidacion['au']['referenciaCruzadaCie102'] = false;}
			if ($this->reglas->au_diagnostico_secundario3 == 1) {$reglasValidacion['au']['diagnosticoSecundario3'] = true;} else { $reglasValidacion['au']['diagnosticoSecundario3'] = false;}
			if ($this->reglas->au_referencia_cruzada_cie103 == 1) {$reglasValidacion['au']['referenciaCruzadaCie103'] = true;} else { $reglasValidacion['au']['referenciaCruzadaCie103'] = false;}
			if ($this->reglas->au_destino_usuario == 1) {$reglasValidacion['au']['destinoUsuario'] = true;} else { $reglasValidacion['au']['destinoUsuario'] = false;}
			if ($this->reglas->au_estado_salida == 1) {$reglasValidacion['au']['estadoSalida'] = true;} else { $reglasValidacion['au']['estadoSalida'] = false;}
			if ($this->reglas->au_causa_basica_muerte == 1) {$reglasValidacion['au']['causaBasicaMuerte'] = true;} else { $reglasValidacion['au']['causaBasicaMuerte'] = false;}
			if ($this->reglas->ah_numero_factura_en_af == 1) {$reglasValidacion['ah']['numeroFacturaEnAf'] = true;} else { $reglasValidacion['ah']['numeroFacturaEnAf'] = false;}
			if ($this->reglas->ah_codigo_prestador_ct == 1) {$reglasValidacion['ah']['codigoPrestadorCt'] = true;} else { $reglasValidacion['ah']['codigoPrestadorCt'] = false;}
			if ($this->reglas->ah_rangos_tipo_identificacion == 1) {$reglasValidacion['ah']['rangosTipoIdentificacion'] = true;} else { $reglasValidacion['ah']['rangosTipoIdentificacion'] = false;}
			if ($this->reglas->ah_longitud_identificacion == 1) {$reglasValidacion['ah']['longitudIdentificacion'] = true;} else { $reglasValidacion['ah']['longitudIdentificacion'] = false;}
			if ($this->reglas->ah_viaIngreso == 1) {$reglasValidacion['ah']['viaIngreso'] = true;} else { $reglasValidacion['ah']['viaIngreso'] = false;}
			if ($this->reglas->ah_fecha_ingreso == 1) {$reglasValidacion['ah']['fechaingreso'] = true;} else { $reglasValidacion['ah']['fechaingreso'] = false;}
			if ($this->reglas->ah_fecha_ingreso_menor_fecha_egreso == 1) {$reglasValidacion['ah']['fechaIngresoMenorFechaEgreso'] = true;} else { $reglasValidacion['ah']['fechaIngresoMenorFechaEgreso'] = false;}
			if ($this->reglas->ah_numero_autorizacion == 1) {$reglasValidacion['ah']['numeroAutorizacion'] = true;} else { $reglasValidacion['ah']['numeroAutorizacion'] = false;}
			if ($this->reglas->ah_causa_externa == 1) {$reglasValidacion['ah']['causaExterna'] = true;} else { $reglasValidacion['ah']['causaExterna'] = false;}
			if ($this->reglas->ah_diangostico_ingreso == 1) {$reglasValidacion['ah']['diangosticoIngreso'] = true;} else { $reglasValidacion['ah']['diangosticoIngreso'] = false;}
			if ($this->reglas->ah_diagnostico_principal_ingreso == 1) {$reglasValidacion['ah']['diagnosticoPrincipalIngreso'] = true;} else { $reglasValidacion['ah']['diagnosticoPrincipalIngreso'] = false;}
			if ($this->reglas->ah_referencia_cruzada_cie10_ingreso == 1) {$reglasValidacion['ah']['referenciaCruzadaCie10Ingreso'] = true;} else { $reglasValidacion['ah']['referenciaCruzadaCie10Ingreso'] = false;}
			if ($this->reglas->ah_diagnostico_principal == 1) {$reglasValidacion['ah']['diagnosticoPrincipal'] = true;} else { $reglasValidacion['ah']['diagnosticoPrincipal'] = false;}
			if ($this->reglas->ah_referencia_cruzada_cie10 == 1) {$reglasValidacion['ah']['referenciaCruzadaCie10'] = true;} else { $reglasValidacion['ah']['referenciaCruzadaCie10'] = false;}
			if ($this->reglas->ah_diagnostico_secundario1 == 1) {$reglasValidacion['ah']['diagnosticoSecundario1'] = true;} else { $reglasValidacion['ah']['diagnosticoSecundario1'] = false;}
			if ($this->reglas->ah_referencia_cruzada_cie101 == 1) {$reglasValidacion['ah']['referenciaCruzadaCie101'] = true;} else { $reglasValidacion['ah']['referenciaCruzadaCie101'] = false;}
			if ($this->reglas->ah_diagnostico_secundario2 == 1) {$reglasValidacion['ah']['diagnosticoSecundario2'] = true;} else { $reglasValidacion['ah']['diagnosticoSecundario2'] = false;}
			if ($this->reglas->ah_referencia_cruzada_cie102 == 1) {$reglasValidacion['ah']['referenciaCruzadaCie102'] = true;} else { $reglasValidacion['ah']['referenciaCruzadaCie102'] = false;}
			if ($this->reglas->ah_diagnostico_secundario3 == 1) {$reglasValidacion['ah']['diagnosticoSecundario3'] = true;} else { $reglasValidacion['ah']['diagnosticoSecundario3'] = false;}
			if ($this->reglas->ah_referencia_cruzada_cie103 == 1) {$reglasValidacion['ah']['referenciaCruzadaCie103'] = true;} else { $reglasValidacion['ah']['referenciaCruzadaCie103'] = false;}
			if ($this->reglas->ah_diagnostico_secundario4 == 1) {$reglasValidacion['ah']['diagnosticoSecundario4'] = true;} else { $reglasValidacion['ah']['diagnosticoSecundario4'] = false;}
			if ($this->reglas->ah_referencia_cruzada_cie104 == 1) {$reglasValidacion['ah']['referenciaCruzadaCie104'] = true;} else { $reglasValidacion['ah']['referenciaCruzadaCie104'] = false;}
			if ($this->reglas->ah_estado_salida == 1) {$reglasValidacion['ah']['estadoSalida'] = true;} else { $reglasValidacion['ah']['estadoSalida'] = false;}
			if ($this->reglas->ah_causa_basica_muerte == 1) {$reglasValidacion['ah']['causaBasicaMuerte'] = true;} else { $reglasValidacion['ah']['causaBasicaMuerte'] = false;}
			if ($this->reglas->ah_diagnostico_secundario5 == 1) {$reglasValidacion['ah']['diagnosticoSecundario5'] = true;} else { $reglasValidacion['ah']['diagnosticoSecundario5'] = false;}
			if ($this->reglas->ah_referencia_cruzada_cie105 == 1) {$reglasValidacion['ah']['referenciaCruzadaCie105'] = true;} else { $reglasValidacion['ah']['referenciaCruzadaCie105'] = false;}
			if ($this->reglas->an_numero_factura_en_af == 1) {$reglasValidacion['an']['numeroFacturaEnAf'] = true;} else { $reglasValidacion['an']['numeroFacturaEnAf'] = false;}
			if ($this->reglas->an_codigo_prestador_ct == 1) {$reglasValidacion['an']['codigoPrestadorCt'] = true;} else { $reglasValidacion['an']['codigoPrestadorCt'] = false;}
			if ($this->reglas->an_rangos_tipo_identificacion == 1) {$reglasValidacion['an']['rangosTipoIdentificacion'] = true;} else { $reglasValidacion['an']['rangosTipoIdentificacion'] = false;}
			if ($this->reglas->an_longitud_identificacion == 1) {$reglasValidacion['an']['longitudIdentificacion'] = true;} else { $reglasValidacion['an']['longitudIdentificacion'] = false;}
			if ($this->reglas->an_fecha_ingreso == 1) {$reglasValidacion['an']['fechaingreso'] = true;} else { $reglasValidacion['an']['fechaingreso'] = false;}
			if ($this->reglas->an_fecha_nacimiento_menor_fecha_salida == 1) {$reglasValidacion['an']['fechaNacimientoMenorFechaSalida'] = true;} else { $reglasValidacion['an']['fechaNacimientoMenorFechaSalida'] = false;}
			if ($this->reglas->an_control_prenatal == 1) {$reglasValidacion['an']['controlPrenatal'] = true;} else { $reglasValidacion['an']['controlPrenatal'] = false;}
			if ($this->reglas->an_genero_an == 1) {$reglasValidacion['an']['generoAn'] = true;} else { $reglasValidacion['an']['generoAn'] = false;}
			if ($this->reglas->an_peso_entero == 1) {$reglasValidacion['an']['pesoEntero'] = true;} else { $reglasValidacion['an']['pesoEntero'] = false;}
			if ($this->reglas->an_diagnostico_secundario1 == 1) {$reglasValidacion['an']['diagnosticoSecundario1'] = true;} else { $reglasValidacion['an']['diagnosticoSecundario1'] = false;}
			if ($this->reglas->an_diagnostico_secundario2 == 1) {$reglasValidacion['an']['diagnosticoSecundario2'] = true;} else { $reglasValidacion['an']['diagnosticoSecundario2'] = false;}
			if ($this->reglas->am_numero_factura_en_af == 1) {$reglasValidacion['am']['numeroFacturaEnAf'] = true;} else { $reglasValidacion['am']['numeroFacturaEnAf'] = false;}
			if ($this->reglas->am_codigo_prestador_ct == 1) {$reglasValidacion['am']['codigoPrestadorCt'] = true;} else { $reglasValidacion['am']['codigoPrestadorCt'] = false;}
			if ($this->reglas->am_rangos_tipo_identificacion == 1) {$reglasValidacion['am']['rangosTipoIdentificacion'] = true;} else { $reglasValidacion['am']['rangosTipoIdentificacion'] = false;}
			if ($this->reglas->am_longitud_identificacion == 1) {$reglasValidacion['am']['longitudIdentificacion'] = true;} else { $reglasValidacion['am']['longitudIdentificacion'] = false;}
			if ($this->reglas->am_numero_autorizacion == 1) {$reglasValidacion['am']['numeroAutorizacion'] = true;} else { $reglasValidacion['am']['numeroAutorizacion'] = false;}
			if ($this->reglas->am_codigo_medicamento == 1) {$reglasValidacion['am']['codigoMedicamento'] = true;} else { $reglasValidacion['am']['codigoMedicamento'] = false;}
			if ($this->reglas->am_tipo_medicamento == 1) {$reglasValidacion['am']['tipoMedicamento'] = true;} else { $reglasValidacion['am']['tipoMedicamento'] = false;}
			if ($this->reglas->am_valor_unitario_producto == 1) {$reglasValidacion['am']['valorUnitarioProducto'] = true;} else { $reglasValidacion['am']['valorUnitarioProducto'] = false;}
			if ($this->reglas->am_valor_total_medicamento == 1) {$reglasValidacion['am']['valorTotalMedicamento'] = true;} else { $reglasValidacion['am']['valorTotalMedicamento'] = false;}
			if ($this->reglas->am_valor_cero == 1) {$reglasValidacion['am']['valorMedicamentoCero'] = true;} else { $reglasValidacion['am']['valorMedicamentoCero'] = false;}
			if ($this->reglas->at_numero_factura_en_af == 1) {$reglasValidacion['at']['numeroFacturaEnAf'] = true;} else { $reglasValidacion['at']['numeroFacturaEnAf'] = false;}
			if ($this->reglas->at_codigo_prestador_ct == 1) {$reglasValidacion['at']['codigoPrestadorCt'] = true;} else { $reglasValidacion['at']['codigoPrestadorCt'] = false;}
			if ($this->reglas->at_rangos_tipo_identificacion == 1) {$reglasValidacion['at']['rangosTipoIdentificacion'] = true;} else { $reglasValidacion['at']['rangosTipoIdentificacion'] = false;}
			if ($this->reglas->at_longitud_identificacion == 1) {$reglasValidacion['at']['longitudIdentificacion'] = true;} else { $reglasValidacion['at']['longitudIdentificacion'] = false;}
			if ($this->reglas->at_numero_autorizacion == 1) {$reglasValidacion['at']['numeroAutorizacion'] = true;} else { $reglasValidacion['at']['numeroAutorizacion'] = false;}
			if ($this->reglas->at_tipo_servicio == 1) {$reglasValidacion['at']['tipoServicio'] = true;} else { $reglasValidacion['at']['tipoServicio'] = false;}
			if ($this->reglas->at_valor_total_material_insumo == 1) {$reglasValidacion['at']['valorTotalMaterialInsumo'] = true;} else { $reglasValidacion['at']['valorTotalMaterialInsumo'] = false;}
			if ($this->reglas->at_valor_insumo_cero == 1) {$reglasValidacion['at']['valorTotalInsumoCero'] = true;} else { $reglasValidacion['at']['valorTotalInsumoCero'] = false;}
			if ($this->reglas->ad_codigo_prestador_ct == 1) {$reglasValidacion['ad']['codigoPrestadorCt'] = true;} else { $reglasValidacion['ad']['codigoPrestadorCt'] = false;}
			if ($this->reglas->ad_codigo_concepto == 1) {$reglasValidacion['ad']['codigoConcepto'] = true;} else { $reglasValidacion['ad']['codigoConcepto'] = false;}
		}
		return $reglasValidacion;
	}
	public function descripcionReglasValidacion() {
		$reglasValidacion = array(
			'ct' => array(
				'codigo' => array(
					'descripcion' => 'Se valida que el código del prestador de servicios de salud siempre debe ser doce (12) dígitos',
					'valido' => '252900003601  => Doce (12) dígitos completos',
					'invalido' => '25290000360 => Once (11) dígitos completos  | 2529000036011 => Trece (13) dígitos completos',
				),
				'fecha' => array(
					'descripcion' => 'Se valida que la fecha de remisión NO sea mayor a la actual',
					'valido' => 'Fecha ingresada: 03/02/2018  Fecha actual: 04/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) NO ES MAYOR a cuatro de febrero de dos mil dieciocho 04/02/2018',
					'invalido' => 'Fecha ingresada: 03/02/2018  Fecha actual: 02/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) ES MAYOR a dos de febrero de dos mil dieciocho 02/02/2018',
				),
				'nombreArchivo' => array(
					'descripcion' => 'Se valida que las dos (2) primeras letras sean validas con los archivos (CT,AF,US,AC,AP,AU,AH,AN,AM,AT,AD). Se valida que el código de radicación este digitado y no sea mayor a seis (6) dígitos.',
					'valido' => 'AF007613 => Iniciales validas "AF", código de radicación de seis (6) dígitos "007613"',
					'invalido' => 'AO007613 => Iniciales NO validas "AO", código de radicación de seis (6) dígitos "007613"  | AF0076133 => Iniciales validas "AF", código de radicación de siete (7) dígitos "0076133"',
				),
				'esEntero' => array(
					'descripcion' => 'Se valida que el numero de registros sea entero',
					'valido' => '132 => numero entero',
					'invalido' => '132.2 => numero decimal',
				)),
			'af' => array(
				'codigoPrestadorCt' => array(
					'descripcion' => 'Se valida que el código del prestador de servicios de salud digitado en el archivo de control "CT" sea igual al archivo  de transacciones "AF"',
					'valido' => 'Archivo CT: 252900003601 - Archivo AF: 252900003601 => código es igual en ambos archivos',
					'invalido' => 'Archivo CT: 252900003600 - Archivo AF: 252900003601 => código NO es igual en ambos archivos',
				),
				'tipoIdentificacionPrestador' => array(
					'descripcion' => 'Se valida que el tipo de identificación exista entre el rango de valores permitidos para el archivo (NI,CC,CE,CD,PA,PE,RE)',
					'valido' => 'NI => es valido por que corresponde a  Número de identificación tributaria – NIT',
					'invalido' => 'NIT => No es valido ya que no corresponde a el rango de valores permitidos',
				),
				'existePrestadorServicioSalud' => array(
					'descripcion' => 'Se valida que el numero de identificación del prestador exista en la base de datos',
					'valido' => '8906800251 => código valido, existe en la base de datos',
					'invalido' => '8906800252 => código no valido, NO existe en la base de datos',
				),
				'identificacionPrestador' => array(
					'descripcion' => 'Se valida que la identificación del prestador NO sea mayor a 16 dígitos, se valida que la identificación del prestador no contenga guiones "-"',
					'valido' => '8906800251 => código valido diez (10) dígitos completos y no tiene guiones',
					'invalido' => 'CASO N°1: 89068002510000001 => código NO valido diecisiete (17) dígitos completos.  | CASO N°2: 8906800251000-1 => código NO valido contiene "-"',
				),
				'mismaFacturaCaptiacion' => array(
					'descripcion' => 'Si el contrato es por capitación. Se valida que el numero de factura sea igual en todos los registros, tomando como referencia la primera factura del primer registro del archivo AF',
					'valido' => 'Registro 1:"HSRF13473786", Registro 2:"HSRF13473786" es valido en todos los registros es la misma factura',
					'invalido' => 'Registro 1:"HSRF13473786", Registro 2:"HSRF13473796" NO es valido en registros diferentes al primero la factura no es la misma',
				),
				'fechaExpedicion' => array(
					'descripcion' => 'Se valida que la fecha de expedición NO sea mayor a la actual',
					'valido' => 'Fecha ingresada: 03/02/2018  Fecha actual: 04/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) NO ES MAYOR a cuatro de febrero de dos mil dieciocho 04/02/2018',
					'invalido' => 'Fecha ingresada: 03/02/2018  Fecha actual: 02/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) ES MAYOR a dos de febrero de dos mil dieciocho 02/02/2018',
				),
				'fechaInicio' => array(
					'descripcion' => 'Se valida que la fecha de inicio del periodo facturado NO sea mayor a la actual',
					'valido' => 'Fecha ingresada: 03/02/2018  Fecha actual: 04/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) NO ES MAYOR a cuatro de febrero de dos mil dieciocho 04/02/2018',
					'invalido' => 'Fecha ingresada: 03/02/2018  Fecha actual: 02/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) ES MAYOR a dos de febrero de dos mil dieciocho 02/02/2018',
				),
				'FechaInicioMenorQueFechafinal' => array(
					'descripcion' => 'Se valida que la fecha de inicio del periodo facturado NO sea mayor a la fecha final del periodo facturado',
					'valido' => 'Fecha inicio: 03/02/2018  Fecha final: 04/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) NO ES MAYOR a cuatro de febrero de dos mil dieciocho 04/02/2018',
					'invalido' => 'Fecha inicio: 03/02/2018  Fecha final: 02/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) ES MAYOR a dos de febrero de dos mil dieciocho 02/02/2018',
				),
				'fechaFinal' => array(
					'descripcion' => 'Se valida que la fecha final del periodo facturado NO sea mayor a la actual',
					'valido' => 'Fecha ingresada: 03/02/2018  Fecha actual: 04/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) NO ES MAYOR a cuatro de febrero de dos mil dieciocho 04/02/2018',
					'invalido' => 'Fecha ingresada: 03/02/2018  Fecha actual: 02/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) ES MAYOR a dos de febrero de dos mil dieciocho 02/02/2018',
				),
				'fechaFinalMayorQueFechaInicial' => array(
					'descripcion' => 'Se valida que la fecha de inicio del periodo facturado NO sea mayor a la fecha final del periodo facturado',
					'valido' => 'Fecha inicio: 03/02/2018  Fecha final: 04/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) NO ES MAYOR a cuatro de febrero de dos mil dieciocho 04/02/2018',
					'invalido' => 'Fecha inicio: 03/02/2018  Fecha final: 02/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) ES MAYOR a dos de febrero de dos mil dieciocho 02/02/2018',
				),
				'codigoEntidadadministradora' => array(
					'descripcion' => '',
					'valido' => '',
					'invalido' => '',
				),
				'numeroPositivo' => array(
					'descripcion' => 'Se valida que el valor neto a pagar por la entidad contratante sea positivo',
					'valido' => '"1253"  Valido, es un numero positivo',
					'invalido' => '"-21" NO valido, es un numero negativo',
				),
				'cruceFacturas' => array(
					'descripcion' => 'Se valida que al buscar cada factura del archivo "AF" en todos los medas archivos que se hayan radicado la suma de los valores encontrados con esa factura en los demás archivos sea igual a la suma del archivo "AF" (Se realiza la trazabilidad de la factura). Se valida que la fecha de prestación del servicio que esta en los archivos evaluados este entre el rango de inicio y fin de facturación.  OBSERVACION: los valores que se suman son; Valor total del pago compartido (copago) + Valor neto a pagar por la entidad contratante',
					'valido' => 'La factura "HSRF13478068" en el archivo AF es $870132.00, se busca en el archivo AC y su valor es $8000.00 se busca en el archivo AP y su valor es $700000 se busca en el archivo AM $162132 la suma de los archivos (AC+AP+AM = AF) (8000+700000+162132=870132) la trazabilidad de la factura es VALIDA. Fechas: "AC" Fecha de consulta (03/02/2018) "AP" Fecha de procedimiento (04/02/2018) fecha de inicio facturado (03/02/2018) fecha final del periodo facturado (04/02/2018) la fecha de consulta y la fecha del procedimiento esta entre el rango de fecha de inicio y fecha final del periodo facturado.',
					'invalido' => 'CASO N°1: La factura "HSRF13478068" en el archivo AF es $870132.00, se busca en el archivo AC y su valor es $8000.00 se busca en el archivo AP y su valor es $700000 se busca en el archivo AM $160132 la suma de los archivos (AC+AP+AM != AF) (8000+700000+162032=870032) la trazabilidad de la factura NO es VALIDA.  CASO N°2: Fechas: "AC" Fecha de consulta (01/02/2018) "AP" Fecha de procedimiento (04/02/2018) fecha de inicio facturado (03/02/2018) fecha final del periodo facturado (04/02/2018) la fecha de consulta esta por fuera de la fecha inicial y la fecha final del periodo facturado y la fecha del procedimiento esta entre el rango de fecha de inicio y fecha final del periodo facturado, fechas NO validas',
				),
				'sumaValoresFacturas' => array(
					'descripcion' => 'Se valida que la suma total de todos los registros del archivo "AF" sea igual a la suma total de todos los registros de todos los archivos radicados',
					'valido' => 'Suma total de todos los registros "AF" $8221520, suma total de todos los registros "AC" $1250500, suma total de todos los registros "AP" $6520500, suma total de todos los registros "AM" $450520. (AC+AP+AM=AF) ($1250500 + $6520500 + $450520 = $8221520) la suma es valida',
					'invalido' => 'Suma total de todos los registros "AF" $8221520, suma total de todos los registros "AC" $1252500, suma total de todos los registros "AP" $6520500, suma total de todos los registros "AM" $450520. (AC+AP+AM=AF) ($1250500 + $6520500 + $450520 = $8223520) la suma NO es valida',
				)),
			'us' => array(
				'rangosTipoEdad' => array(
					'descripcion' => 'Se valida que la unidad de medida de la edad exista entre el rango de valores permitidos (1 = Años, 2 = Meses, 3 = Días)',
					'valido' => ' 1 es valido',
					'invalido' => '4 No es valido no existe entre el rango de valores permitidos',
				),
				'edad' => array(
					'descripcion' => 'Se valida que la edad corresponda con el tipo de edad',
					'valido' => 'Edad: 28, tipo edad: 1  => 28 años edad valida',
					'invalido' => 'Edad:28, tipo edad: 2 ',
				),
				'tipoIdentificacionUsuario' => array(
					'descripcion' => 'Se valida que el tipo de identificación exista entre el rango de valores permitidos. Que la edad corresponda con el tipo de documento.',
					'valido' => '"CC" es valido, edad: 18, unidad de medida de la edad: 1, (18 años). Valido 18 años corresponde para "CC"',
					'invalido' => 'CASO N°1:"CO" NO valido no existe entre el rango de valores permitidos. CASO N°2: "CC" es valido, edad: 17, unidad de medida de la edad: 1, (17 años). NO valido 17 años no corresponde para "CC"',
				),
				'longitudIdentificacion' => array(
					'descripcion' => 'Se valida que el numero de identificación no exceda el máximo permitido por cada tipo de identificación',
					'valido' => '"CC" Identificación: 1069852882  diez (10) dígitos completos. Valido',
					'invalido' => '"CC" Identificación: 10698528822  once (11) dígitos completos. NO valido',
				),
				'tipoUsuario' => array(
					'descripcion' => 'Se valida que el tipo de usuario exista entre el rango de valores permitidos. Se valida que el tipo de usuario sea igual al tipo de usuario seleccionado en la pantalla de radicacion',
					'valido' => 'Tipo de usuario: 1 es valido, Tipo de usuario interfaz: 1, es valido',
					'invalido' => 'CASO N°1: Tipo de usuario 9: No existe entre el rango de valores permitidos. CASO N°2: Tipo de usuario: 2 es valido, Tipo de usuario interfaz: 1, NO es valido los tipos de usuario no son iguales',
				),
				'rangoGenero' => array(
					'descripcion' => 'Se valida que el genero exista entre el rango de valores permitidos',
					'valido' => '"M" es valido para masculino',
					'invalido' => '"E" no existe entre el rango de valores permitidos',
				),
				'codigoDepartamento' => array(
					'descripcion' => 'Se valida que el código del departamento se encuentre en la base de datos del DIVIPOLA',
					'valido' => '"85" código valido para el departamento de CASANARE',
					'invalido' => '"085" código no valido en la base de datos de DIVIPOLA',
				),
				'codigoMunicipio' => array(
					'descripcion' => 'Se valida que el código del municipio se encuentre en la base de datos del DIVIPOLA',
					'valido' => '"85001" código valido para el municipio de Yopal',
					'invalido' => '"001" código NO valido en la base de datos DIVIPOLA',
				),
				'zona' => array(
					'descripcion' => 'Se valida que exista entre el rango de valores permitidos',
					'valido' => '"U" valido para URBANO',
					'invalido' => '"C" NO valido',
				)),
			'ac' => array(
				'numeroFacturaEnAf' => array(
					'descripcion' => 'Se valida que el numero de factura de la consulta exista en el archivo de transacciones "AF"',
					'valido' => 'AC:"HSRF13478068" AF:"HSRF13478068" la factura se encuentra en el archivo AF. Valido',
					'invalido' => 'AC:"HSRF13478068" AF:"HSRF1347806" la factura NO se encuentra en el archivo AF. No valido',
				),
				'codigoPrestadorCt' => array(
					'descripcion' => 'Se valida que el código del prestador de servicios de salud digitado en el archivo de control "CT" sea igual al archivo  de consultas "AC"',
					'valido' => 'Archivo CT: 252900003601 - Archivo AC: 252900003601 => código es igual en ambos archivos',
					'invalido' => 'Archivo CT: 252900003600 - Archivo AC: 252900003601 => código NO es igual en ambos archivos',
				),
				'rangosTipoIdentificacion' => array(
					'descripcion' => 'Se valida que el tipo de identificación exista entre el rango de valores permitidos',
					'valido' => '"CC" es valido para Cedula de Ciudadanía',
					'invalido' => '"CO" NO valido no existe entre el rango de valores permitidos.',
				),
				'longitudIdentificacion' => array(
					'descripcion' => 'Se valida que el numero de identificación no exceda el máximo permitido por cada tipo de identificación',
					'valido' => '"CC" Identificación: 1069852882  diez (10) dígitos completos. Valido',
					'invalido' => '"CC" Identificación: 10698528822  once (11) dígitos completos. NO valido',
				),
				'fechaConsulta' => array(
					'descripcion' => 'Se valida que la fecha de consulta NO sea mayor a la actual',
					'valido' => 'Fecha ingresada: 03/02/2018  Fecha actual: 04/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) NO ES MAYOR a cuatro de febrero de dos mil dieciocho 04/02/2018',
					'invalido' => 'Fecha ingresada: 03/02/2018  Fecha actual: 02/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) ES MAYOR a dos de febrero de dos mil dieciocho 02/02/2018',
				),
				'numeroAutorizacion' => array(
					'descripcion' => 'Si existe. Se valida que el numero de la autorizacion este registrado en el sistema',
					'valido' => 'Autorizacion: 00001. La autorizacion existe en la base de datos. VALIDO',
					'invalido' => 'Autorizacion: 010010. La autorizacion no existe en la base de datos. NO VALIDO',
				),
				'existeCup' => array(
					'descripcion' => 'Se valida que el código de la consulta exista en la base de datos de CUPS',
					'valido' => '"890201" código valido para CONSULTA DE PRIMERA VEZ POR MEDICINA GENERAL',
					'invalido' => '"890201" código no existe en la base de datos CUPS',
				),
				'cupValidoConsulta' => array(
					'descripcion' => 'Se valida que el código CUP sea valido para consulta, los códigos validos para consulta inician siempre por "890"',
					'valido' => '"890201" código valido para CONSULTA DE PRIMERA VEZ POR MEDICINA GENERAL',
					'invalido' => '"891202" código existe en la base de datos CUPS pero NO es valido para consultas',
				),
				'rangoFinalidad' => array(
					'descripcion' => 'Se valida que el rango de la finalidad de la consulta exista entre el rango de valores permitidos',
					'valido' => '"08" código de la finalidad valido',
					'invalido' => '"8" código de la finalidad NO VALIDO',
				),
				'finalidadUltimos' => array(
					'descripcion' => 'Se valida si la finalidad de la consulta sea "Detección de enfermedad profesional" (09) ó "No aplica" (10). OBSERVACION: Esta validación no genera error, se utiliza para que el validador pueda o no validar la referencia cruzada cuando la finalidad es de 01 a 08',
					'valido' => '"09" Valido',
					'invalido' => 'Si el dato es invalido se ejecuta la validación referencia cruzada. Entiéndase por "dato invalido" las finalidades de "01" a "08"',
				),
				'referenciaCruzadaFinalidad' => array(
					'descripcion' => 'Si la finalidad esta en rango "01" a "08": Se valida que el código CIE10 siempre sea un código "Z". Si la finalidad es "05"  la edad debe estar en el rango de 10 a 29 años.',
					'valido' => 'Código CIE10: "Z000" el código es "Z" VALIDO. Finalidad "05" Usuario encontrado en el archivo "US", edad:28, tipo de edad:1, 28 años; Cruce finalidad con edad usuario VALIDA',
					'invalido' => 'CASO N°1: código CIE10: "A000" el código NO es "Z" NO valido; no se realiza cruce finalidad con edad de usuario por que no existe código "Z". CASO N°2: código CIE10: "Z000" el código es "Z" VALIDO; Finalidad "05" Usuario encontrado en el archivo "US", edad:30, tipo de edad:1, 30 años; Cruce finalidad con edad usuario NO VALIDA',
				),
				'causaExterna' => array(
					'descripcion' => 'Se valida que la causa externa exista entre el rango de valores permitidos',
					'valido' => '"01" código valido',
					'invalido' => '"16" código no existe entre el rango de valores permitidos. NO valido',
				),
				'diagnosticoPrincipal' => array(
					'descripcion' => 'Se valida que siempre exista en la base de datos CIE10. ',
					'valido' => '"Z000" el código es valido.',
					'invalido' => '"Z985" el código no existe. NO valido',
				),
				'referenciaCruzadaCie10' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico principal existe. Se valida que la edad del usuario exista entre el rango de edad mínima y edad máxima permitido por el código CIE10. Se valida que el genero del usuario exista entre el rango de genero permitido por el código CIE10. OBSERVACION: Obligatorio que el usuario exista en el archivo usuario',
					'valido' => 'CIE10: Z000, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 0 años a 999 años. CRUCE VALIDO',
					'invalido' => 'CIE10: Z003, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 10 años a 17 años. La edad del usuario no es valida con la edad del CIE10. CRUCE NO VALIDO',
				),
				'referenciaCruzadaCie10Cups' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico principal existe. Se valida que el código CIE10 exista entre el rango del servicio CUPS. OBSERVACION: El código CIE10 que se relaciona es el código CIE10 de 3 dígitos que se encuentra en la base de datos',
					'valido' => 'CASO N°1: CUP: 890229 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ORTODONCIA) CIE10: Z001; El código CIE10 esta relacionado con el código CUP. CRUCE VALIDO.   ||  CASO N°2: CUP: 890226 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ANESTESIOLOGIA) CIE10: Z001; El código CUP no tiene restricción de CIE10 relacionados por lo es CRUCE VALIDO.',
					'invalido' => 'CUP: 890229 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ORTODONCIA) CIE10: H108; El código CIE10 no esta relacionado con el código CUP. CRUCE NO VALIDO',
				),
				'diagnosticoSecundario1' => array(
					'descripcion' => 'Si esta digitado. Se valida que exista en la base de datos CIE10. OBSERVACION: Si no esta digitado no genera error y no se ejecutan las validaciones de "Cruces"',
					'valido' => '"Z000" el código es valido.',
					'invalido' => '"Z985" el código no existe. NO valido',
				),
				'referenciaCruzadaCie101' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico secundario existe. Se valida que la edad del usuario exista entre el rango de edad mínima y edad máxima permitido por el código CIE10. Se valida que el genero del usuario exista entre el rango de genero permitido por el código CIE10. OBSERVACION: Obligatorio que el usuario exista en el archivo usuario',
					'valido' => 'CIE10: Z000, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 0 años a 999 años. CRUCE VALIDO',
					'invalido' => 'CIE10: Z003, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 10 años a 17 años. La edad del usuario no es valida con la edad del CIE10. CRUCE NO VALIDO',
				),
				'referenciaCruzadaCie10Cups1' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico secundario existe. Se valida que el código CIE10 exista entre el rango del servicio CUPS. OBSERVACION: El código CIE10 que se relaciona es el código CIE10 de 3 dígitos que se encuentra en la base de datos',
					'valido' => 'CASO N°1: CUP: 890229 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ORTODONCIA) CIE10: Z001; El código CIE10 esta relacionado con el código CUP. CRUCE VALIDO.   ||  CASO N°2: CUP: 890226 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ANESTESIOLOGIA) CIE10: Z001; El código CUP no tiene restricción de CIE10 relacionados por lo es CRUCE VALIDO.',
					'invalido' => 'CUP: 890229 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ORTODONCIA) CIE10: H108; El código CIE10 no esta relacionado con el código CUP. CRUCE NO VALIDO',
				),
				'diagnosticoSecundario2' => array(
					'descripcion' => 'Si esta digitado. Se valida que exista en la base de datos CIE10. OBSERVACION: Si no esta digitado no genera error y no se ejecutan las validaciones de "Cruces"',
					'valido' => '"Z000" el código es valido.',
					'invalido' => '"Z985" el código no existe. NO valido',
				),
				'referenciaCruzadaCie102' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico secundario existe. Se valida que la edad del usuario exista entre el rango de edad mínima y edad máxima permitido por el código CIE10. Se valida que el genero del usuario exista entre el rango de genero permitido por el código CIE10. OBSERVACION: Obligatorio que el usuario exista en el archivo usuario',
					'valido' => 'CIE10: Z000, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 0 años a 999 años. CRUCE VALIDO',
					'invalido' => 'CIE10: Z003, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 10 años a 17 años. La edad del usuario no es valida con la edad del CIE10. CRUCE NO VALIDO',
				),
				'referenciaCruzadaCie10Cups2' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico secundario existe. Se valida que el código CIE10 exista entre el rango del servicio CUPS. OBSERVACION: El código CIE10 que se relaciona es el código CIE10 de 3 dígitos que se encuentra en la base de datos',
					'valido' => 'CASO N°1: CUP: 890229 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ORTODONCIA) CIE10: Z001; El código CIE10 esta relacionado con el código CUP. CRUCE VALIDO.   ||  CASO N°2: CUP: 890226 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ANESTESIOLOGIA) CIE10: Z001; El código CUP no tiene restricción de CIE10 relacionados por lo es CRUCE VALIDO.',
					'invalido' => 'CUP: 890229 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ORTODONCIA) CIE10: H108; El código CIE10 no esta relacionado con el código CUP. CRUCE NO VALIDO',
				),
				'diagnosticoSecundario3' => array(
					'descripcion' => 'Si esta digitado. Se valida que exista en la base de datos CIE10. OBSERVACION: Si no esta digitado no genera error y no se ejecutan las validaciones de "Cruces"',
					'valido' => '"Z000" el código es valido.',
					'invalido' => '"Z985" el código no existe. NO valido',
				),
				'referenciaCruzadaCie103' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico secundario existe. Se valida que la edad del usuario exista entre el rango de edad mínima y edad máxima permitido por el código CIE10. Se valida que el genero del usuario exista entre el rango de genero permitido por el código CIE10. OBSERVACION: Obligatorio que el usuario exista en el archivo usuario',
					'valido' => 'CIE10: Z000, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 0 años a 999 años. CRUCE VALIDO',
					'invalido' => 'CIE10: Z003, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 10 años a 17 años. La edad del usuario no es valida con la edad del CIE10. CRUCE NO VALIDO',
				),
				'referenciaCruzadaCie10Cups3' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico secundario existe. Se valida que el código CIE10 exista entre el rango del servicio CUPS. OBSERVACION: El código CIE10 que se relaciona es el código CIE10 de 3 dígitos que se encuentra en la base de datos',
					'valido' => 'CASO N°1: CUP: 890229 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ORTODONCIA) CIE10: Z001; El código CIE10 esta relacionado con el código CUP. CRUCE VALIDO.   ||  CASO N°2: CUP: 890226 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ANESTESIOLOGIA) CIE10: Z001; El código CUP no tiene restricción de CIE10 relacionados por lo es CRUCE VALIDO.',
					'invalido' => 'CUP: 890229 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ORTODONCIA) CIE10: H108; El código CIE10 no esta relacionado con el código CUP. CRUCE NO VALIDO',
				),
				'tipoDiagnosticoPrincipal' => array(
					'descripcion' => 'Se valida que el tipo de diagnostico principal exista entre el rango de valores permitidos',
					'valido' => '"1" Corresponde a Impresión diagnostica. código Valido',
					'invalido' => '"4" No existe entre el rango de valores permitidos. código no valido',
				),
				'valorConsultaEntero' => array(
					'descripcion' => 'Se valida que el valor de la consulta sea un numero entero',
					'valido' => '"10000" (diez mil). Valido',
					'invalido' => '"10000,2" valor decimal. NO Valido',
				),
				'valorConsultaCero' => array(
					'descripcion' => 'Se valida que el valor de la consulta nunca puede ser 0.',
					'valido' => '"10000" (diez mil). Valido',
					'invalido' => '"0" (cero). NO Valido',
				),
				'valorConsultaSiempreCeroCapitacion' => array(
					'descripcion' => 'Contrato por capitación. Se valida que el valor de la consulta debe ser siempre 0 (cero)',
					'valido' => '"0" (cero). Valido',
					'invalido' => '"10000" (diez mil). NO Valido',
				),
				'valorCuotaModeradoraEntero' => array(
					'descripcion' => 'Se valida que el valor de la cuota moderadora sea entero',
					'valido' => '"0" (cero). Valido',
					'invalido' => '"10,2" Decimal. NO Valido ',
				),
				'valorNetoEntero' => array(
					'descripcion' => 'Se valida que el valor neto a pagar sea entero',
					'valido' => '"0" (cero). Valido',
					'invalido' => '"10,2" Decimal. NO Valido',
				),
				'valorNetoCero' => array(
					'descripcion' => 'Se valida que el valor neto a pagar nunca puede ser 0.',
					'valido' => '"10000" (diez mil). Valido',
					'invalido' => '"0" (cero). NO Valido',
				))
			,
			'ap' => array(
				'numeroFacturaEnAf' => array(
					'descripcion' => 'Se valida que el numero de factura de la consulta exista en el archivo de transacciones "AF"',
					'valido' => 'AP:"HSRF13478068" AF:"HSRF13478068" la factura se encuentra en el archivo AF. Valido',
					'invalido' => 'AP:"HSRF13478068" AF:"HSRF1347806" la factura NO se encuentra en el archivo AF. No valido',
				),
				'codigoPrestadorCt' => array(
					'descripcion' => 'Se valida que el código del prestador de servicios de salud digitado en el archivo de control "CT" sea igual al archivo  de consultas "AP"',
					'valido' => 'Archivo CT: 252900003601 - Archivo AP: 252900003601 => código es igual en ambos archivos',
					'invalido' => 'Archivo CT: 252900003600 - Archivo AP: 252900003601 => código NO es igual en ambos archivos',
				),
				'rangosTipoIdentificacion' => array(
					'descripcion' => 'Se valida que el tipo de identificación exista entre el rango de valores permitidos',
					'valido' => '"CC" es valido para Cedula de Ciudadanía',
					'invalido' => '"CO" NO valido no existe entre el rango de valores permitidos.',
				),
				'longitudIdentificacion' => array(
					'descripcion' => 'Se valida que el numero de identificación no exceda el máximo permitido por cada tipo de identificación',
					'valido' => '"CC" Identificación: 1069852882  diez (10) dígitos completos. Valido',
					'invalido' => '"CC" Identificación: 10698528822  once (11) dígitos completos. NO valido',
				),
				'fechaProcedimiento' => array(
					'descripcion' => 'Se valida que la fecha de procedimiento NO sea mayor a la actual',
					'valido' => 'Fecha ingresada: 03/02/2018  Fecha actual: 04/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) NO ES MAYOR a cuatro de febrero de dos mil dieciocho 04/02/2018',
					'invalido' => 'Fecha ingresada: 03/02/2018  Fecha actual: 02/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) ES MAYOR a dos de febrero de dos mil dieciocho 02/02/2018',
				),
				'numeroAutorizacion' => array(
					'descripcion' => 'Si existe. Se valida que el numero de la autorizacion este registrado en el sistema',
					'valido' => 'Autorizacion: 00001. La autorizacion existe en la base de datos. VALIDO',
					'invalido' => 'Autorizacion: 010010. La autorizacion no existe en la base de datos. NO VALIDO',
				),
				'existeCup' => array(
					'descripcion' => 'Se valida que el código de la consulta exista en la base de datos de CUPS',
					'valido' => '"890201" código valido para CONSULTA DE PRIMERA VEZ POR MEDICINA GENERAL',
					'invalido' => '"890201" código no existe en la base de datos CUPS',
				),
				'realizacionProcedimiento' => array(
					'descripcion' => 'Se valida que el ámbito de realización del procedimiento exista entre el rango de valores permitidos',
					'valido' => '"1" el código corresponde a "Ambulatorio". Valido',
					'invalido' => '"4" el código no existe entre el rango de valores permitidos',
				),
				'finalidadProcedimiento' => array(
					'descripcion' => 'Se valida que la finalidad del procedimiento exista entre el rango de valores permitidos',
					'valido' => '"1" el código corresponde a "Diagnostico". Valido',
					'invalido' => '"6" el código no existe entre el rango de valores permitidos',
				),
				'personalQueAtiende' => array(
					'descripcion' => 'Se valida que el personal que atiende exista entre el rango de valores permitidos. OBSERVACION: Si esta vació o no digitado es VALIDO',
					'valido' => '"1" corresponde a "Médico (a) especialista". VALIDO',
					'invalido' => '"6" el código no existe entre el rango de valores permitidos',
				),
				'diagnosticoQuirurgico' => array(
					'descripcion' => 'Se valida si el diagnostico principal debe ser obligatorio cuando es un procedimiento quirúrgico. Es un procedimiento quirurgico cuando el dato de "Forma de realización del acto quirúrgico" tiene algun dato registrado. ESTA VALIDACION ESTA RELACIONADA CON LA VALIDACION SIGUIENTE DE DIAGNOSTICO PRINCIPAL',
					'valido' => '"Z000" el codigo es valido por que el procedimiento es quirurgico. "" el codigo vacio es valido por que el procedimiento NO ES Quirurgico',
					'invalido' => '"" el codigo no esta diligenciado y el procedimiento es Quirurgico',
				),
				'diagnosticoPrincipal' => array(
					'descripcion' => 'Se valida que siempre exista en la base de datos CIE10. ',
					'valido' => '"Z000" el código es valido.',
					'invalido' => '"Z985" el código no existe. NO valido',
				),
				'referenciaCruzadaCie10' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico principal existe. Se valida que la edad del usuario exista entre el rango de edad mínima y edad máxima permitido por el código CIE10. Se valida que el genero del usuario exista entre el rango de genero permitido por el código CIE10. OBSERVACION: Obligatorio que el usuario exista en el archivo usuario',
					'valido' => 'CIE10: Z000, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 0 años a 999 años. CRUCE VALIDO',
					'invalido' => 'CIE10: Z003, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 10 años a 17 años. La edad del usuario no es valida con la edad del CIE10. CRUCE NO VALIDO',
				),
				'referenciaCruzadaCie10Cups' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico principal existe. Se valida que el código CIE10 exista entre el rango del servicio CUPS. OBSERVACION: El código CIE10 que se relaciona es el código CIE10 de 3 dígitos que se encuentra en la base de datos',
					'valido' => 'CASO N°1: CUP: 890229 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ORTODONCIA) CIE10: Z001; El código CIE10 esta relacionado con el código CUP. CRUCE VALIDO.   ||  CASO N°2: CUP: 890226 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ANESTESIOLOGIA) CIE10: Z001; El código CUP no tiene restricción de CIE10 relacionados por lo es CRUCE VALIDO.',
					'invalido' => 'CUP: 890229 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ORTODONCIA) CIE10: H108; El código CIE10 no esta relacionado con el código CUP. CRUCE NO VALIDO',
				),
				'diagnosticoSecundario1' => array(
					'descripcion' => 'Si esta digitado. Se valida que exista en la base de datos CIE10. OBSERVACION: Si no esta digitado no genera error y no se ejecutan las validaciones de "Cruces"',
					'valido' => '"Z000" el código es valido.',
					'invalido' => '"Z985" el código no existe. NO valido',
				),
				'referenciaCruzadaCie101' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico relacionado existe. Se valida que la edad del usuario exista entre el rango de edad mínima y edad máxima permitido por el código CIE10. Se valida que el genero del usuario exista entre el rango de genero permitido por el código CIE10. OBSERVACION: Obligatorio que el usuario exista en el archivo usuario',
					'valido' => 'CIE10: Z000, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 0 años a 999 años. CRUCE VALIDO',
					'invalido' => 'CIE10: Z003, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 10 años a 17 años. La edad del usuario no es valida con la edad del CIE10. CRUCE NO VALIDO',
				),
				'referenciaCruzadaCie10Cups1' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico relacionado existe. Se valida que el código CIE10 exista entre el rango del servicio CUPS. OBSERVACION: El código CIE10 que se relaciona es el código CIE10 de 3 dígitos que se encuentra en la base de datos',
					'valido' => 'CASO N°1: CUP: 890229 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ORTODONCIA) CIE10: Z001; El código CIE10 esta relacionado con el código CUP. CRUCE VALIDO.   ||  CASO N°2: CUP: 890226 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ANESTESIOLOGIA) CIE10: Z001; El código CUP no tiene restricción de CIE10 relacionados por lo es CRUCE VALIDO.',
					'invalido' => 'CUP: 890229 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ORTODONCIA) CIE10: H108; El código CIE10 no esta relacionado con el código CUP. CRUCE NO VALIDO',
				),
				'diagnosticoSecundario2' => array(
					'descripcion' => 'Si esta digitado. Se valida que exista en la base de datos CIE10. OBSERVACION: Si no esta digitado no genera error y no se ejecutan las validaciones de "Cruces"',
					'valido' => '"Z000" el código es valido.',
					'invalido' => '"Z985" el código no existe. NO valido',
				),
				'referenciaCruzadaCie102' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico complicación existe. Se valida que la edad del usuario exista entre el rango de edad mínima y edad máxima permitido por el código CIE10. Se valida que el genero del usuario exista entre el rango de genero permitido por el código CIE10. OBSERVACION: Obligatorio que el usuario exista en el archivo usuario',
					'valido' => 'CIE10: Z000, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 0 años a 999 años. CRUCE VALIDO',
					'invalido' => 'CIE10: Z003, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 10 años a 17 años. La edad del usuario no es valida con la edad del CIE10. CRUCE NO VALIDO',
				),
				'referenciaCruzadaCie10Cups2' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico complicación existe. Se valida que el código CIE10 exista entre el rango del servicio CUPS. OBSERVACION: El código CIE10 que se relaciona es el código CIE10 de 3 dígitos que se encuentra en la base de datos',
					'valido' => 'CASO N°1: CUP: 890229 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ORTODONCIA) CIE10: Z001; El código CIE10 esta relacionado con el código CUP. CRUCE VALIDO.   ||  CASO N°2: CUP: 890226 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ANESTESIOLOGIA) CIE10: Z001; El código CUP no tiene restricción de CIE10 relacionados por lo es CRUCE VALIDO.',
					'invalido' => 'CUP: 890229 (CONSULTA DE PRIMERA VEZ POR ESPECIALISTA EN ORTODONCIA) CIE10: H108; El código CIE10 no esta relacionado con el código CUP. CRUCE NO VALIDO',
				),
				'formaRealizacion' => array(
					'descripcion' => 'Se valida que la forma de realización del acto quirúrgico exista entre el rango de valores permitidos. OBSERVACION: Si no esta digitado no genera error',
					'valido' => '"1" corresponde a Único o unilateral. Valido',
					'invalido' => '"6" el código no existe entre el rango de valores permitidos. NO Valido',
				),
				'valorProcedimientoEntero' => array(
					'descripcion' => 'Se valida que el valor del procedimiento sea entero',
					'valido' => '"0" (cero). Valido',
					'invalido' => '"10,2" Decimal. NO Valido ',
				),
				'valorConsultaCero' => array(
					'descripcion' => 'Se valida que el valor del procedimiento no sea 0 (cero)',
					'valido' => '"10000" (diez mil). Valido',
					'invalido' => '"0" (cero). NO Valido',
				),
				'valorConsultaSiempreCero' => array(
					'descripcion' => 'Se valida que el valor del procedimiento no sea 0 (cero)',
					'valido' => '"10000" (diez mil). Valido',
					'invalido' => '"0" (cero). NO Valido',
				))
			,
			'au' => array(
				'numeroFacturaEnAf' => array(
					'descripcion' => 'Se valida que el numero de factura de la consulta exista en el archivo de transacciones "AF"',
					'valido' => 'AU:"HSRF13478068" AF:"HSRF13478068" la factura se encuentra en el archivo AF. Valido',
					'invalido' => 'AU:"HSRF13478068" AF:"HSRF1347806" la factura NO se encuentra en el archivo AF. No valido',
				),
				'codigoPrestadorCt' => array(
					'descripcion' => 'Se valida que el código del prestador de servicios de salud digitado en el archivo de control "CT" sea igual al archivo  de consultas "AU"',
					'valido' => 'Archivo CT: 252900003601 - Archivo AU: 252900003601 => código es igual en ambos archivos',
					'invalido' => 'Archivo CT: 252900003600 - Archivo AU: 252900003601 => código NO es igual en ambos archivos',
				),
				'rangosTipoIdentificacion' => array(
					'descripcion' => 'Se valida que el tipo de identificación exista entre el rango de valores permitidos',
					'valido' => '"CC" es valido para Cedula de Ciudadanía',
					'invalido' => '"CO" NO valido no existe entre el rango de valores permitidos.',
				),
				'longitudIdentificacion' => array(
					'descripcion' => 'Se valida que el numero de identificación no exceda el máximo permitido por cada tipo de identificación',
					'valido' => '"CC" Identificación: 1069852882  diez (10) dígitos completos. Valido',
					'invalido' => '"CC" Identificación: 10698528822  once (11) dígitos completos. NO valido',
				),
				'fechaingreso' => array(
					'descripcion' => 'Se valida que la fecha de ingreso NO sea mayor a la actual',
					'valido' => 'Fecha ingresada: 03/02/2018  Fecha actual: 04/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) NO ES MAYOR a cuatro de febrero de dos mil dieciocho 04/02/2018',
					'invalido' => 'Fecha ingresada: 03/02/2018  Fecha actual: 02/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) ES MAYOR a dos de febrero de dos mil dieciocho 02/02/2018',
				),
				'fechaIngresoMenorFechaEgreso' => array(
					'descripcion' => 'Se valida que la fecha y hora de ingreso no sea mayor a la fecha y hora de egreso',
					'valido' => 'Fecha ingreso: 03/02/2018 13:25 Fecha egreso: 04/02/2018 09:00 | tres de febrero de dos mil dieciocho una y veinticinco de la tarde (03/02/2018 13:25)  NO ES MAYOR a cuatro de febrero de dos mil dieciocho nueve de la mañana 04/02/2018 09:00',
					'invalido' => 'Fecha ingreso: 03/02/2018 13:25 Fecha egreso: 02/02/2018 09:0 | tres de febrero de dos mil dieciocho (03/02/2018) ES MAYOR a dos de febrero de dos mil dieciocho 02/02/2018',
				),
				'numeroAutorizacion' => array(
					'descripcion' => 'Si existe. Se valida que el numero de la autorizacion este registrado en el sistema',
					'valido' => 'Autorizacion: 00001. La autorizacion existe en la base de datos. VALIDO',
					'invalido' => 'Autorizacion: 010010. La autorizacion no existe en la base de datos. NO VALIDO',
				),
				'causaExterna' => array(
					'descripcion' => 'Se valida que la causa externa exista entre el rango de valores permitidos',
					'valido' => '"01" código Valido',
					'invalido' => '"16" código NO Valido',
				),
				'diagnosticoPrincipal' => array(
					'descripcion' => 'Se valida que siempre exista en la base de datos CIE10. ',
					'valido' => '"Z000" el código es valido.',
					'invalido' => '"Z985" el código no existe. NO valido',
				),
				'referenciaCruzadaCie10' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico de salida existe. Se valida que la edad del usuario exista entre el rango de edad mínima y edad máxima permitido por el código CIE10. Se valida que el genero del usuario exista entre el rango de genero permitido por el código CIE10. OBSERVACION: Obligatorio que el usuario exista en el archivo usuario',
					'valido' => 'CIE10: Z000, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 0 años a 999 años. CRUCE VALIDO',
					'invalido' => 'CIE10: Z003, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 10 años a 17 años. La edad del usuario no es valida con la edad del CIE10. CRUCE NO VALIDO',
				),
				'diagnosticoSecundario1' => array(
					'descripcion' => 'Si esta digitado. Se valida que exista en la base de datos CIE10. OBSERVACION: Si no esta digitado no genera error y no se ejecutan las validaciones de "Cruces"',
					'valido' => '"Z000" el código es valido.',
					'invalido' => '"Z985" el código no existe. NO valido',
				),
				'referenciaCruzadaCie101' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico secundario existe. Se valida que la edad del usuario exista entre el rango de edad mínima y edad máxima permitido por el código CIE10. Se valida que el genero del usuario exista entre el rango de genero permitido por el código CIE10. OBSERVACION: Obligatorio que el usuario exista en el archivo usuario',
					'valido' => 'CIE10: Z000, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 0 años a 999 años. CRUCE VALIDO',
					'invalido' => 'CIE10: Z003, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 10 años a 17 años. La edad del usuario no es valida con la edad del CIE10. CRUCE NO VALIDO',
				),
				'diagnosticoSecundario2' => array(
					'descripcion' => 'Si esta digitado. Se valida que exista en la base de datos CIE10. OBSERVACION: Si no esta digitado no genera error y no se ejecutan las validaciones de "Cruces"',
					'valido' => '"Z000" el código es valido.',
					'invalido' => '"Z985" el código no existe. NO valido',
				),
				'referenciaCruzadaCie102' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico secundario existe. Se valida que la edad del usuario exista entre el rango de edad mínima y edad máxima permitido por el código CIE10. Se valida que el genero del usuario exista entre el rango de genero permitido por el código CIE10. OBSERVACION: Obligatorio que el usuario exista en el archivo usuario',
					'valido' => 'CIE10: Z000, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 0 años a 999 años. CRUCE VALIDO',
					'invalido' => 'CIE10: Z003, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 10 años a 17 años. La edad del usuario no es valida con la edad del CIE10. CRUCE NO VALIDO',
				),
				'diagnosticoSecundario3' => array(
					'descripcion' => 'Si esta digitado. Se valida que exista en la base de datos CIE10. OBSERVACION: Si no esta digitado no genera error y no se ejecutan las validaciones de "Cruces"',
					'valido' => '"Z000" el código es valido.',
					'invalido' => '"Z985" el código no existe. NO valido',
				),
				'referenciaCruzadaCie103' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico secundario existe. Se valida que la edad del usuario exista entre el rango de edad mínima y edad máxima permitido por el código CIE10. Se valida que el genero del usuario exista entre el rango de genero permitido por el código CIE10. OBSERVACION: Obligatorio que el usuario exista en el archivo usuario',
					'valido' => 'CIE10: Z000, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 0 años a 999 años. CRUCE VALIDO',
					'invalido' => 'CIE10: Z003, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 10 años a 17 años. La edad del usuario no es valida con la edad del CIE10. CRUCE NO VALIDO',
				),
				'destinoUsuario' => array(
					'descripcion' => 'Se valida que el destino del usuario exista entre el rango de valores permitidos',
					'valido' => '"1" codgio Valido',
					'invalido' => '"4" código NO Valido',
				),
				'estadoSalida' => array(
					'descripcion' => 'Se valida que el estado a la salida exista entre el rango de valores permitidos',
					'valido' => '"1" código Valido',
					'invalido' => '"3" código NO Valido',
				),
				'causaBasicaMuerte' => array(
					'descripcion' => 'Se valida que la causa básica de la muerte exista si el estado de salida sea "2"',
					'valido' => 'Estado de salida: 2, Causa básica de la muerte: I469. código Valido',
					'invalido' => 'Estado de salida: 2, Causa básica de la muerte:  código NO Valido',
				))
			,
			'ah' => array(
				'numeroFacturaEnAf' => array(
					'descripcion' => 'Se valida que el numero de factura de la consulta exista en el archivo de transacciones "AF"',
					'valido' => 'AH:"HSRF13478068" AF:"HSRF13478068" la factura se encuentra en el archivo AF. Valido',
					'invalido' => 'AH:"HSRF13478068" AF:"HSRF1347806" la factura NO se encuentra en el archivo AF. No valido',
				),
				'codigoPrestadorCt' => array(
					'descripcion' => 'Se valida que el código del prestador de servicios de salud digitado en el archivo de control "CT" sea igual al archivo  de consultas "AH"',
					'valido' => 'Archivo CT: 252900003601 - Archivo AH: 252900003601 => código es igual en ambos archivos',
					'invalido' => 'Archivo CT: 252900003600 - Archivo AH: 252900003601 => código NO es igual en ambos archivos',
				),
				'rangosTipoIdentificacion' => array(
					'descripcion' => 'Se valida que el tipo de identificación exista entre el rango de valores permitidos',
					'valido' => '"CC" es valido para Cedula de Ciudadanía',
					'invalido' => '"CO" NO valido no existe entre el rango de valores permitidos.',
				),
				'longitudIdentificacion' => array(
					'descripcion' => 'Se valida que el numero de identificación no exceda el máximo permitido por cada tipo de identificación',
					'valido' => '"CC" Identificación: 1069852882  diez (10) dígitos completos. Valido',
					'invalido' => '"CC" Identificación: 10698528822  once (11) dígitos completos. NO valido',
				),
				'viaIngreso' => array(
					'descripcion' => 'Se valida que la vía de ingreso existe entre el rango de valores permitidos. Si la vía de ingreso es "1", se valida que el usuario se encuentre en el archivo AU, si no se aparece en el archivo AU el usuario debe existir en el archivo AC. Si la vía de ingreso es "2", se valida que el usuario exista en el archivo AC.',
					'valido' => 'Vía de ingreso: 1, Usuario: CC. 1069757882. La vía de ingreso es valida y el usuario existe en el archivo AU. VALIDO',
					'invalido' => 'Vía de ingreso:1 Usuario: CC. 109757882. La vía de ingreso es valida y el usuario NO existe en archivo AU y tampoco existe en el archivo AC. NO VALIDO',
				),
				'fechaingreso' => array(
					'descripcion' => 'Se valida que la fecha de ingreso NO sea mayor a la actual',
					'valido' => 'Fecha ingresada: 03/02/2018  Fecha actual: 04/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) NO ES MAYOR a cuatro de febrero de dos mil dieciocho 04/02/2018',
					'invalido' => 'Fecha ingresada: 03/02/2018  Fecha actual: 02/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) ES MAYOR a dos de febrero de dos mil dieciocho 02/02/2018',
				),
				'fechaIngresoMenorFechaEgreso' => array(
					'descripcion' => 'Se valida que la fecha y hora de ingreso no sea mayor a la fecha y hora de egreso',
					'valido' => 'Fecha ingreso: 03/02/2018 13:25 Fecha egreso: 04/02/2018 09:00 | tres de febrero de dos mil dieciocho una y veinticinco de la tarde (03/02/2018 13:25)  NO ES MAYOR a cuatro de febrero de dos mil dieciocho nueve de la mañana 04/02/2018 09:00',
					'invalido' => 'Fecha ingreso: 03/02/2018 13:25 Fecha egreso: 02/02/2018 09:0 | tres de febrero de dos mil dieciocho (03/02/2018) ES MAYOR a dos de febrero de dos mil dieciocho 02/02/2018',
				),
				'numeroAutorizacion' => array(
					'descripcion' => 'Si existe. Se valida que el numero de la autorizacion este registrado en el sistema',
					'valido' => 'Autorizacion: 00001. La autorizacion existe en la base de datos. VALIDO',
					'invalido' => 'Autorizacion: 010010. La autorizacion no existe en la base de datos. NO VALIDO',
				),
				'causaExterna' => array(
					'descripcion' => 'Se valida que la causa externa exista entre el rango de valores permitidos',
					'valido' => '"01" código Valido',
					'invalido' => '"16" código NO Valido',
				),
				'diangosticoIngreso' => array(
					'descripcion' => 'Se valida que el código principal de Ingreso no sea un código "Z"',
					'valido' => '"I253" el código es valido.',
					'invalido' => '"Z985" el código existe pero es un código "Z". NO Valido',
				),
				'diagnosticoPrincipalIngreso' => array(
					'descripcion' => 'Se valida que siempre exista en la base de datos CIE10. ',
					'valido' => '"Z000" el código es valido.',
					'invalido' => '"Z985" el código no existe. NO valido',
				),
				'referenciaCruzadaCie10Ingreso' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico de salida existe. Se valida que la edad del usuario exista entre el rango de edad mínima y edad máxima permitido por el código CIE10. Se valida que el genero del usuario exista entre el rango de genero permitido por el código CIE10. OBSERVACION: Obligatorio que el usuario exista en el archivo usuario',
					'valido' => 'CIE10: Z000, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 0 años a 999 años. CRUCE VALIDO',
					'invalido' => 'CIE10: Z003, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 10 años a 17 años. La edad del usuario no es valida con la edad del CIE10. CRUCE NO VALIDO',
				),
				'diagnosticoPrincipal' => array(
					'descripcion' => 'Se valida que siempre exista en la base de datos CIE10. ',
					'valido' => '"Z000" el código es valido.',
					'invalido' => '"Z985" el código no existe. NO valido',
				),
				'referenciaCruzadaCie10' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico de salida existe. Se valida que la edad del usuario exista entre el rango de edad mínima y edad máxima permitido por el código CIE10. Se valida que el genero del usuario exista entre el rango de genero permitido por el código CIE10. OBSERVACION: Obligatorio que el usuario exista en el archivo usuario',
					'valido' => 'CIE10: Z000, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 0 años a 999 años. CRUCE VALIDO',
					'invalido' => 'CIE10: Z003, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 10 años a 17 años. La edad del usuario no es valida con la edad del CIE10. CRUCE NO VALIDO',
				),
				'diagnosticoSecundario1' => array(
					'descripcion' => 'Si esta digitado. Se valida que exista en la base de datos CIE10. OBSERVACION: Si no esta digitado no genera error y no se ejecutan las validaciones de "Cruces"',
					'valido' => '"Z000" el código es valido.',
					'invalido' => '"Z985" el código no existe. NO valido',
				),
				'referenciaCruzadaCie101' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico secundario existe. Se valida que la edad del usuario exista entre el rango de edad mínima y edad máxima permitido por el código CIE10. Se valida que el genero del usuario exista entre el rango de genero permitido por el código CIE10. OBSERVACION: Obligatorio que el usuario exista en el archivo usuario',
					'valido' => 'CIE10: Z000, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 0 años a 999 años. CRUCE VALIDO',
					'invalido' => 'CIE10: Z003, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 10 años a 17 años. La edad del usuario no es valida con la edad del CIE10. CRUCE NO VALIDO',
				),
				'diagnosticoSecundario2' => array(
					'descripcion' => 'Si esta digitado. Se valida que exista en la base de datos CIE10. OBSERVACION: Si no esta digitado no genera error y no se ejecutan las validaciones de "Cruces"',
					'valido' => '"Z000" el código es valido.',
					'invalido' => '"Z985" el código no existe. NO valido',
				),
				'referenciaCruzadaCie102' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico secundario existe. Se valida que la edad del usuario exista entre el rango de edad mínima y edad máxima permitido por el código CIE10. Se valida que el genero del usuario exista entre el rango de genero permitido por el código CIE10. OBSERVACION: Obligatorio que el usuario exista en el archivo usuario',
					'valido' => 'CIE10: Z000, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 0 años a 999 años. CRUCE VALIDO',
					'invalido' => 'CIE10: Z003, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 10 años a 17 años. La edad del usuario no es valida con la edad del CIE10. CRUCE NO VALIDO',
				),
				'diagnosticoSecundario3' => array(
					'descripcion' => 'Si esta digitado. Se valida que exista en la base de datos CIE10. OBSERVACION: Si no esta digitado no genera error y no se ejecutan las validaciones de "Cruces"',
					'valido' => '"Z000" el código es valido.',
					'invalido' => '"Z985" el código no existe. NO valido',
				),
				'referenciaCruzadaCie103' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico secundario existe. Se valida que la edad del usuario exista entre el rango de edad mínima y edad máxima permitido por el código CIE10. Se valida que el genero del usuario exista entre el rango de genero permitido por el código CIE10. OBSERVACION: Obligatorio que el usuario exista en el archivo usuario',
					'valido' => 'CIE10: Z000, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 0 años a 999 años. CRUCE VALIDO',
					'invalido' => 'CIE10: Z003, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 10 años a 17 años. La edad del usuario no es valida con la edad del CIE10. CRUCE NO VALIDO',
				),
				'diagnosticoSecundario4' => array(
					'descripcion' => 'Si esta digitado. Se valida que exista en la base de datos CIE10. OBSERVACION: Si no esta digitado no genera error y no se ejecutan las validaciones de "Cruces"',
					'valido' => '"Z000" el código es valido.',
					'invalido' => '"Z985" el código no existe. NO valido',
				),
				'referenciaCruzadaCie104' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico secundario existe. Se valida que la edad del usuario exista entre el rango de edad mínima y edad máxima permitido por el código CIE10. Se valida que el genero del usuario exista entre el rango de genero permitido por el código CIE10. OBSERVACION: Obligatorio que el usuario exista en el archivo usuario',
					'valido' => 'CIE10: Z000, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 0 años a 999 años. CRUCE VALIDO',
					'invalido' => 'CIE10: Z003, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 10 años a 17 años. La edad del usuario no es valida con la edad del CIE10. CRUCE NO VALIDO',
				),
				'estadoSalida' => array(
					'descripcion' => 'Se valida que el estado a la salida exista entre el rango de valores permitidos',
					'valido' => '"1" código Valido',
					'invalido' => '"3" código NO Valido',
				),
				'causaBasicaMuerte' => array(
					'descripcion' => 'Se valida que la causa básica de la muerte exista si el estado de salida sea "2"',
					'valido' => 'Estado de salida: 2, Causa básica de la muerte: I469. código Valido',
					'invalido' => 'Estado de salida: 2, Causa básica de la muerte:  código NO Valido',
				),
				'diagnosticoSecundario5' => array(
					'descripcion' => 'Si esta digitado. Se valida que exista en la base de datos CIE10. OBSERVACION: Si no esta digitado no genera error y no se ejecutan las validaciones de "Cruces"',
					'valido' => '"Z000" el código es valido.',
					'invalido' => '"Z985" el código no existe. NO valido',
				),
				'referenciaCruzadaCie105' => array(
					'descripcion' => 'Si el código CIE10 del diagnostico secundario existe. Se valida que la edad del usuario exista entre el rango de edad mínima y edad máxima permitido por el código CIE10. Se valida que el genero del usuario exista entre el rango de genero permitido por el código CIE10. OBSERVACION: Obligatorio que el usuario exista en el archivo usuario',
					'valido' => 'CIE10: Z000, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 0 años a 999 años. CRUCE VALIDO',
					'invalido' => 'CIE10: Z003, Edad: 18 años, Genero: M.  El código CIE10 no tiene restricción de genero (A), la edad mínima es de 10 años a 17 años. La edad del usuario no es valida con la edad del CIE10. CRUCE NO VALIDO',
				))
			,
			'an' => array(
				'numeroFacturaEnAf' => array(
					'descripcion' => 'Se valida que el numero de factura de la consulta exista en el archivo de transacciones "AF"',
					'valido' => 'AN:"HSRF13478068" AF:"HSRF13478068" la factura se encuentra en el archivo AF. Valido',
					'invalido' => 'AN:"HSRF13478068" AF:"HSRF1347806" la factura NO se encuentra en el archivo AF. No valido',
				),
				'codigoPrestadorCt' => array(
					'descripcion' => 'Se valida que el código del prestador de servicios de salud digitado en el archivo de control "CT" sea igual al archivo  de consultas "AN"',
					'valido' => 'Archivo CT: 252900003601 - Archivo AN: 252900003601 => código es igual en ambos archivos',
					'invalido' => 'Archivo CT: 252900003600 - Archivo AN: 252900003601 => código NO es igual en ambos archivos',
				),
				'rangosTipoIdentificacion' => array(
					'descripcion' => 'Se valida que el tipo de identificación exista entre el rango de valores permitidos',
					'valido' => '"CC" es valido para Cedula de Ciudadanía',
					'invalido' => '"CO" NO valido no existe entre el rango de valores permitidos.',
				),
				'longitudIdentificacion' => array(
					'descripcion' => 'Se valida que el numero de identificación no exceda el máximo permitido por cada tipo de identificación',
					'valido' => '"CC" Identificación: 1069852882  diez (10) dígitos completos. Valido',
					'invalido' => '"CC" Identificación: 10698528822  once (11) dígitos completos. NO valido',
				),
				'fechaingreso' => array(
					'descripcion' => 'Se valida que la fecha de ingreso NO sea mayor a la actual',
					'valido' => 'Fecha ingresada: 03/02/2018  Fecha actual: 04/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) NO ES MAYOR a cuatro de febrero de dos mil dieciocho 04/02/2018',
					'invalido' => 'Fecha ingresada: 03/02/2018  Fecha actual: 02/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) ES MAYOR a dos de febrero de dos mil dieciocho 02/02/2018',
				),
				'fechaNacimientoMenorFechaSalida' => array(
					'descripcion' => 'Se valida que la fecha de nacimiento NO sea mayor a la fecha de salida',
					'valido' => 'Fecha de nacimiento: 03/02/2018  Fecha de salida: 04/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) NO ES MAYOR a cuatro de febrero de dos mil dieciocho 04/02/2018',
					'invalido' => 'Fecha de nacimiento: 03/02/2018  Fecha de salida: 02/02/2018 | tres de febrero de dos mil dieciocho (03/02/2018) ES MAYOR a dos de febrero de dos mil dieciocho 02/02/2018',
				),
				'controlPrenatal' => array(
					'descripcion' => 'Se valida que el control prenatal exista entre el rango de valores permitidos',
					'valido' => '"1" código Valido',
					'invalido' => '"3" código NO Valido',
				),
				'generoAn' => array(
					'descripcion' => 'Se valida que el genero exista entre el rango de valores permitidos',
					'valido' => '"1" código Valido',
					'invalido' => '"3" código NO Valido',
				),
				'pesoEntero' => array(
					'descripcion' => 'Se valida que el peso sea un valor numérico',
					'valido' => '"3,450" es valido',
					'invalido' => '"3,450 KG" NO VALIDO',
				),
				'diagnosticoSecundario1' => array(
					'descripcion' => 'Si esta digitado. Se valida que exista en la base de datos CIE10. OBSERVACION: Si no esta digitado no genera error y no se ejecutan las validaciones de "Cruces"',
					'valido' => '"Z000" el código es valido.',
					'invalido' => '"Z985" el código no existe. NO valido',
				),
				'diagnosticoSecundario2' => array(
					'descripcion' => 'Si esta digitado. Se valida que exista en la base de datos CIE10. OBSERVACION: Si no esta digitado no genera error y no se ejecutan las validaciones de "Cruces"',
					'valido' => '"Z000" el código es valido.',
					'invalido' => '"Z985" el código no existe. NO valido',
				))
			,
			'am' => array(
				'numeroFacturaEnAf' => array(
					'descripcion' => 'Se valida que el numero de factura de la consulta exista en el archivo de transacciones "AF"',
					'valido' => 'AM:"HSRF13478068" AF:"HSRF13478068" la factura se encuentra en el archivo AF. Valido',
					'invalido' => 'AM:"HSRF13478068" AF:"HSRF1347806" la factura NO se encuentra en el archivo AF. No valido',
				),
				'codigoPrestadorCt' => array(
					'descripcion' => 'Se valida que el código del prestador de servicios de salud digitado en el archivo de control "CT" sea igual al archivo  de consultas "AM"',
					'valido' => 'Archivo CT: 252900003601 - Archivo AM: 252900003601 => código es igual en ambos archivos',
					'invalido' => 'Archivo CT: 252900003600 - Archivo AM: 252900003601 => código NO es igual en ambos archivos',
				),
				'rangosTipoIdentificacion' => array(
					'descripcion' => 'Se valida que el tipo de identificación exista entre el rango de valores permitidos',
					'valido' => '"CC" es valido para Cedula de Ciudadanía',
					'invalido' => '"CO" NO valido no existe entre el rango de valores permitidos.',
				),
				'longitudIdentificacion' => array(
					'descripcion' => 'Se valida que el numero de identificación no exceda el máximo permitido por cada tipo de identificación',
					'valido' => '"CC" Identificación: 1069852882  diez (10) dígitos completos. Valido',
					'invalido' => '"CC" Identificación: 10698528822  once (11) dígitos completos. NO valido',
				),
				'numeroAutorizacion' => array(
					'descripcion' => 'Si existe. Se valida que el numero de la autorizacion este registrado en el sistema',
					'valido' => 'Autorizacion: 00001. La autorizacion existe en la base de datos. VALIDO',
					'invalido' => 'Autorizacion: 010010. La autorizacion no existe en la base de datos. NO VALIDO',
				),
				'codigoMedicamento' => array(
					'descripcion' => 'Se valida que el código del medicamento se encuentre en la base de datos de CUMS con estado activo',
					'valido' => '"2203" código valido y activo. VALIDO',
					'invalido' => '"4959" código NO valido',
				),
				'tipoMedicamento' => array(
					'descripcion' => 'Se valida que el tipo de medicamento exista entre el rango de valores permitidos',
					'valido' => '"1" código Valido',
					'invalido' => '"3" código NO Valido',
				),
				'valorUnitarioProducto' => array(
					'descripcion' => 'Se valida que el valor unitario del producto este digitado cuando el numero de unidades sea mayor a 0',
					'valido' => 'CASO N°1: Numero de unidades: , Valor unitario: , Como no hay numero de unidades la validación es correcta VALIDO.  CASO N°2: Numero de unidades: 2, Valor unitario:1250. VALIDO',
					'invalido' => 'Numero de unidades: 2,Valor unitario: , NO Valido el valor unitario no esta digitado',
				),
				'valorTotalMedicamento' => array(
					'descripcion' => 'Se valida que el valor unitario sea igual al valor total del medicamento si el numero de unidades no esta digitado (VUM=VTM). Se valida que la multiplicación del numero de unidades por el valor unitario sean igual al valor total del medicamento (NU*VUM=VTM)',
					'valido' => 'CASO N°1: Numero de unidades (NU): , Valor unitario del medicamento (VUM): 1250, Valor total del medicamento (VTM): 1250; 1250=1250 (VUM=VTM) VALIDO. CASO N°2: Numero de unidades (NU): 2, Valor unitario del medicamento (VUM): 1250, Valor total del medicamento (VTM): 2500; 2*1250=2500 (NU*VUM=VTM) VALIDO ',
					'invalido' => 'CASO N°1: Numero de unidades (NU): , Valor unitario del medicamento (VUM): 1200, Valor total del medicamento (VTM): 1250; 1200=1250 (VUM=VTM) NO VALIDO. CASO N°2: Numero de unidades (NU): 2, Valor unitario del medicamento (VUM): 1250, Valor total del medicamento (VTM): 2500; 2*1250=2550 (NU*VUM=VTM) NO VALIDO ',
				),
				'valorMedicamentoCero' => array(
					'descripcion' => 'Si el contrato es por capitación el valor total del medicamento no puede ser cero (0)',
					'valido' => '"0" código Valido',
					'invalido' => '"3" código NO Valido',
				))
			,
			'at' => array(
				'numeroFacturaEnAf' => array(
					'descripcion' => 'Se valida que el numero de factura de la consulta exista en el archivo de transacciones "AF"',
					'valido' => 'AT:"HSRF13478068" AF:"HSRF13478068" la factura se encuentra en el archivo AF. Valido',
					'invalido' => 'AT:"HSRF13478068" AF:"HSRF1347806" la factura NO se encuentra en el archivo AF. No valido',
				),
				'codigoPrestadorCt' => array(
					'descripcion' => 'Se valida que el código del prestador de servicios de salud digitado en el archivo de control "CT" sea igual al archivo  de consultas "AT"',
					'valido' => 'Archivo CT: 252900003601 - Archivo AT: 252900003601 => código es igual en ambos archivos',
					'invalido' => 'Archivo CT: 252900003600 - Archivo AT: 252900003601 => código NO es igual en ambos archivos',
				),
				'rangosTipoIdentificacion' => array(
					'descripcion' => 'Se valida que el tipo de identificación exista entre el rango de valores permitidos',
					'valido' => '"CC" es valido para Cedula de Ciudadanía',
					'invalido' => '"CO" NO valido no existe entre el rango de valores permitidos.',
				),
				'longitudIdentificacion' => array(
					'descripcion' => 'Se valida que el tipo de identificación exista entre el rango de valores permitidos',
					'valido' => '"CC" es valido para Cedula de Ciudadanía',
					'invalido' => '"CO" NO valido no existe entre el rango de valores permitidos.',
				),
				'numeroAutorizacion' => array(
					'descripcion' => 'Si existe. Se valida que el numero de la autorizacion este registrado en el sistema',
					'valido' => 'Autorizacion: 00001. La autorizacion existe en la base de datos. VALIDO',
					'invalido' => 'Autorizacion: 010010. La autorizacion no existe en la base de datos. NO VALIDO',
				),
				'tipoServicio' => array(
					'descripcion' => 'Se valida que el tipo de servicio exista en el rango de valores permitidos',
					'valido' => '"1" código Valido',
					'invalido' => '"5" código NO Valido',
				),
				'valorTotalMaterialInsumo' => array(
					'descripcion' => 'Se valida que el valor unitario sea igual al valor total del material e insumo si la cantidad no esta digitada (VUM=VTM). Se valida que la multiplicación de la cantidad por el valor unitario sean igual al valor total del insumo (NU*VUM=VTM)',
					'valido' => 'CASO N°1: Numero de unidades (NU): , Valor unitario del material e insumo (VUM): 1250, Valor total del materia e insumo (VTM): 1250; 1250=1250 (VUM=VTM) VALIDO. CASO N°2: Numero de unidades (NU): 2, Valor unitario del material e insumo (VUM): 1250, Valor total del material e insumo (VTM): 2500; 2*1250=2500 (NU*VUM=VTM) VALIDO ',
					'invalido' => 'CASO N°1: Numero de unidades (NU): , Valor unitario del material e insumo (VUM): 1200, Valor total del material e insumo (VTM): 1250; 1200=1250 (VUM=VTM) NO VALIDO. CASO N°2: Numero de unidades (NU): 2, Valor unitario del material e insumo (VUM): 1250, Valor total del material e insumo (VTM): 2500; 2*1250=2550 (NU*VUM=VTM) NO VALIDO ',
				),
				'valorTotalInsumoCero' => array(
					'descripcion' => 'Si el contrato es por capitación el valor total del material e insumo no puede ser cero (0)',
					'valido' => '"0" código Valido',
					'invalido' => '"3" código NO Valido',
				))
			,
			'ad' => array(
				'codigoPrestadorCt' => array(
					'descripcion' => 'Se valida que el código del prestador de servicios de salud digitado en el archivo de control "CT" sea igual al archivo  de consultas "AD"',
					'valido' => 'Archivo CT: 252900003601 - Archivo AD: 252900003601 => código es igual en ambos archivos',
					'invalido' => 'Archivo CT: 252900003600 - Archivo AD: 252900003601 => código NO es igual en ambos archivos',
				),
				'codigoConcepto' => array(
					'descripcion' => 'Se valida que el código del concepto exista entre el rango de valores permitidos',
					'valido' => '"01" código valido',
					'invalido' => '"15" código NO Valido',
				)),
		);
		return $reglasValidacion;
	}
}