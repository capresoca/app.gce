<template>
  <div>
    <loading v-if="value.visibleLoading" v-model="value.loading" />
    <v-flex class="ma-0 pa-0 mb-1" id="flex-create-rol" v-if="filters">
      <v-expansion-panel key="panel1" v-model="activeAdvance" expand readonly>
        <v-expansion-panel-content
          hide-actions
        >
          <v-flex xs12 slot="header">
            <v-container
              fluid
              grid-list-xl
              class="pa-0 px-2"
            >
              <v-layout row wrap>
                <v-flex xs5 sm3 md6 class="text-xs-right">
                    <v-layout align-center justify-end row fill-height class="mt-0 px-3 py-2">
                    <slot name="actions"></slot>
                    <v-tooltip top>
                      <v-btn
                        slot="activator"
                        flat
                        icon
                        large
                        color="primary"
                        @click="reloadPage"
                      >
                        <v-icon large>cached</v-icon>
                      </v-btn>
                      <span>Actualizar registros</span>
                    </v-tooltip>
                  </v-layout>
                </v-flex>
<!--                xs7 sm3 md2-->
                <v-flex xs5 sm2 md1>
                  <v-select
                    label="Por página"
                    v-model="value.pagination.per_page"
                    :items="value.optionsPerPage"
                    item-text="text"
                    item-value="value"
                    @change="reloadCurrentPage"
                  />
                </v-flex>
<!--                xs12 sm6 md4-->
                <v-flex xs12 sm7 md5>
                  <v-text-field
                    v-model="value.search"
                    prepend-icon="search"
                    label="Buscar"
                    clearable
                  >
                    <v-tooltip
                      v-if="value.filters"
                      slot="append-outer"
                      left
                    >
                      <v-icon slot="activator" @click="activeAdvance = [true]" v-if="!activeAdvance[0]">zoom_in</v-icon>
                      <v-icon slot="activator" color="primary" @click="activeAdvance = [false]" v-else>zoom_out</v-icon>
                      {{!activeAdvance[0] ? 'Mostrar busqueda avanzada' : 'Ocultar busqueda avanzada' }}
                    </v-tooltip>
                  </v-text-field>
                </v-flex>
              </v-layout>
            </v-container>
          </v-flex>
          <v-divider/>
          <v-flex xs12 v-if="value.filters">
            <v-layout row wrap>
              <v-flex xs12 class="pa-0 mb-2">
                <v-subheader class="grey lighten-3">
                  <v-icon>loupe</v-icon>
                  Busqueda avanzada
                </v-subheader>
              </v-flex>
              <v-flex xs12>
                <v-container
                  fluid
                  grid-list-sm
                  class="py-0 px-3"
                >
                  <v-layout row wrap>
                    <template v-for="(filter, index) in value.filters">
                      <v-flex class="pa-2" xs12 sm6 md3 v-if="filter.type === 'v-range' && filter.items.type === 'number'">
                        <v-layout align-center>
                          <p class="caption mb-0">{{filter.label}}</p>
                        </v-layout>
                        <v-layout align-center>
                          <v-text-field
                            type="number"
                            v-model.number="filter.items.itemMin.vModel"
                            :label="filter.items.itemMin.label"
                            @input="filterReloadPage"
                            clearable
                          />
                          <v-text-field
                            type="number"
                            v-model.number="filter.items.itemMax.vModel"
                            :label="filter.items.itemMax.label"
                            @input="filterReloadPage"
                            clearable
                          />
                        </v-layout>
                      </v-flex>
                      <v-flex class="pa-2" xs12 sm6 md3 v-if="filter.type === 'v-range' && filter.items.type === 'date'">
                        <v-layout align-center>
                          <p class="caption mb-0">{{filter.label}}</p>
                        </v-layout>
                        <v-layout align-center>
                          <v-flex xs6 class="pa-0 ma-0">
                            <v-menu
                              class="mt-0"
                              :key="'fechaMenu' + index"
                              :ref="filter.items.itemMin.label + index"
                              :close-on-content-click="false"
                              v-model="filter.items.itemMin.menuDate"
                              :nudge-right="40"
                              :return-value.sync="filter.items.itemMin.vModel"
                              lazy
                              transition="scale-transition"
                              offset-y
                              full-width
                              min-width="290px"
                            >
                              <v-text-field
                                :key="'fechaText' + index"
                                slot="activator"
                                v-model="filter.items.itemMin.vModel"
                                :label="filter.items.itemMin.label"
                                clearable
                                @input="filterReloadPage"
                                readonly
                              />
                              <v-date-picker
                                locale="es-co"
                                :key="'fechaPicker' + index"
                                v-model="filter.items.itemMin.vModel"
                                @input="$refs[filter.items.itemMin.label + index][0].save(filter.items.itemMin.vModel)"
                                @change="filterReloadPage"
                              />
                            </v-menu>
                          </v-flex>
                          <v-flex xs6 class="pa-0 ma-0">
                            <v-menu
                              class="mt-0"
                              :key="'fechaMenuMax' + index"
                              :ref="filter.items.itemMax.label + index"
                              :close-on-content-click="false"
                              v-model="filter.items.itemMax.menuDate"
                              :nudge-right="40"
                              :return-value.sync="filter.items.itemMax.vModel"
                              lazy
                              transition="scale-transition"
                              offset-y
                              full-width
                              min-width="290px"
                            >
                              <v-text-field
                                :key="'fechaTextMax' + index"
                                slot="activator"
                                v-model="filter.items.itemMax.vModel"
                                :label="filter.items.itemMax.label"
                                clearable
                                @input="filterReloadPage"
                                readonly
                              />
                              <v-date-picker
                                locale="es-co"
                                :key="'fechaPickerMax' + index"
                                v-model="filter.items.itemMax.vModel"
                                @input="$refs[filter.items.itemMax.label + index][0].save(filter.items.itemMax.vModel)"
                                @change="filterReloadPage"
                              />
                            </v-menu>
                          </v-flex>
                        </v-layout>
                      </v-flex>
                      <v-flex class="pa-2" xs12 sm6 md3 v-if="filter.type === 'v-select' || filter.type === 'v-autocomplete'">
                        <v-layout align-center>
                          <p class="caption mb-0">&nbsp;</p>
                        </v-layout>
                        <v-layout align-center>
                          <component
                            :is="filter.type"
                            :key="'filter' + index"
                            :label="filter.label"
                            :multiple="filter.multiple"
                            v-model="filter.vModel"
                            :item-text="filter.itemText"
                            :item-value="filter.itemValue"
                            :items="filter.items()"
                            return-object
                            @change="filterReloadPage"
                            :clearable="filter.clearable"
                          >
                            <template
                              slot="selection"
                              slot-scope="{ item, index }"
                            >
                              <v-chip small v-if="index === 0">
                                <span>{{ item[filter.itemText] }}</span>
                              </v-chip>
                              <span
                                v-if="index === 1"
                                class="grey--text caption"
                              >(+{{filter.vModel.length - 1 }} más)</span>
                            </template>
                          </component>
                        </v-layout>
                      </v-flex>
                    </template>
                  </v-layout>
                </v-container>
              </v-flex>
              <v-flex xs12>
                <v-container
                  fluid
                  grid-list-sm
                  class="py-0 px-3"
                >
                  <v-layout row wrap>
                    <v-flex class="pa-2" xs12>
                      <v-layout aling-center>
                        <v-select
                          label="Columnas"
                          multiple
                          v-model="value.headers"
                          :items="value.makeHeaders.filter(x => !x.selectable)"
                          item-text="text"
                          item-value="id"
                          return-object
                          chips
                          deletable-chips
                          small-chips
                          hide-selected
                        />
                      </v-layout>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-flex>
            </v-layout>
          </v-flex>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-flex>
    <v-data-table
      v-model="selecteds"
      :headers="value.headersTable"
      :items="value.items"
      :search="value.search"
      hide-actions
      :pagination.sync="value.pagination"
      :total-items="value.items.length"
      :loading="value.visibleLoading && value.loading"
      class="elevation-0 pa-0 ma-0"
      :select-all="selectAll"
    >
      <template slot="headerCell" slot-scope="props">
        <v-tooltip top :disabled="!props.header.tooltip">
        <span slot="activator" :class="props.header.tooltip ? 'subrayado-dot cursor-pointer' : ''">
          {{ props.header.text }}
        </span>
          <span>
          {{ props.header.tooltip }}
        </span>
        </v-tooltip>
      </template>
      <template slot="items" slot-scope="props" >
<!--        <tr @click="value.expand ? (props.expanded = !props.expanded) : ''" :class="value.expand ? 'cursor-pointer' : ''">-->
        <tr>
          <template v-if="value.expand">
            <td
              width="10px"
              class="text-xs-center"
            >
              <v-btn flat icon :color="props.expanded ? 'grey' : 'primary'" @click.stop="props.expanded = !props.expanded">
                <v-icon>fas fa-{{props.expanded ? 'angle-up' : 'angle-down'}}</v-icon>
              </v-btn>
            </td>
          </template>
          <td
            v-for="(datHeader, index) in value.makeHeaders"
            v-if="value.headers.find(x => x.id === (index))"
            :key="'registro' + index"
            :class="datHeader.classData"
            :width="datHeader.width"
          >
            <template v-if="datHeader.component">
              <component
                :is="datHeader.component.component"
                v-model="props.item"
                @click="$emit(datHeader.component.event, props.item)"
                @input="$emit(datHeader.component.event, props.item)"
                @change="$emit(datHeader.component.event, props.item)"
                @focus="$emit(datHeader.component.event, props.item)"
                @blur="$emit(datHeader.component.event, props.item)"
              />
            </template>
            <template v-else-if="datHeader.actions">
              <template v-if="datHeader.singlesActions">
                <v-tooltip v-for="(option, i) in props.item.options" :key="'option' + index + i" top>
                  <v-btn
                    class="ma-0"
                    flat
                    icon
                    :small="option.size === 'small'"
                    :large="option.size === 'large'"
                    :color="option.color ? option.color : 'accent'"
                    slot="activator"
                    @click.stop="$emit(option.event, props.item)"
                  >
                    <v-icon
                      :color="option.color ? option.color : 'accent'"
                      :size="option.size ? option.size : ''"
                    >
                      {{option.icon}}
                    </v-icon>
                  </v-btn>
                  <span>{{option.tooltip}}</span>
                </v-tooltip>
              </template>
              <template v-else>
                <v-tooltip
                  left
                  :disabled="props.item.options && props.item.options.length > 0"
                >
                  <v-speed-dial
                    slot="activator"
                    v-model="props.item.show"
                    direction="left"
                    open-on-hover
                    transition="slide-x-transition"
                    style="z-index: 2 !important;"
                  >
                    <v-btn
                      v-model="props.item.show"
                      class="mx-0"
                      :color="!props.item.options || !props.item.options.length ? 'grey lighten-1 elevation-0' : 'blue darken-2'"
                      dark
                      fab
                      slot="activator"
                      small
                    >
                      <template v-if="props.item.options && props.item.options.length > 0">
                        <v-icon>chevron_left</v-icon>
                        <v-icon>close</v-icon>
                      </template>
                      <v-icon v-else>close</v-icon>
                    </v-btn>

                    <v-tooltip v-for="(option, i) in props.item.options" :key="'option' + index + i" top>
                      <v-btn
                        class="mx-0 mr-1"
                        color="white"
                        fab
                        slot="activator"
                        small
                        @click.stop="$emit(option.event, props.item)"
                      >
                        <v-icon
                          :color="option.color ? option.color : 'accent'"
                          :small="option.size === 'small'"
                          :size="!option.size ? '20px' : ''"
                          :medium="option.size === 'medium'"
                          :large="option.size === 'large'"
                        >
                          {{option.icon}}
                        </v-icon>
                      </v-btn>
                      <span>{{option.tooltip}}</span>
                    </v-tooltip>
                  </v-speed-dial>
                  <span>Sin opciones</span>
                </v-tooltip>
              </template>
            </template>
            <template v-else-if="datHeader.selectable">
              <v-checkbox
                :input-value="props.selected"
                :indeterminate="props.indeterminate"
                primary
                hide-details
                @change="props.selected = !props.selected"
              ></v-checkbox>
            </template>
            <div v-else v-html="datHeader.value ? getProperty(props.item, datHeader.value.split('.')) : ''"></div>
          </td>
        </tr>
      </template>
      <template v-if="value.expand" slot="expand" slot-scope="props">
        <component
          v-if="value.expand.component"
          :is="value.expand.component"
          v-model="props.item"
        />
      </template>
      <template slot="no-data">
        <v-alert  v-if="!value.loading" :value="true" color="error" icon="warning">
          Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
        </v-alert>
        <v-alert v-else :value="true" color="info" icon="info">
          Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
        </v-alert>
      </template>
      <template slot="footer">
        <td colspan="100%" class="text-xs-center">
          <v-layout align-center justify-space-between row fill-height v-if="!value.simplePaginate">
            <v-flex class="title grey--text text--darken-1 text-xs-center caption py-2">
              registros del {{ value.pagination.from }} al {{ value.pagination.to }} de {{ value.pagination.total }} totales
            </v-flex>
          </v-layout>
          <v-layout align-center justify-space-between row fill-height v-if="value.simplePaginate">
            <v-flex class="text-xs-left">
              <strong>Página actual: {{value.pagination.current_page}}</strong>
            </v-flex>
            <v-flex>
              <v-tooltip top :disabled="!value.pagination.prev">
                <button
                  slot="activator"
                  type="button"
                  class="v-pagination__navigation theme--light"
                  :class="!value.pagination.prev ? 'v-pagination__navigation--disabled' : ''"
                  style="position: static;"
                  @click="() => {
                        value.pagination.current_page--
                        reloadPage()
                        }"
                >
                  <div class="v-btn__content">
                    <i aria-hidden="true" class="v-icon material-icons theme--light">
                      keyboard_arrow_left
                    </i>
                  </div>
                </button>
                <span>Anterior</span>
              </v-tooltip>
              <v-tooltip top :disabled="!value.pagination.next">
                <button
                  slot="activator"
                  type="button"
                  class="v-pagination__navigation theme--light"
                  :class="!value.pagination.next ? 'v-pagination__navigation--disabled' : ''"
                  style="position: static;"
                  @click="() => {
                        value.pagination.current_page++
                        reloadPage()
                        }"
                >
                  <div class="v-btn__content">
                    <i aria-hidden="true" class="v-icon material-icons theme--light">
                      keyboard_arrow_right
                    </i>
                  </div>
                </button>
                <span>Siguiente</span>
              </v-tooltip>
            </v-flex>
            <v-flex>
            </v-flex>
          </v-layout>
          <v-pagination v-else v-model="value.pagination.current_page" :total-visible="7" :length="value.pagination.last_page" @input="reloadPage"></v-pagination>
        </td>
      </template>
    </v-data-table>
  </div>
</template>
<script>
  import lodash from 'lodash'
  import Loading from '@/components/general/Loading'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  window.lodash = lodash
  export default {
    name: 'DataTable',
    props: {
      selectAll: {
        type: [Boolean, String],
        default: false
      },
      value: Object,
      filters: {
        type: Boolean,
        default: true
      }
    },
    data () {
      return {
        selecteds: [],
        activePetition: true,
        activeAdvance: [false]
      }
    },
    components: {
      Loading
    },
    computed: {
      stateItem () {
        if (this.value.nameItemState) return this.value.nameItemState
      },
      item () {
        return JSON.parse(JSON.stringify(this.stateItem ? this.$store.state.tables[this.stateItem] : {}))
      }
    },
    watch: {
      'value.route' (val, prev) {
        val && prev && this.reloadPage()
      },
      'selecteds' (val) {
        this.$emit('selecteds', val)
      },
      'item' (val) {
        if (val && val.item) {
          this.$emit('resetOption', val.item)
          if (val.estado === 'crear') {
            this.value.items.unshift(val.item)
          } else if (val.estado === 'editar') {
            this.value.items.splice(this.value.items.findIndex(x => x.id === val.item.id), 1, val.item)
          } else if (val.estado === 'reload') {
            this.reloadPage()
          } else if (val.estado === 'eliminar') {
            // RJPT para eliminar
            this.value.items.splice(this.value.items.findIndex(x => x.id === val.item.id), 1, val.item)
          }
        }
      },
      'value.headers' (item) {
        this.value.headersTable = JSON.parse(JSON.stringify(this.selectAll ? item.filter(x => !x.selectable) : item)).sort((a, b) => {
          if (a.id > b.id) {
            return 1
          }
          if (a.id < b.id) {
            return -1
          }
          return 0
        })
        if (this.value.expand) {
          this.value.headersTable.splice(0, 0, {
            text: '',
            align: 'center',
            sortable: false,
            value: ''
          })
        }
      },
      'value.pagination': {
        handler: window.lodash.debounce(function () {
          this.value.pagination.current_page = 1
          this.reloadPage()
        }, 500)
      }
    },
    created () {
      this.$set(this.value, 'items', [])
      this.$set(this.value, 'loading', true)
      this.$set(this.value, 'search', '')
      this.$set(this.value, 'headers', [])
      this.$set(this.value, 'headersTable', [])
      if (!this.value.visibleLoading) this.$set(this.value, 'visibleLoading', true)
      if (!this.value.simplePaginate) this.$set(this.value, 'simplePaginate', false)
      if (!this.value.optionsPerPage) {
        this.$set(this.value, 'optionsPerPage', [
          {
            text: 15,
            value: 15
          },
          {
            text: 50,
            value: 50
          },
          {
            text: 100,
            value: 100
          }
        ])
      }
      this.$set(this.value, 'pagination', {
        current_page: 1,
        from: 1,
        last_page: 0,
        per_page: this.value.optionsPerPage[0].value,
        to: 15,
        total: 0,
        next: null,
        prev: null,
        descending: null,
        sortBy: null
      })
      this.reloadPage()
    },
    methods: {
      deselectAll () {
        this.selecteds = []
      },
      getProperty: (item, arr) => window.toProperty(item, arr),
      reloadHeaders () {
        this.value.makeHeaders.forEach((item, index) => {
          item.id = index
        })
        this.value.headers = JSON.parse(JSON.stringify(this.value.makeHeaders))
      },
      reloadCurrentPage: window.lodash.debounce(function () {
        this.value.pagination.current_page = 1
        this.reloadPage()
      }, 500),
      stringFilters () {
        let stringFilters = ''
        this.value.filters && this.value.filters.forEach(filtro => {
          let copiaModel = []
          if (filtro.type === 'v-select' || filtro.type === 'v-autocomplete') {
            JSON.parse(JSON.stringify(filtro.vModel)).forEach(item => {
              copiaModel.push(item[filtro.itemValue])
            })
            stringFilters = stringFilters + (copiaModel.length ? '&' + filtro.value + '=' + (copiaModel.join(',')) : '')
          } else if (filtro.type === 'v-range') {
            if (filtro.items.range) {
              if (filtro.items.itemMin.vModel && !filtro.items.itemMax.vModel) {
                stringFilters = stringFilters + ('&' + (filtro.items.itemMin.value + '=(ge)' + filtro.items.itemMin.vModel))
              }
              if (filtro.items.itemMin.vModel && filtro.items.itemMax.vModel) {
                copiaModel.push(filtro.items.itemMin.value + '[]=(ge)' + filtro.items.itemMin.vModel)
                copiaModel.push(filtro.items.itemMax.value + '[]=(le)' + filtro.items.itemMax.vModel)
                stringFilters = stringFilters + (copiaModel.length ? '&' + (copiaModel.join('&')) : '')
              }
              if (!filtro.items.itemMin.vModel && filtro.items.itemMax.vModel) {
                stringFilters = stringFilters + ('&' + (filtro.items.itemMax.value + '=(le)' + filtro.items.itemMax.vModel))
              }
            }
          }
        })
        return stringFilters
      },
      filterReloadPage: window.lodash.debounce(function () {
        if (this.value.pagination.current_page === 1) {
          this.reloadPage()
        } else {
          this.value.pagination.current_page = 1
        }
      }, 500),
      async reloadPage () {
        if (this.activePetition) {
          this.value.loading = true
          this.activePetition = false
          await this.reloadHeaders()
          let stringFilters = await this.stringFilters()
          let stringSort = this.value.pagination.sortBy ? ('&sort=' + this.value.pagination.sortBy + ',' + (this.value.pagination.descending ? 'desc' : 'asc')) : ''
          this.axios.get(this.value.route + (this.value.route.indexOf('?') > -1 ? '&' : '?') + 'per_page=' + this.value.pagination.per_page + stringFilters + stringSort + '&page=' + this.value.pagination.current_page + '&search=%25' + ((this.value.search === null || typeof this.value.search === 'undefined') ? '' : this.value.search) + '%25')
            .then((response) => {
              response.data.data.forEach(item => {
                this.$emit('resetOption', item)
              })
              response.data.meta.per_page = this.value.optionsPerPage.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
              this.value.pagination.last_page = response.data.meta.last_page
              this.value.pagination.from = response.data.meta.from
              this.value.pagination.to = response.data.meta.to
              this.value.pagination.total = response.data.meta.total
              this.value.pagination.next = response.data.links.next
              this.value.pagination.prev = response.data.links.prev
              this.value.items = response.data.data
              setTimeout(() => {
                this.value.loading = false
                this.activePetition = true
              }, 300)
            })
            .catch(e => {
              this.value.loading = false
              this.activePetition = true
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al hacer la busqueda de registros. ', error: e})
            })
        }
      }
    }
  }
</script>

<style>
  #flex-create-rol div.v-expansion-panel__header{
    cursor: default !important;
    padding: 0 !important;
  }
</style>
