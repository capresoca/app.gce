<template>
  <v-autocomplete
    :label="cuenta.item.id ? titleLabel : 'Buscar ' + titleLabel"
    v-model="cuenta.item"
    :items="cuenta.items"
    item-text="nombre"
    :hint="cuenta.item.id ? 'Código: ' + cuenta.item.codigo : ''"
    :search-input.sync="cuenta.search"
    persistent-hint
    :name="titleLabel"
    :required="isRequired ? false : true"
    v-validate="isRequired ? '' : 'required'"
    :error-messages="errors.collect(titleLabel)"
    :filter="cuenta.filter"
    color="primary"
    :loading="cuenta.isLoading"
    placeholder="Comience a escribir para buscar"
    no-data-text="Busqueda por código o nombre"
    return-object
  >
    <template slot="item" slot-scope="data">
      <template>
        <v-list-tile-content>
          <v-list-tile-title v-html="data.item.nombre ? data.item.nombre : ''"/>
          <v-list-tile-sub-title v-html="data.item.codigo ? data.item.codigo : ''"></v-list-tile-sub-title>
        </v-list-tile-content>
      </template>
    </template>
  </v-autocomplete>
</template>

<script>
    import {SNACK_SHOW} from '@/store/modules/general/snackbar'
    export default {
      name: 'CuentaAutocomplete',
      props: ['object', 'label', 'required', 'mode', 'scope'],
      data () {
        return {
          cuenta: {
            item: {id: null},
            items: [],
            isLoading: false,
            search: null,
            filter (item, queryText) {
              const hasValue = val => val != null ? val : ''
              const text = hasValue(item.codigo + ' ' + item.nombre)
              const query = hasValue(queryText)
              return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
            }
          }
        }
      },
      watch: {
        'cuenta.item' (val) {
          if (val) {
            val && this.$emit('change', this.typeObject ? val.id : val)
          }
        },
        'cuenta.search' (val) {
          val && val !== '' && val !== null && this.buscar()
        }
      },
      computed: {
        isRequired () {
          return typeof this.required === 'undefined'
        },
        titleLabel () {
          return typeof this.label === 'undefined' ? 'cuenta' : this.label
        },
        typeObject () {
          return typeof this.object === 'undefined'
        }
      },
      methods: {
        reset () {
          this.$validator.reset()
          this.cuenta.showContent = false
          this.cuenta.items = []
          this.cuenta.search = null
          this.cuenta.item = {id: null}
        },
        assign (item) {
          this.reset()
          this.cuenta.item = item
          this.cuenta.items.push(item)
        },
        buscar: window.lodash.debounce(function () {
          this.cuenta.isLoading = true
          this.axios.get('niifs/auxiliares/search/' + this.cuenta.search)
            .then((response) => {
              console.log(response.data)
              if (response.data.data.length > 0) {
                this.cuenta.items = response.data.data
              }
              this.cuenta.isLoading = false
            })
            .catch(e => {
              this.cuenta.isLoading = false
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de cuentas. ' + e.response, color: 'danger'})
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
