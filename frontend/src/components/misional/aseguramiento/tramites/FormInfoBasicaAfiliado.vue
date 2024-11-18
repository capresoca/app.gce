<template>
  <v-layout row wrap>
    <v-flex xs12 sm6 md3>
      <v-autocomplete
        label="Tipo identificación"
        v-model="value.gn_tipdocidentidad_id"
        :items="complementos && complementos.tipdocidentidades ? complementos.tipdocidentidades.filter(tipo => tipo.id === idsTipoIdentidad.find(val => tipo.id === val)) : null"
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
          <template v-if="data.item">
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
        v-model="value.identificacion"
        name="Identificación"
        required
        v-validate="'uniqueAfiliado:' + (value.afiliado ? value.afiliado.id + ',' + value.afiliado.identificacion : null) + '|required|alpha_num|max:' + longitudDocumento"
        :error-messages="errors.collect('Identificación')"
        :maxlength="longitudDocumento"
        :counter="longitudDocumento"
      />
    </v-flex>
    <v-flex xs12 sm6 md3>
    	<input-date
          v-model="value.fecha_expedicion"
          label="Fecha expedicion (Día/Mes/Año)"
          format="DD/MM/YYYY"
          name="Fecha expedicion"
          :min="minDate"
          :max="maxDateF"
          v-validate="'required|fechaValida:DD/MM/YYYY|date_format:DD/MM/YYYY' + (false ? '' : '|date_between:' + minDateI + ',' + maxDateFI + ',true')"
          :error-messages="errors.collect('Fecha expedicion')"
          
        ></input-date>
    </v-flex>
    <v-flex xs12 sm6 md3>
      <input-date
          v-model="value.fecha_nacimiento"
          label="Fecha nacimiento (Día/Mes/Año)"
          format="DD/MM/YYYY"
          name="Fecha nacimiento"
          :min="minDate"
          :max="maxDate"
          v-validate="'required|fechaValida:DD/MM/YYYY|date_format:DD/MM/YYYY' + (false ? '' : '|date_between:' + minDateI + ',' + maxDateI + ',true')"
          :error-messages="errors.collect('Fecha nacimiento')"
          
        ></input-date>
    </v-flex>
    <v-flex xs12 sm6 md3>
      <v-text-field
        v-upper="'value.apellido1'"
        label="Primer apellido"
        v-model="value.apellido1"
        name="Primer apellido"
        required
        v-validate="'required'"
        :error-messages="errors.collect('Primer apellido')"
      />
    </v-flex>
    <v-flex xs12 sm6 md3>
      <v-text-field
        v-upper="'value.apellido2'"
        label="Segundo apellido"
        v-model="value.apellido2"
      />
    </v-flex>
    <v-flex xs12 sm6 md3>
      <v-text-field
        v-upper="'value.nombre1'"
        label="Primer nombre"
        v-model="value.nombre1"
        name="Primer nombre"
        required
        v-validate="'required'"
        :error-messages="errors.collect('Primer nombre')"
      />
    </v-flex>
    <v-flex xs12 sm6 md3>
      <v-text-field
        v-upper="'value.nombre2'"
        label="Segundo nombre"
        v-model="value.nombre2"
      />
    </v-flex>
    <v-flex xs12 sm6 md2>
      <v-select
        label="Sexo"
        v-model="value.gn_sexo_id"
        :items="complementos.sexos"
        item-value="id"
        item-text="nombre"
        name="Sexo"
        required
        v-validate="'required'"
        :error-messages="errors.collect('Sexo')"
      />
    </v-flex>
    <v-flex xs12 sm6>
	    <postulador-v2
	      no-data="Busqueda por NIT, razon social u código de habilitación."
	      hint="identificacion"
	      item-text="nombre_razon_social"
	      data-title="identificacion"
	      data-subtitle="nombre_razon_social"
	      label="IPS"
	      entidad="entidades_contrato"
	      preicon="location_city"
	      @changeid="val => value.rs_entidade_id = val"
	      v-model="ips"
	      name="ips"
	      rules="required"
	      v-validate="'required'"
	      :error-messages="errors.collect('ips')"
	      :route-params="`rs_tipentidade_id=1&afiliado=${value.responsable_id !== null && value.responsable_id !== undefined ? value.responsable_id : value.as_afiliado_id}`"
	      no-btn-create
	      :min-characters-search="3"
	    />
	  </v-flex>
  </v-layout>
</template>

<script>
  import store from '@/store/complementos/index'
  import { Validator } from 'vee-validate'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'FormInfoBasicaAfiliado',
    props: ['value', 'tiposIdentidad', 'afitramitern'],
    components: {
    },
    filters: {
    },
    data () {
      return {
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
        ips: null,
        minDateI: '1900-01-01',
        maxDateI: '',
        menuDate: false,
        menuDatex: false,
        minDate: '1900-01-01',
        maxDate: new Date().toISOString().substr(0, 10),
        maxDateF: new Date().toISOString().substr(0, 10),
        maxDateFI: new Date().toISOString().substr(0, 10)
      }
    },
    watch: {
      'value' () {
        console.log(this.value)
        this.$validator.reset()
      },
      menuDate (val) {
        val && this.$nextTick(() => (this.$refs.picker.activePicker = 'YEAR'))
      },
      'value.fecha_expedicion' (val) {
        if (val !== undefined && val !== null) {
          // console.log(this.edad_minima_valida)
          var vars = val
          var arrVars = vars.split('/')
          this.maxDate = arrVars[2] + '-' + arrVars[1] + '-' + arrVars[0]
          var temporal = this.maxDate
          this.maxDate = (this.moment(temporal).subtract(this.edad_minima_valida, 'months')).format('YYYY-MM-DD')
          this.maxDateI = (this.moment(temporal).subtract(this.edad_minima_valida, 'months')).format('DD/MM/YYYY')
        }
      },
      'tipo_id' (val) {
        if (val) {
          // this.maxDate = (this.moment().subtract(val.edad_minima, 'months')).format('YYYY-MM-DD')
          // this.minDate = (this.moment().subtract(val.edad_maxima, 'months')).subtract(val.plazo_cambio, 'days').format('YYYY-MM-DD')
          // this.edad_minima_valida = val.edad_minima
          console.log(val.edad_minima)
          if ((!this.value.fecha_expedicion && (typeof this.value.fecha_expedicion === 'undefined')) && (this.value.fecha_expedicion === '0000-00-00') && (this.value.fecha_expedicion === null)) {
            this.maxDate = (this.moment().subtract(val.edad_minima, 'months')).format('YYYY-MM-DD')
          }
          this.minDate = (this.moment().subtract(val.edad_maxima, 'months')).subtract(val.plazo_cambio, 'days').format('YYYY-MM-DD')
          this.minDateI = (this.moment().subtract(val.edad_maxima, 'months')).subtract(val.plazo_cambio, 'days').format('DD/MM/YYYY')
          this.maxDateFI = (this.moment(this.maxDateF)).format('DD/MM/YYYY')
        }
      }
    },
    beforeMount () {
      Validator.extend('uniqueAfiliado', {
        validate: (value, id) => new Promise((resolve) => {
          if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
            this.axios.get(`afiliados?id=${id[0]}&identificacion=${value}`)
              .then(response => {
                return resolve({
                  valid: !(response.status === 200 && response.data && response.data.data && response.data.data.length && (value !== id[1])),
                  data: {
                    message: 'ya se encuentra registrado un afiliado con ésta identificación.'
                  }
                })
              }).catch(e => {
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al intentar validar la identificación del afiliado. ', error: e})
              })
          }
        }),
        getMessage: (field, params, data) => data.message
      })
    },
    mounted () {
      // if (this.afitramitern) {
      console.log(this.afitramitern)
      // }
    },
    computed: {
      idsTipoIdentidad () {
        let ids = []
        if (this.tiposIdentidad) {
          this.tiposIdentidad.forEach(item => { ids.push(item.id) })
        }
        return ids
      },
      complementos () {
        return store.getters.complementosFormInfoBasicaAfiliado
      },
      tipo_id () {
        return (this.complementos.tipdocidentidades && this.complementos.tipdocidentidades.length && (this.value && this.value.gn_tipdocidentidad_id)) ? this.complementos.tipdocidentidades.find(x => x.id === this.value.gn_tipdocidentidad_id) : null
      },
      longitudDocumento () {
        return (this.complementos.tipdocidentidades && this.complementos.tipdocidentidades.length && (this.value && this.value.gn_tipdocidentidad_id)) ? this.complementos.tipdocidentidades.find(x => x.id === this.value.gn_tipdocidentidad_id).longitud : null
      }
    },
    methods: {
      validate () {
        return this.$validator.validateAll()
      }
    }
  }
</script>

<style scoped>
</style>
