<template>
  <v-expansion-panel class="mb-1" v-if="portabilidad">
    <v-expansion-panel-content>
      <template v-slot:header>
        <span class="font-weight-bold">Portabilidad número {{portabilidad.consecutivo}}</span>
      </template>
      <v-container fluid grid-list-sm>
        <v-layout row wrap>
          <input-detail-flex
            flex-class="xs12 sm6 md4 lg3"
            label="Consecutivo"
            :text="portabilidad.consecutivo"
          ></input-detail-flex>
          <input-detail-flex
            flex-class="xs12 sm6 md4 lg3"
            label="Estado"
            :text="portabilidad.estado"
          ></input-detail-flex>
          <input-detail-flex
            flex-class="xs12 sm6 md4 lg3"
            label="Fecha inicio"
            :text="portabilidad.fecha_inicio"
            prepend-icon="event"
          ></input-detail-flex>
          <input-detail-flex
            flex-class="xs12 sm6 md4 lg3"
            label="Fecha vencimiento"
            :text="portabilidad.fecha_fin"
            prepend-icon="event"
          ></input-detail-flex>
          <input-detail-flex
            flex-class="xs12 sm12 md6"
            label="Municipio"
            :text="`${portabilidad.municipio_receptor.nombre}, ${portabilidad.municipio_receptor.nombre_departamento}`"
          ></input-detail-flex>
          <input-detail-flex
            flex-class="xs12 sm12 md6"
            label="Dirección"
            :text="portabilidad.direccion"
          ></input-detail-flex>
          <input-detail-flex
            flex-class="xs12 sm6 md4 lg3"
            label="Teléfono"
            :text="portabilidad.telefono"
          ></input-detail-flex>
          <input-detail-flex
            flex-class="xs12 sm6 md4 lg3"
            label="Correo electrónico"
            :text="portabilidad.email"
          ></input-detail-flex>
          <input-detail-flex
            flex-class="xs12"
            label="Motivo"
            :text="portabilidad.motivo"
          ></input-detail-flex>
          <input-detail-flex
            flex-class="xs12"
            label="Observaciones"
            :text="portabilidad.observaciones"
          ></input-detail-flex>
        </v-layout>
      </v-container>
    </v-expansion-panel-content>
  </v-expansion-panel>
</template>

<script>
  export default {
    name: 'ExpandPortabilidad',
    props: {
      portabilidad: {
        type: Object,
        default: null
      }
    }
  }
</script>

<style scoped>

</style>
