<template>
  <v-data-table
    :headers="headers"
    :items="procesosMA"
    :loading="tableLoading"
    :pagination.sync="pagination"
    hide-actions
    :search="search" class="elevation-1" rows-per-page-text="Registros por página" :rows-per-page-items='[20,40,60,{"text":"Todos","value":-1}]'>
    <template slot="items" slot-scope="props">
      <td>{{ props.item.identificacion}}</td>
      <td>{{ props.item.nombre }}</td>
      <td>{{ props.item.tipo_aportante }}</td>
      <td>{{ props.item.sector_aportante}}</td>
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
          <v-pagination v-model="pagination.page" :length="pages" ></v-pagination>
        </div>
      </td>
    </template>
  </v-data-table>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'MA',
    data () {
      return {
        procesosMA: [],
        pagination: {},
        tableLoading: false,
        search: '',
        headers: [
          {
            text: 'Identificación',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Nombre',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Tipo Aportante',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Sector Aportante',
            align: 'left',
            sortable: false,
            value: ''
          }
        ]
      }
    },
    computed: {
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    mounted () {
      this.tableLoading = true
      this.axios.get('tramites/pendiente?afiliaciones_aportante=1').then(res => {
        console.log('resto', res)
        this.procesosMA = res.data.afiliaciones_aportante
        this.pagination.totalItems = this.procesosMA.length
        this.tableLoading = false
      }).catch(e => {
        this.tableLoading = false
        this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de registros. ' + e.message, color: 'danger'})
      })
    }
  }
</script>

<style scoped>
  table.v-table tbody td, table.v-table tbody th {
    height: 30px;
  }
</style>
