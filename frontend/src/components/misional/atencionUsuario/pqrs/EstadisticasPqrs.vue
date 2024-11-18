<template>
    <div>
      <v-card ref="formPQRS">
        <v-toolbar class="grey lighten-3 elevation-0 toolbar">
          <v-toolbar-title> Estadísticas </v-toolbar-title>
        </v-toolbar>
        <v-container grid-list-md style="max-width: inherit">
          <v-layout row wrap >
            <v-flex xs12>
              <v-form data-vv-scope="formReporte">
                <v-container fluid grid-list-md>
                  <v-layout row wrap>

                    <v-flex xs12 class="pb-4">
                      <v-card>
                        <v-toolbar dense class="grey lighten-4 elevation-0">
                          <v-toolbar-title class="subheading"><v-icon>person</v-icon> Reporte</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                          <v-layout row wrap>
                              <v-flex xs12>
                                <v-subheader class="pa-0 ma-0">Rango </v-subheader>
                                <v-layout row wrap>
                                  <v-flex xs12 sm5>
                                    <v-menu
                                      ref="dialogInicial"
                                      :close-on-content-click="false"
                                      v-model="modalRangoInicial"
                                      :nudge-right="40"
                                      lazy
                                      transition="scale-transition"
                                      offset-y
                                      full-width
                                      max-width="290px"
                                      min-width="290px"
                                    >
                                      <v-text-field
                                        slot="activator"
                                        v-model="rangoInicial"
                                        label="Fecha inicial"
                                        prepend-icon="event"
                                        readonly
                                        name="fecha inicial"
                                        v-validate="'required'"
                                        :error-messages="errors.collect('fecha inicial')"
                                      ></v-text-field>
                                      <v-date-picker
                                        v-model="rangoInicial"
                                        @input="modalRangoInicial = false"
                                        @change="() => {
                                          let index = $validator.errors.items.findIndex(x => x.field === 'fecha inicial')
                                          $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                        }"
                                        locale='es'
                                        no-title
                                      ></v-date-picker>
                                    </v-menu>
                                  </v-flex>

                                  <v-flex xs12 sm5>
                                    <v-menu
                                      ref="dialogFinal"
                                      :close-on-content-click="false"
                                      v-model="modalRangoFinal"
                                      :nudge-right="40"
                                      lazy
                                      transition="scale-transition"
                                      offset-y
                                      full-width
                                      max-width="290px"
                                      min-width="290px"
                                    >
                                      <v-text-field
                                        slot="activator"
                                        v-model="rangoFinal"
                                        label="Fecha final"
                                        prepend-icon="event"
                                        readonly
                                        name="fecha final"
                                        required
                                        data-vv-delay="1000"
                                        v-validate="'required'"
                                        :error-messages="errors.collect('fecha final')"
                                      ></v-text-field>
                                      <v-date-picker
                                        v-model="rangoFinal"
                                        @input="modalRangoFinal = false"
                                        locale='es'
                                        no-title
                                      ></v-date-picker>
                                    </v-menu>
                                  </v-flex>

                                  <v-flex xs12 sm2>
                                    <v-btn
                                      color="blue-grey"
                                      class="white--text"
                                      @click.native="generar"
                                      :loading="loadingSubmit"
                                    >
                                      Generar
                                      <v-icon right dark>assignment</v-icon>
                                    </v-btn>
                                  </v-flex>
                                </v-layout>
                              </v-flex>
                          </v-layout>
                        </v-card-text>
                      </v-card>
                    </v-flex>

                  </v-layout>
                </v-container>
              </v-form>
            </v-flex>
          </v-layout>

        </v-container>
        <v-divider></v-divider>
      </v-card>
    </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'

  export default {
    name: 'EstadisticasPqrs',
    data () {
      return {
        rangoInicial: '',
        rangoFinal: '',
        modalRangoInicial: false,
        modalRangoFinal: false,
        loadingSubmit: false
      }
    },
    methods: {
      formReset () {
        this.rangoInicial = ''
        this.rangoFinal = ''
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async generar () {
        if (await this.validador('formReporte')) {
          this.loadingSubmit = true
          this.axios.get(`firmar-ruta?nombre_ruta=reportePqrs&tiempo=60&inicio=${this.rangoInicial}&fin=${this.rangoFinal}`)
            .then((res) => {
              let url = res.data
              window.fileDownload(url)
              this.loadingSubmit = false
              this.formReset()
            })
            .catch(e => {
              this.loadingSubmit = false
              this.formReset()
              this.$store.commit(SNACK_SHOW, {msg: 'Error al generar el archivo. ' + e, color: 'danger'})
            })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      }
    }
  }
</script>

<style scoped>

</style>
