<template>
  <v-dialog :value="value" width="1000px" persistent>
    <v-card>
      <v-toolbar dense>
        <template>
          <v-icon v-text="'fas fa-stream'"></v-icon>
          <v-toolbar-title>Servicios Solicitados</v-toolbar-title>
          <small class="mt-2 ml-1"> Listado de servicios</small>
          <v-spacer></v-spacer>
          <v-btn
            small
            fab
            color="accent"
            @click="$emit('cancelar')">
            <v-icon>close</v-icon>
          </v-btn>
        </template>
      </v-toolbar>
      <v-data-table
        :headers="headersServicios"
        :items="items"
        dense
        hide-actions
      >
        <template v-slot:items="props">
          <td>{{ props.item.no_autorizacion }}</td>
          <td>{{ props.item.codigo + ' ' + props.item.descrip }}</td>
          <td class="text-xs-center">{{ props.item.cant_solicitada }}</td>
          <td class="text-xs-center">{{ (props.item.estado === 1 && props.item.cant_autorizada) ? props.item.cant_autorizada : 0 }}</td>
          <td>{{ props.item.entidad_destino }}</td>
          <td>{{ (props.item.estado === 2 ? 'Anulado' : props.item.negacion ? 'Negado' : (props.item.estado === 1 && props.item.cant_autorizada) ? 'Aprobado' : 'Pendiente')}}</td>
        </template>
      </v-data-table>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: 'DetalleServicios',
    props: {
      data: {
        type: Object,
        default: null
      },
      value: {
        type: Boolean,
        default: false
      }
    },
    watch: {
      'data' (val) {
        this.items = val ? val.servicios_autorizados : []
      }
    },
    data () {
      return {
        items: [],
        headersServicios: [
          {
            text: '# Autorización',
            align: 'left',
            sortable: false
          },
          {
            text: 'Servicio',
            align: 'left',
            sortable: false
          },
          {
            text: 'Cant. Solicitada',
            align: 'center',
            sortable: false
          },
          {
            text: 'Cant. Autorizada',
            align: 'center',
            sortable: false
          },
          {
            text: 'Ent. Destino',
            align: 'left',
            sortable: false
          },
          {
            text: 'Estado',
            align: 'left',
            sortable: false
          }
        ]
      }
    }
  }
</script>

<style scoped>

</style>
