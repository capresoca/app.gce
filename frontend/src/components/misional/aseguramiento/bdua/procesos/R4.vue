<template>
  <v-data-table
    :headers="headers"
    :items="procesosSolicitudTrasladoContributivo"
    :loading="tableLoading"
    :pagination.sync="pagination"
    hide-actions
    :search="search" class="elevation-1" rows-per-page-text="Registros por página" :rows-per-page-items='[20,40,60,{"text":"Todos","value":-1}]'>
    <template slot="items" slot-scope="props">
      <td>{{ props.item.identificacion }}</td>
      <td>{{ props.item.nombre_afiliado }}</td>
      <td>{{ props.item.fecha_solicitada}}</td>
      <td>{{ props.item.respuesta}}</td>
      <td>{{ validarCausa(props.item.causa_traslado)}}</td>
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
    name: 'RCuatro',
    data () {
      return {
        procesosSolicitudTrasladoContributivo: [],
        pagination: {},
        tableLoading: false,
        search: '',
        headers: [
          {
            text: 'identificacion',
            align: 'left',
            sortable: false,
            value: 'identificacion'
          },
          {
            text: 'Nombre',
            align: 'left',
            sortable: false,
            value: 'nombre_afiliado'
          },
          {
            text: 'Fecha de solicitud',
            align: 'left',
            sortable: false,
            value: 'fecha_solicitada'
          },
          {
            text: 'Respuesta',
            align: 'left',
            sortable: false,
            value: 'respuesta'
          },
          {
            text: 'Causa',
            align: 'left',
            sortable: false,
            value: 'causa_traslado'
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
      this.axios.get('tramites/pendiente?R4=1').then(res => {
        console.log('resto', res)
        this.procesosSolicitudTrasladoContributivo = res.data.R4
        this.pagination.totalItems = this.procesosSolicitudTrasladoContributivo.length
        this.tableLoading = false
      }).catch(e => {
        console.log(e)
        this.tableLoading = false
        this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de registros. ' + e.message, color: 'danger'})
      })
    },
    methods: {
      validarCausa (causa) {
        console.log(causa)
        console.log(causa != null)
        if (causa != null) {
          return causa.causa
        }
        return 'SIN RESPUESTA'
      }
    }
  }
</script>

<style scoped>
  table.v-table tbody td, table.v-table tbody th {
    height: 30px;
  }
</style>
