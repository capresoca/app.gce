<template>
  <div>
    <repetir-comprobante v-model="dataRepetir" :dialog="dialogRepetirComprobante"  @cancelar="dialogRepetirComprobante = false"></repetir-comprobante>
    <v-dialog v-model="dialog" persistent max-width="300">
      <v-card>
        <!--<v-card-title class="headline">Use Google's location service?</v-card-title>-->
        <v-card-text class="subheading font-weight-light">¿Realmente desea cambiar el estado de este comprobante?</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn flat @click.native="dialog = false">No</v-btn>
          <v-btn color="primary" flat @click="comDiario.estado == 'Anulado' ? descAnulado(comDiario) : actualizarEstado(comDiario)">Si</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialog2" persistent max-width="400">
      <v-card>
        <!--<v-card-title class="headline">Use Google's location service?</v-card-title>-->
        <v-card-title class="grey lighten-3">
          <v-card-text class="title">¿Por qué anula este comprobante?</v-card-text>
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
              <!--<textarea box name="Detalle de anulación" v-model="detalle_anulacion" placeholder="Detalle de anulación"></textarea>-->
            </v-flex>
          </v-layout>
        </v-container>
        <v-card-actions>
          <v-spacer></v-spacer>
          <!--<v-btn flat @click.native="dialog = false">No</v-btn>-->
          <v-btn color="primary" flat @click="actualizarEstado(comDiario, detalle_anulacion)">Enviar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card>
      <toolbar-list :info="infoComponent" btnplus btnplustitle="Crear Comprobante de Diario" title="Comprobantes De Diario" subtitle="Registro y gestión"/>
      <data-table
        v-model="dataTableComdiario"
        @resetOption="item => resetOptions(item)"
        @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
        @confirmar="item => preguntarCambiarEstado(item, 'Confirmado')"
        @desconfirmar="item => preguntarCambiarEstado(item, 'Registrado')"
        @anular="item => preguntarCambiarEstado(item, 'Anulado')"
        @imprimir="item => imprimir(item)"
        @repetirComprobante="item => repetir(item)"
      />
    </v-card>
  </div>
</template>

<script>
  import {COMDIARIO_RELOAD_ITEM} from '../../../../store/modules/general/tables'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import RepetirComprobante from './RepetirComprobante'
  export default {
    name: 'ComDiarios',
    components: {
      RepetirComprobante,
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable: () => import('@/components/general/DataTable')
    },
    data () {
      return {
        dataTableComdiario: {
          nameItemState: 'itemComdiario',
          route: 'comdiarios',
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
              value: 'consecutivo'
            },
            {
              text: 'Fecha',
              align: 'center',
              sortable: false,
              value: 'fecha',
              classData: 'text-xs-center'
            },
            {
              text: 'Tipo',
              align: 'left',
              sortable: false,
              value: 'tipo'
            },
            {
              text: 'Valor',
              align: 'left',
              sortable: false,
              value: 'valor',
              classData: 'text-xs-right',
              component: {
                component: {
                  template: `
                    <div>
                      <span class="text-xs-right">
                        {{ value.valor | numberFormat(2,'$') }}
                      </span>
                    </div>
                  `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Estado',
              align: 'center',
              sortable: false,
              value: 'estado',
              classData: 'text-xs-center'
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              classData: 'justify-center layout px-0',
              value: 'id'
            }
          ],
          filters: [
            {
              label: 'Estado',
              type: 'v-autocomplete',
              items: () => [{text: 'Registrado', value: 'Registrado'}, {text: 'Confirmado', value: 'Confirmado'}, {text: 'Anulado', value: 'Anulado'}],
              vModel: [],
              multiple: true,
              itemText: 'text',
              itemValue: 'value',
              value: 'estado',
              clearable: true
            },
            {
              label: 'Tipo',
              type: 'v-autocomplete',
              items: () => this.tipComDiarios,
              vModel: [],
              multiple: true,
              itemText: 'nombre',
              itemValue: 'id',
              value: 'nf_tipcomdiario_id',
              clearable: true
            },
            {
              label: 'Fecha',
              type: 'v-range',
              items: {
                menuDate: false,
                type: 'date',
                range: true,
                itemMin: {
                  label: 'Inicial',
                  vModel: null,
                  clearable: true,
                  value: 'fecha'
                },
                itemMax: {
                  menuDate: false,
                  label: 'Final',
                  vModel: null,
                  clearable: true,
                  value: 'fecha'
                }
              }
            }
          ]
        },
        comDiarios: [],
        tipComDiarios: [],
        tableLoading: false,
        transition: 'slide-x-transition',
        direction: 'left',
        fab: false,
        search: '',
        show: false,
        dialog: false,
        dialog2: false,
        dialogRepetirComprobante: false,
        dataRepetir: null,
        detalle_anulacion: null
      }
    },
    created () {
      this.obtenerTipComDiarios()
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('comdiarios')
      }
    },
    methods: {
      repetir (item) {
        this.dataRepetir = item
        this.dialogRepetirComprobante = true
      },
      obtenerTipComDiarios () {
        this.axios.get('tipcomdiarios')
          .then(res => {
            this.tipComDiarios = res.data.data
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: e.response.data.message, color: 'danger'})
          })
      },
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push((item.estado === 'Registrado') ? {event: 'editar', icon: 'edit', tooltip: 'Editar'} : {event: 'editar', icon: 'remove_red_eye', tooltip: 'Ver'})
        if (this.infoComponent && this.infoComponent.permisos.ver && (item.estado === 'Registrado')) item.options.push({event: 'confirmar', icon: 'check', tooltip: 'Confirmar', color: 'green darken-3'})
        if (this.infoComponent && this.infoComponent.permisos.ver && (item.estado === 'Confirmado')) item.options.push({event: 'desconfirmar', icon: 'remove_circle', tooltip: 'Desconfirmar', color: 'orange'})
        if (this.infoComponent && this.infoComponent.permisos.ver && (item.estado === 'Registrado')) item.options.push({event: 'anular', icon: 'report_off', tooltip: 'Anular', color: 'red darken-1'})
        if (this.infoComponent && this.infoComponent.permisos.ver && (item.estado === 'Confirmado')) item.options.push({event: 'repetirComprobante', icon: 'file_copy', tooltip: 'Repetir Comprobante', color: 'teal'})
        if (this.infoComponent && this.infoComponent.permisos.ver) item.options.push({event: 'imprimir', icon: 'far fa-file-pdf', tooltip: 'Imprimir'})
        return item
      },
      imprimir (item) {
        let id = item.id
        this.axios.get('reportecomdiarios?id=' + id)
          .then(res => {
            let url = res.data
            let win = window.open(url, '_blank')
            win.focus()
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Error al Imprimir. ' + e.response, color: 'danger'})
          })
      },
      preguntarCambiarEstado (comdiario, nuevoEstado) {
        this.dialog = true
        this.comDiario = JSON.parse(JSON.stringify(comdiario))
        this.comDiario.estado = nuevoEstado
      },
      descAnulado (comdiario) {
        this.dialog2 = true
      },
      actualizarEstado (comdiario, detalleAnulacion) {
        this.dialog = false
        this.dialog2 = false
        // delete comdiario.show
        comdiario.detalle_anulacion = detalleAnulacion
        this.axios.put('comdiarios/' + comdiario.id, comdiario)
          .then((response) => {
            this.$store.commit(SNACK_SHOW, {msg: 'Se realizó el cambio de estado a ' + comdiario.estado, color: 'success'})
            this.$store.commit(COMDIARIO_RELOAD_ITEM, {
              item: response.data.data,
              estado: 'editar',
              key: null
            })
            this.detalle_anulacion = null
          }).catch((e) => {
            this.dialog = false
            this.$store.commit(SNACK_SHOW, {msg: e.response.data.message, color: 'danger'})
          })
      }
    }
  }
</script>

<style scoped>

</style>
