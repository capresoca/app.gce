<template>
  <v-dialog v-model="show" width="900">
    <v-card>
      <v-dialog
        v-model="dialogLoading"
        hide-overlay
        persistent
        width="300"
      >
        <v-card
          color="primary"
          dark
        >
          <v-card-text>
            <span v-text="message"></span>
            <v-progress-linear
              indeterminate
              color="white"
              class="mb-0"
            ></v-progress-linear>
          </v-card-text>
        </v-card>
      </v-dialog>
      <toolbar-list
        title="Descargue FTP"
        subtitle="Cargue PILA"
      ></toolbar-list>
      <v-layout
        justify-space-between
        pa-3>
        <v-flex xs12>
          <v-card>
            <v-card-title class="grey lighten-3 pa-2">
              <h3 class="body-1 font-weight-thin" v-text="(!activarLoad) ? 'Archivos Pendientes por Procesar' : 'Cargue PILA'"></h3>
            </v-card-title>
            <v-form v-if="!activarLoad" ref="formContratos">
              <v-container fluid grid-list-md>
                <v-layout row wrap>
                  <v-flex xs12 sm6 md6>
                    <input-date
                      v-model="pila.fecha_pago"
                      label="Día seleccionado"
                      format="YYYY-MM-DD"
                      :min="minDate"
                      :max="maxDate"
                      name="día seleccionado"
                      readonly
                    />
                  </v-flex>
                  <v-flex xs12 sm6 md6>
                    <v-text-field
                      v-model="pila.pendientes"
                      label="Días restantes"
                      key="días restantes"
                      name="días restantes"
                      readonly>
                    </v-text-field>
<!--                    <input-number-->
<!--                      :precision="0"-->
<!--                      v-model.number="pila.pendientes"-->
<!--                      readonly-->
<!--                    ></input-number>-->
                  </v-flex>
                </v-layout>
                <v-flex xs12 class="pt-0 mt-0">
                  <v-data-table
                    :headers="headers"
                    :items="pila.archivos"
                    class="elevation-1"
                  >
                    <template v-slot:items="props">
                      <td v-text="props.item.nombre_archivo"></td>
                      <td v-text="props.item.fecha_descarga"></td>
                    </template>
                  </v-data-table>
                </v-flex>
              </v-container>
            </v-form>
            <v-slide-y-transition v-else>
              <v-container >
                <v-layout>
                  <v-flex xs12 sm12>
                    <v-list-tile avatar v-for="(item, index) in resultado" :key="index">
                      <v-list-tile-content>
                        <v-list-tile-title :class="item.color" v-html="`${item.name}`" ></v-list-tile-title>
                      </v-list-tile-content>
                      <v-list-tile-avatar>
                        <v-btn
                          fab
                          flat
                          v-html="`<b>${item.value}</b>`"
                        />
                      </v-list-tile-avatar>
                    </v-list-tile>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-slide-y-transition>

          </v-card>
        </v-flex>
      </v-layout>
      <v-card-actions class="grey lighten-4">
        <v-btn @click="show = false">Cancelar</v-btn>
        <v-spacer></v-spacer>
        <v-btn color="primary" v-if="!activarLoad" @click.prevent="submit" :loading="loadingSubmit" >Ejecutar Cargue</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: 'DialogDescargaFtp',
    components: {
      InputNumber: () => import('@/components/general/InputNumber')
    },
    data: () => ({
      show: false,
      minDate: '1900-01-01',
      maxDate: new Date().toISOString().substr(0, 10),
      active: [],
      loadingSubmit: false,
      item: null,
      pila: null,
      datos: [],
      message: '',
      dialogLoading: false,
      activarLoad: false,
      open: ['Recaudos'],
      // VER
      isLoading: false,
      tree: [],
      types: [],
      archivos: [],
      headers: [
        {
          text: 'Archivo',
          align: 'left',
          sortable: false,
          value: 'archivo'
        },
        {
          text: 'Fecha Descarga',
          align: 'left',
          sortable: false,
          value: 'fecha_descarga'
        }
      ]
    }),
    watch: {
      'show' (val) {
        if (!val) {
          setTimeout(() => {
            this.loadingSubmit = false
            this.item = null
            this.open = []
            this.$validator.reset()
            this.formReset()
          }, 500)
        }
      }
    },
    created () {
      this.formReset()
    },
    methods: {
      assign (props) {
        this.getDescarga()
        this.show = !this.show
      },
      async submit () {
        this.dialogLoading = true
        this.message = 'Enviando archivo...'
        await this.axios.post('reccompensaciones/pilas', {})
          .then(res => {
            if (res.data !== '') {
              console.log('RESPUESTA', res)
              let data = res.data.data
              this.resultado[0].value = data.archivosProcesados
              this.resultado[1].value = data.archivosExitosos
              this.resultado[2].value = data.archivosErroneos
              this.activarLoad = !this.activarLoad
              this.$store.commit('SNACK_SHOW', {msg: res.data.message, color: 'success'})
              this.dialogLoading = true
              this.message = ''
              // this.show = false
            }
          }).catch((e) => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' Error al obtener los datos.', error: e})
          })
        console.log('Enviar...')
      },
      async getDescarga () {
        await this.axios.get(`reccompensaciones/directories/descargas`)
          .then(response => {
            console.log('response..', response)
            if (response.data !== '') {
              this.pila.fecha_pago = response.data.data.diaSeleccionado
              this.pila.pendientes = response.data.data.diasRestantes
              this.pila.archivos = response.data.data.archivos
            }
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' error al obtener los datos.', error: e})
          })
      },
      formReset () {
        this.pila = {
          fecha_pago: null,
          pendientes: 0,
          archivos: []
        }
        this.datos = []
        this.resultado = [
          {
            name: 'Cantidad Archivos Procesados',
            value: 0,
            color: 'black--text'
          },
          {
            name: 'Cantidad Archivos Cargados Èxitosamente',
            value: 0,
            color: 'green--text'
          },
          {
            name: 'Cantidad Archivos Con Inconsistencias',
            value: 0,
            color: 'red--text'
          }
        ]
      }
    }
  }
</script>

<style scoped>

</style>
