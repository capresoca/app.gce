<template>
  <v-dialog v-model="dialog" persistent max-width="520">
    <template v-slot:activator="{ on }">
      <v-tooltip top class="mt-3">
        <v-btn flat icon color="error" dark slot="activator" v-on="on">
          <v-icon>warning</v-icon>
        </v-btn>
        <span>Errores de archivo</span>
      </v-tooltip>
    </template>
    <v-card>
      <v-toolbar dense>
        <v-avatar color="error" size="36">
          <v-icon class="white--text">warning</v-icon>
        </v-avatar>
        <span class="ml-2 subheading">{{data && data.titulo}}</span>
        <v-spacer></v-spacer>
        <v-btn flat icon @click="dialog = false">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-card-text v-if="data">
        <v-data-table
          :items="data.errores"
          class="elevation-1"
          hide-actions
          hide-headers
        >
          <template v-slot:items="props">
            <td>{{ props.item.tipo_identificacion }}</td>
            <td>{{ props.item.identificacion }}</td>
          </template>
        </v-data-table>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn flat @click="dialog = false">Cerrar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: 'MuestraErroresArchivo',
    props: {
      data: {
        type: Object,
        default: null
      }
    },
    data: () => ({
      dialog: false
    }),
    methods: {
      open () {
        this.dialog = true
      }
    }
  }
</script>

<style scoped>

</style>
