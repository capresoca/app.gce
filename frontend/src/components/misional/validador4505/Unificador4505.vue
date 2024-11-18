<template>
  <div class="container">
    <v-container>
      <v-card flat icon>
      <v-card-title class="headline grey lighten-2" primary-title>
        Unificador de archivos 4505
      </v-card-title>
        <v-card-text>
          <v-container>
            <v-layout row wrap>
              <v-flex xs12 sm12 md6 lg6>
                <v-select
                  v-model="parametros.year"
                  :items="years"
                  item-text="year"
                  item-value="year"
                  placeholder="Seleccione un año"
                  label="Año"
                ></v-select>
              </v-flex>
              <v-flex xs12 sm12 md6 lg6>
                <v-select
                  v-model="parametros.periodo"
                  :items="periodos"
                  placeholder="Seleccione un periodo"
                  label="periodo"
                ></v-select>
              </v-flex>            
            </v-layout>
            <v-layout row wrap>
              <v-flex xs6 class="text-xs-left">
              </v-flex>
              <v-flex xs6 class="text-xs-right">
                <v-btn @click="submit" color="primary">UNIFICAR</v-btn>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-container>
    <v-dialog
      v-model="dialogdescarga"
      hide-overlay
      persistent
      width="300"
    >
      <v-card
        color="primary"
        dark
      >
        <v-card-text>
          Por favor espere, estamos descargando el archivo 4505
          <v-progress-linear
            indeterminate
            color="white"
            class="mb-0"
          ></v-progress-linear>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
  export default {
    name: 'Unificador4505',
    data () {
      return {
        fab: false,
        dialogdescarga: false,
        dialogHelp: false,
        pickerFechaInicio: false,
        pickerFechaFin: false,
        parametros: {
          year: '',
          periodo: ''
        },
        years: [],
        periodos: []
      }
    },
    methods: {
      submit () {
        if (this.parametros.year === '') {
          this.$store.commit('SNACK_SHOW', {msg: 'Seleccione un año', color: 'error'})
          return false
        }
        if (this.parametros.periodo === '') {
          this.$store.commit('SNACK_SHOW', {msg: 'Seleccione un periodo', color: 'error'})
          return false
        }
        this.dialogdescarga = true
        this.axios({
          url: 'unificador-archivos-4505',
          method: 'POST',
          data: this.parametros,
          responseType: 'blob' // important
        })
          .then((response) => {
            this.dialogdescarga = false
            const url = window.URL.createObjectURL(new Blob([response.data]))
            const link = document.createElement('a')
            link.href = url
            link.setAttribute('download', 'archivounificado.TXT')
            document.body.appendChild(link)
            link.click()
          })
          .catch((error) => {
            this.dialogdescarga = false
            const blb = new Blob([error.response.data], {type: 'text/plain'})
            const reader = new FileReader()
            reader.addEventListener('loadend', (e) => {
              const response = JSON.parse(e.srcElement.result)
              console.log(response.state === 'warning')
              if (response.state === 'warning') {
                this.$store.commit('SNACK_SHOW', {msg: response.message, color: 'info'})
              } else {
                if (response.state === 'validador') {
                  this.$store.commit('SNACK_SHOW', {msg: response.message, color: 'info'})
                } else {
                  if (response.state === 'exception') {
                    this.$store.commit('SNACK_SHOW', {msg: response.message + ' | Error: ' + response.message.errors, color: 'error'})
                  } else {
                    this.$store.commit('SNACK_SHOW', {msg: 'Error en el servidor: ' + e.srcElement.result, color: 'error'})
                  }
                }
              }
            })
            reader.readAsText(blb)
          })
      },
      complementos () {
        this.axios.get('complementos-unificador-archivos-4505').then(response => {
          this.years = response.data.years
          this.periodos = response.data.periodos
        }).catch(e => {
          this.$store.commit('SNACK_SHOW', {msg: 'Status: ' + e.response.status + ' | ' + e.response.data.message, color: 'error'})
        })
      }
    },
    mounted () {
      this.complementos()
    }
  }
</script>