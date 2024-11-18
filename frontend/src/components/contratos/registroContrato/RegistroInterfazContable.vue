<template>
  <div>
    <v-layout row wrap>
      <v-flex xs12 sm12 :md9="!value.id">
        <postulador-v2
          no-data="Busqueda por nombre o número de documento."
          hint="identificacion_completa"
          item-text="nombre_completo"
          data-title="identificacion_completa"
          data-subtitle="nombre_completo"
          label="Tercero"
          :detail="detalleTercero"
          entidad="terceros"
          @changeid="val => value.gn_tercero_id = val"
          v-model="value.tercero"
          name="Tercero"
          :rules="'required'"
          v-validate="'required'"
          :error-messages="errors.collect('Tercero')"
        ></postulador-v2>
      </v-flex>
      <v-flex xs12 sm4 :md3="!value.id" :md4="value.id">
        <v-select
          label="Tipo entidad"
          v-model="value.rs_tipentidade_id"
          :items="complementos.tiposEntidad"
          item-value="id"
          item-text="tipo"
          name="Tipo entidad"
          v-validate="'required'"
          :error-messages="errors.collect('Tipo entidad')"
        />
      </v-flex>
      <v-slide-x-reverse-transition>
        <v-flex xs12 sm8 :md4="!value.id" :md8="value.id" v-if="value.rs_tipentidade_id && value.rs_tipentidade_id === 1">
          <v-combobox
            label="Buscar o crear un código de habilitación"
            v-model="value.codigo"
            :filter="filter"
            :hide-no-data="!search"
            :items="items"
            :search-input.sync="search"
            item-text="codigo"
            hide-selected
            name="Código"
            v-validate="'vvCodHabilitacionValido|required'"
            :error-messages="errors.collect('Código')"
            :loading="loadingCombo"
          >
            <template slot="append" v-if="verificandoCodigo">
              <v-progress-circular :indeterminate="true" :value="0" size="20" class="ml-2"></v-progress-circular>
            </template>
            <template slot="no-data">
              <template v-if="noDataCreate">
                <v-list-tile>
                  <span class="subheading">Crear el código </span>
                  <v-chip
                    :color="`teal lighten-1`"
                    label
                    small
                  >
                    {{ search }}
                  </v-chip>
                </v-list-tile>
              </template>
              <template v-else>
                <v-list-tile>
                  {{loadingCombo ? 'Buscando coincidencias de códigos de habilitación...' : 'Buscar coincidencias de códigos de habilitación.'}}
                </v-list-tile>
              </template>
            </template>
            <template
              slot="item"
              slot-scope="{ index, item, parent }"
            >
              <v-list-tile-content>
                {{ item.codigo }}
              </v-list-tile-content>
            </template>
          </v-combobox>
        </v-flex>
      </v-slide-x-reverse-transition>
      <v-flex xs12 :sm12="value.rs_tipentidade_id === 1" :sm8="value.rs_tipentidade_id !== 1" :md12="value.id && value.rs_tipentidade_id === 1" :md8="!value.id || (value.id && value.rs_tipentidade_id !== 1)" >
        <v-text-field
          label="Nombre"
          v-model="value.nombre"
          name="Nombre"
          v-validate="'required'"
          :error-messages="errors.collect('Nombre')"
        />
      </v-flex>
      <v-flex xs12 sm6 md6>
        <v-text-field
          label="Representante legal"
          v-model="value.replegal"
          name="Representante legal"
          v-validate="'required'"
          :error-messages="errors.collect('Representante legal')"
        />
      </v-flex>
      <v-flex xs12 sm6 md6>
        <v-text-field
          label="Gerente"
          v-model="value.gerente"
          name="Gerente"
          v-validate="'required'"
          :error-messages="errors.collect('Gerente')"
        />
      </v-flex>
      <v-flex xs12 sm6 md3>
        <v-select
          label="Tipo locación"
          v-model="value.tipo_locacion"
          :items="complementos.tiposLocacion"
          name="Tipo locación"
          v-validate="'required'"
          :error-messages="errors.collect('Tipo locación')"
        />
      </v-flex>
      <v-flex xs12 sm6 md3>
        <v-select
          label="Naturaleza"
          v-model="value.naturaleza"
          :items="complementos.naturalezas"
          name="Naturaleza"
          v-validate="'required'"
          :error-messages="errors.collect('Naturaleza')"
        />
      </v-flex>
      <v-flex xs12 sm6 md3>
        <v-select
          label="Complejidad"
          v-model="value.complejidad"
          :items="complementos.complejidades"
          name="Complejidad"
          v-validate="'required'"
          :error-messages="errors.collect('Complejidad')"
        />
      </v-flex>
      <v-slide-x-reverse-transition>
        <v-flex xs12 sm6 md3 v-if="value.rs_tipentidade_id && (value.rs_tipentidade_id === 1 || value.rs_tipentidade_id === 2)">
          <v-text-field
            label="Resolución habilitación"
            v-model="value.res_habilitacion"
            name="Resolución habilitación"
            v-validate="value.rs_tipentidade_id === 2 ? 'required' : ''"
            :error-messages="errors.collect('Resolución habilitación')"
          />
        </v-flex>
      </v-slide-x-reverse-transition>
      <v-flex xs12 sm6 md3>
        <v-text-field
          label="Télefono"
          v-model="value.telefono_sede"
          name="Télefono"
          v-validate="'required'"
          :error-messages="errors.collect('Télefono')"
        />
      </v-flex>
      <v-flex xs12 sm6 md3>
        <v-text-field
          label="Correo electrónico"
          v-model="value.correo_electronico_sede"
          name="Correo electrónico"
          v-validate="'email|required'"
          :error-messages="errors.collect('Correo electrónico')"
        />
      </v-flex>
      <v-flex xs12 sm6 md3>
        <v-autocomplete
          label="Municipio"
          v-model="value.gn_municipiosede_id"
          :items="complementos.municipios"
          item-value="id"
          item-text="nombre"
          name="Municipio"
          required
          v-validate="'required'"
          :error-messages="errors.collect('Municipio')"
          :filter="filterMunicipios"
          persistent-hint
          :hint="value.gn_municipiosede_id && complementos.municipios ? complementos.municipios.find(x => x.id === value.gn_municipiosede_id).nombre_departamento : ''"
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
        <v-text-field
          label="Dirección"
          v-model="value.direccion_sede"
          name="Dirección"
          v-validate="'required'"
          :error-messages="errors.collect('Dirección')"
        />
      </v-flex>
    </v-layout>
    <v-layout row wrap v-if="value.rs_tipentidade_id === 1">
      <v-flex d-flex>
        <v-switch
          v-model="value.ive"
          label="Realiza Interrupción Voluntaria del Embarazo"
          color="accent"
          :false-value="0"
          :true-value="1"
          hide-details
        />
      </v-flex>
      <v-flex d-flex>
        <v-switch
          v-model="value.primaria"
          label="IPS Primaria"
          color="accent"
          :false-value="0"
          :true-value="1"
          hide-details
        />
      </v-flex>
      <v-flex d-flex>
        <v-switch
          v-model="value.portabilidad"
          label="IPS de Portabilidad"
          color="accent"
          :false-value="0"
          :true-value="1"
          hide-details
        />
      </v-flex>
      <v-flex d-flex>
        <v-switch
          v-model="value.auditoria_concurrente"
          label="Auditoria Concurrente"
          color="accent"
          :false-value="0"
          :true-value="1"
          hide-details
        />
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
  import store from '@/store/complementos/index'
  import { Validator } from 'vee-validate'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroContratoDatosBasicos',
    props: ['value'],
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    data () {
      return {
        detalleTercero: () => import('@/components/administrativo/niif/terceros/DetalleTercero'),
        filterMunicipios (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.nombre + ' ' + item.nombre_departamento)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        // datos del combo box
        activator: null,
        attach: null,
        items: [],
        search: null,
        noDataCreate: false,
        loadingCombo: false,
        verificandoCodigo: false
      }
    },
    watch: {
      'search' (val) {
        val && val !== '' && val !== null && this.buscar()
      },
      'value.rs_tipentidade_id' (val) {
        if (val && val !== 1) {
          this.value.ive = 0
          this.value.primaria = 0
          this.value.portabilidad = 0
          this.value.auditoria_concurrente = 0
        }
      },
      'value.codigo' (val, prev) {
        if (val) {
          this.noDataCreate = false
          if (typeof val === 'string') {
            let v = {
              codigo: val
            }
            this.items.push(v)
            this.value.cod_habilitacion = v.codigo
            this.value.codigo = v
          } else {
            this.value.cod_habilitacion = val.codigo
            if (typeof val.direccion !== 'undefined') {
              this.value.direccion_sede = val.direccion
              this.value.correo_electronico_sede = val.email
              this.value.gerente = val.gerente
              this.value.gn_municipiosede_id = null
              this.value.nombre = val.nombre
              this.value.telefono_sede = val.telefono
              this.value.replegal = null
              this.value.tipo_locacion = null
              this.value.naturaleza = null
              this.value.complejidad = null
              this.value.res_habilitacion = null
            }
          }
        }
      }
    },
    beforeMount () {
      Validator.extend('vvCodHabilitacionValido', {
        validate: (value) => new Promise((resolve) => {
          if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
            if ((typeof value === 'string' ? value : value.codigo) !== this.value.codHabilitacionValido) {
              return resolve(new Promise((resolve) => {
                this.verificandoCodigo = true
                this.axios.get(`entidades?cod_habilitacion=${(typeof value === 'string' ? value : value.codigo)}`)
                  .then((response) => {
                    resolve(response.data.data.length > 0 ? {valid: false, data: {message: `El código de habilitación seleccionado ya se encuentra vinculado a otra entidad.`}} : {valid: true, data: {message: ''}})
                    this.verificandoCodigo = false
                  })
                  .catch(e => {
                    this.$store.commit(SNACK_ERROR_LIST, {expression: ' al validar el código de habilitación. ', error: e})
                    resolve({valid: false, data: {message: `Error en el servidor al validar el código de habilitación.`}})
                    this.verificandoCodigo = false
                  })
              })
              )
            } else {
              return resolve({
                valid: true,
                data: {
                  message: ''
                }
              })
            }
          }
        }),
        getMessage: (field, params, data) => data.message
      })
    },
    computed: {
      complementos () {
        return store.getters.complementosEntidadFormInformacionBasica
      }
    },
    methods: {
      buscar: window.lodash.debounce(function () {
        this.loadingCombo = true
        this.axios.get('entidades_bases?search=%25' + this.search + '%25')
          .then((response) => {
            this.items = response.data
            this.noDataCreate = this.items.length === 0
            this.loadingCombo = false
          })
          .catch(e => {
            this.loadingCombo = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al buscar coincidencias en entidades base. ', error: e})
          })
      }, 500),
      validate () {
        return this.$validator.validateAll()
      },
      reset () {
        return this.$validator.reset()
      },
      filter (item, queryText, itemText) {
        if (item.header) return false
        const hasValue = val => val != null ? val : ''
        const text = hasValue(itemText)
        const query = hasValue(queryText)
        return text.toString()
          .toLowerCase()
          .indexOf(query.toString().toLowerCase()) > -1
      }
    }
  }
</script>

<style scoped>
</style>
