<template>
  <div>
    <v-dialog v-model="dialog" persistent max-width="300">
        <v-card>
          <!--<v-card-title class="headline">Use Google's location service?</v-card-title>-->
          <v-card-text class="subheading font-weight-light">¿Realmente desea eliminar la prescripción?</v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn flat @click.native="dialog = false">No</v-btn>
            <v-btn color="primary" flat @click="eliminarPrescripcion(comDiario)">Si</v-btn>
          </v-card-actions>
        </v-card>
    </v-dialog>
    <v-card>
      <toolbar-list :info="infoComponent" title="Prescripciones" subtitle="">
        <template slot="actions">
          <v-menu offset-y left>
            <v-btn
              slot="activator"
              color="primary"
              dark
            >
              <v-icon class="mr-1">cloud_download</v-icon> descargas
            </v-btn>
            <v-list class="pa-0">
              <v-list-tile @click="goDowload(0)">
                <v-list-tile-title>Prescripciones por fecha</v-list-tile-title>
              </v-list-tile>
              <v-list-tile @click="goDowload(1)">
                <v-list-tile-title>Juntas médicas por fecha</v-list-tile-title>
              </v-list-tile>
              <v-list-tile @click="goDowload(2)">
                <v-list-tile-title>Histórico de juntas médicas</v-list-tile-title>
              </v-list-tile>
            </v-list>
          </v-menu>
        </template>
      </toolbar-list>
      <data-table
        ref="tablaPrescripciones"
        v-model="dataTable"
        @resetOption="item => resetOptions(item)"
        @detalle="item => $store.commit('NAV_ADD_ITEM', { ruta: 'detallePrescripcion', titulo: 'Detalle prescripción', parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
        @imprimir="item => imprimir(item)"
        @eliminar="item => eliminarRegistro(item)"
      ></data-table>
      <descargas v-model="descargas.visible" :tipo="descargas.tipo" @success="downloadSuccess"></descargas>
    </v-card>
  </div>
</template>

<script>
  import store from '@/store/complementos/index'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import DataTable from '@/components/general/DataTable'
  export default {
    name: 'Prescripciones',
    components: {
      DataTable,
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Descargas: () => import('@/components/mipres/descargas/Descargas')
    },
    data () {
      return {
        descargas: {
          visible: false,
          tipo: 0
        },
        dialog: false,
        comDiario: null,
        dataTable: {
          nameItemState: 'itemMipresPrescripciones',
          route: 'prescripciones',
          optionsPerPage: [
            {
              text: 50,
              value: 50
            },
            {
              text: 100,
              value: 100
            },
            {
              text: 200,
              value: 200
            }
          ],
          makeHeaders: [
            {
              text: 'Número',
              align: 'left',
              sortable: true,
              value: 'NoPrescripcion',
              classData: ''
            },
            {
              text: 'Fecha',
              align: 'left',
              sortable: true,
              value: 'FPrescripcion',
              classData: ''
            },
            {
              text: 'Identificación Paciente',
              align: 'left',
              sortable: false,
              value: 'identificacion',
              classData: '',
              component: {
                component: {
                  template:
                    `<mini-card-detail :data="value.mini_afiliado
                       ? value.mini_afiliado : {identificacion_completa: value.identificacion, nombre_completo: value.paciente, id: null, emoticon: '⚠️'}"/>`,
                  props: ['value']
                }
              }
            },
            {
              text: 'Código IPS',
              align: 'left',
              sortable: false,
              value: 'CodHabIPS',
              classData: ''
            },
            {
              text: 'Nombre IPS',
              align: 'left',
              sortable: false,
              value: 'ips',
              classData: ''
            },
            {
              text: 'Registros',
              align: 'center',
              sortable: false,
              value: 'id',
              classData: 'text-xs-center',
              width: '150px',
              component: {
                component: {
                  template:
                    `<div>
                        <v-tooltip top class="mr-2" v-if="value.medicamentos">
                          <v-badge overlap color="blue" slot="activator" class="cursor-pointer">
                          <span slot="badge" class="caption">{{value.medicamentos}}</span>
                            <v-avatar color="indigo" size="30">
                              <span class="white--text subheading">M</span>
                            </v-avatar>
                          </v-badge>
                          <span>Medicamentos</span>
                        </v-tooltip>
                        <v-tooltip top class="mr-2" v-if="value.procedimientos">
                          <v-badge overlap color="blue" slot="activator" class="cursor-pointer">
                          <span slot="badge" class="caption">{{value.procedimientos}}</span>
                            <v-avatar color="green" size="30">
                              <span class="white--text subheading">P</span>
                            </v-avatar>
                          </v-badge>
                          <span>Procedimientos</span>
                        </v-tooltip>
                        <v-tooltip top class="mr-2" v-if="value.dispositivos">
                          <v-badge overlap color="blue" slot="activator" class="cursor-pointer">
                          <span slot="badge" class="caption">{{value.dispositivos}}</span>
                            <v-avatar color="brown" size="30">
                              <span class="white--text subheading">D</span>
                            </v-avatar>
                          </v-badge>
                          <span>Dispositivos</span>
                        </v-tooltip>
                        <v-tooltip top class="mr-2" v-if="value.productosnutricionales">
                          <v-badge overlap color="blue" slot="activator" class="cursor-pointer">
                          <span slot="badge" class="caption">{{value.productosnutricionales}}</span>
                            <v-avatar color="purple" size="30">
                              <span class="white--text subheading">N</span>
                            </v-avatar>
                          </v-badge>
                          <span>Productos Nutricionales</span>
                        </v-tooltip>
                        <v-tooltip top class="mr-2" v-if="value.serviciosComplementarios">
                          <v-badge overlap color="blue" slot="activator" class="cursor-pointer">
                          <span slot="badge" class="caption">{{value.serviciosComplementarios}}</span>
                            <v-avatar color="teal" size="30">
                              <span class="white--text subheading">C</span>
                            </v-avatar>
                          </v-badge>
                          <span>Servicios Complementarios</span>
                        </v-tooltip>
                    </div>`,
                  props: ['value']
                }
              }
            },
            {
              text: 'Direccionamiento',
              align: 'center',
              sortable: false,
              value: 'direccionado',
              classData: 'text-xs-center',
              width: '100px',
              component: {
                component: {
                  template:
                    `<div>
                        <v-tooltip top>
                          <v-progress-linear class="cursor-pointer" slot="activator" :value="value.direccionado * 100"></v-progress-linear>
                          <span>{{Number((value.direccionado * 100).toFixed(2))}}%</span>
                        </v-tooltip>
                    </div>`,
                  props: ['value']
                }
              }
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              classData: 'text-xs-center',
              // singlesActions: true,
              width: '120px'
            }
          ],
          filters: [
            {
              label: 'Estado',
              type: 'v-autocomplete',
              items: () => this.complementos.estados,
              vModel: [],
              multiple: true,
              itemText: 'text',
              itemValue: 'value',
              value: 'estado',
              clearable: true
            },
            {
              label: 'Fecha Prescripción',
              type: 'v-range',
              items: {
                menuDate: false,
                type: 'date',
                range: true,
                itemMin: {
                  label: 'Inicial',
                  vModel: null,
                  clearable: true,
                  value: 'FPrescripcion'
                },
                itemMax: {
                  menuDate: false,
                  label: 'Final',
                  vModel: null,
                  clearable: true,
                  value: 'FPrescripcion'
                }
              }
            }
          ]
        }
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosTablaPrescripciones
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('prescripciones')
      }
    },
    methods: {
      downloadSuccess () {
        this.$refs.tablaPrescripciones.reloadPage()
      },
      goDowload (tipo) {
        this.descargas.tipo = tipo
        this.descargas.visible = true
      },
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.ver) item.options.push({event: 'detalle', icon: 'find_in_page', tooltip: 'Ver detalle', color: 'primary'})
        if (this.infoComponent && this.infoComponent.permisos.ver) item.options.push({event: 'eliminar', icon: 'remove_circle', tooltip: 'Eliminar', color: 'orange'})
        return item
      },
      eliminarRegistro (comdiario) {
        this.dialog = true
        this.comDiario = JSON.parse(JSON.stringify(comdiario))
      },
      eliminarPrescripcion (comdiario) {
        this.dialog = false
        // delete comdiario.show
        console.log(comdiario.id)
        this.axios.delete('prescripcion/eliminar/' + comdiario.id)
          .then((response) => {
            this.$store.commit(SNACK_SHOW, {msg: 'Se eliminó el registro correctamente', color: 'success'})
            this.$refs.tablaPrescripciones.reloadPage()
          }).catch((e) => {
            this.dialog = false
            this.$store.commit(SNACK_SHOW, {msg: e.response.data.message, color: 'danger'})
          })
      }
    }
  }
</script>

<style>
</style>
