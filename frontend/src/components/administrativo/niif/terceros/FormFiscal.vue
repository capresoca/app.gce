<template>
  <v-form data-vv-scope="formFiscal">
    <v-layout row wrap>
      <v-flex xs12 sm6 md3>
        <v-select
          label="Tipo persona"
          v-model="tercero.tipo_persona"
          :items="complementos.tipos_persona"
          name="Tipo persona"
          required
          v-validate="'required'"
          :error-messages="errors.collect('Tipo persona')"
          :disabled="!verified"
          prepend-icon="people"
        >
        </v-select>
      </v-flex>
      <v-flex xs12 sm6 md3>
        <v-select
          label="Tipo contribuyente"
          v-model="tercero.tipo_contribuyente"
          :items="complementos.tipos_contribuyente"
          name="Tipo contribuyente"
          required
          v-validate="'required'"
          :error-messages="errors.collect('Tipo contribuyente')"
          :disabled="!verified"
          prepend-icon="transfer_within_a_station"
        >
        </v-select>
      </v-flex>
      <v-flex xs12 sm6 md3>
        <v-select
          label="Tipo retención"
          v-model="tercero.tipo_retencion"
          :items="complementos.tipos_retencion"
          item-value="value"
          item-text="value"
          name="Tipo retención"
          required
          v-validate="'required'"
          :error-messages="errors.collect('Tipo retención')"
          :disabled="!verified"
          prepend-icon="assignment"
        >
        </v-select>
      </v-flex>
      <v-flex xs12 sm6 md3 v-if="tercero.tipo_retencion === 'Autorretenedor'">
        <v-select
          label="Autorretenedor"
          v-model="tercero.autorretenedor"
          :items="complementos.autorretenedores"
          name="Autorretenedor"
          required
          v-validate="'required'"
          :error-messages="errors.collect('Autorretenedor')"
          :disabled="!verified"
          multiple
        >
        </v-select>
      </v-flex>
      <v-flex xs12 sm6 md3>
        <v-autocomplete
          label="Actividad económica"
          v-model="tercero.nf_ciiu_id"
          :items="complementos.ciius"
          item-value="id"
          item-text="nombre"
          name="Actividad económica"
          required
          v-validate="'required'"
          :error-messages="errors.collect('Actividad económica')"
          :disabled="!verified"
          :filter="filterCiius"
          prepend-icon="monetization_on"
        >
          <template slot="item" slot-scope="data">
            <template>
              <v-list-tile-content>
                <v-list-tile-title v-html="data.item.codigo"/>
                <v-list-tile-sub-title v-html="data.item.nombre"/>
              </v-list-tile-content>
            </template>
          </template>
        </v-autocomplete>
      </v-flex>
      <v-flex xs12 sm6 md3 v-if="!manager">
        <v-select
          color="accent"
          class="v-chips"
          v-model="tercero.tipo_tercero"
          :items="complementos.tiposterceros"
          item-text="nombre"
          item-value="id"
          label="Tipo Tercero"
          chips
          multiple
          deletable-chips
          small-chips
          name="Tipo Tercero"
          v-validate="'required'"
          :error-messages="errors.collect('Tipo Tercero')"
          :disabled="!verified"
          prepend-icon="group_add"
        ></v-select>
      </v-flex>
      <v-flex xs12 sm3 md3>
        <v-container fluid class="pa-0">
          <v-layout row wrap>
            <v-flex xs1>
              <v-tooltip top>
                <v-checkbox
                  slot="activator"
                  v-model="tercero.ica"
                  color="primary"
                  hide-details
                  :disabled="!verified">
                </v-checkbox>
                <span v-text="!tercero.ica ? '¿Presentá ICA?' : 'Desmarcar'"></span>
              </v-tooltip>
            </v-flex>
            <v-flex xs11>
              <v-text-field
                label="% ICA"
                v-model="tercero.porcentaje_ica"
                name="Porcentaje ICA"
                v-validate="'required|decimal:4|max_value:100|min_value:0'"
                :error-messages="errors.collect('Porcentaje ICA')"
                :disabled="!tercero.ica || !verified"
              ></v-text-field>
            </v-flex>
          </v-layout>
        </v-container>
      </v-flex>
      <v-flex xs12 sm6 md3>
        <v-checkbox
          label="Agente declarante"
          v-model="tercero.agente_declarante"
          :disabled="!verified"
          color="primary"
        />
      </v-flex>
    </v-layout>
  </v-form>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'FormFiscal',
    props: ['manager', 'verified'],
    data () {
      return {
        tercero: {},
        filterCiius (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.codigo + ' ' + item.nombre)
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
      'tercero.tipo_retencion' (val) {
        if (val) {
          if (val !== 'Autorretenedor') {
            this.tercero.autorretenedor = []
          }
        }
      },
      'tercero.ica' (val) {
        if (!val) {
          this.tercero.porcentaje_ica = null
          this.$validator.errors.items.splice(this.$validator.errors.items.indexOf(this.$validator.errors.items.find(item => item.field === 'Porcentaje ICA')), 1)
        }
      },
      'tercero.tipo_contribuyente' (val) {
        if (val) {
          var estado = false
          if (val === 'Régimen Simplificado' && this.tercero.tipo_retencion === 'Autorretenedor') {
            this.tercero.tipo_retencion = ''
            this.tercero.autorretenedor = []
            estado = true
          }
          if (this.complementos.tipos_retencion && this.complementos.tipos_retencion.length > 0) this.complementos.tipos_retencion.find(tipo => tipo.value === 'Autorretenedor').disabled = estado
        }
      },
      'errors.items' (val) {
        this.$emit('errors', !(val.filter(item => item.scope === 'formFiscal').length > 0))
      }
    },
    computed: {
      complementos () {
        return JSON.parse(JSON.stringify(store.getters.complementosTercerosFormFiscal))
      }
    },
    methods: {
      validate () {
        return this.$validator.validateAll('formFiscal').then(result => { return result })
      },
      assign (tercero) {
        this.tercero = tercero
      }
    }
  }
</script>

<style scoped>

</style>
