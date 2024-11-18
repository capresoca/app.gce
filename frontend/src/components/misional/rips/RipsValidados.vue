<template>
  <v-card>
    <toolbar-list :info="infoComponent" title="RIPS Validados"></toolbar-list>
    <data-table-v2
            v-model="dataTable"
            @resetOption="item => resetOptions(item)"
            @descargarRips="item => descargarRips(item)"
            @detalleRadicacion="item => detalleRadicacion(item)"
            @updateTipoFacturacion="item => updateTipoFacturacion(item)"
          >
            <template slot="filters">
              <v-flex xs12 sm12 md6 lg4>
                <v-autocomplete
                  label="Entidad"
                  v-model="entidadRouteString"
                  item-value="id"
                  item-text="nombre"
                  :items="entidadesFiltro"
                  persistent-hint
                  :hint="entidadRouteString && !!entidadesFiltro.find(x => x.id === entidadRouteString) ? 'Código: ' + entidadesFiltro.find(x => x.id === entidadRouteString).cod_habilitacion : ''"
                  clearable
                  :filter="filterEntidades"
                >
                  <template slot="item" slot-scope="data">
                    <div style="width: 100% !important;">
                      <v-list-tile class="content-v-list-tile-p0">
                        <v-list-tile-content>
                          <v-list-tile-title>{{data.item.nombre}}</v-list-tile-title>
                          <v-list-tile-sub-title class=caption>Código: {{data.item.cod_habilitacion}}</v-list-tile-sub-title>
                        </v-list-tile-content>
                      </v-list-tile>
                    </div>
                  </template>
                </v-autocomplete>
              </v-flex>
            </template>
          </data-table-v2>
    <dialog-detalle-radicacion :btn-open="false" ref="dialogDetalleRadicacion"></dialog-detalle-radicacion>
    <v-dialog
      v-model="dialogdescarga"
      hide-overlay
      persistent
      width="300"
    >
      <v-card
        color="primary"
        dark
      >
        <v-card-text>
          Por favor espere, estamos descargando los archivos RIPS
          <v-progress-linear
            indeterminate
            color="white"
            class="mb-0"
          ></v-progress-linear>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
  import {mapState} from 'vuex'
  import DialogDetalleRadicacion from '@/components/misional/rips/DialogDetalleRadicacion'
  export default {
    name: 'RipsValidados',
    components: {
      DialogDetalleRadicacion,
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data: () => ({
      dataTable: {
        nameItemState: 'tableRipsRadicados',
        route: 'rs_rips_radicados',
        advanceFilters: true,
        makeHeaders: [
          {
            text: 'Código',
            align: 'left',
            sortable: false,
            value: 'cod_radicacion',
            component: {
              template:
                      `
                      <v-list-tile class="content-v-list-tile-p0">
                        <v-list-tile-content>
                          <v-list-tile-title class="body-2">RIP: {{value.cod_radicacion}}</v-list-tile-title>
                          <v-list-tile-title class="caption grey--text">Interno: {{value.cod_radicacion_ct}}</v-list-tile-title>
                        </v-list-tile-content>
                      </v-list-tile>
                    `,
              props: ['value']
            }
          },
          {
            text: 'Entidad',
            align: 'left',
            sortable: false,
            component: {
              template:
                      `
                      <v-list-tile class="content-v-list-tile-p0">
                        <v-list-tile-content>
                          <v-list-tile-title class="body-2" v-if="value.rs_entidad">{{value.rs_entidad.tipo.tipo}}: {{value.rs_entidad.nombre}}</v-list-tile-title>
                          <v-list-tile-sub-title class="caption grey--text" v-if="value.rs_entidad">Código habilitación: {{value.rs_entidad.cod_habilitacion}}</v-list-tile-sub-title>
                        </v-list-tile-content>
                      </v-list-tile>
                    `,
              props: ['value']
            }
          },
          {
            text: 'Modalidad',
            align: 'center',
            sortable: true,
            value: 'tipo_facturacion',
            classData: 'text-xs-center'
          },
          {
            text: 'Estado',
            align: 'left',
            sortable: true,
            value: 'estado_radicacion'
          },
          {
            text: 'Opciones',
            align: 'center',
            sortable: false,
            actions: true,
            singlesActions: true,
            classData: 'text-xs-center'
          }
        ]
      },
      entidadesFiltro: [],
      entidadRouteString: null,
      filterEntidades: (item, queryText) => {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.cod_habilitacion + ' ' + item.nombre)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      dialogEliminar: false,
      eliminarRip: {
        titulo: '',
        mensaje: ''
      },
      dialogdescarga: false
    }),
    watch: {
      'entidadRouteString' (val) {
        this.dataTable.route = val ? `rs_rips_radicados?rs_entidad_id=${val}` : 'rs_rips_radicados'
      }
    },
    computed: {
      ...mapState({
        currentUser: state => state.user.profile
      }),
      infoComponent () {
        return this.$store.getters.getInfoComponent('rips')
      },
      accesoUpdateTipoFact () {
        return (this.currentUser && this.currentUser.roles && this.currentUser.roles.find(x => x.id === 1 || x.id === 48))
      }
    },
    created () {
      this.getEntidadesRIPS()
    },
    methods: {
      detalleRadicacion (item) {
        this.$refs.dialogDetalleRadicacion.open(item.id)
      },
      getEntidadesRIPS () {
        this.axios.get(`entidades_filters`)
          .then((response) => {
            console.log('response rips', response)
            this.entidadesFiltro = response.data.data
          })
          .catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {
              expression: ` al traer las entidades con RIPS validados. `,
              error: e
            })
          })
      },
      resetOptions (item) {
        item.options = []
        item.options.push({event: 'descargarRips', color: 'cyan', icon: 'cloud_download', tooltip: 'Descargar Rips'})
        if (item.estado_radicacion === 'Confirmado') item.options.push({event: 'detalleRadicacion', color: 'info', icon: 'find_in_page', tooltip: 'Detalle radicación'})
        if (item.estado_radicacion === 'Validado' && this.accesoUpdateTipoFact) item.options.push({event: 'updateTipoFacturacion', color: 'teal', icon: 'fas fa-exchange-alt', tooltip: 'Cambiar Modalidad'})
        return item
      },
      descargarRips (data) {
        this.dialogdescarga = true
        this.axios({
          url: 'descargar-rips-validados/' + data.id,
          method: 'GET',
          responseType: 'blob' // important
        })
          .then((response) => {
            this.dialogdescarga = false
            const url = window.URL.createObjectURL(new Blob([response.data]))
            const link = document.createElement('a')
            link.href = url
            link.setAttribute('download', 'rips_validados_' + data.cod_radicacion + '.zip')
            document.body.appendChild(link)
            link.click()
          })
          .catch((error) => {
            this.dialogdescarga = false
            if (error.response.status === 513) {
              this.$store.commit('SNACK_SHOW', {msg: 'El directorio de archivos RIPS fue eliminado del servidor. Contacte con el administrador', color: 'error'})
            } else {
              this.$store.commit('SNACK_SHOW', {msg: 'Algo salio mal al descargar el archivo ' + error.response.message, color: 'error'})
            }
          })
      },
      updateTipoFacturacion (item) {
        let data = {
          id: item.id,
          tipo_facturacion: (item.tipo_facturacion === 'Evento' ? 'Capita' : 'Evento')
        }
        this.axios.put(`rs_rips_radicados/${item.id}`, data).then(res => {
          this.$store.commit('SNACK_SHOW', {msg: `Se modificó la modalidad del RIP #${res.data.cod_radicacion}.`, color: 'success'})
          this.$store.commit('reloadTable', 'tableRipsRadicados')
        }).catch(e => {
          this.$store.commit('SNACK_ERROR_LIST', {expression: ' actualizar el registro. ', error: e})
        })
      }
    }
  }
</script>

<style scoped>

</style>
