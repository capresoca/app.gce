<template>
  <div>
    <v-card>
      <toolbar-list :info="infoComponent" title="Respuestas BDUA" subtitle="Gestión"/>
      <loading v-model="tableLoading" />
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
        :items="respuestas"
        :loading="tableLoading"
        :pagination.sync="pagination"
        :total-items="respuestas.length"
        :search="search"
        hide-actions
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <td>{{ props.item.id }}</td>
          <td>{{ props.item.tipo_respuesta }}</td>
          <td>{{ props.item.avance }}</td>
          <td>{{ props.item.aplicadas }}</td>
          <td>{{ props.item.total_registros }}</td>
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
                  @click="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleRespuestas', titulo: 'Detalle Respuestas', parametros: {entidad: props.item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
                >
                  <v-icon color="accent">find_in_page</v-icon>
                </v-btn>
                <span>Ver detalle</span>
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
    </v-card>
  </div>
</template>

<script>
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import Loading from '@/components/general/Loading'

  export default {
    name: 'Respuestas',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Loading
    },
    data () {
      return {
        respuestas: [],
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
            text: 'Id',
            align: 'left',
            sortable: false,
            value: 'id'
          },
          {
            text: 'Tipo',
            align: 'left',
            sortable: false,
            value: 'tipo_respuesta'
          },
          {
            text: 'Avance',
            align: 'left',
            sortable: false,
            value: 'avance'
          },
          {
            text: 'Aplicadas',
            align: 'left',
            sortable: false,
            value: 'aplicadas'
          },
          {
            text: 'Total registros',
            align: 'left',
            sortable: false,
            value: 'total_registros'
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
      'pagination.per_page' () {
        this.pagination.current_page = 1
        this.reloadPage()
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('respuestas')
      },
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.axios.get('bduarespuestas?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page + '&search=%25' + this.search + '%25')
          .then((response) => {
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.respuestas = response.data.data
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
      }, 500)
    }
  }
</script>

<style scoped>

</style>
