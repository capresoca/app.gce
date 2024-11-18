<template>
  <v-dialog v-model="show" persistent max-width="600">
    <v-card v-if="datos">
      <v-toolbar dense>
        <v-toolbar-title
          class="subheading red--text"
          v-text="'¿Está seguro de continuar?'"
        ></v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn flat icon @click.stop="show = false"><v-icon>close</v-icon></v-btn>
      </v-toolbar>
      <v-card-text>
        <h2
          class="body-2"
          v-text="info"
        ></h2>
      </v-card-text>
      <v-container fluid grid-list-xs class="pt-0" v-if="mostrar">
        <v-layout row wrap>
          <v-flex xs12>
            <v-textarea
              label="Observaciones"
              v-model="data.observacion"
              name="observaciones"
              v-validate="'required'"
              :error-messages="errors.collect('observaciones')"
              rows="1"
            ></v-textarea>
          </v-flex>
        </v-layout>
      </v-container>
      <v-divider/>
      <v-card-actions>
        <v-btn flat @click.stop="show = false">Cancelar</v-btn>
        <v-spacer/>
        <v-btn color="primary" @click.stop="aceptarEliminar">Continuar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: 'DetalleEliminacion',
    data: () => ({
      show: false,
      datos: null,
      data: null,
      info: null,
      makeData: {
        id: null,
        observacion: null,
        gs_usuario_id: null,
        index: null
      },
      mostrar: false
    }),
    watch: {
      'show' (val) {
        if (!val) {
          setTimeout(() => {
            this.datos = null
            this.info = null
            this.mostrar = false
          }, 500)
        }
      }
    },
    methods: {
      assign (props, strnum = null) {
        console.log('aa', strnum)
        this.data = this.clone(this.makeData)
        this.$validator.reset()
        this.datos = props
        this.data.index = this.datos.index
        if (strnum === 'dos') {
          this.info = 'Al continuar procederá con desistir del ingreso del afiliado, se marcará entonces el retiro.'
          this.mostrar = true
        } else {
          this.info = 'Al continuar procederá con desistir de la novedad del afiliado, y esto implica regresarlo a los datos anteriores sin generarle novedad.'
          this.mostrar = false
        }
        this.show = true
      },
      aceptarEliminar () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.$emit('eliminar', this.data)
            this.show = false
          }
        })
      }
    }
  }
</script>

<style scoped>

</style>
