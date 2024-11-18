<template>
  <v-form data-vv-scope="formBasicos">
    <v-layout row wrap>
      <v-flex xs12 sm6 md4>
        <v-autocomplete
          label="Tipo identificación"
          v-model="tercero.gn_tipdocidentidad_id"
          :items="complementos.tipdocidentidades"
          item-value="id"
          item-text="nombre"
          name="gn_tipocidentidad_id"
          data-vv-as="Tipo identificación"
          required
          v-validate="'required'"
          :error-messages="errors.collect('gn_tipocidentidad_id')"
          :disabled="verified && (requiereNovedad || !tercero.id)"
          :filter="filterTiposIdentidad"
          prepend-icon="person_pin"
        >
          <template slot="item" slot-scope="data">
            <template>
              <v-list-tile-content>
                <v-list-tile-title v-html="data.item.abreviatura"></v-list-tile-title>
                <v-list-tile-sub-title v-html="data.item.nombre"></v-list-tile-sub-title>
              </v-list-tile-content>
            </template>
          </template>
        </v-autocomplete>
      </v-flex>
      <v-flex xs12 sm6 md4>
        <v-layout align-center class="pt-2 px-2">
          <v-text-field
            v-upper="'tercero.identificacion'"
            label="Identificación"
            v-model="tercero.identificacion"
            name="Identificación"
            required
            :type="tipo_identidad"
            v-validate="'max:' + longitudDocumento + (tipo_identidad === 'text' ? '|alpha_num' : '|numeric') + '|required'"
            :error-messages="errors.collect('Identificación')"
            :disabled="(!tercero.gn_tipdocidentidad_id || verified) && (requiereNovedad || !tercero.id)"
            :maxlength="longitudDocumento"
            :counter="longitudDocumento"
            @keyup.enter="(tercero.gn_tipdocidentidad_id && !verified) ? verificaId() : ''"
            :suffix="(!!tercero.digito_verificacion && tercero.digito_verificacion !== null && (tercero.gn_tipdocidentidad_id === 12 || tercero.gn_tipdocidentidad_id === 1))  ? 'DV' : ''"
            prepend-icon="subtitles"
          >
            <v-avatar v-if="(manager !== 'Afiliado' && tercero.digito_verificacion !== null && (tercero.gn_tipdocidentidad_id === 12 || tercero.gn_tipdocidentidad_id === 1))" slot="append" color="primary" size="26">
              <span class="white--text subheading">{{tercero.digito_verificacion}}</span>
            </v-avatar>
          </v-text-field>
          <v-tooltip top v-if="tercero.gn_tipdocidentidad_id">
            <v-btn
              :disabled="verified || !tercero.identificacion"
              slot="activator"
              flat
              icon
              color="accent"
              @click="verificaId"
            >
              <v-icon>{{verified ? 'done_all' : 'done'}}</v-icon>
            </v-btn>
            <span>{{verified ? 'Identificación verificada' : 'Verificar identificación'}}</span>
          </v-tooltip>
        </v-layout>
      </v-flex>
      <v-flex xs12 sm6 md4>
        <v-autocomplete
          v-if="manager !== 'Afiliado'"
          label="Municipio expedición"
          v-model="tercero.gn_munexpedicion_id"
          :items="complementos.municipios"
          item-value="id"
          item-text="nombre"
          name="Municipio expedición"
          required
          v-validate="'required'"
          :error-messages="errors.collect('Municipio expedición')"
          :disabled="!verified"
          :filter="filterMunicipiosExpedicion"
          prepend-icon="location_city">
          <template slot="item" slot-scope="data">
            <template>
              <v-list-tile-content>
                <v-list-tile-title v-html="data.item.nombre"></v-list-tile-title>
                <v-list-tile-sub-title v-html="data.item.nombre_departamento"></v-list-tile-sub-title>
              </v-list-tile-content>
            </template>
          </template>
        </v-autocomplete>
        <template v-if="manager === 'Afiliado'">
          <input-date
            v-model="tercero.fecha_expedicion"
            label="Fecha expedicion (Día/Mes/Año)"
            format="DD/MM/YYYY"
            name="Fecha expedición"
            :max="maxDateI"
            v-validate="'required|fechaValida:DD/MM/YYYY|date_format:DD/MM/YYYY' + (!verified ? '' : '|date_between:' + minDate + ',' + maxDate + ',true')"
            :error-messages="errors.collect('Fecha expedición')"
            :disabled="!verified"
          />
        </template>
      </v-flex>
    </v-layout>
    <v-layout row wrap>
      <template v-if="!esEmpresa">
        <v-flex xs12 sm6 md3>
          <v-text-field
            v-upper="'tercero.apellido1'"
            label="Primer apellido"
            v-model="tercero.apellido1"
            name="Primer apellido"
            required
            v-validate="'required'"
            :error-messages="errors.collect('Primer apellido')"
            :disabled="!verified || requiereNovedad"
            prepend-icon="person"
          ></v-text-field>
        </v-flex>
        <v-flex xs12 sm6 md3>
          <v-text-field
            v-upper="'tercero.apellido2'"
            label="Segundo apellido"
            v-model="tercero.apellido2"
            :disabled="!verified || requiereNovedad"
            prepend-icon="person"
          ></v-text-field>
        </v-flex>
        <v-flex xs12 sm6 md3>
          <v-text-field
            v-upper="'tercero.nombre1'"
            label="Primer nombre"
            v-model="tercero.nombre1"
            name="Primer nombre"
            required
            v-validate="'required'"
            :error-messages="errors.collect('Primer nombre')"
            :disabled="!verified || requiereNovedad"
            prepend-icon="person"
          ></v-text-field>
        </v-flex>
        <v-flex xs12 sm6 md3>
          <v-text-field
            v-upper="'tercero.nombre2'"
            label="Segundo nombre"
            v-model="tercero.nombre2"
            :disabled="!verified || requiereNovedad"
            prepend-icon="person"
          ></v-text-field>
        </v-flex>
      </template>
      <v-flex xs12 v-else>
        <v-text-field
          v-upper="'tercero.razon_social'"
          label="Razón social"
          v-model="tercero.razon_social"
          key="Razón social"
          name="Razón social"
          required
          v-validate="'required'"
          :error-messages="errors.collect('Razón social')"
          :disabled="!verified || requiereNovedad"
          prepend-icon="assignment"
        ></v-text-field>
      </v-flex>
    </v-layout>
  </v-form>
</template>

<script>
  import store from '@/store/complementos/index'
  import InputDate from '@/components/general/InputDate'
  export default {
    name: 'FormBasicos',
    props: ['manager', 'verified', 'edicionTotal'],
    inject: ['$validator'],
    components: {
      InputDate,
      InputDetailFlex: () => import('@/components/general/InputDetailFlex')
    },
    data () {
      return {
        menuDatex: false,
        tercero: {},
        maxDateI: this.moment().format('YYYY-MM-DD'),
        maxDate: this.moment().format('DD/MM/YYYY'),
        minDate: '01/01/1900',
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
        }
      }
    },
    watch: {
      'verified' (val) {
        if (!val) {
          this.$validator.reset()
        }
      },
      'tercero.id' (val) {
        if (val) {
          this.$emit('verified', true)
        }
      },
      'tercero.gn_tipdocidentidad_id' (val) {
        if (val) {
          if (val === 12) {
            this.tercero.nombre1 = ''
            this.tercero.nombre2 = ''
            this.tercero.apellido1 = ''
            this.tercero.apellido2 = ''
          } else {
            this.tercero.razon_social = ''
          }
        }
      },
      'errors.items' (val) {
        this.$emit('errors', !(val.filter(item => item.scope === 'formBasicos').length > 0))
      }
    },
    computed: {
      requiereNovedad () {
        return false
        // return this.tercero && this.tercero.id && this.manager === 'Afiliado' && this.tercero.estado !== 1 && !this.edicionTotal
      },
      complementos () {
        let beforeComplement = JSON.parse(JSON.stringify(store.getters.complementosTercerosFormBasicos))
        if (this.manager === 'Afiliado' && beforeComplement.tipdocidentidades) {
          beforeComplement.tipdocidentidades = beforeComplement.tipdocidentidades.filter(x => x.id !== 12)
        }
        return beforeComplement
      },
      tipo_identidad () {
        return this.tercero && (this.tercero.gn_tipdocidentidad_id === 1 || this.tercero.gn_tipdocidentidad_id === 12) ? 'number' : 'text'
      },
      esEmpresa () {
        return this.tercero.gn_tipdocidentidad_id === 12
      },
      longitudDocumento () {
        if (this.complementos.tipdocidentidades && this.complementos.tipdocidentidades.length && this.tercero.gn_tipdocidentidad_id) {
          return this.complementos.tipdocidentidades.find(tipo => tipo.id === this.tercero.gn_tipdocidentidad_id).longitud
        }
      }
    },
    methods: {
      async verificaId () {
        if (this.tercero.identificacion) {
          if (!this.$validator.errors.items.find(item => item.field === 'Identificación')) {
            if (this.manager === 'Afiliado') {
              await this.axios.get(`afiliados?id=${this.tercero.id}&identificacion=${this.tercero.identificacion}`)
                .then(response => {
                  if (response.status === 200 && response.data && response.data.data && response.data.data.length) this.tercero = response.data.data[0]
                  this.$emit('update', this.tercero)
                  this.$emit('verified', true)
                }).catch(e => {
                  const item = this.$validator.fields.items.find(item => item.name === 'Identificación')
                  this.$validator.errors.items.push({ id: item.id, field: item.name, msg: 'Error al intentar verificar la identificación: ' + e, rule: 'required', scope: item.scope })
                })
            } else {
              await this.axios.post('exists-tercero', {id: this.tercero.id, identificacion: this.tercero.identificacion})
                .then(response => {
                  let validar = true
                  this.tercero.digito_verificacion = response.data.dv
                  if (response.data.exists) {
                    validar = true
                    this.tercero = response.data.tercero
                  }
                  if (validar) {
                    this.$emit('update', this.tercero)
                    this.$emit('verified', true)
                  }
                }).catch(e => {
                  const item = this.$validator.fields.items.find(item => item.name === 'Identificación')
                  this.$validator.errors.items.push({ id: item.id, field: item.name, msg: 'Error al intentar verificar la identificación: ' + e, rule: 'required', scope: item.scope })
                })
            }
          }
        }
      },
      validate () {
        return this.$validator.validateAll('formBasicos').then(result => { return result })
      },
      assign (tercero) {
        this.tercero = tercero
        this.$emit('verified', this.tercero.id !== null)
        this.$emit('update', this.tercero)
      }
    }
  }
</script>

<style scoped>

</style>
