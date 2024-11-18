import Vuex from 'vuex'
import axios from 'axios/index'
import Vue from 'vue'

export const COMP_OBTENER = 'COMP_OBTENER'
export const COMP_PROCESAR = 'COMP_PROCESAR'
export const COMP_REESTABLECER = 'COMP_REESTABLECER'

Vue.use(Vuex)
export default new Vuex.Store({
  strict: true,
  state: {
    // entidades
    tiposLocacion: ['Principal', 'Sede'],
    naturalezas: ['Publica', 'Privada', 'Mixta'],
    complejidades: ['Baja', 'Media', 'Alta'],
    // otros
    estadosBasicos: ['Activo', 'Inactivo'],
    nivelesSISBEN: [{id: '1', nombre: 'Nivel 1'}, {id: '2', nombre: 'Nivel 2'}, {id: '3', nombre: 'Nivel 3'}, {id: 'N', nombre: 'N'}],
    complementos: {},
    tipcomdiarios: []
  },
  modules: {
  },
  actions: {
    [COMP_OBTENER]: ({commit, dispatch}) => {
      return new Promise((resolve, reject) => {
        axios.get('complementos-all')
          .then((response) => {
            commit(COMP_PROCESAR, response.data.data)
            resolve()
          })
          .catch(error => {
            reject(error)
          })
      })
    }
  },
  mutations: {
    [COMP_PROCESAR]: (state, complementos) => {
      complementos.tipos_retencion.forEach((tipo, index) => {
        complementos.tipos_retencion[index] = {value: tipo, disabled: false}
      })
      state.complementos = complementos
    }
  },
  getters: {
    // Auditoría
    // Factura
    complementosFacturaAuditoria (state) {
      return {
        tiposAltoCosto: state.complementos.tipo_de_alto_costos,
        tiposRecobro: state.complementos.recobros
      }
    },
    complementosFormTipComdiarios (state) {
      return {
        tipcomdiarios: state.tipcomdiarios
      }
    },
    // Contratación estatal
    // Contratistas
    complementosRegistroContratista (state) {
      return {
        municipios: state.complementos.municipios,
        natjuridicas: state.complementos.natjuridicas
      }
    },
    // Procesos contractuales
    complementosProcesosContractualesFormBasicos (state) {
      return {
        dependencias: state.complementos.dependencias
      }
    },
    complementosProcesosContractualesFormEstudioPrevio (state) {
      return {
        tiposContratacion: state.complementos.tiposContratacion,
        tiposContrato: state.complementos.tiposContrato,
        tiposFormaPago: state.complementos.tiposFormaPago,
        garantias: state.complementos.garantias,
        municipios: state.complementos.municipios ? state.complementos.municipios.filter(x => x.gn_departamento_id === 11) : [],
        rsServicios: state.complementos.rsServicios ? state.complementos.rsServicios.filter(x => x.primario) : []
      }
    },
    // Relaciones laborales
    // Tabla de Relaciones laborales
    complementosTablaRelacionesLaborales (state) {
      return {
        estadosRelacionesLaborales: state.complementos && state.complementos.estadosRelacionesLaborales ? state.complementos.estadosRelacionesLaborales.map((obj) => {
          let rObj = {}
          rObj['value'] = obj
          rObj['text'] = obj
          return rObj
        }) : []
      }
    },
    // MIPRES
    // Tabla de prescripciones
    complementosTablaPrescripciones (state) {
      return {
        estados: state.complementos && state.complementos.estadosPrescripciones ? state.complementos.estadosPrescripciones.map((obj) => {
          let rObj = {}
          rObj['value'] = obj
          rObj['text'] = obj
          return rObj
        }) : []
      }
    },
    // Registro de notificaión en direccionamientos
    complementosRegistroNotificaionDireccionamiento (state) {
      return {
        tipos: state.complementos && state.complementos.tiposNotificaionesDireccionamiento ? state.complementos.tiposNotificaionesDireccionamiento.map((obj) => {
          let rObj = {}
          rObj['value'] = obj
          rObj['text'] = obj
          return rObj
        }) : []
      }
    },
    // Tabla de tutelas
    complementosTablaTutelas (state) {
      return {
        estados: state.complementos && state.complementos.estadosTutelas ? state.complementos.estadosTutelas.map((obj) => {
          let rObj = {}
          rObj['value'] = obj
          rObj['text'] = obj
          return rObj
        }) : []
      }
    },
    // Trámites
    // Tabla de afiliaciones
    complementosTablaAfiliaciones (state) {
      return {
        estadosFormafiliaciones: state.complementos && state.complementos.estadosFormafiliaciones ? state.complementos.estadosFormafiliaciones.map((obj) => {
          let rObj = {}
          rObj['value'] = obj
          rObj['text'] = obj
          return rObj
        }) : []
      }
    },
    // TESORERÍA
    // Registro de comprobantes de egreso
    complementosFormComprobantesEgresos (state) {
      return {
        formasPagoEgreso: state.complementos.formasPagoEgreso,
        cencostos: state.complementos.cencostos
      }
    },
    // Registro de afiliación subsidiado
    complementosFormAfiliacion (state) {
      return {
        tipos_tramite: state.complementos.tipos_tramite,
        ciius: state.complementos.ciius,
        arls: state.complementos.arls,
        regimenes: state.complementos.regimenes,
        tipafiliaciones: state.complementos.tipafiliaciones,
        tipafiliados: state.complementos.tipafiliados,
        tipcotizantes: state.complementos.tipcotizantes,
        clasecotizantes: state.complementos.clasecotizantes,
        tipdocidentidades: state.complementos.tipdocidentidades,
        estadosAfiliado: state.complementos.estadosAfiliado,
        municipios: state.complementos.municipios
      }
    },
    // Registro de traslado
    complementosFormTraslado (state) {
      return {
        tipdocidentidades: state.complementos.tipdocidentidades ? JSON.parse(JSON.stringify(state.complementos.tipdocidentidades)).filter(x => x.id !== 12) : null,
        arls: state.complementos.arls,
        epss: state.complementos.epss,
        estadosAfiliado: state.complementos.estadosAfiliado,
        ciius: state.complementos.ciius,
        clasecotizantes: state.complementos.clasecotizantes
      }
    },
    complementosRegistroAnetramites (state) {
      return {
        tipanexos: state.complementos.tipanexos,
        tipdocidentidades: state.complementos.tipdocidentidades
      }
    },
    // Trámite de autorización
    complementosFormAutorizacion (state) {
      return {
        tiposOrigen: state.complementos.tiposOrigen,
        modalidadesServicio: state.complementos.modalidadesServicio,
        estadosAfiliado: state.complementos.estadosAfiliado,
        municipios: state.complementos.municipios,
        zonas: state.complementos.zonas
      }
    },
    complementosFormRegistroMedico (state) {
      return {
        especialidadesAutorizacion: state.complementos.especialidadesAutorizacion
      }
    },
    // Trámite de afiliación de Recién nacido
    complementosFormAfiliacionRN (state) {
      return {
        tipdocidentidades: state.complementos.tipdocidentidades ? state.complementos.tipdocidentidades.filter(tipo => tipo.id === [3, 4].find(val => tipo.id === val)) : null,
        condicionesdiscapacidad: state.complementos.condicionesdiscapacidad,
        estadosAfiliado: state.complementos.estadosAfiliado
      }
    },
    complementosFormInfoBasicaAfiliado (state) {
      return {
        regimenes: state.complementos.regimenes,
        tipdocidentidades: state.complementos.tipdocidentidades,
        municipios: state.complementos.municipios,
        sexos: state.complementos.sexos
      }
    },
    // Anetramite
    complementosFormAnetramite (state) {
      return {
        tipanexos: state.complementos.tipanexos,
        tipdocidentidades: state.complementos.tipdocidentidades
      }
    },
    complementosTablaNovedades (state) {
      return {
        // estadosNovtramites: state.complementos && state.complementos.estadosNovtramites ? state.complementos.estadosNovtramites.map((obj) => {
        //   let rObj = {}
        //   rObj['value'] = obj
        //   rObj['text'] = obj
        //   return rObj
        // }) : []
      }
    },
    // Novtramite
    complementosTablaTraslados (state) {
      return {
        estadosTrasmovs: state.complementos && state.complementos.estadosTrasmovs ? state.complementos.estadosTrasmovs.map((obj) => {
          let rObj = {}
          rObj['value'] = obj
          rObj['text'] = obj
          return rObj
        }) : []
      }
    },
    complementosFormNovtramite (state) {
      return {
        tipdocidentidades: state.complementos.tipdocidentidades ? JSON.parse(JSON.stringify(state.complementos.tipdocidentidades)).filter(x => x.id !== 12) : null,
        tipcambiodocs: state.complementos.tipcambiodocs,
        municipios: state.complementos.municipios,
        clasecotizantes: state.complementos.clasecotizantes,
        ciius: state.complementos.ciius,
        condicionesdiscapacidad: state.complementos.condicionesdiscapacidad,
        parentescos: state.complementos.parentescos,
        condterminaciones: state.complementos.condterminaciones,
        nivelesSISBEN: state.nivelesSISBEN,
        pobespeciales: state.complementos.pobespeciales,
        zonasSisben: state.complementos.zonas,
        estadosAfiliado: state.complementos.estadosAfiliado,
        nomenclaturasUrbano: state.complementos.nomenclaturasUrbano,
        nomenclaturasRural: state.complementos.nomenclaturasRural
      }
    },
    // Solicitudes de autorización
    complementosTablaSolicitudesAutorizacion (state) {
      return {
        estadosSolAutorizacion: state.complementos && state.complementos.estadosSolAutorizacion ? state.complementos.estadosSolAutorizacion.map((obj) => {
          let rObj = {}
          rObj['value'] = obj
          rObj['text'] = obj
          return rObj
        }) : []
      }
    },
    // Entidades
    complementosEntidadFormInformacionBasica (state) {
      return {
        tiposEntidad: state.complementos.tiposEntidad,
        tiposLocacion: state.tiposLocacion,
        naturalezas: state.naturalezas,
        complejidades: state.complejidades,
        municipios: state.complementos.municipios
      }
    },
    // Contratos
    complementosContratoFormDatosBasica (state) {
      return {
        tiposContrato: state.complementos.tiposContrato
      }
    },
    complementosContratoFormNovedad (state) {
      return {
        tiposNovedad: state.complementos.tiposNovedad
      }
    },
    // Pagadores
    complementosTablaPagadores (state) {
      return {
        tipaportantes: state.complementos.tipaportantes
      }
    },
    complementosFormPagadores (state) {
      return {
        tipaportantes: state.complementos.tipaportantes
      }
    },
    // Afiliados
    complementosFormAfiliados (state) {
      return {
        zonas: state.complementos.zonas,
        arls: state.complementos.arls,
        afps: state.complementos.afps,
        tiposdiscapacidad: state.complementos.tiposdiscapacidad,
        condicionesdiscapacidad: state.complementos.condicionesdiscapacidad,
        pobespeciales: state.complementos.pobespeciales,
        etnias: state.complementos.etnias,
        sexos: state.complementos.sexos,
        ccfs: state.complementos.ccfs,
        tipdocidentidades: state.complementos.tipdocidentidades,
        regimenes: state.complementos.regimenes,
        ciius: state.complementos.ciius,
        nivelesSisben: state.complementos && state.complementos.nivelesSisben ? state.complementos.nivelesSisben.map((obj) => String(obj)) : [],
        municipios: state.complementos.municipios,
        paises: state.complementos.paises,
        tipafiliados: state.complementos.tipafiliados,
        estadosAfiliado: state.complementos.estadosAfiliado
      }
    },
    // Detalle General Básicos Afiliados
    complementosDetalleGeneralBasicosAfiliado (state) {
      return {
        municipios: state.complementos.municipios,
        paises: state.complementos.paises
      }
    },
    // Postulador Afiliado
    complementosPostuladorAfiliado (state) {
      return {
        tipdocidentidades: state.complementos.tipdocidentidades
      }
    },
    // Postulador afiliado
    complementosPostuladorAfiliados (state) {
      return {
        municipios: state.complementos.municipios
      }
    },
    // Terceros
    complementosTablaTerceros (state) {
      return {
        tipos_contribuyente: state.complementos && state.complementos.tipos_contribuyente ? state.complementos.tipos_contribuyente.map((obj) => {
          let rObj = {}
          rObj['value'] = obj
          rObj['text'] = obj
          return rObj
        }) : []
      }
    },
    complementosTercerosFormBasicos (state) {
      return {
        tipdocidentidades: state.complementos.tipdocidentidades,
        municipios: state.complementos.municipios
      }
    },
    complementosTercerosFormComplementarios (state) {
      return {
        municipios: state.complementos.municipios,
        nomenclaturasUrbano: state.complementos.nomenclaturasUrbano,
        nomenclaturasRural: state.complementos.nomenclaturasRural
      }
    },
    complementosTercerosFormFiscal (state) {
      return {
        tipos_persona: state.complementos.tipos_persona,
        tipos_contribuyente: state.complementos.tipos_contribuyente,
        tipos_retencion: state.complementos.tipos_retencion,
        autorretenedores: state.complementos.autorretenedores,
        ciius: state.complementos.ciius,
        tiposterceros: state.complementos.tiposterceros
      }
    },
    // Beneficiarios
    complementosFormBeneficiario (state) {
      return {
        tipdocidentidades: state.complementos.tipdocidentidades,
        parentescos: state.complementos.parentescos,
        estadosAfiliado: state.complementos.estadosAfiliado
      }
    },
    // Niif
    complementosFormNiif (state) {
      return {
        clasescuentas: state.complementos.clasescuentas,
        nivelescuentas: state.complementos.nivelescuentas,
        anexosdeclaracion: state.complementos.anexosdeclaracion,
        autorretenedores: state.complementos.autorretenedores,
        tiposniif: state.complementos.tiposniif,
        tiposreteniif: state.complementos.tiposreteniif,
        dian: state.complementos.dian,
        alcaldia: state.complementos.alcaldia
      }
    },
    // Comprobantes Diarios
    complementosFormComDiario (state) {
      return {
        tipcomdiarios: state.complementos.tipcomdiarios,
        cencostos: state.complementos.cencostos,
        conrtfs: state.complementos.conrtfs
      }
    },
    // Proveedor
    complementosFormProveedor: state => { return {tiposProveedor: state.complementos.tiposProveedor} },
    // CXP
    complementosFormCxp (state) {
      return {
        conpagos: state.complementos.conpagos,
        uniapoyos: state.complementos.uniapoyos,
        cencostos: state.complementos.cencostos,
        conrtfs: state.complementos.conrtfs
      }
    },
    // Empresa
    complementosEmpresa (state) {
      return {
        municipios: state.complementos.municipios,
        tipcomdiarios: state.complementos.tipcomdiarios,
        cargosEmpleado: state.complementos.cargosEmpleado
      }
    },
    // Usuarios
    complementosFormUsuario: state => state.complementos.tipusuarios,

    // Bancos
    complementosFormBancos (state) {
      return {
        bancos: state.complementos.bancos
      }
    },
    // Causal Traslado
    complementosFormCausalTraslado (state) {
      return {
        bancos: state.complementos.cautraslados
      }
    },
    // Plan Adquisición estado
    complementosPlanAdquisicionEstado (state) {
      return {
        planadquisicionEstado: state.complementos.planadquisicionEstado
      }
    },
    // Modelo contratación
    complementosModcontrataciones (state) {
      return {
        modcontrataciones: state.complementos.modcontrataciones
      }
    },
    // Tutelas
    complementosTutelas (state) {
      return {
        tipdocidentidades: state.complementos.tipdocidentidades,
        pretenciones: state.complementos.pretenciones,
        juzgados: state.complementos.juzgados,
        tiposTutela: state.complementos.tiposTutela,
        departamentos: state.complementos.departamentos
      }
    },
    // Presupuesto
    complementosPresupuesto (state) {
      return {
        tipoIngresos: state.complementos.tipoIngresos,
        tipoGastos: state.complementos.tipoGastos,
        dependencias: state.complementos.dependencias
      }
    },
    // Cie10
    complementosCie10: state => { return { generos: state.complementos.generos } },
    complementosReferencias (state) {
      return {
        estadosReferencias: state.complementos.estadosReferencias,
        tipoOrigenRefs: state.complementos.tipoOrigenRefs,
        estadoEgresoRefs: state.complementos.estadoEgresoRefs,
        modalidadesServicio: state.complementos.modalidadesServicio,
        rsServicios: state.complementos.rsServicios,
        tipacciones: state.complementos.tipacciones
      }
    },
    // Concurrencias
    complementosConcurrencias (state) {
      return {
        rsServicios: state.complementos.rsServicios,
        especialidades: state.complementos.especialidades
      }
    },
    // RED DE SERVICIOS
    // Registro de Asignación Masiva
    complementosAsignacionMasiva (state) {
      return {
        regimenes: state.complementos.regimenes,
        municipios: state.complementos.municipios ? state.complementos.municipios.filter(x => x.gn_departamento_id === 11) : [],
        tiposProceso: state.complementos.tiposProceso
      }
    },
    complementosGruposIPS (state) {
      return {
        municipios: state.complementos.municipios,
        departamentos: state.complementos.departamentos
      }
    },
    complementosPeriodosEnvios (state) {
      return {
        periodosNovedades: state.complementos.periodosNovedades,
        periodosTraslados: state.complementos.periodosTraslados,
        periodosNovedadesCerrados: state.complementos.periodosNovedadesCerrados,
        periodosTrasladosCerrados: state.complementos.periodosTrasladosCerrados
      }
    },
    complementosTutelasIncapacidad (state) {
      return {
        tutelas: state.complementos.tutelas
      }
    },
    complementosClasesFondos (state) {
      return {
        clasefondo: state.complementos.clasefondo
      }
    },
    complementosEntidadesFinancieras (state) {
      return {
        claseentidad: state.complementos.claseentidad
      }
    },
    complementosProfesion (state) {
      return {
        profesiones: state.complementos.profesion
      }
    },
    complementosFondosEntidades (state) {
      return {
        fondosEntidades: state.complementos.fondosEntidades
      }
    },
    complementosFondosF (state) {
      return {
        fondosEntidades: state.complementos.fondosFondos
      }
    },
    complementoscontExtrasLaborales (state) {
      return {
        extras_laborales: state.complementos.extras_laborales,
        causacionesextras: state.complementos.causacionesextras
      }
    },
    complementosNominales (state) {
      return {
        cbomeses: state.complementos.cbomeses,
        estadosnominales: state.complementos.estadosnominales,
        periodosnominales: state.complementos.periodosnominales,
        cencostos: state.complementos.cencostos,
        areas: state.complementos.areas,
        dependencias: state.complementos.dependencias,
        operadoresliquidacion: state.complementos.operadoresliquidacion
      }
    },
    // RJPT Tabla Tb_PeriodoLiquidacion
    complementosPeriodoLiquidacion (state) {
      return {
        periodoliquidacion: state.complementos.periodoliquidacion,
        departamentos: state.complementos.departamentos
      }
    },
    complementosTipoSoporte (state) {
      return {
        tiposoporte: state.complementos.tipossoportes
      }
    },
    complementosRechazos (state) {
      return {
        rechazos: state.complementos.rechazos
      }
    },
    complementosIncapacidades (state) {
      return {
        entidades: state.complementos.entidades,
        medicosolicitante: state.complementos.aumedicosolicitante,
        cie10: state.complementos.rscie10
      }
    }
  }

})
