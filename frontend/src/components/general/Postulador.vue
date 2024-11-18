<template>
  <v-flex class="px-0">
    <v-layout align-center class="pl-1 pr-3">
      <v-icon :v-if="!noIcon" class="mx-2" v-text="preicon"></v-icon>
      <v-autocomplete
        :label="noNull ? titleLabel : 'Buscar ' + titleLabel"
        v-model="entity.item"
        :items="entity.items"
        :item-text="itemtext"
        :hint="theHint"
        persistent-hint
        :name="titleLabel"
        :required="isRequired ? false : true"
        v-validate="isRequired ? '' : 'required'"
        :error-messages="errors.collect(titleLabel)"
        :loading="entity.loading"
        :search-input.sync="entity.search"
        :filter="entity.filter"
        :no-data-text="nodata"
        return-object
        :disabled="noDisabled ? false : true"
        :clearable="noClearable ? false : true"
        :multiple="noMultiple ? false : true"
        hide-selected
        cache-items
        @input="input"
      >
        <template :slot="!noListTitle || !noListSubTitle && entity.items ? 'item' : ''" slot-scope="data">
          <template v-if="entity.items">
            <v-list-tile-content>
              <v-list-tile-title v-if="!noListTitle" v-html="convertidor(data.item, datatitle)"/>
              <v-list-tile-sub-title v-if="!noListSubTitle" v-html="convertidor(data.item, datasubtitle)"/>
            </v-list-tile-content>
          </template>
        </template>
      </v-autocomplete>
      <v-tooltip top v-if="infoComponent ? infoComponent.permisos.agregar && noLite : false">
        <v-btn
          slot="activator"
          flat
          icon
          :disabled="noDisabled ? false : true"
          @click="showBtnplusTruncate ? $emit('click') : $store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: infoComponent.titulo_registro, parametros: {entidad: null, key: entity.key, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
        >
          <v-icon color="accent">add</v-icon>
        </v-btn>
        <span>Crear {{titleLabel}} {{noDisabled ? '' : ' >> Deshabilitado'}}</span>
      </v-tooltip>
      <v-tooltip top v-if="!noDetail">
        <v-btn
          slot="activator"
          flat
          v-if="noNull"
          icon
          :disabled="noDisabled ? false : true"
          @click.native="entity.showContent = !entity.showContent"
        >
          <v-icon>{{ !entity.showContent ? 'keyboard_arrow_down' : 'keyboard_arrow_up' }}</v-icon>
        </v-btn>
        <span>{{entity.showContent ? 'Ocultar' : 'Mostrar'}} detalle {{noDisabled ? '' : ' >> Deshabilitado'}}</span>
      </v-tooltip>
    </v-layout>
    <v-slide-y-transition>
      <v-flex class="px-0 py-0 mx-0 my-0" v-if="noNull && !noDetail" v-show="entity.showContent">
        <v-toolbar dense class="mt-1">
          <v-toolbar-title>Información {{titleLabel}}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-tooltip top>
            <v-btn
              slot="activator"
              flat
              icon
              @click="$store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: infoComponent.titulo_registro, parametros: {entidad: entity.item, key: entity.key, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
            >
              <v-icon>mode_edit</v-icon>
            </v-btn>
            <span>Actualizar información</span>
          </v-tooltip>
        </v-toolbar>
        <component v-if="detail" class="elevation-1" :is="detail" :item="entity.item"/>
      </v-flex>
    </v-slide-y-transition>
  </v-flex>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'Postulador',
    props: [
      'clearable',
      'datasubtitle',
      'datatitle',
      'detail',
      'disabled',
      'entidad',
      'filter',
      'hint',
      'itemtext',
      'label',
      'lite',
      'multiple',
      'nodata',
      'object',
      'routeparams',
      'preicon',
      'required',
      'scope',
      'btnplustruncate',
      'value'
    ],
    data () {
      return {
        change: false,
        theHint: null,
        entity: {
          showContent: false,
          item: null,
          items: [],
          loading: false,
          search: null,
          key: 'entity.' + Math.random().toString(36).substring(2),
          filter: this.filtrar
        }
      }
    },
    watch: {
      'noDisabled' (val) {
        !val && this.$validator.reset()
      },
      'value' (val) {
        if (val !== this.entity.item) this.assign(val)
      },
      'entity.item' (val) {
        if (typeof val === 'undefined') val = null
        this.theHint = val && this.noHint ? null : (val && val.id !== null) ? this.convertidor(val, this.hint) : null
        this.$emit('change', this.typeObject ? (val ? val.id : val) : val)
        this.$emit('objectReturn', val)
      },
      'entity.search' (val) {
        val && val !== '' && val !== null && !this.change && this.buscar()
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
              this.$emit('change', this.typeObject ? item.id : item)
              this.$emit('objectReturn', item)
            })
            .catch(e => {
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el registro en ' + this.entidad + '. ' + e, color: 'danger'})
            })
        }
      }
    },
    computed: {
      showBtnplusTruncate () {
        return typeof this.btnplustruncate !== 'undefined'
      },
      isRequired () {
        return typeof this.required === 'undefined' || this.required === false
      },
      titleLabel () {
        return typeof this.label === 'undefined' ? 'entity' : this.label
      },
      typeObject () {
        return typeof this.object === 'undefined' || this.object === false
      },
      noDisabled () {
        return typeof this.disabled === 'undefined' || this.disabled === false
      },
      noMultiple () {
        return typeof this.multiple === 'undefined' || this.multiple === false
      },
      noClearable () {
        return typeof this.clearable === 'undefined' || this.clearable === false
      },
      noIcon () {
        return typeof this.preicon === 'undefined'
      },
      noHint () {
        return typeof this.hint === 'undefined' || !this.noMultiple
      },
      noLite () {
        return typeof this.lite === 'undefined' || this.lite === false
      },
      noFilter () {
        return typeof this.filter === 'undefined'
      },
      noDetail () {
        return (typeof this.detail === 'undefined' || !this.noLite)
      },
      noNull () {
        return this.entity.item && this.entity.item.id
      },
      noEntidad () {
        return typeof this.entidad === 'undefined' || this.entidad === '' || this.entidad === null
      },
      noRouteParams () {
        return typeof this.routeparams === 'undefined' || this.routeparams === '' || this.routeparams === null
      },
      noListTitle () {
        return typeof this.datatitle === 'undefined' || this.datatitle === '' || this.datatitle === null
      },
      noListSubTitle () {
        return typeof this.datasubtitle === 'undefined' || this.datasubtitle === '' || this.datasubtitle === null
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent(this.entidad)
      },
      item () {
        return !this.noEntidad && this.convertidor(this.$store.state.tables, 'item' + (this.entidad.charAt(0).toUpperCase() + (this.entidad.endsWith('s') ? this.entidad.slice(1, this.entidad.length - 1) : this.entidad.slice(1))))
      }
    },
    mounted () {
      if (this.value !== null) this.assign(this.value)
    },
    methods: {
      input () {
        this.change = true
        setTimeout(() => { this.change = false }, 700)
      },
      convertidor (item, text) {
        var arrTitle = text.split('.')
        while (arrTitle.length) {
          item = item[arrTitle.shift()]
        }
        return item
      },
      filtrar (item, queryText) {
        let array = this.noFilter ? [this.itemtext] : this.filter.split(',')
        array.forEach((item, key) => { array[key] = 'item.' + item })
        const hasValue = val => val != null ? val : ''
        // eslint-disable-next-line no-eval
        const text = hasValue(eval(array.join(' + " " + ')))
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
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
        this.entity.loading = true
        let stringRouteSearch = 'search=%25' + this.entity.search + '%25'
        let stringRoute = this.entidad + '?' + (this.noRouteParams ? stringRouteSearch : this.routeparams + '&' + stringRouteSearch)
        this.axios.get(stringRoute)
          .then((response) => {
            if (response.data.data.length > 0) {
              this.entity.items = response.data.data
            }
            this.entity.loading = false
          })
          .catch(e => {
            this.entity.loading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de registros en ' + this.entidad + '. ' + e, color: 'danger'})
          })
      }, 500),
      validate () {
        return this.$validator.validateAll(this.scope)
      }
    }
  }
</script>

<style scoped>

</style>
