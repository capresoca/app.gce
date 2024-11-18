<template>
  <div>
    <v-card>
      <toolbar-list :info="infoComponent"
                    title="Cuentas Bancarias"
                    subtitle="Registro y gestión"
                    btnplus
                    btnplustitle="Crear Cuenta"/>
      <loading v-model="tableLoading" />
      <v-card-title>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Buscar"
          single-line=""
          hide-details
          flat
        >
        </v-text-field>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="cuentasBancarias"
        :loading="tableLoading"
        :pagination.sync="pagination"
        hide-actions
        class="elevation-1"
        :search="search" rows-per-page-text="Registros por página" :rows-per-page-items='[10,25,50,{"text":"Todos","value":-1}]'>
        <template slot="items" slot-scope="props">
          <td>{{ props.item.codigo }}</td>
          <td>{{ props.item.banco ? props.item.banco.nombre : null }}</td>
          <td>{{ props.item.municipio ? props.item.municipio.nombre : null}}</td>
          <td>{{ props.item.numero_cuenta}}</td>
          <td>{{ props.item.niif ? props.item.niif.nombre : null}}</td>
          <td>{{ props.item.cencosto ? props.item.cencosto.nombre : null}}</td>
          <td>{{ props.item.estado}}</td>
          <td>{{ props.item.tipo_cuenta}}</td>
          <td class="text-xs-center">
            <v-tooltip top>
              <v-btn icon class="mx-0" slot="activator" @click="$store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: infoComponent.titulo_registro, parametros: {entidad: props.item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })">
                <v-icon color="accent">mode_edit</v-icon>
              </v-btn>
              <span>Editar</span>
            </v-tooltip>

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
        <template slot="pageText" slot-scope="props">
          {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
        </template>
        <v-alert slot="no-results" :value="true" color="error" icon="warning">
          No se encontraron resultados de "{{ search }}"
        </v-alert>
      </v-data-table>
      <v-card-actions class="text-xs-center pt-2 d-block">
        <v-pagination v-model="pagination.page" :length="pages"></v-pagination>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import {mapState} from 'vuex'
  import Loading from '@/components/general/Loading'

  export default {
    name: 'CuentasBancarias',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Loading
    },
    data () {
      return {
        cuentasBancarias: [],
        search: '',
        dialog: false,
        tableLoading: false,
        pagination: {},

        headers: [
          {
            text: 'Código',
            align: 'left',
            sortable: false,
            value: 'codigo'
          },
          {
            text: 'Entidad Bancaria',
            align: 'left',
            sortable: false,
            value: 'entidadBancaria'
          },
          {
            text: 'Ciudad Origen',
            align: 'left',
            sortable: false,
            value: 'ciudadOrigen'
          },
          {
            text: 'Numero de Cuenta',
            align: 'left',
            sortable: false,
            value: 'estado'
          },
          {
            text: 'Cuenta NIIF',
            align: 'left',
            sortable: false,
            value: 'fechaApertura'
          },
          {
            text: 'Centro de Costos',
            align: 'left',
            sortable: false,
            value: 'centroDeCostos'
          },
          {
            text: 'Estado',
            align: 'left',
            sortable: false,
            value: 'estado'
          },
          {
            text: 'Tipo de Cuenta',
            align: 'left',
            sortable: false,
            value: 'tipoDeCuenta'
          },
          {
            text: 'Opciones',
            align: 'center',
            sortable: false,
            value: 'id'
          }
        ]
      }
    },
    beforeMount () {
      this.reloadPage()
    },
    watch: {
      'itemCuentaBancaria' (value) {
        if (value.estado === 'crear') {
          this.cuentasBancarias.splice(0, 0, value.item)
        } else if (value.estado === 'editar') {
          this.cuentasBancarias.splice(this.cuentasBancarias.findIndex(x => x.id === value.item.id), 1, value.item)
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('cuentasBancarias')
      },
      ...mapState({
        itemCuentaBancaria: state => state.tables.itemCuentaBancaria
      }),
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.axios.get('cuentas')
          .then((res) => {
            this.cuentasBancarias = res.data.data
            this.pagination.totalItems = this.cuentasBancarias.length
            this.tableLoading = false
          })
          .catch(e => {
            this.tableLoading = false
            console.log('Hay un error. ' + e)
            return false
          })
      }
    }

  }
</script>

<style scoped>
  .v-subheader {
    height: 28px;
  }
</style>
