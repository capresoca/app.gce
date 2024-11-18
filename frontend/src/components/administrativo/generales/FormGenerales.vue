<template>
    <v-form data-vv-scope="formTabGeneral">
      <v-card>
        <v-container fluid grid-list-xl>
          <v-layout row wrap>
            <v-flex xs12>
              <postulador-v2
                no-data="Busqueda por nombre o número de documento."
                hint="identificacion_completa"
                item-text="nombre_completo"
                data-title="identificacion_completa"
                data-subtitle="nombre_completo"
                scope="formTabGeneral"
                :label="value.gn_tercero_id != null ? 'Razón Social ' : 'razón social '"
                entidad="terceros"
                preicon="domain"
                @changeid="val => value.gn_tercero_id = val"
                v-model="value.tercero"
                v-validate="'required'"
                name="razón social"
                :error-messages="errors.collect('razón social')"
                :disabled="!value.id ? false : !isEditing"
                no-btn-create
                :min-characters-search="3"
                clearable/>
<!--              <postulador-->
<!--                nodata="Busqueda por nombre o número de documento."-->
<!--                required-->
<!--                hint="identificacion_completa"-->
<!--                itemtext="nombre_completo"-->
<!--                datatitle="identificacion"-->
<!--                datasubtitle="nombre_completo"-->
<!--                filter="identificacion_completa,nombre_completo"-->
<!--                :label="value.gn_tercero_id != null ? 'Razón social ' : 'razón social '"-->
<!--                scope="formTabGeneral"-->
<!--                ref="postulaTercero"-->
<!--                entidad="terceros"-->
<!--                preicon="domain"-->
<!--                @change="val => value.gn_tercero_id = val"-->
<!--                @objectReturn="val => value.tercero = val"-->
<!--                :value="value.tercero"-->
<!--                :disabled="!value.id ? false : !isEditing"-->
<!--                clearable></postulador>-->
            </v-flex>
            <v-flex xs12 sm6 v-if="value.gn_tercero_id">
              <v-text-field
                label="Dirección"
                :value="value.tercero ? value.tercero.direccion : null"
                readonly
                :disabled="!value.id ? false : !isEditing"
              ></v-text-field>
            </v-flex>
            <v-flex xs12 sm3 v-if="value.gn_tercero_id">
              <v-text-field
                label="Teléfono"
                :value="value.tercero ? value.tercero.telefono_fijo : null"
                readonly
                :disabled="!value.id ? false : !isEditing"
              ></v-text-field>
            </v-flex>
            <v-flex xs12 sm3 v-if="value.gn_tercero_id">
              <v-text-field
                label="Celular"
                :value="value.tercero ? value.tercero.celular : null"
                readonly
                :disabled="!value.id ? false : !isEditing"
              ></v-text-field>
            </v-flex>
            <v-flex xs12 sm6>
              <v-autocomplete
                label="Municipio"
                v-model="value.gn_municipio_id"
                :items="complementos.municipios"
                :hint="departamentoFind"
                persistent-hint
                item-value="id"
                item-text="nombre"
                name="Municipio"
                required
                v-validate="'required'"
                :error-messages="errors.collect('Municipio')"
                :disabled="!value.id ? false : !isEditing"
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
            <v-flex xs12 sm6>
              <postulador-v2
                no-data="Busqueda por nombre o número de documento."
                hint="identificacion_completa"
                item-text="nombre_completo"
                data-title="identificacion_completa"
                data-subtitle="nombre_completo"
                scope="formTabGeneral"
                :label="value.rep_legal != null ? 'Representante legal ' : 'representante legal '"
                entidad="terceros"
                preicon="face"
                @changeid="val => value.rep_legal = val"
                v-model="value.representante"
                v-validate="'required'"
                name="representante legal"
                :error-messages="errors.collect('representante legal')"
                :disabled="!value.id ? false : !isEditing"
                no-btn-create
                :min-characters-search="3"
                clearable/>
<!--              <postulador-->
<!--                nodata="Busqueda por nombre o número de documento."-->
<!--                required-->
<!--                hint="identificacion_completa"-->
<!--                itemtext="nombre_completo"-->
<!--                datatitle="identificacion"-->
<!--                datasubtitle="nombre_completo"-->
<!--                filter="identificacion_completa,nombre_completo"-->
<!--                :label="value.rep_legal != null ? 'Representante legal ' : 'representante legal '"-->
<!--                scope="formTabGeneral"-->
<!--                ref="postulaRepresentante"-->
<!--                entidad="terceros"-->
<!--                preicon="face"-->
<!--                @change="val => value.rep_legal = val"-->
<!--                @objectReturn="val => value.representante = val"-->
<!--                :value="value.representante"-->
<!--                :disabled="!value.id ? false : !isEditing"-->
<!--                clearable></postulador>-->
            </v-flex>
          </v-layout>
        </v-container>
      </v-card>
    </v-form>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'FormGenerales',
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2')
      // Postulador: () => import('@/components/general/Postulador')
    },
    props: ['value', 'isEditing'],
    inject: {
      $validator: '$validator'
    },
    data () {
      return {
        departamentoFind: null,
        filterMunicipios (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.nombre + ' ' + item.nombre_departamento)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        }
      }
    },
    watch: {
      'value.tercero' (val) {
        if (val) {
          this.value.gn_municipio_id = val ? val.gn_munexpedicion_id : null
          this.departamentoFind = val ? this.departamentName(val.gn_munexpedicion_id) : null
        }
      },
      'value.gn_municipio_id' (val) {
        if (val) this.departamentoFind = this.departamentName(val)
        // 'Departamento: ' + this.complementos.municipios.find(x => x.id === val).nombre_departamento
      },
      'errors.items' (val) {
        this.$emit('errors', !(val.filter(item => item.scope === 'formTabGeneral').length > 0))
      }
    },
    methods: {
      departamentName (item) {
        return 'Departamento: ' + this.complementos.municipios.find(x => x.id === item).nombre_departamento
      },
      async validate () {
        return this.$validator.validateAll('formTabGeneral').then(result => { return result })
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosEmpresa
      }
    }
  }
</script>

<style scoped>

</style>
