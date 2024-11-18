<template>
  <v-layout row wrap id="flag-top">
    <loading v-model="loading" />
    <v-layout row justify-center>
    </v-layout>
    <v-flex xs12>
      <v-slide-y-transition>
        <v-card v-if="showFormRegistro">
          <v-toolbar dense>
            <v-toolbar-title>
              <v-icon>healing</v-icon>
              Registro de procedimientos
            </v-toolbar-title>
            <v-spacer/>
            <v-btn flat icon :disabled="loading" @click="showFormRegistro = false">
              <v-icon>close</v-icon>
            </v-btn>
          </v-toolbar>
          <v-card-text>
            <v-container class="pa-0" grid-list-md>
              <v-layout row wrap>
                <v-flex xs12 sm4 md4>
                  <v-select
                    label="Tipo Tarifa"
                    v-model="tipoTarifa"
                    :items="tiposTarifa"
                    item-value="id"
                    item-text="text"
                    name="Tipo Tarifa"
                    v-validate="'required'"
                    :error-messages="errors.collect('Tipo Tarifa')"
                  />
                </v-flex>
                <template v-if="tipoTarifa !== 'Institucional'">
                  <v-flex xs12 sm4 md4>
                    <v-select
                      label="Año"
                      v-model="anio"
                      :items="anios"
                      item-value="id"
                      name="Año"
                      v-validate="'required'"
                      :error-messages="errors.collect('Año')"
                    >
                      <template slot="selection" slot-scope="{ item, index }">
                        <span>{{item.anio}}</span>
                        <v-chip label small>
                          <span>SMLV: {{item.valor | numberFormat(0, '$')}}</span>
                        </v-chip>
                      </template>
                      <template slot="item" slot-scope="data">
                        <template>
                          <v-list-tile-content>
                            <v-list-tile-title v-html="data.item.anio"/>
                            <v-list-tile-sub-title >SMLV: {{data.item.valor | numberFormat(0, '$')}}</v-list-tile-sub-title>
                          </v-list-tile-content>
                        </template>
                      </template>
                    </v-select>
                  </v-flex>
                  <v-flex xs12 sm4 md4>
                    <v-text-field
                      label="Porcentaje"
                      name="Porcentaje"
                      v-validate="'required|min_value:-100'"
                      min="-100"
                      type="number"
                      v-model.number="porcentaje"
                      :error-messages="errors.collect('Porcentaje')"
                    />
                  </v-flex>
                  <v-flex xs12>
                    <treeselect
                      :maxHeight="1000"
                      openDirection="bottom"
                      :show-count="true"
                      showCountOf="LEAF_DESCENDANTS"
                      :disabled="!tipoTarifa || !anio || !porcentaje"
                      multiple
                      value-consists-of="LEAF_PRIORITY"
                      v-model="tree"
                      :options="itemsArbol"
                      placeholder="Seleccionar procedimientos"
                      name="ProcedimientosArbol"
                      v-validate="'required'"
                      data-vv-as="Procedimientos"
                    />
                    <span class="caption v-messages__message error--text">{{errors.first('ProcedimientosArbol')}}</span>
                  </v-flex>
                </template>
                <template v-else>
                  <v-flex md10 sm8 xs12>
                    <v-autocomplete
                      ref="autocompleteProcedimiento"
                      label="Procedimiento"
                      v-model="procedimiento.cup"
                      return-object
                      :items="items"
                      item-value="cup.id"
                      item-text="cup.descripcion"
                      name="Procedimiento"
                      v-validate="'required'"
                      :error-messages="errors.collect('Procedimiento')"
                      :filter="filterProcedimientos"
                      @keyup.enter="submitProcedimiento"
                      @change="() => {
                        procedimiento.rs_cups_id = procedimiento.cup.id
                        procedimiento.tarifa = JSON.parse(JSON.stringify(procedimiento.cup.tarifa))
                      }"
                    >
                      <template slot="item" slot-scope="data">
                        <template>
                          <v-list-tile-content>
                            <v-list-tile-title v-html="data.item.cup.codigo + ' - ' + data.item.cup.descripcion"/>
                            <v-list-tile-sub-title v-html="data.item.cup.cobertura"/>
                          </v-list-tile-content>
                        </template>
                      </template>
                    </v-autocomplete>
                  </v-flex>
                  <v-flex md2 sm4 xs12>
                    <v-text-field
                      key="tarifapro"
                      label="Tarifa"
                      min="0"
                      type="number"
                      v-model.number="procedimiento.tarifa"
                      name="Tarifa"
                      v-validate="'min_value:0|required'"
                      :error-messages="errors.collect('Tarifa')"
                      @keyup.enter="submitProcedimiento"
                    />
                  </v-flex>
                </template>
              </v-layout>
            </v-container>
          </v-card-text>
          <v-divider/>
          <v-card-actions>
            <v-spacer/>
            <v-btn
              :loading="loading"
              :disabled="loading"
              color="blue darken-1"
              class="white--text"
              @click="submitProcedimiento"
            >
              Registrar
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-slide-y-transition>
      <v-slide-y-transition mide="out-in">
        <v-card v-if="!showFormRegistro">
          <v-toolbar dense class="elevation-0">
            <v-toolbar-title v-html="'Procedimientos registrados'"/>
            <small class="mt-2 ml-1"> Vinculación al contrato</small>
            <v-spacer/>
            <v-tooltip top v-if="!showFormRegistro && !value.actaInicio">
              <v-btn
                slot="activator"
                fab
                right
                small
                color="accent"
                @click="showFormRegistro = true"
              >
                <v-icon>add</v-icon>
              </v-btn>
              <span>Agregar procedimientos</span>
            </v-tooltip>
          </v-toolbar>
          <v-container fluid class="ma-0 pa-0">
            <v-layout row wrap>
              <v-flex xs12 sm12 md12>
                <v-text-field
                  class="pa-2"
                  v-model="search"
                  append-icon="search"
                  solo
                  clearable
                  label="Filtrar procedimientos..."
                  type="text"
                  hide-details
                >
                  <v-tooltip
                    slot="prepend"
                    top
                    style="top:-10px"
                  >
                    <v-btn slot="activator" flat icon color="primary" @click="setExpandir">
                      <v-icon>{{expandir ? 'expand_more' : 'expand_less'}}</v-icon>
                    </v-btn>
                    {{expandir ? 'Expandir todos' : 'Contraer todos'}}
                  </v-tooltip>
                </v-text-field>
                <div
                  v-if="!Object.entries(itemsContratados).length"
                  class="title grey--text text-xs-center font-weight-light pa-2"
                >
                  No hay coincidencias de busqueda. <v-icon>sentiment_very_dissatisfied</v-icon>
                </div>
                <v-expansion-panel
                  v-else
                  v-model="panel"
                  expand
                >
                  <v-expansion-panel-content
                    class="v-expansion-header-dark"
                    v-for="(reportes, modulo) in itemsContratados"
                    :key="modulo"
                  >
                    <div slot="header">
                      {{modulo}}
                      <v-chip label small color="primary" text-color="white">
                        {{reportes.length === 1 ? 'Un' : reportes.length}} procedimiento{{reportes.length === 1 ? '' : 's'}}
                      </v-chip>
                    </div>
                    <v-card>
                      <v-data-table
                        :headers="headersContratados"
                        :items="reportes"
                        rows-per-page-text="Registros por página:"
                      >
                        <template slot="items" slot-scope="props">
                          <td class="text-xs-center">{{ props.item.cup.codigo }}</td>
                          <td>{{ props.item.cup.descripcion }}</td>
                          <td class="text-xs-right">{{'$'}}{{ props.item.tarifa_entidad | numberFormat(2)}}</td>
                          <td class="text-xs-right">{{'$'}}{{ props.item.tarifa | numberFormat(2) }}</td>
                          <td class="text-xs-center">
                            <v-tooltip top>
                              <v-btn slot="activator" flat icon color="danger" @click="procedimientoBorrar(props.item)">
                                <v-icon>close</v-icon>
                              </v-btn>
                              Borrar procedimiento
                            </v-tooltip>
                          </td>
                        </template>
                        <v-alert slot="no-results" :value="true" color="error" icon="warning">
                          No hay registros para mostrar{{ search ? ' segun criterio de busqueda : ' + search : '.' }}
                        </v-alert>
                        <template slot="pageText" slot-scope="props">
                          Mostrando registros del {{ props.pageStart }} al {{ props.pageStop }} de {{ props.itemsLength }}
                        </template>
                      </v-data-table>
                    </v-card>
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card>
      </v-slide-y-transition>
    </v-flex>
  </v-layout>
</template>

<script>
  import store from '@/store/complementos/index'
  import Loading from '@/components/general/Loading'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import Treeselect from '@riophae/vue-treeselect'
  import '@riophae/vue-treeselect/dist/vue-treeselect.css'
  export default {
    name: 'RegistroProcedimientos',
    props: ['value'],
    components: {
      Loading,
      Treeselect
    },
    data: () => ({
      tiposTarifa: [
        { id: 'SOAT', text: 'SOAT' },
        { id: 'ISS', text: 'ISS' },
        { id: 'Institucional', text: 'INSTITUCIONAL' }
      ],
      anios: [],
      rs_contrato_id: null,
      tipoTarifa: null,
      anio: null,
      porcentaje: null,
      procedimiento: {
        cup: null,
        rs_cups_id: null,
        tarifa: 0
      },
      filterProcedimientos (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.cup.codigo + ' ' + item.cup.nombre)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      showFormRegistro: false,
      loading: false,
      tree: [],
      items: [],
      itemsContratadosBase: [],
      panel: [],
      search: '',
      headersContratados: [
        {
          text: 'Código',
          align: 'center',
          sortable: true,
          value: 'cup.codigo'
        },
        {
          text: 'Descripción',
          align: 'left',
          sortable: true,
          value: 'cup.descripcion'
        },
        {
          text: 'Tarifa entidad',
          align: 'right',
          sortable: false,
          value: 'tarifa_entidad'
        },
        {
          text: 'Tarifa contratada',
          align: 'right',
          sortable: false,
          value: 'tarifa'
        },
        {
          text: '',
          align: 'center',
          sortable: false,
          value: 'id'
        }
      ],
      expandir: true
    }),
    computed: {
      itemsContratados () {
        if (!this.search) this.search = ''
        return this.itemsContratadosBase.length ? this.itemsContratadosBase.filter(item => (item.grupo_tarifa && item.grupo_tarifa.toLowerCase().search(this.search.toLowerCase()) !== -1) || (item.cup.descripcion && item.cup.descripcion.toLowerCase().search(this.search.toLowerCase()) !== -1) || (item.cup.codigo && item.cup.codigo.toLowerCase().search(this.search.toLowerCase()) !== -1)).reduce((value, key) => {
          (value[key['grupo_tarifa']] = value[key['grupo_tarifa']] || []).push(key)
          return value
        }, {}) : []
      },
      itemsArbol () {
        return (this.anio && this.porcentaje && (this.tipoTarifa === 'Institucional' || !this.tipoTarifa)) ? [] : JSON.parse(JSON.stringify(this.filtro(this.items.filter(x => this.tipoTarifa === 'SOAT' ? x.cup.cm_mansoat_id : x.cup.cm_maniss_id))))
      }
    },
    watch: {
      'itemsContratados' (val, prev) {
        if (Object.entries(prev).length && Object.entries(val).length) {
          setTimeout(() => {
            this.panel = []
            Object.entries(val).forEach(x => { this.panel.push(true) })
          }, 300)
        }
      },
      'panel' (val) {
        this.expandir = !val.find(x => !!x)
      },
      'tipoTarifa' (val, prev) {
        if (prev && val) {
          this.refreshForm()
          this.tipoTarifa = val
        }
      },
      'showFormRegistro' (val) {
        !val && this.refreshForm()
      }
    },
    created () {
      this.reloadAll()
    },
    methods: {
      async reloadAll () {
        let newContratadosBase = await this.getCupsContratados()
        this.itemsContratadosBase = newContratadosBase
        this.anios = await new Promise(resolve => {
          this.axios.get(`salminimos`)
            .then(response => resolve(response.data))
            .catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los salarios mínimos. ', error: e})
            })
        })
        //this.items = await this.getCupsEntidad()
      },
      setExpandir () {
        this.panel = []
        if (this.expandir) Object.entries(this.itemsContratados).forEach(() => { this.panel.push(true) })
        this.expandir = !this.expandir
      },
      getCupsEntidad () {
        return new Promise(resolve => {
          this.axios.get(`entidades/${this.value.entidad.id}/cups`)
            .then(response => {
              response.data.data.map(x => { this.$set(x, 'label', `${x.cup.codigo} - ${x.cup.descripcion}`) })
              resolve(response.data.data)
            })
            .catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los procedimientos de la entidad. ', error: e})
            })
        })
      },
      async getCupsContratados () {
        return new Promise(resolve => {
          this.loading = true
          this.axios.get(`contratos/${this.value.id}/cups`)
            .then(response => {
              this.loading = false
              resolve(response.data.data)
            })
            .catch(e => {
              this.loading = false
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los procedimientos contratados. ', error: e})
            })
        })
      },
      refreshForm () {
        this.tipoTarifa = null
        this.anio = null
        this.porcentaje = null
        this.tree = []
        this.procedimiento = {
          cup: null,
          rs_cups_id: null,
          tarifa: null
        }
        this.$validator.reset()
      },
      reduce (array, type) {
        let datos = array.reduce((value, key) => {
          (value[type === 'grupos' ? key['cup']['parent']['parent']['parent']['nombre'] : type === 'subgrupos' ? key['cup']['parent']['parent']['nombre'] : key['cup']['parent']['nombre']] = value[type === 'grupos' ? key['cup']['parent']['parent']['parent']['nombre'] : type === 'subgrupos' ? key['cup']['parent']['parent']['nombre'] : key['cup']['parent']['nombre']] || []).push(key)
          return value
        }, {})
        return datos
      },
      filtro (rows) {
        let array = []
        Object.entries(this.reduce(rows, 'grupos')).forEach((x, i) => {
          let datos = {
            id: null,
            label: null,
            children: []
          }
          datos.id = `g${i}`
          datos.label = x[0]
          Object.entries(this.reduce(x[1], 'subgrupos')).forEach((y, index) => {
            datos.children.push({
              id: `g${i}sg${index}`,
              label: y[0],
              children: Object.entries(this.reduce(y[1], 'categorias')).map((z, key) => {
                return {
                  id: `g${i}sg${index}c${key}`,
                  label: z[0],
                  children: z[1]
                }
              })
            })
          })
          array.push(datos)
        })
        return array
      },
      procedimientoBorrar (item) {
        this.loading = true
        this.showFormRegistro = false
        this.axios.post(`contratos/remove-cup/${item.id}`)
          .then(() => {
            this.$store.commit(SNACK_SHOW, {msg: 'El procedimiento se borro correctamente.', color: 'success'})
            this.itemsContratadosBase.splice(this.itemsContratadosBase.findIndex(x => x.id === item.id), 1)
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al borrar el procedimiento. ', error: e})
          })
      },
      async submitProcedimiento () {
        if (await this.$validator.validateAll()) {
          this.loading = false
          let data = {}
          if (!(this.tipoTarifa === 'Institucional')) {
            data = {
              rs_salminimo_id: this.anio,
              porcentaje: this.porcentaje,
              tipo_manual: this.tipoTarifa,
              tarifa: null,
              cups: this.tree
            }
          } else {
            data = {
              rs_salminimo_id: null,
              porcentaje: null,
              tipo_manual: this.tipoTarifa,
              tarifa: this.procedimiento.tarifa,
              cups: [this.procedimiento.rs_cups_id]
            }
          }
          let response = await new Promise(resolve => {
            this.axios.post(`contratos/${this.value.id}/add-cup-masivo`, data)
              .then(() => {
                this.$store.commit(SNACK_SHOW, {msg: (data.cups.length > 1 ? 'Los procedimientos se registraron ' : 'El procedimiento se registro ') + 'correctamente.', color: 'success'})
                if (this.$refs.autocompleteProcedimiento) {
                  this.procedimiento = {
                    cup: null,
                    rs_cups_id: null,
                    tarifa: null
                  }
                  this.$validator.reset()
                  this.$refs.autocompleteProcedimiento.focus()
                } else {
                  this.showFormRegistro = false
                }
                resolve(true)
              })
              .catch(e => {
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al registrar el procedimiento. ', error: e})
                resolve(false)
              })
          })
          this.loading = false
          if (response) {
            let newContratadosBase = await this.getCupsContratados()
            this.itemsContratadosBase = newContratadosBase
          }
        }
      }
    }
  }
</script>

<style scoped>

</style>
