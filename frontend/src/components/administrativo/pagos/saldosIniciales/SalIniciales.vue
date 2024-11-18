<template>
  <div>
    <v-dialog v-model="dialog" persistent max-width="300">
      <v-card>
        <v-card-text class="subheading font-weight-light">¿Realmente desea cambiar el estado de esta cuenta?</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn flat @click.native="dialog = false">No</v-btn>
          <v-btn color="primary" flat @click="salInicial.estado == 'Anulado' ? descAnulado(salInicial) : actualizarEstado(salInicial)">Si</v-btn>
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
          <v-btn color="primary" flat @click="actualizarEstado(salInicial, detalle_anulacion)">Enviar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card>
      <v-container fluid grid-list-xl>
        <v-layout row wrap>
          <v-flex xs12>
            <v-toolbar class="grey lighten-3 elevation-1 toolbar--dense">
              <v-toolbar-title>Saldos Iniciales </v-toolbar-title>
              <v-spacer></v-spacer>
              <v-btn
                fab
                right
                small
                color="accent"
                @click="$store.commit('NAV_ADD_ITEM',
                  { ruta: infoComponent.ruta_registro,
                    titulo: infoComponent.titulo_registro,
                    parametros: {
                      entidad: null,
                      tabOrigin: $store.state.nav.currentItem.split('tab-')[1]
                    }
                  })"
                v-if="infoComponent ? infoComponent.permisos.agregar : false">
                <v-icon>add</v-icon>
              </v-btn>
            </v-toolbar>
            <v-card-title class="elevation-1">
              <v-layout row wrap>
                <v-spacer></v-spacer>
                <v-flex xs12 sm3 md3>
                  <v-select
                    label="Registros por página"
                    v-model="pagination.per_page"
                    :items="items_page"
                    item-text="text"
                    item-value="value"
                  />
                </v-flex>
                <v-flex xs12 sm3 md2 v-if="salIniciales.length < 0">
                  <v-text-field
                    v-model="search"
                    append-icon="search"
                    label="Buscar"
                    hide-details
                    single-line
                  >
                  </v-text-field>
                </v-flex>
              </v-layout>
            </v-card-title>
            <v-data-table
              :headers="headers"
              :items="salIniciales"
              :loading="tableLoading"
              :pagination.sync="pagination"
              :total-items="salIniciales.length"
              hide-actions
              :search="search" class="elevation-1">
              <template slot="items" slot-scope="props">
                <tr class="white" :class="{'grey lighten-4': props.item.estado ? props.item.estado == 'Anulado' : ''}">
                  <td v-text="str_pad(props.item.consecutivo, 6)"></td>
                  <td v-text="props.item.fecha"></td>
                  <td v-text="props.item.tipo"></td>
                  <td v-text="props.item.proveedor.tercero.nombre_completo"></td>
                  <td class="text-xs-right">{{ props.item.valor | numberFormat(2,'$')}}</td>
                  <td v-text="props.item.estado"></td>
                  <td class="right">
                    <v-speed-dial
                      v-model="props.item.show"
                      :direction="direction"
                      :transition="transition"
                      open-on-hover>
                      <v-btn v-model="props.item.show" v-if="props.item.estado !== 'Anulado'" slot="activator"
                             color="blue darken-2" dark small
                             fab>
                        <v-icon>chevron_left</v-icon>
                        <v-icon>close</v-icon>
                      </v-btn>
                      <!--<v-tooltip bottom>-->
                        <!--<v-btn small fab-->
                               <!--color="white" slot="activator"-->
                               <!--@click.prevent="imprimir(props.item)">-->
                          <!--<v-icon color="black">far fa-file-pdf</v-icon>-->
                        <!--</v-btn>-->
                        <!--<span>Imprimir</span>-->
                      <!--</v-tooltip>-->
                      <v-tooltip bottom>
                        <v-btn small fab color="white"
                               slot="activator"
                               v-if="props.item.estado === 'Registrado'"
                               @click="preguntarCambiarEstado(props.item, 'Anulado')">
                          <v-icon color="grey">report_off</v-icon>
                        </v-btn>
                        <span>Anular</span>
                      </v-tooltip>
                      <v-tooltip bottom>
                        <v-btn small fab
                               slot="activator"
                               v-if="props.item.estado === 'Registrado'"
                               @click="$store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: infoComponent.titulo_registro, parametros: {entidad: props.item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })">
                          <v-icon color="accent">mode_edit</v-icon>
                        </v-btn>
                        <span>Editar</span>
                      </v-tooltip>
                      <v-tooltip bottom>
                        <v-btn small fab color="white"
                               slot="activator"
                               v-if="props.item.estado === 'Registrado'"
                               @click="preguntarCambiarEstado(props.item, 'Confirmado')">
                          <v-icon color="teal">check</v-icon>
                        </v-btn>
                        <span>Confirmar</span>
                      </v-tooltip>
                      <v-tooltip bottom>
                        <v-btn small fab
                               color="white" slot="activator"
                               v-if="props.item.estado === 'Confirmado'"
                               @click="$store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: infoComponent.titulo_registro, parametros: {entidad: props.item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })">
                          <v-icon color="teal">remove_red_eye</v-icon>
                        </v-btn>
                        <span>Ver Registro</span>
                      </v-tooltip>
                    </v-speed-dial>
                  </td>
                </tr>
              </template>
              <template slot="no-data">
                <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
                  Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
                </v-alert>
                <v-alert v-else :value="true" color="info" icon="info">
                  Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
                </v-alert>
              </template>
              <template slot="footer">
                <td colspan="100%">
                  <div class="text-xs-center">
                    <v-pagination v-model="pagination.current_page" :length="pagination.last_page" @input="reloadPage"></v-pagination>
                  </div>
                </td>
              </template>
            </v-data-table>
          </v-flex>
        </v-layout>
      </v-container>
    </v-card>
  </div>
</template>

<script>
  import {mapState} from 'vuex'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'SalIniciales',
    data () {
      return {
        salIniciales: [],
        tableLoading: false,
        transition: 'slide-x-transition',
        direction: 'left',
        fab: false,
        search: '',
        show: false,
        dialog: false,
        dialog2: false,
        detalle_anulacion: null,
        items_page: [
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
        pagination: {
          per_page: 15,
          current_page: 1
        },
        headers: [
          {
            text: 'Consecutivo',
            align: 'left',
            sortable: false,
            value: 'consecutivo'
          },
          {
            text: 'Fecha',
            align: 'left',
            sortable: false,
            value: 'fecha'
          },
          {
            text: 'Tipo',
            align: 'left',
            sortable: false,
            value: 'tipo'
          },
          {
            text: 'Proveedor',
            align: 'left',
            sortable: false,
            value: 'pg_proveedore_id'
          },
          {
            text: 'Valor',
            align: 'center',
            sortable: false,
            value: 'valor'
          },
          {
            text: 'Estado',
            align: 'left',
            sortable: false,
            value: 'estado'
          },
          {
            text: 'Opciones',
            align: 'right',
            sortable: false,
            value: 'id'
          }
        ]

      }
    },
    created () {
      this.reloadPage()
    },
    watch: {
      'pagination.per_page' (value) {
        this.pagination.current_page = 1
        this.reloadPage()
      },
      'itemSalinicial' (value) {
        value.salInicial.show = false
        // console.log(value)
        if (value.estado === 'crear') {
          this.salIniciales.splice(0, 0, value.salInicial)
        } else if (value.estado === 'editar') {
          this.salIniciales.splice(this.salIniciales.findIndex(x => x.id === value.salInicial.id), 1, value.salInicial)
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('salIniciales')
      },
      ...mapState({
        itemSalinicial: state => state.tables.itemSalinicial
      }),
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      // imprimir (item) {
      //   console.log('Abriendo DOM')
      //   let url = axios.defaults.baseURL + 'salIniciales/pdf/' + item.id
      //   let win = window.open(url, '_blank')
      //   win.focus()
      // },
      preguntarCambiarEstado (salInicial, nuevoEstado) {
        this.dialog = true
        console.log(salInicial)
        this.salInicial = JSON.parse(JSON.stringify(salInicial))
        this.salInicial.estado = nuevoEstado
      },
      descAnulado (salInicial) {
        this.dialog2 = true
      },
      actualizarEstado (salInicial, detalleAnulacion) {
        this.dialog = false
        this.dialog2 = false
        delete salInicial.show
        salInicial.detalle_anulacion = detalleAnulacion
        this.axios.put('pg_saliniciales/' + salInicial.id, salInicial)
          .then((response) => {
            console.log('Estado Actualizado')
            this.$store.commit(SNACK_SHOW, {msg: 'Se realizó el cambio de estado a ' + salInicial.estado, color: 'success'})
            this.salIniciales.splice(this.salIniciales.findIndex(salInicial => salInicial.id === this.salInicial.id), 1, this.salInicial)
            console.log(response)
          }).catch(e => {
            this.dialog = false
            this.$store.commit(SNACK_SHOW, {msg: 'Existe un error al actualizar el estado. ' + e.response, color: 'danger'})
          })
      },
      buscar: window.lodash.debounce(function () {
        this.pagination.current_page = 1
        this.reloadPage()
      }, 500),
      reloadPage () {
        this.tableLoading = true
        this.axios.get('pg_saliniciales?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page)
          .then((response) => {
            // console.log('response', response)
            // console.log(response)
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.salIniciales = response.data.data
            this.tableLoading = false
          }).catch(e => {
            this.tableLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de registros. ' + e.message, color: 'danger'})
          })
      }
    }
  }
</script>

<style scoped>

</style>
