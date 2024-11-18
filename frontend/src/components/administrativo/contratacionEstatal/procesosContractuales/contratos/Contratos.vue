<template>
  <v-card>
    <toolbar-list :info="infoComponent" title="Contratos" subtitle="Gestión"/>
    <data-table
      ref="tablaPContratos"
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @eliminar="item => eliminar(item)"
      @revisar="item => $store.commit('NAV_ADD_ITEM', { ruta: 'minutaContrato', titulo: `Minuta contrato: ${item.numero_contrato}`, parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
    />
    <confirmar
      :loading="dialogA.loading"
      :value="dialogA.visible"
      :content="dialogA.content"
      @cancelar="cancelaConfirmacion"
      :requiere-motivo="false"
      @aceptar="aceptaConfirmacion"
    />
  </v-card>
</template>

<script>
  import {SNACK_ERROR_LIST, SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'Contratos',
    components: {
      DataTable: () => import('@/components/general/DataTable'),
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Confirmar: () => import('@/components/general/Confirmar')
    },
    data () {
      return {
        dialogA: {
          loading: false,
          visible: false,
          content: null,
          registro: null
        },
        dataTable: {
          nameItemState: 'itemContrato',
          route: 'minutas',
          makeHeaders: [
            {
              text: 'Número',
              align: 'center',
              sortable: true,
              value: 'numero_contrato',
              classData: 'text-xs-center'
            },
            {
              text: 'Fecha Contrato',
              align: 'left',
              sortable: false,
              classData: 'text-xs-left',
              component: {
                component: {
                  template:
                    `<span>{{value.fecha_contrato ? moment(value.fecha_contrato).format('YYYY-MM-DD') : ''}}</span>`,
                  props: ['value']
                }
              }
            },
            {
              text: 'Entidad',
              align: 'left',
              sortable: true,
              value: 'entidad'
            },
            {
              text: 'Objeto',
              align: 'left',
              sortable: false,
              value: 'objeto'
            },
            {
              text: 'Fecha Inicio',
              align: 'left',
              sortable: false,
              classData: 'text-xs-left',
              width: '120px',
              component: {
                component: {
                  template:
                    `<span>{{value.fecha_acta_inicio ? moment(value.fecha_acta_inicio).format('YYYY-MM-DD') : ''}}</span>`,
                  props: ['value']
                }
              }
            },
            {
              text: 'Fecha Fin',
              align: 'right',
              sortable: true,
              classData: 'text-xs-right',
              value: 'fecha_finalizacion',
              width: '120px',
              component: {
                component: {
                  template: `
                    <div>
                      <v-tooltip top v-if="value.modificaciones_plazo">
                        <v-badge
                        slot="activator"
                        color="purple"
                        >
                          <span slot="badge">{{value.modificaciones_plazo}}</span>
                        </v-badge>
                        <span>Modificaciones en plazo</span>
                      </v-tooltip>
                      <p v-if="value.modificaciones_plazo">{{value.fecha_finalizacion ? moment(value.fecha_finalizacion).format('YYYY-MM-DD') : ''}}</p>
                      <span v-else>{{value.fecha_finalizacion ? moment(value.fecha_finalizacion).format('YYYY-MM-DD') : ''}}</span>
                    </div>
                  `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Valor Total',
              align: 'right',
              sortable: true,
              classData: 'text-xs-right',
              value: 'valor',
              component: {
                component: {
                  template: `
                    <div>
                      <v-tooltip top v-if="value.modificaciones_valor">
                        <v-badge
                        slot="activator"
                        color="purple"
                        >
                          <span slot="badge">{{value.modificaciones_valor}}</span>
                        </v-badge>
                        <span>Modificaciones en valor</span>
                      </v-tooltip>
                      <p v-if="value.modificaciones_valor">{{'$'}}{{value.valor | numberFormat(2)}}</p>
                      <span v-else>{{'$'}}{{value.valor | numberFormat(2)}}</span>
                    </div>
                  `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Estado',
              align: 'center',
              sortable: true,
              value: 'estado',
              classData: 'text-xs-center'
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              classData: 'text-xs-center'
            }
          ]
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('contratos')
      }
    },
    methods: {
      eliminar (item) {
        this.dialogA.registro = JSON.parse(JSON.stringify(item))
        this.dialogA.content = `<strong>El contrato ${this.dialogA.registro.numero_contrato} con la entidad ${this.dialogA.registro.entidad} será eliminado.</strong><br/> ¿Está seguro de eliminar el contrato?`
        this.dialogA.visible = true
      },
      cancelaConfirmacion () {
        this.dialogA.loading = false
        this.dialogA.visible = false
        this.dialogA.content = null
        this.dialogA.registro = null
      },
      async aceptaConfirmacion () {
        this.dialogA.loading = true
        this.axios.delete(`minutas/${this.dialogA.registro.id}`)
          .then(() => {
            this.$store.commit(SNACK_SHOW, {msg: 'El contrato se ha eliminado correctamente.', color: 'success'})
            this.$store.commit('CONTRATO_RELOAD_ITEM', {item: this.dialogA.registro, estado: 'reload', key: null})
            this.cancelaConfirmacion()
          })
          .catch(e => {
            this.dialogA.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al realizar la eliminación del contrato. ', error: e})
          })
      },
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push({event: 'editar', icon: 'build', tooltip: 'Gestionar', color: 'primary'})
        if (this.infoComponent && this.infoComponent.permisos.anular && item.estado === 'Registrado') item.options.push({event: 'eliminar', icon: 'delete_forever', tooltip: 'Eliminar', color: 'error'})
        if (this.infoComponent && this.infoComponent.permisos.confirmar && item.estado === 'Confirmado') item.options.push({event: 'revisar', icon: 'receipt', tooltip: 'Revisar minuta', color: 'orange'})
        return item
      }
    }
  }
</script>

<style scoped>
</style>
