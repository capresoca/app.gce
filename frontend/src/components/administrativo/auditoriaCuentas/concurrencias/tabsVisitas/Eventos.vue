<template>
  <div>
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
              <v-flex xs12>
                <v-textarea
                  v-model="evento.descripcion"
                  outline
                  name="descripcion"
                  label="Descripción"
                ></v-textarea>
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
            :disabled="concurrencia.estado === 'Cerrada'"
          >
            <v-icon>add</v-icon>
          </v-btn>
          <span>{{ concurrencia.estado === 'Abierta' ? 'Crear registro' : 'Concurrencia cerrada'}}</span>
        </v-tooltip>
      </v-toolbar>

      <loading v-model="loading" />
      <v-data-table
        :headers="headers"
        :items="data.eventos || eventos"
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
                :disabled="concurrencia.estado === 'Cerrada'"
              >
                <v-icon color="accent">edit</v-icon>
              </v-btn>
              <span>{{ concurrencia.estado === 'Abierta' ? 'Editar' : 'Concurrencia cerrada'}}</span>
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
      Loading
    },
    props: {
      data: {
        type: Object,
        default: null
      },
      concurrencia: {
        type: Object,
        default: {}
      }
    },
    data () {
      return {
        evento: {},
        eventos: [],
        dialogEvento: false,
        menuDateEvento: false,
        menuTimeEvento: false,
        fechaRecepcionEvento: null,
        horaRecepcionEvento: null,
        loading: false,
        loadingSubmit: false,
        tiposEvento: ['Evento Adverso', 'Falla', 'Fuga'],

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
    computed: {
      computedDateFormattedEvento () {
        return this.formDate(this.fechaRecepcionEvento)
      }
    },
    methods: {
      resetDialogEvento () {
        this.evento = {
          id: '',
          cm_convisita_id: null,
          tipo: null,
          fecha: null,
          descripcion: null,
          salud_publica: 0
        }
        this.fechaRecepcionEvento = null
        this.horaRecepcionEvento = null
      },
      edit (item) {
        this.openDialogEvento()
        this.evento = JSON.parse(JSON.stringify(item))
      },
      openDialogEvento () {
        this.dialogEvento = true
        this.evento.cm_convisita_id = this.data.id
        let fechaYHoraActual = this.moment().format('YYYY-MM-DD HH:mm').split(' ')
        this.fechaRecepcionEvento = fechaYHoraActual[0]
        this.horaRecepcionEvento = fechaYHoraActual[1]
      },
      closeEvento () {
        this.dialogEvento = false
        this.resetDialogEvento()
      },
      formDate (date) {
        if (!date) return null
        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async guardarEvento () {
        if (await this.validador('formEvento')) {
          this.loadingSubmit = true
          let send = !this.evento.id ? this.axios.post('cuentasmedicas/concurrencia/eventos', this.evento) : this.axios.put('cuentasmedicas/concurrencia/eventos/' + this.evento.id, this.evento)
          this.evento.fecha = `${this.fechaRecepcionEvento} ${this.horaRecepcionEvento}`
          send.then(response => {
            if (this.evento.id) {
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Item actualizado satisfactoriamente', color: 'success'})
              this.data.eventos.splice(this.data.eventos.findIndex(evento => evento.id === response.data.data.id), 1, response.data.data)
            } else {
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Item creado satisfactoriamente', color: 'success'})
              this.data.eventos.unshift(response.data.data)
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
