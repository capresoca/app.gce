<template>
  <v-form data-vv-scope="formComplementarios">
    <v-layout row wrap>
      <v-flex xs12 sm6 md3>
        <v-text-field
          v-upper="'tercero.telefono_fijo'"
          label="Teléfono fijo"
          v-model="tercero.telefono_fijo"
          name="Telefono fijo"
          v-number="'tercero.telefono_fijo'"
          data-vv-validate-on="input|change|custom|focus|blur"
          v-validate="'numeric|max:10'"
          :error-messages="errors.collect('Telefono fijo')"
          :disabled="!verified"
          prepend-icon="local_phone"
        />
      </v-flex>
      <v-flex xs12 sm6 md3>
        <v-text-field
          label="Celular"
          v-model="tercero.celular"
          name="Celular"
          v-number="'tercero.celular'"
          data-vv-validate-on="input|change|custom|focus|blur"
          v-validate="'numeric|max:10|required'"
          :error-messages="errors.collect('Celular')"
          :disabled="!verified"
          prepend-icon="phone_android"
        />
      </v-flex>
      <v-flex xs12 md6>
        <v-text-field
          label="Correo electrónico"
          v-model="tercero.correo_electronico"
          name="Correo electrónico"
          v-validate="'email'"
          data-vv-validate-on="input|change|custom|blur"
          :error-messages="errors.collect('Correo electrónico')"
          :disabled="!verified"
          prepend-icon="email"
        />
      </v-flex>
      <v-flex xs12 md3 v-if="manager !== 'Afiliado'">
        <v-select
          label="Ciudadanía"
          v-model="tercero.ciudadania"
          :items="ciudadanias"
          name="ciudadanía"
          required
          v-validate="'required'"
          :error-messages="errors.collect('ciudadanía')"
          :disabled="!verified || requiereNovedad"
          prepend-icon="assignment_ind"
        >
        </v-select>
      </v-flex>
      <v-flex xs12 sm6 md3>
        <v-autocomplete
          label="Municipio residencia"
          v-model="tercero.gn_municipio_id"
          :items="complementos.municipios"
          item-value="id"
          item-text="nombre"
          name="Municipio residencia"
          required
          v-validate="'required'"
          :error-messages="errors.collect('Municipio residencia')"
          :disabled="!verified || requiereNovedad"
          :filter="filterMunicipios"
          prepend-icon="location_city"
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
      <v-flex v-if="manager === 'Afiliado'" xs12 sm6 md2>
        <v-select
          label="Zona"
          v-model="tercero.gn_zona_id"
          :items="complementos2.zonas"
          item-value="id"
          item-text="nombre"
          name="Zona"
          
          :error-messages="errors.collect('Zona')"
          :disabled="!verified || !tercero.gn_municipio_id || requiereNovedad"
        />
      </v-flex>
      <v-flex v-if="manager === 'Afiliado'" xs12 sm6 md2>
        <v-select
          label="Nomenclaturas"
          v-model="tercero.nomenclatura"
          :items="tercero.gn_zona_id == 1 ? complementos.nomenclaturasUrbano : complementos.nomenclaturasRural"
          item-value="nomenclatura"
          item-text="abreviatura"
          name="nomenclatura"
          
          :error-messages="errors.collect('nomenclatura')"
          :disabled="!verified || !tercero.gn_zona_id || requiereNovedad"
        ></v-select>
      </v-flex>
      <v-flex xs12 md4>
        <v-text-field
          v-upper="'tercero.direccion'"
          label="Dirección"
          v-model="tercero.direccion"
          name="Dirección"
          required
          v-validate="'required|verify_direccion'"
          :error-messages="errors.collect('Dirección')"
          :disabled="!verified"
          prepend-icon="home"
        />
      </v-flex>
    </v-layout>
  </v-form>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'FormComplementarios',
    props: ['manager', 'verified', 'edicionTotal'],
    data () {
      return {
        tercero: {},
        filterMunicipios (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.nombre + ' ' + item.nombre_departamento)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        ciudadanias: ['Nacional', 'Extranjero']
      }
    },
    watch: {
      'verified' (val) {
        if (!val) {
          this.$validator.reset()
        }
      },
      'errors.items' (val) {
        this.$emit('errors', !(val.filter(item => item.scope === 'formComplementarios').length > 0))
      }
    },
    computed: {
      requiereNovedad () {
        return this.tercero && this.tercero.id && this.manager === 'Afiliado' && this.tercero.estado !== 11 && !this.edicionTotal
      },
      complementos2 () {
        return store.getters.complementosFormAfiliados
      },
      complementosnomenclaturas () {
        return store.getters.complementosNomenclaturas
      },
      complementos () {
        return store.getters.complementosTercerosFormComplementarios
      }
    },
    methods: {
      validate () {
        return this.$validator.validateAll('formComplementarios').then(result => { return result })
      },
      assign (tercero) {
        this.tercero = tercero
      }
    },
    mounted () {
      this.$validator.extend('verify_direccion', {
        getMessage: field => `El campo dirección no debe contener carácteres especiales.`,
        validate: value => {
          var strongRegex = new RegExp('^[a-zA-Z0-9_ ]*$')
          return strongRegex.test(value)
        }
      })
    }
  }
</script>

<style scoped>

</style>
