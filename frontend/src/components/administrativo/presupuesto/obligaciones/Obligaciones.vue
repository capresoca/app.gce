<template>
  <div>
    <v-card>
      <toolbar-list :info="infoComponent" title="Obligaciones" subtitle="Registro y gestión" btnplus btnplustitle="Crear Item"/>
      <loading v-model="tableLoading" />
      <v-container fluid>
        <v-layout row wrap>
          <v-flex xs1 sm3 md6 class="text-xs-right">
            <v-tooltip top>
              <v-btn
                slot="activator"
                flat
                icon
                color="accent"
                @click="reloadPage"
              >
                <v-icon>cached</v-icon>
              </v-btn>
              <span>Actualizar registros</span>
            </v-tooltip>
          </v-flex>
          <v-flex xs12 sm3 md2>
            <v-select
              label="Registros por página"
              v-model="pagination.per_page"
              :items="items_page"
              item-text="text"
              item-value="value"
            />
          </v-flex>
          <v-flex xs12 sm6 md4>
            <v-text-field
              v-model="search"
              append-icon="search"
              label="Buscar"
              @input="buscar"
            />
          </v-flex>
        </v-layout>

        <v-data-table
          :headers="headers"
          :items="obligaciones"
          :loading="tableLoading"
          :pagination.sync="pagination"
          :total-items="obligaciones.length"
          :search="search"
          hide-actions
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.periodo }}</td>
            <td>{{ props.item.consecutivo }}</td>
            <td>{{ props.item.fecha }}</td>
            <td>{{ props.item.documento }}</td>
            <td>{{ props.item.valor_obligacion | numberFormat(0,'$')}}</td>
            <td>{{ props.item.estado }}</td>
            <td>
              <v-speed-dial
                v-model="props.item.show"
                direction="left"
                open-on-hover
                transition="slide-x-transition"
              >
                <v-btn
                  slot="activator"
                  v-model="props.item.show"
                  color="blue darken-2"
                  dark
                  fab
                  small
                >
                  <v-icon>chevron_left</v-icon>
                  <v-icon>close</v-icon>
                </v-btn>
                <v-tooltip top>
                  <v-btn
                    fab
                    dark
                    small
                    color="white"
                    slot="activator"
                    @click="$store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: infoComponent.titulo_registro, parametros: {entidad: props.item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
                  >
                    <v-icon v-if="props.item.estado === 'Registrada'" color="accent">mode_edit</v-icon>
                    <v-icon v-else color="accent">remove_red_eye</v-icon>
                  </v-btn>
                  <span v-if="props.item.estado === 'Registrada'">Editar</span>
                  <span v-else>Ver</span>
                </v-tooltip>

                <v-tooltip top>
                  <v-btn
                    fab
                    dark
                    small
                    color="white"
                    slot="activator"
                    @click="descargarPdf(props.item.id)"
                  >
                    <v-icon color="accent">far fa-file-pdf</v-icon>
                  </v-btn>
                  <span>Imprimir</span>
                </v-tooltip>
              </v-speed-dial>
            </td>
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
            <td colspan="100%" class="text-xs-center">
              <v-pagination v-model="pagination.current_page" :total-visible="7" :length="pagination.last_page" @input="reloadPage"></v-pagination>
            </td>
          </template>
        </v-data-table>
      </v-container>
    </v-card>
  </div>
</template>

<script>
  import {mapState} from 'vuex'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import Loading from '@/components/general/Loading'

  export default {
    name: 'Obligaciones',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Loading
    },
    data () {
      return {
        obligaciones: [],
        search: '',
        tableLoading: false,
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
            text: 'Periodo',
            align: 'left',
            sortable: false,
            value: 'periodo'
          },
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
            text: 'Objeto',
            align: 'left',
            sortable: false,
            value: 'objeto'
          },
          {
            text: 'Valor',
            align: 'left',
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
            align: 'left',
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
      'itemObligaciones' (value) {
        if (value.estado === 'crear') {
          this.obligaciones.splice(0, 0, value.item)
        } else if (value.estado === 'editar') {
          this.obligaciones.splice(this.obligaciones.findIndex(x => x.id === value.item.id), 1, value.item)
        }
      },
      'pagination.per_page' () {
        this.pagination.current_page = 1
        this.reloadPage()
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('obligaciones')
      },
      ...mapState({
        itemObligaciones: state => state.tables.itemObligaciones
      }),
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.axios.get('pr_obligaciones?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page + '&search=%25' + this.search + '%25')
          .then((response) => {
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.obligaciones = response.data.data
            this.tableLoading = false
          })
          .catch(e => {
            this.tableLoading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al hacer la busqueda de registros. ', error: e})
            return false
          })
      },
      buscar: window.lodash.debounce(function () {
        this.pagination.current_page = 1
        this.reloadPage()
      }, 500),
      descargarPdf (id) {
        this.axios.get(`firmar-ruta?nombre_ruta=reporte-pr-obligaciones&id=${id}`)
          .then(res => {
            window.open(res.data)
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Error al generar el archivo. ' + e, color: 'danger'})
          })
      }
    }
  }
</script>

<style scoped>

</style>
