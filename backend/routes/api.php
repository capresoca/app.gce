<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
return $request->user();
});
 */
Route::post('reporte/probar', 'ReporteController@probarSQL');

//Ro
//Route::get('index','TestController@prueba');
Route::post('login', 'AuthController@login');
Route::post('refresh', 'AuthController@refresh');


//Rutas Firmadas(Dejar fuera de Auth)
Route::post('reporte/ejecutar', 'ReporteController@ejecutarSQL')->name('ejecutarReporte')->middleware('signed');
Route::get('formafiliacion/pdf_formafiliacion/{formafiliacion}', 'Aseguramiento\FormafiliacionController@getPdf')->name('pdf_formafiliacion')->middleware('signed');
Route::get('novedadtramites/pdf/{novedad}', 'Aseguramiento\NovtramiteController@getPdf')->name('pdf_novedad')->middleware('signed');

Route::get('pqrsds/reporte', 'AtencionUsuario\PqrsdController@getReport')->name('reportePqrs');
Route::get('bduafiles/download', 'Aseguramiento\BDUAArchivoController@downloadFile')->name('bdua_file');
Route::get('bduaarchivos/download/{bduaarchivo}', 'Aseguramiento\BDUAArchivoController@download')->name('bdua_archivo')->middleware('signed');
Route::get('oj_tutelas/reporte/', 'OficinaJuridica\TutelaController@reporteTutela')->name('reporteTutela')->middleware('signed');
Route::get('rips/reporte/', 'Riips\RiipsRadicadoController@reporteRip')->name('reporteRip')->middleware('signed'); // RJTP
Route::get('cxps/pdf/{pg_cxp}', 'Pagos\PgCxpController@getPDF')->name('pdf_pgCxp')->middleware('signed');
Route::get('comdiarios/pdf/{comdiario}', 'Niif\ComdiarioController@getPDF')->name('comdiario')->middleware('signed');
Route::get('encuestas/pdf', 'AtencionUsuario\EncuestaController@getPdf')->name('encuesta_salud')->middleware('signed');
Route::get('cm_radicadas/pdf', 'CuentasMedicas\RadicadoController@getPdfRadicado')->name('documeto_radicado')->middleware('signed');
Route::get('cm_auditorias/pdf', 'CuentasMedicas\FacturaController@getPdfInforme')->name('informe_sosalud')->middleware('signed');
Route::get('informe_glosas/reporte', 'CuentasMedicas\FacturaController@getReporteGlosas')->name('reporte_glosas')->middleware('signed');
Route::get('au_referencias/pdf', 'AtencionUsuario\CentroRegulador\ReferenciaController@getPdf')->name('reporte_bitacora')->middleware('signed');
Route::get('au_autorizaciones/pdf', 'Autorizaciones\AuAnexoTecnico3Controller@getPdf')->name('autorizacione')->middleware('signed');
Route::get('autorizaciones_reporte/usuario', 'Autorizaciones\AuAnexoTecnico3Controller@getInformeEstadisticaUsuario')->name('informe_autusuario')->middleware('signed');
Route::get('archivo/{id}', 'GestionSeguridad\GnArchivoContoller@index')->name('archivo');
Route::get('afiliadoaportantes/liquidacion-morosos', 'Aseguramiento\AfiliadoPagadorController@liquidacionesMorosos')->name('relaciones-morosas')->middleware('signed');
Route::get('pqrsd/pdfrespuesta', 'AtencionUsuario\PqrsdController@getPdfRespuesta')->name('respuesta-pqrs-pdf')->middleware('signed');
Route::get('pqrsd/pdfdescargos', 'AtencionUsuario\PqrsdController@getPdfDescargos')->name('descargos-pqrs-pdf')->middleware('signed');
Route::get('portabilidad/pdfsolicitud', 'RedServicios\PortabilidadController@getPdfSolicitud')->name('solicitud-portabilidad-pdf')->middleware('signed');

Route::get('afiliado/certafiliacion', 'Aseguramiento\AfiliadoController@getCertificadoAfiliacion')->name('certificado-afiliacion')->middleware('signed');
Route::get('afiliado/certredservicios', 'Aseguramiento\AfiliadoController@getCertificadoRed')->name('certificado-red-servicios')->middleware('signed');
Route::get('afiliado/certificadoelectronico', 'Aseguramiento\AfiliadoController@getCertificadoElectronico')->name('certificado-electronico')->middleware('signed');

Route::get('incapacidades/certificadopresupuestal', 'AtencionUsuario\IncapacidadController@getPdf')->name('certificado-presupuestal')->middleware('signed');
Route::get('incapacidades/solicitud', 'AtencionUsuario\IncapacidadController@getPedfSolicitud')->name('solicitud_incapacidad')->middleware('signed');
Route::get('pr_stringresos/pdf', 'Presupuesto\StringresoController@getInformePdf')->name('reporte-pr-ingresos')->middleware('signed');
Route::get('pr_strgastos/pdf', 'Presupuesto\StrgastoController@getInformePdf')->name('reporte-pr-gastos')->middleware('signed');
Route::get('pr_cdps/pdf', 'Presupuesto\PrCdpController@getPdf')->name('reporte-pr-cdp')->middleware('signed');
Route::get('pr_rps/pdf', 'Presupuesto\PrRpController@getPdf')->name('reporte-pr-rp')->middleware('signed');
Route::get('pr_obligaciones/pdf', 'Presupuesto\PrObligacioneController@getPdf')->name('reporte-pr-obligaciones')->middleware('signed');
Route::get('niifs/pdf', 'Niif\NiifController@getReporte')->name('reporte-nf-niifs')->middleware('signed');
Route::get('formtrasmov/pdf', 'Aseguramiento\FormtrasmovController@getPdfTraslado')->name('formulario-tramite-traslado')->middleware('signed');
Route::get('estudioprevio/pdf', 'ContratacionEstatal\ProcontractualeController@getPdfEstudioPrevio')->name('documento-estudio-previo')->middleware('signed');
Route::get('minuta/pdf', 'ContratacionEstatal\ProconminutaController@getMinutaPDF')->name('minuta-pdf')->middleware('signed');
Route::get('compegresos/pdf', 'Tesoreria\CompegresoController@getPDF')->name('comprobante-egreso-pdf')->middleware('signed');
Route::get('reportes/recaudos','RecCompensacion\RecCompensacionController@exportar')->name('reportes-reccompensaciones-pila')->middleware('signed');
Route::get('reportes/recaudo-planilla','RecCompensacion\RecCompensacionController@getContenidoRecaudoPlanillaOriginal')->name('reporte-pila')->middleware('signed');
//Route::get('comdiarios/pdf/{comdiario}', 'Niif\ComdiarioController@getPDF');
Route::apiResource('afiliadosfuera', 'Aseguramiento\AfiliadoController');
//Only for test
Route::get('seguridad/audits', 'GestionSeguridad\AuditsController@index');

Route::group([
    'middleware' => 'auth:api',
    //'prefix' => 'auth'
], function () {
    //Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('me', 'AuthController@me');
    Route::post('favorito/{componente_id}', 'General\FavoritosController');
    Route::post('changepassword', 'AuthController@changePassword');
    Route::get('firmar-ruta', 'General\ComplementosController@firmarRuta');

    Route::apiResource('usuarios', 'GestionSeguridad\UsuarioController');
    Route::get('usuarios/email/{email}', 'GestionSeguridad\UsuarioController@findByEmail');
    Route::get('usuarios/identificacion/{identification}', 'GestionSeguridad\UsuarioController@findByIdentification');

    // Aseguramiento
    Route::get('afiliados/tercero/{id_tercero}', 'Aseguramiento\AfiliadoController@findByTerceroId');
    Route::get('pagadores/tercero/{id_tercero}', 'Aseguramiento\PagadorController@findByTerceroId');
    Route::apiResource('pagadores', 'Aseguramiento\PagadorController');
    Route::get('afiliados/incidencias', 'Aseguramiento\AfiliadoController@incidencias');
    Route::apiResource('afiliados', 'Aseguramiento\AfiliadoController');
    Route::apiResource('afiliadoaportantes', 'Aseguramiento\AfiliadoPagadorController');
    Route::apiResource('afiliadoaltocostos', 'Aseguramiento\AfiliadoAltocostoController');
    Route::get('afiliado/{id}/atencionesyservicios', 'Aseguramiento\AfiliadoController@getAtencionesYServicios')->where('id', '[0-9]+');
    Route::get('tipnovedades/{afiliado}', 'Aseguramiento\TipNovedadController@index');
    Route::get('tramites/pendiente', 'Aseguramiento\TramiteController@getPendientes');
    Route::get('tramites/traslados-pendiente', 'Aseguramiento\TramiteController@getTrasladosPendientes');
    Route::get('tramites/search/{per_page?}/{search?}', 'Aseguramiento\TramiteController@search');
    Route::apiResource('tramites', 'Aseguramiento\TramiteController');
    Route::apiResource('as_afps', 'Aseguramiento\AfpController');
    Route::apiResource('as_arls', 'Aseguramiento\ArlController');
    Route::apiResource('as_ccfs', 'Aseguramiento\CcfController');
    Route::apiResource('as_epss', 'Aseguramiento\EpsController');
    Route::apiResource('formtrasmov', 'Aseguramiento\FormtrasmovController');
    Route::post('formtrasmovs', 'Aseguramiento\FormtrasmovController@anularTraslado');
    Route::post('aportantes/cargar', 'Aseguramiento\AportanteController@cargar');
    Route::apiResource('aportantes', 'Aseguramiento\AportanteController');
    Route::apiResource('formpagadores', 'Aseguramiento\FormpagadoreController');
    Route::apiResource('soltraslados', 'Aseguramiento\SoltrasladoController');
    Route::post('afiliados/cargarHM', 'Aseguramiento\AfiliadoController@cargarHM');
    Route::post('afiliados/beneficiario/{id}', 'Aseguramiento\AfiliadoController@registrarBeneficiario');
    Route::get('buscar_planillas/{numero}', 'Aseguramiento\CpPlanillasController@findByNumeroPlanilla');
    Route::apiResource('cp_aportes', 'Aseguramiento\CpPlanillasController');
    Route::post('as_cargador_recaudos', 'Recaudos\RecaudosController@store');
    Route::get('recaudos_cargados', 'Aseguramiento\CargadorRecaudosController@index');
    Route::get('afiliadoaportantes/{afiliadoaportante}/estado', 'Aseguramiento\AfiliadoPagadorController@estado');

    //RJPT rutas para td_vigencia_traslado
    Route::get('vigencia', 'TbVigenciaTrasladoController@index');
    Route::get('logtrasladovigencia', 'TbVigenciaTrasladoController@logTraslado');
    Route::post('vigencia/crear', 'TbVigenciaTrasladoController@store');
    Route::delete('vigencia/eliminar/{id}', 'TbVigenciaTrasladoController@destroy');
    Route::put('vigencia/actualizar/{id}', 'TbVigenciaTrasladoController@actualizar');

    // //RJPT rutas para td_periodo_liquidacion
    Route::get('periodo', 'TbPeriodoLiquidacionController@index');
    Route::post('periodo/crear', 'TbPeriodoLiquidacionController@store');
    Route::delete('periodo/eliminar/{id}', 'TbPeriodoLiquidacionController@destroy');
    Route::put('periodo/actualizar/{id}', 'TbPeriodoLiquidacionController@actualizar');

    Route::get('servicios/primarios', 'RedServicios\ServicioController@getPrimarios');
    Route::post('afiliados/{afiliado}/servicios/sync', 'Aseguramiento\AfiliadoController@asignarServicios');

    Route::get('resultado_compensacion', 'Compensacion\ResultadoCompensacionesController@index');
    Route::post('resultado_compensacion', 'Compensacion\ResultadoCompensacionesController@store');

    Route::apiResource('bduarespuestas', 'Aseguramiento\BDUARespuestaController');

    Route::get('presupuestoActual', 'Presupuesto\PresupuestoActualController');

    // RJPT rutas tipos soporte
    Route::get('soporte', 'AtencionUsuario\TBTipoSoporteController@index');
    Route::post('soporte/crear', 'AtencionUsuario\TBTipoSoporteController@store');
    Route::put('soporte/actualizar/{id}', 'AtencionUsuario\TBTipoSoporteController@actualizar');

    Route::get('soportexincapacidad', 'AtencionUsuario\TBTipoSoportexIncapacidadController@index');
    Route::post('soportexincapacidad/crear', 'AtencionUsuario\TBTipoSoportexIncapacidadController@store');
    Route::put('soportexincapacidad/actualizar/{id}', 'AtencionUsuario\TBTipoSoportexIncapacidadController@actualizar');
    Route::get('soportexincapacidad/{id}', 'AtencionUsuario\TBTipoSoportexIncapacidadController@findById');

    Route::get('soportedetalle', 'AtencionUsuario\TBProcesoAdministrativoDetallesController@index');
    Route::post('soportedetalle/crear', 'AtencionUsuario\TBProcesoAdministrativoDetallesController@store');
    Route::put('soportedetalle/actualizar/{id1}/{id2}', 'AtencionUsuario\TBProcesoAdministrativoDetallesController@actualizar');
    Route::delete('soportedetalle/eliminar/{id1}/{id2}', 'AtencionUsuario\TBProcesoAdministrativoDetallesController@destroy');

    // Incapacidades
    Route::get('incapacidadafiliadopagador', 'AtencionUsuario\GestionIncapacidadController@asAfiliadoPagadorActivo');
    Route::get('getsoportes', 'AtencionUsuario\GestionIncapacidadController@getSOportes');
    Route::post('getcounter', 'AtencionUsuario\GestionIncapacidadController@verificarLicencia');
    Route::post('radicar', 'AtencionUsuario\GestionIncapacidadController@radicar');
    Route::get('licencias', 'Aseguramiento\AuditoriaGestionEconomicaController@index');
    Route::get('licencia', 'Aseguramiento\AuditoriaGestionEconomicaController@show');
    Route::get('soportesLicencia', 'Aseguramiento\AuditoriaGestionEconomicaController@getSoportes');
    Route::get('descargasoporte', 'Aseguramiento\AuditoriaGestionEconomicaController@descargar');
    Route::post('liquidar', 'Aseguramiento\LiquidacionAuditoriaPrestacionEconomicaController@sendLiquidar');
    Route::post('calcularIbc', 'Aseguramiento\AuditoriaGestionEconomicaController@calcularIbc');
    Route::post('send_approve_or_reject', 'Aseguramiento\AuditoriaGestionEconomicaController@sendApproveOrReject');

    // //RJPT rutas para tb_dia_festivos
    Route::get('diafestivo', 'AtencionUsuario\TBDiaFestivoController@index');
    Route::post('diafestivo/crear', 'AtencionUsuario\TBDiaFestivoController@store');
    Route::delete('diafestivo/eliminar/{id}', 'AtencionUsuario\TBDiaFestivoController@destroy');
    Route::put('diafestivo/actualizar/{id}', 'AtencionUsuario\TBDiaFestivoController@actualizar');


    Route::apiResource('pqrsds', 'AtencionUsuario\PqrsdController');
    Route::post('incapacidades/{incapacidade}', 'AtencionUsuario\IncapacidadController@update');
    Route::apiResource('incapacidades', 'AtencionUsuario\IncapacidadController');
    Route::get('incapacidades_rubros', 'AtencionUsuario\IncapacidadController@getDetstrgastos');
    Route::apiResource('tipincapacidades', 'AtencionUsuario\TipincapacidadController');

    //Presupuesto
    Route::apiResource('rubros', 'Presupuesto\ConceptoController');
    Route::apiResource('presupuesto/conceptos', 'Presupuesto\ConceptoController');
    Route::apiResource('presupuesto/niveles', 'Presupuesto\NivelesController');
    Route::get('presupuesto/estructuras/{estructura}', 'Presupuesto\EstructuraController@show');

    Route::get('presupuesto/arbol', 'Presupuesto\ConceptoController@arbol');

    // Eliminar prescripcion
    Route::delete('prescripcion/eliminar/{id}', 'Mipres\PrescripcionController@destroy');
    // Eliminar prescripcion
    Route::delete('tutelamipres/eliminar/{id}', 'Mipres\MipresController@destroy');
    
    // Niif
    Route::post('exists-tercero', 'Niif\TerceroController@exists');
    Route::get('terceros/search/{per_page?}/{search?}', 'Niif\TerceroController@search');
    Route::apiResource('terceros', 'Niif\TerceroController');
    Route::get('rps_tercero', 'Niif\TerceroController@getTercerosConRps');
    Route::get('obligaciones_terceros', 'Niif\TerceroController@getTercerosConObligaciones');
    Route::apiResource('anedeclaraciones', 'Niif\AnedeclaracionController');
    Route::apiResource('cencostos', 'Niif\CencostoController');
    Route::apiResource('clascuentas', 'Niif\ClascuentaController');
    Route::apiResource('tipcomdiarios', 'Niif\TipcomdiarioController');
    Route::apiResource('nivcuentas', 'Niif\NivcuentaController');
    Route::get('niifs/search/{search?}', 'Niif\NiifController@search');
    Route::get('niifs/complementos', 'Niif\NiifController@getComplementos');
    Route::get('niifs/auxiliares/search/{search?}', 'Niif\NiifController@searchAuxiliares');
    Route::get('niifs/puc', 'Niif\NiifController@puc');
    Route::apiResource('niifs', 'Niif\NiifController');
    Route::delete('conrtfs/detalles/{detalle}', 'Niif\ConrtfController@removeDetalle');
    Route::apiResource('conrtfs', 'Niif\ConrtfController');
    Route::get('reportecomdiarios', 'Niif\ComdiarioController@firmarComdiario');
    Route::post('repetir-comprobante-diario', 'Niif\ComdiarioController@repetirComprobante');
    Route::get('consecutivo-comdiario', 'Niif\ComdiarioController@consecutivoActual');
    Route::get('cierre-apertura-meses', 'Niif\ComdiarioController@getMeses');
    Route::post('cerrar-mes-comdiario', 'Niif\ComdiarioController@cerarMes');
    Route::post('abrir-mes-comdiario', 'Niif\ComdiarioController@abrirMes');
    Route::apiResource('comdiarios', 'Niif\ComdiarioController');
    Route::post('interfaz-contable/importar-comdiario', 'Niif\InterfazContableController@ImportarComprobanteDiarioLote');
    Route::delete('interfaz-contable/comdiario-lote/{id}', 'Niif\InterfazContableController@EliminarComprobanteDiarioLote')->where('id', '[0-9]+');
    Route::get('interfaz-contable/comdiario-lotes', 'Niif\InterfazContableController@IndexComprobanteDiarioLote');

    // Pagos
    Route::apiResource('pg_conpagos', 'Pagos\PgConpagoController');
    Route::apiResource('pg_uniapoyos', 'Pagos\PgUniapoyoController');
    Route::apiResource('pg_proveedores', 'Pagos\PgProveedoreController');
    Route::apiResource('pg_saliniciales', 'Pagos\PgSalinicialeController');
    Route::get('reportecxps', 'Pagos\PgCxpController@firmarCxp');
    Route::apiResource('pg_cxps', 'Pagos\PgCxpController');
    Route::apiResource('pg_configuraciones', 'Pagos\ConfiguracionController');
    Route::apiResource('pg_configuraciones.rangos', 'Pagos\PgRangoController');
    Route::get('pg_anticipos', 'Pagos\PgAnticipoController@getAnticipos');
    Route::get('cxps/tercero/{idTercero}', 'Pagos\PgAnticipoController@getCxpsOfTercero');
    Route::apiResource('pg_anticipos_cxps', 'Pagos\PgAnticipoController');


    //Cartera
    Route::get('vendedor/codigo/{codigo}', 'Cartera\VendedorController@findByCodigo');
    Route::apiResource('vendedor', 'Cartera\VendedorController');
    Route::get('clientes/codigo/{codigo}', 'Cartera\ClienteController@findByCodigo');
    Route::get('clientes/tercero/{id}', 'Cartera\ClienteController@findTercero');
    Route::get('clientes/{id}', 'Cartera\ClienteController@findById');
    Route::apiResource('clientes', 'Cartera\ClienteController');
    Route::get('conceptoscartera/codigo/{codigo}/{tipoConcepto}', 'Cartera\ConceptosController@findByCodigo');
    Route::get('conceptoscartera/{id}', 'Cartera\ConceptosController@findById');
    Route::apiResource('conceptoscartera', 'Cartera\ConceptosController');
    Route::apiResource('cr_cuentasxcobrars', 'Cartera\CuentasPorCobrarController');
    Route::get('cxc/{id}', 'Cartera\CuentasPorCobrarController@findById');
    Route::get('cxc/consecutivo', 'Cartera\CuentasPorCobrarController@getConsecutivo');
    Route::get('saliniciales/consecutivo', 'Cartera\SaldosInicialesController@getConsecutivo');
    Route::get('saliniciales/{id}', 'Cartera\SaldosInicialesController@findById');
    Route::apiResource('saliniciales', 'Cartera\SaldosInicialesController');
    Route::apiResource('cr_configuraciones', 'Cartera\ConfiguracionesController');
    Route::get('configuracionescartera/configuracion', 'Cartera\ConfiguracionesController@findInique');

    // Tesorería
    Route::apiResource('bancos', 'Tesoreria\BancoController');
    Route::apiResource('cajas', 'Tesoreria\CajaController');
    Route::apiResource('ts_conceptos', 'Tesoreria\ConceptoController');
    Route::apiResource('cuentas', 'Tesoreria\CuentaController');
    Route::get('ts_concepto_recibos/{id}', 'Tesoreria\ConceptoRecibosController@findById');
    Route::get('ts_concepto_recibos/codigo/{codigo}', 'Tesoreria\ConceptoRecibosController@findByCodigo');
    Route::apiResource('ts_concepto_recibos', 'Tesoreria\ConceptoRecibosController');
    Route::get('ts_concepto_egresos/{ts_concepto}', 'Tesoreria\ConceptoEgresosController@findById');
    Route::get('ts_concepto_egresos/codigo/{codigo}', 'Tesoreria\ConceptoEgresosController@findByCodigo');
    Route::apiResource('ts_concepto_egresos', 'Tesoreria\ConceptoEgresosController');
    Route::get('ts_concepto_notas/{id}', 'Tesoreria\ConceptoNotasController@findById');
    Route::get('ts_concepto_notas/codigo/{codigo}', 'Tesoreria\ConceptoNotasController@findByCodigo');
    Route::apiResource('ts_concepto_notas', 'Tesoreria\ConceptoNotasController');
    Route::get('ts_parametros/unico', 'Tesoreria\ParametrosController@getFirst');
    Route::apiResource('ts_parametros', 'Tesoreria\ParametrosController');
    Route::apiResource('ts_ciudades_bancarias', 'Tesoreria\CiudadesBancariaController');
    Route::apiResource('compegresos', 'Tesoreria\CompegresoController');
    Route::get('anti_compegresos/{tercero}/{conegreso}', 'Tesoreria\CompegresoController@getAnticiposAndContratosTercero');

    // Oficina Juridica
    Route::apiResource('oj_juzgados', 'OficinaJuridica\JuzgadoController');
    Route::get('reporte_oj_tutelas', 'OficinaJuridica\TutelaController@firmarReporte');
    Route::apiResource('oj_tutelas', 'OficinaJuridica\TutelaController');
    Route::post('tutelas/{id}', 'OficinaJuridica\TutelaController@actualizar');
    Route::get('oj_tutelas/download/{file}', 'OficinaJuridica\TutelaController@downloadFile');
    Route::get('oj_tutelas/afiliado/{identificacion}', 'OficinaJuridica\TutelaController@findAfiliadoIdentificacion');
    Route::apiResource('oj_tutcontestaciones', 'OficinaJuridica\TutcontestacionController');
    Route::post('oj_tutcontestaciones/{id}', 'OficinaJuridica\TutcontestacionController@actualizar');
    Route::get('oj_tutcontestaciones/download/{id}', 'OficinaJuridica\TutcontestacionController@downloadFile');
    Route::apiResource('oj_tutfallos', 'OficinaJuridica\TutfalloController');
    Route::post('oj_tutfallos/{id}', 'OficinaJuridica\TutfalloController@actualizar');
    Route::get('oj_tutfallos/download/{id}', 'OficinaJuridica\TutfalloController@downloadFile');
    Route::apiResource('oj_tutimpugnaciones', 'OficinaJuridica\TutimpugnacioneController');
    Route::post('oj_tutimpugnaciones/{id}', 'OficinaJuridica\TutimpugnacioneController@actualizar');
    Route::get('oj_tutimpugnaciones/download/{id}', 'OficinaJuridica\TutimpugnacioneController@downloadFile');
    Route::get('oj_tutimpugnaciones/complementos/{id}', 'OficinaJuridica\TutimpugnacioneController@compleFallo');
    Route::apiResource('oj_tutdesacatos', 'OficinaJuridica\TutdesacatoController');
    Route::post('oj_tutdesacatos/{id}', 'OficinaJuridica\TutdesacatoController@actualizar');
    Route::get('oj_tutdesacatos/download/{id}', 'OficinaJuridica\TutdesacatoController@downloadFile');
    Route::apiResource('oj_tutbitacoras', 'OficinaJuridica\TutbitacoraController');
    Route::post('oj_tutbitacoras/{id}', 'OficinaJuridica\TutbitacoraController@actualizar');
    Route::get('oj_tutbitacoras/download/{id}', 'OficinaJuridica\TutbitacoraController@downloadFile');
    Route::delete('borrarTutela/{id}', 'OficinaJuridica\TutelaController@destroy')->where('id', '[0-9]+');// ruta agregada para eliminar
    Route::get('comprobarDuplicado/', 'OficinaJuridica\TutelaController@comprobarTutelaDuplicada');// ruta agregada para comprobarTutelaDuplicada

    Route::get('migrar/entidades/{nitactual}/{nitnuevo}', 'RedServicios\EntidadController@migrar');
    
    // Red Servicios //ENTIDAD
    Route::get('entidades/{tipo?}/search/{per_page?}/{search?}', 'RedServicios\EntidadController@search');
    Route::post('entidades/remove-cum/{cumentidade}', 'RedServicios\EntidadController@removeCums');
    Route::post('entidades/remove-cup/{cupentidade}', 'RedServicios\EntidadController@removeCups');
    Route::post('entidades/remove-capinstalada/{capinstalada}', 'RedServicios\EntidadController@removeCapinstalada');
    Route::post('entidades/add-cum', 'RedServicios\EntidadController@addCums');
    Route::post('entidades/add-capinstalada', 'RedServicios\EntidadController@addCapinstalada');
    Route::get('entidades_bases', 'RedServicios\EntidadBaseController@index');
    Route::get('gruposervicios', 'RedServicios\GrupservicioController@index');
    Route::post('entidades/{entidad}/habilitar-servicios', 'RedServicios\EntidadController@habilitarServicios');
    Route::post('entidades/add-cups', 'RedServicios\EntidadController@addCups');
    Route::apiResource('otrosentidades', 'RedServicios\OtrosentidadController');
    Route::apiResource('rs_cums', 'RedServicios\CumController');
    Route::apiResource('cups', 'RedServicios\CupController');
    Route::apiResource('tipcapinstaladas', 'RedServicios\TipcapinstaladaController');
    Route::get('complementos_cups', 'RedServicios\CupController@complementos');
    Route::get('cups_grupos', 'RedServicios\CupsGrupoController@index');
    Route::get('complementos_cums', 'RedServicios\CumController@complementos');
    Route::post('entidades/{entidade}/importcups', 'RedServicios\EntidadController@importCups');
    Route::post('entidades/{entidade}/importcums', 'RedServicios\EntidadController@importCums');
    Route::post('entidades/{entidade}/importotros', 'RedServicios\EntidadController@importOtros');
    Route::post('contratos/{contrato}/importcups', 'RedServicios\ContratoController@importCups');
    Route::post('contratos/{contrato}/importcums', 'RedServicios\ContratoController@importCums');
    Route::post('contratos/{contrato}/importotros', 'RedServicios\ContratoController@importOtros');
    Route::get('entidades/{entidade}/cums', 'RedServicios\EntidadController@getCums');
    Route::get('entidades/{entidade}/cups', 'RedServicios\EntidadController@getCups');
    Route::get('entidades/{entidade}/otros', 'RedServicios\EntidadController@getOtros');

    // Redservicios asignacion masiva
    Route::post('redservicios/asigna_from_csv', 'RedServicios\AfiliadoServicioController@fromCSV');
    Route::post('redservicios/valida_csv', 'RedServicios\AfiliadoServicioController@validaCSV');
    Route::get('redservicios/asignamasivos', 'RedServicios\AsignamasivoController@index');
    Route::get('redservicios/asignamasivos/{id}', 'RedServicios\AsignamasivoController@show')->where('id', '[0-9]+');
    Route::get('redservicios/afiliadoservicios/inhabilitados', 'RedServicios\AfiliadoServicioController@inhabilitados');
    Route::get('redservicios/afiliadoservicios/cambioservicios', 'RedServicios\AfiliadoServicioController@cambio_servicios');
    Route::post('redservicios/afiliadoservicios', 'RedServicios\AfiliadoServicioController@store');

    //PORTABILIDADES
    Route::get('entidades_diferente', 'RedServicios\EntidadController@getEntidades');
    Route::apiResource('entidades', 'RedServicios\EntidadController');
    Route::get('entidades_contrato', 'RedServicios\EntidadController@getEntidadesContrato');

    // Inventarios
    Route::get('in_grupos/{id}', 'Inventarios\GruposController@findById');
    Route::get('in_grupos/codigo/{codigo}', 'Inventarios\GruposController@findByCodigo');
    Route::apiResource('in_grupos', 'Inventarios\GruposController');
    Route::get('in_subgrupos/{id}', 'Inventarios\SubgruposController@findById');
    Route::get('in_subgrupos/codigo/{codigo}', 'Inventarios\SubgruposController@findByCodigo');
    Route::apiResource('in_subgrupos', 'Inventarios\SubgruposController');
    Route::get('in_almacen/{id}', 'Inventarios\AlmacenController@findById');
    Route::get('in_almacen/codigo/{codigo}', 'Inventarios\AlmacenController@findByCodigo');
    Route::apiResource('in_almacen', 'Inventarios\AlmacenController');
    Route::get('in_unidades_medida/{id}', 'Inventarios\UnidadesMedidaController@findById');
    Route::get('in_unidades_medida/codigo/{codigo}', 'Inventarios\UnidadesMedidaController@findByCodigo');
    Route::apiResource('in_unidades_medida', 'Inventarios\UnidadesMedidaController');
    Route::get('in_productos/{id}', 'Inventarios\ProductosController@findById');
    Route::get('in_productos/codigo/{codigo}', 'Inventarios\ProductosController@findByCodigo');
    Route::apiResource('in_productos', 'Inventarios\ProductosController');

    // Talento Humano
    Route::get('th_grupos_empleados/{id}', 'TalentoHumano\GruposEmpleadosController@findById');
    Route::get('th_grupos_empleados/codigo/{codigo}', 'TalentoHumano\GruposEmpleadosController@findByCodigo');
    Route::apiResource('th_grupos_empleados', 'TalentoHumano\GruposEmpleadosController');
    Route::get('th_subgrupos_empleados/{id}', 'TalentoHumano\SubgruposEmpleadosController@findById');
    Route::get('th_subgrupos_empleados/codigo/{codigo}', 'TalentoHumano\SubgruposEmpleadosController@findByCodigo');
    Route::apiResource('th_subgrupos_empleados', 'TalentoHumano\SubgruposEmpleadosController');
    Route::get('th_fondos/{id}', 'TalentoHumano\FondosController@findById');
    Route::get('th_fondos/codigo/{codigo}', 'TalentoHumano\FondosController@findByCodigo');
    Route::apiResource('th_fondos', 'TalentoHumano\FondosController');
    Route::get('th_niveles_cargos/{id}', 'TalentoHumano\NivelesCargoController@findById');
    Route::get('th_niveles_cargos/codigo/{codigo}', 'TalentoHumano\NivelesCargoController@findByCodigo');
    Route::apiResource('th_niveles_cargos', 'TalentoHumano\NivelesCargoController');
    Route::get('th_cargos_empleados/{id}', 'TalentoHumano\CargosEmpleadosController@findById');
    Route::get('th_cargos_empleados/codigo/{codigo}', 'TalentoHumano\CargosEmpleadosController@findByCodigo');
    Route::apiResource('th_cargos_empleados', 'TalentoHumano\CargosEmpleadosController');
    Route::get('th_profesionalismos/{id}', 'TalentoHumano\ProfesionalismoController@findById');
    Route::get('th_profesionalismos/codigo/{codigo}', 'TalentoHumano\ProfesionalismoController@findByCodigo');
    Route::apiResource('th_profesionalismos', 'TalentoHumano\ProfesionalismoController');
    Route::get('th_retiros/{id}', 'TalentoHumano\RetirosController@findById');
    Route::get('th_retiros/codigo/{codigo}', 'TalentoHumano\RetirosController@findByCodigo');
    Route::apiResource('th_retiros', 'TalentoHumano\RetirosController');
    Route::apiResource('th_encargos', 'TalentoHumano\EncargoController');
    Route::get('empleado/{id}', 'TalentoHumano\EmpleadosController@findById');
    Route::get('empleado/codigo/{codigo}', 'TalentoHumano\EmpleadosController@findByCodigo');
    Route::get('empleado/tercero/{id}', 'TalentoHumano\EmpleadosController@findTerceroById');
    Route::get('empleado/complementos', 'TalentoHumano\EmpleadosController@complementos');
    Route::apiResource('empleado', 'TalentoHumano\EmpleadosController');
    // //RJPT rutas para tb_clase_fondo
    Route::get('clasefondo', 'TalentoHumano\TbClaseFondoController@index');
    Route::post('clasefondo/crear', 'TalentoHumano\TbClaseFondoController@store');
    Route::delete('clasefondo/eliminar/{id}', 'TalentoHumano\TbClaseFondoController@destroy');
    Route::put('clasefondo/actualizar/{id}', 'TalentoHumano\TbClaseFondoController@actualizar');
    // //RJPT rutas para tb_fondo
    Route::get('fondo', 'TalentoHumano\TbFondoController@index');
    Route::post('fondo/crear', 'TalentoHumano\TbFondoController@store');
    Route::delete('fondo/eliminar/{id}', 'TalentoHumano\TbFondoController@destroy');
    Route::put('fondo/actualizar/{id}', 'TalentoHumano\TbFondoController@actualizar');
    // //RJPT rutas para tb_salario_minimo
    Route::get('salario', 'TalentoHumano\TbSalarioMinimoController@index');
    Route::post('salario/crear', 'TalentoHumano\TbSalarioMinimoController@store');
    Route::delete('salario/eliminar/{id}', 'TalentoHumano\TbSalarioMinimoController@destroy');
    Route::put('salario/actualizar/{id}', 'TalentoHumano\TbSalarioMinimoController@actualizar');
    // //RJPT rutas para tb_extra_laboral
    Route::get('extra', 'TalentoHumano\TbExtraLaboralController@index');
    Route::post('extra/crear', 'TalentoHumano\TbExtraLaboralController@store');
    Route::delete('extra/eliminar/{id}', 'TalentoHumano\TbExtraLaboralController@destroy');
    Route::put('extra/actualizar/{id}', 'TalentoHumano\TbExtraLaboralController@actualizar');
    // //RJPT rutas para tb_configuracion_salarial
    Route::get('confsalario', 'TalentoHumano\TbConfiguracionSalarialController@index');
    Route::post('confsalario/crear', 'TalentoHumano\TbConfiguracionSalarialController@store');
    Route::delete('confsalario/eliminar/{id}', 'TalentoHumano\TbConfiguracionSalarialController@destroy');
    Route::put('confsalario/actualizar/{id}', 'TalentoHumano\TbConfiguracionSalarialController@actualizar');
    // //RJPT rutas para tb_profesion
    Route::get('profesion', 'TalentoHumano\TbProfesionController@index');
    Route::post('profesion/crear', 'TalentoHumano\TbProfesionController@store');
    Route::delete('profesion/eliminar/{id}', 'TalentoHumano\TbProfesionController@destroy');
    Route::put('profesion/actualizar/{id}', 'TalentoHumano\TbProfesionController@actualizar');  
    // //RJPT rutas para sc_empleado
    Route::get('empleadosc', 'TalentoHumano\SCEmpleadoController@index');  
    Route::delete('empleadosc/eliminar/{id}', 'TalentoHumano\SCEmpleadoController@destroy');
    Route::post('empleadosc/crear', 'TalentoHumano\SCEmpleadoController@store');
    Route::get('empleadosc/{id}', 'TalentoHumano\SCEmpleadoController@findById');
    Route::put('empleadosc/actualizar/{id}', 'TalentoHumano\SCEmpleadoController@actualizar'); 

    Route::get('empleadosestudio', 'TalentoHumano\EstudioController@index');
    Route::post('empleadosestudio/crear', 'TalentoHumano\EstudioController@store');
    Route::delete('empleadosestudio/eliminar/{id}', 'TalentoHumano\EstudioController@destroy');
    Route::put('empleadosestudio/actualizar/{id}', 'TalentoHumano\EstudioController@actualizar'); 
    
    Route::get('empleadosfamilia', 'TalentoHumano\FamiliaController@index');
    Route::post('empleadosfamilia/crear', 'TalentoHumano\FamiliaController@store');
    Route::delete('empleadosfamilia/eliminar/{id}', 'TalentoHumano\FamiliaController@destroy');
    Route::put('empleadosfamilia/actualizar/{id}', 'TalentoHumano\FamiliaController@actualizar');

    Route::get('empleadoslaboral', 'TalentoHumano\LaboralController@index');
    Route::post('empleadoslaboral/crear', 'TalentoHumano\LaboralController@store');
    Route::delete('empleadoslaboral/eliminar/{id}', 'TalentoHumano\LaboralController@destroy');
    Route::put('empleadoslaboral/actualizar/{id}', 'TalentoHumano\LaboralController@actualizar');

    // Presupuesto
    Route::apiResource('pr_rubros', 'Presupuesto\RubroController');
    Route::get('pr_entidad_resolucion/{id}', 'Presupuesto\EntidadResolucionController@findById');
    Route::get('pr_entidad_resolucion/codigo/{codigo}', 'Presupuesto\EntidadResolucionController@findByCodigo');
    Route::apiResource('pr_entidad_resolucion', 'Presupuesto\EntidadResolucionController');
    Route::get('pr_dependencias/{id}', 'Presupuesto\DependenciaController@findById');
    Route::get('pr_dependencias/codigo/{codigo}', 'Presupuesto\DependenciaController@findByCodigo');
    Route::apiResource('pr_dependencias', 'Presupuesto\DependenciaController');
    Route::get('pr_tipo_ingresos/complementos', 'Presupuesto\TipoIngresoController@complementos');
    Route::get('pr_tipo_ingresos/{id}', 'Presupuesto\TipoIngresoController@findById');
    Route::get('pr_tipo_ingresos/codigo/{codigo}', 'Presupuesto\TipoIngresoController@findByCodigo');
    Route::apiResource('pr_tipo_ingresos', 'Presupuesto\TipoIngresoController');
    Route::get('pr_tipos_gastos/complementos', 'Presupuesto\TipoGastoController@complementos');
    Route::get('pr_tipos_gastos/{id}', 'Presupuesto\TipoGastoController@findById');
    Route::get('pr_tipos_gastos/codigo/{codigo}', 'Presupuesto\TipoGastoController@findByCodigo');
    Route::apiResource('pr_tipos_gastos', 'Presupuesto\TipoGastoController');
    Route::get('pr_tipo_recursos/complementos', 'Presupuesto\TipoRecursoController@complementos');
    Route::get('pr_tipo_recursos/{id}', 'Presupuesto\TipoRecursoController@findById');
    Route::get('pr_tipo_recursos/codigo/{codigo}', 'Presupuesto\TipoRecursoController@findByCodigo');
    Route::apiResource('pr_tipo_recursos', 'Presupuesto\TipoRecursoController');
    Route::get('pr_concepto_presupuestos/{id}', 'Presupuesto\ConceptoPresupuestoController@findById');
    Route::get('pr_concepto_presupuestos/codigo/{codigo}', 'Presupuesto\ConceptoPresupuestoController@findByCodigo');
    // Nuevas RUTAS PRESUPUESTO
    Route::apiResource('pr_concepto_presupuestos', 'Presupuesto\ConceptoPresupuestoController');
    Route::apiResource('pr_stringresos', 'Presupuesto\StringresoController');
    Route::get('entidades_resolucion/{periodo}', 'Presupuesto\StringresoController@findByPeriodo');
    Route::get('entidades_resolucion_gastos/{periodo}', 'Presupuesto\StrgastoController@findByPeriodo');
    Route::get('periodos_stringresos', 'Presupuesto\StringresoController@getPeriodos');
    Route::get('periodos_strgastos', 'Presupuesto\StrgastoController@getPeriodos');
    Route::get('peridos_presupuesto_gastos', 'Presupuesto\ModPresupuestaleController@getPresupuestoConfirmado');
    Route::get('peridos_presupuesto_ingresos', 'Presupuesto\ModPresupuestaleController@getPresupuestoIngresoConfirmado');
    Route::get('periodo_presupuestos', 'Presupuesto\ModPresupuestaleController@findByPeriodo');
    Route::get('presupuesto_rubro', 'Presupuesto\ModPresupuestaleController@findRubro');
    Route::apiResource('pr_mod_presupuestales', 'Presupuesto\ModPresupuestaleController');
    Route::apiResource('pr_cdps', 'Presupuesto\PrCdpController');
    Route::get('cdps/{periodo}', 'Presupuesto\PrCdpController@findByPeriodo');
    Route::get('cdp_periodos_presupuestos', 'Presupuesto\PrCdpController@getPresupuestoConfirmado');
    Route::get('cdp_presupuesto_rubro', 'Presupuesto\PrCdpController@findRubroForDetcdp');
    Route::apiResource('pr_rps', 'Presupuesto\PrRpController');
    Route::get('rps_periodos_cdps', 'Presupuesto\PrRpController@getEntidadResolucionWithCdp');
    Route::get('rps/{periodo}', 'Presupuesto\PrRpController@findByPeriodo');
    Route::get('rps/tercero/{id}', 'Presupuesto\PrRpController@getRpsDeTercero');

    // RUTAS PARA CONTRATO CDP
    Route::get('contratos_cdps', 'Presupuesto\PrRpController@contratosSinRpAndConCdp');
    Route::get('contrato_with_cdp_rubros/{id}', 'Presupuesto\PrRpController@rubrosOfCdp');
    Route::get('cdps', 'Presupuesto\PrCdpController@getCdpsSinMinuta');
    Route::get('cdp_rubro', 'Presupuesto\PrRpController@findRubro');
    // RUTAS DE TESORERIA OBLIGACIONES
    Route::apiResource('pr_obligaciones', 'Presupuesto\PrObligacioneController');
    Route::get('obligacion_periodos', 'Presupuesto\PrObligacioneController@getPeriodos');
    Route::get('obligaciones/{periodo}', 'Presupuesto\PrObligacioneController@findByPeriodo');
    Route::get('obligaciones/rubros/{id}', 'Presupuesto\PrObligacioneController@getRubrosDelRp');
    Route::get('obligaciones/rp/cdp/{id}', 'Presupuesto\PrObligacioneController@getCdpDelRp');
    Route::get('obligacion_rubro', 'Presupuesto\PrObligacioneController@findRubro');
    Route::get('obligaciones/tercero/{id}', 'Presupuesto\PrObligacioneController@getObligacionesDeTercero');
    // RUTAS DE ORDENES DE PAGO
    Route::apiResource('pr_orden_de_pagos', 'Presupuesto\OrdenesDePagoController');
    Route::get('orden_pago_periodos', 'Presupuesto\OrdenesDePagoController@getPeriodos');
    Route::get('ordenes_de_pagos/{periodo}', 'Presupuesto\OrdenesDePagoController@findByPeriodo');
    Route::get('ordenes_de_pagos/rubros_obligacion/{id}', 'Presupuesto\OrdenesDePagoController@getRubrosDeObligacion');
    Route::get('ordenes_de_pagos/cdp_rp_de_obligacion/{id}', 'Presupuesto\OrdenesDePagoController@getRpAndCdpDeObligacion');
    Route::get('ordenes_de_pago_rubro', 'Presupuesto\OrdenesDePagoController@findRubroForDetobligacione');

    Route::apiResource('pr_stringresos.detalles', 'Presupuesto\DetstringresoController');
    Route::apiResource('pr_strgastos', 'Presupuesto\StrgastoController');
    Route::apiResource('pr_strgastos.detalles', 'Presupuesto\DetstrgastoController');
    Route::get('periodos_strgastos', 'Presupuesto\StrgastoController@getPeriodos');

    Route::apiResource('pr_traslados', 'Presupuesto\PrTrasladosController');
    Route::get('presupuesto_rubro_traslado', 'Presupuesto\PrTrasladosController@findRubro');

    // RIIPS
    Route::apiResource('rs_reglas_validaciones', 'Riips\ReglasValidacioneController');
    Route::apiResource('rs_entidades_temporales', 'Riips\EntidadTemporaleController');
    Route::apiResource('rs_rips_radicados', 'Riips\RiipsRadicadoController');
    Route::post('ejecutarseed', 'Riips\RiipsRadicadoController@ejecutarSeed');
    Route::get('reporte_rips', 'Riips\RiipsRadicadoController@firmarReporte');
    Route::get('descargar-rips-validados/{idRips}', 'Riips\RiipsRadicadoController@getRipsValidadosZip');
    Route::apiResource('rs_cie10s', 'RedServicios\Cie10Controller');
    Route::post('importar-cie10-desde-plano', 'RedServicios\Cie10Controller@importarPorArchivoPlano');
    Route::post('importar-cie10-desde-csv', 'RedServicios\Cie10Controller@importarPorArchivoCsv');
    Route::get('entidades_rips', 'Riips\RiipsRadicadoController@getEntidadesRips');
    Route::get('entidades_filters', 'Riips\RiipsRadicadoController@getEntysValidadasYConfirmadas');
    // VALIDADOR 4505
    Route::apiResource('rs_reglas_validacion_4505', 'Validador4505\ReglasValidacionController');
    Route::apiResource('rs_validador4505_radicados', 'Validador4505\Validador4505Controller');
    Route::get('descargar-4505-validados/{id4505}', 'Validador4505\Validador4505Controller@getArchivo4505');
    Route::post('unificador-archivos-4505', 'Validador4505\Validador4505Controller@unificador4505');
    Route::get('complementos-unificador-archivos-4505', 'Validador4505\Validador4505Controller@complementos');
    Route::get('periodos4505/{id}', 'Validador4505\Periodos4505Controller@findById');
    Route::apiResource('periodos4505', 'Validador4505\Periodos4505Controller');

    // Aseguramiento
    Route::apiResource('beneficiarios', 'Aseguramiento\BeneficiarioController');
    Route::get('bduarchivo-por-id/{id}', 'Aseguramiento\BDUAArchivoController@show');
    Route::get('cautraslados', 'Aseguramiento\CautrasladoController@index');
    Route::apiResource('novtramites', 'Aseguramiento\NovtramiteController');
    Route::apiResource('bduaprocesos', 'Aseguramiento\BDUAProcesoController');
    Route::get('decautramites/crear', 'Aseguramiento\DecauttramiteController@creaDeclaraciones');
    Route::apiResource('decautramites', 'Aseguramiento\DecauttramiteController');
    Route::apiResource('anetramites', 'Aseguramiento\AnettramiteController');
    Route::apiResource('formafiliaciones', 'Aseguramiento\FormafiliacionController');
    Route::post('formafiliaciones/anular', 'Aseguramiento\FormafiliacionController@anularAfiliacion');
    Route::get('ruta-pdf-formafiliacion', 'Aseguramiento\FormafiliacionController@rutaPdf');
    Route::get('novedadtramites-pdf', 'Aseguramiento\NovtramiteController@rutaPdf');
    
    Route::get('afitramites/{afiliado}', 'Aseguramiento\AfitramiteController@show');
    Route::get('novedadtramites/{afiliado}', 'Aseguramiento\NovtramiteController@novedades_afiliado');
    
    Route::get('traslatramites/{afiliado}', 'Aseguramiento\TraslatramiteController@show');

    // Contratación estatal
    Route::apiResource('ce_bienservicios', 'ContratacionEstatal\BienservicioController');
    Route::apiResource('ce_planadquisiciones', 'ContratacionEstatal\PlanadquisicioneController');
    Route::get('ce_planadquisiciones/by_uniapoyo/{uniapoyo_id}', 'ContratacionEstatal\PlanadquisicioneController@ByUniapoyo');
    Route::apiResource('ce_planadquisiciones.detalles', 'ContratacionEstatal\DetplanadquisicioneController');
    Route::get('segbienservicios', 'ContratacionEstatal\SegbienservicioController@index');
    Route::get('clasbienservicios/{id}', 'ContratacionEstatal\ClasbienservicioController@show');
    Route::apiResource('ce_natjuridicas', 'ContratacionEstatal\NatjuridicaController');
    Route::apiResource('ce_contratistas', 'ContratacionEstatal\ContratistaController');
    Route::get('ce_contratistas/tercero/{id_tercero}', 'ContratacionEstatal\ContratistaController@findByTerceroId');
    Route::get('ce_aseguradora/codigo/{codigo}', 'ContratacionEstatal\AseguradoraController@findByCodigo');
    Route::get('ce_aseguradora/{id}', 'ContratacionEstatal\AseguradoraController@findById');
    Route::apiResource('ce_aseguradora', 'ContratacionEstatal\AseguradoraController');
    Route::get('ce_tipo_poliza/codigo/{codigo}', 'ContratacionEstatal\TipoPolizaController@findByCodigo');
    Route::get('ce_tipo_poliza/{id}', 'ContratacionEstatal\TipoPolizaController@findById');
    Route::apiResource('ce_tipo_poliza', 'ContratacionEstatal\TipoPolizaController');
    Route::get('ce_abogado/codigo/{codigo}', 'ContratacionEstatal\AbogadoController@findByCodigo');
    Route::get('ce_abogado/{id}', 'ContratacionEstatal\AbogadoController@findById');
    Route::apiResource('ce_abogado', 'ContratacionEstatal\AbogadoController');
    Route::get('ce_interventores/codigo/{codigo}', 'ContratacionEstatal\InterventoresController@findByCodigo');
    Route::get('ce_interventores/{id}', 'ContratacionEstatal\InterventoresController@findById');
    Route::apiResource('ce_interventores', 'ContratacionEstatal\InterventoresController');
    Route::get('ce_supervisores/codigo/{codigo}', 'ContratacionEstatal\SupervisoresController@findByCodigo');
    Route::get('ce_supervisores/{id}', 'ContratacionEstatal\SupervisoresController@findById');
    Route::apiResource('ce_supervisores', 'ContratacionEstatal\SupervisoresController');
    Route::post('estpregarantias/{estpregarantia}', 'ContratacionEstatal\EstpregarantiasController@update');
    Route::post('minuta-firmada/{minuta}', 'ContratacionEstatal\ProconminutaController@addMinuta');

    Route::apiResource('ce_procontractuales', 'ContratacionEstatal\ProcontractualeController');
    Route::get('usuarios-firman-estudio-previo', 'ContratacionEstatal\ProconestprevioController@usuariosFirmanEstudioPrevio');
    Route::apiResource('ce_proconestprevios', 'ContratacionEstatal\ProconestprevioController');

    // Atención Usuario
    Route::get('complementos-pqrs', 'AtencionUsuario\PqrsdController@complementos');
    Route::get('pqrsds/estadisticas', 'AtencionUsuario\RespuestaPqrsdController@estadisticas');
    Route::post('pqrsds/{pqrsd}/respuesta/{respuesta}', 'AtencionUsuario\RespuestaPqrsdController@update');
    Route::post('pqrsds/{pqrsd}/respuesta/', 'AtencionUsuario\RespuestaPqrsdController@store');
    Route::post('pqrsds/{pqrsd}', 'AtencionUsuario\PqrsdController@update');
    // Atención Usuario - Centro Regulador
    Route::apiResource('au_referencias', 'AtencionUsuario\CentroRegulador\ReferenciaController');
    Route::post('referencias/{id}', 'AtencionUsuario\CentroRegulador\ReferenciaController@actualizar');
    Route::get('referencias/by-afiliado/{id}', 'AtencionUsuario\CentroRegulador\ReferenciaController@byAfiliado');
    Route::get('referencias/situtela/{id}', 'AtencionUsuario\CentroRegulador\ReferenciaController@afiliadoConTutela');
    Route::get('referencias/traslado/{afiliado_id}', 'AtencionUsuario\CentroRegulador\ReferenciaController@traslado');
    Route::apiResource('au_refpresentaciones', 'AtencionUsuario\CentroRegulador\RefpresentacioneController');
    Route::apiResource('au_refbitacoras', 'AtencionUsuario\CentroRegulador\RefbitacoraController');
    Route::get('refbitacoras/{id_referencia}', 'AtencionUsuario\CentroRegulador\RefbitacoraController@findBitacorasId');
    Route::apiResource('au_encuestas', 'AtencionUsuario\EncuestaController');
    Route::get('encuesta_complementos', 'AtencionUsuario\EncuestaController@complementos');

    // //RJPT rutas para tb_preferencia
    Route::get('preferencia', 'AtencionUsuario\TbPreferenciaController@index');
    Route::post('preferencia/crear', 'AtencionUsuario\TbPreferenciaController@store');
    Route::delete('preferencia/eliminar/{id}', 'AtencionUsuario\TbPreferenciaController@destroy');
    Route::put('preferencia/actualizar/{id}', 'AtencionUsuario\TbPreferenciaController@actualizar'); 

    // //RJPT rutas para tb_rechazo_prestacion_social
    Route::get('rechazo', 'AtencionUsuario\TbRechazoPrestacionSocialController@index');
    Route::post('rechazo/crear', 'AtencionUsuario\TbRechazoPrestacionSocialController@store');
    Route::delete('rechazo/eliminar/{id}', 'AtencionUsuario\TbRechazoPrestacionSocialController@destroy');
    Route::put('rechazo/actualizar/{id}', 'AtencionUsuario\TbRechazoPrestacionSocialController@actualizar'); 

    // Gestión y Seguridad
    Route::apiResource('gs_roles', 'GestionSeguridad\RoleController');
    Route::apiResource('gs_permiso_roles', 'GestionSeguridad\PermisoRoleController');
    Route::apiResource('empresas', 'GestionSeguridad\EmpresaController');
    //Route::apiResource('gs_modulos', 'GestionSeguridad\ModuloController');

    //Auditoria Y Cuentas
    Route::get('ac_auditores/complementos', 'AuditoriaCuentas\AuditorController@complementos');
    Route::get('ac_auditores/codigo/{codigo}', 'AuditoriaCuentas\AuditorController@findByCodigo');
    Route::get('ac_auditores/{id}', 'AuditoriaCuentas\AuditorController@findById');
    Route::apiResource('ac_auditores', 'AuditoriaCuentas\AuditorController');
    Route::apiResource('archivosaportes', 'Compensacion\ArchivoaporteController');
    Route::get('manglosas', 'AuditoriaCuentas\RadicadosAsignadoController@getGlosasComplementos');

    // CUENTAS MÉDICAS
    Route::apiResource('cm_radicadas', 'CuentasMedicas\RadicadoController');
    Route::get('facturas_rips/{rip}', 'CuentasMedicas\RadicadoController@getRipsFacturas');
    Route::get('radicadas/rips_radicados/{id}', 'CuentasMedicas\RadicadoController@scopeRipsradicados');
    Route::get('radicadas/contratos_ips/{id}', 'CuentasMedicas\RadicadoController@scopeContratosActivos');
    Route::get('contratos_radicados', 'CuentasMedicas\RadicadoController@getContratosRadicados');
    Route::apiResource('cm_manisss', 'CuentasMedicas\ManissController');
    Route::apiResource('cm_mansoats', 'CuentasMedicas\MansoatController');
    Route::post('asignacion_auditores/{id}', 'CuentasMedicas\RadicadoController@asignarAuditor');
    Route::post('primeras_asignaciones/{id}', 'CuentasMedicas\RadicadoController@asignar');
    Route::post('cm_radcambios', 'CuentasMedicas\RadicadoController@nuevoEstadoRadicado');
    Route::prefix('cuentasmedicas/concurrencia')->group(function () {
        Route::get('visitas/{id_concurrencia}', 'CuentasMedicas\VisitasController@show')->where('id_concurrencia', '[0-9]+');
        Route::post('visitas', 'CuentasMedicas\VisitasController@store');
        Route::put('visitas/{id_visita}', 'CuentasMedicas\VisitasController@update')->where('id_visita', '[0-9]+');
        Route::get('condicionesclinicas', 'CuentasMedicas\ConcondclinicasController@index');
        Route::post('condicionesclinicas', 'CuentasMedicas\ConcondclinicasController@store');
        Route::put('condicionesclinicas/{id}', 'CuentasMedicas\ConcondclinicasController@update')->where('id', '[0-9]+');
        Route::get('materno/{id}', 'CuentasMedicas\ConmaternosController@show')->where('id', '[0-9]+');
        Route::post('materno', 'CuentasMedicas\ConmaternosController@store');
        Route::post('materno/{id}', 'CuentasMedicas\ConmaternosController@update')->where('id', '[0-9]+');
        Route::apiResource('egreso', 'CuentasMedicas\ConegresosController');
        Route::apiResource('altocosto', 'CuentasMedicas\ConaltocostosController');
        Route::get('eventos/{id}', 'CuentasMedicas\EventosController@show')->where('id', '[0-9]+');
        Route::post('eventos', 'CuentasMedicas\EventosController@store');
        Route::post('eventos/{id}', 'CuentasMedicas\EventosController@update')->where('id', '[0-9]+');
        Route::delete('eventos/{id}', 'CuentasMedicas\EventosController@destroy')->where('id', '[0-9]+');
        Route::apiResource('concups', 'CuentasMedicas\ConcupsController');
        Route::apiResource('concums', 'CuentasMedicas\ConcumsController');
        Route::apiResource('insumos', 'CuentasMedicas\ConinsumosController');
        Route::apiResource('diagnosticos', 'CuentasMedicas\Concie10sController');
        Route::apiResource('hallazgos', 'CuentasMedicas\ConhallazgosController');
        Route::apiResource('gestionriesgos', 'CuentasMedicas\CongestionriesgoController');
    });
    Route::apiResource('cuentasmedicas/concurrencia', 'CuentasMedicas\ConcurrenciasController');
    Route::get('cuentasmedicas/concurrenciasAsignadas', 'CuentasMedicas\ConcurrenciasController@indexCocurrente');
    Route::apiResource('cuentasmedicas/complicacionpatologia', 'CuentasMedicas\ComppatologiaController');
    Route::apiResource('cuentasmedicas/eventossaludpublica', 'CuentasMedicas\EventosaludpublicaController');
    Route::apiResource('cuentasmedicas/patologiastrazadoras', 'CuentasMedicas\PatologiaTrazadoraController');
    Route::apiResource('cuentasmedicas/tipocausalhospitalizacion', 'CuentasMedicas\TipocausalhospitalizacionController');
    Route::apiResource('cuentasmedicas/complicacionespartos', 'CuentasMedicas\ComplicacionpartoController');
    Route::apiResource('cuentasmedicas/complicacionesneonatos', 'CuentasMedicas\ComplicacionneonatoController');
    Route::apiResource('cuentasmedicas/eventosadversoeps', 'CuentasMedicas\EventoadversoepsController');
    Route::apiResource('cuentasmedicas/eventosadversoips', 'CuentasMedicas\EventoadversoipsController');
    // Facturas
    Route::apiResource('cm_facservicios', 'CuentasMedicas\FacServiciosGlosasController');
    Route::post('facservicios/{facservicio}/glosa', 'CuentasMedicas\FacServiciosGlosasController@saveGlosa');
    Route::post('cm_facturas/{factura}/glosa', 'CuentasMedicas\FacServiciosGlosasController@saveGlosaFactura');
    Route::delete('facservicios/glosas/{glosa}', 'CuentasMedicas\FacServiciosGlosasController@destroy');
    Route::get('cm_facturas/{id}', 'CuentasMedicas\FacturaController@getFactura');
    Route::get('facturas/{factura}', 'CuentasMedicas\FacturaController@show');
    Route::post('facservicios/altoscostos', 'CuentasMedicas\FacServiciosGlosasController@saveAltocostoFacservicio');
    Route::get('complementos/facturas', 'CuentasMedicas\FacturaController@getComplementos');
    Route::post('asignaciones/facturas', 'CuentasMedicas\FacturaController@asignacionFactura');
    // Route::post('facservicios/recobros', 'CuentasMedicas\FacServiciosGlosasController@saveRecobroOrCapita');
    Route::post('facturas/{factura}', 'CuentasMedicas\FacturaController@actualizarEstadoFactura');
    Route::get('cm_radicado/{radicado}/facturas', 'CuentasMedicas\FacturaController@getAllFacturas');
    Route::get('facturas_all', 'CuentasMedicas\FacturaController@index');
    Route::post('facservicios/masivos', 'CuentasMedicas\FacServiciosGlosasController@saveMassive');
    Route::get('radicado_capitados/{id}', 'CuentasMedicas\CuentasCapitadasController@getFacturaCapita')->where('id', '[0-9]+');
    Route::put('cmcapitadas/{id}', 'CuentasMedicas\CuentasCapitadasController@update');
    //Route::apiResource('cm_respuestas', 'CuentasMedicas\RespuestasGlosasController');

    // CONCURRENCIAS
    Route::post('censos', 'CuentasMedicas\CensoController@store');
    Route::get('censos', 'CuentasMedicas\CensoController@index');
    Route::get('cm_censos/{censo}/pacientes', 'CuentasMedicas\CensoController@getPacientes');
    Route::post('asignarConcurrencia', 'CuentasMedicas\PacientesHospitalariosController@asignarConcurrencia');
    Route::apiResource('cm_pacientes_hospitalarios', 'CuentasMedicas\PacientesHospitalariosController');
    Route::delete('eliminar_concurrencia_asignada/{idAuditor}/{idConcurrencia}/{pacienteId}', 'CuentasMedicas\PacientesHospitalariosController@eliminarAuditorAsignado');
    //Reportes
    Route::get('reportes-disponibles', 'ReporteController@disponibles');
    Route::apiResource('reportes', 'ReporteController');

    //CONTRATOS
    Route::apiResource('contratos', 'RedServicios\ContratoController');
    Route::post('contratos/{contrato}/add-servicios', 'RedServicios\ContratoController@addServicios');
    Route::post('contratos/{contrato}/add-cup', 'RedServicios\ContratoController@addCups');
    Route::post('contratos/{contrato}/add-cup-masivo', 'RedServicios\ContratoController@addCupsMasivo');
    Route::post('contratos/{contrato}/add-cum', 'RedServicios\ContratoController@addCums');
    Route::post('contratos/{contrato}/add-otro', 'RedServicios\ContratoController@addOtro');
    Route::apiResource('contratos/{contrato}/novcontratos', 'RedServicios\NovcontratoController');

    Route::get('contratos/{contrato}/cums', 'RedServicios\ContratoController@getCums');
    Route::get('contratos/{contrato}/cups', 'RedServicios\ContratoController@getCups');
    Route::get('contratos/{contrato}/otros', 'RedServicios\ContratoController@getOtros');

    Route::post('contratos/remove-cum/{cumcontratado}', 'RedServicios\ContratoController@removeCums');
    Route::post('contratos/remove-cup/{cupcontratado}', 'RedServicios\ContratoController@removeCups');
    Route::post('contratos/remove-otro/{otroscontratado}', 'RedServicios\ContratoController@removeOtro');
    Route::get('cumcontratados', 'RedServicios\CumContratadoController@index');
    Route::get('cupcontratados', 'RedServicios\CupContratadoController@index');
    Route::get('otroscontratados', 'RedServicios\OtrosContratatadoController@index');
    Route::post('portabilidades/{portabilidade}', 'RedServicios\PortabilidadController@update');
    Route::apiResource('portabilidades', 'RedServicios\PortabilidadController');
    Route::post('cancelar_portabilidad/{portabilidade}', 'RedServicios\PortabilidadController@cancelarPotabilidad');

    //MIPRES
    Route::apiResource('prescripciones', 'Mipres\PrescripcionController');
    // Route::apiResource('', 'Mipres\PrescripcionController');
    Route::get('logtraslados', 'Aseguramiento\AfiliadoController@getLogTrasladoMs');
    //Route::post('prescripciones/{prescripcion}/entregas', 'Mipres\EntregasController@store');
    Route::apiResource('direccionamientos', 'Mipres\DireccionamientoController');
    Route::post('notificaciones-mipres', 'Mipres\NotificacionController@store');
    Route::get('reporteentregas', 'Mipres\ReportesEntregaController@index');
    Route::post('suministros', 'Mipres\SuministrosController@store');
    Route::get('mipres/prescripcionesFecha', 'Mipres\MipresController@prescripciones_fecha');
    Route::get('mipres/juntasFecha', 'Mipres\MipresController@juntas_fecha');
    Route::get('mipres/tutelasFecha', 'Mipres\MipresController@tutelas_fecha');
    Route::get('mipres/historicoJuntas', 'Mipres\MipresController@historico_juntas');
    Route::get('mipres/reportesFecha', 'Mipres\MipresController@reportes_fecha');

    //Auditoría MS
    Route::get('maestroafiliados/{nombre}', 'Aseguramiento\MaestroAfiliadosController@buscarSiExisteArchivo');
    Route::post('maestroafiliados/store', 'Aseguramiento\MaestroAfiliadosController@store');
    Route::get('maestroafiliados', 'Aseguramiento\MaestroAfiliadosController@index');

    Route::get('institucionesConPacientes', 'CuentasMedicas\PacientesHospitalariosController@entidadesConPacientes');

    Route::apiResource('autorizaciones/medicos', 'Autorizaciones\MedicosSolicitantesController');
    Route::post('bduas', 'Aseguramiento\CargadorArchivosBDUAController@store');
    Route::post('bduafiles/respuesta', 'Aseguramiento\BDUARespuestaController@cargarRespuesta');
    Route::get('respuestasbdua', 'Aseguramiento\BDUARespuestaController@respuestas');

    // Autorizaciones
    Route::get('autorizaciones_all', 'Autorizaciones\AuAnexoTecnico3Controller@index');
    Route::get('aut_all_prestadores', 'Autorizaciones\AuAnexoTecnico3Controller@indexPrestadotes');
    //Route::get('aut_all_funcionarios','Autorizaciones\AuAnexoTecnico3Controller@index');
    Route::get('anexos_complementos', 'Autorizaciones\AuAnexoTecnico3Controller@getComplementosAnexo');
    Route::get('complementos_modal', 'Autorizaciones\AuAnexoTecnico3Controller@getComplementosModal');
    Route::prefix('autorizaciones')->group(function () {
        Route::get('anexo3/{anexot3}', 'Autorizaciones\AuAnexoTecnico3Controller@show');
        Route::post('anexo3', 'Autorizaciones\AuAnexoTecnico3Controller@store');
        Route::put('anexo3/{anexot3}', 'Autorizaciones\AuAnexoTecnico3Controller@update');
        Route::post('anexo3/detalles/{id}', 'Autorizaciones\AuAnexoT31Controller@store');
        Route::get('refcups/{refcup}', 'Autorizaciones\AuAnexoTecnico3Controller@getEspecialidadOfRefCup');
        Route::get('servicios', 'Autorizaciones\RefcupController@index');
        Route::get('contrato/{codigo}', 'Autorizaciones\AuAnexoTecnico3Controller@getPlanesCobertura');
        Route::get('/{anexo}/entidades_prints', 'Autorizaciones\AuAnexoTecnico3Controller@getEntidadesPdf');
        Route::get('valida_edades/{afiliado}', 'Autorizaciones\AuAnexoTecnico3Controller@validacionEdadesAndGenero');
        Route::put('anular/{anexot3}', 'Autorizaciones\AuAnexoTecnico3Controller@update');
        Route::get('valida_autorizacionxanio', 'Autorizaciones\AuAnexoTecnico3Controller@getValServicioODxInAnio');
        Route::post('/{anexot3}/aprobar','Autorizaciones\AuAnexot3AproServiciosController@store');
        Route::get('/sin-aprobar','Autorizaciones\AuAnexoTecnico3Controller@indexSolicitudesPorAprobar');
        Route::get('/sin-aprobar-prestadores','Autorizaciones\AuAnexoTecnico3Controller@indexSolicitudesPorAprobarPrestadores');
    });
    Route::get('tutelas_afiliado/{afiliado}', 'OficinaJuridica\AfiliadosTutelaController@byAfiliado');

    Route::get('aportes_ach', 'Compensacion\AportesACHController@index');

    //Asignación servicios
    Route::prefix('rs_servicios')->group(function () {
        Route::apiResource('/grupos_ips', 'RedServicios\AsignacionServiciosController');
        Route::get('/prestadores/{id_municipio}', 'RedServicios\AsignacionServiciosController@getPrestadores');
        Route::get('/grupos/{id_municipio}', 'RedServicios\AsignacionServiciosController@getGruposPorMunicio');
        Route::get('/afiliado_grupo/{afiliado}', 'Aseguramiento\AfiliadoController@getGruposIPs');
        Route::post('/afiliado/{afiliado}', 'Aseguramiento\AfiliadoController@asignarServicios');
        Route::get('/asignacion_masivos', 'RedServicios\AsginacionMasivaGruposController@index');
        Route::get('/masivos/{id}', 'RedServicios\AsginacionMasivaGruposController@show');
        Route::post('/masivos', 'RedServicios\AsginacionMasivaGruposController@store');
    });

    //Depuracion BDUA
    Route::prefix('bduas')->group(function (){
        Route::apiResource('depuraciones','Aseguramiento\DepuracionNovedadesController');
        Route::get('validacion-estado/{novtramite}','Aseguramiento\DepuracionNovedadesController@validarSiNegAbierto');
        Route::get('tipo_novedad/campos/{codigo}','Aseguramiento\DepuracionNovedadesController@camposNovedad');
        Route::delete('eliminar-estado/{novtramite}','Aseguramiento\DepuracionNovedadesController@destroy');
        Route::apiResource('depuracionms','Aseguramiento\DepuracionesBDUAController');
        Route::post('eliminars/{no_consecutivoaf}','Aseguramiento\DepuracionesBDUAController@eliminarBDUA');
    });

    //TalentoHumano
    Route::prefix('talentohumano')->group(function () {
        Route::apiResource('cencostos','TalentoHumano\TbCentroCostoController');
        Route::apiResource('areas','TalentoHumano\TbAreaController');
        Route::apiResource('dependencias','TalentoHumano\TbDependenciaController');
        Route::apiResource('cargos','TalentoHumano\TbCargoController');
        Route::apiResource('tipos_contratos','TalentoHumano\TbTipoContratosController');
        Route::get('complementos','TalentoHumano\TbCentroCostoController@getComTalentoHumano');
        // Contratos
        Route::apiResource('contratos-empleados','TalentoHumano\ScContratosEmpleadoController');
        Route::apiResource('contratosempleados/extras','TalentoHumano\ScContEmpleadoExtrasController');
        Route::apiResource('contratosempleados/remuneraciones','TalentoHumano\ScContEmpleadoRemuneradoController');
        // Nominales
        Route::apiResource('nominaliquidaciones','TalentoHumano\ScLiquidacionNoEncabezadoController');
    });

    Route::prefix('reccompensaciones')->group(function () {
        Route::apiResource('pilas','RecCompensacion\RecCompensacionController');
        Route::get('directories/descargas','RecCompensacion\RecCompensacionController@getRecaudoPlanillaFTP');
        Route::get('log_bancarindexs','RecCompensacion\RecLogBancarioController@index');
        Route::post('logs_bancarios','RecCompensacion\RecLogBancarioController@store');
        Route::post('conciliacion_pila_bancos','RecCompensacion\RecConciliacionController@store');
        Route::get('planilla-conciliada/{consecutivoRecaudo}','RecCompensacion\RecCompensacionController@esPlanillaConciliada');
        Route::delete('eliminar-conciliada/{consecutivoRecaudo}','RecCompensacion\RecCompensacionController@eliminarRecaudoPlanilla');
    });

});

// General
Route::get('complementos-all', 'General\ComplementosController@getAll');
Route::post('complementos', 'General\ComplementosController@getComplementos');
Route::get('complementos/veredas/{municipio_id}', 'General\ComplementosController@getVeredas');
Route::get('complementos/barrios/{municipio_id}', 'General\ComplementosController@getBarrios');
Route::get('fecha-limite', 'General\ComplementosController@getFechaLimite');
// Todo incluir en autenticación
// Formulario traslado moviolodad|
Route::get('storage/api-docs', 'StorageController@getOpenApiJson');
// BROADCASTING
Broadcast::routes(['middleware' => 'auth:api']);

//Route::get('afiliadoaportantes/{afiliadoaportante}','Aseguramiento\AfiliadoPagadorController@getPeriodosVencidos');

Route::apiResource('autorizaciones', 'Autorizaciones\AutorizacionController');
Route::get('autaprobadas', 'Autorizaciones\AutorizacionController@autAprobadas');
Route::put('deleteautorizacion/{id}', 'Autorizaciones\AutorizacionController@deleteAutorizacion');
Route::apiResource('autsolicitudes', 'Autorizaciones\AutsolicitudController');

Route::get('cuotacopagos', 'RedServicios\CuotacopagoController@index');

//@Jorge ajustar a su necesidad, el metodo recibe el id del radicado
Route::get('cm_radicados/pdf', 'CuentasMedicas\RadicadoController@getPdf');
Route::get('referencias/pdf', 'AtencionUsuario\CentroRegulador\ReferenciaController@getPdf');

Route::get('salminimos', 'RedServicios\SalminimoController@index');

Route::apiResource('minutas', 'ContratacionEstatal\ProconminutaController');

Route::apiResource('mptutelas', 'Mipres\TutelaController');

//Route::apiResource('minutas','ContratacionEstatal\ProconminutaController');

Route::apiResource('barrios', 'General\BarriosController');

