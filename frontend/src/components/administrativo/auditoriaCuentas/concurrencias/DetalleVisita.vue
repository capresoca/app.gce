<template>
    <div v-if="value">
      <v-dialog v-model="dialog" width="720px">
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
                                label="Habitación" name="habitacion"
                                :error-messages="errors.collect('habitacion')"></v-text-field>
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
                    name="causal hospitalizacion"
                    prepend-icon="contacts"
                    v-validate="'required'" required
                    :error-messages="errors.collect('causal hospitalizacion')"
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
                    v-model="convisita.condiciones_clinicas"
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
                    v-model="signosVitales"
                    label="Signos vitales"
                  ></v-checkbox>
                </v-flex>

              </v-layout>



              <v-expand-transition>

                <v-layout row wrap v-if="signosVitales">

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
                    name="evolucion"
                    label="Evolución"
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
      <v-tooltip top >
        <v-btn
          fab
          dark
          small
          color="blue darken-2"
          slot="activator"
          @click="$emit('volver')"
          class="mb-15"
        >
          <v-icon color="white">arrow_back</v-icon>
        </v-btn>
        <span>Volver</span>
      </v-tooltip>
      <div style="display: flex; justify-content: space-between">
        <div style="font-weight: bold">{{ value.fecha_visita }}</div>
        <a v-if="concurrencia.estado === 'Abierta'" @click="edit(value)"><v-icon small color="accent">mode_edit</v-icon> Editar</a>
      </div>
      <v-card class="mb-3">
        <v-card-title>
          <span class="title font-weight-bold">Evolución</span>
          <v-spacer/>
          <v-icon>person</v-icon>
          {{ value.usuario.name }}
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <span>{{ value.evolucion }}</span><br>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-title>
          <span class="title font-weight-bold">Observaciones</span>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <span>{{ value.observaciones }}</span><br>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-title>
          <span class="title font-weight-bold">Condiciones clínicas</span>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <ul>
            <li v-for="item in value.condclinicas">{{ item.nombre }}</li>
          </ul>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions style="display: flex; justify-content: space-around;">
          <span v-if="value.fio2" class="grey--text font-weight-bold">fio2: {{ value.fio2 }}</span>
          <span v-if="value.peep" class="grey--text font-weight-bold">peep: {{ value.peep }}</span>
          <span v-if="value.tension_arterial" class="grey--text font-weight-bold">TA: {{ value.tension_arterial }}</span>
          <span v-if="value.frec_cardiaca" class="grey--text font-weight-bold">FC: {{ value.frec_cardiaca }}</span>
          <span v-if="value.frec_respiratoria" class="grey--text font-weight-bold">FR: {{ value.frec_respiratoria }}</span>
          <span v-if="value.temperatura" class="grey--text font-weight-bold">Temperatura: {{ value.temperatura }}</span>
          <span v-if="value.glasgow" class="grey--text font-weight-bold">Glasgow: {{ value.glasgow }}</span>
          <span v-if="value.uci" class="grey--text font-weight-bold">UCI: Si</span>
          <span v-if="value.uci && value.ventilacion_asistido" class="grey--text font-weight-bold">Ventilación asistida: {{ value.frec_ve }}</span>
        </v-card-actions>
      </v-card>
      <v-card>
        <v-card-title style="justify-content: center; background-color: #00bcd4; color: white">
          <div class="title font-weight-bold">OBJECIONES</div>
        </v-card-title>
      </v-card>


      <v-tabs class="mb-2 tabs-objeciones-notificaciones"
        dark
        color="cyan"
        show-arrows
      >
        <v-tabs-slider color="yellow"></v-tabs-slider>

        <v-tab
          v-for="(item, i) in items"
          :key="i"
          :href="'#tab-' + i"
        >
          <v-icon>{{ item.icon }}</v-icon> {{ item.title }}
        </v-tab>

        <v-tabs-items>
          <v-tab-item
            v-for="(item, i) in items"
            :key="i"
            :value="'tab-' + i"
          >
            <v-card flat>
              <component :is="item.component" :data="value" :concurrencia="concurrencia"/>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </v-tabs>
    </div>
</template>

<script>
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import store from '@/store/complementos/index'

  export default {
    name: 'DetalleVisita',
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    props: {
      value: {
        type: Object,
        default: null
      },
      concurrencia: {
        type: Object,
        defatul: {}
      }
    },
    data () {
      return {
        convisita: {},
        condicionesClinicas: [],
        causalesHospitalizacion: [],
        signosVitales: false,
        dialog: false,
        loadingSubmit: false,
        menuDate: false,
        items: [
          {title: 'Servicios', icon: 'compare_arrows', component: () => import('@/components/administrativo/auditoriaCuentas/concurrencias/tabsVisitas/Procedimientos')},
          {title: 'Medicamentos', icon: 'all_inbox', component: () => import('@/components/administrativo/auditoriaCuentas/concurrencias/tabsVisitas/Medicamentos')},
          {title: 'Insumos', icon: 'assessment', component: () => import('@/components/administrativo/auditoriaCuentas/concurrencias/tabsVisitas/Insumos')},
          {title: 'DX', icon: 'assignment', component: () => import('@/components/administrativo/auditoriaCuentas/concurrencias/tabsVisitas/Diagnosticos')},
          {title: 'Hallazgos', icon: 'search', component: () => import('@/components/administrativo/auditoriaCuentas/concurrencias/tabsVisitas/Hallazgos')}
          /* {title: 'Eventos', icon: 'find_in_page', component: () => import('@/components/administrativo/auditoriaCuentas/concurrencias/tabsVisitas/Eventos')} */
        ]
      }
    },
    beforeMount () {
      this.resetDialogVisita()
    },
    mounted () {
      this.getCondicionesClinicas()
      this.getCausalesHospitalizacion()
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
      edit () {
        this.dialog = true
        this.convisita = this.convisita = JSON.parse(JSON.stringify(this.value))
        this.formatearObjeto()
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
          condiciones_clinicas: [],
          causal_hospitalizacion: {tabla: null},
          habitacion_hospitalaria: null,
          uci: null,
          ventilacion_asistido: null,
          fecha_visita: null
        }
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
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      closeVisita () {
        this.dialog = false
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async guardarVisita () {
        if (await this.validador('formVisita')) {
          this.loadingSubmit = true
          let send = this.axios.put('cuentasmedicas/concurrencia/visitas/' + this.convisita.id, this.convisita)
          send.then(response => {
            if (this.convisita.id) {
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Item actualizado satisfactoriamente', color: 'success'})
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

<style>
  .mb-15 {
    margin-bottom: 15px;
  }

  .tabs-objeciones-notificaciones>.v-tabs__bar>.v-tabs__wrapper {
    height:72px !important
  }
  .tabs-objeciones-notificaciones>.v-tabs__bar>.v-tabs__wrapper>.v-tabs__container>.v-tabs__slider-wrapper {
    bottom: -24px !important
  }

</style>
