<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="procesosMC"
      :search="search"
      :loading="tableLoading"
      :pagination.sync="pagination"
      hide-actions
      class="elevation-1" rows-per-page-text="Registros por página" :rows-per-page-items='[20,40,60,{"text":"Todos","value":-1}]'>
      <template slot="items" slot-scope="props">
        <td>{{ props.item.identificacion}}</td>
        <td>{{ props.item.nombre }}</td>
        <td>{{ props.item.fecha_afiliacion }}</td>
        <td>{{ props.item.municipio}}</td>
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
            <v-pagination v-model="pagination.page" :length="pages"></v-pagination>
          </div>
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'MC',
    data () {
      return {
        procesosMC: [],
        pagination: {},
        tableLoading: false,
        search: '',
        headers: [
          {
            text: 'Identificación',
            align: 'left',
            sortable: false,
            value: 'identificacion'
          },
          {
            text: 'Nombre',
            align: 'left',
            sortable: false,
            value: 'nombre'
          },
          {
            text: 'Fecha Afiliación',
            align: 'left',
            sortable: false,
            value: 'fecha_afiliacion'
          },
          {
            text: 'Municipio',
            align: 'left',
            sortable: false,
            value: 'municipio'
          }
        ]
      }
    },
    beforeMount () {
      this.tableLoading = true
      this.axios.get('tramites/pendiente?MC=1').then(res => {
        console.log('response', res)
        this.procesosMC = res.data.MC
        this.pagination.totalItems = this.procesosMC.length
        this.tableLoading = false
      }).catch(e => {
        this.tableLoading = false
        this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de registros. ' + e.message, color: 'danger'})
      })
    },
    computed: {
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    }
  }
</script>

<style scoped>

</style>
