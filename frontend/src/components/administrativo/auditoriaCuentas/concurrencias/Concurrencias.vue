<template>
  <div>
    <v-card>
      <toolbar-list :info="infoComponent" title="Concurrencias" subtitle="Registro y gestión" btnplus btnplustitle="Crear Item"/>
      <loading v-model="tableLoading" />
        <v-container fluid grid-list-xl class="pa-1">
          <v-layout row wrap>
            <v-flex xs12 sm12 class="gridMd">
              <v-autocomplete
                :items="funcionarios"
                v-model="funcionario"
                @change="() => filtrarConcurrencias()"
                item-text="usuario.name"
                item-value="usuario.id"
                name="funcionario"
                label="Funcionario"
                persistent-hint
                no-data-text="No existen datos"
                prepend-icon="person"
              >
                <template slot="item" slot-scope="data">
                  <template>
                    <v-list-tile-content>
                      <v-list-tile-title v-html="data.item.usuario.name"/>
                      <v-list-tile-sub-title v-html="data.item.identificacion_completa"/>
                    </v-list-tile-content>
                  </template>
                </template>
              </v-autocomplete>
            </v-flex>
            <v-flex xs5 sm4 md2 class="text-xs-right">
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
            <v-flex xs7 sm2 md1>
              <v-select
                label="Por página"
                v-model="pagination.per_page"
                :items="items_page"
                item-text="text"
                item-value="value"
              ></v-select>
            </v-flex>
            <v-flex xs12 sm6 md3>
              <v-text-field
                v-model="search"
                append-icon="search"
                label="Buscar"
                @input="buscar"
              ></v-text-field>
            </v-flex>
          </v-layout>
        </v-container>

        <v-data-table
          :headers="headers"
          :items="concurrencias"
          :loading="tableLoading"
          :pagination.sync="pagination"
          :total-items="concurrencias.length"
          :search="search"
          hide-actions
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.consecutivo }}</td>
            <td>
              <mini-card-detail :data="props.item.afiliado.mini_afiliado"/>
            </td>
            <td><v-chip color="red" text-color="white">{{ props.item.tiempo_estancia }} días</v-chip></td>
            <td>{{ props.item.consecutivo_ips }}</td>
            <td>{{ props.item.entidad.nombre }}</td>
            <td>{{ props.item.fecha }}</td>
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
                    @click="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleConcurrencia', titulo: 'Detalle concurrencia', parametros: {entidad: props.item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
                  >
                    <v-icon color="accent">remove_red_eye</v-icon>
                  </v-btn>
                  <span>Ver</span>
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
              <v-pagination v-model="pagination.current_page" :total-visible="7" :length="pagination.last_page" @input="filtrarConcurrencias"></v-pagination>
            </td>
          </template>
        </v-data-table>
    </v-card>
  </div>
</template>

<script>
  import {mapState} from 'vuex'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import Loading from '@/components/general/Loading'

  export default {
    name: 'Concurrencias',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Loading
    },
    data () {
      return {
        concurrencias: [],
        funcionarios: [],
        funcionario: '',
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
            text: 'Consecutivo',
            align: 'left',
            sortable: false,
            value: 'consecutivo'
          },
          {
            text: 'Afiliado',
            align: 'left',
            sortable: false,
            value: 'afiliado'
          },
          {
            text: 'Estancia',
            align: 'left',
            sortable: false,
            value: 'estancia'
          },
          {
            text: 'Consecutivo IPS',
            align: 'left',
            sortable: false,
            value: 'consecutivo ips'
          },
          {
            text: 'Entidad',
            align: 'left',
            sortable: false,
            value: 'entidad'
          },
          {
            text: 'Fecha',
            align: 'left',
            sortable: false,
            value: 'fecha'
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
      this.getAuditores()
    },
    watch: {
      'itemConcurrencias' (value) {
        if (value.estado === 'crear') {
          this.concurrencias.splice(0, 0, value.item)
        } else if (value.estado === 'editar') {
          this.concurrencias.splice(this.concurrencias.findIndex(x => x.id === value.item.id), 1, value.item)
        }
      },
      'pagination.per_page' () {
        this.pagination.current_page = 1
        this.reloadPage()
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('concurrencias')
      },
      ...mapState({
        itemConcurrencias: state => state.tables.itemConcurrencias
      }),
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.axios.get('cuentasmedicas/concurrencia?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page + '&search=%25' + this.search + '%25')
          .then((response) => {
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.concurrencias = response.data.data
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
      getAuditores () {
        this.axios.get('ac_auditores')
          .then((response) => {
            this.funcionarios = response.data.data
            this.funcionarios.unshift({usuario: {id: null, name: 'Todos'}, identificacion_completa: 'Listar todas las concurrencias'})
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer auditores. ', error: e})
            return false
          })
      },
      filtrarConcurrencias () {
        this.funcionario ? this.concurrenciasPorAuditor() : this.reloadPage()
      },
      concurrenciasPorAuditor () {
        this.tableLoading = true
        this.axios.get('cuentasmedicas/concurrencia?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page + '&search=%25' + this.search + '%25' + '&auditores:gs_usuario_id=' + this.funcionario)
          .then((response) => {
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.concurrencias = response.data.data
            this.tableLoading = false
          })
          .catch(e => {
            this.tableLoading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al hacer la busqueda de registros. ', error: e})
            return false
          })
      }
    }
  }
</script>

<style scoped>
  @media (min-width: 960px) {
    .gridMd {
      flex-basis: 50%;
      flex-grow: 0;
      max-width: 30%;
      margin-right: 20%;
    }
  }
</style>
