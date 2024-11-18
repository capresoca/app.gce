<template>
  <div ref="loaderRef">
    <v-dialog v-model="dialogEvento" width="720px">
      <v-form data-vv-scope="formEvento">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Evento</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12 md6>
                <v-menu
                  ref="menuDateEvento"
                  :close-on-content-click="false"
                  v-model="menuDateEvento"
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
                    v-model="computedDateFormattedEvento"
                    label="Fecha"
                    prepend-icon="event"
                    readonly
                    name="fecha"
                    required
                    data-vv-delay="1000"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="fechaRecepcionEvento"
                    @input="menuDateEvento = false"
                    locale='es'
                    no-title
                  ></v-date-picker>
                </v-menu>
              </v-flex>
              <v-flex xs12 md6>
                <v-menu
                  ref="menuTimeEvento"
                  :close-on-content-click="false"
                  v-model="menuTimeEvento"
                  :nudge-right="40"
                  :return-value.sync="horaRecepcionEvento"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  max-width="290px"
                  min-width="290px"
                >
                  <v-text-field
                    slot="activator"
                    v-model="horaRecepcionEvento"
                    label="Hora de recepción"
                    prepend-icon="access_time"
                    readonly
                    required
                    name="hora recepcion"
                    v-validate="'required'"
                    :error-messages="errors.collect('hora recepcion')"
                  ></v-text-field>
                  <v-time-picker
                    v-if="menuTimeEvento"
                    v-model="horaRecepcionEvento"
                    full-width
                    @change="$refs.menuTimeEvento.save(horaRecepcionEvento)"
                  ></v-time-picker>
                </v-menu>
              </v-flex>
              <v-flex xs12>
                <v-select
                  :items="tiposEvento"
                  v-model="evento.tipo"
                  label="Tipo"
                  name="tipo"
                  prepend-icon="assignment"
                  :error-messages="errors.collect('tipo')"
                  v-validate="'required'" required
                ></v-select>
              </v-flex>

              <v-flex xs12 v-if="evento.tipo === 'Evento Adverso - Incidente'">
                <v-radio-group v-model="causa" row>
                  <v-radio label="Causa IPS" value="ips"></v-radio>
                  <v-radio label="Causa EPS" value="eps"></v-radio>
                </v-radio-group>
              </v-flex>

              <v-flex xs12>
                <v-autocomplete v-if="evento.tipo === 'Evento Adverso - Incidente' && causa === 'ips'"
                                :items="eventosAdversosIps"
                                v-model="evento.cm_eventoadversoips_id"
                                item-text="nombre"
                                item-value="id"
                                label="Seleccionar..."
                                no-data-text="No hay datos disponibles"
                                name="evento adeverso ips"
                                :error-messages="errors.collect('evento adeverso ips')"
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
              </v-flex>

              <v-flex xs12>
                <v-autocomplete v-if="evento.tipo === 'Evento Adverso - Incidente' && causa === 'eps'"
                                :items="eventosAdversosEps"
                                v-model="evento.cm_eventoadversoeps_id"
                                item-text="nombre"
                                item-value="id"
                                label="Seleccionar..."
                                no-data-text="No hay datos disponibles"
                                name="evento adeverso eps"
                                :error-messages="errors.collect('evento adeverso eps')"
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
              </v-flex>

              <v-flex v-if="evento.tipo === 'Evento Adverso - Incidente' || evento.tipo === 'Falla'">
                <v-flex xs12>
                  <v-autocomplete
                    label="Seleccionar glosa"
                    :items="glosas"
                    v-model="evento.cm_manglosa_id"
                    item-value="id"
                    item-text="descripcion"
                    :filter="filterGlosas"
                    name="glosa"
                    prepend-icon="security"
                    :error-messages="errors.collect('glosa')"
                  >
                    <template slot="selection" slot-scope="data">
                      <div style="width: 100% !important;">
                        <v-list-tile class="content-v-list-tile-p0">
                          <v-list-tile-content class="truncate-content">
                            <span class="ma-0 body-2">{{data.item.glosa}}: {{data.item.descripcion}}</span>
                            <span class="ma-0 body-2">Tipo: {{data.item.tipo}}</span>
                          </v-list-tile-content>
                        </v-list-tile>
                        <v-divider></v-divider>
                      </div>
                    </template>
                    <template slot="item" slot-scope="data">
                      <div style="width: 100% !important;">
                        <v-list-tile class="content-v-list-tile-p0">
                          <v-list-tile-content class="truncate-content">
                            <span class="ma-0 body-1">{{data.item.glosa}}: {{data.item.descripcion}}</span>
                            <span class="ma-0 body-1">Tipo: {{data.item.tipo}}</span>
                          </v-list-tile-content>
                        </v-list-tile>
                      </div>
                    </template>
                  </v-autocomplete>
                </v-flex>
              </v-flex>

              <v-flex xs12>
                <v-textarea
                  v-model="evento.descripcion"
                  outline
                  name="descripcion"
                  label="Descripción"
                ></v-textarea>
              </v-flex>

              <v-flex xs12 md6>
                <v-layout align-center fill-height>
                  <v-flex d-flex xs10 sm8>
                    <input-file
                      ref="archivoAdjunto"
                      label="Historia clínica"
                      v-model="evento.file"
                      :file-name="evento.historia_clinica ? evento.historia_clinica.nombre : null"
                      accept="application/pdf"
                      :hint="'Extenciones aceptadas: pdf'"
                      class="mb-3"
                      prepend-icon="attach_file"
                    />
                  </v-flex>
                  <v-flex d-flex xs2 sm4 v-if="evento.historia_clinica && evento.historia_clinica.url_signed">
                    <v-tooltip bottom>
                      <v-btn
                        fab
                        color="success"
                        small
                        :href="evento.historia_clinica ? evento.historia_clinica.url_signed : ''"
                        target="_blank"
                        slot="activator"
                      >
                        <v-icon>remove_red_eye</v-icon>
                      </v-btn>
                      <span>Ver archivo</span>
                    </v-tooltip>
                  </v-flex>
                </v-layout>
              </v-flex>

              <v-flex xs12>
                <v-subheader class="pa-0 ma-0">Salud Pública</v-subheader>
                <v-switch class="ma-0 pa-0"  color="accent"
                          v-model="evento.salud_publica"  :true-value="1" :false-value="0"></v-switch>
              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="closeEvento">Cancelar</v-btn>
            <v-btn color="primary" @click="guardarEvento" :loading="loadingSubmit" >Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>

    <v-card>
      <v-toolbar dense class="elevation-0">
        <v-toolbar-title>Eventos</v-toolbar-title>
        <small class="mt-2 ml-1"> Registro y gestión</small>
        <v-spacer/>
        <v-tooltip top>
          <v-btn
            fab
            color="accent"
            small
            slot="activator"
            @click="openDialogEvento()"
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
        :items="eventos"
        :loading="loading"
        rows-per-page-text="Registros por página"
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <td>{{ props.item.id  }}</td>
          <td>{{ props.item.fecha}}</td>
          <td>{{ props.item.tipo}}</td>
          <td>{{ props.item.salud_publica}}</td>
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
    name: 'Eventos',
    components: {
      Loading,
      InputFile: () => import('@/components/general/InputFile')
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
        evento: {},
        eventos: [],
        eventosAdversosEps: [],
        eventosAdversosIps: [],
        glosas: [],
        dialogEvento: false,
        menuDateEvento: false,
        menuTimeEvento: false,
        fechaRecepcionEvento: null,
        horaRecepcionEvento: null,
        loading: false,
        loadingSubmit: false,
        causa: null,
        tiposEvento: ['Evento Adverso - Incidente', 'Falla', 'Fuga'],
        payload: null,

        headers: [
          {
            text: 'id',
            align: 'left',
            sortable: false,
            value: 'id'
          },
          {
            text: 'Fecha',
            align: 'left',
            sortable: false,
            value: 'fecha'
          },
          {
            text: 'Tipo',
            align: 'left',
            sortable: false,
            value: 'tipo'
          },
          {
            text: 'Salud pública',
            align: 'left',
            sortable: false,
            value: 'salud_publica'
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
      this.resetDialogEvento()
    },
    mounted () {
      this.getRegistro()
      this.getEventosAdversosEps()
      this.getEventosAdversosIps()
      this.getGlosas()
    },
    computed: {
      computedDateFormattedEvento () {
        return this.formDate(this.fechaRecepcionEvento)
      }
    },
    methods: {
      getRegistro () {
        let loader = this.$loading.show({
          container: this.$refs.loaderRef.$el
        })
        this.axios.get('cuentasmedicas/concurrencia/eventos/' + this.concurrenciaId)
          .then((response) => {
            if (response.data !== '') {
              this.eventos = response.data.data
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el ingreso. ' + e, color: 'danger'})
          })
      },
      getEventosAdversosEps () {
        this.axios.get('cuentasmedicas/eventosadversoeps')
          .then((response) => {
            if (response.data !== '') {
              this.eventosAdversosEps = response.data.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los registros. ', error: e})
          })
      },
      getEventosAdversosIps () {
        this.axios.get('cuentasmedicas/eventosadversoips')
          .then((response) => {
            if (response.data !== '') {
              this.eventosAdversosIps = response.data.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los registros. ', error: e})
          })
      },
      getGlosas () {
        this.axios.get('manglosas')
          .then((response) => {
            if (response.data !== '') {
              this.glosas = response.data.data.glosas
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer las glosas. ' + e, color: 'danger'})
          })
      },
      resetDialogEvento () {
        this.evento = {
          id: '',
          cm_convisita_id: null,
          tipo: null,
          fecha: null,
          descripcion: null,
          salud_publica: 0,
          cm_eventoadeversoeps_id: null,
          cm_eventoadeversoips_id: null,
          cm_manglosa_id: null
        }
        this.fechaRecepcionEvento = null
        this.horaRecepcionEvento = null
      },
      edit (item) {
        this.openDialogEvento()
        this.evento = JSON.parse(JSON.stringify(item))
        console.log('xxedit', this.evento)
        if (this.evento.cm_eventoadversoips_id) this.causa = 'ips'
        if (this.evento.cm_eventoadversoeps_id) this.causa = 'eps'
      },
      openDialogEvento () {
        this.dialogEvento = true
        this.evento.cm_concurrencia_id = this.concurrenciaId
        let fechaYHoraActual = this.moment().format('YYYY-MM-DD HH:mm').split(' ')
        this.fechaRecepcionEvento = fechaYHoraActual[0]
        this.horaRecepcionEvento = fechaYHoraActual[1]
      },
      closeEvento () {
        this.dialogEvento = false
        this.resetDialogEvento()
      },
      filterGlosas (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.tipo + ' ' + item.glosa + ' ' + item.descripcion)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      formDate (date) {
        if (!date) return null
        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      addFormData () {
        let data = new FormData()
        data.append('id', this.evento.id)
        data.append('cm_concurrencia_id', this.evento.cm_concurrencia_id)
        data.append('tipo', this.evento.tipo)
        data.append('fecha', this.evento.fecha)
        data.append('descripcion', this.evento.descripcion)
        data.append('salud_publica', this.evento.salud_publica)
        if (this.evento.tipo === 'Evento Adverso - Incidente' || this.evento.tipo === 'Falla') data.append('cm_manglosa_id', this.evento.cm_manglosa_id)
        if (this.evento.cm_eventoadversoeps_id) data.append('cm_eventoadversoeps_id', this.evento.cm_eventoadversoeps_id)
        if (this.evento.cm_eventoadversoips_id) data.append('cm_eventoadversoips_id', this.evento.cm_eventoadversoips_id)

        data.append('file', typeof this.evento.file === 'undefined' ? '' : this.evento.file)

        this.payload = data
      },
      async guardarEvento () {
        if (await this.validador('formEvento')) {
          this.loadingSubmit = true
          this.evento.fecha = `${this.fechaRecepcionEvento} ${this.horaRecepcionEvento}`
          this.addFormData()

          let send = !this.evento.id ? this.axios.post('cuentasmedicas/concurrencia/eventos', this.payload) : this.axios.post('cuentasmedicas/concurrencia/eventos/' + this.evento.id, this.payload)

          send.then(response => {
            if (this.evento.id) {
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Item actualizado satisfactoriamente', color: 'success'})
              this.eventos.splice(this.eventos.findIndex(evento => evento.id === response.data.data.id), 1, response.data.data)
            } else {
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Item creado satisfactoriamente', color: 'success'})
              this.eventos.unshift(response.data.data)
            }
            this.closeEvento()
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
