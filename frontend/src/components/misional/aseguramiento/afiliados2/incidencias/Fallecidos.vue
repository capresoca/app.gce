<template>
  <v-card>
    <v-card-title class="title">
      Fallecidos
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        append-icon="search"
        label="Buscar"
        single-line
        hide-details
      ></v-text-field>
    </v-card-title>
    <v-data-table
      :headers="headers"
      :items="incidencias"
      :search="search"
      disable-initial-sort
    >
      <template v-slot:items="props">
        <td>
          {{props.item.tipo_identificacion}}{{props.item.identificacion}}
        </td>
        <td>
          {{props.item.nombre_completo}}
        </td>
        <td>{{ props.item.fecha_nacimiento }}</td>
        <td>{{ props.item.fecha_reporte }}</td>
        <td>{{ props.item.Fuente }}</td>
        <td class="text-xs-center">
          <v-avatar size="38" class="white--text" :color="props.item.dias_reportado < 2 ? 'green' : props.item.dias_reportado < 3 ? 'orange' : 'red'">
            {{ props.item.dias_reportado }}
          </v-avatar>
        </td>
      </template>
    </v-data-table>
  </v-card>
</template>

<script>
  export default {
    name: 'Fallecidos',
    props: {
      incidencias: {
        type: Array,
        default: []
      }
    },
    data: () => ({
      search: '',
      headers: [
        {
          text: 'Identificación Afiliado',
          align: 'left',
          sortable: true,
          value: 'identificacion'
        },
        {
          text: 'Nombre Afiliado',
          align: 'left',
          sortable: true,
          value: 'nombre_completo'
        },
        {
          text: 'Fecha Nacimiento',
          align: 'left',
          sortable: true,
          value: 'fecha_nacimiento'
        },
        {
          text: 'Fecha Reporte',
          align: 'left',
          sortable: true,
          value: 'fecha_reporte'
        },
        {
          text: 'Fuente',
          align: 'left',
          sortable: true,
          value: 'Fuente'
        },
        {
          text: 'Días sin procesar',
          align: 'center',
          sortable: true,
          value: 'dias_reportado'
        }
      ]
    })
  }
</script>

<style scoped>

</style>
