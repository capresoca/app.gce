<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="1000">
      <v-card>
        <v-card-title class="headline"><v-icon>child_care</v-icon> {{rn.id ? ' Actualización' : ' Creación'}} de recién nacido</v-card-title>
        <v-divider/>
        <v-container fluid grid-list-xl>
          <v-form data-vv-scope="formRN">
            <v-layout row wrap>
              <v-flex xs12 sm6 md3>
                <v-autocomplete
                  label="Tipo identificación"
                  v-model="rn.gn_tipdocidentidad_id"
                  :items="complementos.tipdocidentidades.filter(tipo => tipo.id === [3, 4].find(val => tipo.id === val))"
                  item-value="id"
                  item-text="nombre"
                  name="gn_tipocidentidad_id"
                  data-vv-as="Tipo identificación"
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('gn_tipocidentidad_id')"
                  :filter="filterTiposIdentidad"
                >
                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.abreviatura"/>
                        <v-list-tile-sub-title v-html="data.item.nombre"/>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>
              <v-flex xs12 sm6 md3>
                <v-text-field
                  label="Identificación"
                  v-model="rn.identificacion"
                  name="Identificación"
                  required
                  v-validate="'uniqueTercero:' + (rn.beneficiario.afiliado ? rn.beneficiario.afiliado.id : null) + '|required|alpha_num|max:' + longitudDocumento"
                  :error-messages="errors.collect('Identificación')"
                  :maxlength="longitudDocumento"
                  :counter="longitudDocumento"
                />
              </v-flex>
              <v-flex xs12 sm6 md3>
                <v-autocomplete
                  label="Municipio expedición"
                  v-model="rn.gn_munexpedicion_id"
                  :items="complementos.municipios"
                  item-value="id"
                  item-text="nombre"
                  name="Municipio expedición"
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('Municipio expedición')"
                  :filter="filterMunicipiosExpedicion"
                >
                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.nombre"/>
                        <v-list-tile-sub-title v-html="data.item.nombre_departamento"/>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>
              <v-flex xs12 sm6 md3>
                <v-menu
                  ref="Fecha nacimiento"
                  v-model="menuDate"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  min-width="290px"
                >
                  <v-text-field
                    slot="activator"
                    label="Fecha nacimiento"
                    v-model="rn.fecha_nacimiento"
                    name="Fecha nacimiento"
                    required
                    v-validate="'required|date_format:YYYY-MM-DD|date_between:' + minDate + ',' + maxDate + ',true'"
                    :error-messages="errors.collect('Fecha nacimiento')"
                    return-masked-value
                    mask="####-##-##"
                    @click="menuDate = false"
                    readonly
                  />
                  <v-date-picker
                    locale="es-co"
                    ref="picker"
                    v-model="rn.fecha_nacimiento"
                    :max="maxDate"
                    :min="minDate"
                    @input="menuDate = false"
                    @change="saveDate('Fecha nacimiento', rn.fecha_nacimiento)"
                  />
                </v-menu>
              </v-flex>
              <v-flex xs12 sm6 md3>
                <v-text-field
                  v-upper="'rn.apellido1'"
                  label="Primer apellido"
                  v-model="rn.apellido1"
                  name="Primer apellido"
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('Primer apellido')"
                />
              </v-flex>
              <v-flex xs12 sm6 md3>
                <v-text-field
                  v-upper="'rn.apellido2'"
                  label="Segundo apellido"
                  v-model="rn.apellido2"
                />
              </v-flex>
              <v-flex xs12 sm6 md3>
                <v-text-field
                  v-upper="'rn.nombre1'"
                  label="Primer nombre"
                  v-model="rn.nombre1"
                  name="Primer nombre"
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('Primer nombre')"
                />
              </v-flex>
              <v-flex xs12 sm6 md3>
                <v-text-field
                  v-upper="'rn.nombre2'"
                  label="Segundo nombre"
                  v-model="rn.nombre2"
                />
              </v-flex>
              <v-flex xs12 sm6 md2>
                <v-select
                  label="Sexo"
                  v-model="rn.gn_sexo_id"
                  :items="complementos.sexos"
                  item-value="id"
                  item-text="nombre"
                  name="Sexo"
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('Sexo')"
                />
              </v-flex>
              <v-flex xs12 sm6 md6>
                <v-select
                  label="Condición de Discapacidad"
                  v-model="rn.as_condicion_discapacidades_id"
                  :items="complementos.condicionesdiscapacidad"
                  item-value="id"
                  item-text="nombre"
                  name="Condición de Discapacidad"
                  clearable
                />
              </v-flex>
              <v-flex xs12 sm6 md4>
                <v-autocomplete
                  label="Parentesco"
                  v-model="rn.as_parentesco_id"
                  :items="complementos.parentescos"
                  item-value="id"
                  item-text="nombre"
                  name="Parentesco"
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('Parentesco')"
                />
              </v-flex>
            </v-layout>
          </v-form>
        </v-container>
        <v-divider/>
        <v-card-actions>
          <v-spacer/>
          <v-btn flat @click.stop="$emit('cancelar')">Cancelar</v-btn>
          <v-btn color="primary" @click.stop="submit">Registrar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>
  import FormInfoBasicaAfiliado from '@/components/misional/aseguramiento/tramites/FormInfoBasicaAfiliado'
  import store from '@/store/complementos/index'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import { Validator } from 'vee-validate'
  Validator.extend('uniqueTercero', {
    validate: (value, id) => new Promise((resolve) => {
      this.axios.post('exists-tercero', {id: id[0], identificacion: value})
        .then(response => {
          return resolve({
            valid: !response.data.exists,
            data: {
              message: 'ya se encuentra registrado un tercero con ésta identificación.'
            }
          })
        }).catch(e => {
          console.log('Error al intentar validar la identificación del tercero... ' + e)
        })
    }),
    getMessage: (field, params, data) => data.message
  })
  export default {
    name: 'FormRecienNacido',
    props: ['dialog', 'reciennacido', 'afitramitern'],
    components: {
      FormInfoBasicaAfiliado
    },
    filters: {
    },
    data () {
      return {
        rn: null,
        makeRecienNacido: {
          as_afitramite_id: null,
          as_beneficiario_id: null,
          beneficiario: {},
          id: null,
          gn_tipdocidentidad_id: null,
          identificacion: null,
          gn_munexpedicion_id: null,
          as_condicion_discapacidades_id: null,
          as_parentesco_id: null,
          gn_sexo_id: null,
          nombre1: null,
          nombre2: null,
          apellido1: null,
          apellido2: null,
          fecha_nacimiento: null
        },
        filterMunicipiosExpedicion (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.nombre + ' ' + item.nombre_departamento)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        filterTiposIdentidad (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.abreviatura + ' ' + item.nombre)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        menuDate: false,
        minDate: '1900-01-01',
        maxDate: new Date().toISOString().substr(0, 10)
      }
    },
    watch: {
      'dialog' (val) {
        if (!val) {
          this.rn = JSON.parse(JSON.stringify(this.makeRecienNacido))
          this.$validator.reset()
        }
      },
      menuDate (val) {
        val && this.$nextTick(() => (this.$refs.picker.activePicker = 'YEAR'))
      },
      'reciennacido' (val) {
        if (val) this.rn = val
      },
      'tipo_id' (val) {
        if (val) {
          this.maxDate = (this.moment().subtract(val.edad_minima, 'months')).format('YYYY-MM-DD')
          this.minDate = (this.moment().subtract(val.edad_maxima, 'months')).subtract(val.plazo_cambio, 'days').format('YYYY-MM-DD')
        }
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosFormAfiliacionRN
      },
      tipo_id () {
        return (this.complementos.tipdocidentidades && this.complementos.tipdocidentidades.length && (this.rn && this.rn.gn_tipdocidentidad_id)) ? this.complementos.tipdocidentidades.find(x => x.id === this.rn.gn_tipdocidentidad_id) : null
      },
      longitudDocumento () {
        return (this.complementos.tipdocidentidades && this.complementos.tipdocidentidades.length && (this.rn && this.rn.gn_tipdocidentidad_id)) ? this.complementos.tipdocidentidades.find(x => x.id === this.rn.gn_tipdocidentidad_id).longitud : null
      }
    },
    created () {
      this.rn = JSON.parse(JSON.stringify(this.makeRecienNacido))
    },
    methods: {
      async submit () {
        if (await this.$validator.validateAll('formRN')) {
          if (this.afitramitern.recien_nacidos.length ? this.afitramitern.recien_nacidos.filter(x => x.id === null).find(x => x.identificacion === this.rn.identificacion) : null) {
            this.$store.commit(SNACK_SHOW, {msg: 'Ya hay un recién nacido registrado con ésta identificación.', color: 'danger'})
          } else {
            if (this.afitramitern.id) {

            } else {
              this.$emit('download', this.rn)
            }
          }
        }
      },
      saveDate (name, date) {
        this.$refs[name].save(date)
        this.$validator.errors.items.splice(this.$validator.errors.items.indexOf(this.$validator.errors.items.find(item => item.field === name)), 1)
      }
    }
  }
</script>

<style scoped>
</style>
