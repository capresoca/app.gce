<template>
  <div>
    <v-dialog v-model="dialog" persistent max-width="300">
      <v-card>
        <v-card-text class="subheading font-weight-light">¿Realmente desea cambiar el estado de esta cuenta?</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn flat @click.native="dialog = false">No</v-btn>
          <v-btn color="primary" flat @click="cxp.estado == 'Anulado' ? descAnulado(cxp) : actualizarEstado(cxp)">Si</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialog2" persistent max-width="400">
      <v-card>
        <v-card-title class="grey lighten-3">
          <v-card-text class="title">¿Por qué anula esta cuenta?</v-card-text>
        </v-card-title>
        <v-container fluid>
          <v-layout row wrap>
            <v-flex xs12>
              <v-textarea
                outline
                v-model="detalle_anulacion"
                color="primary"
                name="Detalle de anulación"
              >
                <div slot="label">
                  Detalle de anulación
                </div>
              </v-textarea>
            </v-flex>
          </v-layout>
        </v-container>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" flat @click="actualizarEstado(cxp, detalle_anulacion)">Enviar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card>
      <toolbar-list :info="infoComponent" btnplus btnplustitle="Crear CXP" title="Cuentas Por Pagar" subtitle="CXP"/>
      <data-table
        v-model="dataTableCxp"
        @resetOption="item => resetOptions(item)"
        @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: (item.estado !== 'Registrado') ? `Visualizando CXP No. ${str_pad(item.consecutivo, 8)}` : this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
        @confirmar="item => preguntarCambiarEstado(item, 'Confirmado')"
        @desconfirmar="item => preguntarCambiarEstado(item, 'Registrado')"
        @anular="item => preguntarCambiarEstado(item, 'Anulado')"
        @imprimir="item => imprimir(item)"
      />
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import {CXP_RELOAD_ITEM} from '@/store/modules/general/tables'
  export default {
    name: 'Cxps',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable: () => import('@/components/general/DataTable')
    },
    data () {
      return {
        dataTableCxp: {
          nameItemState: 'itemCxp',
          route: 'pg_cxps',
          optionsPerPage: [
            {
              text: 15,
              value: 15
            },
            {
              text: 50,
              value: 50
            },
            {
              text: 100,
              value: 100
            }
          ],
          makeHeaders: [
            {
              text: 'Consecutivo',
              align: 'left',
              sortable: false,
              value: 'consecutivo',
              component: {
                component: {
                  template: `
                    <div>
                      <span v-text="str_pad(value.consecutivo, 8)"></span>
                    </div>
                  `,
                  props: ['value']
                }
              }
            },
            {
              text: 'No. Factura',
              align: 'left',
              sortable: false,
              value: 'no_factura',
              component: {
                component: {
                  template: `
                    <div>
                      <v-chip label outline color="red" class="pl-2 pr-2" v-text="value.no_factura"></v-chip>
                    </div>
                  `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Proveedor',
              align: 'left',
              sortable: false,
              value: 'identificacion_completa',
              component: {
                component: {
                  template: `
                  <div>
                    <span
                    >
                    <b v-text="value.identificacion_completa + ' - '"></b>
                    {{value.nombre_completo}}
                    </span>
                  </div>
                  `,
                  props: ['value']
                }
              }
            },
            // {
            //   text: 'Proveedor',
            //   align: 'left',
            //   sortable: false,
            //   value: 'nombre_completo'
            // },
            {
              text: 'Valor',
              align: 'left',
              sortable: false,
              value: 'valor_moneda'
            },
            {
              text: 'Fecha Causación',
              align: 'left',
              sortable: false,
              value: 'fecha_causacion'
            },
            {
              text: 'Estado',
              align: 'left',
              sortable: false,
              value: 'estado'
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              classData: 'justify-center layout px-0'
            }
          ],
          filters: [
            {
              label: 'Módulo de enlace CXP',
              type: 'v-autocomplete',
              items: () => [
                {text: 'CXP', value: 'CXP'},
                {text: 'Inventarios', value: 'Inventarios'},
                {text: 'Saldo Inicial', value: 'Saldo Inicial'},
                {text: 'Activos Fijos', value: 'Activos Fijos'}
              ],
              vModel: [],
              multiple: true,
              itemText: 'text',
              itemValue: 'value',
              value: 'estado',
              clearable: true
            }
          ]
        },
        cxps: [],
        tableLoading: false,
        transition: 'slide-x-transition',
        direction: 'left',
        fab: false,
        show: false,
        dialog: false,
        dialog2: false,
        detalle_anulacion: null
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('cxps')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push((item.estado === 'Registrado') ? {event: 'editar', icon: 'edit', tooltip: 'Editar'} : {event: 'editar', icon: 'fas fa-eye', tooltip: 'Visualizar', size: '20px'})
        if (this.infoComponent && this.infoComponent.permisos.ver && (item.estado === 'Registrado')) item.options.push({event: 'confirmar', icon: 'check', tooltip: 'Confirmar'})
        if (this.infoComponent && this.infoComponent.permisos.ver && (item.estado === 'Confirmado') && item.modulo !== 'Saldo Inicial') item.options.push({event: 'desconfirmar', icon: 'remove_circle', tooltip: 'Desconfirmar'})
        if (this.infoComponent && this.infoComponent.permisos.ver && (item.estado === 'Registrado')) item.options.push({event: 'anular', icon: 'report_off', tooltip: 'Anular'})
        if (this.infoComponent && this.infoComponent.permisos.ver) item.options.push({event: 'imprimir', icon: 'far fa-file-pdf', tooltip: 'Imprimir'})
        return item
      },
      imprimir (item) {
        console.log('Abriendo DOM')
        let id = item.id
        this.axios.get('reportecxps?id=' + id)
          .then((res) => {
            let url = res.data
            let win = window.open(url, '_blank')
            win.focus()
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: e.response.data.msg, color: 'danger'})
          })
      },
      preguntarCambiarEstado (cxp, nuevoEstado) {
        this.dialog = true
        this.cxp = JSON.parse(JSON.stringify(cxp))
        this.cxp.estado = nuevoEstado
      },
      descAnulado (cxp) {
        this.dialog2 = true
      },
      actualizarEstado (cxp, detalleAnulacion) {
        this.dialog = false
        this.dialog2 = false
        delete cxp.show
        cxp.detalle_anulacion = detalleAnulacion
        this.axios.put('pg_cxps/' + cxp.id, cxp)
          .then((response) => {
            this.$store.commit(SNACK_SHOW, {msg: 'Se realizó el cambio de estado a ' + cxp.estado, color: 'success'})
            this.$store.commit(CXP_RELOAD_ITEM, {
              item: response.data.cxp,
              estado: 'editar',
              key: null
            })
            // this.cxps.splice(this.cxps.findIndex(cxp => cxp.id === this.cxp.id), 1, this.cxp)
          }).catch(e => {
            this.dialog = false
            this.$store.commit(SNACK_SHOW, {msg: 'Existe un error al actualizar el estado. ' + e.response, color: 'danger'})
          })
      }
    }
  }
</script>

<style scoped>

</style>
