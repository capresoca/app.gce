<template>
<v-card>
  <toolbar-list :info="infoComponent" btnplus btnplustitle="Cuenta Médica" title="Cuentas Médicas" subtitle="Registro y gestión"></toolbar-list>
  <data-table-v2
    ref="tableCuentasMedicas"
    select-all
    v-model="dataTable"
    @resetOption="itemFactura => resetOptions(itemFactura)"
    @anular="item => anular(item)"
    @imprimir="item => imprimir(item, 1)"
    @impInforme="item => imprimir(item, 2)"
    @imprimirGlosa="item => imprimir(item, 3)"
    @ver="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: `Cuenta Médica N° ${item.consecutivo_radicado}`, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
    @selecteds="val => cuentasSeleccionadas = val"
    @apply-filters="aplicaFiltros"
    @enviarglosas="item => enviarGlosas(item)"
  >
    <template slot="filters">
      <v-flex xs12 sm12 md6>
        <v-autocomplete
          clearable
          label="Plan de cobertura"
          v-model="filtroRuta.contrato"
          :items="filters.contratos"
          item-value="id"
          item-text="nombre"
        ></v-autocomplete>
      </v-flex>
    </template>
  </data-table-v2>
  <envio-cuentas v-model="cuentasSeleccionadas" @enviado="enviado"></envio-cuentas>
  <confirmar
    :loading="dialogA.loading"
    :value="dialogA.visible"
    :content="dialogA.content"
    @cancelar="cancelaEnvioGlosas"
    :requiere-motivo="false"
    @aceptar="aceptaEnvioGlosas"
  />
</v-card>
</template>

<script>
  import {mapState} from 'vuex'
  export default {
    name: 'CmRadicada',
    components: {
      EnvioCuentas: () => import('@/components/misional/radicacionCuentas/EnvioCuentas.vue'),
      Confirmar: () => import('@/components/general/Confirmar')
    },
    data: () => ({
      dialogA: {
        loading: false,
        visible: false,
        content: null,
        registro: null
      },
      cuentasSeleccionadas: [],
      dataTable: {
        nameItemState: 'tableCuentasMedicas',
        route: 'cm_radicadas',
        advanceFilters: true,
        makeHeaders: [
          {
            selectable: true
          },
          {
            text: 'Radicado Cuenta',
            align: 'left',
            sortable: false,
            value: 'consecutivo_radicado'
          },
          {
            text: 'Tipo',
            align: 'left',
            sortable: false,
            value: 'tipo_facturacion'
          },
          {
            text: 'Radicado RIPS',
            align: 'left',
            sortable: false,
            value: 'radicado_entidad',
            component: {
              template:
                `
                      <div>
                        <v-list-tile class="content-v-list-tile-p0">
                          <v-list-tile-content v-if="value.radiccado_rip">
                            <v-list-tile-title class="body-1">Número: {{value.radiccado_rip}}</v-list-tile-title>
                            <v-list-tile-title class="body-1">Fecha: {{value.fecha_radicado}}</v-list-tile-title>
                          </v-list-tile-content>
                          <v-list-tile-content v-else>
                            <v-list-tile-title class="body-1">Sin RIPS</v-list-tile-title>
                          </v-list-tile-content>
                        </v-list-tile>
                      </div>
                    `,
              props: ['value']
            }
          },
          {
            text: 'IPS',
            align: 'left',
            sortable: false,
            value: 'entidad.nombre'
          },
          {
            text: 'Plan Cobertura',
            align: 'left',
            sortable: false,
            value: 'nombre'
          },
          {
            text: 'Usuario',
            align: 'left',
            sortable: false,
            value: 'gs_usuario_id',
            component: {
              template:
                `
                      <div>
                        <v-list-tile class="content-v-list-tile-p0">
                          <v-list-tile-content>
                            <v-list-tile-title class="body-1">{{value.usuario ? value.usuario.name : ''}}</v-list-tile-title>
                              <v-list-tile-sub-title class="caption">{{value.usuario ? value.usuario.email : ''}}</v-list-tile-sub-title>
                          </v-list-tile-content>
                        </v-list-tile>
                      </div>
                    `,
              props: ['value']
            }
          },
          {
            text: 'Estado',
            align: 'left',
            sortable: false,
            component: {
              template:
                `<span>{{value.estadolote.nombre_estado}}</span>`,
              props: ['value']
            }
          },
          {
            text: 'Avance Auditoria',
            align: 'center',
            sortable: false,
            value: 'avance_proceso',
            classData: 'text-xs-center',
            width: '100px',
            component: {
              template:
                `<div>
                        <v-tooltip v-if="value.estado === 'En Auditoria'" top>
                          <div slot="activator" class="elevation-2">
                            <v-progress-linear class="cursor-pointer" :value="value.avance_proceso"></v-progress-linear>
                          </div>
                          <span v-text="value.avance_proceso + ' %'"></span>
                        </v-tooltip>
                        <div v-else>
                          <v-chip color="red" text-color="white">Por asignar</v-chip>
                        </div>
                    </div>`,
              props: ['value']
            }
          },
          {
            text: 'Opciones',
            align: 'center',
            sortable: false,
            actions: true,
            classData: 'justify-center layout px-0'
          }
        ]
      },
      filters: {
        contratos: []
      },
      filtroRuta: {
        contrato: null
      },
      rutaBase: ''
    }),
    computed: {
      ...mapState({
        currentUser: state => state.user.profile
      }),
      esAuditor () {
        return this.currentUser && this.currentUser.roles && this.currentUser.roles.find(x => x.id === 43)
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('cmradicadas')
      }
    },
    created () {
      this.reloadComplementos()
      this.rutaBase = JSON.parse(JSON.stringify(this.dataTable.route))
    },
    methods: {
      enviado () {
        this.$refs.tableCuentasMedicas.deselectAll()
      },
      imprimir (item, numer) {
        let id = item.id
        this.axios.get(`firmar-ruta?nombre_ruta=${numer === 1 ? 'documeto_radicado' : (numer === 2 ? `informe_sosalud&user=${this.currentUser.id}` : 'reporte_glosas')}&id=` + id)
          .then((res) => {
            let url = res.data
            if (numer === 1 || numer === 2) {
              window.open(url)
            } else {
              window.fileDownload(url)
            }
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' Error al Imprimir.', error: e})
          })
      },
      anular (item) {
        let cmradicada = JSON.parse(JSON.stringify(item))
        cmradicada.estado = 'Anulado'
        delete cmradicada.options
        delete cmradicada.facturas
        delete cmradicada.show
        this.axios.put('cm_radicadas/' + cmradicada.id, cmradicada)
          .then(res => {
            this.$store.commit('SNACK_SHOW', {msg: 'Se anuló la radicación de la cuenta.', color: 'success'})
          }).catch(() => {
            this.$store.commit('SNACK_SHOW', {msg: 'Error al tratar de anular la radicación de la cuenta médica.', color: 'danger'})
          })
      },
      aplicaFiltros () {
        let rutaTemp = this.rutaBase
        if (this.filtroRuta.contrato) {
          rutaTemp = rutaTemp + (rutaTemp.indexOf('?') > -1 ? '&' : '?') + 'id_contrato=' + this.filtroRuta.contrato
        }
        this.dataTable.route = rutaTemp
      },
      reloadComplementos () {
        this.axios.get(`contratos_radicados`)
          .then((response) => {
            this.filters.contratos = response.data
          })
          .catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ` al traer los complementos para crear los filtros. `, error: e})
          })
      },
      resetOptions (item) {
        // item.selectable = (item.estadolote && item.estadolote.estado === 'Auditoria')
        item.selectable = (!!item.estadolote)
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar && item.estado !== 'Anulado' && item.estado !== 'En Proceso') item.options.push({event: 'anular', icon: 'not_interested', tooltip: 'Anular', color: 'red'})
        if (this.infoComponent && this.infoComponent.permisos.imprimir) item.options.push({event: 'imprimirGlosa', icon: 'far fa-file-excel', tooltip: 'Informe de Glosas', color: 'teal'})
        if (this.infoComponent && this.infoComponent.permisos.imprimir) item.options.push({event: 'impInforme', icon: 'far fa-file-pdf', tooltip: 'Informe Auditoría Facturas Radicadas', color: 'red'})
        if (this.infoComponent && this.infoComponent.permisos.agregar && item.estado === '') item.options.push({event: 'ver', icon: 'remove_red_eye', tooltip: 'Ver cuenta médica'})
        if (this.infoComponent && this.infoComponent.permisos.imprimir) item.options.push({event: 'imprimir', icon: 'far fa-file-pdf', tooltip: 'Radicado de Cuenta Médica', color: 'black'})
        if (this.infoComponent && this.infoComponent.permisos.agregar && !this.esAuditor && item.estadolote.estado === 'Auditoria' && item.avance_proceso >= 100 && item.total_glosas > 0 && item.enviar_glosas === 0) item.options.push({event: 'enviarglosas', icon: 'fas fa-paper-plane', tooltip: 'Responder Glosas', color: 'error'})
        return item
      },
      enviarGlosas (item) {
        this.dialogA.registro = JSON.parse(JSON.stringify(item))
        this.dialogA.content = `<strong>Las glosas del radicado No. ${this.dialogA.registro.consecutivo_radicado} serán enviadas al prestador.</strong><br/> ¿Está seguro de continuar?`
        this.dialogA.visible = true
      },
      cancelaEnvioGlosas () {
        this.dialogA.loading = false
        this.dialogA.visible = false
        this.dialogA.content = null
        this.dialogA.registro = null
      },
      async aceptaEnvioGlosas () {
        this.dialogA.loading = true
        this.axios.put(`cm_radicadas/${this.dialogA.registro.id}`, { id: this.dialogA.registro.id, enviar_glosas: true })
          .then(() => {
            this.$store.commit('SNACK_SHOW', {msg: 'Las glosas se han enviado al prestador.', color: 'success'})
            this.$store.commit('reloadTable', 'tableCuentasMedicas')
            this.cancelaEnvioGlosas()
          })
          .catch(e => {
            this.dialogA.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al realizar el envío de glosas al prestador. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
