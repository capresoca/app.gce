<template>
  <v-flex>
    <v-layout align-center>
      <v-autocomplete
        :prepend-icon="prependIcon"
        :label="value ? label : `Buscar ${label}`"
        :value="value"
        :items="items"
        :item-text="itemText"
        :hint="hint"
        persistent-hint
        :loading="loading"
        :search-input.sync="search"
        :no-data-text="textSearch"
        return-object
        :disabled="disabled"
        :readonly="readonly"
        :clearable="clearable"
        :multiple="multiple"
        :name="name"
        :error-messages="errorMessages"
        :placeholder="placeholder"
        :filter="filtrar"
        @input="input"
        @keyup.enter="enter"
      >
        <template :slot="slotPrepend ? 'prepend' : ''">
          <div @click="clickPrepend">
            <component class="ma-0" :is="slotPrepend" v-model="value"></component>
          </div>
        </template>
        <template :slot="slotAppend ? 'append' : ''">
          <div @click="clickAppend">
            <component class="ma-0" :is="slotAppend" v-model="value"></component>
          </div>
        </template>
        <template :slot="slotAppendOuter ? 'append-outer' : ''">
          <div @click="clickAppendOuter">
            <component class="ma-0" :is="slotAppendOuter" v-model="value"></component>
          </div>
        </template>
        <template :slot="slotNoData ? 'no-data' : ''">
          <component :is="slotNoData" v-model="value"></component>
        </template>
        <template :slot="slotSelection ? 'selection' : ''" slot-scope="data">
          <component :is="slotSelection" v-model="data.item"></component>
        </template>
        <template :slot="slotData ? 'item' : ''" slot-scope="data">
          <component :is="slotData" v-model="data.item"></component>
        </template>
      </v-autocomplete>
      <v-tooltip top v-if="infoComponent ? infoComponent.permisos.agregar && modelCreate : false">
        <v-btn
          slot="activator"
          flat
          icon
          :disabled="disabled"
          @click="$store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: infoComponent.titulo_registro, parametros: {entidad: null, key: key, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
        >
          <v-icon color="accent">add</v-icon>
        </v-btn>
        <span>Crear {{!disabled ? '' : ' >> Deshabilitado'}}</span>
      </v-tooltip>
    </v-layout>
  </v-flex>
</template>

<script>
  export default {
    name: 'PostuladorV3',
    inject: ['$validator'],
    $_veeValidate: {
      name () {
        return this.name
      },
      value () {
        return this.value
      }
    },
    props: {
      slotPrepend: {
        default: null
      },
      slotAppend: {
        default: null
      },
      slotAppendOuter: {
        default: null
      },
      slotNoData: {
        default: null
      },
      slotSelection: {
        default: null
      },
      slotData: {
        default: null
      },
      prependIcon: {
        type: String,
        default: null
      },
      label: {
        type: String,
        default: null
      },
      itemText: {
        type: String,
        default: null
      },
      hint: {
        type: String,
        default: null
      },
      noDataText: {
        type: String,
        default: 'No hay registros para mostrar'
      },
      value: {
        type: Object,
        default: null
      },
      disabled: {
        type: Boolean,
        default: false
      },
      readonly: {
        type: Boolean,
        default: false
      },
      clearable: {
        type: Boolean,
        default: false
      },
      multiple: {
        type: Boolean,
        default: false
      },
      name: {
        type: String,
        default: null
      },
      placeholder: {
        type: String,
        default: null
      },
      errorMessages: {
        type: [String, Array],
        default: null
      },
      route: {
        type: String,
        default: null
      },
      filter: {
        type: String,
        default: null
      },
      model: {
        type: String,
        default: null
      },
      modelState: {
        type: String,
        default: null
      },
      modelCreate: {
        type: Boolean,
        default: false
      }
    },
    data: () => ({
      items: [],
      loading: false,
      search: '',
      textSearch: '',
      key: 'entity.' + Math.random().toString(36).substring(2)
    }),
    watch: {
      'search' (val) {
      },
      'value': {
        immediate: true,
        handler (val) {
          if (val) {
            !this.items.find(x => x.id === val.id) && this.items.push(val)
            this.$emit('input', val)
          } else {
            this.$emit('input', null)
          }
        }
      },
      'item' (val) {
        if (val.key === this.key && val.item && val.item.id) {
          if (this.items.findIndex(x => x.id === val.item.id) > -1) {
            this.items.splice(this.items.findIndex(x => x.id === val.item.id), 1, val.item)
          } else {
            this.items.splice(0, 0, val.item)
          }
          this.$emit('input', val.item)
        }
      }
    },
    computed: {
      infoComponent () {
        return this.model ? this.$store.getters.getInfoComponent(this.model) : null
      },
      item () {
        return this.model && this.modelState ? this.$store.state.tables[this.modelState] : null
      }
    },
    created () {
      this.textSearch = this.noDataText
    },
    methods: {
      clickAppend () {
        this.$emit('click:append', this.value)
      },
      clickAppendOuter () {
        this.$emit('click:append-outer', this.value)
      },
      clickPrepend () {
        this.$emit('click:prepend', this.value)
      },
      enter (event) {
        this.loading = true
        this.textSearch = `Buscando registros...`
        let stringRouteSearch = 'search=%25' + (event.target.value === null ? '' : event.target.value) + '%25'
        let stringRoute = this.route + (this.route.includes('?') ? '&' : '?') + stringRouteSearch
        this.axios.get(stringRoute)
          .then((response) => {
            console.log('response', response)
            if (response.data.data.length) {
              this.items = response.data.data
              this.textSearch = this.noDataText
            } else {
              this.textSearch = `Lo sentimos... no hay registros para mostrar`
            }
            this.loading = false
          })
          .catch(e => {
            this.textSearch = `Error al buscar los registros.`
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al hacer la busqueda de registros' + (this.model ? ` en ${this.model}` : '') + '. ', error: e})
          })
      },
      input (value) {
        this.$emit('input', !value ? null : value)
      },
      filtrar (item, queryText) {
        let array = this.filter ? this.filter.split(',') : this.itemText ? [this.itemText] : []
        array.forEach((item, key) => { array[key] = 'item.' + item })
        const hasValue = val => val != null ? val : ''
        // eslint-disable-next-line no-eval
        const text = hasValue(eval(array.join(' + " " + ')))
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      convertidor (item, text) {
        var arrTitle = text.split('.')
        while (arrTitle.length) {
          item = item[arrTitle.shift()]
        }
        return item
      }
    }
  }
</script>

<style scoped>

</style>
