<template>
  <v-dialog v-model="show" persistent max-width="600">
    <v-card v-if="datos">
      <v-toolbar dense>
        <v-toolbar-title class="body-2">
          Aprobación de Solicitud # {{ datos.id }}
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn flat icon @click.stop="show = false">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-container fluid grid-list-xs>
        <v-layout row wrap>
          <v-flex xs12>
            <input-date
              v-model="data.fecha_aprobacion"
              label="Fecha aprobación"
              format="YYYY-MM-DD"
              name="fecha aprobación"
              :min="minDate"
              :max="maxDate"
              v-validate="'required|fechaValida|date_format:YYYY-MM-DD|date_between:' + minDate + ',' + maxDate + ',true'"
              :error-messages="errors.collect('fecha aprobación')"
            ></input-date>
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
        <v-btn color="primary" @click.stop="aprobar">Aprobar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: 'DialogAprobacionServicios',
    data: () => ({
      show: false,
      data: null,
      datos: null,
      minDate: '1900-01-01',
      maxDate: new Date().toISOString().substr(0, 10),
      makeData: {
        id: null,
        fecha_aprobacion: null,
        observacion: null,
        anexo_id: null,
        index: null
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
        this.data.index = this.datos.index
        this.data.anexo_id = this.datos.id
        this.show = true
      },
      aprobar () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.$emit('aprobar', this.data)
            this.show = false
          }
        })
      }
    }
  }
</script>

<style scoped>

</style>
