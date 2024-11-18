<template>
  <span v-if="tercero">
    <template v-if="cambiaEstado">
      <v-tooltip top>
      <template v-slot:activator="{ on }">
        <v-switch
          v-on="on"
          @click.stop="dialog = true"
          v-model="tercero.estado"
          :false-value="0"
          :true-value="1"
          readonly
          color="success"
          hide-details
        ></v-switch>
      </template>
      <span>{{tercero.estado === 0 ? 'Activar' : 'Inactivar'}}</span>
    </v-tooltip>
    <v-dialog v-model="dialog" persistent max-width="320" v-if="tercero">
      <v-card>
        <v-card-title class="headline">¿está seguro de {{tercero.estado === 0 ? 'Activar' : 'Inactivar'}} el estado del tercero?</v-card-title>
        <template v-if="tercero">
          <v-card-text>
            <span class="text-xs-center font-weight-bold">{{tercero.nombre_completo}}</span>
            <br/>
            <span class="text-xs-center font-weight-bold">{{tercero.identificacion}}</span>
          </v-card-text>
        </template>
        <v-card-text v-if="tercero.estado === 1">
          Las implicaciones de inactivar el tercero en cuanto a modulos y flujo de información.
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn flat @click="dialog = false">Cancelar</v-btn>
          <v-btn color="green darken-1" flat @click="submit">Aceptar</v-btn>
        </v-card-actions>
        <loading v-model="loading"></loading>
      </v-card>
    </v-dialog>
    </template>
    <span v-else>
      {{tercero.estado === 0 ? 'Inactivo' : 'Activo'}}
    </span>
  </span>
</template>

<script>
  import {mapState} from 'vuex'
  export default {
    name: 'CambiarEstadoTercero',
    props: {
      tercero: {
        type: Object,
        default: null
      }
    },
    data: () => ({
      dialog: false,
      loading: false
    }),
    computed: {
      ...mapState({
        currentUser: state => state.user.profile
      }),
      cambiaEstado () {
        return this.currentUser && this.currentUser.roles && this.currentUser.roles.find(x => x.id === 1 || x.id === 24 || x.id === 25)
      }
    },
    methods: {
      submit () {
        this.loading = true
        this.axios.put(`terceros/cambiarestado/${this.tercero.id}`, {id: this.tercero.id})
          .then((response) => {
            if (this.tercero.estado === 0) {
              this.tercero.estado = 1
            } else {
              this.tercero.estado = 0
            }
            this.$store.commit('SNACK_SHOW', {msg: `El estado del tercero se actualizó correctamente.`, color: 'success'})
            this.dialog = false
            this.loading = false
          })
          .catch(e => {
            this.dialog = false
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ` al actualizar el estado del tercero. `, error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
