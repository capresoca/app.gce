<template>
  <div>
    <loading v-if="value.visibleLoading" :status="value.loading"></loading>
    <v-container
      v-if="value.filters || value.advanceFilters"
      fluid
      grid-list-md
      class="py-1 px-2"
    >
      <v-layout row wrap align-center justify-end fill-height v-if="value.filters">
        <v-flex xs1 style="height: 66px !important;">
          <v-layout align-center justify-center fill-height>
            <v-tooltip top>
              <v-btn slot="activator" icon large @click="reloadCurrentPage">
                <v-icon color="grey">cached</v-icon>
              </v-btn>
              <span>Recargar Registros</span>
            </v-tooltip>
          </v-layout>
        </v-flex>
        <v-flex xs11 sm6 md5 lg3 style="height: 66px !important;">
          <v-select
            label="Columnas visibles"
            multiple
            v-model="value.headers"
            :items="value.makeHeaders.filter(x => !x.selectable)"
            item-text="text"
            item-value="id"
            return-object
            clearable
          >
            <template
              slot="selection"
              slot-scope="{ item, index }"
            >
              <span
                v-if="index === 0"
                class="grey--text caption"
              >
                  {{`${value.headers.length} ${value.headers.length === 1 ? 'columna ' : 'columnas'} de ${value.makeHeaders.length} disponible${value.makeHeaders.length === 1 ? '' : 's'}`}}
              </span>
            </template>
          </v-select>
        </v-flex>
        <v-flex xs12 sm6 md3 lg2 style="height: 66px !important;">
          <v-select
            label="Registros por página"
            v-model="value.pagination.per_page"
            :items="value.optionsPerPage"
            item-text="text"
            item-value="value"
            @change="reloadCurrentPage"
            hide-details
          ></v-select>
        </v-flex>
        <v-flex xs12 sm12 md6 lg3 style="height: 66px !important;">
          <v-text-field
            v-model="value.search"
            label="Buscar"
            clearable
            hide-details
            prepend-icon="search"
            @keyup.enter="reloadCurrentPage"
          >
          </v-text-field>
        </v-flex>
      </v-layout>
      <v-expansion-panel v-if="value.advanceFilters" focusable>
        <v-expansion-panel-content>
          <template v-slot:actions>
            <v-icon color="primary">$vuetify.icons.expand</v-icon>
          </template>
          <template v-slot:header>
            <v-spacer></v-spacer>
            <span class="text-xs-right mr-2 font-weight-bold"><v-icon :size="20">filter</v-icon> Filtros avanzados</span>
          </template>
          <v-divider></v-divider>
          <v-card>
            <v-container
              fluid
              grid-list-md
              class="py-1 px-2"
            >
              <v-layout row wrap align-center justify-end fill-height>
                <slot name="filters"></slot>
              </v-layout>
            </v-container>
            <v-divider></v-divider>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn small color="primary" @click.stop="$emit('apply-filters')">Aplicar filtros</v-btn>
            </v-card-actions>
          </v-card>
        </v-expansion-panel-content>
      </v-expansion-panel>
      <v-divider></v-divider>
    </v-container>
    <v-data-table
      v-model="selecteds"
      v-show="value.headers.length"
      :headers="value.headersTable"
      :items="value.items"
      :pagination.sync="value.pagination"
      hide-actions
      :total-items="value.items.length"
      :loading="value.visibleLoading && value.loading"
      class="elevation-0 pa-0 ma-0 mt-1"
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
        <tr @click="value.expand ? (props.expanded = !props.expanded) : ''" :class="value.expand ? 'cursor-pointer' : ''">
          <td
            v-for="(datHeader, index) in value.makeHeaders"
            v-if="value.headers.find(x => x.id === (index))"
            :key="'registro' + index"
            :class="datHeader.classData"
            :width="datHeader.width"
          >
            <template v-if="datHeader.component">
              <component
                :is="datHeader.component"
                v-model="props.item"
              ></component>
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
                    :loading="props.item.loading"
                    :disabled="option.disabled"
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
                        :loading="props.item.loading"
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
                v-if="props.item.selectable"
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
        <v-alert :value="value.loading" color="primary" icon="search">
          Cargando registros.
        </v-alert>
        <template v-if="!value.loading">
          <v-alert :value="!filtrado" color="info" icon="info">
            Ejecute los controles para realizar la busqueda de registros.
          </v-alert>
          <v-alert :value="filtrado" color="orange" icon="warning">
            No se han encontrado registros que coincidan con los criterios suministrados.
          </v-alert>
        </template>
      </template>
      <template slot="footer">
        <td colspan="100%" class="text-xs-center">
          <v-layout align-center justify-space-between row v-if="!value.simplePaginate">
            <v-flex class="title grey--text text--darken-1 text-xs-center caption pa-1">
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
    <div v-show="!value.headers.length" class="title grey--text text-xs-center pa-4">No hay columnas seleccionadas para mostrar</div>
  </div>
</template>
<script>
  import lodash from 'lodash'
  window.lodash = lodash
  export default {
    name: 'DataTableV2',
    props: {
      selectAll: {
        type: [Boolean, String],
        default: false
      },
      value: Object
    },
    data: () => ({
      selecteds: [],
      activePetition: false,
      filtrado: false
    }),
    computed: {
      stateItem () {
        if (this.value.nameItemState) return this.value.nameItemState
      },
      item () {
        return JSON.parse(JSON.stringify(this.stateItem ? this.$store.state.dataTable.tables[this.stateItem] : {}))
      },
      version () {
        return JSON.parse(JSON.stringify(this.stateItem ? this.$store.state.dataTable.tables[this.stateItem + 'Version'] : {}))
      }
    },
    watch: {
      'selecteds' (val) {
        this.$emit('selecteds', val)
      },
      'item' (val) {
        val && this.reloadPage()
      },
      'version' (val) {
        val && this.reloadHeaders(true)
      },
      'value.route' (val, prev) {
        if (val && prev) {
          this.reloadCurrentPage()
        }
      },
      'value.headers' (items) {
        this.value.headersTable = JSON.parse(JSON.stringify(this.selectAll ? items.filter(x => !x.selectable) : items)).sort((a, b) => {
          if (a.id > b.id) {
            return 1
          }
          if (a.id < b.id) {
            return -1
          }
          return 0
        })
        if (this.value.nameItemState) {
          localStorage.setItem(this.value.nameItemState, JSON.stringify(items))
        }
      },
      'value.search': window.lodash.debounce(function (val) {
        !val && this.reloadPage()
      }, 800),
      'value.pagination': {
        handler: window.lodash.debounce(function () {
          this.value.pagination.current_page = 1
          this.reloadPage()
        }, 0)
      }
    },
    created () {
      this.$set(this.value, 'items', [])
      this.$set(this.value, 'loading', false)
      this.$set(this.value, 'filters', true)
      if (typeof this.value.advanceFilters === 'undefined') {
        this.$set(this.value, 'advanceFilters', false)
      }
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
          },
          {
            text: 200,
            value: 200
          },
          {
            text: 500,
            value: 500
          },
          {
            text: 1000,
            value: 1000
          },
          {
            text: 'Todos',
            value: 'all'
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
      if (this.stateItem && localStorage.getItem(this.stateItem + 'Version') === null) {
        this.reloadHeaders(true)
      } else if (this.stateItem && localStorage.getItem(this.stateItem + 'Version') && localStorage.getItem(this.stateItem + 'Version') !== this.version.toString()) {
        this.reloadHeaders(true)
      } else {
        this.reloadHeaders()
      }
      localStorage.setItem((this.stateItem + 'Version'), this.version)
      this.reloadPage()
    },
    methods: {
      deselectAll () {
        this.selecteds = []
      },
      getProperty: (item, arr) => window.toProperty(item, arr),
      reloadHeaders (force = false) {
        this.value.makeHeaders.forEach((item, index) => {
          item.id = index
          if (typeof item.visibleColumn === 'undefined') item.visibleColumn = true
        })
        if (this.value.nameItemState) {
          if (force) {
            localStorage.setItem(this.value.nameItemState, JSON.stringify(this.value.makeHeaders.filter(x => x.visibleColumn)))
          } else {
            if (localStorage.getItem(this.value.nameItemState) === null) {
              localStorage.setItem(this.value.nameItemState, JSON.stringify(this.value.makeHeaders.filter(x => x.visibleColumn)))
            }
          }
          this.value.headers = JSON.parse(localStorage.getItem(this.value.nameItemState))
        } else {
          this.value.headers = JSON.parse(JSON.stringify(this.value.makeHeaders.filter(x => x.visibleColumn)))
        }
      },
      reloadCurrentPage () {
        this.value.pagination.current_page = 1
        this.activePetition = true
        this.reloadPage()
      },
      async reloadPage () {
        if (this.activePetition) {
          this.value.loading = true
          this.activePetition = false
          let stringSort = this.value.pagination.sortBy ? (`&sort=${this.value.pagination.sortBy},${this.value.pagination.descending ? 'desc' : 'asc'}`) : ''
          this.axios.get(this.value.route + (this.value.route.indexOf('?') > -1 ? '&' : '?') + 'per_page=' + this.value.pagination.per_page + stringSort + '&page=' + this.value.pagination.current_page + '&search=%25' + ((this.value.search === null || typeof this.value.search === 'undefined') ? '' : this.value.search) + '%25')
            .then((response) => {
              this.filtrado = true
              response.data.data.forEach(item => {
                item.options = []
                item.loading = false
                item.disabled = false
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
              this.$store.commit('snackbar', {
                color: 'error',
                message: `al hacer la busqueda de registros`,
                error: e
              })
            })
        }
      }
    }
  }
</script>

<style>
  #flex-create-rol div.v-expansion-panel__header {
    cursor: default !important;
    padding: 0 !important;
  }

  .cursor-pointer {
    cursor: pointer !important;
  }

  .subrayado-dot {
    border-bottom: 0.107em dotted #9999CC !important;
  }
</style>
