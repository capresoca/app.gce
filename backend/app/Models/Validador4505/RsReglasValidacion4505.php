<?php

namespace App\Models\Validador4505;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class RsReglasValidacion4505 extends Model implements Auditable {

	protected $table = 'rs_reglas_validacion_4505';
	use \OwenIt\Auditing\Auditable;

	protected $fillable = [
		'existePrestadorServicioSaludInicioSesion',
		'longitudIdentificacionAfiliado',
		'primerApellidoAfiliado',
		'segundoApellidoAfiliado',
		'primerNombreAfiliado',
		'segundoNombreAfiliado',
		'fechaNacimientoAfiliado',
		'generoAfiliado',
		'pertenenciaEtnica',
		'ocupacion',
		'nivelEducativo',
		'edadGestacion',
		'gestacionGenero',
		'sifilisGestacionalCongenitaEdad',
		'sifilisGestacionalCongenitaEdadGeneroGestacion',
		'sifilisGestacionalCongenitaGenero',
		'sifilisGestacionalCongenitaGestacion',
		'sifilisGestacionalCongenitaInfeccionTransmisionSexual',
		'hipertensionInducidaGestacionEdadSi',
		'hipertensionInducidaGestacionGenero',
		'hipertensionInducidaGestacionGestacion',
		'hipotiroidismoCongenitoEdadSi',
		'hipotiroidismoCongenitoResultadoTSH',
		'hipotiroidismoCongenitoTratamiento',
		'sintomaticoRespiratorioBaciloscopiaDiagnostico',
		'lepraTratamientoLepra',
		'obsidadDesnutricionProteicoEdadSi',
		'obsidadDesnutricionProteicoFechaDiagnostico',
		'obsidadDesnutricionProteicoPeso',
		'obsidadDesnutricionProteicoTall',
		'victimaMaltratoEdadMenorSiMenor',
		'victimaMaltratoMujerVictima',
		'victimaMaltratoFechaConsultaMujerOMenorVictima',
		'victimaMaltratoEdadGeneral',
		'victimaViolenciaSexualFechaConsulta',
		'enfermedadMentalDiagnostico',
		'canverCervixGenero',
		'pesoKilogramos',
		'pesoFechaConsulta',
		'tallaCentimetros',
		'tallaFechaConsulta',
		'fechaProbablePartoGenero',
		'fechaProbablePartoGestacion',
		'fechaProbablePartoEdad',
		'edadGestacionalNacerDiferenteCero',
		'edadGestacionalNacerEdad',
		'edadGestacionalNacer43Semanas',
		'bcgEdadDiferenteNoAplica',
		'bcgMenor6Anios',
		'bcgMayor6Anios',
		'hepatitisBMenorNoAplica',
		'hepatitisBMenor1AnioMenor6Anios',
		'hepatitisBMenor1AnioMayor6Anios',
		'pentavalenteMenor1AnioNoAplica',
		'pentavalenteMenor1AnioMenor6Anios',
		'pentavalenteMenor1AnioMayor6Anios',
		'pentavalenteMenor4Meses',
		'pentavalenteMenor6Meses',
		'polioMenor1AnioNoAplica',
		'polioMenor1AnioMenor6Anios',
		'polioMenor1AnioMayor6Anios',
		'polioMenor1AnioMeses',
		'DPTMenor5AnioNoAplica',
		'DPTMenor5AnioMenor6Anios',
		'DPTMenor5AnioMayor6Anios',
		'DPTMenor5AnioMeses',
		'rotavirusNoAplica',
		'rotavirusMenor6Anios',
		'rotavirusMeses',
		'neumococoNoAplica',
		'neumococoMayor6Anios',
		'neumococoMenor6Anios',
		'neumococoMeses',
		'influenzaNiniosNoAplica',
		'influenzaNiniosMayor6Anios',
		'influenzaNiniosMenor6Anios',
		'influenzaNiniosMeses',
		'fiebreAmarilla1AnioNiniosNoAplica',
		'fiebreAmarilla1AnioNiniosMayor6Anios',
		'fiebreAmarilla1AnioNiniosMenor6Anios',
		'fiebreAmarilla1AnioNiniosMeses',
		'hepatitisANoAplica',
		'hepatitisAMayor6Anios',
		'hepatitisAMenor6Anios',
		'hepatitisAMeses',
		'tripleViralNiniosNoAplica',
		'tripleViralNiniosMayor6Anios',
		'tripleViralNiniosMenor6Anios',
		'tripleViralNiniosMeses',
		'VPhMayor9Anios',
		'VPhGenero',
		'TdOTtGenero',
		'TdOTtMayor9Anios',
		'placaBacterianaEdad',
		'edadFechaAtencionPartoCesaria',
		'fechaPartoGenero',
		'fechaAtencionPartoMenorFechaSalida',
		'fechaAtencionPartoNullFechaSalida',
		'fechaPartoGestacion',
		'fechaAtencionPartoVariablesExtras',
		'edadFechaSalidaAtencionPartoCesaria',
		'fechaSalidaPartoGenero',
		'fechaSalidaPartoGestacion',
		'fechaSalidaPartoMayorFechaAtencionParto',
		'fechaSalidaPartoNullFechaAtencionParto',
		'fechaSalidaAtencionPartoVariablesExtras',
		'edadFechaConsejeriaLactanciaMaternaDiferente1885',
		'fechaConsejeriaLactanciaMaternaGenero',
		'edadFechaControlRecienNacidoDiferente1885',
		'edadFechaPlanificacionFamiliarPrimeraVez',
		'suministroMetodoAnticonseptivoFechaSuministro',
		'suministroMetodoAnticonseptivoEdad',
		'fechaSuministroMetodoAnticonceptivoSMA',
		'fechaSuministroMetodoAnticonceptivoPFPV',
		'controlPrenatalPrimeraVezEdad',
		'controlPrenatalPrimeraVezGenero',
		'controlPrenatalPrimeraVezGestacion',
		'controlPrenatalPrimeraVezMenorUCP',
		'edadControlPrenatalPrimeraVez',
		'controlPrenatalEdad',
		'controlPrenatalControles',
		'edadControlPrenatal',
		'controlPrenatalGenero',
		'controlPrenatalGestacion',
		'fechaUltimoControlPrenatalEdad',
		'fechaUltimoControlPrenatalControlPrenatalPrimeraVez',
		'edadFechaUltimoControlPrenatal',
		'ultimoControlPrenatalGenero',
		'ultimoControlPrenatalGestacion',
		'suministroAcidoFolicoUltimoControlPrenatalEdad',
		'edadSuministroAcidoFolicoUltimoControlPrenatal',
		'suministroAcidoFolicoUCPGenero',
		'suministroAcidoFolicoUcpGestacion',
		'sulfatoFerrosoUltimoControlPrenatalEdad',
		'edadSulfatoFerrosoUltimoControlPrenatal',
		'suministroSulfatoFerrosoUCPGenero',
		'suministroSulfatoFerrosoUcpGestacion',
		'carbonatoCalcioUltimoControlPrenatalEdad',
		'edadCarbonatoCalcioUltimoControlPrenatal',
		'suministroCarbonatoCalcioUCPGenero',
		'suministroCarbonatoCalcioGestacion',
		'fechaDiagnosticoDesnutricionProteicoCaloricaObesidad',
		'fechaDiagnosticoDesnutricionProteicoCaloricaPeso',
		'fechaDiagnosticoDesnutricionProteicoCaloricaTalla',
		'fechaConsultaMujerMenorVictimaMaltrato',
		'fechaConsultaVictimaViolenciaSexual',
		'edadFechaConsultaCrecimientoDesarrolloPrimeraVez',
		'edadFechaSuministroSulfatoFerrosoUltimaConsultaMenor10Anios',
		'suministroSulfatoFerrosoUltimaConsultaMenor10AniosEdad',
		'edadFechaSuministroVitaminaAUltimaConsultaMenor10AniosDiferente0',
		'suministroVitaminaAUltimaConsultaMenor10AniosEdad',
		'consultaJovenPrimeraVezEdad',
		'edadConsultaJovenPrimeraVez',
		'consultaAdultoPrimeraVezEdad',
		'edadConsultaAdultoPrimeraVez',
		'consultaAdultoPrimeraVezRangosEdad',
		'preservativosEntregadosPacConITS',
		'asesoriaPreTestElisaVIHMenorPostAsesoriaMismoTest',
		'asesoriaPreTestElisaVIHMenorFechaTomaTest',
		'asesoriaPostTestMayorPreAsesoriaMismoTest',
		'asesoriaPostTestElisaVIHMayorFechaTomaTest',
		'diagnosticoPacienteEnfermedadMental',
		'fechaAntigenoSuperficieHepatitisBGestantesEdad',
		'fechaAntigenoSuperficieHepatitisBGestantesResultado',
		'fechaAntigenoSuperficieHepatitisBGestantesGenero',
		'fechaAntigenoSuperficieHepatitisBGestantesGestacion',
		'resultadoAntigenoSuperficieHepatitisBGestantesEdad',
		'resultadoAntigenoSuperficieHepatitisBGestantesFechaAntigeno',
		'resultadoAntigenoSuperficieHepatitisBGestantesGenero',
		'resultadoAntigenoSuperficieHepatitisBGestantesGestacion',
		'fechaSerologiaSifilisResultadoSerologiaSifilis',
		'resultadoSerologiaSifilisFechaSerologiaSifilis',
		'fechaTomaElisaVIHResultadoElisaVIH',
		'fechaTomaElisaVIHMayorFechaAsesoriaPreTest',
		'fechaTomaElisaVIHMayorFechaAsesoriaPostTest',
		'resultadoElisaVIHFechaTomaTest',
		'fechaTSHNeonatalResultadoTest',
		'resultadoTSHNeonatalFechaTomaTest',
		'fechaTamizajeCancerCuelloUterinoDiferenteCero',
		'tamizajeCancerCuelloUterinoGenero',
		'edadFechaCitologiaCervicoUterina1845',
		'citologiaCervicoUterinaGenero',
		'fechaCalidadMuestraCitologiaCervicouterinaBethesdaDiferenteCero',
		'resultadoCitologiaCervicoUterinaFechaCitologia',
		'citologiaCervicoUterinaSegunBethesdaGenero',
		'fechaCalidadMuestraCitologiaCervicouterinaDiferenteCero',
		'calidadMuestraCitologiaCervicouterinaResultados',
		'calidadMuestraCitologiaCervicoUterinaGenero',
		'fechaCodigoHabilitacionIpsCitologiaCervicouterina',
		'codigoIpsTomaCitologiaCervicoUterinaGenero',
		'codigoIpsTomaCitologiaCervicoUterinaMuestraTest',
		'edadFechaColposcopia',
		'fechacolposcopiaGenero',
		'fechacolposcopiaCodigoIpsTomaTest',
		'codigoHabilitacionIPSColposcopiaDB',
		'fechaCodigoHabilitacionIPSColposcopia',
		'codigoIpsTomaColposcopiaGenero',
		'codigoIpsTomaColposcopiaFechaColposcopia',
		'edadFechaBiopsiaCervical',
		'fechaBiopsiaCervicalGenero',
		'fechaBiopsiaCervicalResultadoTest',
		'fechaResultadoBiopsiaCervical',
		'resultadoBiopsiaCervicalGenero',
		'resultadoBiopsiaCervicalFechaTest',
		'codigoHabilitacionIpsBiopsiaCervicalDB',
		'fechaCodigoHabilitacionIpsBiopsiaCervical',
		'codigoIpsTomaBiopsiaCervicalGenero',
		'codigoIpsTomaBiopsiaCervicalResultadoTest',
		'fechaMamografiaGenero',
		'fechaMamografia',
		'fechaMamografiaEdad',
		'fechaResultadoMamografia',
		'resultadoMamografiaGenero',
		'resultadoMamografiaFechaEdad',
		'resultadoMamografiaFechaTest',
		'codigoHabilitacionIPSTomaMamografiaDB',
		'fechaCodigoHabilitacionIPSTomaMamografia',
		'codigoIpsTomaMamografiaGenero',
		'codigoIpsTomaMamografiaEdad',
		'codigoIpsTomaMamografiaResultadoTest',
		'fechatomaBiopsiaSenoBACAFGenero',
		'fechaResultadoBiopsiaSenoGenero',
		'fechaResultadoBiopsiaSenoResultado',
		'fechaResultadoBiopsiaSenoMayorFechaTomaTest',
		'fResultadoBiopsiaSenoGenero',
		'resultadoBiopsiaSenoResultadoTest',
		'codigoIpsTomaBiopsiaSenoDB',
		'codigoIpsTomaBiopsiaSenoGenero',
		'codigoIpsTomaBiopsiaSenoResultadoTomaTest',
		'hemoglobina',
		'fechaCreatininaCreatinina',
		'creatinina',
		'creatininaFechaCreatinina',
		'hemoglobinaGlicosilada',
		'fechaHemoglobinaGlicosilada',
		'hemoglobinaGlicosiladaFechaHemoglobinaGlicosilada',
		'fechaTomaBaciloscopiaDiagnosticoResultadoTest',
		'resultadoBaciloscopiaDiagnosticoFechaTest',
		'tratamientoParaHipotiroidismoCongenitoHipotiroidismo',
		'tratamientoSifilisGestacionalGenero',
		'tratamientoSifilisGestacionalGestacion',
		'tratamientoSifilisGestacionalCongenitaOGestacional',
		'tratamientoSifilisGestacionalEdad',
		'tratamientoSifilisCongenitaGestacionalCongenita',
	];
	protected $hidden = ['created_at', 'updated_at'];
}