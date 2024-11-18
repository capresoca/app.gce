<template>
  <v-card>
    <v-card-title class="title">
      Pacientes no Afiliados
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
        <td>{{props.item.identificacion}}</td>
        <td>{{props.item.nombre_paciente}}</td>
        <td>{{ props.item.ips_hospitalizado }}</td>
        <td>{{ props.item.fecha_hospitalizacion }}</td>
        <td>{{ props.item.cama_servicio }}</td>
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
    name: 'Inexistentes',
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
          text: 'identificacion',
          align: 'left',
          sortable: true,
          value: 'identificacion'
        },
        {
          text: 'nombre',
          align: 'left',
          sortable: true,
          value: 'nombre_paciente'
        },
        {
          text: 'Prestador',
          align: 'left',
          sortable: true,
          value: 'ips_hospitalizado'
        },
        {
          text: 'Fecha hospitalización',
          align: 'left',
          sortable: true,
          value: 'fecha_hospitalizacion'
        },
        {
          text: 'Cama',
          align: 'left',
          sortable: true,
          value: 'cama_servicio'
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
