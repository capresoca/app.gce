<template>
    <div>
      <v-card>
        <v-container fluid grid-list-xs>
          <v-layout row wrap>
            <v-flex xs12>
              <!--<toolbar-list class="elevation-1 grey lighten-4" :info="infoComponent" title="Conceptos de Cartera" btnplus btnplustitle="Crear Conceptos Cartera"/>-->
              <v-toolbar class="grey lighten-3 elevation-1 toolbar--dense">
                <v-toolbar-title>Conceptos de Cartera </v-toolbar-title>
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
                      item-value="value"></v-select>
                  </v-flex>
                  <v-flex xs12 sm3 md2 v-if="conceptosCartera.length < 0">
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
                :items="conceptosCartera"
                :loading="tableLoading"
                :pagination.sync="pagination"
                :total-items="conceptosCartera.length"
                hide-actions
                :search="search" class="elevation-1">
                <template slot="items" slot-scope="props">
                  <tr class="white" :class="{'grey lighten-4': props.item.estado ? props.item.estado == 'Anulado' : ''}">
                    <td>{{ props.item.codigo}}</td>
                    <td>{{ props.item.nombre }}</td>
                    <td>{{ props.item.tipo_concepto }}</td>
                    <td>{{ props.item.niif ? props.item.niif.codigo : 'No Registra'  }}</td>
                    <td class="right">
                      <v-speed-dial
                        v-model="props.item.show"
                        :direction="direction"
                        :transition="transition"
                        open-on-hover>
                        <v-btn v-model="props.item.show" slot="activator"
                               color="blue darken-2" dark small
                               fab>
                          <v-icon>chevron_left</v-icon>
                          <v-icon>close</v-icon>
                        </v-btn>
                        <v-tooltip bottom>
                          <v-btn small fab
                                 slot="activator"
                                 @click="$store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: infoComponent.titulo_registro, parametros: {entidad: props.item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })">
                            <v-icon color="accent">mode_edit</v-icon>
                          </v-btn>
                          <span>Editar</span>
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
  import DataTable from '@/components/general/DataTable'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import {mapState} from 'vuex'
  export default {
    name: 'ConceptosCartera',
    data () {
      return {
        conceptosCartera: [],
        search: '',
        nameItemState: 'itemConceptoCartera',
        route: 'conceptoscartera',
        tableLoading: false,
        transition: 'slide-x-transition',
        direction: 'left',
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
            text: 'Codigo',
            align: 'left',
            sortable: false,
            value: 'codigo'
          },
          {
            text: 'Nombre',
            align: 'left',
            sortable: false,
            value: 'nombre'
          },
          {
            text: 'Tipo de concepto',
            align: 'left',
            sortable: false,
            value: 'tipo_concepto'
          },
          {
            text: 'Cuenta',
            align: 'left',
            sortable: false,
            value: 'niif.codigo'
          },
          {
            text: 'Opciones',
            align: 'center',
            sortable: false,
            actions: true,
            classData: 'justify-center layout px-0'
          }
        ]
      }
    },
    created () {
      this.reloadPage()
    },
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable
    },
    watch: {
      'pagination.per_page' (value) {
        this.pagination.current_page = 1
        this.reloadPage()
      },
      'itemConceptoCartera' (value) {
        // console.log('Value actual', value)
        value.conceptoCartera.show = false
        // console.log(value)
        if (value.estado === 'crear') {
          this.conceptosCartera.splice(0, 0, value.conceptoCartera)
        } else if (value.estado === 'editar') {
          this.conceptosCartera.splice(this.conceptosCartera.findIndex(x => x.id === value.conceptoCartera.id), 1, value.conceptoCartera)
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('conceptosCartera')
      },
      ...mapState({
        itemConceptoCartera: state => state.tables.itemConceptoCartera
      })
    },
    methods: {
      buscar: window.lodash.debounce(function () {
        this.pagination.current_page = 1
        this.reloadPage()
      }, 500),
      reloadPage () {
        this.tableLoading = true
        this.axios.get('conceptoscartera?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page)
          .then((response) => {
            // console.log('res', response.data)
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.conceptosCartera = response.data.data
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
