<template>
  <v-form data-vv-scope="formBasicoProcesoContractual">
    <v-layout row wrap>
      <v-flex xs12 sm6 md3>
        <v-autocomplete
          label="Dependencia"
          v-model="value.pr_dependencia_id"
          :items="complementos.dependencias"
          item-value="dependencia"
          item-text="descripcion"
          :filter="filterDependencia"
          name="Dependencia"
          v-validate="'required'"
          :error-messages="errors.collect('Dependencia')"
        >
          <template slot="item" slot-scope="data">
            <template>
              <v-list-tile-content>
                <v-list-tile-title v-html="data.item.descripcion"/>
                <v-list-tile-sub-title v-html="'Código:' + str_pad(data.item.dependencia,6)"/>
              </v-list-tile-content>
            </template>
          </template>
        </v-autocomplete>
      </v-flex>
      <v-flex xs12 sm6 md3>
        <v-switch
          label="Servicios de salud"
          v-model.number="value.servicios_salud"
          :false-value="0"
          :true-value="1"
        ></v-switch>
      </v-flex>
      <v-flex xs12>
        <v-textarea
          label="Objeto contractual"
          v-model="value.objeto"
          rows="1"
          auto-grow
          name="Objeto contractual"
          v-validate="'required'"
          :error-messages="errors.collect('Objeto contractual')"
        ></v-textarea>
      </v-flex>
    </v-layout>
  </v-form>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'FormDatosBasicos',
    props: ['value'],
    data () {
      return {
        filterDependencia: (item, queryText) => this.getFilter(item, queryText, item.codigo + ' ' + item.nombre)
      }
    },
    components: {
    },
    watch: {
    },
    computed: {
      complementos () {
        return store.getters.complementosProcesosContractualesFormBasicos
      }
    },
    methods: {
      reset () {
        this.$validator.reset()
      },
      async validate () {
        let errorForm = await this.$validator.validateAll('formBasicoProcesoContractual')
        return errorForm
      },
      getFilter (item, queryText, compareString) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(compareString)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      }
    }
  }
</script>

<style scoped>

</style>
