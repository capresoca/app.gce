<?php

namespace App\Http\Controllers\General;

//use App\Http\Resources\General\ComplementosResource;
use App\Models\Aseguramiento\AsAfp;
use App\Models\Aseguramiento\AsArl;
use App\Models\Aseguramiento\AsCampnovedad;
use App\Models\Aseguramiento\AsCcf;
use App\Models\Aseguramiento\AsClasecotizante;
use App\Models\Aseguramiento\AsCondicionDiscapacidade;
use App\Models\Aseguramiento\AsCondterminacione;
use App\Models\Aseguramiento\AsDeclaracione;
use App\Models\Aseguramiento\AsEps;
use App\Models\Aseguramiento\AsEstadoafiliado;
use App\Models\Aseguramiento\AsEtnia;
use App\Models\Aseguramiento\AsParentesco;
use App\Models\Aseguramiento\AsPobespeciale;
use App\Models\Aseguramiento\AsRegimene;
use App\Models\Aseguramiento\AsTipafiliacione;
use App\Models\Aseguramiento\AsTipafiliado;
use App\Models\Aseguramiento\AsTipaltocosto;
use App\Models\Aseguramiento\AsTipanexo;
use App\Models\Aseguramiento\AsTipaportante;
use App\Models\Aseguramiento\AsTipcambiodoc;
use App\Models\Aseguramiento\AsTipcotizante;
use App\Models\Aseguramiento\AsTipnovedade;
use App\Models\Aseguramiento\AsTipoDiscapacidade;
use App\Models\Autorizaciones\AuEspecialidad;
use App\Models\Autorizaciones\AuModservicio;
use App\Models\Autorizaciones\AuServicio;
use App\Models\CentroRegulador\AuReftipaccione;
use App\Models\ContratacionEstatal\CeGarantia;
use App\Models\ContratacionEstatal\CeModcontratacione;
use App\Models\ContratacionEstatal\CeNatjuridica;
use App\Models\ContratacionEstatal\CePlanadquisicione;
use App\Models\ContratacionEstatal\CeTipcontratacione;
use App\Models\ContratacionEstatal\CeTipocontrato;
use App\Models\General\GnBarrio;
use App\Models\General\GnCargo;
use App\Models\General\GnDepartamento;
use App\Models\General\GnMunicipio;
use App\Models\General\GnPaise;
use App\Models\General\GnSexo;
use App\Models\General\GnTipdocidentidade;
use App\Models\General\GnVereda;
use App\Models\General\GnZona;
use App\Models\Niif\GnTercero;
use App\Models\Niif\NfAnedeclaracione;
use App\Models\Niif\NfCencosto;
use App\Models\Niif\NfCiiu;
use App\Models\Niif\NfClascuenta;
use App\Models\Niif\NfConrtf;
use App\Models\Niif\NfNiif;
use App\Models\Niif\NfNivcuenta;
use App\Models\Niif\NfTipcomdiario;
use App\Models\OficinaJuridica\OjJuzgado;
use App\Models\OficinaJuridica\OjPretencione;
use App\Models\Pagos\PgConpago;
use App\Models\Pagos\PgProveedore;
use App\Models\Pagos\PgUniapoyo;
use App\Models\Presupuesto\PrDependencia;
use App\Models\Presupuesto\PrTipoIngreso;
use App\Models\Presupuesto\PrTiposGasto;
use App\Models\RedServicios\RsEntidade;
use App\Models\RedServicios\RsServicio;
use App\Models\RedServicios\RsTipentidade;
use App\Models\TalentoHumano\TbArea;
use App\Models\TalentoHumano\TbCentroConsto;
use App\Models\TalentoHumano\TbDependencia;
use App\Models\TalentoHumano\TbExtraLaboral;
use App\Models\TalentoHumano\ThCargosEmpleado;
use App\Models\Tesoreria\TsBanco;
use App\Models\TbPeriodoLiquidacion;
use App\Models\TalentoHumano\TbClaseFondo;
use App\Models\TalentoHumano\TbProfesion;
use App\Models\TalentoHumano\TbFondo;
use App\Traits\CarbonColombia;
use App\Traits\EnumsTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use App\Models\TbVigenciaTraslado;
use App\Models\OficinaJuridica\OjTutela;
use App\Models\AtencionUsuario\TbTipoSoporte;
use App\Models\AtencionUsuario\TbRechazoPrestacionSocial;
use App\Models\Autorizaciones\AuMedicosSolicitante;
use App\Models\RedServicios\RsCie10;
use App\Models\General\TbNomenclatura;

class ComplementosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getComplementos(Request $request)
    {
        $complementos = collect();
        foreach ($request->items as $item) {
            switch ($item) {
                case 'sexos':
                    $complementos->put('sexos', GnSexo::all());
                    break;

                case 'tipdocidentidad':
                    $complementos->put('tipdocidentidades', GnTipdocidentidade::all());
                    break;

                case 'municipio':
                    $complementos->put('municipios', GnMunicipio::all());
                    break;

                case 'zona':
                    $complementos->put('zonas', GnZona::all());
                    break;

                case 'tipo_retencion':
                    $tiposrete = EnumsTrait::getEnumValues('gn_terceros', 'tipo_retencion');
                    $complementos->put('tipos_retencion', $tiposrete);
                    break;

                case 'tipo_contribuyente':
                    $tiposcontrib = EnumsTrait::getEnumValues('gn_terceros', 'tipo_contribuyente');
                    $complementos->put('tipos_contribuyente', $tiposcontrib);
                    break;

                case 'tipo_persona':
                    $tipospersona = EnumsTrait::getEnumValues('gn_terceros', 'tipo_persona');
                    $complementos->put('tipos_persona', $tipospersona);
                    break;

                case 'autorretenedor':
                    $tipospersona = EnumsTrait::getEnumValues('gn_terceros', 'autorretenedor');
                    $complementos->put('autorretenedores', $tipospersona);
                    break;

                case 'ciiu':
                    $complementos->put('ciius', NfCiiu::all());
                    break;
            }
        }

        return response()->json([
            'data' => $complementos
        ], 200);

    }

    public function getAll()
    {
        $complementos = collect();
        $complementos->put('sexos', GnSexo::all());
        $complementos->put('tipdocidentidades', GnTipdocidentidade::all());
        $complementos->put('municipios', GnMunicipio::all());
        $complementos->put('zonas', GnZona::all());
        
        $tiposrete = EnumsTrait::getEnumValues('gn_terceros', 'tipo_retencion');
        $complementos->put('tipos_retencion', $tiposrete);
        $tiposcontrib = EnumsTrait::getEnumValues('gn_terceros', 'tipo_contribuyente');
        $complementos->put('tipos_contribuyente', $tiposcontrib);
        $tipospersona = EnumsTrait::getEnumValues('gn_terceros', 'tipo_persona');
        $complementos->put('tipos_persona', $tipospersona);
        $tipospersona = EnumsTrait::getEnumValues('gn_terceros', 'autorretenedor');
        $complementos->put('autorretenedores', $tipospersona);
        $tipospersona = EnumsTrait::getEnumValues('gn_terceros', 'autorretenedor');
        $complementos->put('autorretenedores', $tipospersona);
        $tipostramite = EnumsTrait::getEnumValues('as_tramites', 'tipo_tramite');
        $complementos->put('tipos_tramite', $tipostramite);
        $complementos->put('ciius', NfCiiu::all());
        $complementos->put('etnias', AsEtnia::all());
        $complementos->put('tiposdiscapacidad', AsTipoDiscapacidade::all());
        $complementos->put('condicionesdiscapacidad', AsCondicionDiscapacidade::all());
        $complementos->put('pobespeciales', AsPobespeciale::all());
        $complementos->put('arls', AsArl::all());
        $complementos->put('afps', AsAfp::all());
        $complementos->put('ccfs', AsCcf::all());
        $complementos->put('tipaportantes', AsTipaportante::all());
        $complementos->put('regimenes', AsRegimene::all());
        $complementos->put('tipafiliaciones', AsTipafiliacione::all());
        $complementos->put('tipafiliados', AsTipafiliado::all());
        $complementos->put('tipcotizantes', AsTipcotizante::all());
        $complementos->put('clasecotizantes', AsClasecotizante::all());
        $complementos->put('clasescuentas', NfClascuenta::all());
        $complementos->put('nivelescuentas', NfNivcuenta::all());
        $complementos->put('anexosdeclaracion', NfAnedeclaracione::all());
        $complementos->put('parentescos', AsParentesco::all());
        // $complementos->put('entidades', RsEntidade::all()); //!!!REVISAR
        $tiposniif = EnumsTrait::getEnumValues('nf_niifs', 'tipo');
        $complementos->put('tiposniif', $tiposniif);
        $tiposrten = EnumsTrait::getEnumValues('nf_niifs', 'tipo_retencion');
        $complementos->put('tiposreteniif', $tiposrten);
        $dian = GnTercero::find(1);
        $complementos->put('dian', $dian);
        $alcaldia = GnTercero::find(2);
        $complementos->put('alcaldia', $alcaldia);
        $complementos->put('declaraciones', AsDeclaracione::all());
        $complementos->put('tipcomdiarios', NfTipcomdiario::all());
        $complementos->put('cencostos', NfCencosto::all());
        $tipusuario = EnumsTrait::getEnumValues('gs_usuarios', 'tipo');
        $complementos->put('tipusuarios', $tipusuario);
        $complementos->put('conrtfs', NfConrtf::all());
        $complementos->put('tipanexos', AsTipanexo::all());
        $complementos->put('tipnovedades', AsTipnovedade::all());
        $complementos->put('tipcambiodocs', AsTipcambiodoc::all());
        $tiposProveedor = EnumsTrait::getEnumValues('pg_proveedores', 'tipo_proveedor');
        $complementos->put('condterminaciones', AsCondterminacione::where('codigo', '<>', '4')->get());
        $complementos->put('tiposProveedor', $tiposProveedor);
        $complementos->put('conpagos', PgConpago::with('niif.clascuenta')->get());
        $complementos->put('uniapoyos', PgUniapoyo::all());
        $complementos->put('bancos', TsBanco::all());
        $complementos->put('epss', AsEps::all());
        $estados_formafiliaciones = EnumsTrait::getEnumValues('as_formafiliaciones', 'estado');
        $complementos->put('estadosFormafiliaciones', $estados_formafiliaciones);
        $estados_formtrasmovs = EnumsTrait::getEnumValues('as_formtrasmovs', 'estado');
        $complementos->put('estadosTrasmovs', $estados_formtrasmovs);
        $complementos->put('natjuridicas', CeNatjuridica::all());
        $planadquisicion_estado = EnumsTrait::getEnumValues('ce_planadquisiciones', 'estado');
        $complementos->put('planadquisicionEstado', $planadquisicion_estado);
        $complementos->put('modcontrataciones', CeModcontratacione::all());
        $tiposTerceros = EnumsTrait::getEnumValues('gn_terceros', 'tipo_tercero');
        $complementos->put('tiposterceros', $tiposTerceros);
        $niveles_sisben = EnumsTrait::getEnumValues('as_afiliados', 'nivel_sisben');
        $complementos->put('nivelesSisben', $niveles_sisben);
        $complementos->put('estadosAfiliado', AsEstadoafiliado::all());
        $estados_afiliado_pagador = EnumsTrait::getEnumValues('as_afiliado_pagador', 'estado');
        $complementos->put('estadosRelacionesLaborales', $estados_afiliado_pagador);
        $complementos->put('cargos', GnCargo::all());
        $complementos->put('pretenciones', OjPretencione::all());
        $complementos->put('juzgados', OjJuzgado::all());
        $estados_tutela = EnumsTrait::getEnumValues('oj_tutelas', 'estado');
        $complementos->put('estadosTutela', $estados_tutela);
        $tipos_tutela = EnumsTrait::getEnumValues('oj_tutelas', 'tipo_tutela');
        $complementos->put('tiposTutela', $tipos_tutela);
        $complementos->put('modalidadesServicio', AuModservicio::all());
        $complementos->put('departamentos', GnDepartamento::all());
        $complementos->put('paises', GnPaise::all());
        $complementos->put('tiposEntidad', RsTipentidade::all());
        $generosRs = EnumsTrait::getEnumValues('rs_cie10s', 'genero');
        $complementos->put('generos', $generosRs);
        $estadosReferencias = EnumsTrait::getEnumValues('au_referencias', 'estado');
        $complementos->put('estadosReferencias', $estadosReferencias);
        $tipoOrigenRefs = EnumsTrait::getEnumValues('au_referencias', 'tipo_origen');
        $complementos->put('tipoOrigenRefs', $tipoOrigenRefs);
        $estadoEgresoRefs = EnumsTrait::getEnumValues('au_referencias', 'estado_egreso');
        $complementos->put('estadoEgresoRefs', $estadoEgresoRefs);
        $complementos->put('rsServicios', RsServicio::all());
        $complementos->put('tipacciones', AuReftipaccione::all());
        // Contratación estatal
        $complementos->put('dependencias', PrDependencia::all());
        $complementos->put('tiposContratacion', CeTipcontratacione::all());
        $complementos->put('tiposFormaPago', EnumsTrait::getEnumValues('ce_estpreforpagos', 'tipo'));
        // contratos
        //$complementos->put('tiposContrato', EnumsTrait::getEnumValues('ce_proconminutas','tipo'));
        $complementos->put('tiposContrato', CeTipocontrato::all());
        //        $complementos->put('tiposPlanCobertura', EnumsTrait::getEnumValues('rs_contratos','tipo'));
        $complementos->put('tiposNovedad', EnumsTrait::getEnumValues('rs_novcontratos', 'tipo'));
        $complementos->put('garantias', CeGarantia::all());
        // Autorizaciones
        $complementos->put('tiposOrigen', EnumsTrait::getEnumValues('au_autorizaciones', 'tipo_origen'));
        $complementos->put('cargosEmpleado', ThCargosEmpleado::all());
        $complementos->put('tipoIngresos', PrTipoIngreso::all());
        $complementos->put('tipoGastos', PrTiposGasto::all());
        $complementos->put('estadosPrescripciones', EnumsTrait::getEnumValues('mp_prescripciones', 'estado'));
        $complementos->put('estadosTutelas', EnumsTrait::getEnumValues('mp_tutelas', 'estado'));
        $complementos->put('dependencias', PrDependencia::all());
        $complementos->put('tiposNotificaionesDireccionamiento', EnumsTrait::getEnumValues('mp_notificacions', 'tipo'));
        $complementos->put('especialidadesAutorizacion', AuEspecialidad::all());

        $complementos->put('especialidadesAutorizacion', AuEspecialidad::all());

        // Concurrencia
        $complementos->put('coningreso_vias', EnumsTrait::getEnumValues('cm_coningresos', 'via_ingreso'));
        $complementos->put('conaltocosto_tipos', EnumsTrait::getEnumValues('cm_conaltocostos', 'tipo'));
        $complementos->put('formasPagoEgreso', EnumsTrait::getEnumValues('ts_compegresos', 'forma_pago'));
        $complementos->put('especialidades', RsServicio::where('codigo', '>', 300)->get());
        $complementos->put('tipo_de_alto_costos', AsTipaltocosto::all());

        $complementos->put('nomenclaturasUrbano', TbNomenclatura::where([
            ['zona',1]
        ])->get());
        $complementos->put('nomenclaturasRural', TbNomenclatura::where([
            ['zona',2]
        ])->get());
        $complementos->put('nomenclaturas', TbNomenclatura::all());
        
        $complementos->put('periodosNovedades', TbVigenciaTraslado::where([
            ['tipo', 1]
        ])->orderBy('consecutivo_vigencia','DESC')->get());
        $complementos->put('periodosTraslados', TbVigenciaTraslado::where([
            ['tipo', 2]
        ])->orderBy('consecutivo_vigencia','DESC')->get());

        $complementos->put('periodosNovedadesCerrados', TbVigenciaTraslado::where([
            ['tipo', 1],
            ['sw_abierto', 0]
        ])->orderBy('consecutivo_vigencia','DESC')->get());
        $complementos->put('periodosTrasladosCerrados', TbVigenciaTraslado::where([
            ['tipo', 2],
            ['sw_abierto', 0]
        ])->orderBy('consecutivo_vigencia','DESC')->get());

        //Red de servicios
        //Asignación masiva
        $complementos->put('tiposProceso', EnumsTrait::getEnumValues('rs_asignamasivos', 'tipo_proceso'));
        // RJPT TB_PERIODOLIQUIDACION
        $complementos->put('periodoliquidacion', TbPeriodoLiquidacion::all());
        // $complementos->put('clasefondo', TbClaseFondo::all());
        $complementos->put('clasefondo', TbClaseFondo::where('tipo_fondo', '=', '1')->get());
        $complementos->put('claseentidad', TbClaseFondo::where('tipo_fondo', '2')->get());
        $complementos->put('profesion', TbProfesion::all());
        $complementos->put('tutelas', OjTutela::all());
        $complementos->put('aumedicosolicitante', AuMedicosSolicitante::all());
        $complementos->put('rscie10', RsCie10::all());
        $complementos->put('entidades', RsEntidade::select('id','nombre')->get());
        $complementos->put('fondosEntidades', TbFondo::join('tb_clase_fondo', 'tb_fondo.clase_fondo', '=', 'tb_clase_fondo.clase_fondo')
                                                     ->select('tb_fondo.descripcion', 'tb_fondo.fondo')
                                                     ->where('tb_clase_fondo.tipo_fondo', '2')
                                                     ->get());
        $complementos->put('fondosFondos', TbFondo::join('tb_clase_fondo', 'tb_fondo.clase_fondo', '=', 'tb_clase_fondo.clase_fondo')
                                                  ->select('tb_fondo.descripcion', 'tb_fondo.fondo')
                                                  ->where('tb_clase_fondo.tipo_fondo', '1')
                                                  ->get());
        $complementos->put('extras_laborales', TbExtraLaboral::all());
        $complementos->put('tipossoportes', TbTipoSoporte::all());
        $complementos->put('rechazos', TbRechazoPrestacionSocial::where('sw_activo', '1')->get());
        $complementos->put('causacionesextras',[
            ['id' => 1, 'nombre' => 'Quincenal'],
            ['id' => 2, 'nombre' => 'Mensual'],
            ['id' => 3, 'nombre' => 'Bimensual'],
            ['id' => 4, 'nombre' => 'Trimensual'],
            ['id' => 5, 'nombre' => 'Semestral'],
            ['id' => 6, 'nombre' => 'Anual']
        ]);
        $complementos->put('cbomeses', [
            ['id' => 1, 'nombre' => 'Enero'],
            ['id' => 2, 'nombre' => 'Febrero'],
            ['id' => 3, 'nombre' => 'Marzo'],
            ['id' => 4, 'nombre' => 'Abril'],
            ['id' => 5, 'nombre' => 'Mayo'],
            ['id' => 6, 'nombre' => 'Junio'],
            ['id' => 7, 'nombre' => 'Julio'],
            ['id' => 8, 'nombre' => 'Agosto'],
            ['id' => 9, 'nombre' => 'Septiembre'],
            ['id' => 10, 'nombre' => 'Octubre'],
            ['id' => 11, 'nombre' => 'Noviembre'],
            ['id' => 12, 'nombre' => 'Diciembre']
        ]);
        $complementos->put('estadosnominales',[
            ['id' => 0, 'nombre' => 'Anulada'],
            ['id' => 1, 'nombre' => 'Abierta'],
            ['id' => 2, 'nombre' => 'Liquidada'],
            ['id' => 3, 'nombre' => 'Pagada'],
            ['id' => 4, 'nombre' => 'Preliquidada'],
        ]);
        $complementos->put('periodosnominales', [
            ['id' => 1, 'nombre' => '1 Quincena'],
            ['id' => 2, 'nombre' => '2 Quincena']
        ]);
        $complementos->put('operadoresliquidacion', [
            ['id' => 1, 'nombre' => 'SOI']
        ]);
        $complementos->put('cencostos', TbCentroConsto::all());
        $complementos->put('areas', TbArea::all());
        $complementos->put('dependencias', TbDependencia::all());
        return response()->json([
            'data' => $complementos
        ], 200);

    }

    public function getVeredas($municipio_id)
    {
        $veredas = GnVereda::where('gn_municipio_id', $municipio_id)->get();
        return response()->json([
            'data' => $veredas
        ], 200);
    }

    public function getBarrios($municipio_id)
    {
        $barrios = GnBarrio::where('gn_municipio_id', $municipio_id)->get();
        return response()->json([
            'data' => $barrios
        ], 200);
    }

    public function getFechaLimite()
    {
        try {
            $fecha_inicial = CarbonColombia::createFromFormat('Y-m-d', Input::get('fecha_inicial'));
            $fecha_inicial->addBussinessDays(Input::get('plazo'));
            return $fecha_inicial->toDateString();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function firmarRuta()
    {
        $params = Input::toArray();
        $params['user'] = Auth::user();
        $name = $params['nombre_ruta'];
        $tiempo = isset($params['tiempo']) ? $params['tiempo'] : 7200;
        array_forget($params, ['nombre_ruta', 'tiempo']);
        return URL::temporarySignedRoute($name, now()->addSeconds($tiempo), $params);
    }
}
