<template>
  <v-data-table
    :headers="headers"
    :items="procesosNS"
    :loading="tableLoading"
    :pagination.sync="pagination"
    hide-actions
    :search="search" class="elevation-1" rows-per-page-text="Registros por página" :rows-per-page-items='[20,40,60,{"text":"Todos","value":-1}]'>
    <template slot="items" slot-scope="props">
      <td>{{ props.item.nombre }}</td>
      <td class="pa-2">{{ props.item.identificacion}}</td>
      <td>{{ props.item.fecha_afiliacion }}</td>
      <td>{{ props.item.fecha_ini_novedad }}</td>
      <td>{{ props.item.codigo_novedad }}</td>
      <td>{{ props.item.nuevo_valor_1 }}</td>
      <td>{{ props.item.nuevo_valor_2 }}</td>
      <td>{{ props.item.nuevo_valor_3 }}</td>
      <td>{{ props.item.nuevo_valor_4 }}</td>
      <td>{{ props.item.nuevo_valor_5 }}</td>
      <td>{{ props.item.nuevo_valor_6 }}</td>
      <td>{{ props.item.nuevo_valor_7 }}</td>
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
    name: 'NS',
    data () {
      return {
        procesosNS: [],
        pagination: {},
        tableLoading: false,
        search: '',
        headers: [
          {
            text: 'Nombre',
            align: 'left',
            sortable: false,
            value: '',
            width: '450px',
            class: 'pt-3 pb-2 d-inline-flex'
          },
          {
            text: 'Identificación',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Fecha Afiliación',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Fecha Inicio Novedad',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Código Novedad',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'V1',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'V2',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'V3',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'V4',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'V5',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'V6',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'V7',
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
      this.axios.get('tramites/pendiente?novedades_subsidiado=1').then(res => {
        this.procesosNS = res.data.novedades_subsidiado
        this.pagination.totalItems = this.procesosNS.length
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
