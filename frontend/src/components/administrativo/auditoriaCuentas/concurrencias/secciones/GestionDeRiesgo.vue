<template>
  <div ref="loader">
    <v-dialog v-model="dialog" width="1024px">
      <v-form data-vv-scope="form">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Gestión de riesgo</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12 md6>
                <v-flex xs12>

                  <v-checkbox
                    v-model="eventoCentinela"
                    label="Evento centinela"
                  ></v-checkbox>

                  <v-expand-transition>
                    <v-autocomplete v-if="eventoCentinela"
                                    :items="tiposEventosCentinela"
                                    v-model="riesgo.evento_centinela"
                                    label="Seleccionar..."
                                    no-data-text="No hay datos disponibles"
                                    name="servicio"
                                    :error-messages="errors.collect('servicio')"
                                    prepend-icon="contacts"
                    >
                    </v-autocomplete>
                  </v-expand-transition>

                </v-flex>
              </v-flex>

              <v-flex xs12 md6>

                <v-checkbox
                  v-model="eventoSaludPublica"
                  label="Evento salud pública"
                ></v-checkbox>

                <v-expand-transition>
                  <v-autocomplete v-if="eventoSaludPublica"
                                  :items="eventosSaludPublica"
                                  v-model="riesgo.cm_eventosaludpublica_id"
                                  item-text="nombre"
                                  item-value="id"
                                  label="Seleccionar..."
                                  no-data-text="No hay datos disponibles"
                                  name="salud publica"
                                  :error-messages="errors.collect('salud publica')"
                                  prepend-icon="contacts"
                  >
                    <template slot="item" slot-scope="data">
                      <template>
                        <v-list-tile-content>
                          <v-list-tile-title v-html="data.item.nombre"/>
                          <v-list-tile-sub-title v-html="data.item.codigo"/>
                        </v-list-tile-content>
                      </template>
                    </template>
                  </v-autocomplete>
                </v-expand-transition>
              </v-flex>

              <v-flex xs12 md6>

                <v-checkbox
                  v-model="patologiaTrazadora"
                  label="Patología trazadora"
                ></v-checkbox>

                <v-expand-transition>
                  <v-autocomplete v-if="patologiaTrazadora"
                    :items="patologiasTrazadoras"
                    v-model="riesgo.cm_patologiatrazadora_id"
                    item-text="nombre"
                    item-value="id"
                    label="Seleccionar..."
                    no-data-text="No hay datos disponibles"
                    name="patologia trazadora"
                    :error-messages="errors.collect('patologia trazadora')"
                    prepend-icon="contacts"
                  >
                    <template slot="item" slot-scope="data">
                      <template>
                        <v-list-tile-content>
                          <v-list-tile-title v-html="data.item.nombre"/>
                          <v-list-tile-sub-title v-html="data.item.codigo"/>
                        </v-list-tile-content>
                      </template>
                    </template>
                  </v-autocomplete>
                </v-expand-transition>
              </v-flex>

              <v-flex xs12 md6>

                <v-checkbox
                  v-model="patologia"
                  label="Patología"
                ></v-checkbox>

                <v-expand-transition>
                  <v-autocomplete v-if="patologia"
                    :items="patologias"
                    v-model="riesgo.cm_comppatologia_id"
                    item-text="nombre"
                    item-value="id"
                    label="Seleccionar..."
                    no-data-text="No hay datos disponibles"
                    name="patologia"
                    :error-messages="errors.collect('patologia')"
                    prepend-icon="contacts"
                  >
                    <template slot="item" slot-scope="data">
                      <template>
                        <v-list-tile-content>
                          <v-list-tile-title v-html="data.item.nombre"/>
                          <v-list-tile-sub-title v-html="data.item.codigo"/>
                        </v-list-tile-content>
                      </template>
                    </template>
                  </v-autocomplete>
                </v-expand-transition>
              </v-flex>

              <v-flex xs12 md6>

                <v-checkbox
                  v-model="rutaAtencion"
                  label="Ruta atención"
                ></v-checkbox>

                <v-expand-transition>
                  <v-select v-if="rutaAtencion"
                    :items="tiposRutasAtencion"
                    v-model="riesgo.ruta_atencion"
                    label="Seleccionar..."
                    name="ruta atencion"
                    prepend-icon="assignment"
                    :error-messages="errors.collect('ruta atencion')"
                  ></v-select>
                </v-expand-transition>
              </v-flex>

              <v-flex xs12>
                <v-textarea
                  v-model="riesgo.observaciones"
                  outline
                  name="observaciones"
                  label="Observaciones"
                ></v-textarea>
              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn color="primary" @click="guardar" :loading="loadingSubmit" >Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>

    <v-card>
      <v-toolbar dense class="elevation-0">
        <v-toolbar-title>Gestión de riesgo</v-toolbar-title>
        <small class="mt-2 ml-1"> Registro y gestión</small>
        <v-spacer/>
        <v-tooltip top>
          <v-btn
            fab
            color="accent"
            small
            slot="activator"
            @click="openDialog()"
            :disabled="concurrenciaObj.estado === 'Cerrada'"
          >
            <v-icon>add</v-icon>
          </v-btn>
          <span>Crear registro</span>
        </v-tooltip>
      </v-toolbar>

      <loading v-model="loading" />
      <v-data-table
        :headers="headers"
        :items="riesgos"
        :loading="loading"
        rows-per-page-text="Registros por página"
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <td>{{ props.item.id  }}</td>
          <td v-html="props.item.descripcion"></td>
          <td>
            <v-tooltip top>
              <v-btn
                icon
                slot="activator"
                @click="edit(props.item)"
                :disabled="concurrenciaObj.estado === 'Cerrada'"
              >
                <v-icon color="accent">edit</v-icon>
              </v-btn>
              <span>Ver detalle</span>
            </v-tooltip>
          </td>
        </template>
        <template slot="no-data">
          <v-alert  v-if="!loading" :value="true" color="error" icon="warning">
            Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
          </v-alert>
          <v-alert v-else :value="true" color="info" icon="info">
            Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
          </v-alert>
        </template>
      </v-data-table>
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import Loading from '@/components/general/Loading'

  export default {
    name: 'GestionDeRiesgo',
    components: {
      Loading
    },
    props: {
      concurrenciaId: {
        type: Number,
        default: 0
      },
      concurrenciaObj: {
        type: Object,
        defatul: {}
      }
    },
    data () {
      return {
        riesgo: {},
        riesgos: [],
        dialog: false,
        loading: false,
        loadingSubmit: false,
        tiposEventosCentinela: ['BAJO PESO AL NACER', 'HOSPITALIZACIÓN DE NIÑOS DE 3-5 AÑOS POR EDA', 'HOSPITALIZACIÓN DE 3-5 AÑOS POR IRA', 'MALTRATO INFANTIL', 'MORTINATO', 'MUERTE MATERNA', 'MUERTE PERITONAL', 'MUERTE POR DENGUE', 'MUERTE POR MALARIA', 'QUEMADURA EN MENOR 5 AÑOS', 'TRASMISIÓN VERTICAL (VIH-SÍFILIS)'],
        tiposRutasAtencion: ['PROMOCIÓN Y MANTENIMIENTO DE LA SALUD', 'SPA Y SALUD MENTAL', 'MATERNO PERITONAL', 'CÁNCER', 'CEREBRO VASCULAR'],
        patologias: [],
        patologiasTrazadoras: [],
        eventosSaludPublica: [],
        eventoCentinela: false,
        eventoSaludPublica: false,
        patologiaTrazadora: false,
        patologia: false,
        rutaAtencion: false,

        headers: [
          {
            text: 'id',
            align: 'left',
            sortable: false,
            value: 'id'
          },
          {
            text: 'Descripción',
            align: 'left',
            sortable: false,
            value: 'descripcion'
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'id'
          }
        ]
      }
    },
    beforeMount () {
      this.resetDialog()
    },
    mounted () {
      this.getRegistro()
      this.getPatologias()
      this.getEventosSaludPublica()
      this.getPatologiasTrazadoras()
    },
    methods: {
      getRegistro () {
        let loader = this.$loading.show({
          container: this.$refs.loader.$el
        })
        this.axios.get('cuentasmedicas/concurrencia/gestionriesgos/' + this.concurrenciaId)
          .then((response) => {
            if (response.data !== '') {
              this.riesgos = response.data.data
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el ingreso. ' + e, color: 'danger'})
          })
      },
      getPatologias () {
        this.axios.get('cuentasmedicas/complicacionpatologia')
          .then((response) => {
            if (response.data !== '') {
              this.patologias = response.data.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los registros. ', error: e})
          })
      },
      getEventosSaludPublica () {
        this.axios.get('cuentasmedicas/eventossaludpublica')
          .then((response) => {
            if (response.data !== '') {
              this.eventosSaludPublica = response.data.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los registros. ', error: e})
          })
      },
      getPatologiasTrazadoras () {
        this.axios.get('cuentasmedicas/patologiastrazadoras')
          .then((response) => {
            if (response.data !== '') {
              this.patologiasTrazadoras = response.data.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los registros. ', error: e})
          })
      },
      verificarCheckbox () {
        console.log('eventoCentinela', this.riesgo.evento)

        this.riesgo.evento_centinela !== null ? this.eventoCentinela = true : this.eventoCentinela = false
        this.riesgo.evento_salud_publica !== null ? this.eventoSaludPublica = true : this.eventoSaludPublica = false
        this.riesgo.patologia_trazadora !== null ? this.patologiaTrazadora = true : this.patologiaTrazadora = false
        this.riesgo.complicacion_patologia !== null ? this.patologia = true : this.patologia = false
        this.riesgo.ruta_atencion !== null ? this.rutaAtencion = true : this.rutaAtencion = false
      },
      resetDialog () {
        this.riesgo = {
          id: '',
          cm_concurrencia_id: null,
          eventos_centinela: null,
          cm_eventosaludpublica_id: null,
          cm_patologiatrazadora_id: null,
          cm_comppatologia_id: null,
          ruta_atencion: null,
          observaciones: null
        }
      },
      edit (item) {
        this.openDialog()
        this.riesgo = JSON.parse(JSON.stringify(item))
        this.verificarCheckbox()
      },
      openDialog () {
        this.dialog = true
        this.riesgo.cm_concurrencia_id = this.concurrenciaId
      },
      close () {
        this.dialog = false
        this.resetDialog()
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async guardar () {
        if (await this.validador('form')) {
          this.loadingSubmit = true
          let send = !this.riesgo.id ? this.axios.post('cuentasmedicas/concurrencia/gestionriesgos', this.riesgo) : this.axios.put('cuentasmedicas/concurrencia/gestionriesgos/' + this.riesgo.id, this.riesgo)
          send.then(response => {
            if (this.riesgo.id) {
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Item actualizado satisfactoriamente', color: 'success'})
              this.riesgos.splice(this.riesgos.findIndex(riesgo => riesgo.id === response.data.data.id), 1, response.data.data)
            } else {
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Item creado satisfactoriamente', color: 'success'})
              this.riesgos.unshift(response.data.data)
            }
            this.close()
          }).catch(e => {
            this.loadingSubmit = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al guardar el registro. ', error: e})
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
