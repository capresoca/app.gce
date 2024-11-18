<template>
  <v-flex>
    <v-layout align-center>
      <v-autocomplete
        ref="autocomplete"
        :prepend-icon="preicon"
        :label="noNull ? titleLabel : 'Buscar ' + titleLabel"
        v-model="entity.item"
        :items="entity.items"
        :item-text="itemText"
        :hint="hintText ? hintText : (minCharactersSearch > 0 && (entity.search === null || (entity.search && entity.search.length < minCharactersSearch)) && !entity.item) ? ('Se requieren al menos ' + minCharactersSearch +' carateres para iniciar la busqueda.') :  theHint"
        persistent-hint
        :loading="entity.loading"
        :search-input.sync="entity.search"
        no-filter
        :no-data-text="noDataCopy"
        return-object
        :disabled="disabled"
        :readonly="readonly"
        :clearable="clearable"
        :multiple="multiple"
        :name="name ? name : 'undefined'"
        v-validate="name && rules ? rules : ''"
        :error-messages="(errorMessages && name) ? (errorMessages.length ? errorMessages : errors.collect(name)) : null"
        @input="input"
        @click="''"
        @keyup.enter="''"
        :placeholder="entity.items.length ? noData : ''"
      >
        <template slot="prepend">
          <component :is="slotPrepend" v-model="entity.item"></component>
        </template>
        <template slot="append" v-if="entity && entity.item && slotAppend">
          <span v-if="!slotAppend.template">{{convertidor(entity.item, slotAppend)}}</span>
          <component v-else :is="slotAppend" v-model="entity.item"></component>
        </template>
        <template slot="no-data">
          <v-list-tile-content>
            <v-list-tile-title class="px-3" v-text="noDataCopy"></v-list-tile-title>
            <v-list-tile-sub-title class="px-3" v-if="(minCharactersSearch > 0 && (entity.search === null || (entity.search && entity.search.length < minCharactersSearch)))" v-text="`Digitar ${minCharactersSearch} caracteres para iniciar la busqueda`"></v-list-tile-sub-title>
          </v-list-tile-content>
        </template>
        <template :slot="slotSelection ? 'selection' : ''" slot-scope="data">
          <template v-if="data.item">
            <component :is="slotSelection" v-model="data.item"/>
          </template>
        </template>
        <template :slot="((dataTitle !== '' || dataSubtitle !== '') || slotData) && entity.items ? 'item' : ''" slot-scope="data">
          <template v-if="entity.items">
            <component v-if="slotData" :is="slotData" v-model="data.item"></component>
            <template v-else>
              <v-list-tile-content>
                <v-list-tile-title v-if="dataTitle !== ''" v-html="convertidor(data.item, dataTitle)"></v-list-tile-title>
                <v-list-tile-sub-title v-if="dataSubtitle !== ''" v-html="convertidor(data.item, dataSubtitle)"></v-list-tile-sub-title>
              </v-list-tile-content>
            </template>
          </template>
        </template>
      </v-autocomplete>
      <v-tooltip top v-if="infoComponent ? infoComponent.permisos.agregar && !noPlus : false">
        <v-btn
          slot="activator"
          flat
          icon
          :disabled="disabled"
          @click="btnCreateTruncate ? $emit('create') : $store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: infoComponent.titulo_registro, parametros: {entidad: (prosDatos ? {prop_dato: prosDatos} : null), key: entity.key, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
        >
          <v-icon color="accent">add</v-icon>
        </v-btn>
        <span>Crear {{titleLabel}} {{!disabled ? '' : ' >> Deshabilitado'}}</span>
      </v-tooltip>
      <v-tooltip top v-if="detail || btnDetailTruncate">
        <v-btn
          slot="activator"
          flat
          v-if="noNull"
          icon
          @click.native="btnDetailTruncate ? $emit('detail', entity.item) : entity.showContent = !entity.showContent"
        >
          <v-icon>{{ btnDetailTruncate ? 'more_horiz' : (entity.showContent ? 'keyboard_arrow_down' : 'keyboard_arrow_up') }}</v-icon>
        </v-btn>
        <span>{{entity.showContent ? 'Ocultar' : 'Mostrar'}} detalle</span>
      </v-tooltip>
    </v-layout>
    <v-expand-transition>
      <v-layout align-center v-if="noNull && detail && !btnDetailTruncate" v-show="entity.showContent">
        <v-flex class="px-0 py-0 mx-0 my-0" >
          <v-toolbar v-if="!noToolbarDetail" dense class="mt-1">
            <v-toolbar-title>Información {{titleLabel}}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-tooltip top v-if="infoComponent ? infoComponent.permisos.agregar && !noEdit : false">
              <v-btn
                slot="activator"
                flat
                icon
                @click="btnEditTruncate ? $emit('edit', entity.item) : $store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: infoComponent.titulo_registro, parametros: {entidad: entity.item, key: entity.key, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
              >
                <v-icon>mode_edit</v-icon>
              </v-btn>
              <span>Actualizar información</span>
            </v-tooltip>
          </v-toolbar>
          <component v-if="detail" class="elevation-1" :is="detail" :item="entity.item"></component>
        </v-flex>
      </v-layout>
    </v-expand-transition>
  </v-flex>
</template>
<script>
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'PostuladorV2',
    props: {
      clearable: {
        type: Boolean,
        default: false
      },
      dataSubtitle: {
        type: String,
        default: ''
      },
      dataTitle: {
        type: String,
        default: ''
      },
      detail: {
        type: Function,
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
      entidad: {
        type: String,
        default: ''
      },
      route: {
        type: String,
        default: ''
      },
      hintText: {
        type: String,
        default: null
      },
      hint: String,
      itemText: String,
      label: String,
      noBtnCreate: Boolean,
      noBtnEdit: Boolean,
      multiple: {
        type: Boolean,
        default: false
      },
      noData: {
        type: String,
        default: ''
      },
      routeParams: {
        type: String,
        default: ''
      },
      preicon: {
        type: String,
        default: ''
      },
      errorMessages: Array,
      btnCreateTruncate: Boolean,
      noToolbarDetail: {
        type: Boolean,
        default: false
      },
      btnEditTruncate: Boolean,
      btnDetailTruncate: Boolean,
      value: Object,
      name: String,
      rules: String,
      slotAppend: {
        default: null
      },
      slotPrepend: {
        default: null
      },
      slotData: {
        default: null
      },
      slotSelection: {
        default: null
      },
      minCharactersSearch: {
        type: Number,
        default: 0
      },
      prosDatos: {
        type: Object,
        default: null
      }
    },
    data () {
      return {
        component: null,
        componentSlotAppend: null,
        noDataCopy: JSON.parse(JSON.stringify(this.noData)),
        noDataCopy2: JSON.parse(JSON.stringify(this.noData)),
        change: false,
        theHint: null,
        entity: {
          showContent: false,
          item: null,
          items: [],
          loading: false,
          search: null,
          key: 'entity.' + Math.random().toString(36).substring(2)
        }
      }
    },
    watch: {
      'disabled' (val) {
        val && this.$validator.reset()
      },
      'value' (val) {
        if ((val && val.id) || val === null) this.assign(val)
        // if (val && ((this.entity.item && this.entity.item.id) ? (val.id !== this.entity.item.id) : (val !== this.entity.item))) this.assign(val)
      },
      'entity.item' (val) {
        if (typeof val === 'undefined') val = null
        this.theHint = val && this.noHint ? null : (val && val.id !== null) ? this.convertidor(val, this.hint) : null
        this.$emit('changeid', val === null ? null : val.id)
        this.$emit('input', val)
        // if (typeof val === 'undefined') val = null
        // if (val !== null) {
        //   this.axios.get(this.entidad + '/' + val.id)
        //     .then((response) => {
        //       let item = response.data.data
        //       this.theHint = item && this.noHint ? null : (item && item.id !== null) ? this.convertidor(item, this.hint) : null
        //       this.$emit('changeid', item === null ? null : item.id)
        //       this.$emit('input', item)
        //     })
        //     .catch(e => {
        //       this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el registros en ' + this.entidad + '. ', error: e})
        //     })
        // } else {
        //   this.theHint = val && this.noHint ? null : (val && val.id !== null) ? this.convertidor(val, this.hint) : null
        //   this.$emit('changeid', val === null ? null : val.id)
        //   this.$emit('input', val)
        // }
      },
      'entity.search' (val) {
        if (val === '') {
          this.noDataCopy = JSON.parse(JSON.stringify(this.noDataCopy2))
        }
        val && val !== '' && val !== null && (val.length >= this.minCharactersSearch) && !this.change && this.buscar()
      },
      'item' (value) {
        if (value.key === this.entity.key) {
          this.axios.get(this.entidad + '/' + value.item.id)
            .then((response) => {
              let item = response.data.data
              if (this.entity.items.findIndex(x => x.id === item.id) > -1) {
                this.entity.items.splice(this.entity.items.findIndex(x => x.id === item.id), 1, item)
              } else {
                this.entity.items.splice(0, 0, item)
              }
              this.$validator.errors.items.splice(this.$validator.errors.items.findIndex(x => x.field === this.titleLabel), 1)
              this.$emit('changeid', item === null ? null : item.id)
              this.$emit('input', item)
            })
            .catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el registros en ' + this.entidad + '. ', error: e})
            })
        }
      }
    },
    computed: {
      titleLabel () {
        return typeof this.label === 'undefined' ? 'entity' : this.label
      },
      noHint () {
        return typeof this.hint === 'undefined' || this.multiple
      },
      noPlus () {
        return this.noBtnCreate === true
      },
      noEdit () {
        return this.noBtnEdit === true
      },
      noNull () {
        return this.entity.item && this.entity.item.id
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent(this.entidad)
      },
      item () {
        return this.entidad !== '' && this.convertidor(this.$store.state.tables, 'item' + (this.entidad.charAt(0).toUpperCase() + (this.entidad.endsWith('s') ? this.entidad.slice(1, this.entidad.length - 1) : this.entidad.slice(1))))
      }
    },
    mounted () {
      if (this.value !== null) this.assign(this.value)
    },
    methods: {
      focus () {
        this.$refs.autocomplete.focus()
      },
      input () {
        this.change = true
        setTimeout(() => {
          this.change = false
          this.noDataCopy = JSON.parse(JSON.stringify(this.noData))
        }, 700)
      },
      convertidor (item, text) {
        var arrTitle = text.split('.')
        while (arrTitle.length) {
          item = item[arrTitle.shift()]
        }
        return item
      },
      reset () {
        this.$validator.reset()
        this.entity.showContent = false
        this.entity.items = []
        this.entity.search = null
        this.entity.item = null
      },
      assign (item) {
        this.entity.item = item
        this.$validator.reset()
        if (item && item.id !== null) this.entity.items.push(item)
      },
      buscar: window.lodash.debounce(function () {
        this.noDataCopy = 'Buscando registros... por favor espere un momento.'
        this.entity.loading = true
        let stringRouteSearch = 'search=%25' + (this.entity.search === null ? '' : this.entity.search) + '%25'
        let stringRoute = (this.route ? this.route : this.entidad) + '?' + (this.routeParams === '' ? stringRouteSearch : this.routeParams + '&' + stringRouteSearch)
        this.axios.get(stringRoute)
          .then((response) => {
            if (response.data.data.length > 0) {
              this.entity.items = response.data.data
            } else {
              this.noDataCopy = `Lo sentimos... no hay registros para mostrar`
            }
            this.entity.loading = false
          })
          .catch(e => {
            this.entity.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al hacer la busqueda de registros en ' + this.entidad + '. ', error: e})
          })
      }, 500)
    }
  }
</script>

<style scoped>
</style>
