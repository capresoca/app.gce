<template>
  <v-dialog
    v-model="dialog"
    width="1200"
  >
    <template v-slot:activator="{ on }">
      <v-btn small color="primary" v-on="on">
        <v-icon left>widgets</v-icon>
        Red de servicios
      </v-btn>
    </template>

    <v-card>
      <v-toolbar dense>
        <v-toolbar-title>
          <v-avatar color="primary" class="mr-2" size="40">
            <v-icon class="white--text">widgets</v-icon>
          </v-avatar>
          Red de servicios
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn flat icon @click.stop="dialog = false">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>
      <detalle-red-servicios :afiliado-id="afiliadoId"></detalle-red-servicios>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          flat
          @click="dialog = false"
        >
          Cerrar
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  import DetalleRedServicios from '@/components/misional/redservicios/DetalleRedServicios'
  export default {
    name: 'DialogRedServicios',
    props: {
      afiliadoId: {
        type: [String, Number],
        default: null
      }
    },
    components: {
      DetalleRedServicios
    },
    data: () => ({
      dialog: false
    })
  }
</script>

<style scoped>

</style>
