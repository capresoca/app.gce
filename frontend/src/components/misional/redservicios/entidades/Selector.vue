<template>
  <v-autocomplete
    :label="entidad.item.id ? titleLabel : 'Buscar ' + titleLabel"
    v-model="entidad.item"
    :items="entidad.items"
    item-text="tercero.nombre_completo"
    :hint="entidad.item.id ? 'Código de habilitación: ' + entidad.item.cod_habilitacion : ''"
    persistent-hint
    :name="titleLabel"
    :required="isRequired ? false : true"
    v-validate="isRequired ? '' : 'required'"
    :error-messages="errors.collect(titleLabel)"
    :loading="entidad.loading"
    :search-input.sync="entidad.search"
    :filter="entidad.filter"
    no-data-text="Busqueda por nombre, NIT o código de habilitación."
    return-object
  >
    <template slot="item" slot-scope="data">
      <template>
        <v-list-tile-content>
          <v-list-tile-title v-html="data.item.tercero ? data.item.tercero.nombre_completo : ''"/>
          <v-list-tile-sub-title v-html="data.item.tercero ? tipdocidentidades.find(x => x.id === data.item.tercero.gn_tipdocidentidad_id).abreviatura + ' ' + data.item.tercero.identificacion + ' - Código de habilitación: ' + data.item.cod_habilitacion : ''"/>
        </v-list-tile-content>
      </template>
    </template>
  </v-autocomplete>
</template>
<script>
  import store from '@/store/complementos/index'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'Selector',
    props: ['object', 'label', 'required', 'mode', 'scope'],
    data () {
      return {
        entidad: {
          item: {id: null},
          items: [],
          loading: false,
          search: null,
          filter (item, queryText) {
            const hasValue = val => val != null ? val : ''
            const text = hasValue(item.cod_habilitacion + ' ' + item.tercero.identificacion + ' ' + item.tercero.nombre_completo)
            const query = hasValue(queryText)
            return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
          }
        }
      }
    },
    watch: {
      'entidad.item' (val) {
        val && this.$emit('change', this.typeObject ? val.id : val)
      },
      'entidad.search' (val) {
        val && val !== '' && val !== null && this.buscar()
      }
    },
    computed: {
      isRequired () {
        return typeof this.required === 'undefined'
      },
      tipdocidentidades () {
        return store.getters.complementosTercerosFormBasicos.tipdocidentidades
      },
      titleLabel () {
        return typeof this.label === 'undefined' ? 'entidad' : this.label
      },
      typeObject () {
        return typeof this.object === 'undefined'
      }
    },
    methods: {
      reset () {
        this.$validator.reset()
        this.entidad.showContent = false
        this.entidad.items = []
        this.entidad.search = null
        this.entidad.item = {id: null}
      },
      assign (item) {
        this.reset()
        this.entidad.item = item
        this.entidad.items.push(item)
      },
      buscar: window.lodash.debounce(function () {
        this.entidad.loading = true
        this.axios.get('entidades/?rs_tipentidade_id=' + this.mode + '&search=%20' + this.entidad.search + '%20')
          .then((response) => {
            console.log('response.data')
            console.log(response.data.data)
            if (response.data.data.length > 0) {
              this.entidad.items = response.data.data
            }
            this.entidad.loading = false
            console.log(this.entidad.items)
          })
          .catch(e => {
            this.entidad.loading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de entidades. ' + e.response, color: 'danger'})
          })
      }, 500),
      async validate () {
        return this.$validator.validateAll(this.scope).then(result => { return result })
      }
    }
  }
</script>

<style scoped>

</style>
