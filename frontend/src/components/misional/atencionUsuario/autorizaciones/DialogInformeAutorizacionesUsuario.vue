<template>
  <v-dialog
    v-model="dialog"
    width="400"
    persistent
  >
    <template v-slot:activator="{ on }">
      <v-btn small color="success" v-on="on">
        <v-icon left>fas fa-file-excel</v-icon>
        Estadísticas autorizador
      </v-btn>
    </template>
    <v-card>
      <loading v-model="loading"></loading>
      <v-toolbar dense>
        <v-toolbar-title>
          <v-avatar color="success" class="mr-2" size="40">
            <v-icon class="white--text">fas fa-file-excel</v-icon>
          </v-avatar>
          Estadísticas autorizador
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn flat icon @click.stop="dialog = false">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-container fluid grid-list-sm>
        <v-layout row wrap>
          <v-flex xs12>
            <input-date
              v-model="data.fecha_inicio"
              label="Fecha inicio (Año-Mes-Día)"
              format="YYYY-MM-DD"
              name="fecha inicio"
              :min="minDate"
              :max="maxDate"
              v-validate="'required|fechaValida|date_format:YYYY-MM-DD|date_between:' + minDate + ',' + maxDate + ',true'"
              :error-messages="errors.collect('fecha inicio')"
            ></input-date>
          </v-flex>
          <v-flex xs12>
            <input-date
              v-model="data.fecha_fin"
              label="Fecha fin (Año-Mes-Día)"
              format="YYYY-MM-DD"
              name="fecha fin"
              :min="minDate"
              :max="maxDate"
              v-validate="'required|fechaValida|date_format:YYYY-MM-DD|date_between:' + minDate + ',' + maxDate + ',true'"
              :error-messages="errors.collect('fecha fin')"
            ></input-date>
          </v-flex>
        </v-layout>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions>
        <v-btn
          flat
          @click="dialog = false"
        >
          Cerrar
        </v-btn>
        <v-spacer></v-spacer>
        <v-btn
          flat
          color="primary"
          @click="generar"
        >
          Descargar
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  import {mapState} from 'vuex'
  import InputDate from '@/components/general/InputDate'
  export default {
    name: 'DialogInformeAutorizacionesUsuario',
    components: {
      InputDate
    },
    data: () => ({
      minDate: '1900-01-01',
      maxDate: new Date().toISOString().substr(0, 10),
      loading: false,
      dialog: false,
      data: null,
      makeData: {
        fecha_inicio: null,
        fecha_fin: null,
        user: null
      }
    }),
    watch: {
      'data.fecha_inicio' (val) {
        if (val) {
          this.data.fecha_fin = this.moment(val).diff(this.moment(this.data.fecha_fin), 'days') > 0 ? val : this.data.fecha_fin
        }
      },
      'data.fecha_fin' (val) {
        if (val) {
          this.data.fecha_inicio = this.moment(val).diff(this.moment(this.data.fecha_inicio), 'days') < 0 ? val : this.data.fecha_inicio
        }
      },
      'dialog' (val) {
        if (!val) {
          setTimeout(() => {
            this.refresh()
          }, 400)
        }
      }
    },
    computed: {
      ...mapState({
        currentUser: state => state.user.profile
      })
    },
    created () {
      this.refresh()
    },
    methods: {
      generar () {
        this.$validator.validateAll().then(async result => {
          if (result) {
            this.loading = true
            await this.filePrint(`informe_autusuario&fecha_inicio=${this.data.fecha_inicio}&fecha_fin=${this.data.fecha_fin}&user=${this.currentUser.id}`, false)
            this.loading = false
          }
        })
      },
      refresh () {
        this.data = JSON.parse(JSON.stringify(this.makeData))
        this.$validator.reset()
      }
    }
  }
</script>

<style scoped>

</style>
