<?php

namespace App\Models\Validador4505;

use App\Models\Validador4505\RsReglasValidacion4505;

class ReglasValidacion4505 {
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

	function __construct(RsReglasValidacion4505 $reglas = null) {
		$this->reglas = $reglas;
	}

	public function getReglasArray() {
		//dd($this->reglas);
		$reglasValidacion = array('existePrestadorServicioSaludInicioSesion' => true, 'longitudIdentificacionAfiliado' => true, 'primerApellidoAfiliado' => true, 'segundoApellidoAfiliado' => true, 'primerNombreAfiliado' => true, 'segundoNombreAfiliado' => true, 'fechaNacimientoAfiliado' => true, 'generoAfiliado' => true, 'pertenenciaEtnica' => true, 'ocupacion' => true, 'nivelEducativo' => true, 'edadGestacion' => true, 'gestacionGenero' => true, 'sifilisGestacionalCongenitaEdad' => true, 'sifilisGestacionalCongenitaEdadGeneroGestacion' => true, 'sifilisGestacionalCongenitaGenero' => true, 'sifilisGestacionalCongenitaGestacion' => true, 'sifilisGestacionalCongenitaInfeccionTransmisionSexual' => true, 'hipertensionInducidaGestacionEdadSi' => true, 'hipertensionInducidaGestacionGenero' => true, 'hipertensionInducidaGestacionGestacion' => true, 'hipotiroidismoCongenitoEdadSi' => true, 'hipotiroidismoCongenitoResultadoTSH' => true, 'hipotiroidismoCongenitoTratamiento' => true, 'sintomaticoRespiratorioBaciloscopiaDiagnostico' => true, 'lepraTratamientoLepra' => true, 'obsidadDesnutricionProteicoEdadSi' => true, 'obsidadDesnutricionProteicoFechaDiagnostico' => true, 'obsidadDesnutricionProteicoPeso' => true, 'obsidadDesnutricionProteicoTall' => true, 'victimaMaltratoEdadMenorSiMenor' => true, 'victimaMaltratoMujerVictima' => true, 'victimaMaltratoFechaConsultaMujerOMenorVictima' => true, 'victimaMaltratoEdadGeneral' => true, 'victimaViolenciaSexualFechaConsulta' => true, 'enfermedadMentalDiagnostico' => true, 'canverCervixGenero' => true, 'pesoKilogramos' => true, 'pesoFechaConsulta' => true, 'tallaCentimetros' => true, 'tallaFechaConsulta' => true, 'fechaProbablePartoGenero' => true, 'fechaProbablePartoGestacion' => true, 'fechaProbablePartoEdad' => true, 'edadGestacionalNacerDiferenteCero' => true, 'edadGestacionalNacerEdad' => true, 'edadGestacionalNacer43Semanas' => true, 'bcgEdadDiferenteNoAplica' => true, 'bcgMenor6Anios' => true, 'bcgMayor6Anios' => true, 'hepatitisBMenorNoAplica' => true, 'hepatitisBMenor1AnioMenor6Anios' => true, 'hepatitisBMenor1AnioMayor6Anios' => true, 'pentavalenteMenor1AnioNoAplica' => true, 'pentavalenteMenor1AnioMenor6Anios' => true, 'pentavalenteMenor1AnioMayor6Anios' => true, 'pentavalenteMenor4Meses' => true, 'pentavalenteMenor6Meses' => true, 'polioMenor1AnioNoAplica' => true, 'polioMenor1AnioMenor6Anios' => true, 'polioMenor1AnioMayor6Anios' => true, 'polioMenor1AnioMeses' => true, 'DPTMenor5AnioNoAplica' => true, 'DPTMenor5AnioMenor6Anios' => true, 'DPTMenor5AnioMayor6Anios' => true, 'DPTMenor5AnioMeses' => true, 'rotavirusNoAplica' => true, 'rotavirusMenor6Anios' => true, 'rotavirusMeses' => true, 'neumococoNoAplica' => true, 'neumococoMayor6Anios' => true, 'neumococoMenor6Anios' => true, 'neumococoMeses' => true, 'influenzaNiniosNoAplica' => true, 'influenzaNiniosMayor6Anios' => true, 'influenzaNiniosMenor6Anios' => true, 'influenzaNiniosMeses' => true, 'fiebreAmarilla1AnioNiniosNoAplica' => true, 'fiebreAmarilla1AnioNiniosMayor6Anios' => true, 'fiebreAmarilla1AnioNiniosMenor6Anios' => true, 'fiebreAmarilla1AnioNiniosMeses' => true, 'hepatitisANoAplica' => true, 'hepatitisAMayor6Anios' => true, 'hepatitisAMenor6Anios' => true, 'hepatitisAMeses' => true, 'tripleViralNiniosNoAplica' => true, 'tripleViralNiniosMayor6Anios' => true, 'tripleViralNiniosMenor6Anios' => true, 'tripleViralNiniosMeses' => true, 'VPhMayor9Anios' => true, 'VPhGenero' => true, 'TdOTtGenero' => true, 'TdOTtMayor9Anios' => true, 'placaBacterianaEdad' => true, 'edadFechaAtencionPartoCesaria' => true, 'fechaPartoGenero' => true, 'fechaAtencionPartoMenorFechaSalida' => true, 'fechaAtencionPartoNullFechaSalida' => true, 'fechaPartoGestacion' => true, 'fechaAtencionPartoVariablesExtras' => true, 'edadFechaSalidaAtencionPartoCesaria' => true, 'fechaSalidaPartoGenero' => true, 'fechaSalidaPartoGestacion' => true, 'fechaSalidaPartoMayorFechaAtencionParto' => true, 'fechaSalidaPartoNullFechaAtencionParto' => true, 'fechaSalidaAtencionPartoVariablesExtras' => true, 'edadFechaConsejeriaLactanciaMaternaDiferente1885' => true, 'fechaConsejeriaLactanciaMaternaGenero' => true, 'edadFechaControlRecienNacidoDiferente1885' => true, 'edadFechaPlanificacionFamiliarPrimeraVez' => true, 'suministroMetodoAnticonseptivoFechaSuministro' => true, 'suministroMetodoAnticonseptivoEdad' => true, 'fechaSuministroMetodoAnticonceptivoSMA' => true, 'fechaSuministroMetodoAnticonceptivoPFPV' => true, 'controlPrenatalPrimeraVezEdad' => true, 'controlPrenatalPrimeraVezGenero' => true, 'controlPrenatalPrimeraVezGestacion' => true, 'controlPrenatalPrimeraVezMenorUCP' => true, 'edadControlPrenatalPrimeraVez' => true, 'controlPrenatalEdad' => true, 'controlPrenatalControles' => true, 'edadControlPrenatal' => true, 'controlPrenatalGenero' => true, 'controlPrenatalGestacion' => true, 'fechaUltimoControlPrenatalEdad' => true, 'fechaUltimoControlPrenatalControlPrenatalPrimeraVez' => true, 'edadFechaUltimoControlPrenatal' => true, 'ultimoControlPrenatalGenero' => true, 'ultimoControlPrenatalGestacion' => true, 'suministroAcidoFolicoUltimoControlPrenatalEdad' => true, 'edadSuministroAcidoFolicoUltimoControlPrenatal' => true, 'suministroAcidoFolicoUCPGenero' => true, 'suministroAcidoFolicoUcpGestacion' => true, 'sulfatoFerrosoUltimoControlPrenatalEdad' => true, 'edadSulfatoFerrosoUltimoControlPrenatal' => true, 'suministroSulfatoFerrosoUCPGenero' => true, 'suministroSulfatoFerrosoUcpGestacion' => true, 'carbonatoCalcioUltimoControlPrenatalEdad' => true, 'edadCarbonatoCalcioUltimoControlPrenatal' => true, 'suministroCarbonatoCalcioUCPGenero' => true, 'suministroCarbonatoCalcioGestacion' => true, 'fechaDiagnosticoDesnutricionProteicoCaloricaObesidad' => true, 'fechaDiagnosticoDesnutricionProteicoCaloricaPeso' => true, 'fechaDiagnosticoDesnutricionProteicoCaloricaTalla' => true, 'fechaConsultaMujerMenorVictimaMaltrato' => true, 'fechaConsultaVictimaViolenciaSexual' => true, 'edadFechaConsultaCrecimientoDesarrolloPrimeraVez' => true, 'edadFechaSuministroSulfatoFerrosoUltimaConsultaMenor10Anios' => true, 'suministroSulfatoFerrosoUltimaConsultaMenor10AniosEdad' => true, 'edadFechaSuministroVitaminaAUltimaConsultaMenor10AniosDiferente0' => true, 'suministroVitaminaAUltimaConsultaMenor10AniosEdad' => true, 'consultaJovenPrimeraVezEdad' => true, 'edadConsultaJovenPrimeraVez' => true, 'consultaAdultoPrimeraVezEdad' => true, 'edadConsultaAdultoPrimeraVez' => true, 'consultaAdultoPrimeraVezRangosEdad' => true, 'preservativosEntregadosPacConITS' => true, 'asesoriaPreTestElisaVIHMenorPostAsesoriaMismoTest' => true, 'asesoriaPreTestElisaVIHMenorFechaTomaTest' => true, 'asesoriaPostTestMayorPreAsesoriaMismoTest' => true, 'asesoriaPostTestElisaVIHMayorFechaTomaTest' => true, 'diagnosticoPacienteEnfermedadMental' => true, 'fechaAntigenoSuperficieHepatitisBGestantesEdad' => true, 'fechaAntigenoSuperficieHepatitisBGestantesResultado' => true, 'fechaAntigenoSuperficieHepatitisBGestantesGenero' => true, 'fechaAntigenoSuperficieHepatitisBGestantesGestacion' => true, 'resultadoAntigenoSuperficieHepatitisBGestantesEdad' => true, 'resultadoAntigenoSuperficieHepatitisBGestantesFechaAntigeno' => true, 'resultadoAntigenoSuperficieHepatitisBGestantesGenero' => true, 'resultadoAntigenoSuperficieHepatitisBGestantesGestacion' => true, 'fechaSerologiaSifilisResultadoSerologiaSifilis' => true, 'resultadoSerologiaSifilisFechaSerologiaSifilis' => true, 'fechaTomaElisaVIHResultadoElisaVIH' => true, 'fechaTomaElisaVIHMayorFechaAsesoriaPreTest' => true, 'fechaTomaElisaVIHMayorFechaAsesoriaPostTest' => true, 'resultadoElisaVIHFechaTomaTest' => true, 'fechaTSHNeonatalResultadoTest' => true, 'resultadoTSHNeonatalFechaTomaTest' => true, 'fechaTamizajeCancerCuelloUterinoDiferenteCero' => true, 'tamizajeCancerCuelloUterinoGenero' => true, 'edadFechaCitologiaCervicoUterina1845' => true, 'citologiaCervicoUterinaGenero' => true, 'fechaCalidadMuestraCitologiaCervicouterinaBethesdaDiferenteCero' => true, 'resultadoCitologiaCervicoUterinaFechaCitologia' => true, 'citologiaCervicoUterinaSegunBethesdaGenero' => true, 'fechaCalidadMuestraCitologiaCervicouterinaDiferenteCero' => true, 'calidadMuestraCitologiaCervicouterinaResultados' => true, 'calidadMuestraCitologiaCervicoUterinaGenero' => true, 'fechaCodigoHabilitacionIpsCitologiaCervicouterina' => true, 'codigoIpsTomaCitologiaCervicoUterinaGenero' => true, 'codigoIpsTomaCitologiaCervicoUterinaMuestraTest' => true, 'edadFechaColposcopia' => true, 'fechacolposcopiaGenero' => true, 'fechacolposcopiaCodigoIpsTomaTest' => true, 'codigoHabilitacionIPSColposcopiaDB' => true, 'fechaCodigoHabilitacionIPSColposcopia' => true, 'codigoIpsTomaColposcopiaGenero' => true, 'codigoIpsTomaColposcopiaFechaColposcopia' => true, 'edadFechaBiopsiaCervical' => true, 'fechaBiopsiaCervicalGenero' => true, 'fechaBiopsiaCervicalResultadoTest' => true, 'fechaResultadoBiopsiaCervical' => true, 'resultadoBiopsiaCervicalGenero' => true, 'resultadoBiopsiaCervicalFechaTest' => true, 'codigoHabilitacionIpsBiopsiaCervicalDB' => true, 'fechaCodigoHabilitacionIpsBiopsiaCervical' => true, 'codigoIpsTomaBiopsiaCervicalGenero' => true, 'codigoIpsTomaBiopsiaCervicalResultadoTest' => true, 'fechaMamografiaGenero' => true, 'fechaMamografia' => true, 'fechaMamografiaEdad' => true, 'fechaResultadoMamografia' => true, 'resultadoMamografiaGenero' => true, 'resultadoMamografiaFechaEdad' => true, 'resultadoMamografiaFechaTest' => true, 'codigoHabilitacionIPSTomaMamografiaDB' => true, 'fechaCodigoHabilitacionIPSTomaMamografia' => true, 'codigoIpsTomaMamografiaGenero' => true, 'codigoIpsTomaMamografiaEdad' => true, 'codigoIpsTomaMamografiaResultadoTest' => true, 'fechatomaBiopsiaSenoBACAFGenero' => true, 'fechaResultadoBiopsiaSenoGenero' => true, 'fechaResultadoBiopsiaSenoResultado' => true, 'fechaResultadoBiopsiaSenoMayorFechaTomaTest' => true, 'fResultadoBiopsiaSenoGenero' => true, 'resultadoBiopsiaSenoResultadoTest' => true, 'codigoIpsTomaBiopsiaSenoDB' => true, 'codigoIpsTomaBiopsiaSenoGenero' => true, 'codigoIpsTomaBiopsiaSenoResultadoTomaTest' => true, 'hemoglobina' => true, 'fechaCreatininaCreatinina' => true, 'creatininaFechaCreatinina' => true, 'creatinina' => true, 'fechaHemoglobinaGlicosilada' => true, 'hemoglobinaGlicosilada' => true, 'hemoglobinaGlicosiladaFechaHemoglobinaGlicosilada' => true, 'fechaTomaBaciloscopiaDiagnosticoResultadoTest' => true, 'resultadoBaciloscopiaDiagnosticoFechaTest' => true, 'tratamientoParaHipotiroidismoCongenitoHipotiroidismo' => true, 'tratamientoSifilisGestacionalGenero' => true, 'tratamientoSifilisGestacionalGestacion' => true, 'tratamientoSifilisGestacionalCongenitaOGestacional' => true, 'tratamientoSifilisGestacionalEdad' => true, 'tratamientoSifilisCongenitaGestacionalCongenita' => true, 'codigoIpsTomaCitologiaCervicoUterinaDB' => true);
		if (!is_null($this->reglas)) {
			if ($this->reglas->existePrestadorServicioSaludInicioSesion == 1) {$reglasValidacion['existePrestadorServicioSaludInicioSesion'] = true;} else { $reglasValidacion['existePrestadorServicioSaludInicioSesion'] = false;}
			if ($this->reglas->longitudIdentificacionAfiliado == 1) {$reglasValidacion['longitudIdentificacionAfiliado'] = true;} else { $reglasValidacion['longitudIdentificacionAfiliado'] = false;}
			if ($this->reglas->primerApellidoAfiliado == 1) {$reglasValidacion['primerApellidoAfiliado'] = true;} else { $reglasValidacion['primerApellidoAfiliado'] = false;}
			if ($this->reglas->segundoApellidoAfiliado == 1) {$reglasValidacion['segundoApellidoAfiliado'] = true;} else { $reglasValidacion['segundoApellidoAfiliado'] = false;}
			if ($this->reglas->primerNombreAfiliado == 1) {$reglasValidacion['primerNombreAfiliado'] = true;} else { $reglasValidacion['primerNombreAfiliado'] = false;}
			if ($this->reglas->segundoNombreAfiliado == 1) {$reglasValidacion['segundoNombreAfiliado'] = true;} else { $reglasValidacion['segundoNombreAfiliado'] = false;}
			if ($this->reglas->fechaNacimientoAfiliado == 1) {$reglasValidacion['fechaNacimientoAfiliado'] = true;} else { $reglasValidacion['fechaNacimientoAfiliado'] = false;}
			if ($this->reglas->generoAfiliado == 1) {$reglasValidacion['generoAfiliado'] = true;} else { $reglasValidacion['generoAfiliado'] = false;}
			if ($this->reglas->pertenenciaEtnica == 1) {$reglasValidacion['pertenenciaEtnica'] = true;} else { $reglasValidacion['pertenenciaEtnica'] = false;}
			if ($this->reglas->ocupacion == 1) {$reglasValidacion['ocupacion'] = true;} else { $reglasValidacion['ocupacion'] = false;}
			if ($this->reglas->nivelEducativo == 1) {$reglasValidacion['nivelEducativo'] = true;} else { $reglasValidacion['nivelEducativo'] = false;}
			if ($this->reglas->edadGestacion == 1) {$reglasValidacion['edadGestacion'] = true;} else { $reglasValidacion['edadGestacion'] = false;}
			if ($this->reglas->gestacionGenero == 1) {$reglasValidacion['gestacionGenero'] = true;} else { $reglasValidacion['gestacionGenero'] = false;}
			if ($this->reglas->sifilisGestacionalCongenitaEdad == 1) {$reglasValidacion['sifilisGestacionalCongenitaEdad'] = true;} else { $reglasValidacion['sifilisGestacionalCongenitaEdad'] = false;}
			if ($this->reglas->sifilisGestacionalCongenitaEdadGeneroGestacion == 1) {$reglasValidacion['sifilisGestacionalCongenitaEdadGeneroGestacion'] = true;} else { $reglasValidacion['sifilisGestacionalCongenitaEdadGeneroGestacion'] = false;}
			if ($this->reglas->sifilisGestacionalCongenitaGenero == 1) {$reglasValidacion['sifilisGestacionalCongenitaGenero'] = true;} else { $reglasValidacion['sifilisGestacionalCongenitaGenero'] = false;}
			if ($this->reglas->sifilisGestacionalCongenitaGestacion == 1) {$reglasValidacion['sifilisGestacionalCongenitaGestacion'] = true;} else { $reglasValidacion['sifilisGestacionalCongenitaGestacion'] = false;}
			if ($this->reglas->sifilisGestacionalCongenitaInfeccionTransmisionSexual == 1) {$reglasValidacion['sifilisGestacionalCongenitaInfeccionTransmisionSexual'] = true;} else { $reglasValidacion['sifilisGestacionalCongenitaInfeccionTransmisionSexual'] = false;}
			if ($this->reglas->hipertensionInducidaGestacionEdadSi == 1) {$reglasValidacion['hipertensionInducidaGestacionEdadSi'] = true;} else { $reglasValidacion['hipertensionInducidaGestacionEdadSi'] = false;}
			if ($this->reglas->hipertensionInducidaGestacionGenero == 1) {$reglasValidacion['hipertensionInducidaGestacionGenero'] = true;} else { $reglasValidacion['hipertensionInducidaGestacionGenero'] = false;}
			if ($this->reglas->hipertensionInducidaGestacionGestacion == 1) {$reglasValidacion['hipertensionInducidaGestacionGestacion'] = true;} else { $reglasValidacion['hipertensionInducidaGestacionGestacion'] = false;}
			if ($this->reglas->hipotiroidismoCongenitoEdadSi == 1) {$reglasValidacion['hipotiroidismoCongenitoEdadSi'] = true;} else { $reglasValidacion['hipotiroidismoCongenitoEdadSi'] = false;}
			if ($this->reglas->hipotiroidismoCongenitoResultadoTSH == 1) {$reglasValidacion['hipotiroidismoCongenitoResultadoTSH'] = true;} else { $reglasValidacion['hipotiroidismoCongenitoResultadoTSH'] = false;}
			if ($this->reglas->hipotiroidismoCongenitoTratamiento == 1) {$reglasValidacion['hipotiroidismoCongenitoTratamiento'] = true;} else { $reglasValidacion['hipotiroidismoCongenitoTratamiento'] = false;}
			if ($this->reglas->sintomaticoRespiratorioBaciloscopiaDiagnostico == 1) {$reglasValidacion['sintomaticoRespiratorioBaciloscopiaDiagnostico'] = true;} else { $reglasValidacion['sintomaticoRespiratorioBaciloscopiaDiagnostico'] = false;}
			if ($this->reglas->lepraTratamientoLepra == 1) {$reglasValidacion['lepraTratamientoLepra'] = true;} else { $reglasValidacion['lepraTratamientoLepra'] = false;}
			if ($this->reglas->obsidadDesnutricionProteicoEdadSi == 1) {$reglasValidacion['obsidadDesnutricionProteicoEdadSi'] = true;} else { $reglasValidacion['obsidadDesnutricionProteicoEdadSi'] = false;}
			if ($this->reglas->obsidadDesnutricionProteicoFechaDiagnostico == 1) {$reglasValidacion['obsidadDesnutricionProteicoFechaDiagnostico'] = true;} else { $reglasValidacion['obsidadDesnutricionProteicoFechaDiagnostico'] = false;}
			if ($this->reglas->obsidadDesnutricionProteicoPeso == 1) {$reglasValidacion['obsidadDesnutricionProteicoPeso'] = true;} else { $reglasValidacion['obsidadDesnutricionProteicoPeso'] = false;}
			if ($this->reglas->obsidadDesnutricionProteicoTall == 1) {$reglasValidacion['obsidadDesnutricionProteicoTall'] = true;} else { $reglasValidacion['obsidadDesnutricionProteicoTall'] = false;}
			if ($this->reglas->victimaMaltratoEdadMenorSiMenor == 1) {$reglasValidacion['victimaMaltratoEdadMenorSiMenor'] = true;} else { $reglasValidacion['victimaMaltratoEdadMenorSiMenor'] = false;}
			if ($this->reglas->victimaMaltratoMujerVictima == 1) {$reglasValidacion['victimaMaltratoMujerVictima'] = true;} else { $reglasValidacion['victimaMaltratoMujerVictima'] = false;}
			if ($this->reglas->victimaMaltratoFechaConsultaMujerOMenorVictima == 1) {$reglasValidacion['victimaMaltratoFechaConsultaMujerOMenorVictima'] = true;} else { $reglasValidacion['victimaMaltratoFechaConsultaMujerOMenorVictima'] = false;}
			if ($this->reglas->victimaMaltratoEdadGeneral == 1) {$reglasValidacion['victimaMaltratoEdadGeneral'] = true;} else { $reglasValidacion['victimaMaltratoEdadGeneral'] = false;}
			if ($this->reglas->victimaViolenciaSexualFechaConsulta == 1) {$reglasValidacion['victimaViolenciaSexualFechaConsulta'] = true;} else { $reglasValidacion['victimaViolenciaSexualFechaConsulta'] = false;}
			if ($this->reglas->enfermedadMentalDiagnostico == 1) {$reglasValidacion['enfermedadMentalDiagnostico'] = true;} else { $reglasValidacion['enfermedadMentalDiagnostico'] = false;}
			if ($this->reglas->canverCervixGenero == 1) {$reglasValidacion['canverCervixGenero'] = true;} else { $reglasValidacion['canverCervixGenero'] = false;}
			if ($this->reglas->pesoKilogramos == 1) {$reglasValidacion['pesoKilogramos'] = true;} else { $reglasValidacion['pesoKilogramos'] = false;}
			if ($this->reglas->pesoFechaConsulta == 1) {$reglasValidacion['pesoFechaConsulta'] = true;} else { $reglasValidacion['pesoFechaConsulta'] = false;}
			if ($this->reglas->tallaCentimetros == 1) {$reglasValidacion['tallaCentimetros'] = true;} else { $reglasValidacion['tallaCentimetros'] = false;}
			if ($this->reglas->tallaFechaConsulta == 1) {$reglasValidacion['tallaFechaConsulta'] = true;} else { $reglasValidacion['tallaFechaConsulta'] = false;}
			if ($this->reglas->fechaProbablePartoGenero == 1) {$reglasValidacion['fechaProbablePartoGenero'] = true;} else { $reglasValidacion['fechaProbablePartoGenero'] = false;}
			if ($this->reglas->fechaProbablePartoGestacion == 1) {$reglasValidacion['fechaProbablePartoGestacion'] = true;} else { $reglasValidacion['fechaProbablePartoGestacion'] = false;}
			if ($this->reglas->fechaProbablePartoEdad == 1) {$reglasValidacion['fechaProbablePartoEdad'] = true;} else { $reglasValidacion['fechaProbablePartoEdad'] = false;}
			if ($this->reglas->edadGestacionalNacerDiferenteCero == 1) {$reglasValidacion['edadGestacionalNacerDiferenteCero'] = true;} else { $reglasValidacion['edadGestacionalNacerDiferenteCero'] = false;}
			if ($this->reglas->edadGestacionalNacerEdad == 1) {$reglasValidacion['edadGestacionalNacerEdad'] = true;} else { $reglasValidacion['edadGestacionalNacerEdad'] = false;}
			if ($this->reglas->edadGestacionalNacer43Semanas == 1) {$reglasValidacion['edadGestacionalNacer43Semanas'] = true;} else { $reglasValidacion['edadGestacionalNacer43Semanas'] = false;}
			if ($this->reglas->bcgEdadDiferenteNoAplica == 1) {$reglasValidacion['bcgEdadDiferenteNoAplica'] = true;} else { $reglasValidacion['bcgEdadDiferenteNoAplica'] = false;}
			if ($this->reglas->bcgMenor6Anios == 1) {$reglasValidacion['bcgMenor6Anios'] = true;} else { $reglasValidacion['bcgMenor6Anios'] = false;}
			if ($this->reglas->bcgMayor6Anios == 1) {$reglasValidacion['bcgMayor6Anios'] = true;} else { $reglasValidacion['bcgMayor6Anios'] = false;}
			if ($this->reglas->hepatitisBMenorNoAplica == 1) {$reglasValidacion['hepatitisBMenorNoAplica'] = true;} else { $reglasValidacion['hepatitisBMenorNoAplica'] = false;}
			if ($this->reglas->hepatitisBMenor1AnioMenor6Anios == 1) {$reglasValidacion['hepatitisBMenor1AnioMenor6Anios'] = true;} else { $reglasValidacion['hepatitisBMenor1AnioMenor6Anios'] = false;}
			if ($this->reglas->hepatitisBMenor1AnioMayor6Anios == 1) {$reglasValidacion['hepatitisBMenor1AnioMayor6Anios'] = true;} else { $reglasValidacion['hepatitisBMenor1AnioMayor6Anios'] = false;}
			if ($this->reglas->pentavalenteMenor1AnioNoAplica == 1) {$reglasValidacion['pentavalenteMenor1AnioNoAplica'] = true;} else { $reglasValidacion['pentavalenteMenor1AnioNoAplica'] = false;}
			if ($this->reglas->pentavalenteMenor1AnioMenor6Anios == 1) {$reglasValidacion['pentavalenteMenor1AnioMenor6Anios'] = true;} else { $reglasValidacion['pentavalenteMenor1AnioMenor6Anios'] = false;}
			if ($this->reglas->pentavalenteMenor1AnioMayor6Anios == 1) {$reglasValidacion['pentavalenteMenor1AnioMayor6Anios'] = true;} else { $reglasValidacion['pentavalenteMenor1AnioMayor6Anios'] = false;}
			if ($this->reglas->pentavalenteMenor4Meses == 1) {$reglasValidacion['pentavalenteMenor4Meses'] = true;} else { $reglasValidacion['pentavalenteMenor4Meses'] = false;}
			if ($this->reglas->pentavalenteMenor6Meses == 1) {$reglasValidacion['pentavalenteMenor6Meses'] = true;} else { $reglasValidacion['pentavalenteMenor6Meses'] = false;}
			if ($this->reglas->polioMenor1AnioNoAplica == 1) {$reglasValidacion['polioMenor1AnioNoAplica'] = true;} else { $reglasValidacion['polioMenor1AnioNoAplica'] = false;}
			if ($this->reglas->polioMenor1AnioMenor6Anios == 1) {$reglasValidacion['polioMenor1AnioMenor6Anios'] = true;} else { $reglasValidacion['polioMenor1AnioMenor6Anios'] = false;}
			if ($this->reglas->polioMenor1AnioMayor6Anios == 1) {$reglasValidacion['polioMenor1AnioMayor6Anios'] = true;} else { $reglasValidacion['polioMenor1AnioMayor6Anios'] = false;}
			if ($this->reglas->polioMenor1AnioMeses == 1) {$reglasValidacion['polioMenor1AnioMeses'] = true;} else { $reglasValidacion['polioMenor1AnioMeses'] = false;}
			if ($this->reglas->DPTMenor5AnioNoAplica == 1) {$reglasValidacion['DPTMenor5AnioNoAplica'] = true;} else { $reglasValidacion['DPTMenor5AnioNoAplica'] = false;}
			if ($this->reglas->DPTMenor5AnioMenor6Anios == 1) {$reglasValidacion['DPTMenor5AnioMenor6Anios'] = true;} else { $reglasValidacion['DPTMenor5AnioMenor6Anios'] = false;}
			if ($this->reglas->DPTMenor5AnioMayor6Anios == 1) {$reglasValidacion['DPTMenor5AnioMayor6Anios'] = true;} else { $reglasValidacion['DPTMenor5AnioMayor6Anios'] = false;}
			if ($this->reglas->DPTMenor5AnioMeses == 1) {$reglasValidacion['DPTMenor5AnioMeses'] = true;} else { $reglasValidacion['DPTMenor5AnioMeses'] = false;}
			if ($this->reglas->rotavirusNoAplica == 1) {$reglasValidacion['rotavirusNoAplica'] = true;} else { $reglasValidacion['rotavirusNoAplica'] = false;}
			if ($this->reglas->rotavirusMenor6Anios == 1) {$reglasValidacion['rotavirusMenor6Anios'] = true;} else { $reglasValidacion['rotavirusMenor6Anios'] = false;}
			if ($this->reglas->rotavirusMeses == 1) {$reglasValidacion['rotavirusMeses'] = true;} else { $reglasValidacion['rotavirusMeses'] = false;}
			if ($this->reglas->neumococoNoAplica == 1) {$reglasValidacion['neumococoNoAplica'] = true;} else { $reglasValidacion['neumococoNoAplica'] = false;}
			if ($this->reglas->neumococoMayor6Anios == 1) {$reglasValidacion['neumococoMayor6Anios'] = true;} else { $reglasValidacion['neumococoMayor6Anios'] = false;}
			if ($this->reglas->neumococoMenor6Anios == 1) {$reglasValidacion['neumococoMenor6Anios'] = true;} else { $reglasValidacion['neumococoMenor6Anios'] = false;}
			if ($this->reglas->neumococoMeses == 1) {$reglasValidacion['neumococoMeses'] = true;} else { $reglasValidacion['neumococoMeses'] = false;}
			if ($this->reglas->influenzaNiniosNoAplica == 1) {$reglasValidacion['influenzaNiniosNoAplica'] = true;} else { $reglasValidacion['influenzaNiniosNoAplica'] = false;}
			if ($this->reglas->influenzaNiniosMayor6Anios == 1) {$reglasValidacion['influenzaNiniosMayor6Anios'] = true;} else { $reglasValidacion['influenzaNiniosMayor6Anios'] = false;}
			if ($this->reglas->influenzaNiniosMenor6Anios == 1) {$reglasValidacion['influenzaNiniosMenor6Anios'] = true;} else { $reglasValidacion['influenzaNiniosMenor6Anios'] = false;}
			if ($this->reglas->influenzaNiniosMeses == 1) {$reglasValidacion['influenzaNiniosMeses'] = true;} else { $reglasValidacion['influenzaNiniosMeses'] = false;}
			if ($this->reglas->fiebreAmarilla1AnioNiniosNoAplica == 1) {$reglasValidacion['fiebreAmarilla1AnioNiniosNoAplica'] = true;} else { $reglasValidacion['fiebreAmarilla1AnioNiniosNoAplica'] = false;}
			if ($this->reglas->fiebreAmarilla1AnioNiniosMayor6Anios == 1) {$reglasValidacion['fiebreAmarilla1AnioNiniosMayor6Anios'] = true;} else { $reglasValidacion['fiebreAmarilla1AnioNiniosMayor6Anios'] = false;}
			if ($this->reglas->fiebreAmarilla1AnioNiniosMenor6Anios == 1) {$reglasValidacion['fiebreAmarilla1AnioNiniosMenor6Anios'] = true;} else { $reglasValidacion['fiebreAmarilla1AnioNiniosMenor6Anios'] = false;}
			if ($this->reglas->fiebreAmarilla1AnioNiniosMeses == 1) {$reglasValidacion['fiebreAmarilla1AnioNiniosMeses'] = true;} else { $reglasValidacion['fiebreAmarilla1AnioNiniosMeses'] = false;}
			if ($this->reglas->hepatitisANoAplica == 1) {$reglasValidacion['hepatitisANoAplica'] = true;} else { $reglasValidacion['hepatitisANoAplica'] = false;}
			if ($this->reglas->hepatitisAMayor6Anios == 1) {$reglasValidacion['hepatitisAMayor6Anios'] = true;} else { $reglasValidacion['hepatitisAMayor6Anios'] = false;}
			if ($this->reglas->hepatitisAMenor6Anios == 1) {$reglasValidacion['hepatitisAMenor6Anios'] = true;} else { $reglasValidacion['hepatitisAMenor6Anios'] = false;}
			if ($this->reglas->hepatitisAMeses == 1) {$reglasValidacion['hepatitisAMeses'] = true;} else { $reglasValidacion['hepatitisAMeses'] = false;}
			if ($this->reglas->tripleViralNiniosNoAplica == 1) {$reglasValidacion['tripleViralNiniosNoAplica'] = true;} else { $reglasValidacion['tripleViralNiniosNoAplica'] = false;}
			if ($this->reglas->tripleViralNiniosMayor6Anios == 1) {$reglasValidacion['tripleViralNiniosMayor6Anios'] = true;} else { $reglasValidacion['tripleViralNiniosMayor6Anios'] = false;}
			if ($this->reglas->tripleViralNiniosMenor6Anios == 1) {$reglasValidacion['tripleViralNiniosMenor6Anios'] = true;} else { $reglasValidacion['tripleViralNiniosMenor6Anios'] = false;}
			if ($this->reglas->tripleViralNiniosMeses == 1) {$reglasValidacion['tripleViralNiniosMeses'] = true;} else { $reglasValidacion['tripleViralNiniosMeses'] = false;}
			if ($this->reglas->VPhMayor9Anios == 1) {$reglasValidacion['VPhMayor9Anios'] = true;} else { $reglasValidacion['VPhMayor9Anios'] = false;}
			if ($this->reglas->VPhGenero == 1) {$reglasValidacion['VPhGenero'] = true;} else { $reglasValidacion['VPhGenero'] = false;}
			if ($this->reglas->TdOTtGenero == 1) {$reglasValidacion['TdOTtGenero'] = true;} else { $reglasValidacion['TdOTtGenero'] = false;}
			if ($this->reglas->TdOTtMayor9Anios == 1) {$reglasValidacion['TdOTtMayor9Anios'] = true;} else { $reglasValidacion['TdOTtMayor9Anios'] = false;}
			if ($this->reglas->placaBacterianaEdad == 1) {$reglasValidacion['placaBacterianaEdad'] = true;} else { $reglasValidacion['placaBacterianaEdad'] = false;}
			if ($this->reglas->edadFechaAtencionPartoCesaria == 1) {$reglasValidacion['edadFechaAtencionPartoCesaria'] = true;} else { $reglasValidacion['edadFechaAtencionPartoCesaria'] = false;}
			if ($this->reglas->fechaPartoGenero == 1) {$reglasValidacion['fechaPartoGenero'] = true;} else { $reglasValidacion['fechaPartoGenero'] = false;}
			if ($this->reglas->fechaAtencionPartoMenorFechaSalida == 1) {$reglasValidacion['fechaAtencionPartoMenorFechaSalida'] = true;} else { $reglasValidacion['fechaAtencionPartoMenorFechaSalida'] = false;}
			if ($this->reglas->fechaAtencionPartoNullFechaSalida == 1) {$reglasValidacion['fechaAtencionPartoNullFechaSalida'] = true;} else { $reglasValidacion['fechaAtencionPartoNullFechaSalida'] = false;}
			if ($this->reglas->fechaPartoGestacion == 1) {$reglasValidacion['fechaPartoGestacion'] = true;} else { $reglasValidacion['fechaPartoGestacion'] = false;}
			if ($this->reglas->fechaAtencionPartoVariablesExtras == 1) {$reglasValidacion['fechaAtencionPartoVariablesExtras'] = true;} else { $reglasValidacion['fechaAtencionPartoVariablesExtras'] = false;}
			if ($this->reglas->edadFechaSalidaAtencionPartoCesaria == 1) {$reglasValidacion['edadFechaSalidaAtencionPartoCesaria'] = true;} else { $reglasValidacion['edadFechaSalidaAtencionPartoCesaria'] = false;}
			if ($this->reglas->fechaSalidaPartoGenero == 1) {$reglasValidacion['fechaSalidaPartoGenero'] = true;} else { $reglasValidacion['fechaSalidaPartoGenero'] = false;}
			if ($this->reglas->fechaSalidaPartoGestacion == 1) {$reglasValidacion['fechaSalidaPartoGestacion'] = true;} else { $reglasValidacion['fechaSalidaPartoGestacion'] = false;}
			if ($this->reglas->fechaSalidaPartoMayorFechaAtencionParto == 1) {$reglasValidacion['fechaSalidaPartoMayorFechaAtencionParto'] = true;} else { $reglasValidacion['fechaSalidaPartoMayorFechaAtencionParto'] = false;}
			if ($this->reglas->fechaSalidaPartoNullFechaAtencionParto == 1) {$reglasValidacion['fechaSalidaPartoNullFechaAtencionParto'] = true;} else { $reglasValidacion['fechaSalidaPartoNullFechaAtencionParto'] = false;}
			if ($this->reglas->fechaSalidaAtencionPartoVariablesExtras == 1) {$reglasValidacion['fechaSalidaAtencionPartoVariablesExtras'] = true;} else { $reglasValidacion['fechaSalidaAtencionPartoVariablesExtras'] = false;}
			if ($this->reglas->edadFechaConsejeriaLactanciaMaternaDiferente1885 == 1) {$reglasValidacion['edadFechaConsejeriaLactanciaMaternaDiferente1885'] = true;} else { $reglasValidacion['edadFechaConsejeriaLactanciaMaternaDiferente1885'] = false;}
			if ($this->reglas->fechaConsejeriaLactanciaMaternaGenero == 1) {$reglasValidacion['fechaConsejeriaLactanciaMaternaGenero'] = true;} else { $reglasValidacion['fechaConsejeriaLactanciaMaternaGenero'] = false;}
			if ($this->reglas->edadFechaControlRecienNacidoDiferente1885 == 1) {$reglasValidacion['edadFechaControlRecienNacidoDiferente1885'] = true;} else { $reglasValidacion['edadFechaControlRecienNacidoDiferente1885'] = false;}
			if ($this->reglas->edadFechaPlanificacionFamiliarPrimeraVez == 1) {$reglasValidacion['edadFechaPlanificacionFamiliarPrimeraVez'] = true;} else { $reglasValidacion['edadFechaPlanificacionFamiliarPrimeraVez'] = false;}
			if ($this->reglas->suministroMetodoAnticonseptivoFechaSuministro == 1) {$reglasValidacion['suministroMetodoAnticonseptivoFechaSuministro'] = true;} else { $reglasValidacion['suministroMetodoAnticonseptivoFechaSuministro'] = false;}
			if ($this->reglas->suministroMetodoAnticonseptivoEdad == 1) {$reglasValidacion['suministroMetodoAnticonseptivoEdad'] = true;} else { $reglasValidacion['suministroMetodoAnticonseptivoEdad'] = false;}
			if ($this->reglas->fechaSuministroMetodoAnticonceptivoSMA == 1) {$reglasValidacion['fechaSuministroMetodoAnticonceptivoSMA'] = true;} else { $reglasValidacion['fechaSuministroMetodoAnticonceptivoSMA'] = false;}
			if ($this->reglas->fechaSuministroMetodoAnticonceptivoPFPV == 1) {$reglasValidacion['fechaSuministroMetodoAnticonceptivoPFPV'] = true;} else { $reglasValidacion['fechaSuministroMetodoAnticonceptivoPFPV'] = false;}
			if ($this->reglas->controlPrenatalPrimeraVezEdad == 1) {$reglasValidacion['controlPrenatalPrimeraVezEdad'] = true;} else { $reglasValidacion['controlPrenatalPrimeraVezEdad'] = false;}
			if ($this->reglas->controlPrenatalPrimeraVezGenero == 1) {$reglasValidacion['controlPrenatalPrimeraVezGenero'] = true;} else { $reglasValidacion['controlPrenatalPrimeraVezGenero'] = false;}
			if ($this->reglas->controlPrenatalPrimeraVezGestacion == 1) {$reglasValidacion['controlPrenatalPrimeraVezGestacion'] = true;} else { $reglasValidacion['controlPrenatalPrimeraVezGestacion'] = false;}
			if ($this->reglas->controlPrenatalPrimeraVezMenorUCP == 1) {$reglasValidacion['controlPrenatalPrimeraVezMenorUCP'] = true;} else { $reglasValidacion['controlPrenatalPrimeraVezMenorUCP'] = false;}
			if ($this->reglas->edadControlPrenatalPrimeraVez == 1) {$reglasValidacion['edadControlPrenatalPrimeraVez'] = true;} else { $reglasValidacion['edadControlPrenatalPrimeraVez'] = false;}
			if ($this->reglas->controlPrenatalEdad == 1) {$reglasValidacion['controlPrenatalEdad'] = true;} else { $reglasValidacion['controlPrenatalEdad'] = false;}
			if ($this->reglas->controlPrenatalControles == 1) {$reglasValidacion['controlPrenatalControles'] = true;} else { $reglasValidacion['controlPrenatalControles'] = false;}
			if ($this->reglas->edadControlPrenatal == 1) {$reglasValidacion['edadControlPrenatal'] = true;} else { $reglasValidacion['edadControlPrenatal'] = false;}
			if ($this->reglas->controlPrenatalGenero == 1) {$reglasValidacion['controlPrenatalGenero'] = true;} else { $reglasValidacion['controlPrenatalGenero'] = false;}
			if ($this->reglas->controlPrenatalGestacion == 1) {$reglasValidacion['controlPrenatalGestacion'] = true;} else { $reglasValidacion['controlPrenatalGestacion'] = false;}
			if ($this->reglas->fechaUltimoControlPrenatalEdad == 1) {$reglasValidacion['fechaUltimoControlPrenatalEdad'] = true;} else { $reglasValidacion['fechaUltimoControlPrenatalEdad'] = false;}
			if ($this->reglas->fechaUltimoControlPrenatalControlPrenatalPrimeraVez == 1) {$reglasValidacion['fechaUltimoControlPrenatalControlPrenatalPrimeraVez'] = true;} else { $reglasValidacion['fechaUltimoControlPrenatalControlPrenatalPrimeraVez'] = false;}
			if ($this->reglas->edadFechaUltimoControlPrenatal == 1) {$reglasValidacion['edadFechaUltimoControlPrenatal'] = true;} else { $reglasValidacion['edadFechaUltimoControlPrenatal'] = false;}
			if ($this->reglas->ultimoControlPrenatalGenero == 1) {$reglasValidacion['ultimoControlPrenatalGenero'] = true;} else { $reglasValidacion['ultimoControlPrenatalGenero'] = false;}
			if ($this->reglas->ultimoControlPrenatalGestacion == 1) {$reglasValidacion['ultimoControlPrenatalGestacion'] = true;} else { $reglasValidacion['ultimoControlPrenatalGestacion'] = false;}
			if ($this->reglas->suministroAcidoFolicoUltimoControlPrenatalEdad == 1) {$reglasValidacion['suministroAcidoFolicoUltimoControlPrenatalEdad'] = true;} else { $reglasValidacion['suministroAcidoFolicoUltimoControlPrenatalEdad'] = false;}
			if ($this->reglas->edadSuministroAcidoFolicoUltimoControlPrenatal == 1) {$reglasValidacion['edadSuministroAcidoFolicoUltimoControlPrenatal'] = true;} else { $reglasValidacion['edadSuministroAcidoFolicoUltimoControlPrenatal'] = false;}
			if ($this->reglas->suministroAcidoFolicoUCPGenero == 1) {$reglasValidacion['suministroAcidoFolicoUCPGenero'] = true;} else { $reglasValidacion['suministroAcidoFolicoUCPGenero'] = false;}
			if ($this->reglas->suministroAcidoFolicoUcpGestacion == 1) {$reglasValidacion['suministroAcidoFolicoUcpGestacion'] = true;} else { $reglasValidacion['suministroAcidoFolicoUcpGestacion'] = false;}
			if ($this->reglas->sulfatoFerrosoUltimoControlPrenatalEdad == 1) {$reglasValidacion['sulfatoFerrosoUltimoControlPrenatalEdad'] = true;} else { $reglasValidacion['sulfatoFerrosoUltimoControlPrenatalEdad'] = false;}
			if ($this->reglas->edadSulfatoFerrosoUltimoControlPrenatal == 1) {$reglasValidacion['edadSulfatoFerrosoUltimoControlPrenatal'] = true;} else { $reglasValidacion['edadSulfatoFerrosoUltimoControlPrenatal'] = false;}
			if ($this->reglas->suministroSulfatoFerrosoUCPGenero == 1) {$reglasValidacion['suministroSulfatoFerrosoUCPGenero'] = true;} else { $reglasValidacion['suministroSulfatoFerrosoUCPGenero'] = false;}
			if ($this->reglas->suministroSulfatoFerrosoUcpGestacion == 1) {$reglasValidacion['suministroSulfatoFerrosoUcpGestacion'] = true;} else { $reglasValidacion['suministroSulfatoFerrosoUcpGestacion'] = false;}
			if ($this->reglas->carbonatoCalcioUltimoControlPrenatalEdad == 1) {$reglasValidacion['carbonatoCalcioUltimoControlPrenatalEdad'] = true;} else { $reglasValidacion['carbonatoCalcioUltimoControlPrenatalEdad'] = false;}
			if ($this->reglas->edadCarbonatoCalcioUltimoControlPrenatal == 1) {$reglasValidacion['edadCarbonatoCalcioUltimoControlPrenatal'] = true;} else { $reglasValidacion['edadCarbonatoCalcioUltimoControlPrenatal'] = false;}
			if ($this->reglas->suministroCarbonatoCalcioUCPGenero == 1) {$reglasValidacion['suministroCarbonatoCalcioUCPGenero'] = true;} else { $reglasValidacion['suministroCarbonatoCalcioUCPGenero'] = false;}
			if ($this->reglas->suministroCarbonatoCalcioGestacion == 1) {$reglasValidacion['suministroCarbonatoCalcioGestacion'] = true;} else { $reglasValidacion['suministroCarbonatoCalcioGestacion'] = false;}
			if ($this->reglas->fechaDiagnosticoDesnutricionProteicoCaloricaObesidad == 1) {$reglasValidacion['fechaDiagnosticoDesnutricionProteicoCaloricaObesidad'] = true;} else { $reglasValidacion['fechaDiagnosticoDesnutricionProteicoCaloricaObesidad'] = false;}
			if ($this->reglas->fechaDiagnosticoDesnutricionProteicoCaloricaPeso == 1) {$reglasValidacion['fechaDiagnosticoDesnutricionProteicoCaloricaPeso'] = true;} else { $reglasValidacion['fechaDiagnosticoDesnutricionProteicoCaloricaPeso'] = false;}
			if ($this->reglas->fechaDiagnosticoDesnutricionProteicoCaloricaTalla == 1) {$reglasValidacion['fechaDiagnosticoDesnutricionProteicoCaloricaTalla'] = true;} else { $reglasValidacion['fechaDiagnosticoDesnutricionProteicoCaloricaTalla'] = false;}
			if ($this->reglas->fechaConsultaMujerMenorVictimaMaltrato == 1) {$reglasValidacion['fechaConsultaMujerMenorVictimaMaltrato'] = true;} else { $reglasValidacion['fechaConsultaMujerMenorVictimaMaltrato'] = false;}
			if ($this->reglas->fechaConsultaVictimaViolenciaSexual == 1) {$reglasValidacion['fechaConsultaVictimaViolenciaSexual'] = true;} else { $reglasValidacion['fechaConsultaVictimaViolenciaSexual'] = false;}
			if ($this->reglas->edadFechaConsultaCrecimientoDesarrolloPrimeraVez == 1) {$reglasValidacion['edadFechaConsultaCrecimientoDesarrolloPrimeraVez'] = true;} else { $reglasValidacion['edadFechaConsultaCrecimientoDesarrolloPrimeraVez'] = false;}
			if ($this->reglas->edadFechaSuministroSulfatoFerrosoUltimaConsultaMenor10Anios == 1) {$reglasValidacion['edadFechaSuministroSulfatoFerrosoUltimaConsultaMenor10Anios'] = true;} else { $reglasValidacion['edadFechaSuministroSulfatoFerrosoUltimaConsultaMenor10Anios'] = false;}
			if ($this->reglas->suministroSulfatoFerrosoUltimaConsultaMenor10AniosEdad == 1) {$reglasValidacion['suministroSulfatoFerrosoUltimaConsultaMenor10AniosEdad'] = true;} else { $reglasValidacion['suministroSulfatoFerrosoUltimaConsultaMenor10AniosEdad'] = false;}
			if ($this->reglas->edadFechaSuministroVitaminaAUltimaConsultaMenor10AniosDiferente0 == 1) {$reglasValidacion['edadFechaSuministroVitaminaAUltimaConsultaMenor10AniosDiferente0'] = true;} else { $reglasValidacion['edadFechaSuministroVitaminaAUltimaConsultaMenor10AniosDiferente0'] = false;}
			if ($this->reglas->suministroVitaminaAUltimaConsultaMenor10AniosEdad == 1) {$reglasValidacion['suministroVitaminaAUltimaConsultaMenor10AniosEdad'] = true;} else { $reglasValidacion['suministroVitaminaAUltimaConsultaMenor10AniosEdad'] = false;}
			if ($this->reglas->consultaJovenPrimeraVezEdad == 1) {$reglasValidacion['consultaJovenPrimeraVezEdad'] = true;} else { $reglasValidacion['consultaJovenPrimeraVezEdad'] = false;}
			if ($this->reglas->edadConsultaJovenPrimeraVez == 1) {$reglasValidacion['edadConsultaJovenPrimeraVez'] = true;} else { $reglasValidacion['edadConsultaJovenPrimeraVez'] = false;}
			if ($this->reglas->consultaAdultoPrimeraVezEdad == 1) {$reglasValidacion['consultaAdultoPrimeraVezEdad'] = true;} else { $reglasValidacion['consultaAdultoPrimeraVezEdad'] = false;}
			if ($this->reglas->edadConsultaAdultoPrimeraVez == 1) {$reglasValidacion['edadConsultaAdultoPrimeraVez'] = true;} else { $reglasValidacion['edadConsultaAdultoPrimeraVez'] = false;}
			if ($this->reglas->consultaAdultoPrimeraVezRangosEdad == 1) {$reglasValidacion['consultaAdultoPrimeraVezRangosEdad'] = true;} else { $reglasValidacion['consultaAdultoPrimeraVezRangosEdad'] = false;}
			if ($this->reglas->preservativosEntregadosPacConITS == 1) {$reglasValidacion['preservativosEntregadosPacConITS'] = true;} else { $reglasValidacion['preservativosEntregadosPacConITS'] = false;}
			if ($this->reglas->asesoriaPreTestElisaVIHMenorPostAsesoriaMismoTest == 1) {$reglasValidacion['asesoriaPreTestElisaVIHMenorPostAsesoriaMismoTest'] = true;} else { $reglasValidacion['asesoriaPreTestElisaVIHMenorPostAsesoriaMismoTest'] = false;}
			if ($this->reglas->asesoriaPreTestElisaVIHMenorFechaTomaTest == 1) {$reglasValidacion['asesoriaPreTestElisaVIHMenorFechaTomaTest'] = true;} else { $reglasValidacion['asesoriaPreTestElisaVIHMenorFechaTomaTest'] = false;}
			if ($this->reglas->asesoriaPostTestMayorPreAsesoriaMismoTest == 1) {$reglasValidacion['asesoriaPostTestMayorPreAsesoriaMismoTest'] = true;} else { $reglasValidacion['asesoriaPostTestMayorPreAsesoriaMismoTest'] = false;}
			if ($this->reglas->asesoriaPostTestElisaVIHMayorFechaTomaTest == 1) {$reglasValidacion['asesoriaPostTestElisaVIHMayorFechaTomaTest'] = true;} else { $reglasValidacion['asesoriaPostTestElisaVIHMayorFechaTomaTest'] = false;}
			if ($this->reglas->diagnosticoPacienteEnfermedadMental == 1) {$reglasValidacion['diagnosticoPacienteEnfermedadMental'] = true;} else { $reglasValidacion['diagnosticoPacienteEnfermedadMental'] = false;}
			if ($this->reglas->fechaAntigenoSuperficieHepatitisBGestantesEdad == 1) {$reglasValidacion['fechaAntigenoSuperficieHepatitisBGestantesEdad'] = true;} else { $reglasValidacion['fechaAntigenoSuperficieHepatitisBGestantesEdad'] = false;}
			if ($this->reglas->fechaAntigenoSuperficieHepatitisBGestantesResultado == 1) {$reglasValidacion['fechaAntigenoSuperficieHepatitisBGestantesResultado'] = true;} else { $reglasValidacion['fechaAntigenoSuperficieHepatitisBGestantesResultado'] = false;}
			if ($this->reglas->fechaAntigenoSuperficieHepatitisBGestantesGenero == 1) {$reglasValidacion['fechaAntigenoSuperficieHepatitisBGestantesGenero'] = true;} else { $reglasValidacion['fechaAntigenoSuperficieHepatitisBGestantesGenero'] = false;}
			if ($this->reglas->fechaAntigenoSuperficieHepatitisBGestantesGestacion == 1) {$reglasValidacion['fechaAntigenoSuperficieHepatitisBGestantesGestacion'] = true;} else { $reglasValidacion['fechaAntigenoSuperficieHepatitisBGestantesGestacion'] = false;}
			if ($this->reglas->resultadoAntigenoSuperficieHepatitisBGestantesEdad == 1) {$reglasValidacion['resultadoAntigenoSuperficieHepatitisBGestantesEdad'] = true;} else { $reglasValidacion['resultadoAntigenoSuperficieHepatitisBGestantesEdad'] = false;}
			if ($this->reglas->resultadoAntigenoSuperficieHepatitisBGestantesFechaAntigeno == 1) {$reglasValidacion['resultadoAntigenoSuperficieHepatitisBGestantesFechaAntigeno'] = true;} else { $reglasValidacion['resultadoAntigenoSuperficieHepatitisBGestantesFechaAntigeno'] = false;}
			if ($this->reglas->resultadoAntigenoSuperficieHepatitisBGestantesGenero == 1) {$reglasValidacion['resultadoAntigenoSuperficieHepatitisBGestantesGenero'] = true;} else { $reglasValidacion['resultadoAntigenoSuperficieHepatitisBGestantesGenero'] = false;}
			if ($this->reglas->resultadoAntigenoSuperficieHepatitisBGestantesGestacion == 1) {$reglasValidacion['resultadoAntigenoSuperficieHepatitisBGestantesGestacion'] = true;} else { $reglasValidacion['resultadoAntigenoSuperficieHepatitisBGestantesGestacion'] = false;}
			if ($this->reglas->fechaSerologiaSifilisResultadoSerologiaSifilis == 1) {$reglasValidacion['fechaSerologiaSifilisResultadoSerologiaSifilis'] = true;} else { $reglasValidacion['fechaSerologiaSifilisResultadoSerologiaSifilis'] = false;}
			if ($this->reglas->resultadoSerologiaSifilisFechaSerologiaSifilis == 1) {$reglasValidacion['resultadoSerologiaSifilisFechaSerologiaSifilis'] = true;} else { $reglasValidacion['resultadoSerologiaSifilisFechaSerologiaSifilis'] = false;}
			if ($this->reglas->fechaTomaElisaVIHResultadoElisaVIH == 1) {$reglasValidacion['fechaTomaElisaVIHResultadoElisaVIH'] = true;} else { $reglasValidacion['fechaTomaElisaVIHResultadoElisaVIH'] = false;}
			if ($this->reglas->fechaTomaElisaVIHMayorFechaAsesoriaPreTest == 1) {$reglasValidacion['fechaTomaElisaVIHMayorFechaAsesoriaPreTest'] = true;} else { $reglasValidacion['fechaTomaElisaVIHMayorFechaAsesoriaPreTest'] = false;}
			if ($this->reglas->fechaTomaElisaVIHMayorFechaAsesoriaPostTest == 1) {$reglasValidacion['fechaTomaElisaVIHMayorFechaAsesoriaPostTest'] = true;} else { $reglasValidacion['fechaTomaElisaVIHMayorFechaAsesoriaPostTest'] = false;}
			if ($this->reglas->resultadoElisaVIHFechaTomaTest == 1) {$reglasValidacion['resultadoElisaVIHFechaTomaTest'] = true;} else { $reglasValidacion['resultadoElisaVIHFechaTomaTest'] = false;}
			if ($this->reglas->fechaTSHNeonatalResultadoTest == 1) {$reglasValidacion['fechaTSHNeonatalResultadoTest'] = true;} else { $reglasValidacion['fechaTSHNeonatalResultadoTest'] = false;}
			if ($this->reglas->resultadoTSHNeonatalFechaTomaTest == 1) {$reglasValidacion['resultadoTSHNeonatalFechaTomaTest'] = true;} else { $reglasValidacion['resultadoTSHNeonatalFechaTomaTest'] = false;}
			if ($this->reglas->fechaTamizajeCancerCuelloUterinoDiferenteCero == 1) {$reglasValidacion['fechaTamizajeCancerCuelloUterinoDiferenteCero'] = true;} else { $reglasValidacion['fechaTamizajeCancerCuelloUterinoDiferenteCero'] = false;}
			if ($this->reglas->tamizajeCancerCuelloUterinoGenero == 1) {$reglasValidacion['tamizajeCancerCuelloUterinoGenero'] = true;} else { $reglasValidacion['tamizajeCancerCuelloUterinoGenero'] = false;}
			if ($this->reglas->edadFechaCitologiaCervicoUterina1845 == 1) {$reglasValidacion['edadFechaCitologiaCervicoUterina1845'] = true;} else { $reglasValidacion['edadFechaCitologiaCervicoUterina1845'] = false;}
			if ($this->reglas->citologiaCervicoUterinaGenero == 1) {$reglasValidacion['citologiaCervicoUterinaGenero'] = true;} else { $reglasValidacion['citologiaCervicoUterinaGenero'] = false;}
			if ($this->reglas->fechaCalidadMuestraCitologiaCervicouterinaBethesdaDiferenteCero == 1) {$reglasValidacion['fechaCalidadMuestraCitologiaCervicouterinaBethesdaDiferenteCero'] = true;} else { $reglasValidacion['fechaCalidadMuestraCitologiaCervicouterinaBethesdaDiferenteCero'] = false;}
			if ($this->reglas->resultadoCitologiaCervicoUterinaFechaCitologia == 1) {$reglasValidacion['resultadoCitologiaCervicoUterinaFechaCitologia'] = true;} else { $reglasValidacion['resultadoCitologiaCervicoUterinaFechaCitologia'] = false;}
			if ($this->reglas->citologiaCervicoUterinaSegunBethesdaGenero == 1) {$reglasValidacion['citologiaCervicoUterinaSegunBethesdaGenero'] = true;} else { $reglasValidacion['citologiaCervicoUterinaSegunBethesdaGenero'] = false;}
			if ($this->reglas->fechaCalidadMuestraCitologiaCervicouterinaDiferenteCero == 1) {$reglasValidacion['fechaCalidadMuestraCitologiaCervicouterinaDiferenteCero'] = true;} else { $reglasValidacion['fechaCalidadMuestraCitologiaCervicouterinaDiferenteCero'] = false;}
			if ($this->reglas->calidadMuestraCitologiaCervicouterinaResultados == 1) {$reglasValidacion['calidadMuestraCitologiaCervicouterinaResultados'] = true;} else { $reglasValidacion['calidadMuestraCitologiaCervicouterinaResultados'] = false;}
			if ($this->reglas->calidadMuestraCitologiaCervicoUterinaGenero == 1) {$reglasValidacion['calidadMuestraCitologiaCervicoUterinaGenero'] = true;} else { $reglasValidacion['calidadMuestraCitologiaCervicoUterinaGenero'] = false;}
			if ($this->reglas->fechaCodigoHabilitacionIpsCitologiaCervicouterina == 1) {$reglasValidacion['fechaCodigoHabilitacionIpsCitologiaCervicouterina'] = true;} else { $reglasValidacion['fechaCodigoHabilitacionIpsCitologiaCervicouterina'] = false;}
			if ($this->reglas->codigoIpsTomaCitologiaCervicoUterinaGenero == 1) {$reglasValidacion['codigoIpsTomaCitologiaCervicoUterinaGenero'] = true;} else { $reglasValidacion['codigoIpsTomaCitologiaCervicoUterinaGenero'] = false;}
			if ($this->reglas->codigoIpsTomaCitologiaCervicoUterinaMuestraTest == 1) {$reglasValidacion['codigoIpsTomaCitologiaCervicoUterinaMuestraTest'] = true;} else { $reglasValidacion['codigoIpsTomaCitologiaCervicoUterinaMuestraTest'] = false;}
			if ($this->reglas->edadFechaColposcopia == 1) {$reglasValidacion['edadFechaColposcopia'] = true;} else { $reglasValidacion['edadFechaColposcopia'] = false;}
			if ($this->reglas->fechacolposcopiaGenero == 1) {$reglasValidacion['fechacolposcopiaGenero'] = true;} else { $reglasValidacion['fechacolposcopiaGenero'] = false;}
			if ($this->reglas->fechacolposcopiaCodigoIpsTomaTest == 1) {$reglasValidacion['fechacolposcopiaCodigoIpsTomaTest'] = true;} else { $reglasValidacion['fechacolposcopiaCodigoIpsTomaTest'] = false;}
			if ($this->reglas->codigoHabilitacionIPSColposcopiaDB == 1) {$reglasValidacion['codigoHabilitacionIPSColposcopiaDB'] = true;} else { $reglasValidacion['codigoHabilitacionIPSColposcopiaDB'] = false;}
			if ($this->reglas->fechaCodigoHabilitacionIPSColposcopia == 1) {$reglasValidacion['fechaCodigoHabilitacionIPSColposcopia'] = true;} else { $reglasValidacion['fechaCodigoHabilitacionIPSColposcopia'] = false;}
			if ($this->reglas->codigoIpsTomaColposcopiaGenero == 1) {$reglasValidacion['codigoIpsTomaColposcopiaGenero'] = true;} else { $reglasValidacion['codigoIpsTomaColposcopiaGenero'] = false;}
			if ($this->reglas->codigoIpsTomaColposcopiaFechaColposcopia == 1) {$reglasValidacion['codigoIpsTomaColposcopiaFechaColposcopia'] = true;} else { $reglasValidacion['codigoIpsTomaColposcopiaFechaColposcopia'] = false;}
			if ($this->reglas->edadFechaBiopsiaCervical == 1) {$reglasValidacion['edadFechaBiopsiaCervical'] = true;} else { $reglasValidacion['edadFechaBiopsiaCervical'] = false;}
			if ($this->reglas->fechaBiopsiaCervicalGenero == 1) {$reglasValidacion['fechaBiopsiaCervicalGenero'] = true;} else { $reglasValidacion['fechaBiopsiaCervicalGenero'] = false;}
			if ($this->reglas->fechaBiopsiaCervicalResultadoTest == 1) {$reglasValidacion['fechaBiopsiaCervicalResultadoTest'] = true;} else { $reglasValidacion['fechaBiopsiaCervicalResultadoTest'] = false;}
			if ($this->reglas->fechaResultadoBiopsiaCervical == 1) {$reglasValidacion['fechaResultadoBiopsiaCervical'] = true;} else { $reglasValidacion['fechaResultadoBiopsiaCervical'] = false;}
			if ($this->reglas->resultadoBiopsiaCervicalGenero == 1) {$reglasValidacion['resultadoBiopsiaCervicalGenero'] = true;} else { $reglasValidacion['resultadoBiopsiaCervicalGenero'] = false;}
			if ($this->reglas->resultadoBiopsiaCervicalFechaTest == 1) {$reglasValidacion['resultadoBiopsiaCervicalFechaTest'] = true;} else { $reglasValidacion['resultadoBiopsiaCervicalFechaTest'] = false;}
			if ($this->reglas->codigoHabilitacionIpsBiopsiaCervicalDB == 1) {$reglasValidacion['codigoHabilitacionIpsBiopsiaCervicalDB'] = true;} else { $reglasValidacion['codigoHabilitacionIpsBiopsiaCervicalDB'] = false;}
			if ($this->reglas->fechaCodigoHabilitacionIpsBiopsiaCervical == 1) {$reglasValidacion['fechaCodigoHabilitacionIpsBiopsiaCervical'] = true;} else { $reglasValidacion['fechaCodigoHabilitacionIpsBiopsiaCervical'] = false;}
			if ($this->reglas->codigoIpsTomaBiopsiaCervicalGenero == 1) {$reglasValidacion['codigoIpsTomaBiopsiaCervicalGenero'] = true;} else { $reglasValidacion['codigoIpsTomaBiopsiaCervicalGenero'] = false;}
			if ($this->reglas->codigoIpsTomaBiopsiaCervicalResultadoTest == 1) {$reglasValidacion['codigoIpsTomaBiopsiaCervicalResultadoTest'] = true;} else { $reglasValidacion['codigoIpsTomaBiopsiaCervicalResultadoTest'] = false;}
			if ($this->reglas->fechaMamografiaGenero == 1) {$reglasValidacion['fechaMamografiaGenero'] = true;} else { $reglasValidacion['fechaMamografiaGenero'] = false;}
			if ($this->reglas->fechaMamografia == 1) {$reglasValidacion['fechaMamografia'] = true;} else { $reglasValidacion['fechaMamografia'] = false;}
			if ($this->reglas->fechaMamografiaEdad == 1) {$reglasValidacion['fechaMamografiaEdad'] = true;} else { $reglasValidacion['fechaMamografiaEdad'] = false;}
			if ($this->reglas->fechaResultadoMamografia == 1) {$reglasValidacion['fechaResultadoMamografia'] = true;} else { $reglasValidacion['fechaResultadoMamografia'] = false;}
			if ($this->reglas->resultadoMamografiaGenero == 1) {$reglasValidacion['resultadoMamografiaGenero'] = true;} else { $reglasValidacion['resultadoMamografiaGenero'] = false;}
			if ($this->reglas->resultadoMamografiaFechaEdad == 1) {$reglasValidacion['resultadoMamografiaFechaEdad'] = true;} else { $reglasValidacion['resultadoMamografiaFechaEdad'] = false;}
			if ($this->reglas->resultadoMamografiaFechaTest == 1) {$reglasValidacion['resultadoMamografiaFechaTest'] = true;} else { $reglasValidacion['resultadoMamografiaFechaTest'] = false;}
			if ($this->reglas->codigoHabilitacionIPSTomaMamografiaDB == 1) {$reglasValidacion['codigoHabilitacionIPSTomaMamografiaDB'] = true;} else { $reglasValidacion['codigoHabilitacionIPSTomaMamografiaDB'] = false;}
			if ($this->reglas->fechaCodigoHabilitacionIPSTomaMamografia == 1) {$reglasValidacion['fechaCodigoHabilitacionIPSTomaMamografia'] = true;} else { $reglasValidacion['fechaCodigoHabilitacionIPSTomaMamografia'] = false;}
			if ($this->reglas->codigoIpsTomaMamografiaGenero == 1) {$reglasValidacion['codigoIpsTomaMamografiaGenero'] = true;} else { $reglasValidacion['codigoIpsTomaMamografiaGenero'] = false;}
			if ($this->reglas->codigoIpsTomaMamografiaEdad == 1) {$reglasValidacion['codigoIpsTomaMamografiaEdad'] = true;} else { $reglasValidacion['codigoIpsTomaMamografiaEdad'] = false;}
			if ($this->reglas->codigoIpsTomaMamografiaResultadoTest == 1) {$reglasValidacion['codigoIpsTomaMamografiaResultadoTest'] = true;} else { $reglasValidacion['codigoIpsTomaMamografiaResultadoTest'] = false;}
			if ($this->reglas->fechatomaBiopsiaSenoBACAFGenero == 1) {$reglasValidacion['fechatomaBiopsiaSenoBACAFGenero'] = true;} else { $reglasValidacion['fechatomaBiopsiaSenoBACAFGenero'] = false;}
			if ($this->reglas->fechaResultadoBiopsiaSenoGenero == 1) {$reglasValidacion['fechaResultadoBiopsiaSenoGenero'] = true;} else { $reglasValidacion['fechaResultadoBiopsiaSenoGenero'] = false;}
			if ($this->reglas->fechaResultadoBiopsiaSenoResultado == 1) {$reglasValidacion['fechaResultadoBiopsiaSenoResultado'] = true;} else { $reglasValidacion['fechaResultadoBiopsiaSenoResultado'] = false;}
			if ($this->reglas->fechaResultadoBiopsiaSenoMayorFechaTomaTest == 1) {$reglasValidacion['fechaResultadoBiopsiaSenoMayorFechaTomaTest'] = true;} else { $reglasValidacion['fechaResultadoBiopsiaSenoMayorFechaTomaTest'] = false;}
			if ($this->reglas->fResultadoBiopsiaSenoGenero == 1) {$reglasValidacion['fResultadoBiopsiaSenoGenero'] = true;} else { $reglasValidacion['fResultadoBiopsiaSenoGenero'] = false;}
			if ($this->reglas->resultadoBiopsiaSenoResultadoTest == 1) {$reglasValidacion['resultadoBiopsiaSenoResultadoTest'] = true;} else { $reglasValidacion['resultadoBiopsiaSenoResultadoTest'] = false;}
			if ($this->reglas->codigoIpsTomaBiopsiaSenoDB == 1) {$reglasValidacion['codigoIpsTomaBiopsiaSenoDB'] = true;} else { $reglasValidacion['codigoIpsTomaBiopsiaSenoDB'] = false;}
			if ($this->reglas->codigoIpsTomaBiopsiaSenoGenero == 1) {$reglasValidacion['codigoIpsTomaBiopsiaSenoGenero'] = true;} else { $reglasValidacion['codigoIpsTomaBiopsiaSenoGenero'] = false;}
			if ($this->reglas->codigoIpsTomaBiopsiaSenoResultadoTomaTest == 1) {$reglasValidacion['codigoIpsTomaBiopsiaSenoResultadoTomaTest'] = true;} else { $reglasValidacion['codigoIpsTomaBiopsiaSenoResultadoTomaTest'] = false;}
			if ($this->reglas->hemoglobina == 1) {$reglasValidacion['hemoglobina'] = true;} else { $reglasValidacion['hemoglobina'] = false;}
			if ($this->reglas->fechaCreatininaCreatinina == 1) {$reglasValidacion['fechaCreatininaCreatinina'] = true;} else { $reglasValidacion['fechaCreatininaCreatinina'] = false;}
			if ($this->reglas->creatinina == 1) {$reglasValidacion['creatinina'] = true;} else { $reglasValidacion['creatinina'] = false;}
			if ($this->reglas->creatininaFechaCreatinina == 1) {$reglasValidacion['creatininaFechaCreatinina'] = true;} else { $reglasValidacion['creatininaFechaCreatinina'] = false;}
			if ($this->reglas->hemoglobinaGlicosilada == 1) {$reglasValidacion['hemoglobinaGlicosilada'] = true;} else { $reglasValidacion['hemoglobinaGlicosilada'] = false;}
			if ($this->reglas->fechaHemoglobinaGlicosilada == 1) {$reglasValidacion['fechaHemoglobinaGlicosilada'] = true;} else { $reglasValidacion['fechaHemoglobinaGlicosilada'] = false;}
			if ($this->reglas->hemoglobinaGlicosiladaFechaHemoglobinaGlicosilada == 1) {$reglasValidacion['hemoglobinaGlicosiladaFechaHemoglobinaGlicosilada'] = true;} else { $reglasValidacion['hemoglobinaGlicosiladaFechaHemoglobinaGlicosilada'] = false;}
			if ($this->reglas->fechaTomaBaciloscopiaDiagnosticoResultadoTest == 1) {$reglasValidacion['fechaTomaBaciloscopiaDiagnosticoResultadoTest'] = true;} else { $reglasValidacion['fechaTomaBaciloscopiaDiagnosticoResultadoTest'] = false;}
			if ($this->reglas->resultadoBaciloscopiaDiagnosticoFechaTest == 1) {$reglasValidacion['resultadoBaciloscopiaDiagnosticoFechaTest'] = true;} else { $reglasValidacion['resultadoBaciloscopiaDiagnosticoFechaTest'] = false;}
			if ($this->reglas->tratamientoParaHipotiroidismoCongenitoHipotiroidismo == 1) {$reglasValidacion['tratamientoParaHipotiroidismoCongenitoHipotiroidismo'] = true;} else { $reglasValidacion['tratamientoParaHipotiroidismoCongenitoHipotiroidismo'] = false;}
			if ($this->reglas->tratamientoSifilisGestacionalGenero == 1) {$reglasValidacion['tratamientoSifilisGestacionalGenero'] = true;} else { $reglasValidacion['tratamientoSifilisGestacionalGenero'] = false;}
			if ($this->reglas->tratamientoSifilisGestacionalGestacion == 1) {$reglasValidacion['tratamientoSifilisGestacionalGestacion'] = true;} else { $reglasValidacion['tratamientoSifilisGestacionalGestacion'] = false;}
			if ($this->reglas->tratamientoSifilisGestacionalCongenitaOGestacional == 1) {$reglasValidacion['tratamientoSifilisGestacionalCongenitaOGestacional'] = true;} else { $reglasValidacion['tratamientoSifilisGestacionalCongenitaOGestacional'] = false;}
			if ($this->reglas->tratamientoSifilisGestacionalEdad == 1) {$reglasValidacion['tratamientoSifilisGestacionalEdad'] = true;} else { $reglasValidacion['tratamientoSifilisGestacionalEdad'] = false;}
			if ($this->reglas->tratamientoSifilisCongenitaGestacionalCongenita == 1) {$reglasValidacion['tratamientoSifilisCongenitaGestacionalCongenita'] = true;} else { $reglasValidacion['tratamientoSifilisCongenitaGestacionalCongenita'] = false;}
			if ($this->reglas->codigoIpsTomaCitologiaCervicoUterinaDB == 1) {$reglasValidacion['codigoIpsTomaCitologiaCervicoUterinaDB'] = true;} else { $reglasValidacion['codigoIpsTomaCitologiaCervicoUterinaDB'] = false;}
		}
		return $reglasValidacion;
	}
	public function descripcionReglasValidacion() {
		$reglasValidacion = array(
			'existePrestadorServicioSaludInicioSesion' => array(
				'descripcion' => '<strong>(VARIABLE #2: Código de habilitación IPS primaria)</strong><br> Se valida que el codigo de la IPS del archivo 4505 sea igual al codigo de la IPS del usuario que inicia sesion, esta codigo de la IPS del usuario se relaciona con la entidad',
				'valido' => 'Codigo del el archivo plano: <strong>252900011401</strong><br>Codigo del usuario: <strong>252900011401</strong><br> <span style="color:teal">252900011401</span><strong> = </strong><span style="color:teal">252900011401</span>',
				'invalido' => 'Codigo del el archivo plano: <strong>252900011401</strong><br>Codigo del usuario: <strong>252900011402</strong><br> <span style="color:teal">252900011401</span><strong> = </strong><span style="color:red">252900011402</span>',
			), 'longitudIdentificacionAfiliado' => array(
				'descripcion' => '<strong>(VARIABLE #3: Tipo de identificación del usuario y VARIABLE #4: Número de identificación del usuario)</strong><br>Se valida que el tipo de identificacion corresponda con la longitud maxima de la identificacion',
				'valido' => 'Tipo de documento: <strong>CC</strong> | Identificacion: <strong>1069751229</strong><br> <span style="color:teal">1069751229</span> Longitud maxima aceptada para CC es 10 digitos',
				'invalido' => 'Tipo de documento: <strong>CC</strong> | Identificacion: <strong>10697512299</strong><br><span style="color:red">10697512299</span> Longitud maxima aceptada para CC es 10 digitos',
			), 'primerApellidoAfiliado' => array(
				'descripcion' => '<strong>(VARIABLE #5: Primer apellido del usuario)</strong><br>Se valida que el primer apellido del usuario sea igual al primer apellido del usuario en la base de datos BDUA',
				'valido' => 'Primer Apellido Archivo Plano: <strong>VILLA</strong> Primer Apellido BDUA: <strong>VILLA</strong><br> <span style="color:teal">VILLA</span> <strong>=</strong> <span style="color:teal">VILLA</span>',
				'invalido' => 'Primer Apellido Archivo Plano: <strong>VILLA</strong> Primer Apellido BDUA: <strong>VILA</strong><br> <span style="color:teal">VILLA</span> <strong>=</strong> <span style="color:red">VILA</span>',
			), 'segundoApellidoAfiliado' => array(
				'descripcion' => '<strong>(VARIABLE #6: Segundo apellido del usuario)</strong><br>Se valida que el segundo apellido del usuario sea igual al segundo apellido del usuario en la base de datos BDUA',
				'valido' => 'Segundo Apellido Archivo Plano: <strong>VILLA</strong> Segundo Apellido BDUA: <strong>VILLA</strong><br> <span style="color:teal">VILLA</span> <strong>=</strong> <span style="color:teal">VILLA</span>',
				'invalido' => 'Segundo Apellido Archivo Plano: <strong>VILLA</strong> Segundo Apellido BDUA: <strong>VILA</strong><br> <span style="color:teal">VILLA</span> <strong>=</strong> <span style="color:red">VILA</span>',
			), 'primerNombreAfiliado' => array(
				'descripcion' => '<strong>(VARIABLE #7: Primer Nombre del usuario)</strong><br>Se valida que el primer nombre del usuario sea igual al primer nombre del usuario en la base de datos BDUA',
				'valido' => 'Primer Nombre Archivo Plano: <strong>JHON</strong> Primer Nombre BDUA: <strong>JHON</strong><br> <span style="color:teal">JHON</span> <strong>=</strong> <span style="color:teal">JHON</span>',
				'invalido' => 'Primer Nombre Archivo Plano: <strong>JHON</strong> Primer Nombre BDUA: <strong>JON</strong><br> <span style="color:teal">JHON</span> <strong>=</strong> <span style="color:red">JON</span>',
			), 'segundoNombreAfiliado' => array(
				'descripcion' => '<strong>(VARIABLE #8: Segundo Nombre del usuario)</strong><br>Se valida que el segundo nombre del usuario sea igual al segundo nombre del usuario en la base de datos BDUA',
				'valido' => 'Segundo Nombre Archivo Plano: <strong>JHON</strong> Segundo Nombre BDUA: <strong>JHON</strong><br> <span style="color:teal">JHON</span> <strong>=</strong> <span style="color:teal">JHON</span>',
				'invalido' => 'Segundo Nombre Archivo Plano: <strong>JHON</strong> Segundo Nombre BDUA: <strong>JON</strong><br> <span style="color:teal">JHON</span> <strong>=</strong> <span style="color:red">JON</span>',
			), 'fechaNacimientoAfiliado' => array(
				'descripcion' => '<strong>(VARIABLE #9: Fecha de Nacimiento)</strong><br>Se valida que la fecha de nacimiento del usuario sea igual a la fecha de nacimiento del usuario en la base de datos BDUA',
				'valido' => 'Fecha de nacimiento archivo plano: <strong>1997-01-09</strong> Fecha de nacimiento BDUA: <strong>1997-01-09</strong><br> <span style="color:teal">1997-01-09</span> <strong>=</strong> <span style="color:teal">1997-09-01</span>',
				'invalido' => 'Fecha de nacimiento Archivo Plano: <strong>1997-01-09</strong> Fecha de nacimiento BDUA: <strong>1997-01-09</strong><br> <span style="color:teal">1997-09-01</span> <strong>=</strong> <span style="color:red">1997-09-01</span>',
			), 'generoAfiliado' => array(
				'descripcion' => '<strong>(VARIABLE #10: Sexo)</strong><br>Se valida que el sexos del usuario sea igual a el sexo usuario en la base de datos BDUA <br><span class="caption">Esta validacion se ejecuta si el dato esta entre el rango de valores permitidos</span class="caption">',
				'valido' => 'Sexo archivo plano: <strong>M</strong> Sexo BDUA: <strong>M</strong><br> <span style="color:teal">M</span> <strong>=</strong> <span style="color:teal">M</span>',
				'invalido' => 'Sexo Archivo Plano: <strong>M</strong> Sexo BDUA: <strong>F</strong><br> <span style="color:teal">M</span> <strong>=</strong> <span style="color:red">F</span>',
			), 'pertenenciaEtnica' => array(
				'descripcion' => '<strong>(VARIABLE #11: Código pertenencia étnica)</strong><br>Se valida que el dato exista entre el rango de valores permitidos',
				'valido' => 'Codigo pertenencia étnica archivo plano: <strong>6</strong><br> <span style="color:teal">6</span> Codigo valido',
				'invalido' => 'Codigo pertenencia étnica archivo plano: <strong>7</strong><br> <span style="color:red">7</span> Codigo No valido',
			), 'ocupacion' => array(
				'descripcion' => '<strong>(VARIABLE #12: Código ocupación)</strong><br>Se valida que el dato exista entre el rango de valores permitidos',
				'valido' => 'Codigo ocupación archivo plano: <strong>9998</strong><br> <span style="color:teal">9998</span> Codigo valido',
				'invalido' => 'Codigo ocupación archivo plano: <strong>9997</strong><br> <span style="color:red">9997</span> Codigo No valido',
			), 'nivelEducativo' => array(
				'descripcion' => '<strong>(VARIABLE #13: Código de nivel educativo)</strong><br>Se valida que el dato exista entre el rango de valores permitidos',
				'valido' => 'Codigo de nivel educativo archivo plano: <strong>13</strong><br> <span style="color:teal">13</span> Codigo valido',
				'invalido' => 'Codigo de nivel educativo archivo plano: <strong>14</strong><br> <span style="color:red">14</span> Codigo No valido',
			), 'edadGestacion' => array(
				'descripcion' => '<strong>(VARIABLE #14: Gestación)</strong><br>Validar que cuando la variable 14 registre 1 la variable 9 corresponda a >= 10 años y < 60 años <br>Validar que si es < 10 años variable 14 registre 0 <br> Validar que si es >=10 años variable 14 registre un dato diferente a 0 No aplica',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'gestacionGenero' => array(
				'descripcion' => '<strong>(VARIABLE #14: Gestación y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 14 registre 1 la variable 10 corresponda a F. ',
				'valido' => 'Gestacion: 1 Sexo: F <br>Gestacion: 0 Sexo: F <br> <span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Gestacion: 1 Sexo: M <br> <span style="color:red">Cruce No Valido</span>',
			), 'sifilisGestacionalCongenitaEdad' => array(
				'descripcion' => '<strong>(VARIABLE #15: Sífilis Gestacional o congénita y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 15 registre 1 la variable 9 corresponda a < 60 años. <br>Validar que cuando la variable 15 registre 2 el cálculo de la edad* debe ser menor de 3 meses.<br><span class="caption">Esta validacion se ejecuta si el dato esta entre el rango de valores permitidos</span class="caption">',
				'valido' => 'Ejemplo Valido',
				'invalido' => 'Ejemplo No Valido',
			), 'sifilisGestacionalCongenitaEdadGeneroGestacion' => array(
				'descripcion' => '<strong>(VARIABLE #15: Sífilis Gestacional o congénita y VARIABLE #9: Edad y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 9 corresponda >= 10 años y la variable 10 corresponda a F y la variable 14 registre un dato diferente a 1, la variable 15 registre 0 <br><span class="caption">Esta validacion se ejecuta si el dato esta entre el rango de valores permitidos</span class="caption">',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'sifilisGestacionalCongenitaGenero' => array(
				'descripcion' => '<strong>(VARIABLE #15: Sífilis Gestacional o congénita y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 16 registre 1 la variable 10 corresponda a F. ',
				'valido' => 'Sífilis Gestacional o congénita: 1 Sexo: F <br>Sífilis Gestacional o congénita: 0 Sexo: F <br> <span style="color:teal">Cruce Valido</span><br><span class="caption">Esta validacion se ejecuta si el dato esta entre el rango de valores permitidos</span class="caption">',
				'invalido' => 'Sífilis Gestacional o congénita: 1 Sexo: M <br> <span style="color:red">Cruce No Valido</span>',
			), 'sifilisGestacionalCongenitaGestacion' => array(
				'descripcion' => '<strong>(VARIABLE #15: Sífilis Gestacional o congénita y VARIABLE #14: Gestacipón)</strong><br>Validar que cuando la variable 16 registre 1 la variable 14 corresponda a 1.<br><span class="caption">Esta validacion se ejecuta si el dato esta entre el rango de valores permitidos</span class="caption">',
				'valido' => 'Sífilis Gestacional o congénita: 1 Gestacion: 1 <br>Sífilis Gestacional o congénita: 0 Gestacion: 1 <br> <span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Sífilis Gestacional o congénita: 1 Gestacion: 0 <br> <span style="color:red">Cruce No Valido</span>',
			), 'sifilisGestacionalCongenitaInfeccionTransmisionSexual' => array(
				'descripcion' => '<strong>(VARIABLE #15: Sífilis Gestacional o congénita y VARIABLE #24: Infecciones de Trasmisión Sexual)</strong><br>Validar que cuando la variable 15 registre 1 la variable 24 corresponda 1<br><span class="caption">Esta validacion se ejecuta si el dato esta entre el rango de valores permitidos</span class="caption">',
				'valido' => 'Sífilis Gestacional o congénita: 1 Infecciones de Trasmisión Sexual: 1 <br>Sífilis Gestacional o congénita: 0 Infecciones de Trasmisión Sexual: 1 <br> <span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Sífilis Gestacional o congénita: 1 Infecciones de Trasmisión Sexual: 0 <br> <span style="color:red">Cruce No Valido</span>',
			), 'hipertensionInducidaGestacionEdadSi' => array(
				'descripcion' => '<strong>(VARIABLE #16: Hipertensión Inducida por la Gestación y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 16 registre 1 el cálculo de la edad* debe mayor a 10 años y menor a 60 años',
				'valido' => 'Hipertensión Inducida por la Gestación: 1 x Edad: 27 años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Hipertensión Inducida por la Gestación: 1 x Edad: 9 años<br><span style="color:red">Cruce No Valido</span>',
			), 'hipertensionInducidaGestacionGenero' => array(
				'descripcion' => '<strong>(VARIABLE #16: Hipertensión Inducida por la Gestación y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 16 registre 1 la variable 10 corresponda a F.',
				'valido' => 'Hipertensión Inducida por la Gestación: 1 x Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Hipertensión Inducida por la Gestación: 1 x Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'hipertensionInducidaGestacionGestacion' => array(
				'descripcion' => '<strong>(VARIABLE #16: Hipertensión Inducida por la Gestación y VARIABLE #14: Gestacion)</strong><br>Validar que cuando la variable 16 registre 1 la variable 14 corresponda a 1',
				'valido' => 'Hipertensión Inducida por la Gestación: 1 x Gestacion: 1<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Hipertensión Inducida por la Gestación: 1 x Gestacion: 2<br><span style="color:red">Cruce No Valido</span>',
			), 'hipotiroidismoCongenitoEdadSi' => array(
				'descripcion' => '<strong>(VARIABLE #17: Hipotiroidismo Congénito y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 17 registre 1 el cálculo de la edad* debe ser menor a 36 meses.',
				'valido' => 'Hipotiroidismo Congénito: 1 x Edad: 14 meses<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Hipotiroidismo Congénito: 1 x Edad: 39 meses<br><span style="color:red">Cruce No Valido</span>',
			), 'hipotiroidismoCongenitoResultadoTSH' => array(
				'descripcion' => '<strong>(VARIABLE #17: Hipotiroidismo Congénito y VARIABLE #85: Resultado de TSH Neonatal)</strong><br>Validar que cuando la variable 17 registre 1 , la variable 85 corresponda a 2',
				'valido' => 'Hipotiroidismo Congénito: 1 x Resultado de TSH Neonatal: 2<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Hipotiroidismo Congénito: 1 x Resultado de TSH Neonatal: 1<br><span style="color:red">Cruce No Valido</span>',
			), 'hipotiroidismoCongenitoTratamiento' => array(
				'descripcion' => '<strong>(VARIABLE #17: Hipotiroidismo Congénito y VARIABLE #114: Tratamiento para Hipotiroidismo Congénito)</strong><br>Validar que cuando la variable 17 registre 1 , la variable 114 corresponda a un dato diferente a 0',
				'valido' => 'Hipotiroidismo Congénito: 1 x Tratamiento para Hipotiroidismo Congénito: 2<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Hipotiroidismo Congénito: 1 x Tratamiento para Hipotiroidismo Congénito: 0<br><span style="color:red">Cruce No Valido</span>',
			), 'sintomaticoRespiratorioBaciloscopiaDiagnostico' => array(
				'descripcion' => '<strong>(VARIABLE #18: Sintomático Respiratorio y VARIABLE #113: Baciloscopia de Diagnóstico )</strong><br>Validar que cuando la variable 18 registre 1 , la variable 113 corresponda a un dato diferente a 4',
				'valido' => 'Sintomático Respiratorio: 1 x Baciloscopia de Diagnóstico: 2<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Sintomático Respiratorio: 1 x Baciloscopia de Diagnóstico: 4<br><span style="color:red">Cruce No Valido</span>',
			), 'lepraTratamientoLepra' => array(
				'descripcion' => '<strong>(VARIABLE #20: Lepra y VARIABLE #117: Tratamiento para Lepra )</strong><br>Validar que cuando variable 20 registre un dato igual a 1 o 2 , variable 117 registre un dato diferente a 0',
				'valido' => 'Lepra: 1 x Tratamiento para Lepra: 2<br>Lepra: 2 x Tratamiento para Lepra: 2<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Lepra: 1 x Tratamiento para Lepra: 0<br>Lepra: 2 x Tratamiento para Lepra: 0<br><span style="color:red">Cruce No Valido</span>',
			), 'obsidadDesnutricionProteicoEdadSi' => array(
				'descripcion' => '<strong>(VARIABLE #21: Obesidad o Desnutrición Proteico Calórica y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 21 registre 1 y el cálculo de edad* sea mayor a 18 años',
				'valido' => 'Obesidad o Desnutrición Proteico Calórica: 1 x Edad: 19 años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Obesidad o Desnutrición Proteico Calórica: 1 x Edad: 17 años<br><span style="color:red">Cruce No Valido</span>',
			), 'obsidadDesnutricionProteicoFechaDiagnostico' => array(
				'descripcion' => '<strong>(VARIABLE #21: Obesidad o Desnutrición Proteico Calórica y VARIABLE #64: Fecha Diagnóstico Desnutrición Proteico Calórica)</strong><br>Validar que cuando la variable 21 registre 2 ,la variable 64 registre 1800-01-01 o > 1900-01-01',
				'valido' => 'Obesidad o Desnutrición Proteico Calórica: 2 x Fecha Diagnóstico Desnutrición Proteico Calórica: 1800-01-01<br>Obesidad o Desnutrición Proteico Calórica: 1 x Fecha Diagnóstico Desnutrición Proteico Calórica: 2019-01-01<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Obesidad o Desnutrición Proteico Calórica: 2 x Fecha Diagnóstico Desnutrición Proteico Calórica: 1899-01-01<br><span style="color:red">Cruce No Valido</span>',
			), 'obsidadDesnutricionProteicoPeso' => array(
				'descripcion' => '<strong>(VARIABLE #21: Obesidad o Desnutrición Proteico Calórica y VARIABLE #30: Peso)</strong><br>Validar que cuando la variable 21 registre 2 ,la variable 30 registre un dato diferente a 999',
				'valido' => 'Obesidad o Desnutrición Proteico Calórica: 2 x Peso: 30<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Obesidad o Desnutrición Proteico Calórica: 2 x Peso: 999<br><span style="color:red">Cruce No Valido</span>',
			), 'obsidadDesnutricionProteicoTall' => array(
				'descripcion' => '<strong>(VARIABLE #21: Obesidad o Desnutrición Proteico Calórica y VARIABLE #32: Talla)</strong><br>Validar que cuando la variable 21 registre 2 ,la variable 32 registre un dato diferente a 999',
				'valido' => 'Obesidad o Desnutrición Proteico Calórica: 2 x Talla: 30<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Obesidad o Desnutrición Proteico Calórica: 2 x Talla: 999<br><span style="color:red">Cruce No Valido</span>',
			), 'victimaMaltratoEdadMenorSiMenor' => array(
				'descripcion' => '<strong>(VARIABLE #22: Victima de Maltrato y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 22 registre 2 el cálculo de edad* debe ser menor a 18 años y 3 meses. ',
				'valido' => 'Victima de Maltrato: 2 x edad: 18 años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Victima de Maltrato: 2 x edad: 19 años<br><span style="color:red">Cruce No Valido</span>',
			), 'victimaMaltratoMujerVictima' => array(
				'descripcion' => '<strong>(VARIABLE #22: Victima de Maltrato y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 22 registre 1 la variable 10 corresponda a F.',
				'valido' => 'Victima de Maltrato: 1 x Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Victima de Maltrato: 1 x Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'victimaMaltratoFechaConsultaMujerOMenorVictima' => array(
				'descripcion' => '<strong>(VARIABLE #22: Victima de Maltrato y VARIABLE #65: Consulta Mujer o Menor Víctima del Maltrato)</strong><br>Validar que cuando la variable 22 registre 1, la variable 65 registre 1800-01-01 o > 1900-01-01',
				'valido' => 'Victima de Maltrato: 1 x Consulta Mujer o Menor Víctima del Maltrato: 1800-01-01<br>Victima de Maltrato: 1 x Consulta Mujer o Menor Víctima del Maltrato: 2019-01-01<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Victima de Maltrato: 1 x Consulta Mujer o Menor Víctima del Maltrato: 1799-01-01<br><span style="color:red">Cruce No Valido</span>',
			), 'victimaMaltratoEdadGeneral' => array(
				'descripcion' => '<strong>(VARIABLE #22: Victima de Maltrato y VARIABLE #9: Edad)</strong><br>Validar que si variable 9 es <= 18 años, la variable 22 registre un dato diferente a 0.',
				'valido' => 'Victima de Maltrato: 1 x Edad: 17 años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Victima de Maltrato: 0 x Edad: 17 años<br><span style="color:red">Cruce No Valido</span>',
			), 'victimaViolenciaSexualFechaConsulta' => array(
				'descripcion' => '<strong>(VARIABLE #23: Víctima de violencia sexual y VARIABLE #66: Consulta Víctimas de Violencia Sexual)</strong><br>Validar que cuando la variable 23 registre 1, la variable 66 registre 1800-01-01 o > 1900-01-01',
				'valido' => 'Víctima de violencia sexual: 1 x Consulta Víctimas de Violencia Sexual: 1800-01-01<br>Victima de Maltrato: 1 x Consulta Víctimas de Violencia Sexual: 2019-01-01<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Víctima de violencia sexual: 1 x Consulta Víctimas de Violencia Sexual: 1799-01-01<br><span style="color:red">Cruce No Valido</span>',
			), 'enfermedadMentalDiagnostico' => array(
				'descripcion' => '<strong>(VARIABLE #25: Enfermedad Mental y VARIABLE #77: Paciente con Diagnóstico de: Ansiedad, Depresión, Esquizofrenia, déficit de atención, consumo SPA y Bipolaridad recibió Atención en los últimos 6 meses por Equipo Interdisciplinario Completo)</strong><br>Validar que cuando la variable 25 este entre 1 y 6, la variable 77 registre un dato diferente a 0 ',
				'valido' => 'Enfermedad Mental: 1 x Variable #77: 1<br>Victima de Maltrato: 1 x Consulta Víctimas de Violencia Sexual: 2019-01-01<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Enfermedad Mental: 1 x Variable #77: 0<br><span style="color:red">Cruce No Valido</span>',
			), 'canverCervixGenero' => array(
				'descripcion' => '<strong>(VARIABLE #26: Cáncer de Cérvix y VARIABLE #10: Sexo</strong><br>Validar que cuando la variable 26 registre 1 la variable 10 corresponda a F.',
				'valido' => 'Cáncer de Cérvix: 1 x Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Cáncer de Cérvix: 1 x Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'pesoKilogramos' => array(
				'descripcion' => '<strong>(VARIABLE #30: Peso en Kilogramos)</strong><br>Validar que cuando la variable 30 registre un dato diferente a 999 el valor registrado sea menor a 250.',
				'valido' => 'Peso en Kilogramos: 60<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Peso en Kilogramos: 255<br><span style="color:red">Cruce No Valido</span>',
			), 'pesoFechaConsulta' => array(
				'descripcion' => '<strong>(VARIABLE #30: Peso en Kilogramos y VARIABLE #29: Fecha del Peso)</strong><br>Validar que cuando la variable 30 registre 999 la variable 29 corresponda a 1800-01-01',
				'valido' => 'Peso en Kilogramos: 999 X Fecha del peso: 1800-01-01<br> <span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Peso en Kilogramos: 999 X Fecha del peso: 2019-01-01 <br><span style="color:red">Cruce No Valido</span>',
			), 'tallaCentimetros' => array(
				'descripcion' => '<strong>(VARIABLE #32: Talla en Centímetros (población general))</strong><br>Validar que cuando la variable 32 registre un dato diferente a 999 el valor registrado sea menor a 225.',
				'valido' => 'Talla en centimetros: 70<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Talla en centimetros: 230<br><span style="color:red">Cruce No Valido</span>',
			), 'tallaFechaConsulta' => array(
				'descripcion' => '<strong>(VARIABLE #32: Talla en Centímetros (población general) y VARIABLE #31: Fecha de la talla)</strong><br>Validar que cuando la variable 32 registre 999 la variable 31 corresponda a 1800-01-01.',
				'valido' => 'Talla en centimetros: 999 X Fecha de la talla: 1800-01-01<br> <span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Talla en centimetros: 999 X Fecha de la talla: 2019-01-01 <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaProbablePartoGenero' => array(
				'descripcion' => '<strong>(VARIABLE #33: Fecha Probable de Parto y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 33 registre un dato diferente a 1845-01-01 la variable 10 corresponda a F.',
				'valido' => 'Fecha Probable de Parto: 2019-01-01 X Sexo: F <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha Probable de Parto: 2019-01-01 X Sexo: M <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaProbablePartoGestacion' => array(
				'descripcion' => '<strong>(VARIABLE #33: Fecha Probable de Parto y VARIABLE #14: Gestacion)</strong><br>Validar que cuando la variable 33 registre un dato diferente a 1845-01-01 la variable 14 corresponda a 1.',
				'valido' => 'Fecha Probable de Parto: 2019-01-01 X Gestacion: 1 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha Probable de Parto: 2019-01-01 X Gestacion: 0 <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaProbablePartoEdad' => array(
				'descripcion' => '<strong>(VARIABLE #33: Fecha Probable de Parto y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 33 registre un dato diferente a 1845-01-01 la variable 9 corresponda a >= 10 años y < 60 años',
				'valido' => 'Fecha Probable de Parto: 2019-01-01 X Edad: 18 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha Probable de Parto: 2019-01-01 X Edad: 61 años <br><span style="color:red">Cruce No Valido</span>',
			), 'edadGestacionalNacerDiferenteCero' => array(
				'descripcion' => '<strong>(VARIABLE #34: Edad Gestacional al Nacer y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 34 registre un valor diferente a 0 el cálculo de la edad* sea menor a 6 años.',
				'valido' => 'Edad gestacional al nacer: 1 X Edad: 2 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Edad gestacional al nacer: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'edadGestacionalNacerEdad' => array(
				'descripcion' => '<strong>(VARIABLE #34: Edad Gestacional al Nacer y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 9 >= 6 años, variable 34 registre 0',
				'valido' => 'Edad gestacional al nacer: 0 X Edad: 2 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Edad gestacional al nacer: 0 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'edadGestacionalNacer43Semanas' => array(
				'descripcion' => '<strong>(VARIABLE #34: Edad Gestacional al Nacer y VARIABLE #9: Edad)</strong><br>Validar que si variable 34 diferente a 0 o 999 el dato sea <= 43 semanas',
				'valido' => 'Edad gestacional al nacer: 1 X Edad: 40 semanas <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Edad gestacional al nacer: 5 X Edad: 45 semanas <br><span style="color:red">Cruce No Valido</span>',
			), 'bcgEdadDiferenteNoAplica' => array(
				'descripcion' => '<strong>(VARIABLE #35: BCG y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 35 registre un valor diferente a 0 el cálculo de la edad* sea < 6 años.',
				'valido' => 'BCG: 1 X Edad: 2 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'BCG: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'bcgMenor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #35: BCG y VARIABLE #9: Edad)</strong><br>Validar que en < 6 años de edad variable 35 sea diferente a 0 No aplica',
				'valido' => 'BCG: 1 X Edad: 2 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'BCG: 0 X Edad: 2 años <br><span style="color:red">Cruce No Valido</span>',
			), 'bcgMayor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #35: BCG y VARIABLE #9: Edad)</strong><br>Validar que en >= 6 años de edad variable 35 sea 0 No aplica',
				'valido' => 'BCG: 0 X Edad: 7 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'BCG: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'hepatitisBMenorNoAplica' => array(
				'descripcion' => '<strong>(VARIABLE #36: Hepatitis B menores de 1 año y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 36 registre un valor diferente a 0 el cálculo de la edad* sea < 6 años.',
				'valido' => 'Hepatitis B menores de 1 año: 1 X Edad: 3 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Hepatitis B menores de 1 año: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'hepatitisBMenor1AnioMenor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #36: Hepatitis B menores de 1 año y VARIABLE #9: Edad)</strong><br>Validar que en < 6 años de edad variable 36 sea diferente a 0 No aplica',
				'valido' => 'Hepatitis B menores de 1 año: 1 X Edad: 3 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Hepatitis B menores de 1 año: 0 X Edad: 3 años <br><span style="color:red">Cruce No Valido</span>',
			), 'hepatitisBMenor1AnioMayor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #36: Hepatitis B menores de 1 año y VARIABLE #9: Edad)</strong><br>Validar que en >= 6 años de edad variable 36 sea 0 No aplica',
				'valido' => 'Hepatitis B menores de 1 año: 0 X Edad: 7 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Hepatitis B menores de 1 año: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'pentavalenteMenor1AnioNoAplica' => array(
				'descripcion' => '<strong>(VARIABLE #37: Pentavalente y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 37 registre un valor diferente a 0 el cálculo de la edad* sea < 6 años.',
				'valido' => 'Pentavalente: 1 X Edad: 3 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Pentavalente: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'pentavalenteMenor1AnioMenor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #37: Pentavalente y VARIABLE #9: Edad)</strong><br>Validar que en < 6 años de edad variable 37 sea diferente a 0 No aplica',
				'valido' => 'Pentavalente: 1 X Edad: 3 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Pentavalente: 0 X Edad: 3 años <br><span style="color:red">Cruce No Valido</span>',
			), 'pentavalenteMenor1AnioMayor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #37: Pentavalente y VARIABLE #9: Edad)</strong><br>Validar que en >= 6 años de edad variable 37 sea 0 No aplica',
				'valido' => 'Pentavalente: 0 X Edad: 7 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Pentavalente: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'pentavalenteMenor4Meses' => array(
				'descripcion' => '<strong>(VARIABLE #37: Pentavalente y VARIABLE #9: Edad)</strong><br>Edad en meses < 4 variable 37 diferente a 2, 3',
				'valido' => 'Pentavalente: 1 X Edad: 3 meses <span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Pentavalente: 2 X Edad: 3 meses <br>Pentavalente: 3 X Edad: 3 meses<br><span style="color:red">Cruce No Valido</span>',
			), 'pentavalenteMenor6Meses' => array(
				'descripcion' => '<strong>(VARIABLE #37: Pentavalente y VARIABLE #9: Edad)</strong><br>Edad en meses < 6 variable 37 diferente a 3',
				'valido' => 'Pentavalente: 1 X Edad: 5 meses<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Pentavalente: 3 X Edad: 5 meses <br><span style="color:red">Cruce No Valido</span>',
			), 'polioMenor1AnioNoAplica' => array(
				'descripcion' => '<strong>(VARIABLE #38: Polio y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 38 registre un valor diferente a 0 el cálculo de la edad* sea < 6 años.',
				'valido' => 'Polio: 1 X Edad: 5 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Polio: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'polioMenor1AnioMenor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #38: Polio y VARIABLE #9: Edad)</strong><br>Validar que en < 6 años de edad variable 38 sea diferente a 0 No aplica',
				'valido' => 'Polio: 1 X Edad: 5 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Polio: 0 X Edad: 5 años <br><span style="color:red">Cruce No Valido</span>',
			), 'polioMenor1AnioMayor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #38: Polio y VARIABLE #9: Edad)</strong><br>Validar que en >= 6 años de edad variable 38 sea 0 No aplica',
				'valido' => 'Polio: 0 X Edad: 7 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Polio: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'polioMenor1AnioMeses' => array(
				'descripcion' => '<strong>(VARIABLE #38: Polio y VARIABLE #9: Edad)</strong><br>Edad en meses < 4 variable 38 diferente a 2, 3, 4 y 5<br>Edad en meses < 6 variable 38 diferente a 3, 4 y 5<br>Edad en meses < 12 variable 38 diferente a 4 y 5<br>Edad en meses < 18 variable 38 diferente a 5',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'DPTMenor5AnioNoAplica' => array(
				'descripcion' => '<strong>(VARIABLE #39: DPT menores de 5 años y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 39 registre un valor diferente a 0 el cálculo de la edad* sea < 6 años.',
				'valido' => 'DPT menores de 5 años: 1 X Edad: 5 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'DPT menores de 5 años: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'DPTMenor5AnioMenor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #39: DPT menores de 5 años y VARIABLE #9: Edad)</strong><br>Validar que en < 6 años de edad variable 39 sea diferente a 0 No aplica',
				'valido' => 'DPT menores de 5 años: 1 X Edad: 5 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'DPT menores de 5 años: 0 X Edad: 5 años <br><span style="color:red">Cruce No Valido</span>',
			), 'DPTMenor5AnioMayor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #39: DPT menores de 5 años y VARIABLE #9: Edad)</strong><br>Validar que en >= 6 años de edad variable 39 sea 0 No aplica',
				'valido' => 'DPT menores de 5 años: 0 X Edad: 7 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'DPT menores de 5 años: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'DPTMenor5AnioMeses' => array(
				'descripcion' => '<strong>(VARIABLE #39: DPT menores de 5 años y VARIABLE #9: Edad)</strong><br>Edad en meses < 18 variable 39 diferente a 4, 5<br>Edad en meses < 60 variable 39 diferente a 5',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'rotavirusNoAplica' => array(
				'descripcion' => '<strong>(VARIABLE #40: Rotavirus y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 40 registre un valor diferente a 0 el cálculo de la edad* sea < 6 años.',
				'valido' => 'Rotavirus: 1 X Edad: 5 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Rotavirus: 0 X Edad: 5 años <br><span style="color:red">Cruce No Valido</span>',
			), 'rotavirusMenor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #40: Rotavirus y VARIABLE #9: Edad)</strong><br>Validar que en >= 6 años de edad variable 40 sea 0 No aplica',
				'valido' => 'Rotavirus: 0 X Edad: 7 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Rotavirus: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'rotavirusMeses' => array(
				'descripcion' => '<strong>(VARIABLE #40: Rotavirus y VARIABLE #9: Edad)</strong><br>Validar que si Edad en meses < 4 variable 40 diferente a 2',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'neumococoNoAplica' => array(
				'descripcion' => '<strong>(VARIABLE #41: Neumococo y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 41 registre un valor diferente a 0 el cálculo de la edad* sea < 6 años.',
				'valido' => 'Neumococo: 1 X Edad: 5 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Neumococo: 0 X Edad: 5 años <br><span style="color:red">Cruce No Valido</span>',
			), 'neumococoMayor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #41: Neumococo y VARIABLE #9: Edad)</strong><br>Validar que en >= 6 años de edad variable 41 sea 0 No aplica',
				'valido' => 'Neumococo: 0 X Edad: 7 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Neumococo: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'neumococoMenor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #41: Neumococo y VARIABLE #9: Edad)</strong><br>Validar que en < 6 años de edad variable 41 sea diferente a 0 No aplica',
				'valido' => 'Neumococo: 1 X Edad: 5 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Neumococo: 0 X Edad: 5 años <br><span style="color:red">Cruce No Valido</span>',
			), 'neumococoMeses' => array(
				'descripcion' => '<strong>(VARIABLE #41: Neumococo y VARIABLE #9: Edad)</strong><br>Validar que si:<br> Edad en meses < 4 variable 41 diferente a 2, 3<br>Edad en meses < 6 variable 41 diferente a 3',
				'valido' => 'Neumococo: 1 X Edad: 5 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Neumococo: 0 X Edad: 5 años <br><span style="color:red">Cruce No Valido</span>',
			), 'influenzaNiniosNoAplica' => array(
				'descripcion' => '<strong>(VARIABLE #42: Influenza Niños y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 42 registre un valor diferente a 0 el cálculo de la edad* sea < 6 años.',
				'valido' => 'Influenza Niños: 1 X Edad: 5 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Influenza Niños: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'influenzaNiniosMayor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #42: Influenza Niños y VARIABLE #9: Edad)</strong><br>Validar que en >= 6 años de edad variable 42 sea 0 No aplica',
				'valido' => 'Influenza Niños: 0 X Edad: 7 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Influenza Niños: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'influenzaNiniosMenor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #42: Influenza Niños y VARIABLE #9: Edad)</strong><br>Validar que en < 6 años de edad variable 42 sea diferente a 0 No aplica',
				'valido' => 'Influenza Niños: 1 X Edad: 5 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Influenza Niños: 0 X Edad: 5 años <br><span style="color:red">Cruce No Valido</span>',
			), 'influenzaNiniosMeses' => array(
				'descripcion' => '<strong>(VARIABLE #42: Influenza Niños y VARIABLE #9: Edad)</strong><br>Validar que si: <br>Edad en meses < 4 variable 42 diferente a 2, 3 <br>Edad en meses < 6 variable 42 diferente a 3',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'fiebreAmarilla1AnioNiniosNoAplica' => array(
				'descripcion' => '<strong>(VARIABLE #43: Fiebre Amarilla niños de 1 año y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 43 registre un valor diferente a 0 el cálculo de la edad* sea < 6 años.',
				'valido' => 'Fiebre Amarilla niños de 1 año: 1 X Edad: 5 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fiebre Amarilla niños de 1 año: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'fiebreAmarilla1AnioNiniosMayor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #43: Fiebre Amarilla niños de 1 año y VARIABLE #9: Edad)</strong><br>Validar que en >= 6 años de edad variable 43 sea 0 No aplica',
				'valido' => 'Fiebre Amarilla niños de 1 año: 0 X Edad: 7 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fiebre Amarilla niños de 1 año: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'fiebreAmarilla1AnioNiniosMenor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #43: Fiebre Amarilla niños de 1 año y VARIABLE #9: Edad)</strong><br>Validar que en < 6 años de edad variable 43 sea diferente a 0 No aplica',
				'valido' => 'Fiebre Amarilla niños de 1 año: 1 X Edad: 5 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fiebre Amarilla niños de 1 año: 0 X Edad: 5 años <br><span style="color:red">Cruce No Valido</span>',
			), 'fiebreAmarilla1AnioNiniosMeses' => array(
				'descripcion' => '<strong>(VARIABLE #43: Fiebre Amarilla niños de 1 año y VARIABLE #9: Edad)</strong><br>Validar que si: <br>Edad en meses < 12 variable 43 diferente a 1',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'hepatitisANoAplica' => array(
				'descripcion' => '<strong>(VARIABLE #44: Hepatitis A y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 44 registre un valor diferente a 0 el cálculo de la edad* sea < 6 años.',
				'valido' => 'Hepatitis A: 1 X Edad: 5 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Hepatitis A: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'hepatitisAMayor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #44: Hepatitis A y VARIABLE #9: Edad)</strong><br>Validar que en >= 6 años de edad variable 44 sea 0 No aplica',
				'valido' => 'Hepatitis A: 0 X Edad: 7 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Hepatitis A: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'hepatitisAMenor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #44: Hepatitis A y VARIABLE #9: Edad)</strong><br>Validar que en < 6 años de edad variable 44 sea diferente a 0 No aplica',
				'valido' => 'Hepatitis A: 1 X Edad: 5 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Hepatitis A: 0 X Edad: 5 años <br><span style="color:red">Cruce No Valido</span>',
			), 'hepatitisAMeses' => array(
				'descripcion' => '<strong>(VARIABLE #44: Hepatitis A y VARIABLE #9: Edad)</strong><br>Validar que si:<br>Edad en meses < 12 variable 44 diferente a 1',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'tripleViralNiniosNoAplica' => array(
				'descripcion' => '<strong>(VARIABLE #45: Triple Viral Niños y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 45 registre un valor diferente a 0 el cálculo de la edad* sea < 6 años',
				'valido' => 'Triple Viral Niños: 1 X Edad: 5 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Triple Viral Niños: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'tripleViralNiniosMayor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #45: Triple Viral Niños y VARIABLE #9: Edad)</strong><br>Validar que en >= 6 años de edad variable 45 sea 0 No aplica',
				'valido' => 'Triple Viral Niños: 0 X Edad: 7 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Triple Viral Niños: 1 X Edad: 7 años <br><span style="color:red">Cruce No Valido</span>',
			), 'tripleViralNiniosMenor6Anios' => array(
				'descripcion' => '<strong>(VARIABLE #45: Triple Viral Niños y VARIABLE #9: Edad)</strong><br>Validar que en < 6 años de edad variable 45 sea diferente a 0 No aplica',
				'valido' => 'Triple Viral Niños: 1 X Edad: 5 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Triple Viral Niños: 0 X Edad: 5 años <br><span style="color:red">Cruce No Valido</span>',
			), 'tripleViralNiniosMeses' => array(
				'descripcion' => '<strong>(VARIABLE #45: Triple Viral Niños y VARIABLE #9: Edad)</strong><br>Validar que si:<br>Edad en meses < 12 variable 44 diferente a 1, 2<br>Edad en meses < 60 variable 44 diferente a 2',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'VPhMayor9Anios' => array(
				'descripcion' => '<strong>(VARIABLE #46: Virus del Papiloma Humano (VPH) y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 46 registre un dato diferente a 0 la variable 9 corresponda a >= 9 años ',
				'valido' => 'Virus del Papiloma Humano (VPH): 1 X Edad: 10 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Virus del Papiloma Humano (VPH): 1 X Edad: 5 años <br><span style="color:red">Cruce No Valido</span>',
			), 'VPhGenero' => array(
				'descripcion' => '<strong>(VARIABLE #46: Virus del Papiloma Humano (VPH) y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 46 registre un dato diferente a 0 la variable 10 corresponda a F.',
				'valido' => 'Virus del Papiloma Humano (VPH): 1 X Sexo: F <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Virus del Papiloma Humano (VPH): 1 X Sexo: M <br><span style="color:red">Cruce No Valido</span>',
			), 'TdOTtGenero' => array(
				'descripcion' => '<strong>(VARIABLE #47: TD o TT Mujeres en Edad Fértil 15 a 49 años y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 47 registre un dato diferente a 0 la variable 10 corresponda a F. ',
				'valido' => 'TD o TT Mujeres en Edad Fértil 15 a 49 años: 1 X Sexo: F <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'TD o TT Mujeres en Edad Fértil 15 a 49 años: 1 X Sexo: M <br><span style="color:red">Cruce No Valido</span>',
			), 'TdOTtMayor9Anios' => array(
				'descripcion' => '<strong>(VARIABLE #47: TD o TT Mujeres en Edad Fértil 15 a 49 años y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 46 registre un dato diferente a 0 la variable 9 corresponda a >= 9 años ',
				'valido' => 'TD o TT Mujeres en Edad Fértil 15 a 49 años: 1 X Edad: 10 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'TD o TT Mujeres en Edad Fértil 15 a 49 años: 1 X Edad: 5 años <br><span style="color:red">Cruce No Valido</span>',
			), 'placaBacterianaEdad' => array(
				'descripcion' => '<strong>(VARIABLE #48:Control de Placa Bacteriana y VARIABLE #9: Edad)</strong><br>Validar que cuando variable 9 registre < 2 años, variable 48 registre 0',
				'valido' => 'Control de Placa Bacteriana: 0 X Edad: 2 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Control de Placa Bacteriana: 1 X Edad: 2 años <br><span style="color:red">Cruce No Valido</span>',
			), 'edadFechaAtencionPartoCesaria' => array(
				'descripcion' => '<strong>(VARIABLE #49:Fecha atención parto o cesárea y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 49 registre un dato diferente a 1845-01-01 la variable 9 corresponda a >= 10 años y < 60 años',
				'valido' => 'Fecha atención parto o cesárea: 2019-01-01 X Edad: 25 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha atención parto o cesárea: 2019-01-01 X Edad: 9 años <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaPartoGenero' => array(
				'descripcion' => '<strong>(VARIABLE #49:Fecha atención parto o cesárea y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 49 registre un dato diferente a 1845-01-01 la variable 10 corresponda a F.',
				'valido' => 'Fecha atención parto o cesárea: 2019-01-01 X Sexo: F <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha atención parto o cesárea: 2019-01-01 X Sexo: M <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaAtencionPartoMenorFechaSalida' => array(
				'descripcion' => '<strong>(VARIABLE #49:Fecha atención parto o cesárea y VARIABLE #50: Fecha salida de la atención del parto o cesárea)</strong><br>Validar que si la variable 49 registra > 1900-01-01, el dato registrado sea <= al de la variable 50',
				'valido' => 'Fecha atención parto o cesárea: 2019-01-01 X Fecha salida de la atención del parto o cesárea: 2019-01-05 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha atención parto o cesárea: 2019-01-01 X Fecha salida de la atención del parto o cesárea: 2018-12-31 <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaAtencionPartoNullFechaSalida' => array(
				'descripcion' => '<strong>(VARIABLE #49:Fecha atención parto o cesárea y VARIABLE #50: Fecha salida de la atención del parto o cesárea)</strong><br>Validar que si la variable 49 registra diferente a 1845-01-01 la variable 50 registre diferente a 1845-01-01',
				'valido' => 'Fecha atención parto o cesárea: 2019-01-01 X Fecha salida de la atención del parto o cesárea: 2019-01-05 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha atención parto o cesárea: 2019-01-01 X Fecha salida de la atención del parto o cesárea: 1845-01-01 <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaPartoGestacion' => array(
				'descripcion' => '<strong>(VARIABLE #49:Fecha atención parto o cesárea y VARIABLE #14: Gestacion)</strong><br>Validar que cuando la variable 49 registre un dato diferente a 1845-01-01 la variable 14 corresponda a 2',
				'valido' => 'Fecha atención parto o cesárea: 2019-01-01 X Gestacion: 2 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha atención parto o cesárea: 2019-01-01 X Gestacion: 0 <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaAtencionPartoVariablesExtras' => array(
				'descripcion' => '<strong>(VARIABLE #49:Fecha atención parto o cesárea y VARIABLES EXTRAS)</strong><br>Validar que si la variable 49 registra > 1900-01-01, las siguientes variables deben registrar:<br><br>variable 14 sea 2<br>variable 15 sea 0<br>variable 16 sea 0<br>variable 33 sea 1845-01-01<br>variable 56 sea 1845-01-01<br>variable 57 sea 0<br>variable 58 sea 1845-01-01<br>variable 59 sea 0<br>variable 60 sea 0<br>variable 61 sea 0<br>variable 78 sea 1845-01-01<br>variable 79 sea 0<br>variable 115 sea 0',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'edadFechaSalidaAtencionPartoCesaria' => array(
				'descripcion' => '<strong>(VARIABLE #50:Fecha salida de la atención del parto o cesárea y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 50 registre un dato diferente a 1845-01-01 la variable 9 corresponda a >= 10 años y < 60 años',
				'valido' => 'Fecha salida de la atención del parto o cesárea: 2019-01-01 X Edad: 25 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha salida de la atención del parto o cesárea: 2019-01-01 X Edad: 9 años <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaSalidaPartoGenero' => array(
				'descripcion' => '<strong>(VARIABLE #50:Fecha salida de la atención del parto o cesárea y VARIABLE #10: Sexp)</strong><br>Validar que cuando la variable 50 registre un dato diferente a 1845-01-01 la variable 10 corresponda a F.',
				'valido' => 'Fecha salida de la atención del parto o cesárea: 2019-01-01 X Sexo: F <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha salida de la atención del parto o cesárea: 2019-01-01 X Sexo: M <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaSalidaPartoGestacion' => array(
				'descripcion' => '<strong>(VARIABLE #50:Fecha salida de la atención del parto o cesárea y VARIABLE #14: Gestacion)</strong><br>Validar que cuando la variable 50 registre un dato diferente a 1845-01-01 la variable 14 corresponda a 2',
				'valido' => 'Fecha salida de la atención del parto o cesárea: 2019-01-01 X Gestacion: 2 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha salida de la atención del parto o cesárea: 2019-01-01 X Gestacion: 0 <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaSalidaPartoMayorFechaAtencionParto' => array(
				'descripcion' => '<strong>(VARIABLE #50:Fecha salida de la atención del parto o cesárea y VARIABLE #49: Fecha atención parto o cesárea)</strong><br>Validar que si variable 50 registra un dato > 1900-01-01, sea >= a la variable 49',
				'valido' => 'Fecha salida de la atención del parto o cesárea: 2019-01-05 X Fecha atención parto o cesárea: 2019-01-01 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha salida de la atención del parto o cesárea: 2018-12-31 X Fecha atención parto o cesárea: 2019-01-01 <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaSalidaPartoNullFechaAtencionParto' => array(
				'descripcion' => '<strong>(VARIABLE #50: Fecha salida de la atención del parto o cesárea y VARIABLE #49:Fecha atención parto o cesárea)</strong><br>Validar que si la variable 50 registra diferente a 1845-01-01 la variable 49 registre diferente a 1845-01-01',
				'valido' => 'Fecha salida de la atención del parto o cesárea: 2019-01-01 X Fecha atención parto o cesárea: 2019-01-05 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha salida de la atención del parto o cesárea: 2019-01-01 X Fecha atención parto o cesárea: 1845-01-01 <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaSalidaAtencionPartoVariablesExtras' => array(
				'descripcion' => '<strong>(VARIABLE #50:Fecha salida de la atención del parto o cesárea y VARIABLES EXTRAS)</strong><br>Validar que si la variable 50 registra > 1900-01-01, las siguientes variables deben registrar: <br>variable 14 sea 2 <br>variable 15 sea 0 <br>variable 16 sea 0 <br>variable 33 sea 1845-01-01 <br>variable 56 sea 1845-01-01 <br>variable 57 sea 0 <br>variable 58 sea 1845-01-01 <br>variable 59 sea 0 <br>variable 60 sea 0 <br>variable 61 sea 0 <br>variable 78 sea 1845-01-01 <br>variable 79 sea 0 <br>variable 115 sea 0',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'edadFechaConsejeriaLactanciaMaternaDiferente1885' => array(
				'descripcion' => '<strong>(VARIABLE #51: Fecha de consejería en Lactancia Materna y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 51 registre un dato diferente a 1845-01-01 la variable 9 corresponda a >= 10 años y < 60 años',
				'valido' => 'Fecha de consejería en Lactancia Materna: 2019-01-01 X Edad: 25 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha de consejería en Lactancia Materna: 2019-01-01 X Edad: 9 años <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaConsejeriaLactanciaMaternaGenero' => array(
				'descripcion' => '<strong>(VARIABLE #51: Fecha de consejería en Lactancia Materna y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 51 registre un dato diferente a 1845-01-01 la variable 10 corresponda a F',
				'valido' => 'Fecha de consejería en Lactancia Materna: 2019-01-01 X Sexo: F <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha de consejería en Lactancia Materna: 2019-01-01 X Sexo: M <br><span style="color:red">Cruce No Valido</span>',
			), 'edadFechaControlRecienNacidoDiferente1885' => array(
				'descripcion' => '<strong>(VARIABLE #52: Control Recién Nacido y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 52 registre un dato diferente a 1845-01-01 el cálculo de la edad* debe ser menor a 30 días.',
				'valido' => 'Control Recién Nacido: 2019-01-01 X Edad: 30 dias <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Control Recién Nacido: 2019-01-01 X Edad: 35 dias <br><span style="color:red">Cruce No Valido</span>',
			), 'edadFechaPlanificacionFamiliarPrimeraVez' => array(
				'descripcion' => '<strong>(VARIABLE #53: Planificación Familiar Primera Vez y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 53 registre 1845-01-01, la variable 9 corresponda a < 10 años.',
				'valido' => 'Planificación Familiar Primera Vez: 2019-01-01 X Edad: 9 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Planificación Familiar Primera Vez: 2019-01-01 X Edad: 12 años <br><span style="color:red">Cruce No Valido</span>',
			), 'suministroMetodoAnticonseptivoFechaSuministro' => array(
				'descripcion' => '<strong>(VARIABLE #54: Suministro de Método Anticonceptivo y VARIABLE #55: Fecha de suministro de Método Anticonceptivo)</strong><br>Validar que cuando la variable 54 registre un valor diferente a 0, la variable 55 corresponda a un dato diferente a 1845-01-01',
				'valido' => 'Suministro de Método Anticonceptivo: 1 X Fecha de suministro de Método Anticonceptivo: 2019-01-01 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Suministro de Método Anticonceptivo: 1 X Fecha de suministro de Método Anticonceptivo: 1845-01-01 <br><span style="color:red">Cruce No Valido</span>',
			), 'suministroMetodoAnticonseptivoEdad' => array(
				'descripcion' => '<strong>(VARIABLE #54: Suministro de Método Anticonceptivo y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 54 registro un dato diferente a 0 la edad sea > 10 años y < 60 años',
				'valido' => 'Suministro de Método Anticonceptivo: 1 X Edad: 25 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Suministro de Método Anticonceptivo: 1 X Edad: 9 años <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaSuministroMetodoAnticonceptivoSMA' => array(
				'descripcion' => '<strong>(VARIABLE #55: Fecha de suministro de Método Anticonceptivo y VARIABLE #54: Suministro de Método Anticonceptivo)</strong><br>Validar que cuando variable 55 registre un dato diferente a 1845-01-01, variable 54 registre un valor diferente a 0',
				'valido' => 'Fecha de Suministro de Método Anticonceptivo: 2019-01-01 X Suministro de Método Anticonceptivo: 1 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha de Suministro de Método Anticonceptivo: 2019-01-01 X Suministro de Método Anticonceptivo: 0 <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaSuministroMetodoAnticonceptivoPFPV' => array(
				'descripcion' => '<strong>(VARIABLE #9: Edad y VARIABLE #53: Planificación Familiar Primera Vez)</strong><br>Validar que cuando variable 9 registre <10 años y >60 años, variable 53 registre 1845-01-01',
				'valido' => 'Edad: 9 años X Planificación Familiar Primera Vez: 1845-01-01 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Edad: 9 años X Planificación Familiar Primera Vez: 2019-01-01 <br><span style="color:red">Cruce No Valido</span>',
			), 'controlPrenatalPrimeraVezEdad' => array(
				'descripcion' => '<strong>(VARIABLE #56: Control Prenatal de Primera vez y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 56 registre un dato diferente a 1845-01-01 la variable 9 corresponda a >= 10 años y < a 60 años',
				'valido' => 'Control Prenatal de Primera vez: 2019-01-01 X Edad: 25 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Control Prenatal de Primera vez: 2019-01-01 X Edad: 9 años <br><span style="color:red">Cruce No Valido</span>',
			), 'controlPrenatalPrimeraVezGenero' => array(
				'descripcion' => '<strong>(VARIABLE #56: Control Prenatal de Primera vez y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 56 registre un dato diferente a 1845-01-01 la variable 10 corresponda a F',
				'valido' => 'Control Prenatal de Primera vez: 2019-01-01 X Sexo: F <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Control Prenatal de Primera vez: 2019-01-01 X Sexo: M <br><span style="color:red">Cruce No Valido</span>',
			), 'controlPrenatalPrimeraVezGestacion' => array(
				'descripcion' => '<strong>(VARIABLE #56: Control Prenatal de Primera vez y VARIABLE #14: Gestacion)</strong><br>Validar que cuando la variable 56 registre un dato diferente a 1845-01-01 la variable 14 corresponda a 1.',
				'valido' => 'Control Prenatal de Primera vez: 2019-01-01 X Gestacion: 1 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Control Prenatal de Primera vez: 2019-01-01 X Gestacion: 0 <br><span style="color:red">Cruce No Valido</span>',
			), 'controlPrenatalPrimeraVezMenorUCP' => array(
				'descripcion' => '<strong>(VARIABLE #56: Control Prenatal de Primera vez y VARIABLE #58: Ultimo Control Prenatal)</strong><br>Validar que si variable 56 registra un dato > 1900-01-01, el dato registrado sea < al dato de la variable 58',
				'valido' => 'Control Prenatal de Primera vez: 2019-01-01 X Ultimo Control Prenatal: 2019-01-05 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Control Prenatal de Primera vez: 2019-01-01 X Ultimo Control Prenatal: 2019-01-05 <br><span style="color:red">Cruce No Valido</span>',
			), 'edadControlPrenatalPrimeraVez' => array(
				'descripcion' => '<strong>(VARIABLE #56: Control Prenatal de Primera vez y VARIABLE #9: Edad)</strong><br>Validar que cuando variable 9 registre <10 años y >= 60 años, variable 56 registre 1845-01-01 ',
				'valido' => 'Control Prenatal de Primera vez: 1845-01-01 X Edad: 9 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Control Prenatal de Primera vez: 1845-01-01 X Edad: 9 años <br><span style="color:red">Cruce No Valido</span>',
			), 'controlPrenatalEdad' => array(
				'descripcion' => '<strong>(VARIABLE #57: Control Prenatal y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 57 registre un dato diferente a 0 la variable 9 corresponda a >= 10 años y < 60 años',
				'valido' => 'Control Prenatal: 1 X Edad: 25 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Control Prenatal: 1 X Edad: 9 años <br><span style="color:red">Cruce No Valido</span>',
			), 'controlPrenatalControles' => array(
				'descripcion' => '<strong>(VARIABLE #57: Control Prenatal)</strong><br>Validar que en variable 57 <= 25 controles',
				'valido' => 'Control Prenatal: 21<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Control Prenatal: 27<br><span style="color:red">Cruce No Valido</span>',
			), 'edadControlPrenatal' => array(
				'descripcion' => '<strong>(VARIABLE #57: Control Prenatal y VARIABLE #9: Edad)</strong><br>Validar que cuando variable 9 registre < 10 años y >= 60 años, variable 57 registre 0',
				'valido' => 'Control Prenatal: 0 X Edad: 8 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Control Prenatal: 1 X Edad: 8 años <br><span style="color:red">Cruce No Valido</span>',
			), 'controlPrenatalGenero' => array(
				'descripcion' => '<strong>(VARIABLE #57: Control Prenatal y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 57 registre un dato diferente a 0 la variable 10 corresponda a F',
				'valido' => 'Control Prenatal: 0 X Sexo: F <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Control Prenatal: 1 X Sexo: M <br><span style="color:red">Cruce No Valido</span>',
			), 'controlPrenatalGestacion' => array(
				'descripcion' => '<strong>(VARIABLE #57: Control Prenatal y VARIABLE #14: Gestacion)</strong><br>Validar que cuando la variable 57 registre un dato diferente a 0 la variable 14 corresponda a 1.',
				'valido' => 'Control Prenatal: 0 X Gestacion: 1 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Control Prenatal: 1 X Gestacion: 0 <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaUltimoControlPrenatalEdad' => array(
				'descripcion' => '<strong>(VARIABLE #58: Último Control Prenatal y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 58 registre un dato diferente a 1845-01-01 la variable 9 corresponda a >= 10 años y < 60 años',
				'valido' => 'Último Control Prenatal: 2019-01-01 X Edad: 25 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Último Control Prenatal: 2019-01-01 X Edad: 9 años <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaUltimoControlPrenatalControlPrenatalPrimeraVez' => array(
				'descripcion' => '<strong>(VARIABLE #58: Último Control Prenatal y VARIABLE #56: Control Prenatal de Primera vez)</strong><br>Validar que si variable 58 registra > 1900-01-01, el dato registrado sea > al dato de la variable 56',
				'valido' => 'Último Control Prenatal: 2019-01-05 X Control Prenatal de Primera vez: 2019-01-01 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Último Control Prenatal: 2019-01-01 X Control Prenatal de Primera vez: 2019-01-05 <br><span style="color:red">Cruce No Valido</span>',
			), 'edadFechaUltimoControlPrenatal' => array(
				'descripcion' => '<strong>(VARIABLE #58: Último Control Prenatal y VARIABLE #9: Edad)</strong><br>Validar que cuando variable 9 registre <10 años y >= 60 años, variable 58 registre 1845-01-01',
				'valido' => 'Último Control Prenatal: 1845-01-01 X Edad: 9 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Último Control Prenatal: 1845-01-01 X Edad: 25 años <br><span style="color:red">Cruce No Valido</span>',
			), 'ultimoControlPrenatalGenero' => array(
				'descripcion' => '<strong>(VARIABLE #58: Último Control Prenatal y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 58 registre un dato diferente a 1845-01-01 la variable 10 corresponda a F.',
				'valido' => 'Último Control Prenatal: 2019-01-01 X Sexo: F <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Último Control Prenatal: 2019-01-01 X Sexo: M <br><span style="color:red">Cruce No Valido</span>',
			), 'ultimoControlPrenatalGestacion' => array(
				'descripcion' => '<strong>(VARIABLE #58: Último Control Prenatal y VARIABLE #14: Gestacion)</strong><br>Validar que cuando la variable 58 registre un dato diferente a 1845-01-01 la variable 14 corresponda a 1.',
				'valido' => 'Último Control Prenatal: 2019-01-01 X Gestacion: 1 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Último Control Prenatal: 2019-01-01 X Gestacion: 0 <br><span style="color:red">Cruce No Valido</span>',
			), 'suministroAcidoFolicoUltimoControlPrenatalEdad' => array(
				'descripcion' => '<strong>(VARIABLE #59: Suministro de Ácido Fólico en el Último Control Prenatal y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 59 registre un dato diferente a 0 la variable 9 corresponda a >= 10 años y < 60 años',
				'valido' => 'Suministro de Ácido Fólico en el Último Control Prenatal: 1 X Edad: 25 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Suministro de Ácido Fólico en el Último Control Prenatal: 1 X Edad: 9 años <br><span style="color:red">Cruce No Valido</span>',
			), 'edadSuministroAcidoFolicoUltimoControlPrenatal' => array(
				'descripcion' => '<strong>(VARIABLE #59: Suministro de Ácido Fólico en el Último Control Prenatal y VARIABLE #9: Edad)</strong><br>Validar que cuando variable 9 registre <10 años y >= 60 años, variable 59 registre 0',
				'valido' => 'Suministro de Ácido Fólico en el Último Control Prenatal: 0 X Edad: 9 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Suministro de Ácido Fólico en el Último Control Prenatal: 1 X Edad: 9 años <br><span style="color:red">Cruce No Valido</span>',
			), 'suministroAcidoFolicoUCPGenero' => array(
				'descripcion' => '<strong>(VARIABLE #59: Suministro de Ácido Fólico en el Último Control Prenatal y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 59 registre un dato diferente a 0 la variable 10 corresponda a F.',
				'valido' => 'Suministro de Ácido Fólico en el Último Control Prenatal: 1 X Sexo: F <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Suministro de Ácido Fólico en el Último Control Prenatal: 0 X Sexo: M <br><span style="color:red">Cruce No Valido</span>',
			), 'suministroAcidoFolicoUcpGestacion' => array(
				'descripcion' => '<strong>(VARIABLE #59: Suministro de Ácido Fólico en el Último Control Prenatal y VARIABLE #14: Gestacion)</strong><br>Validar que cuando la variable 59 registre un dato diferente a 0 la variable 14 corresponda a 1.',
				'valido' => 'Suministro de Ácido Fólico en el Último Control Prenatal: 1 X Gestacion: 1 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Suministro de Ácido Fólico en el Último Control Prenatal: 1 X Gestacion: 2 <br><span style="color:red">Cruce No Valido</span>',
			), 'sulfatoFerrosoUltimoControlPrenatalEdad' => array(
				'descripcion' => '<strong>(VARIABLE #60: Suministro de Sulfato Ferroso en el Último Control Prenatal  y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 60 registre un dato diferente a 0 la variable 9 corresponda a >= 10 años y < 60 años',
				'valido' => 'Suministro de Sulfato Ferroso en el Último Control Prenatal : 1 X Edad: 25 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Suministro de Sulfato Ferroso en el Último Control Prenatal : 1 X Edad: 9 años <br><span style="color:red">Cruce No Valido</span>',
			), 'edadSulfatoFerrosoUltimoControlPrenatal' => array(
				'descripcion' => '<strong>(VARIABLE #60: Suministro de Sulfato Ferroso en el Último Control Prenatal  y VARIABLE #9: Edad)</strong><br>Validar que cuando variable 9 registre <10 años y >= 60 años, variable 60 registre 0',
				'valido' => 'Suministro de Sulfato Ferroso en el Último Control Prenatal : 0 X Edad: 9 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Suministro de Sulfato Ferroso en el Último Control Prenatal : 1 X Edad: 9 años <br><span style="color:red">Cruce No Valido</span>',
			), 'suministroSulfatoFerrosoUCPGenero' => array(
				'descripcion' => '<strong>(VARIABLE #60: Suministro de Sulfato Ferroso en el Último Control Prenatal  y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 60 registre un dato diferente a 0 la variable 10 corresponda a F.',
				'valido' => 'Suministro de Sulfato Ferroso en el Último Control Prenatal : 1 X Sexo: F <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Suministro de Sulfato Ferroso en el Último Control Prenatal : 0 X Sexo: M <br><span style="color:red">Cruce No Valido</span>',
			), 'suministroSulfatoFerrosoUcpGestacion' => array(
				'descripcion' => '<strong>(VARIABLE #60: Suministro de Sulfato Ferroso en el Último Control Prenatal  y VARIABLE #14: Gestacion)</strong><br>Validar que cuando la variable 60 registre un dato diferente a 0 la variable 14 corresponda a 1.',
				'valido' => 'Suministro de Sulfato Ferroso en el Último Control Prenatal : 1 X Gestacion: 1 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Suministro de Sulfato Ferroso en el Último Control Prenatal : 1 X Gestacion: 2 <br><span style="color:red">Cruce No Valido</span>',
			), 'carbonatoCalcioUltimoControlPrenatalEdad' => array(
				'descripcion' => '<strong>(VARIABLE #61: Suministro de Carbonato de Calcio en el Último Control Prenatal   y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 61 registre un dato diferente a 0 la variable 9 corresponda a >= 10 años y < 60 años',
				'valido' => 'Suministro de Carbonato de Calcio en el Último Control Prenatal  : 1 X Edad: 25 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Suministro de Carbonato de Calcio en el Último Control Prenatal  : 1 X Edad: 9 años <br><span style="color:red">Cruce No Valido</span>',
			), 'edadCarbonatoCalcioUltimoControlPrenatal' => array(
				'descripcion' => '<strong>(VARIABLE #61: Suministro de Carbonato de Calcio en el Último Control Prenatal   y VARIABLE #9: Edad)</strong><br>Validar que cuando variable 9 registre <10 años y >= 60 años, variable 61 registre 0',
				'valido' => 'Suministro de Carbonato de Calcio en el Último Control Prenatal  : 0 X Edad: 9 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Suministro de Carbonato de Calcio en el Último Control Prenatal  : 1 X Edad: 9 años <br><span style="color:red">Cruce No Valido</span>',
			), 'suministroCarbonatoCalcioUCPGenero' => array(
				'descripcion' => '<strong>(VARIABLE #61: Suministro de Carbonato de Calcio en el Último Control Prenatal   y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 61 registre un dato diferente a 0 la variable 10 corresponda a F.',
				'valido' => 'Suministro de Carbonato de Calcio en el Último Control Prenatal  : 1 X Sexo: F <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Suministro de Carbonato de Calcio en el Último Control Prenatal  : 0 X Sexo: M <br><span style="color:red">Cruce No Valido</span>',
			), 'suministroCarbonatoCalcioGestacion' => array(
				'descripcion' => '<strong>(VARIABLE #61: Suministro de Carbonato de Calcio en el Último Control Prenatal   y VARIABLE #14: Gestacion)</strong><br>Validar que cuando la variable 61 registre un dato diferente a 0 la variable 14 corresponda a 1.',
				'valido' => 'Suministro de Carbonato de Calcio en el Último Control Prenatal  : 1 X Gestacion: 1 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Suministro de Carbonato de Calcio en el Último Control Prenatal  : 1 X Gestacion: 2 <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaDiagnosticoDesnutricionProteicoCaloricaObesidad' => array(
				'descripcion' => '<strong>(VARIABLE #64: Fecha Diagnóstico Desnutrición Proteico Calórica y VARIABLE #21: Obesidad o Desnutrición Proteico Calórica)</strong><br>Validar que cuando la variable 64 registre un valor diferente a 1845-01-01 la variable 21 corresponda a 2.',
				'valido' => 'Fecha Diagnóstico Desnutrición Proteico Calórica: 2019-01-01 X Obesidad o Desnutrición Proteico Calórica: 2 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha Diagnóstico Desnutrición Proteico Calórica: 2019-01-01 X Obesidad o Desnutrición Proteico Calórica: 1 <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaDiagnosticoDesnutricionProteicoCaloricaPeso' => array(
				'descripcion' => '<strong>(VARIABLE #64: Fecha Diagnóstico Desnutrición Proteico Calórica y VARIABLE #30: Peso en kilogramos)</strong><br>Validar que cuando la variable 64 registre un valor diferente a 1845-01-01 la variable 30 corresponda a un dato diferente a 999',
				'valido' => 'Fecha Diagnóstico Desnutrición Proteico Calórica: 2019-01-01 X Peso en kilogramos: 25 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha Diagnóstico Desnutrición Proteico Calórica: 2019-01-01 X Peso en kilogramos: 999 <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaDiagnosticoDesnutricionProteicoCaloricaTalla' => array(
				'descripcion' => '<strong>(VARIABLE #64: Fecha Diagnóstico Desnutrición Proteico Calórica y VARIABLE #32: Talla en centimetros)</strong><br>Validar que cuando la variable 64 registre un valor diferente a 1845-01-01 la variable 32 corresponda a un dato diferente a 999',
				'valido' => 'Fecha Diagnóstico Desnutrición Proteico Calórica: 2019-01-01 X Talla en centimetros: 155 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha Diagnóstico Desnutrición Proteico Calórica: 2019-01-01 X Talla en centimetros: 999 <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaConsultaMujerMenorVictimaMaltrato' => array(
				'descripcion' => '<strong>(VARIABLE #65: Consulta Mujer o Menor Víctima del Maltrato y VARIABLE #22: Víctima de Maltrato)</strong><br>Validar que cuando la variable 65 registre un valor diferente a 1845-01-01 la variable 22 corresponda a 1 o 2.',
				'valido' => 'Consulta Mujer o Menor Víctima del Maltrato: 2019-01-01 X Victima de Maltrato: 1 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Consulta Mujer o Menor Víctima del Maltrato: 2019-01-01 X Victima de Maltrato: 0 <br><span style="color:red">Cruce No Valido</span>',
			), 'fechaConsultaVictimaViolenciaSexual' => array(
				'descripcion' => '<strong>(VARIABLE #66: Consulta Víctimas de Violencia Sexual y VARIABLE #23: Víctima de Violencia Sexual)</strong><br>Validar que cuando la variable 66 registre un valor diferente a 1845-01-01 la variable 23 corresponda a 1.',
				'valido' => 'Consulta Víctimas de Violencia Sexual: 2019-01-01 X Víctima de Violencia Sexual: 1 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Consulta Víctimas de Violencia Sexual: 2019-01-01 X Víctima de Violencia Sexual: 0 <br><span style="color:red">Cruce No Valido</span>',
			), 'edadFechaConsultaCrecimientoDesarrolloPrimeraVez' => array(
				'descripcion' => '<strong>(VARIABLE #69: Consulta de Crecimiento y Desarrollo Primera vez y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 69 registre un valor diferente a 1845-01-01 el cálculo de la edad* debe ser menor a 10 años.',
				'valido' => 'Consulta de Crecimiento y Desarrollo Primera vez: 2019-01-01 X Edad: 9 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Consulta de Crecimiento y Desarrollo Primera vez: 2019-01-01 X Edad: 11 años <br><span style="color:red">Cruce No Valido</span>',
			), 'edadFechaSuministroSulfatoFerrosoUltimaConsultaMenor10Anios' => array(
				'descripcion' => '<strong>(VARIABLE #70: Suministro de Sulfato Ferroso en la Última Consulta del Menor de 10 años y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 70 registre un valor diferente a 0 el cálculo de la edad* debe ser menor a 10 años.',
				'valido' => 'Suministro de Sulfato Ferroso en la Última Consulta del Menor de 10 años: 1 X Edad: 9 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Suministro de Sulfato Ferroso en la Última Consulta del Menor de 10 años: 1 X Edad: 11 años <br><span style="color:red">Cruce No Valido</span>',
			), 'suministroSulfatoFerrosoUltimaConsultaMenor10AniosEdad' => array(
				'descripcion' => '<strong>(VARIABLE #70: Suministro de Sulfato Ferroso en la Última Consulta del Menor de 10 años y VARIABLE #9: Edad)</strong><br>Validar que cuando variable 9 registre >= 10 años variable 70 registre 0',
				'valido' => 'Suministro de Sulfato Ferroso en la Última Consulta del Menor de 10 años: 0 X Edad: 11 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Suministro de Sulfato Ferroso en la Última Consulta del Menor de 10 años: 1 X Edad: 11 años <br><span style="color:red">Cruce No Valido</span>',
			), 'edadFechaSuministroVitaminaAUltimaConsultaMenor10AniosDiferente0' => array(
				'descripcion' => '<strong>(VARIABLE #71: Suministro de Vitamina A en la Última Consulta del Menor de 10 años y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 71 registre un valor diferente a 0 el cálculo de la edad* debe ser menor a 10 años.',
				'valido' => 'Suministro de Vitamina A en la Última Consulta del Menor de 10 años: 0 X Edad: 11 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Suministro de Vitamina A en la Última Consulta del Menor de 10 años: 1 X  Edad: 11 años <br><span style="color:red">Cruce No Valido</span>',
			), 'suministroVitaminaAUltimaConsultaMenor10AniosEdad' => array(
				'descripcion' => '<strong>(VARIABLE #71: Suministro de Vitamina A en la Última Consulta del Menor de 10 años y VARIABLE #9: Edad)</strong><br>Validar que cuando variable 9 registre >= 10 años variable 71 registre 0',
				'valido' => 'Suministro de Vitamina A en la Última Consulta del Menor de 10 años: 0 X Edad: 15 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Suministro de Vitamina A en la Última Consulta del Menor de 10 años: 1 X  Edad: 15 años <br><span style="color:red">Cruce No Valido</span>',
			), 'consultaJovenPrimeraVezEdad' => array(
				'descripcion' => '<strong>(VARIABLE #72: Consulta de Joven Primera Vez y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 72 registre un dato diferente a 1845-01-01 la variable 9 corresponda a >= 10 años a < 30 años',
				'valido' => 'Consulta de Joven Primera Vez: 2019-01-01 X Edad: 15 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Consulta de Joven Primera Vez: 2019-01-01 X Edad: 35 años <br><span style="color:red">Cruce No Valido</span>',
			), 'edadConsultaJovenPrimeraVez' => array(
				'descripcion' => '<strong>(VARIABLE #72: Consulta de Joven Primera Vez y VARIABLE #9: Edad)</strong><br>Validar que si variable 9 es < 10 años y registre 1845-01-01',
				'valido' => 'Consulta de Joven Primera Vez: 1845-01-01 X Edad: 9 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Consulta de Joven Primera Vez: 1845-01-01 X Edad: 35 años <br><span style="color:red">Cruce No Valido</span>',
			), 'consultaAdultoPrimeraVezEdad' => array(
				'descripcion' => '<strong>(VARIABLE #73: Consulta de adulto de primera vez y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 73 registre un dato diferente a 1845-01-01 la variable 9 corresponda a >= 45años',
				'valido' => 'Consulta de adulto de primera vez: 2019-01-01 X Edad: 47 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Consulta de adulto de primera vez: 2019-01-01 X Edad: 35 años <br><span style="color:red">Cruce No Valido</span>',
			), 'edadConsultaAdultoPrimeraVez' => array(
				'descripcion' => '<strong>(VARIABLE #73: Consulta de adulto de primera vez y VARIABLE #9: Edad)</strong><br>Validar que cuando variable 9 registre < 45 años variable 73 registre 1845-01-01',
				'valido' => 'Consulta de adulto de primera vez: 1845-01-01 X Edad: 35 años <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Consulta de adulto de primera vez: 1845-01-01 X Edad: 47 años <br><span style="color:red">Cruce No Valido</span>',
			), 'consultaAdultoPrimeraVezRangosEdad' => array(
				'descripcion' => '<strong>(VARIABLE #73: Consulta de adulto de primera vez y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 73 registre un dato mayor a 1900- 01-01, la variable 9 corresponda a 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100 y más año',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'preservativosEntregadosPacConITS' => array(
				'descripcion' => '<strong>(VARIABLE #74: Preservativos entregados a pacientes con ITS)</strong><br>Validar que si la variable 74 registra diferente a 0 ó 999, el valor sea < 150',
				'valido' => 'Preservativos entregados a pacientes con ITS: 20 <br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Preservativos entregados a pacientes con ITS: 175<br><span style="color:red">Cruce No Valido</span>',
			), 'asesoriaPreTestElisaVIHMenorPostAsesoriaMismoTest' => array(
				'descripcion' => '<strong>(VARIABLE #75: Asesoría pre test Elisa para VIH y VARIABLE #76: Asesoría post test Elisa para VIH)</strong><br>Validar que si la variable 75 registra > 1900-01-01, el dato registrado sea < de la variable 76',
				'valido' => 'Asesoría pre test Elisa para VIH: 2019-01-01 X Asesoría post test Elisa para VIH: 2019-01-05<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Asesoría pre test Elisa para VIH: 2019-01-05 X Asesoría post test Elisa para VIH: 2019-01-01<br><span style="color:red">Cruce No Valido</span>',
			), 'asesoriaPreTestElisaVIHMenorFechaTomaTest' => array(
				'descripcion' => '<strong>(VARIABLE #75: Asesoría pre test Elisa para VIH y VARIABLE #82: Fecha de Toma de Elisa para VIH)</strong><br>Validar que si la variable 75 registra > 1900-01-01, el dato registrado sea < al dato de la variable 82',
				'valido' => 'Asesoría pre test Elisa para VIH: 2019-01-05 X Fecha de Toma de Elisa para VIH: 2019-01-01<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Asesoría pre test Elisa para VIH: 2019-01-01 X Fecha de Toma de Elisa para VIH: 2019-01-05<br><span style="color:red">Cruce No Valido</span>',
			), 'asesoriaPostTestMayorPreAsesoriaMismoTest' => array(
				'descripcion' => '<strong>(VARIABLE #76: Asesoría post test Elisa para VIH y VARIABLE #75: Asesoría pre test Elisa para VIH)</strong><br>Validar que si la variable 76 registra > 1900-01-01, el dato registrado sea > al dato de la variable 75',
				'valido' => 'Asesoría post test Elisa para VIH: 2019-01-05 X Asesoría pre test Elisa para VIH: 2019-01-01<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Asesoría post test Elisa para VIH: 2019-01-01 X Asesoría pre test Elisa para VIH: 2019-01-05<br><span style="color:red">Cruce No Valido</span>',
			), 'asesoriaPostTestElisaVIHMayorFechaTomaTest' => array(
				'descripcion' => '<strong>(VARIABLE #76: Asesoría post test Elisa para VIH y VARIABLE #82: Fecha de Toma de Elisa para VIH)</strong><br>Validar que si la variable 76 registra > 1900-01-01, el dato registrado sea > al dato de la variable 82',
				'valido' => 'Asesoría post test Elisa para VIH: 2019-01-05 X Fecha de Toma de Elisa para VIH: 2019-01-01<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Asesoría post test Elisa para VIH: 2019-01-01 X Fecha de Toma de Elisa para VIH: 2019-01-05<br><span style="color:red">Cruce No Valido</span>',
			), 'diagnosticoPacienteEnfermedadMental' => array(
				'descripcion' => '<strong>(VARIABLE #77: Paciente con Diagnóstico de: Ansiedad, Depresión, Esquizofrenia, déficit de atención, consumo SPA y Bipolaridad recibió Atención en los últimos 6 meses por Equipo Interdisciplinario Completo y VARIABLE #25: Enfermedad Mental)</strong><br>Validar que cuando la variable 77 registre un dato diferente a 0 la variable 25 corresponda a 1, 2, 3, 4, 5 o 6.',
				'valido' => 'VARIABLE #77: 1 X Enfermedad Mental: 1<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'VARIABLE #77: 1 X Enfermedad Mental: 0<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaAntigenoSuperficieHepatitisBGestantesEdad' => array(
				'descripcion' => '<strong>(VARIABLE #78:Fecha Antígeno de Superficie Hepatitis B en Gestante y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 78 registre un dato diferente a 1845-01-01la variable 9 corresponda a >= 10 años y a < 60 años',
				'valido' => 'Fecha Antígeno de Superficie Hepatitis B en Gestante: 2019-01-01 X Edad: 25 años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha Antígeno de Superficie Hepatitis B en Gestante: 2019-01-01 X Edad: 9 años<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaAntigenoSuperficieHepatitisBGestantesResultado' => array(
				'descripcion' => '<strong>(VARIABLE #78:Fecha Antígeno de Superficie Hepatitis B en Gestante y VARIABLE #79: Resultado Antígeno de Superficie Hepatitis B en Gestantes)</strong><br>Validar que cuando la variable 78 registre un dato diferente a 1845-01-01, 1805-01-01,1810-01-01, 1825-01-01,1830-01-01,1835-01-01 la variable 79 corresponda a 1,2 o 22',
				'valido' => 'Fecha Antígeno de Superficie Hepatitis B en Gestante: 2019-01-01 X Resultado Antígeno de Superficie Hepatitis B en Gestantes: 1<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha Antígeno de Superficie Hepatitis B en Gestante: 2019-01-01 X Resultado Antígeno de Superficie Hepatitis B en Gestantes: 0<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaAntigenoSuperficieHepatitisBGestantesGenero' => array(
				'descripcion' => '<strong>(VARIABLE #78:Fecha Antígeno de Superficie Hepatitis B en Gestante y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 78 registre un dato diferente a 1845-01-01 la variable 10 corresponda a F.',
				'valido' => 'Fecha Antígeno de Superficie Hepatitis B en Gestante: 2019-01-01 X Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha Antígeno de Superficie Hepatitis B en Gestante: 2019-01-01 X Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaAntigenoSuperficieHepatitisBGestantesGestacion' => array(
				'descripcion' => '<strong>(VARIABLE #78:Fecha Antígeno de Superficie Hepatitis B en Gestante y VARIABLE #14: Gestacion)</strong><br>Validar que cuando la variable 78 registre un dato diferente a 1845-01-01 la variable 14 corresponda a 1.',
				'valido' => 'Fecha Antígeno de Superficie Hepatitis B en Gestante: 2019-01-01 X Gestacion: 1<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha Antígeno de Superficie Hepatitis B en Gestante: 2019-01-01 X Gestacion: 0<br><span style="color:red">Cruce No Valido</span>',
			), 'resultadoAntigenoSuperficieHepatitisBGestantesEdad' => array(
				'descripcion' => '<strong>(VARIABLE #79:Resultado Antígeno de Superficie Hepatitis B en Gestantes y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 79 registre un dato diferente a 0 la variable 9 corresponda a >= 10 años y a < 60 años',
				'valido' => 'Resultado Antígeno de Superficie Hepatitis B en Gestantes: 1 X Edad: 30 años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Resultado Antígeno de Superficie Hepatitis B en Gestantes: 1 X Edad: 9 años<br><span style="color:red">Cruce No Valido</span>',
			), 'resultadoAntigenoSuperficieHepatitisBGestantesFechaAntigeno' => array(
				'descripcion' => '<strong>(VARIABLE #79:Resultado Antígeno de Superficie Hepatitis B en Gestantes y VARIABLE #78: Fecha Antígeno de Superficie Hepatitis B en Gestantes)</strong><br>Validar que cuando la variable 79 registre un dato diferente a 0 la variable 78 sea igual a 1800-01-01 o > 1900-01-01',
				'valido' => 'Resultado Antígeno de Superficie Hepatitis B en Gestantes: 1 X  Fecha Antígeno de Superficie Hepatitis B en Gestantes: 2019-01-01<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Resultado Antígeno de Superficie Hepatitis B en Gestantes: 1 X  Fecha Antígeno de Superficie Hepatitis B en Gestantes: 1899-01-01<br><span style="color:red">Cruce No Valido</span>',
			), 'resultadoAntigenoSuperficieHepatitisBGestantesGenero' => array(
				'descripcion' => '<strong>(VARIABLE #79:Resultado Antígeno de Superficie Hepatitis B en Gestantes y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 79 registre un dato diferente a 0 la variable 10 corresponda a F.',
				'valido' => 'Resultado Antígeno de Superficie Hepatitis B en Gestantes: 1 X  Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Resultado Antígeno de Superficie Hepatitis B en Gestantes: 1 X  Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'resultadoAntigenoSuperficieHepatitisBGestantesGestacion' => array(
				'descripcion' => '<strong>(VARIABLE #79:Resultado Antígeno de Superficie Hepatitis B en Gestantes y VARIABLE #14: Gestaciones)</strong><br>Validar que cuando la variable 79 registre un dato diferente a 0 la variable 10 corresponda a F.',
				'valido' => 'Resultado Antígeno de Superficie Hepatitis B en Gestantes: 1 X  Gestaciones: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Resultado Antígeno de Superficie Hepatitis B en Gestantes: 1 X  Gestaciones: M<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaSerologiaSifilisResultadoSerologiaSifilis' => array(
				'descripcion' => '<strong>(VARIABLE #80:Fecha de serología para sífilis y VARIABLE #81:Resultado serología para sífilis )</strong><br>Validar que cuando la variable 81 registre un dato diferente a 0 la variable 80 sea igual a 1800-01-01 o > 1900-01-01',
				'valido' => 'Fecha de serología para sífilis: 2019-01-01 X  Resultado serología para sífilis: 1<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => 'Fecha de serología para sífilis: 1899-01-01 X  Resultado serología para sífilis: 1<br><span style="color:red">Cruce No Valido</span>',
			), 'resultadoSerologiaSifilisFechaSerologiaSifilis' => array(
				'descripcion' => '<strong>(VARIABLE #81:Resultado serología para sífilis y VARIABLE #80:Fecha de serología para sífilis)</strong><br>Validar que cuando la variable 80 registre un dato diferente a 1845-01-01, 1805-01-01,1810-01-01, 1825-01-01,1830-01-01,1835-01-01 la variable 81 corresponda a 1,2 o 2',
				'valido' => ' Resultado serología para sífilis: 1 X Fecha de serología para sífilis: 2019-01-01<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Resultado serología para sífilis: 0 X Fecha de serología para sífilis: 1899-01-01<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaTomaElisaVIHResultadoElisaVIH' => array(
				'descripcion' => '<strong>(VARIABLE #82: Fecha de toma de elisa para VIH y VARIABLE #83: Resultado elisa para VIH)</strong><br>Validar que cuando la variable 82 registre un dato diferente a 1845-01-01, 1805-01-01,1810 -01-01, 1825-01-01,1830-01- 01,1835-01-01 la variable 83 corresponda a 1,2 o 22',
				'valido' => ' Fecha de toma de elisa para VIH: 2019-01-01 X Resultado elisa para VIH: 1<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Fecha de toma de elisa para VIH: 2019-01-01 X Resultado elisa para VIH: 0<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaTomaElisaVIHMayorFechaAsesoriaPreTest' => array(
				'descripcion' => '<strong>(VARIABLE #82: Fecha de toma de elisa para VIH y VARIABLE #75: Asesoría pre test Elisa para VIH)</strong><br>Validar que si la variable 82 registra > 1900-01-01, el dato registrado sea > al dato de la variable 75',
				'valido' => ' Fecha de toma de elisa para VIH: 2019-01-05 X Asesoría pre test Elisa para VIH: 2019-01-01<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Fecha de toma de elisa para VIH: 2019-01-05 X Asesoría pre test Elisa para VIH: 2019-01-06<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaTomaElisaVIHMayorFechaAsesoriaPostTest' => array(
				'descripcion' => '<strong>(VARIABLE #82: Fecha de toma de elisa para VIH y VARIABLE #76: Asesoría post test Elisa para VIH)</strong><br>Validar que si la variable 82 registra > 1900-01-01, el dato registrado sea < al dato de la variable 76',
				'valido' => ' Fecha de toma de elisa para VIH: 2019-01-01 X Asesoría post test Elisa para VIH: 2019-01-05<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Fecha de toma de elisa para VIH: 2019-01-05 X Asesoría post test Elisa para VIH: 2019-01-01<br><span style="color:red">Cruce No Valido</span>',
			), 'resultadoElisaVIHFechaTomaTest' => array(
				'descripcion' => '<strong>(VARIABLE #83: Resultado elisa para VIH y VARIABLE #82: Fecha de toma de elisa para VIH)</strong><br>Validar que cuando la variable 83 registre un dato diferente a 0 la variable 82 sea igual a 1800-01-01 o > 1900-01-01',
				'valido' => ' Resultado elisa para VIH: 1 X Fecha de toma de elisa para VIH: 2019-01-05<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Resultado elisa para VIH: 1 X Fecha de toma de elisa para VIH: 1825-01-01<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaTSHNeonatalResultadoTest' => array(
				'descripcion' => '<strong>(VARIABLE #84: Fecha TSH Neonatal y VARIABLE #85: Resultado de TSH Neonatal)</strong><br>Validar que cuando la variable 84 registre un dato diferente a 1845-01-01, 1805-01-01,1810-01-01, 1825-01-01,1830-01-01,1835-01-01 la variable 85 corresponda a 1,2 o 22',
				'valido' => ' Fecha TSH Neonatal: 2019-01-01 X Resultado de TSH Neonatal: 1<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Fecha TSH Neonatal: 2019-01-01 X Resultado de TSH Neonatal: 0<br><span style="color:red">Cruce No Valido</span>',
			), 'resultadoTSHNeonatalFechaTomaTest' => array(
				'descripcion' => '<strong>(VARIABLE #85: Resultado de TSH Neonatal y VARIABLE #84: Fecha de TSH Neonatal)</strong><br>Validar que cuando la variable 85 registre un dato diferente a 0 la variable 84 sea igual a 1800-01-01 o > 1900-01-01',
				'valido' => ' Resultado de TSH Neonatal: 1 X Fecha de TSH Neonatal: 2019-01-01<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Resultado de TSH Neonatal: 1 X Fecha de TSH Neonatal: 1825-01-01<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaTamizajeCancerCuelloUterinoDiferenteCero' => array(
				'descripcion' => '<strong>(VARIABLE #86: Tamizaje Cáncer de Cuello Uterino y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 86 registre un valor diferente a 0 el  cálculo de la edad* debe ser mayor a 10 años',
				'valido' => ' Tamizaje Cáncer de Cuello Uterino: 1 X Edad: 25 años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Tamizaje Cáncer de Cuello Uterino: 1 X Edad: 9 años<br><span style="color:red">Cruce No Valido</span>',
			), 'tamizajeCancerCuelloUterinoGenero' => array(
				'descripcion' => '<strong>(VARIABLE #86: Tamizaje Cáncer de Cuello Uterino y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 86 registre un dato diferente a 0 la variable 10 corresponda a F.',
				'valido' => ' Tamizaje Cáncer de Cuello Uterino: 1 X Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Tamizaje Cáncer de Cuello Uterino: 1 X Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'edadFechaCitologiaCervicoUterina1845' => array(
				'descripcion' => '<strong>(VARIABLE #87: Citología Cérvico uterina y VARIABLE #88: Citología Cérvico uterina Resultados según Bethesda)</strong><br>Validar que cuando la variable 87 registre un dato diferente a 1845-01-01, 1805-01-01,1810-01-01, 1825-01-01,1830-01- 01,1835-01-01 la variable 88 corresponda a un dato diferente de 0',
				'valido' => ' Citología Cérvico uterina: 2019-01-01 X Citología Cérvico uterina Resultados según Bethesda: 1<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Citología Cérvico uterina: 2019-01-01 X Citología Cérvico uterina Resultados según Bethesda: 0<br><span style="color:red">Cruce No Valido</span>',
			), 'citologiaCervicoUterinaGenero' => array(
				'descripcion' => '<strong>(VARIABLE #87: Citología Cérvico uterina y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 87 registre un dato diferente a 1845-01-01 la variable 10 corresponda a F.',
				'valido' => ' Citología Cérvico uterina: 2019-01-01 X Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Citología Cérvico uterina: 2019-01-01 X Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaCalidadMuestraCitologiaCervicouterinaBethesdaDiferenteCero' => array(
				'descripcion' => '<strong>(VARIABLE #88: Citología Cérvico uterina Resultados según Bethesda y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 88 registre un valor diferente a 0 el cálculo de la edad* debe ser mayor a 10 años.',
				'valido' => ' Citología Cérvico uterina Resultados según Bethesda: 2019-01-01 X Edad: 25 años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Citología Cérvico uterina Resultados según Bethesda: 2019-01-01 X Edad: 9 años<br><span style="color:red">Cruce No Valido</span>',
			), 'resultadoCitologiaCervicoUterinaFechaCitologia' => array(
				'descripcion' => '<strong>(VARIABLE #88: Citología Cérvico uterina Resultados según Bethesda y VARIABLE #87: Citología Cérvico uterina)</strong><br>Validar que cuando variable 88 registre un dato diferente de 0 la variable 87 sea igual a 1800-01-01 o >1900-01-01',
				'valido' => ' Citología Cérvico uterina Resultados según Bethesda: 0 X Citología Cérvico uterina: 2019-01-01<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Citología Cérvico uterina Resultados según Bethesda: 0 X Citología Cérvico uterina: 1825-01-01<br><span style="color:red">Cruce No Valido</span>',
			), 'citologiaCervicoUterinaSegunBethesdaGenero' => array(
				'descripcion' => '<strong>(VARIABLE #88: Citología Cérvico uterina Resultados según Bethesda y VARIABLE #87: Sexo)</strong><br>Validar que cuando la variable 88 registre un dato diferente a 0 la variable 10 corresponda a F.',
				'valido' => ' Citología Cérvico uterina Resultados según Bethesda: 1 X Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Citología Cérvico uterina Resultados según Bethesda: 1 X Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaCalidadMuestraCitologiaCervicouterinaDiferenteCero' => array(
				'descripcion' => '<strong>(VARIABLE #89: Calidad en la Muestra de Citología Cervicouterina y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 89 registre un valor diferente a 0 el cálculo de la edad* debe ser mayor a 10 años',
				'valido' => ' Calidad en la Muestra de Citología Cervicouterina: 2019-01-01 X Edad: 25 años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Calidad en la Muestra de Citología Cervicouterina: 2019-01-01 X Edad: 9 años<br><span style="color:red">Cruce No Valido</span>',
			), 'calidadMuestraCitologiaCervicouterinaResultados' => array(
				'descripcion' => '<strong>(VARIABLE #89: Calidad en la Muestra de Citología Cervicouterina y VARIABLE #88: Citología Cérvico uterina Resultados según Bethesda)</strong><br>Validar que cuando variable 89 registre un dato diferente a 0 ó 999, la variable 88 registre un dato diferente 0',
				'valido' => ' Calidad en la Muestra de Citología Cervicouterina: 1 X Citología Cérvico uterina Resultados según Bethesda: 1<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Calidad en la Muestra de Citología Cervicouterina: 1 X Citología Cérvico uterina Resultados según Bethesda: 0<br><span style="color:red">Cruce No Valido</span>',
			), 'codigoIpsTomaCitologiaCervicoUterinaDB' => array(
				'descripcion' => '<strong>(VARIABLE #90: Código de habilitación IPS donde se toma Citología Cervicouterina.)</strong><br>Validadr que el código de habilitación IPS donde se toma Citología Cervicouterina exista en la base de datos',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'calidadMuestraCitologiaCervicoUterinaGenero' => array(
				'descripcion' => '<strong>(VARIABLE #89: Calidad en la Muestra de Citología Cervicouterina y VARIABLE #87: Sexo)</strong><br>Validar que cuando la variable 89 registre un dato diferente a 0 la variable 10 corresponda a F',
				'valido' => ' Calidad en la Muestra de Citología Cervicouterina: 1 X Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Calidad en la Muestra de Citología Cervicouterina: 1 X Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaCodigoHabilitacionIpsCitologiaCervicouterina' => array(
				'descripcion' => '<strong>(VARIABLE #90: Código de habilitación IPS donde se toma Citología Cervicouterina y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 90 registre un valor diferente a 0 el cálculo de la edad* debe ser mayor a 10 años.',
				'valido' => ' Código de habilitación IPS donde se toma Citología Cervicouterina: 1 X Edad: 25 años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Código de habilitación IPS donde se toma Citología Cervicouterina: 1 X Edad: 9 años<br><span style="color:red">Cruce No Valido</span>',
			), 'codigoIpsTomaCitologiaCervicoUterinaGenero' => array(
				'descripcion' => '<strong>(VARIABLE #90: Código de habilitación IPS donde se toma Citología Cervicouterina y VARIABLE #9: Sexo)</strong><br>Validar que cuando la variable 90 registre un dato diferente a 0 la variable 10 corresponda a F',
				'valido' => ' Código de habilitación IPS donde se toma Citología Cervicouterina: 1 X Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Código de habilitación IPS donde se toma Citología Cervicouterina: 1 X Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'codigoIpsTomaCitologiaCervicoUterinaMuestraTest' => array(
				'descripcion' => '<strong>(VARIABLE #90: Código de habilitación IPS donde se toma Citología Cervicouterina y VARIABLE #89: Calidad en la Muestra de Citología Cervicouterina)</strong><br>Validar que cuando la variable 90 registre un dato diferente a 0, la variable 89 registre un dato diferente a 0',
				'valido' => ' Código de habilitación IPS donde se toma Citología Cervicouterina: 1 X Calidad en la Muestra de Citología Cervicouterina: 1<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Código de habilitación IPS donde se toma Citología Cervicouterina: 1 X Calidad en la Muestra de Citología Cervicouterina: 0<br><span style="color:red">Cruce No Valido</span>',
			), 'edadFechaColposcopia' => array(
				'descripcion' => '<strong>(VARIABLE #91: Fecha Colposcopia  y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 91 registre un valor diferente a 1845-01-01 el cálculo de la edad* debe ser mayor a 10 años',
				'valido' => ' Fecha Colposcopia : 2019-01-01 X Edad: 25 años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Fecha Colposcopia : 2019-01-01 X Edad: 9 años<br><span style="color:red">Cruce No Valido</span>',
			), 'fechacolposcopiaGenero' => array(
				'descripcion' => '<strong>(VARIABLE #91: Fecha Colposcopia  y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 91 registre un dato diferente a 1845-01-01 la variable 10 corresponda a F',
				'valido' => ' Fecha Colposcopia : 2019-01-01 X Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Fecha Colposcopia : 2019-01-01 X Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'fechacolposcopiaCodigoIpsTomaTest' => array(
				'descripcion' => '<strong>(VARIABLE #91: Fecha Colposcopia  y VARIABLE #92: Código de habilitación IPS donde se toma Colposcopia)</strong><br>Validar que cuando la variable 91 registre un dato diferente a 1845-01-01, 1805-01-01,1810 -01-01, 1825-01-01,1830-01- 01,1835-01-01, la variable 92 corresponda a un dato diferente de 0',
				'valido' => ' Fecha Colposcopia : 2019-01-01 X Código de habilitación IPS donde se toma Colposcopia: 1<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Fecha Colposcopia : 2019-01-01 X Código de habilitación IPS donde se toma Colposcopia: 0<br><span style="color:red">Cruce No Valido</span>',
			), 'codigoHabilitacionIPSColposcopiaDB' => array(
				'descripcion' => '<strong>(VARIABLE #92: Código de habilitación IPS donde se toma Colposcopia)</strong><br>Validadr que el codigo de habilitación IPS donde se toma Colposcopia exista en la base de datos',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'fechaCodigoHabilitacionIPSColposcopia' => array(
				'descripcion' => '<strong>(VARIABLE #92: Código de habilitación IPS donde se toma Colposcopia y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 92 registre un valor diferente a 0 el cálculo de la edad* debe ser mayor a 10 años.',
				'valido' => ' Código de habilitación IPS donde se toma Colposcopia: 1 X Edad: 25 Años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Código de habilitación IPS donde se toma Colposcopia: 1 X Edad: 9 Años<br><span style="color:red">Cruce No Valido</span>',
			), 'codigoIpsTomaColposcopiaGenero' => array(
				'descripcion' => '<strong>(VARIABLE #92: Código de habilitación IPS donde se toma Colposcopia y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 92 registre un dato diferente a 0 la variable 10 corresponda a F.',
				'valido' => ' Código de habilitación IPS donde se toma Colposcopia: 1 X Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Código de habilitación IPS donde se toma Colposcopia: 1 X Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'codigoIpsTomaColposcopiaFechaColposcopia' => array(
				'descripcion' => '<strong>(VARIABLE #92: Código de habilitación IPS donde se toma Colposcopia y VARIABLE #91: Fecha Colposcopia)</strong><br>Validar que cuando la variable 92 registre un dato diferente de 0, la variable 91 registre a un dato diferente a 1845-01-01, 1805-01-01,1810-01-01, 1825-01-01,1830-01-01,1835-01-01',
				'valido' => ' Código de habilitación IPS donde se toma Colposcopia: 1 X Fecha Colposcopia: 2019-01-01<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Código de habilitación IPS donde se toma Colposcopia: 1 X Fecha Colposcopia: 1845-01-01<br><span style="color:red">Cruce No Valido</span>',
			), 'edadFechaBiopsiaCervical' => array(
				'descripcion' => '<strong>(VARIABLE #93: Fecha Biopsia Cervical y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 93 registre un valor diferente a 1845-01-01 el cálculo de la edad* debe ser mayor a 10 años.',
				'valido' => ' Fecha Biopsia Cervical: 2019-01-01 X Edad: 25 Años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Fecha Biopsia Cervical: 2019-01-01 X Edad: 9 Años<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaBiopsiaCervicalGenero' => array(
				'descripcion' => '<strong>(VARIABLE #93: Fecha Biopsia Cervical y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 93 registre un dato diferente a 1845-01-01 la variable 10 corresponda a F.',
				'valido' => ' Fecha Biopsia Cervical: 2019-01-01 X Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Fecha Biopsia Cervical: 2019-01-01 X Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaBiopsiaCervicalResultadoTest' => array(
				'descripcion' => '<strong>(VARIABLE #93: Fecha Biopsia Cervical y VARIABLE #94: Resultado de Biopsia Cervical)</strong><br>Validar que cuando la variable 93 registre un dato diferente a 1845-01-01, 1805-01-01,1810 -01-01, 1825-01-01,1830-01- 01,1835-01-01, la variable 94 corresponda a un dato diferente de 0',
				'valido' => ' Fecha Biopsia Cervical: 2019-01-01 X Resultado de Biopsia Cervical: 1<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Fecha Biopsia Cervical: 2019-01-01 X Resultado de Biopsia Cervical: 0<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaResultadoBiopsiaCervical' => array(
				'descripcion' => '<strong>(VARIABLE #94: Resultado de Biopsia Cervical y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 94 registre un valor diferente a 0 el cálculo de la edad* debe ser mayor a 10 años.',
				'valido' => ' Resultado de Biopsia Cervical: 1 X Edad: 25 Años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Resultado de Biopsia Cervical: 1 X Edad: 9 Años<br><span style="color:red">Cruce No Valido</span>',
			), 'resultadoBiopsiaCervicalGenero' => array(
				'descripcion' => '<strong>(VARIABLE #94: Resultado de Biopsia Cervical y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 94 registre un dato diferente a 0 la variable 10 corresponda a F.',
				'valido' => ' Resultado de Biopsia Cervical: 1 X Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Resultado de Biopsia Cervical: 1 X Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'resultadoBiopsiaCervicalFechaTest' => array(
				'descripcion' => '<strong>(VARIABLE #94: Resultado de Biopsia Cervical y VARIABLE #93: Fecha Biopsia Cervical)</strong><br>Validar que cuando la variable 94 registre un dato diferente de 0, la variable 93 registre a un dato diferente a 1845-01-01, 1805-01- 01,1810 -01-01, 1825-01-01,1830-01-01,1835-01-01',
				'valido' => ' Resultado de Biopsia Cervical: 1 X Fecha Biopsia Cervical: 2019-01-01<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Resultado de Biopsia Cervical: 1 X Fecha Biopsia Cervical: 1845-01-01<br><span style="color:red">Cruce No Valido</span>',
			), 'codigoHabilitacionIpsBiopsiaCervicalDB' => array(
				'descripcion' => '<strong>(VARIABLE #95: Código de habilitación IPS donde se toma Biopsia Cervical)</strong><br>Validadr que el Código de habilitación IPS donde se toma Biopsia Cervical exista en la base de datos',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'fechaCodigoHabilitacionIpsBiopsiaCervical' => array(
				'descripcion' => '<strong>(VARIABLE #95: Código de habilitación IPS donde se toma Biopsia Cervical y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 95 registre un valor diferente a 0 el cálculo de la edad* debe ser mayor 10 años.',
				'valido' => ' Código de habilitación IPS donde se toma Biopsia Cervical: 1 X Edad: 25 Años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Código de habilitación IPS donde se toma Biopsia Cervical: 1 X Edad: 9 Años<br><span style="color:red">Cruce No Valido</span>',
			), 'codigoIpsTomaBiopsiaCervicalGenero' => array(
				'descripcion' => '<strong>(VARIABLE #95: Código de habilitación IPS donde se toma Biopsia Cervical y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 95 registre un dato diferente a 0 la variable 10 corresponda a F.',
				'valido' => ' Código de habilitación IPS donde se toma Biopsia Cervical: 1 X Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Código de habilitación IPS donde se toma Biopsia Cervical: 1 X Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'codigoIpsTomaBiopsiaCervicalResultadoTest' => array(
				'descripcion' => '<strong>(VARIABLE #95: Código de habilitación IPS donde se toma Biopsia Cervical y VARIABLE #94: Resultado de Biopsia Cervical)</strong><br>Validar que cuando la variable 95 registre un dato diferente de 0, la variable 94 registre un dato diferente a 0',
				'valido' => ' Código de habilitación IPS donde se toma Biopsia Cervical: 1 X Resultado de Biopsia Cervical: 1<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Código de habilitación IPS donde se toma Biopsia Cervical: 1 X Resultado de Biopsia Cervical: 0<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaMamografiaGenero' => array(
				'descripcion' => '<strong>(VARIABLE #96: Fecha Mamografía y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 96 registre un dato diferente a 1845-01-01 la variable 10 corresponda a F.',
				'valido' => ' Fecha Mamografía: 2019-01-01 X Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Fecha Mamografía: 2019-01-01 X Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaMamografia' => array(
				'descripcion' => '<strong>(VARIABLE #96: Fecha Mamografía y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 96 registre un valor diferente a 1845-01-01 el cálculo de la edad* debe ser mayor a 35 años.',
				'valido' => ' Fecha Mamografía: 2019-01-01 X Edad: 40 años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Fecha Mamografía: 2019-01-01 X Edad: 30 años<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaMamografiaEdad' => array(
				'descripcion' => '<strong>(VARIABLE #96: Fecha Mamografía y VARIABLE #9: Edad)</strong><br>Validar que si variable 9 < 35 años variable 96 registre 1845-01-01',
				'valido' => ' Fecha Mamografía: 1845-01-01 X Edad: 30 años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Fecha Mamografía: 2019-01-01 X Edad: 30 años<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaResultadoMamografia' => array(
				'descripcion' => '<strong>(VARIABLE #97: Resultado Mamografía y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 97 registre un valor diferente a 0 el cálculo de la edad* debe ser mayor a 35años.',
				'valido' => ' Resultado Mamografía: 1 X Edad: 35 años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Resultado Mamografía: 1 X Edad: 30 años<br><span style="color:red">Cruce No Valido</span>',
			), 'resultadoMamografiaGenero' => array(
				'descripcion' => '<strong>(VARIABLE #97: Resultado Mamografía y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 97 registre un dato diferente a 0 la variable 10 corresponda a F.',
				'valido' => ' Resultado Mamografía: 1 X Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Resultado Mamografía: 1 X Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'resultadoMamografiaFechaEdad' => array(
				'descripcion' => '<strong>(VARIABLE #97: Resultado Mamografía y VARIABLE #9: Edad)</strong><br>Validar que si variable 9 < 35 años variable 97 registre 0',
				'valido' => ' Resultado Mamografía: 0 X Edad: 30 años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Resultado Mamografía: 1 X Edad: 30 años<br><span style="color:red">Cruce No Valido</span>',
			), 'resultadoMamografiaFechaTest' => array(
				'descripcion' => '<strong>(VARIABLE #97: Resultado Mamografía y VARIABLE #96: Fecha Mamografía )</strong><br>Validar que cuando la variable 97 registre un dato diferente de 0, la variable 96 registre a un dato diferente a 1845-01-01, 1805-01-01,1810-01-01, 1825-01-01,1830-01-01,1835-01-01',
				'valido' => ' Resultado Mamografía: 1 X Fecha Mamografía : 2019-01-01<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Resultado Mamografía: 1 X Fecha Mamografía : 1845-01-01<br><span style="color:red">Cruce No Valido</span>',
			), 'codigoHabilitacionIPSTomaMamografiaDB' => array(
				'descripcion' => '<strong>(VARIABLE #98: Código de habilitación IPS donde se toma Mamografía)</strong><br>Validadr que el Código de habilitación IPS donde se toma Mamografía exista en la base de datos',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'fechaCodigoHabilitacionIPSTomaMamografia' => array(
				'descripcion' => '<strong>(VARIABLE #98: Código de habilitación IPS donde se toma Mamografía y VARIABLE #9: Edad)</strong><br>Validar que cuando la variable 98 registre un valor diferente a 0 el cálculo de la edad* debe ser mayor a 35 años',
				'valido' => ' Código de habilitación IPS donde se toma Mamografía: 1 X Edad: 35 años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Código de habilitación IPS donde se toma Mamografía: 1 X Edad: 30 años<br><span style="color:red">Cruce No Valido</span>',
			), 'codigoIpsTomaMamografiaGenero' => array(
				'descripcion' => '<strong>(VARIABLE #98: Código de habilitación IPS donde se toma Mamografía y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 98 registre un dato diferente a 0 la variable 10 corresponda a F.',
				'valido' => ' Código de habilitación IPS donde se toma Mamografía: 1 X Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Código de habilitación IPS donde se toma Mamografía: 1 X Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'codigoIpsTomaMamografiaEdad' => array(
				'descripcion' => '<strong>(VARIABLE #98: Código de habilitación IPS donde se toma Mamografía y VARIABLE #9: Edad)</strong><br>Validar que si variable 9 < 35 años variable 98 registre 0',
				'valido' => ' Código de habilitación IPS donde se toma Mamografía: 0 X Edad: 30 años<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Código de habilitación IPS donde se toma Mamografía: 1 X Edad: 30 años<br><span style="color:red">Cruce No Valido</span>',
			), 'codigoIpsTomaMamografiaResultadoTest' => array(
				'descripcion' => '<strong>(VARIABLE #98: Código de habilitación IPS donde se toma Mamografía y VARIABLE #97: Resultado Mamografía)</strong><br>Validar que cuando la variable 98 registre un dato diferente de 0, la variable 97 registre a un dato diferente a 0',
				'valido' => ' Código de habilitación IPS donde se toma Mamografía: 1 X Resultado Mamografía: 1<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Código de habilitación IPS donde se toma Mamografía: 1 X Resultado Mamografía: 0<br><span style="color:red">Cruce No Valido</span>',
			), 'fechatomaBiopsiaSenoBACAFGenero' => array(
				'descripcion' => '<strong>(VARIABLE #99: Fecha Toma Biopsia Seno por BACAF y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 99 registre un dato diferente a 1845-01-01 la variable 10 corresponda a F.',
				'valido' => ' Fecha Toma Biopsia Seno por BACAF: 2019-01-01 X Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Fecha Toma Biopsia Seno por BACAF: 2019-01-01 X Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaResultadoBiopsiaSenoGenero' => array(
				'descripcion' => '<strong>(VARIABLE #100: Fecha Resultado Biopsia Seno  y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 100 registre un dato diferente a 1845-01-01 la variable 10 corresponda a F.',
				'valido' => ' Fecha Resultado Biopsia Seno : 2019-01-01 X Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Fecha Resultado Biopsia Seno : 2019-01-01 X Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaResultadoBiopsiaSenoResultado' => array(
				'descripcion' => '<strong>(VARIABLE #100: Fecha Resultado Biopsia Seno  y VARIABLE #101: Resultado Biopsia Seno)</strong><br>Validar que cuando la variable 100 registre un dato diferente a 1845-01-01, 1805-01 01,1810 -01-01, 1825-01-01,1830-01- 01,1835-01-01, la variable 101 corresponda a un dato diferente de 0',
				'valido' => ' Fecha Resultado Biopsia Seno : 2019-01-01 X Resultado Biopsia Seno: 1<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Fecha Resultado Biopsia Seno : 2019-01-01 X Resultado Biopsia Seno: 0<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaResultadoBiopsiaSenoMayorFechaTomaTest' => array(
				'descripcion' => '<strong>(VARIABLE #100: Fecha Resultado Biopsia Seno  y VARIABLE #99: Fecha Toma Biopsia Seno por BACAF)</strong><br>Validar que si variable 100 registra > 1900-01-01, el dato registrado sea > al dato de la variable 99 ',
				'valido' => ' Fecha Resultado Biopsia Seno : 2019-01-05 X Fecha Toma Biopsia Seno por BACAF: 2019-01-01<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Fecha Resultado Biopsia Seno : 2019-01-01 X Fecha Toma Biopsia Seno por BACAF: 2019-01-05<br><span style="color:red">Cruce No Valido</span>',
			), 'fResultadoBiopsiaSenoGenero' => array(
				'descripcion' => '<strong>(VARIABLE #101: Resultado Biopsia Seno y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 101 registre un dato diferente a 0 la variable 10 corresponda a F.',
				'valido' => ' Resultado Biopsia Seno: 1 X Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Resultado Biopsia Seno: 1 X Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'resultadoBiopsiaSenoResultadoTest' => array(
				'descripcion' => '<strong>(VARIABLE #101: Resultado Biopsia Seno  y VARIABLE #100: Fecha Resultado Biopsia Seno)</strong><br>Validar que cuando la variable 101 registre un dato diferente de 0, la variable 100 registre a un dato diferente a 1845-01-01, 1805-01-01,1810 -01-01, 1825-01-01,1830-01-01,1835-01-01',
				'valido' => ' Resultado Biopsia Seno : 1 X Fecha Resultado Biopsia Seno: 2019-01-01<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Resultado Biopsia Seno : 1 X Fecha Resultado Biopsia Seno: 1845-01-01<br><span style="color:red">Cruce No Valido</span>',
			), 'codigoIpsTomaBiopsiaSenoDB' => array(
				'descripcion' => '<strong>(VARIABLE #102:Código de habilitación IPS donde se toma Biopsia Seno )</strong><br>Validadr que elCódigo de habilitación IPS donde se toma Biopsia Seno  exista en la base de datos',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'codigoIpsTomaBiopsiaSenoGenero' => array(
				'descripcion' => '<strong>(VARIABLE #102: Código de habilitación IPS donde se toma Biopsia Seno y VARIABLE #10: Sexo)</strong><br>Validar que cuando la variable 102 registre un dato diferente a 0 la variable 10 corresponda a F',
				'valido' => ' Código de habilitación IPS donde se toma Biopsia Seno: 1 X Sexo: F<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Código de habilitación IPS donde se toma Biopsia Seno: 1 X Sexo: M<br><span style="color:red">Cruce No Valido</span>',
			), 'codigoIpsTomaBiopsiaSenoResultadoTomaTest' => array(
				'descripcion' => '<strong>(VARIABLE #102: Código de habilitación IPS donde se toma Biopsia Seno y VARIABLE #101: Resultado Biopsia Seno)</strong><br>Validar que cuando la variable 102 registre un dato diferente de 0, la variable 101 registre a un dato diferente a 0',
				'valido' => ' Código de habilitación IPS donde se toma Biopsia Seno: 1 X Resultado Biopsia Seno: 1<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Código de habilitación IPS donde se toma Biopsia Seno: 1 X Resultado Biopsia Seno: 0<br><span style="color:red">Cruce No Valido</span>',
			), 'hemoglobina' => array(
				'descripcion' => '<strong>(VARIABLE #104: Código de habilitación IPS donde se toma Biopsia Seno)</strong><br>Validar que cuando la variable 104 registre un dato diferente a 0 el valor registrado este en el rango de 1,5 y 20',
				'valido' => ' Código de habilitación IPS donde se toma Biopsia Seno: 5<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Código de habilitación IPS donde se toma Biopsia Seno: 25<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaCreatininaCreatinina' => array(
				'descripcion' => '<strong>(VARIABLE #106: fecha de creatinina  y VARIABLE #107: Creatinina)</strong><br>Validar que cuando variable 106 registre un dato igual a 1800-01-01 o > 1900-01-01, variable 107 registre un dato diferente a 0',
				'valido' => ' fecha de creatinina : 2019-01-05 X Creatinina: 1<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' fecha de creatinina : 2019-01-05 X Creatinina: 0<br><span style="color:red">Cruce No Valido</span>',
			), 'creatinina' => array(
				'descripcion' => '<strong>(VARIABLE #107: Creatinina)</strong><br>Validar que cuando la variable 107 registre un dato diferente a 0 o 999 el valor registrado este en el rango de 0,2 y 25.',
				'valido' => ' Creatinina: 5<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Creatinina: 35<br><span style="color:red">Cruce No Valido</span>',
			), 'creatininaFechaCreatinina' => array(
				'descripcion' => '<strong>(VARIABLE #106: fecha de creatinina  y VARIABLE #107: Creatinina)</strong><br>Validar que cuando variable 106 registre un dato igual a 1800-01-01 o > 1900-01-01, variable 107 registre un dato diferente a 0',
				'valido' => ' fecha de creatinina : 2019-01-05 X Creatinina: 1<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' fecha de creatinina : 2019-01-05 X Creatinina: 0<br><span style="color:red">Cruce No Valido</span>',
			), 'hemoglobinaGlicosilada' => array(
				'descripcion' => '<strong>(VARIABLE #109: Hemoglobina Glicosilada)</strong><br>Validar que cuando la variable 109 registre un dato diferente a 0 o 999 el valor registrado este en el rango de 5 y 20.',
				'valido' => ' Hemoglobina Glicosilada: 7<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' Hemoglobina Glicosilada: 25<br><span style="color:red">Cruce No Valido</span>',
			), 'fechaHemoglobinaGlicosilada' => array(
				'descripcion' => '<strong>(VARIABLE #106: fecha de creatinina y VARIABLE #107: Creatinina)</strong><br>Validar que cuando variable 106 registre un dato igual a 1800-01-01 o > 1900-01-01, variable 107 registre un dato diferente a 0',
				'valido' => ' fecha de creatinina : 2019-01-05 X Creatinina: 1<br><span style="color:teal">Cruce Valido</span>',
				'invalido' => ' fecha de creatinina : 2019-01-05 X Creatinina: 0<br><span style="color:red">Cruce No Valido</span>',
			), 'hemoglobinaGlicosiladaFechaHemoglobinaGlicosilada' => array(
				'descripcion' => 'Descripcion de la regla',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'fechaTomaBaciloscopiaDiagnosticoResultadoTest' => array(
				'descripcion' => 'Descripcion de la regla',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'resultadoBaciloscopiaDiagnosticoFechaTest' => array(
				'descripcion' => 'Descripcion de la regla',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'tratamientoParaHipotiroidismoCongenitoHipotiroidismo' => array(
				'descripcion' => 'Descripcion de la regla',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'tratamientoSifilisGestacionalGenero' => array(
				'descripcion' => 'Descripcion de la regla',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'tratamientoSifilisGestacionalGestacion' => array(
				'descripcion' => 'Descripcion de la regla',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'tratamientoSifilisGestacionalCongenitaOGestacional' => array(
				'descripcion' => 'Descripcion de la regla',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'tratamientoSifilisGestacionalEdad' => array(
				'descripcion' => 'Descripcion de la regla',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			), 'tratamientoSifilisCongenitaGestacionalCongenita' => array(
				'descripcion' => 'Descripcion de la regla',
				'valido' => 'Ejemplo valido',
				'invalido' => 'Ejemplo no valido',
			));
		return $reglasValidacion;
	}
}