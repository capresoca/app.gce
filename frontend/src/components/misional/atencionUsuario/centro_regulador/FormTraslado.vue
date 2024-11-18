<template>
  <v-dialog v-model="value" persistent max-width="750px">
      <v-form data-vv-scope="formReftraslado">
        <v-card class="pa-0">
          <v-container fluid grid-list-xs grid-list-xl>
            <v-layout row wrap class="refSpace">
              <v-flex xs12 sm12  class="grey lighten-4" :class="{'md5 lg5 xl5': (typeForm === '4' || typeForm === '8' || typeForm === '9')}">
                <v-toolbar class="grey lighten-4 elevation-0 toolbar--dense">
                  <v-layout row wrap>
                    <v-flex xs12 sm6 md12 class="pa-0 pt-2 head1">
                      <v-toolbar-title v-text="titleForm"></v-toolbar-title>
                    </v-flex>
                    <v-flex xs12 sm6 md12 class="pa-0 head2">
                      <h2 class="d-inline-block subheading" v-text="tapModalTituloSecond"></h2>
                      <!--<div v-if="referencia.id !== null" class="d-inline-block">-->
                      <!--<v-chip label color="white" text-color="red" dark absolute right top>-->
                      <!--<span class="subheading">N°</span>&nbsp;-->
                      <!--<span class="subheading" v-text="referencia.consecutivo == null ? '0000' : referencia.consecutivo"></span>-->
                      <!--</v-chip>-->
                      <!--</div>-->
                    </v-flex>
                  </v-layout>
                  <!--&nbsp;-->
                </v-toolbar>
              </v-flex>
              <v-spacer></v-spacer>
              <v-divider vertical class="dNone pl-1"></v-divider>
              <v-flex xs12 sm12 md3 lg3 xl3>
                <v-datetime-picker v-if="typeForm === '4'" label="Fecha Solicitud" :datetime="datetimeThree" @input="updateDateTimeThree"
                :timeData="traslado.traslado.fecha_solicitud" :min="traslado.presentacion.fecha_aceptacion" :max="moment().format('YYYY-MM-DD HH:mm:ss')"></v-datetime-picker>
                <v-datetime-picker v-else-if="typeForm === '8'" label="Fecha Traslado" :datetime="datetimeFour" @input="updateDateTimeFour"
                :timeData="traslado.traslado.fecha_traslado" :min="traslado.traslado.fecha_solicitud" :max="moment().format('YYYY-MM-DD')"></v-datetime-picker>
                <!--:min="traslado.traslado.fecha_solicitud"-->
                <v-datetime-picker v-else-if="typeForm === '9'" label="Fecha Llegada" :datetime="datetimeFive" @input="updateDateTimeFive"
                :timeData="traslado.traslado.fecha_llegada" :max="moment().format('YYYY-MM-DD HH:mm:ss')" :showCurrent="getMaxFecha(traslado.traslado.fecha_traslado)"></v-datetime-picker>
                <!--:min="traslado.traslado.fecha_traslado"-->
              </v-flex>
              <v-flex xs12 sm6 md6 class="pb-2" v-if="typeForm === '4'">
                <v-select
                  :items="tiposTraslados"
                  v-model="traslado.traslado.tipo_traslado"
                  label="Tipo Traslado"
                  name="tipo traslado"
                  :error-messages="errors.collect('tipo traslado')"
                  required v-validate="'required'"
                ></v-select>
              </v-flex>
              <v-flex xs12 sm6 md6 class="pb-2" v-if="typeForm === '4'">
                <v-select
                  :items="tiposAmbulancias"
                  v-model="traslado.traslado.tipo_ambulancia"
                  label="Tipo Transporte"
                  name="tipo transporte"
                  :error-messages="errors.collect('tipo transporte')"
                  required v-validate="'required'"
                ></v-select>
              </v-flex>
              <v-flex xs12 v-if="typeForm === '4'">
                <postulador-v2
                no-data="Busqueda por NIT, razon social u código de habilitación."
                hint="tercero.identificacion_completa"
                item-text="nombre"
                data-title="tercero.identificacion_completa"
                data-subtitle="nombre"
                label="Entidad Transportadora"
                entidad="entidades"
                :disabled="transportDisabled"
                preicon="local_shipping"
                @changeid="val => traslado.traslado.entidad_transporta_id = val"
                v-model="traslado.traslado.entidad_transportadora"
                name="entidad transportadora"
                route-params="rs_tipentidade_id=4"
                rules="required"
                v-validate="'required'"
                :error-messages="errors.collect('entidad transportadora')"
                no-btn-create
                :min-characters-search="3"
                clearable/>
              </v-flex>
              <v-flex xs12 sm6 v-if="typeForm === '4'">
                <postulador-v2
                no-data="Busqueda por NIT, razon social u código de habilitación."
                hint="tercero.identificacion_completa"
                item-text="nombre"
                data-title="tercero.identificacion_completa"
                data-subtitle="nombre"
                label="IPS Origen"
                entidad="entidades"
                preicon="location_city"
                @changeid="val => traslado.traslado.entidad_origen_id = val"
                v-model="traslado.traslado.entidad_origen"
                name="IPS Origen"
                route-params="rs_tipentidade_id=1"
                rules="required"
                disabled
                v-validate="'required'"
                :error-messages="errors.collect('IPS Origen')"
                no-btn-create
                :min-characters-search="3"/>
              </v-flex>
              <v-flex xs12 sm6 v-if="typeForm === '4'">
                <postulador-v2
                no-data="Busqueda por NIT, razon social u código de habilitación."
                hint="tercero.identificacion_completa"
                item-text="nombre"
                data-title="tercero.identificacion_completa"
                data-subtitle="nombre"
                label="IPS Destino"
                entidad="entidades"
                preicon="location_city"
                @changeid="val => traslado.traslado.entidad_destino_id = val"
                v-model="traslado.traslado.entidad_destino"
                name="IPS Destino"
                route-params="rs_tipentidade_id=1"
                rules="required"
                readonly
                v-validate="'required'"
                :error-messages="errors.collect('IPS Destino')"
                no-btn-create
                :min-characters-search="3"
                />
              </v-flex>
              <v-flex xs12 sm6 class="pb-0 pt-0" v-if="typeForm === '4'">
                <v-textarea
                  v-model="traslado.traslado.contacto"
                  color="primary"
                  name="contacto"
                  required
                  v-validate="'required'"
                  key="contacto"
                  :error-messages="errors.collect('contacto')">
                  <div slot="label">
                    Contacto
                  </div>
                </v-textarea>
              </v-flex>
              <v-flex xs12 class="pb-0 pt-0" v-if="typeForm === '10'">
                <v-select
                  :items="opcionesCancelado"
                  v-model="cancelado"
                  @change="val => traslado.au_tipaccion_id = val ? val.id : null"
                  item-text="accion"
                  name="motivo"
                  label="Motivo"
                  return-object
                ></v-select>
              </v-flex>
              <v-flex xs12 class="pb-0 pt-0" :class="{'sm6': (typeForm === '4')}">
                <v-textarea
                  v-model="traslado.observaciones"
                  color="primary"
                  name="observaciones"
                  key="observaciones"
                  :error-messages="errors.collect('observaciones')"
                  required v-validate="'required'"
                >
                  <div slot="label">
                    Observaciones
                  </div>
                </v-textarea>
              </v-flex>
            </v-layout>
          </v-container>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn flat @click.stop="$emit('cancelar')">Cancelar</v-btn>
            <v-btn flat color="primary" @click.prevent="submit">Guardar</v-btn>
            <!--<v-btn @click="save" color="blue darken-1" type="submit" flat>Guardar</v-btn>-->
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import VDatetimePicker from '../../../general/VDatetimePicker'
  export default {
    name: 'FormTraslado',
    inject: {
      $validator: '$validator'
    },
    props: {
      value: {
        type: Boolean,
        default: false
      },
      traslado: {
        type: Object,
        default: null
      },
      typeForm: {
        type: String,
        default: null
      },
      referencia: {
        type: Object,
        default: null
      },
      titleForm: {
        type: String,
        default: ''
      },
      optionsCancelable: {
        type: Array,
        default: null
      }
    },
    components: {
      VDatetimePicker,
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    watch: {
      'traslado' (val) {
        // console.log('traslado', val)
      },
      'optionsCancelable' (values) {
        this.opcionesCancelado = values.length ? values : []
      },
      // 'errors.items' (val) {
      //   this.$emit('errors', !(val.filter(item => item.scope === 'formReftraslado').length > 0))
      // },
      'value' (val) {
        if (val === false) {
          this.$validator.reset()
          this.cancelado = null
        }
      },
      'traslado.presentacion.entidad' (val) {
        this.traslado.traslado.entidad_destino = !val ? null : val
      },
      'traslado.traslado.tipo_traslado' (val) {
        this.traslado.traslado.tipo_ambulancia = (val === 'Interno') ? 'Otro' : null
        this.transportDisabled = (val === 'Interno') ? true : null
      }
    },
    data () {
      return {
        datetimeThree: this.moment().format('YYYY-MM-DD HH:mm:ss'),
        datetimeFour: this.moment().format('YYYY-MM-DD HH:mm:ss'),
        datetimeFive: this.moment().format('YYYY-MM-DD HH:mm:ss'),
        tiposTraslados: ['Terrestre', 'Aereo', 'Interno'],
        cancelado: null,
        transportDisabled: false,
        opcionesCancelado: [],
        tiposAmbulancias: ['Transporte Asistencial Basico', 'Transporte Asistencial Medicalizado', 'Otro']
      }
    },
    methods: {
      // getFechaValidacion (val) {
      //   let datetime = this.moment(val).format('YYYY-MM-DD h:mm:ss a')
      //   let dateFormat = datetime.split(' ')[0]
      //   // let dateFormat = this.moment(date1).format('YYYY/MM/DD')
      //   return `${dateFormat}`
      // },
      getMaxFecha (val) {
        let datetime = this.moment(val).format('YYYY-MM-DD h:mm:ss a')
        let dateFormat = datetime.split(' ')[0]
        // let dateFormat2 = datetime.split(' ')[1]
        // let dateFormat = this.moment(date1).format('YYYY/MM/DD')
        return `${dateFormat}`
      },
      updateDateTimeThree (datetimeThree) {
        this.datetimeThree = datetimeThree
        this.traslado.traslado.fecha_solicitud = datetimeThree
      },
      updateDateTimeFour (datetimeFour) {
        this.datetimeFour = datetimeFour
        this.traslado.traslado.fecha_traslado = datetimeFour
      },
      updateDateTimeFive (datetimeFive) {
        this.datetimeFour = datetimeFive
        this.traslado.traslado.fecha_llegada = datetimeFive
      },
      async submit () {
        if (await this.validate()) {
          this.$emit('crear', this.traslado)
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no deben estar vacíos.', color: 'danger'})
        }
      },
      validate () {
        return this.$validator.validateAll('formReftraslado').then(result => { return result })
      }
    },
    computed: {
      // tapModalTitulo () {
      //   return this.typeForm === '4' ? 'Solicitud de Traslado' : (this.typeForm === '14' ? 'Traslado Aceptado' : 'Traslado Terminado')
      // },
      tapModalTituloSecond () {
        return this.traslado.presentacion
          ? 'IPS: ' + (this.traslado.presentacion.entidad
            ? this.traslado.presentacion.entidad.nombre : '####') : '######'
      }
    }
  }
</script>

<style scoped>
  .dividerTwo {
    padding: 0.1mm;
    background-color: #f5f5f5 !important;
    border-color: #f5f5f5 !important;
  }
  .refSpace .spacer {
    background-color: #f5f5f5 !important;
    border-color: #f5f5f5 !important;
  }
  @media (max-width: 601px) {
    .head2 {
      text-align: right !important;
    }
  }
  @media (min-width: 602px) and (max-width: 959px){
    .head1 {
      padding-top: 4px !important;
    }
    .head2 {
      text-align: right;
    }
    .head2 h2 {
      padding-top: 12px;
      font-size: 17px !important;
      font-weight: 500;
      line-height: 1 !important;
      letter-spacing: 0.02em !important;
      font-family: 'Roboto', sans-serif !important;
    }
  }

  @media (max-width: 959px) {
    .dNone {
      display: none !important;
    }
    .refSpace .spacer {
      display: none !important;
    }
  }
</style>
