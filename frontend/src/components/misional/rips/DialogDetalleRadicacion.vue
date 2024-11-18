<template>
  <v-dialog
    v-model="dialog"
    persistent
    width="720"
  >
    <template v-if="btnOpen" v-slot:activator="{ on }">
      <v-btn flat icon color="primary" v-on="on">
        <v-icon>find_in_page</v-icon>
        Ver detalle
      </v-btn>
    </template>

    <v-card>
      <loading v-model="loading"></loading>
      <v-toolbar dense>
        <v-avatar color="primary" size="40">
          <v-icon class="white--text">find_in_page</v-icon>
        </v-avatar>
        <v-toolbar-title>Detalle de radicado <strong>{{radicado ? radicado.cod_radicacion : ''}}</strong></v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn flat icon @click="dialog = false">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-container fluid grid-list-sm>
        <v-layout row wrap>
        </v-layout>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn flat @click="dialog = false">
          Cerrar
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: 'DialogDetalleRadicacion',
    props: {
      btnOpen: {
        type: Boolean,
        default: true
      }
    },
    data: () => ({
      dialog: false,
      loading: false,
      radicado: null
    }),
    watch: {
      'dialog' (val) {
        if (!val) {
          setTimeout(() => {
            this.refresh()
          }, 400)
        }
      }
    },
    methods: {
      open (id) {
        this.dialog = true
        this.loading = true
        this.axios.get(`autorizaciones/medicos`, id)
          .then((response) => {
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.dialog = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al consultar el radicado. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
