const state = {
  tables: {
    tableTerceros: 0,
    tableTercerosVersion: 3,
    tablePortabilidad: 0,
    tablePortabilidadVersion: 3,
    tablePortabilidadAprobacion: 0,
    tablePortabilidadAprobacionVersion: 3,
    tableRipsRadicados: 0,
    tableRipsRadicadosVersion: 3,
    tableFacturasRadicadas: 0,
    tableFacturasRadicadasVersion: 4,
    tableCuentasMedicas: 0,
    tableCuentasMedicasVersion: 6,
    tableGruposIPS: 0,
    tableGruposIPSVersion: 7,
    tableAutorizacionesFuncionarios: 0,
    tableAutorizacionesFuncionariosVersion: 2,
    tableAutorizacionesPrestadores: 0,
    tableAutorizacionesPrestadoresVersion: 2,
    tableAsignacionMasiva: 0,
    tableAsignacionMasivaVersion: 2,
    tableRespuestasBDUA: 0,
    tableRespuestasBDUAVersion: 1,
    tableSolicitudesTrasladosS2: 0,
    tableSolicitudesTrasladosS2Version: 1,
    tableSolicitudesTrasladosR2: 0,
    tableSolicitudesTrasladosR2Version: 1,
    tableDepuracionNovedades: 0,
    tableDepuracionNovedadesVersion: 1,
    tableDepuracionMs: 0,
    tableDepuracionMsVersion: 2,
    tableCentroCosto: 0,
    tableCentroCostoVersion: 1,
    tableAreaTh: 0,
    tableAreaThVersion: 1,
    tableDependenciaTh: 0,
    tableDependenciaThVersion: 1,
    tableCargoTh: 0,
    tableCargoThVersion: 1,
    tableTipoContratoTh: 0,
    tableTipoContratoThVersion: 1,
    tableContratosEmpleado: 0,
    tableContratosEmpleadoVersion: 3,
    tableContratosExtrasEmpleado: 0,
    tableContratosExtrasEmpleadoVersion: 3,
    tableContratosRemuneracionesEmpleado: 0,
    tableContratosRemuneracionesEmpleadoVersion: 3,
    tableScNominalLiquidacion: 0,
    tableScNominalLiquidacionVersion: 3,
    tableReccompensaciones: 0,
    tableReccompensacionesVersion: 1,
    tableReflogbancario: 0,
    tableReflogbancarioVersion: 1,
    tableItemAffiliate: 0,
    tableItemAffiliateVersion: 1,
    tableItemAfilliatum: 0,
    tableItemAfilliatumVersion: 1,
    tableItemTrasladosAfiliados: 0,
    tableItemTrasladosAfiliadosVersion: 1
  }
}

// getters
const getters = {
}

// actions
const actions = {
}

// mutations
const mutations = {
  reloadTable (state, table) {
    state.tables[table] = state.tables[table] + 1
  }
}

export default {
  state,
  getters,
  actions,
  mutations
}
