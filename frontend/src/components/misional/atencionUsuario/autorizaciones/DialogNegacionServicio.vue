<template>
  <v-dialog v-model="show" persistent max-width="600">
    <v-card v-if="datos">
      <v-toolbar dense>
        <v-toolbar-title class="body-2">
          Negación del servicio con código {{datos.item.codigo}}
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn flat icon @click.stop="show = false">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-container fluid grid-list-xs>
        <v-layout row wrap>
          <v-flex xs12>
            <v-autocomplete
              label="Motivo de negación"
              v-model="data.codigo"
              :items="motivos"
              item-value="codigo"
              item-text="descrip"
              name="motivo de negación"
              v-validate="'required'"
              :error-messages="errors.collect('motivo de negación')"
              >
            </v-autocomplete>
          </v-flex>
          <v-flex xs12>
            <v-textarea
              label="Observaciones"
              v-model="data.observacion"
            ></v-textarea>
          </v-flex>
        </v-layout>
      </v-container>
      <v-divider/>
      <v-card-actions>
        <v-btn flat @click.stop="show = false">Cancelar</v-btn>
        <v-spacer/>
        <v-btn color="primary" @click.stop="aceptarNegacion">Aceptar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: 'DialogNegacionServicio',
    data: () => ({
      show: false,
      datos: null,
      data: null,
      makeData: {
        id: null,
        codigo: null,
        observacion: null,
        index: null,
        au_anexot31_id: null,
        gs_usuario_id: null
      },
      motivos: []
    }),
    watch: {
      'show' (val) {
        if (!val) {
          setTimeout(() => {
            this.datos = null
          }, 500)
        }
      }
    },
    created () {
      this.getMotivos()
    },
    methods: {
      assign (props) {
        this.data = JSON.parse(JSON.stringify(this.makeData))
        this.$validator.reset()
        this.datos = props
        this.data.au_anexot31_id = this.datos.item.id
        this.data.index = this.datos.index
        this.show = true
      },
      aceptarNegacion () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.$emit('negar', this.data)
            this.show = false
          }
        })
      },
      getMotivos () {
        this.axios.get('complementos_modal')
          .then(response => {
            this.motivos = response.data.data.motivosnegacion
          })
          .catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ` al recuperar los motivos de negación. `, error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
