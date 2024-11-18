<template>
  <v-dialog :value="value" persistent dark :class="{'transparent': value === false}" width="800">
    <v-card class="elevation-0 blue-grey darken-4" :class="{'transparent': value === false}">
      <v-toolbar dark color="primary">
        <v-toolbar-title v-text="'Información del Afiliado'"></v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items>
          <!--<v-btn icon small @click="$emit('editar')">-->
            <!--<v-icon size="18">edit</v-icon>-->
          <!--</v-btn>-->
          <v-btn icon small @click="$emit('cancelar')">
            <v-icon size="18">close</v-icon>
          </v-btn>
        </v-toolbar-items>
      </v-toolbar>
      <v-container fluid class="pa-3" grid-list-xl :class="{'transparent': value === false}">
        <v-layout row wrap>
          <v-flex xs12 class="pt-1 pb-3">
            <v-text-field
              prepend-icon="person"
              :value="referencia.afiliado ? referencia.afiliado.nombre_completo : null"
              :hint="referencia.afiliado ? referencia.afiliado.identificacion_completa : null"
              persistent-hint
              label="Nombre Completo"
              readonly
            ></v-text-field>
          </v-flex>
          <v-flex xs12 sm4 class="pt-0 pb-1">
            <v-text-field prepend-icon="smartphone"
              :value="referencia.afiliado ? (referencia.afiliado.celular ? referencia.afiliado.celular : 'No Registra') : null"
              label="Celular"
              readonly
            ></v-text-field>
          </v-flex>
          <v-flex xs12 sm4 class="pt-0 pb-1">
            <v-text-field prepend-icon="home"
              :value="referencia.afiliado ? (referencia.afiliado.direccion ? referencia.afiliado.direccion : 'No Registra') : null"
              label="Dirección"
              readonly
            ></v-text-field>
          </v-flex>
          <v-flex xs12 sm4 class="pt-0 pb-1">
            <v-text-field
              :value="referencia.afiliado ? (referencia.afiliado.regimen ? referencia.afiliado.regimen.nombre : 'No Registra') : null"
              label="Regimén"
              readonly
            ></v-text-field>
          </v-flex>
          <v-flex xs12 sm12 class="pt-0">
            <v-text-field
              prepend-icon="location_city"
              :value="referencia.entidad_uno ? referencia.entidad_uno.nombre : null"
              label="IPS Origen"
              readonly
            ></v-text-field>
          </v-flex>
          <v-flex v-if="referencia.entidad_two !== null" xs12 sm12 class="pt-0">
            <v-text-field
              prepend-icon="location_city"
              :value="referencia.entidad_two ? referencia.entidad_two.nombre : null"
              label="IPS Destino"
              readonly
            ></v-text-field>
          </v-flex>
          <v-flex xs12 sm12 class="pt-0">
            <v-text-field
              prepend-icon="format_align_justify"
              :value="referencia.cie10_uno ? referencia.cie10_uno.descripcion : null"
              label="Diagnóstico de Ingreso"
              readonly
            ></v-text-field>
          </v-flex>
          <v-flex v-if="referencia.cie10_two !== null" xs12 sm12 class="pt-0">
            <v-text-field
              prepend-icon="format_align_justify"
              :value="referencia.cie10_two ? referencia.cie10_two.descripcion : null"
              label="Diagnóstico de Egreso"
              readonly
            ></v-text-field>
          </v-flex>
          <v-flex xs12 sm4 class="pt-0">
            <v-text-field
              :value="referencia.tipo_origen ? referencia.tipo_origen : null"
              label="Tipo Origen"
              readonly
            ></v-text-field>
          </v-flex>
          <v-flex xs12 sm8 class="pt-0">
            <v-text-field
              :value="referencia.mod_servicio ? referencia.mod_servicio.tipo : null"
              label="Modalidad de Servicio"
              readonly
            ></v-text-field>
          </v-flex>
          <v-flex xs12 sm12 class="pt-0">
            <v-text-field
              :value="referencia.servicio ? referencia.servicio.nombre : null"
              label="Modalidad de Servicio"
              readonly
            ></v-text-field>
          </v-flex>
          <v-flex xs12 sm12 class="pt-0">
            <v-textarea
              :value="referencia.observaciones ? referencia.observaciones : '...'"
              label="Observaciones"
              readonly
            ></v-textarea>
          </v-flex>

        </v-layout>
      </v-container>
    </v-card>
  </v-dialog>

</template>

<script>
  export default {
    name: 'InformacionRefAfiliado',
    props: {
      value: {
        type: Boolean,
        default: false
      },
      referencia: {
        type: Object,
        default: null
      }
    },
    data () {
      return {
        mini: true
      }
    }
  }
</script>

<style scoped>

</style>
