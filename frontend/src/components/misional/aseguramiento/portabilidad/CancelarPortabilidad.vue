<template>
  <v-dialog v-model="show" persistent max-width="600">
    <v-card v-if="datos">
      <v-toolbar dense>
        <v-toolbar-title class="body-2">
         Cancelación de la Portabilida # {{datos.consecutivo}}
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn flat icon @click.stop="show = false">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-container fluid grid-list-xs>
        <v-layout row wrap>
          <h3 class="font-weight">¿Por qué cancela la portabilidad?</h3>
          <v-flex xs12>
            <v-textarea
              label="Descripción"
              v-model="data.descripcion"
              hint="Motivo por el cual se cancela el registro."
              persistent-hint
              rows="2"
              name="descripción"
              v-validate="'required'"
              :error-messages="errors.collect('descripción')"
            ></v-textarea>
          </v-flex>
        </v-layout>
      </v-container>
      <v-divider/>
      <v-card-actions>
        <v-btn flat @click.stop="show = false">Cancelar</v-btn>
        <v-spacer/>
        <v-btn color="primary" @click.stop="aceptarCancelacion">Aceptar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: 'CancelarPortabilidad',
    data: () => ({
      show: false,
      datos: null,
      data: null,
      makeData: {
        id: null,
        descripcion: null,
        index: null,
        rs_portabilidade_id: null,
        gs_usuario_id: null
      }
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
    methods: {
      assign (props) {
        this.data = JSON.parse(JSON.stringify(this.makeData))
        this.$validator.reset()
        this.datos = props
        this.data.rs_portabilidade_id = this.datos.id
        this.data.index = this.datos.index
        this.show = true
      },
      aceptarCancelacion () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.$emit('cancelar', this.data)
            this.show = false
          }
        })
      }
    }
  }
</script>

<style scoped>

</style>
