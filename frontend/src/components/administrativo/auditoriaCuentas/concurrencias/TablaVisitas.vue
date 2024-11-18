<template>
  <div ref="conVisitas">
    <v-dialog v-model="dialogVisita" persistent width="720px">
      <v-dialog v-model="dialogConfirm" persistent max-width="290">
        <v-card>
          <v-card-title class="headline grey lighten-3">¿Desea cargar datos de la última visita?</v-card-title>

          <v-divider></v-divider>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="green darken-1" :loading="loadingSubmit" flat @click.native="dialogConfirm = false">No</v-btn>
            <v-btn color="primary" :loading="loadingSubmit" flat @click.native="cargarDatosUltimaVisita">Si</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-form data-vv-scope="formVisita">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Visita</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>
               <v-flex xs12 sm4>
                <v-menu
                  ref="menuDate"
                  :close-on-content-click="false"
                  v-model="menuDate"
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
                    v-model="computedDateFormatted"
                    label="Fecha"
                    prepend-icon="event"
                    readonly
                    name="fecha"
                    v-validate="'date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="convisita.fecha_visita"
                    @input="menuDate = false"
                    @change="() => {
                      let index = $validator.errors.items.findIndex(x => x.field === 'fecha')
                      $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                    }"
                    locale='es'
                    no-title
                  ></v-date-picker>
                </v-menu>
              </v-flex>
              <v-flex xs12>
                <v-subheader class="pa-0 ma-0">Estancia pertiente</v-subheader>
                <v-radio-group class="pa-0 ma-0" v-model="convisita.estancia_pertinente" row>
                  <v-radio label="Si" :value="1"></v-radio>
                  <v-radio label="No" :value="0"></v-radio>
                </v-radio-group>
              </v-flex>

              <v-flex xs12 sm4>
                <v-text-field v-model="convisita.habitacion_hospitalaria" prepend-icon="hotel"
                              label="Habitación" name="habitación"
                              v-validate="'required'" required
                              :error-messages="errors.collect('habitación')"></v-text-field>
              </v-flex>

              <v-flex xs12>
                <v-autocomplete
                  :items="causalesHospitalizacion"
                  v-model="convisita.causal_hospitalizacion"
                  @change="val => convisita.cm_causalhospitalizacion_id = val ? val.id : null"
                  item-text="nombre"
                  item-value="id"
                  label="Causal hospitalización"
                  no-data-text="No hay datos disponibles"
                  name="causal hospitalización"
                  prepend-icon="contacts"
                  v-validate="'required'" required
                  :error-messages="errors.collect('causal hospitalización')"
                  return-object
                >
                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.nombre"/>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>

              <v-expand-transition>
                <v-flex xs12 v-if="convisita.causal_hospitalizacion.tabla === 'cups'">
                  <postulador-v2
                    ref="postuladorProcedimiento"
                    no-data="Busqueda por código o nombre."
                    item-text="descripcion"
                    label="Procedimiento"
                    entidad="cups"
                    hint="codigo"
                    persistent-hint
                    v-model="convisita.cup"
                    @changeid="val => convisita.rs_cup_id = val"
                    no-btn-create
                    no-btn-edit
                    name="procedimiento"
                    rules="required"
                    preicon="assignment"
                    v-validate="'required'"
                    :error-messages="errors.collect('procedimiento')"
                    :min-characters-search="4"
                    :slot-data='{
                      template:`
                      <v-list-tile>
                      <v-list-tile-action>
                        <v-chip
                        color="indigo lighten-2"
                        label
                        small
                        >
                        {{ value.codigo }}
                        </v-chip>
                        </v-list-tile-action>
                        <v-list-tile-content>
                          <v-list-tile-title>{{value.descripcion}}</v-list-tile-title>
                          <v-list-tile-sub-title class=caption>{{ value.cobertura }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                      </v-list-tile>
                      `,
                      props: [`value`]
                     }'
                  />
                </v-flex>
              </v-expand-transition>

              <v-expand-transition>
                <v-flex xs12 v-if="convisita.causal_hospitalizacion.tabla === 'cums'">
                  <postulador-v2
                    ref="postuladorMedicamento"
                    no-data="Busqueda por código, nombre o registro sanitario."
                    item-text="producto"
                    data-title="producto"
                    data-subtitle="expediente"
                    label="Medicamento"
                    entidad="rs_cums"
                    v-model="convisita.cum"
                    @changeid="val => convisita.rs_cum_id = val"
                    no-btn-create
                    no-btn-edit
                    name="Medicamento"
                    rules="required"
                    preicon="featured_play_list"
                    v-validate="'required'"
                    :error-messages="errors.collect('Medicamento')"
                    :min-characters-search="4"
                    :slot-data='{
                      template:`
                      <v-list-tile>
                      <v-list-tile-action>
                        <v-chip
                        color="indigo lighten-2"
                        label
                        small
                        >
                        {{ value.estado_cum }}
                        </v-chip>
                        </v-list-tile-action>
                        <v-list-tile-content>
                          <v-list-tile-title>{{value.expediente}} - {{ value.producto }} - {{value.titular}}</v-list-tile-title>
                          <v-list-tile-sub-title class=caption>{{ value.descripcion_comercial }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                      </v-list-tile>
                      `,
                      props: [`value`]
                     }'
                  />
                </v-flex>
              </v-expand-transition>

              <v-expand-transition>
                <v-flex xs12 v-if="convisita.causal_hospitalizacion.tabla === 'especialidad'">
                  <v-autocomplete
                    :items="complementos.especialidades"
                    v-model="convisita.rs_servicio_id"
                    item-text="nombre"
                    item-value="id"
                    name="especialidad"
                    label="Especialidad"
                    no-data-text="No hay especialidades disponibles"
                    :error-messages="errors.collect('especialidad')"
                    v-validate="'required'" required
                    prepend-icon="assignment_turned_in"
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
              </v-expand-transition>

              <v-flex xs12>
                <v-autocomplete
                  :items="complementos.rsServicios"
                  v-model="convisita.rs_especialidadtratante_id"
                  item-text="nombre"
                  item-value="id"
                  label="Especialidad tratante"
                  no-data-text="No hay datos disponibles"
                  name="especiaildad tratante"
                  :error-messages="errors.collect('especiaildad tratante')"
                  v-validate="'required'" required
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
                <v-autocomplete
                  :items="complementos.rsServicios"
                  v-model="convisita.rs_especialidadinterconsultante_id"
                  item-text="nombre"
                  item-value="id"
                  label="Especialidad interconsultante"
                  no-data-text="No hay datos disponibles"
                  name="especialidad interconsultante"
                  :error-messages="errors.collect('especialidad interconsultante')"
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
                <v-autocomplete
                  :items="condicionesClinicas"
                  v-model="convisita.condclinicas"
                  item-text="nombre"
                  item-value="nombre"
                  name="condiciones clinicas"
                  label="Condiciones clínicas"
                  no-data-text="No hay condiciones clínicas"
                  :error-messages="errors.collect('condiciones clinicas')"
                  prepend-icon="contacts"
                  return-object
                  multiple
                >
                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.nombre"/>
                        <v-list-tile-sub-title v-html="data.item.descripcion"/>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>

              <v-flex xs12>
                <v-checkbox
                  v-model="convisita.signos_vitales"
                  label="Signos vitales"
                ></v-checkbox>
              </v-flex>

            </v-layout>



              <v-expand-transition>

                <v-layout row wrap v-if="convisita.signos_vitales">

                  <v-flex xs12 sm4>
                    <v-text-field v-model="convisita.fio2" prepend-icon="assignment"
                                  label="FIO2" name="FIO2"
                                  :error-messages="errors.collect('FIO2')"></v-text-field>
                  </v-flex>

                  <v-flex xs12 sm4>
                    <v-text-field v-model="convisita.peep" prepend-icon="assignment"
                                  label="PEEP" name="PEEP"
                                  :error-messages="errors.collect('PEEP')"></v-text-field>
                  </v-flex>

                  <v-flex xs12 sm4>
                    <v-text-field v-model="convisita.tension_arterial" prepend-icon="assignment"
                                  label="TA" name="tension arterial"
                                  :error-messages="errors.collect('tension arterial')"></v-text-field>
                  </v-flex>

                  <v-flex xs12 sm4>
                    <v-text-field v-model="convisita.frec_cardiaca" prepend-icon="assignment"
                                  label="FC" name="frecuencia cardiaca"
                                  :error-messages="errors.collect('frecuencia cardiaca')"></v-text-field>
                  </v-flex>

                  <v-flex xs12 sm4>
                    <v-text-field v-model="convisita.frec_respiratoria" prepend-icon="assignment"
                                  label="FR" name="frecuencia respiratoria"
                                  :error-messages="errors.collect('frecuencia respiratoria')"></v-text-field>
                  </v-flex>

                  <v-flex xs12 sm4>
                    <v-text-field v-model="convisita.temperatura" prepend-icon="assignment"
                                  label="Temperatura" name="tempreratura"
                                  :error-messages="errors.collect('tempreratura')"></v-text-field>
                  </v-flex>

                  <v-flex xs12 sm4>
                    <v-text-field v-model="convisita.glasgow" prepend-icon="assignment"
                                  label="Glasgow" name="glasgow"
                                  :error-messages="errors.collect('glasgow')"></v-text-field>
                  </v-flex>

                </v-layout>

              </v-expand-transition>

            <v-layout row wrap>

              <v-flex xs12 md4>
                <v-checkbox
                  v-model="convisita.uci"
                  label="UCI"
                  :false-value="0"
                  :true-value="1"
                ></v-checkbox>
              </v-flex>

              <v-expand-x-transition>
                <v-flex xs12 md4 v-if="convisita.uci">
                  <v-checkbox
                    v-model="convisita.ventilacion_asistido"
                    label="Ventilación asistida"
                    :false-value="0"
                    :true-value="1"
                  ></v-checkbox>
                </v-flex>
              </v-expand-x-transition>

              <v-expand-x-transition>
                <v-flex xs12 sm4 v-if="convisita.ventilacion_asistido && convisita.uci">
                  <v-text-field v-model="convisita.frec_ve" prepend-icon="assignment"
                                label="Frec ve" name="Frec ve"
                                :error-messages="errors.collect('Frec ve')"></v-text-field>
                </v-flex>
              </v-expand-x-transition>

            </v-layout>

            <v-layout row wrap>

              <v-flex xs12>
                <v-textarea
                  v-model="convisita.evolucion"
                  outline
                  name="evolución"
                  label="Evolución"
                  v-validate="'required'" required
                  :error-messages="errors.collect('evolución')"
                ></v-textarea>
              </v-flex>

              <v-flex xs12>
                <v-textarea
                  v-model="convisita.observaciones"
                  outline
                  name="observaciones"
                  label="Observaciones"
                ></v-textarea>
              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="closeVisita">Cancelar</v-btn>
            <v-btn color="primary" @click="guardarVisita" :loading="loadingSubmit">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>

    <v-card>
      <v-toolbar dense class="elevation-0">
        <v-toolbar-title>Visitas</v-toolbar-title>
        <small class="mt-2 ml-1"> Registro y gestión</small>
        <v-spacer/>
        <v-tooltip top>
          <v-btn
            fab
            color="accent"
            small
            slot="activator"
            @click="openDialogVisita()"
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
          :items="convisitas"
          :loading="loading"
          rows-per-page-text="Registros por página"
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.id  }}</td>
            <td>{{ props.item.fecha_visita}}</td>
            <td>{{ props.item.usuario.name}}</td>
            <td class="text-xs-center">
              <v-tooltip top>
                <v-btn
                  icon
                  slot="activator"
                  @click="$emit('verDetalle', props)"
                >
                  <v-icon color="accent">remove_red_eye</v-icon>
                </v-btn>
                <span>Ver detalle</span>
              </v-tooltip>

              <v-tooltip top>
                <v-btn
                  icon
                  slot="activator"
                  @click="edit(props.item)"
                  :disabled="concurrencia.estado === 'Cerrada'"
                >
                  <v-icon color="accent">mode_edit</v-icon>
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
  import store from '@/store/complementos/index'

  export default {
    name: 'TablaVisitas',
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      Loading
    },
    props: {
      concurrenciaId: {
        type: Number,
        default: 0
      },
      concurrencia: {
        type: Object,
        default: {}
      }
    },
    data () {
      return {
        convisita: {},
        convisitas: [],
        condicionesClinicas: [],
        causalesHospitalizacion: [],
        menuDate: false,
        dialogVisita: false,
        dialogConfirm: false,
        loading: false,
        loadingSubmit: false,

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
            value: 'fecha_visita'
          },
          {
            text: 'Creado por',
            align: 'left',
            sortable: false,
            value: 'gs_usuario_id'
          },
          {
            text: 'Opciones',
            align: 'center',
            sortable: false,
            value: 'id'
          }
        ]
      }
    },
    beforeMount () {
      this.resetDialogVisita()
    },
    mounted () {
      this.getRegisto()
      this.getCondicionesClinicas()
      this.getCausalesHospitalizacion()
      console.log('xxxConcurrencia', this.concurrencia)
    },
    computed: {
      complementos () {
        return store.getters.complementosConcurrencias
      },
      computedDateFormatted () {
        return this.formDate(this.convisita.fecha_visita)
      }
    },
    methods: {
      getRegisto () {
        let loader = this.$loading.show({
          container: this.$refs.conVisitas.$el
        })
        this.axios.get('cuentasmedicas/concurrencia/visitas/' + this.concurrenciaId)
          .then((response) => {
            if (response.data !== '') {
              this.convisitas = response.data.data
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el ingreso. ' + e, color: 'danger'})
          })
      },
      getCondicionesClinicas () {
        this.axios.get('cuentasmedicas/concurrencia/condicionesclinicas')
          .then((response) => {
            if (response.data !== '') {
              this.condicionesClinicas = response.data.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer las condiciones. ' + e, color: 'danger'})
          })
      },
      getCausalesHospitalizacion () {
        this.axios.get('cuentasmedicas/tipocausalhospitalizacion')
          .then((response) => {
            if (response.data !== '') {
              this.causalesHospitalizacion = response.data.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer las causales. ' + e, color: 'danger'})
          })
      },
      resetDialogVisita () {
        this.convisita = {
          id: '',
          cm_concurrencia_id: this.concurrenciaId,
          estancia_pertinente: null,
          cm_causalhospitalizacion_id: null,
          rs_especialidadtratante_id: null,
          rs_especialidadinterconsultante_id: null,
          evolucion: null,
          observaciones: null,
          fio2: null,
          peep: null,
          frec_ve: null,
          condclinicas: [],
          causal_hospitalizacion: {tabla: null},
          habitacion_hospitalaria: null,
          signos_vitales: 0,
          uci: null,
          ventilacion_asistido: null,
          fecha_visita: null
        }
      },
      cargarDatosUltimaVisita () {
        this.convisita = JSON.parse(JSON.stringify(this.convisitas[0]))
        this.convisita.id = null
        this.formatearObjeto()
        this.dialogConfirm = false
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      openDialogVisita () {
        this.dialogVisita = true
        if (this.convisitas.length > 0) this.dialogConfirm = true
      },
      edit (item) {
        this.dialogVisita = true
        this.convisita = JSON.parse(JSON.stringify(item))
        this.formatearObjeto()
      },
      formatearObjeto () {
        this.convisita.cm_causalhospitalizacion_id = this.convisita.causal_hospitalizacion.cm_causalhospitalizacion_id
        this.convisita.rs_cum_id = this.convisita.causal_hospitalizacion.rs_cum_id
        this.convisita.cum = this.convisita.causal_hospitalizacion.cum
        this.convisita.rs_cup_id = this.convisita.causal_hospitalizacion.rs_cup_id
        this.convisita.cup = this.convisita.causal_hospitalizacion.cup
        this.convisita.rs_servicio_id = this.convisita.causal_hospitalizacion.rs_servicio_id
        this.convisita.causal_hospitalizacion = this.convisita.causal_hospitalizacion.tipo_causal_hospitalizacion
      },
      closeVisita () {
        this.dialogVisita = false
        this.resetDialogVisita()
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async guardarVisita () {
        if (await this.validador('formVisita')) {
          this.loadingSubmit = true
          let send = !this.convisita.id ? this.axios.post('cuentasmedicas/concurrencia/visitas', this.convisita) : this.axios.put('cuentasmedicas/concurrencia/visitas/' + this.convisita.id, this.convisita)
          send.then(response => {
            if (this.convisita.id) {
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Item actualizado satisfactoriamente', color: 'success'})
              this.convisitas.splice(this.convisitas.findIndex(visita => visita.id === response.data.data.id), 1, response.data.data)
            } else {
              this.convisitas.unshift(response.data.data)
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Item creado satisfactoriamente', color: 'success'})
            }
            this.dialogVisita = false
            this.closeVisita()
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
