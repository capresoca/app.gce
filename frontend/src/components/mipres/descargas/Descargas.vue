<template>
  <v-layout row justify-center>
    <v-dialog key="dialogConfirm" v-model="value" persistent max-width="400">
      <v-card>
        <v-card-text class="title font-weight-bold text-xs-center">{{titulos[tipo]}}</v-card-text>
        <v-divider></v-divider>
        <v-container fluid grid-list-xs>
          <v-layout row wrap>
            <v-flex xs12>
              <input-date
                v-if="value"
                key="dateDownload"
                v-model="descarga.fecha"
                :label="(tipo === 2) ? 'Fecha desde' : 'Fecha'"
                hint="Formato: (Año-Mes-Día)"
                :name="(tipo === 2) ? 'fecha desde' : 'fecha'"
                v-validate="'required|fechaValida'"
                :error-messages="errors.collect((tipo === 2) ? 'fecha desde' : 'fecha')"
              ></input-date>
            </v-flex>
            <v-flex xs12 v-if="tipo !== 2">
              <v-select
                label="Régimen"
                :items="regimenes"
                name="regimen"
                v-validate="'required'"
                :error-messages="errors.collect('regimen')"
                v-model="descarga.regimen"
              ></v-select>
            </v-flex>
          </v-layout>
        </v-container>
        <v-divider/>
        <v-card-actions>
          <v-btn flat @click.stop="$emit('input', false)">Cerrar</v-btn>
          <v-spacer/>
          <v-btn color="primary" :loading="loading" @click.stop="download">Descargar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>
  export default {
    name: 'Descargas',
    props: {
      value: {
        type: Boolean,
        default: false
      },
      tipo: {
        type: Number,
        default: 0
      }
    },
    components: {
      InputDate: () => import('@/components/general/InputDate')
    },
    data: () => ({
      loading: false,
      motivo: null,
      titulos: [
        'Prescripciones por fecha y régimen',
        'Juntas médicas por fecha y régimen',
        'Histórico de juntas médicas',
        'Tutelas por fecha y régimen',
        'Reportes de entrega por fecha y régimen'
      ],
      regimenes: [
        'Subsidiado',
        'Contributivo'
      ],
      descarga: {}
    }),
    watch: {
      'value' (val) {
        if (!val) {
          this.$validator.reset()
        } else {
          console.log('se veeee')
          this.formReset()
          this.$validator.reset()
        }
      }
    },
    computed: {
      ruta () {
        switch (this.tipo) {
          case 0: return `mipres/prescripcionesFecha?fecha=${this.descarga.fecha}&regimen=${this.descarga.regimen}`
          case 1: return `mipres/juntasFecha?fecha=${this.descarga.fecha}&regimen=${this.descarga.regimen}`
          case 2: return `mipres/historicoJuntas?desde=${this.descarga.fecha}`
          case 3: return `mipres/tutelasFecha?fecha=${this.descarga.fecha}&regimen=${this.descarga.regimen}`
          case 4: return `mipres/reportesFecha?fecha=${this.descarga.fecha}&regimen=${this.descarga.regimen}`
        }
      }
    },
    methods: {
      async download () {
        if (await this.$validator.validateAll()) {
          this.loading = true
          this.$store.commit('SNACK_SHOW', {msg: `La descarga MIPRES "${this.titulos[this.tipo]}" ha iniciado, se notificará en pantalla cuando el proceso termine.`, color: 'orange'})
          this.axios.get(this.ruta)
            .then(() => {
              this.loading = false
              this.$emit('success')
              this.$store.commit('SNACK_SHOW', {msg: `La descarga MIPRES "${this.titulos[this.tipo]}" se ha realizado correctamente.`, color: 'success'})
            })
            .catch(e => {
              this.loading = false
              this.$store.commit('SNACK_ERROR_LIST', {expression: ` al realizar la descarga de registros.`, error: e})
            })
        }
      },
      formReset () {
        this.descarga = {
          fecha: null,
          regimen: 'Subsidiado'
        }
      }
    }
  }
</script>

<style scoped>
</style>
