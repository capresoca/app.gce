<template>
  <div>
    <v-container fluid grid-list-xl class="pa-0">
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

      <loading v-model="localLoading" />
      <v-data-table
        :headers="headers"
        :items="atencionesYServicios"
        :loading="tableLoading"
        :pagination.sync="pagination"
        :total-items="atencionesYServicios.length"
        :search="search"
        :expand="expand"
        hide-actions
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <tr @click="props.expanded = !props.expanded">
            <td>{{ props.item.codigo }}</td>
            <td>{{ props.item.Servicio }}</td>
            <td>{{ props.item.cAut }}</td>
            <td>{{ props.item.fecha }}</td>
            <td>{{ props.item.tipoServicio }}</td>
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
          <td colspan="100%" class="text-xs-center">
            <v-pagination v-model="pagination.current_page" :total-visible="7" :length="pagination.last_page" @input="reloadPage"></v-pagination>
          </td>
        </template>
      </v-data-table>
    </v-container>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import Loading from '@/components/general/Loading'

  export default {
    name: 'DetalleGeneralAportes',
    components: {
      Loading
    },
    props: {
      afiliado: {
        type: Object,
        default: null
      }
    },
    data () {
      return {
        atencionesYServicios: [],
        tableLoading: false,
        localLoading: false,
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
            text: 'Código',
            align: 'left',
            sortable: false
          },
          {
            text: 'Servicio',
            align: 'left',
            sortable: false
          },
          {
            text: 'Cant. Aut.',
            align: 'left',
            sortable: false
          },
          {
            text: 'Fecha',
            align: 'left',
            sortable: false
          },
          {
            text: 'Tipo Servicio',
            align: 'left',
            sortable: false
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
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.localLoading = true
        this.axios.get(`afiliado/${this.afiliado.id}/atencionesyservicios`)
          .then((response) => {
            this.atencionesYServicios = response.data.data
            this.tableLoading = false
            this.localLoading = false
          })
          .catch(e => {
            this.tableLoading = false
            this.localLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer los registros. ' + e.response, color: 'danger'})
          })
      },
      buscar: window.lodash.debounce(function () {
        this.pagination.current_page = 1
        this.reloadPage()
      }, 500)
    }

  }
</script>

<style>

</style>
